<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class HealthCheckController extends Controller
{
    /**
     * Application health check endpoint
     */
    public function check(): JsonResponse
    {
        $health = [
            'status' => 'healthy',
            'timestamp' => now()->toIso8601String(),
            'services' => [
                'database' => $this->checkDatabase(),
                'cache' => $this->checkCache(),
            ],
            'application' => [
                'name' => config('app.name'),
                'environment' => config('app.env'),
                'debug' => config('app.debug'),
            ],
        ];

        $overallStatus = $this->determineOverallStatus($health['services']);
        $health['status'] = $overallStatus;

        $statusCode = $overallStatus === 'healthy' ? 200 : 503;

        return response()->json($health, $statusCode);
    }

    /**
     * Check database connectivity
     */
    private function checkDatabase(): array
    {
        try {
            \DB::connection()->getPdo();
            return [
                'status' => 'healthy',
                'message' => 'Database connection successful',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => 'Database connection failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check cache system
     */
    private function checkCache(): array
    {
        try {
            \Cache::put('health_check', 'test', 10);
            $value = \Cache::get('health_check');
            
            return [
                'status' => $value === 'test' ? 'healthy' : 'unhealthy',
                'message' => $value === 'test' ? 'Cache working' : 'Cache not working',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => 'Cache failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Determine overall status based on service health
     */
    private function determineOverallStatus(array $services): string
    {
        foreach ($services as $service) {
            if ($service['status'] !== 'healthy') {
                return 'unhealthy';
            }
        }
        return 'healthy';
    }
}
