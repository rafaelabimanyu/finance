# Deployment Guide & Production Optimization Checklist - J&J Group Finance

Panduan ini ditujukan bagi tim DevOps dan Administrator Sistem untuk melakukan deployment aplikasi keuangan dan operasional internal J&J Group (`finance.jj-group.id`) ke server production Ubuntu/Debian Linux dengan aman dan teroptimasi.

---

## 1. Konfigurasi Environment (`.env.production`)

Buat atau ubah file `.env` di server produksi dengan pengaturan pengerasan keamanan berikut:

```env
APP_NAME="J&J Group Finance"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://finance.jj-group.id

# Matikan opsi debug stack trace untuk meredam kebocoran informasi kode program
DEBUGBAR_ENABLED=false

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jj_finance_prod
DB_USERNAME=jj_db_user
DB_PASSWORD=KombinasiPasswordSangatKuat_2026!

# Log channel yang disarankan untuk lingkungan production
LOG_CHANNEL=daily
LOG_LEVEL=error

# Session & Cookie Security (Wajib aktif di produksi dengan HTTPS)
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
```

---

## 2. Optimasi Kinerja Laravel (Artisan Commands)

Lakukan pembersihan cache lokal dan buat cache statis untuk performa maksimal di production:

```bash
# 1. Jalankan instalasi dependensi tanpa mode dev
composer install --no-dev --optimize-autoloader

# 2. Cache konfigurasi aplikasi
php artisan config:cache

# 3. Cache definisi rute
php artisan route:cache

# 4. Compile dan cache template blade
php artisan view:cache

# 5. Compile aset frontend dengan Vite
npm run build

# 6. Jalankan migrasi basis data secara aman (Force di produksi)
php artisan migrate --force
```

---

## 3. Konfigurasi Server Web (Nginx Block)

Buat file blok konfigurasi Nginx baru pada `/etc/nginx/sites-available/finance.jj-group.id` lalu arahkan subdomain target secara bersih dengan sertifikat SSL/HTTPS dari Let's Encrypt:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name finance.jj-group.id;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name finance.jj-group.id;
    root /var/www/finance/public;

    # SSL Certificates (Let's Encrypt Path)
    ssl_certificate /etc/letsencrypt/live/finance.jj-group.id/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/finance.jj-group.id/privkey.pem;
    
    # SSL Best Practices
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_prefer_server_ciphers on;
    ssl_ciphers 'ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384';
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 1d;
    ssl_session_tickets off;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";
    add_header Referrer-Policy "strict-origin-when-cross-origin";
    
    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock; # Sesuaikan dengan versi PHP-FPM Anda
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        
        # PHP FastCGI Optimizations
        fastcgi_read_timeout 600;
        fastcgi_buffer_size 16k;
        fastcgi_buffers 4 16k;
    }

    # Deny access to hidden files (.env, .git, etc.)
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 4. Keamanan Cron Job & Queue Worker

Aplikasi keuangan ini memerlukan pengoperasian cron untuk pemantauan berkelanjutan:

```bash
# Daftarkan pada crontab server (crontab -e)
* * * * * cd /var/www/finance && php artisan schedule:run >> /dev/null 2>&1
```

Gunakan **Supervisor** untuk memantau worker antrean jika terdapat proses background:
```ini
[program:jj-finance-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/finance/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/finance/storage/logs/worker.log
stopwaitsecs=3600
```
