<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Models\ArcGISConnection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Services\ArcGIS\Survey123Service;
use Throwable;

class DashboardController extends Controller
{
    protected Survey123Service $service;

    public function __construct(Survey123Service $service)
    {
        $this->service = $service;
    }
    public function index()
    {
        return view('dashboard.pages.dashboard');
    }

    public function map()
    {
        return view('dashboard.pages.map');
    }
    public function login()
    {
        $state = Str::random(40);

        session(['arcgis_oauth_state' => $state]);

        $query = http_build_query([
            'client_id' => config('services.arcgis.client_id'),
            'response_type' => 'code',
            'redirect_uri' => config('services.arcgis.redirect_uri'),
            'state' => $state,
        ]);

        return redirect(
            config('services.arcgis.portal') .
                '/sharing/rest/oauth2/authorize?' . $query
        );
    }


    // callback insert  koneksi
    public function callback(Request $request)
    {
        $code = $request->query('code');

        // Periksa authorization code
        if (!$code) {
            return response()->json([
                'status' => false,
                'message' => 'Authorization code tidak ditemukan.',
                'error' => $request->query('error'),
                'error_description' => $request->query('error_description'),
            ], 400);
        }

        // Tukar authorization code dengan access token
        $response = Http::asForm()->post(
            config('services.arcgis.portal') . '/sharing/rest/oauth2/token',
            [
                'client_id' => config('services.arcgis.client_id'),
                'client_secret' => config('services.arcgis.client_secret'),
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => config('services.arcgis.redirect_uri'),
                'f' => 'json',
            ]
        );

        $data = $response->json();

        // Periksa respons ArcGIS
        if (!$response->successful() || empty($data['access_token'])) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mendapatkan access token.',
                'response' => $data,
            ], 400);
        }

        // Simpan token menggunakan Eloquent Model
        $connection = ArcGISConnection::updateOrCreate(
            ['id' => 1],
            [
                'username' => $data['username'] ?? null,

                'access_token' => Crypt::encryptString(
                    $data['access_token']
                ),

                'refresh_token' => !empty($data['refresh_token'])
                    ? Crypt::encryptString($data['refresh_token'])
                    : null,

                'expires_at' => now()->addSeconds(
                    $data['expires_in'] ?? 1800
                ),
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Koneksi ArcGIS berhasil disimpan.',
            'username' => $connection->username,
            'database_id' => $connection->id,
        ]);
    }

    private function getArcGISAccessToken()
    {
        $token = session('arcgis_access_token');

        $expiresAt = session('arcgis_expires_at');

        // Token masih berlaku
        if (
            $token &&
            $expiresAt &&
            now()->timestamp < ($expiresAt - 60)
        ) {
            return $token;
        }

        $refreshToken = session('arcgis_refresh_token');

        if (!$refreshToken) {
            return null;
        }

        // Refresh Access Token
        $response = Http::asForm()->post(
            config('services.arcgis.portal') .
                '/sharing/rest/oauth2/token',
            [
                'client_id' => config('services.arcgis.client_id'),
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'f' => 'json',
            ]
        );

        $data = $response->json();

        if (
            !$response->successful() ||
            isset($data['error']) ||
            empty($data['access_token'])
        ) {
            session()->forget([
                'arcgis_access_token',
                'arcgis_refresh_token',
                'arcgis_expires_at',
            ]);

            return null;
        }

        session([
            'arcgis_access_token' => $data['access_token'],

            'arcgis_expires_at' => now()
                ->addSeconds($data['expires_in'] ?? 1800)
                ->timestamp,
        ]);

        return $data['access_token'];
    }
    public function profile()
    {
        $token = $this->getArcGISAccessToken();

        if (!$token) {
            return redirect('/arcgis/login');
        }

        $response = Http::get(
            config('services.arcgis.portal') .
                '/sharing/rest/community/self',
            [
                'f' => 'json',
                'token' => $token,
            ]
        );

        return response()->json($response->json());
    }
    public function layers()
    {
        $token = $this->getArcGISAccessToken();


        if (!$token) {
            return response()->json([
                'message' => 'Autentikasi ArcGIS diperlukan.',
            ], 401);
        }

        $url = config('services.arcgis.feature_layer_url');

        $response = Http::get($url, [
            'f' => 'json',
            'token' => $token,
        ]);

        return response()->json(
            $response->json(),
            $response->status()
        );
    }
    public function features()
    {
        $token = $this->getSharedArcGISAccessToken();

        if (!$token) {
            return response()->json([
                'message' => 'Koneksi ArcGIS belum tersedia atau perlu otorisasi ulang administrator.'
            ], 401);
        }

        $response = Http::timeout(30)->get(
            config('services.arcgis.feature_layer_url') . '/0/query',
            [
                'where' => '1=1',
                'outFields' => '*',
                'returnGeometry' => 'true',
                'outSR' => 4326,
                'f' => 'json',
                'token' => $token,
            ]
        );

        $data = $response->json();

        if (!$response->successful() || isset($data['error'])) {
            return response()->json([
                'message' => 'Gagal mengambil data ArcGIS.',
                'detail' => $data['error'] ?? $response->body(),
            ], 502);
        }

        return response()->json($data);
    }
    private function getSharedArcGISAccessToken()
    {
        $connection = ArcgisConnection::where(
            'username',
            'bioramagunanusantara'
        )->first();

        if (!$connection) {
            logger()->error('Koneksi ArcGIS tidak ditemukan');

            return null;
        }

        try {

            // Access token masih berlaku
            if (
                $connection->access_token &&
                $connection->expires_at &&
                now()->lt(
                    $connection->expires_at->copy()->subMinutes(2)
                )
            ) {
                return Crypt::decryptString(
                    $connection->access_token
                );
            }

            // Pastikan refresh token tersedia
            if (!$connection->refresh_token) {
                logger()->error('Refresh token ArcGIS tidak tersedia');

                return null;
            }

            return Cache::lock(
                'arcgis-token-refresh',
                15
            )->block(10, function () {

                // Ambil koneksi YANG SAMA lagi setelah lock
                $connection = ArcgisConnection::where(
                    'username',
                    'bioramagunanusantara'
                )->first();

                if (!$connection) {
                    return null;
                }

                // Cek lagi, mungkin request lain sudah refresh token
                if (
                    $connection->access_token &&
                    $connection->expires_at &&
                    now()->lt(
                        $connection->expires_at->copy()->subMinutes(2)
                    )
                ) {
                    return Crypt::decryptString(
                        $connection->access_token
                    );
                }

                if (!$connection->refresh_token) {
                    return null;
                }

                $refreshToken = Crypt::decryptString(
                    $connection->refresh_token
                );

                $response = Http::asForm()
                    ->timeout(20)
                    ->post(
                        config('services.arcgis.portal') .
                            '/sharing/rest/oauth2/token',
                        [
                            'client_id' =>
                            config('services.arcgis.client_id'),

                            'client_secret' =>
                            config('services.arcgis.client_secret'),

                            'grant_type' => 'refresh_token',

                            'refresh_token' => $refreshToken,

                            'f' => 'json',
                        ]
                    );

                $data = $response->json();

                if (
                    !$response->successful() ||
                    isset($data['error']) ||
                    empty($data['access_token'])
                ) {
                    logger()->error(
                        'ArcGIS token refresh gagal',
                        [
                            'response' => $data,
                        ]
                    );

                    return null;
                }

                $connection->update([
                    'access_token' => Crypt::encryptString(
                        $data['access_token']
                    ),

                    'refresh_token' => !empty($data['refresh_token'])
                        ? Crypt::encryptString(
                            $data['refresh_token']
                        )
                        : $connection->refresh_token,

                    'expires_at' => now()->addSeconds(
                        $data['expires_in'] ?? 1800
                    ),
                ]);

                return $data['access_token'];
            });
        } catch (\Throwable $e) {

            logger()->error('ArcGIS connection error', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }


    public function surveyPoints()
    {
        $token = $this->getSharedArcGISAccessToken();

        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Koneksi ArcGIS belum tersedia atau perlu otorisasi ulang administrator.'
            ], 401);
        }

        $url = config('services.arcgis.feature_layer_url') . '/0/query';

        $response = Http::get($url, [
            'where' => '1=1',
            'outFields' => '*',
            'returnGeometry' => 'true',
            'outSR' => 4326,
            'f' => 'json',
            'token' => $token,
        ]);

        return response()->json(
            $response->json(),
            $response->status()
        );
    }

    public function surveyAttachments($objectId)
    {
        $token = $this->getSharedArcGISAccessToken();

        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Token ArcGIS tidak tersedia.'
            ], 401);
        }

        $url = rtrim(config('services.arcgis.feature_layer_url'), '/')
            . '/0/' . $objectId . '/attachments';

        $response = Http::timeout(20)->get($url, [
            'f' => 'json',
            'token' => $token
        ]);

        if (!$response->successful()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil attachment ArcGIS.'
            ], $response->status());
        }

        $data = $response->json();

        $attachments = collect($data['attachmentInfos'] ?? [])
            ->map(function ($attachment) use ($objectId) {
                return [
                    'id' => $attachment['id'],
                    'name' => $attachment['name'] ?? 'Foto Survey',
                    'contentType' => $attachment['contentType'] ?? null,
                    'size' => $attachment['size'] ?? null,
                    'url' => url(
                        "/arcgis/features/survey-points/{$objectId}/attachments/{$attachment['id']}/image"
                    )
                ];
            })
            ->values();

        return response()->json([
            'status' => true,
            'objectid' => $objectId,
            'total' => $attachments->count(),
            'attachments' => $attachments
        ]);
    }

    public function surveyAttachmentImage($objectId, $attachmentId)
    {
        $token = $this->getSharedArcGISAccessToken();

        if (!$token) {
            return response('Token ArcGIS tidak tersedia.', 401);
        }

        $url = rtrim(config('services.arcgis.feature_layer_url'), '/')
            . '/0/' . $objectId
            . '/attachments/' . $attachmentId;

        $response = Http::timeout(30)->get($url, [
            'token' => $token
        ]);

        if (!$response->successful()) {
            return response('Foto tidak dapat diambil.', $response->status());
        }

        $contentType = $response->header('Content-Type')
            ?? 'application/octet-stream';

        return response($response->body(), 200)
            ->header('Content-Type', $contentType)
            ->header('Cache-Control', 'private, max-age=300');
    }



    // survey
    public function indexsurvey()
    {
        try {

            $result = app(Survey123Service::class)->getSurveys();

            $surveys = $result['features'] ?? [];

            return view('dashboard.pages.survey.index', compact('surveys'));
        } catch (Throwable $e) {

            Log::error('Gagal mengambil data Survey123', [
                'message' => $e->getMessage()
            ]);

            return view('dashboard.pages.survey.index', [
                'surveys' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    public function haltambahsurvey()
    {
        return view('dashboard.pages.survey.tambah');
    }

    public function storesurvey(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'jenis_bangunan' => 'required|string',
            'tipe_bangunan' => 'required|string|max:255',
            'fungsi_bangunan' => 'required|string',
            'kondisi_bangunan' => 'required|string',
            'tanggal_survei' => 'required|date',

            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {

            // 1. Siapkan atribut
            $attributes = [
                'jenis_bangunan' => $request->jenis_bangunan,
                'tipe_bangunan' => $request->tipe_bangunan,
                'fungsi_bangunan' => $request->fungsi_bangunan,
                'kondisi_bangunan' => $request->kondisi_bangunan,

                'tanggal_survei' => Carbon::parse(
                    $request->tanggal_survei
                )->startOfDay()->timestamp * 1000,
            ];

            // 2. Siapkan geometry Point
            $geometry = [
                'x' => (float) $request->longitude,
                'y' => (float) $request->latitude,
                'spatialReference' => [
                    'wkid' => 4326,
                ],
            ];

            // 3. Submit langsung ke ArcGIS
            $result = $this->service->createSurvey(
                $attributes,
                $geometry
            );

            // 4. Redirect setelah berhasil
            return back()->with(
                'success',
                'Data survei berhasil dikirim ke ArcGIS. Object ID: '
                    . $result['objectId']
            );
        } catch (Throwable $e) {

            Log::error('ArcGIS Survey Submit Error', [
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Gagal mengirim survei ke ArcGIS: '
                        . $e->getMessage()
                );
        }
    }
}
