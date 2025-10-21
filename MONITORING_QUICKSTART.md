# Quick Setup Guide for Grafana & Prometheus

## ✅ What Has Been Set Up

1. **Prometheus** - Metrics collection system
2. **Grafana** - Metrics visualization dashboard
3. **Laravel Metrics Controller** - Exports application metrics
4. **Docker Compose** - Container orchestration
5. **Sample Dashboard** - Pre-configured BookShare metrics dashboard

## 🚀 How to Use

### Step 1: Start the Monitoring Stack

```powershell
# Start Prometheus and Grafana
docker-compose up -d prometheus grafana

# Verify containers are running
docker ps
```

You should see:
- `prometheus` container on port 9090
- `grafana` container on port 3000

### Step 2: Start Your Laravel Application

```powershell
# In a new terminal
php artisan serve
```

Your app should be running on http://localhost:8000

### Step 3: Test the Metrics Endpoint

Visit http://localhost:8000/metrics in your browser or:

```powershell
curl http://localhost:8000/metrics
```

You should see Prometheus-formatted metrics like:
```
# HELP bookshare_total_users Total number of users
# TYPE bookshare_total_users gauge
bookshare_total_users 23
# HELP bookshare_total_books Total number of books
# TYPE bookshare_total_books gauge
bookshare_total_books 150
```

### Step 4: Access Grafana

1. Open http://localhost:3000
2. Login with:
   - **Username**: `admin`
   - **Password**: `admin123`
3. Go to **Dashboards** → You should see "BookShare Application Metrics"

### Step 5: Access Prometheus

1. Open http://localhost:9090
2. Go to **Status** → **Targets**
3. You should see the `laravel-app` target
   - If it's UP (green), metrics are being collected ✅
   - If it's DOWN (red), check your Laravel server is running

## 📊 What Metrics Are Available

### Application Metrics
- `bookshare_total_users` - Total registered users
- `bookshare_total_books` - Total books in the system
- `bookshare_total_reviews` - Total reviews written
- `bookshare_total_groups` - Total reading groups
- `bookshare_active_users` - Users active in last 15 minutes
- `bookshare_marketplace_books` - Books available in marketplace
- `bookshare_pending_transactions` - Transactions awaiting completion
- `bookshare_reviews_today` - Reviews created today

### HTTP Metrics
- `bookshare_http_requests_total` - Total HTTP requests (by method, endpoint, status)
- `bookshare_http_request_duration_seconds` - Request duration histogram

## 🔍 Sample Queries in Prometheus

Try these queries in Prometheus (http://localhost:9090):

1. **Total Users**: 
   ```
   bookshare_total_users
   ```

2. **Request Rate (per second)**:
   ```
   rate(bookshare_http_requests_total[1m])
   ```

3. **95th Percentile Response Time**:
   ```
   histogram_quantile(0.95, rate(bookshare_http_request_duration_seconds_bucket[5m]))
   ```

4. **Error Rate**:
   ```
   rate(bookshare_http_requests_total{status=~"5.."}[5m])
   ```

## 🛑 Stopping the Services

```powershell
# Stop all monitoring services
docker-compose stop prometheus grafana

# Or stop and remove containers
docker-compose down
```

## 🔧 Troubleshooting

### Problem: "No data" in Grafana

**Solution:**
1. Check Laravel is running: `php artisan serve`
2. Check metrics endpoint: http://localhost:8000/metrics
3. Check Prometheus targets: http://localhost:9090/targets
4. Ensure `laravel-app` target is UP

### Problem: Prometheus shows "Context deadline exceeded"

**Solution:**
- On Windows with Docker Desktop: Should work with `host.docker.internal`
- If not working, find your machine's IP:
  ```powershell
  ipconfig
  ```
  Then update `monitoring/prometheus/prometheus.yml`:
  ```yaml
  - targets: ['YOUR_IP:8000']
  ```

### Problem: Permission denied on Linux

**Solution:**
```bash
sudo chown -R $USER:$USER monitoring/
```

## 🎯 Next Steps

1. **Add More Metrics**: Edit `app/Http/Controllers/MetricsController.php`
2. **Create Custom Dashboards**: In Grafana, create new dashboards with your own queries
3. **Set Up Alerts**: Configure Grafana alerts for critical metrics
4. **Add Middleware**: Register `PrometheusMetricsMiddleware` to track all HTTP requests automatically

### To Add Middleware (Optional):

In `app/Http/Kernel.php`, add to the `$middleware` array:
```php
protected $middleware = [
    // ... other middleware
    \App\Http\Middleware\PrometheusMetricsMiddleware::class,
];
```

## 📚 Documentation

Full documentation: See `MONITORING.md` for detailed information.

## ✨ You're All Set!

Your monitoring stack is ready. Generate some traffic on your app and watch the metrics flow into Grafana! 🎉
