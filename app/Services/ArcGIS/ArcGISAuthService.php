<?php

namespace App\Services\ArcGIS;

use App\Models\ArcGISConnection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ArcGISAuthService
{
    public function getAccessToken(): string
    {
        $connection = ArcGISConnection::latest('id')->first();

        if (!$connection || !$connection->access_token) {
            throw new RuntimeException(
                'Koneksi ArcGIS belum tersedia.'
            );
        }

        // Refresh jika token sudah atau hampir kedaluwarsa
        if (
            !$connection->expires_at ||
            now()->greaterThanOrEqualTo(
                $connection->expires_at->copy()->subMinutes(2)
            )
        ) {
            return $this->refreshAccessToken($connection);
        }

        return Crypt::decryptString($connection->access_token);
    }

    /**
     * Refresh access token menggunakan refresh token ArcGIS
     */
    private function refreshAccessToken(
        ArcGISConnection $connection
    ): string {

        if (!$connection->refresh_token) {
            throw new RuntimeException(
                'Refresh token tidak tersedia. Silakan login ulang ke ArcGIS.'
            );
        }

        $refreshToken = Crypt::decryptString(
            $connection->refresh_token
        );

        $response = Http::asForm()
            ->timeout(30)
            ->post(
                rtrim(config('services.arcgis.portal'), '/')
                    . '/sharing/rest/oauth2/token',
                [
                    'client_id' => config('services.arcgis.client_id'),
                    'client_secret' => config('services.arcgis.client_secret'),
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refreshToken,
                    'f' => 'json',
                ]
            );

        $data = $response->json();

        if (
            !$response->successful() ||
            empty($data['access_token'])
        ) {
            throw new RuntimeException(
                'Gagal refresh token ArcGIS: ' .
                    ($data['error_description']
                        ?? $data['error']['message']
                        ?? $data['error']
                        ?? 'Respons ArcGIS tidak valid.')
            );
        }

        // Simpan access token baru
        $connection->access_token = Crypt::encryptString(
            $data['access_token']
        );

        // Perbarui refresh token jika ArcGIS mengembalikan token baru
        if (!empty($data['refresh_token'])) {
            $connection->refresh_token = Crypt::encryptString(
                $data['refresh_token']
            );
        }

        $connection->expires_at = now()->addSeconds(
            $data['expires_in'] ?? 1800
        );

        $connection->save();

        return $data['access_token'];
    }
}
