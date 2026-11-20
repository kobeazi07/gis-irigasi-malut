<?php

namespace App\Services\ArcGIS;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class Survey123Service
{
    protected string $featureLayerUrl;
    protected string $layerId;

    public function __construct(
        private ArcGISAuthService $auth
    ) {
        $this->featureLayerUrl = rtrim(
            config('services.arcgis.feature_layer_url'),
            '/'
        );

        $this->layerId = (string) config(
            'services.arcgis.layer_id',
            0
        );
    }

    /**
     * URL Feature Layer
     */
    private function layerUrl(): string
    {
        return $this->featureLayerUrl . '/' . $this->layerId;
    }

    /**
     * Parameter request ArcGIS dengan access token
     */
    private function parameters(array $params = []): array
    {
        $params['token'] = $this->auth->getAccessToken();

        return $params;
    }

    /**
     * GET DATA SURVEY
     */
    public function getSurveys(array $filters = []): array
    {
        $params = array_merge([
            'f' => 'json',
            'where' => '1=1',
            'outFields' => '*',
            'returnGeometry' => 'true',
            'resultRecordCount' => 100,
            'orderByFields' => 'OBJECTID DESC',
        ], $filters);

        $response = Http::timeout(30)->get(
            $this->layerUrl() . '/query',
            $this->parameters($params)
        );

        if (!$response->successful()) {
            throw new RuntimeException(
                'Gagal mengambil data ArcGIS.'
            );
        }

        $data = $response->json();

        if (isset($data['error'])) {
            throw new RuntimeException(
                $data['error']['message'] ?? 'ArcGIS query error.'
            );
        }

        return $data;
    }

    /**
     * POST DATA SURVEY
     */
    public function createSurvey(
        array $attributes,
        array $geometry
    ): array {
        $feature = [
            'attributes' => $attributes,
            'geometry' => $geometry,
        ];

        $params = $this->parameters([
            'f' => 'json',
            'features' => json_encode(
                [$feature],
                JSON_UNESCAPED_UNICODE
            ),
        ]);

        $response = Http::asForm()->timeout(30)->post(
            $this->layerUrl() . '/addFeatures',
            $params
        );

        if (!$response->successful()) {
            throw new RuntimeException(
                'Gagal mengirim data ke ArcGIS.'
            );
        }

        $data = $response->json();

        if (isset($data['error'])) {
            throw new RuntimeException(
                $data['error']['message'] ?? 'ArcGIS addFeatures error.'
            );
        }

        $result = $data['addResults'][0] ?? null;

        if (!$result || !($result['success'] ?? false)) {
            throw new RuntimeException(
                $result['error']['description']
                    ?? 'Data gagal disimpan.'
            );
        }

        return $result;
    }
}
