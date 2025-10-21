# Monitoring Setup - Grafana & Prometheus

This project includes a complete monitoring stack with **Prometheus** for metrics collection and **Grafana** for visualization.

## 🚀 Quick Start

### 1. Start the Monitoring Stack

```bash
docker-compose up -d prometheus grafana
```

### 2. Access the Services

- **Grafana**: http://localhost:3000
  - Username: `admin`
  - Password: `admin123`

- **Prometheus**: http://localhost:9090

- **Laravel Metrics Endpoint**: http://localhost:8000/metrics

## 📊 What's Being Monitored

### Application Metrics
- Total number of users
- Total number of books
- Total number of reviews
- Total number of groups
- Active users (last 15 minutes)
- Marketplace books available
- Pending transactions
- Reviews created today

### HTTP Metrics
- Request count by method, endpoint, and status
- Request duration (histogram)
- Request rate

## 🔧 Configuration

### Prometheus Configuration
Location: `monitoring/prometheus/prometheus.yml`

The Prometheus configuration scrapes metrics from:
- Prometheus itself (self-monitoring)
- Laravel application at `host.docker.internal:8000/metrics`

**Note for Linux users**: Replace `host.docker.internal` with your host machine IP or use `host.docker.internal` if using Docker Desktop.

### Grafana Configuration
Location: `monitoring/grafana/provisioning/`

- **Datasources**: Automatically configured Prometheus datasource
- **Dashboards**: Place custom dashboards in `monitoring/grafana/provisioning/dashboards/json/`

## 📈 Creating Dashboards in Grafana

1. Log in to Grafana at http://localhost:3000
2. Go to **Dashboards** → **New** → **New Dashboard**
3. Add panels with these sample queries:

### Sample Queries

**Total Users**
```promql
bookshare_total_users
```

**Active Users**
```promql
bookshare_active_users
```

**HTTP Requests Rate (per minute)**
```promql
rate(bookshare_http_requests_total[1m])
```

**HTTP Request Duration (95th percentile)**
```promql
histogram_quantile(0.95, rate(bookshare_http_request_duration_seconds_bucket[5m]))
```

**Total Books**
```promql
bookshare_total_books
```

**Reviews Created Today**
```promql
bookshare_reviews_today
```

**Pending Transactions**
```promql
bookshare_pending_transactions
```

## 🔐 Security Considerations

### Production Deployment

1. **Change Default Credentials**
   ```yaml
   # In docker-compose.yml
   environment:
     - GF_SECURITY_ADMIN_USER=your_username
     - GF_SECURITY_ADMIN_PASSWORD=your_secure_password
   ```

2. **Secure Metrics Endpoint**
   Add authentication to the `/metrics` route:
   ```php
   // In routes/web.php
   Route::get('/metrics', [MetricsController::class, 'export'])
       ->middleware('auth')
       ->name('metrics');
   ```

3. **Use HTTPS**
   Configure reverse proxy (nginx/Traefik) with SSL certificates

4. **Firewall Rules**
   Restrict access to Prometheus (9090) and Grafana (3000) ports

## 📦 Storage Backend

Currently using **InMemory** storage for Prometheus metrics. For production:

### Option 1: Use Redis (Recommended)

1. Install Redis adapter:
   ```bash
   composer require promphp/prometheus_client_php_redis
   ```

2. Update `MetricsController.php`:
   ```php
   use Prometheus\Storage\Redis;
   
   public function __construct()
   {
       Redis::setDefaultOptions([
           'host' => env('REDIS_HOST', '127.0.0.1'),
           'port' => env('REDIS_PORT', 6379),
           'password' => env('REDIS_PASSWORD', null),
       ]);
       $this->registry = new CollectorRegistry(new Redis());
   }
   ```

### Option 2: Use APCu

1. Install APCu adapter:
   ```bash
   composer require promphp/prometheus_client_php_apcu
   ```

2. Update `MetricsController.php`:
   ```php
   use Prometheus\Storage\APC;
   
   $this->registry = new CollectorRegistry(new APC());
   ```

## 🎯 Adding Custom Metrics

Add custom metrics in `MetricsController.php`:

```php
// Counter Example
$counter = $this->registry->getOrRegisterCounter(
    'bookshare',
    'custom_event_count',
    'Description of the metric',
    ['label1', 'label2']
);
$counter->inc(['value1', 'value2']);

// Gauge Example
$gauge = $this->registry->getOrRegisterGauge(
    'bookshare',
    'current_value',
    'Current value of something'
);
$gauge->set(100);

// Histogram Example
$histogram = $this->registry->getOrRegisterHistogram(
    'bookshare',
    'operation_duration',
    'Operation duration in seconds',
    ['operation'],
    [0.1, 0.5, 1, 2, 5, 10]
);
$histogram->observe($duration, ['operation_name']);
```

## 🛠️ Troubleshooting

### Metrics endpoint returns empty
- Check if Laravel app is running: `php artisan serve`
- Verify route is registered: `php artisan route:list | grep metrics`

### Prometheus can't scrape Laravel metrics
- Ensure Laravel is accessible from Docker container
- On Windows: Use `host.docker.internal`
- On Linux: Use host IP or add Laravel to docker-compose network

### Grafana shows "No data"
- Check Prometheus is scraping successfully: http://localhost:9090/targets
- Verify datasource connection in Grafana settings
- Check if metrics are being generated: http://localhost:8000/metrics

## 🔄 Stopping the Services

```bash
# Stop all services
docker-compose down

# Stop and remove volumes (data will be lost)
docker-compose down -v
```

## 📚 Additional Resources

- [Prometheus Documentation](https://prometheus.io/docs/)
- [Grafana Documentation](https://grafana.com/docs/)
- [PromQL Basics](https://prometheus.io/docs/prometheus/latest/querying/basics/)
- [Grafana Dashboard Examples](https://grafana.com/grafana/dashboards/)
