<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\InMemory;

class MetricsController extends Controller
{
    private CollectorRegistry $registry;

    public function __construct()
    {
        // Use InMemory storage (for production, consider Redis or APCu)
        $this->registry = new CollectorRegistry(new InMemory());
    }

    /**
     * Export metrics in Prometheus format
     */
    public function export(): Response
    {
        // Register custom metrics
        $this->registerApplicationMetrics();

        // Render metrics
        $renderer = new RenderTextFormat();
        $result = $renderer->render($this->registry->getMetricFamilySamples());

        return response($result, 200, [
            'Content-Type' => RenderTextFormat::MIME_TYPE
        ]);
    }

    /**
     * Register application-specific metrics
     */
    private function registerApplicationMetrics(): void
    {
        // Counter: Total HTTP requests
        $counter = $this->registry->getOrRegisterCounter(
            'bookshare',
            'http_requests_total',
            'Total number of HTTP requests',
            ['method', 'endpoint', 'status']
        );

        // Gauge: Active users
        $gauge = $this->registry->getOrRegisterGauge(
            'bookshare',
            'active_users',
            'Number of active users'
        );

        // Histogram: Request duration
        $histogram = $this->registry->getOrRegisterHistogram(
            'bookshare',
            'http_request_duration_seconds',
            'HTTP request duration in seconds',
            ['method', 'endpoint'],
            [0.1, 0.5, 1, 2, 5]
        );

        // Example: Set active users count
        $activeUsers = \App\Models\User::where('updated_at', '>=', now()->subMinutes(15))->count();
        $gauge->set($activeUsers);

        // You can track more metrics:
        $this->trackDatabaseMetrics();
        $this->trackApplicationMetrics();
    }

    /**
     * Track database-related metrics
     */
    private function trackDatabaseMetrics(): void
    {
        $gauge = $this->registry->getOrRegisterGauge(
            'bookshare',
            'total_users',
            'Total number of users'
        );
        $gauge->set(\App\Models\User::count());

        $gauge = $this->registry->getOrRegisterGauge(
            'bookshare',
            'total_books',
            'Total number of books'
        );
        $gauge->set(\App\Models\Book::count());

        $gauge = $this->registry->getOrRegisterGauge(
            'bookshare',
            'total_reviews',
            'Total number of reviews'
        );
        $gauge->set(\App\Models\Review::count());

        $gauge = $this->registry->getOrRegisterGauge(
            'bookshare',
            'total_groups',
            'Total number of groups'
        );
        $gauge->set(\App\Models\Group::count());
    }

    /**
     * Track application-specific metrics
     */
    private function trackApplicationMetrics(): void
    {
        // Track marketplace metrics
        $gauge = $this->registry->getOrRegisterGauge(
            'bookshare',
            'marketplace_books',
            'Number of books in marketplace'
        );
        $gauge->set(\App\Models\MarketBook::where('is_available', true)->count());

        // Track transactions
        $gauge = $this->registry->getOrRegisterGauge(
            'bookshare',
            'pending_transactions',
            'Number of pending transactions'
        );
        $gauge->set(\App\Models\Transaction::where('status', 'pending')->count());

        // Track new reviews today
        $gauge = $this->registry->getOrRegisterGauge(
            'bookshare',
            'reviews_today',
            'Number of reviews created today'
        );
        $gauge->set(\App\Models\Review::whereDate('created_at', today())->count());
    }
}
