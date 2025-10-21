<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Prometheus\CollectorRegistry;
use Prometheus\Storage\InMemory;
use Symfony\Component\HttpFoundation\Response;

class PrometheusMetricsMiddleware
{
    private CollectorRegistry $registry;

    public function __construct()
    {
        $this->registry = new CollectorRegistry(new InMemory());
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        $response = $next($request);
        
        $duration = microtime(true) - $startTime;
        
        // Track request metrics
        $this->trackRequest($request, $response, $duration);
        
        return $response;
    }

    /**
     * Track request metrics
     */
    private function trackRequest(Request $request, Response $response, float $duration): void
    {
        try {
            // Counter: HTTP requests
            $counter = $this->registry->getOrRegisterCounter(
                'bookshare',
                'http_requests_total',
                'Total HTTP requests',
                ['method', 'endpoint', 'status']
            );

            $endpoint = $this->getEndpoint($request);
            
            $counter->inc([
                'method' => $request->method(),
                'endpoint' => $endpoint,
                'status' => $response->getStatusCode()
            ]);

            // Histogram: Request duration
            $histogram = $this->registry->getOrRegisterHistogram(
                'bookshare',
                'http_request_duration_seconds',
                'HTTP request duration',
                ['method', 'endpoint'],
                [0.1, 0.5, 1, 2, 5]
            );

            $histogram->observe($duration, [
                'method' => $request->method(),
                'endpoint' => $endpoint
            ]);
        } catch (\Exception $e) {
            // Log error but don't break the application
            \Log::error('Prometheus metrics error: ' . $e->getMessage());
        }
    }

    /**
     * Get simplified endpoint name
     */
    private function getEndpoint(Request $request): string
    {
        $route = $request->route();
        
        if ($route) {
            return $route->getName() ?? $route->uri();
        }
        
        return $request->path();
    }
}
