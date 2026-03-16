<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Integrasi Telegram untuk Auto Berita AI

Fitur ini memungkinkan bot Telegram menerima foto + judul, lalu server membuat berita dengan NVIDIA AI dan mengirim link berita kembali ke chat.

### 1. Konfigurasi `.env`

Tambahkan nilai berikut:

- `TELEGRAM_BOT_TOKEN` = token bot dari BotFather
- `TELEGRAM_WEBHOOK_SECRET` = secret token webhook (bebas, acak)
- `TELEGRAM_ALLOWED_CHAT_IDS` = daftar chat ID yang diizinkan, pisahkan dengan koma
- `TELEGRAM_NEWS_AUTHOR_EMAIL` = email user penulis berita otomatis
- `TELEGRAM_NEWS_DEFAULT_CATEGORY_SLUG` = slug kategori default (opsional)
- `TELEGRAM_NEWS_AUTO_PUBLISH` = `true` untuk langsung publish, `false` untuk simpan draft

Endpoint webhook aplikasi:

- `POST /telegram/webhook`

### 2. Daftarkan webhook ke Telegram

Contoh request (ganti domain, token, dan secret):

```bash
curl -X POST "https://api.telegram.org/bot<TELEGRAM_BOT_TOKEN>/setWebhook" \
	-H "Content-Type: application/json" \
	-d '{
		"url": "https://domain-anda.com/telegram/webhook",
		"secret_token": "<TELEGRAM_WEBHOOK_SECRET>",
		"drop_pending_updates": true
	}'
```

### 3. Format kirim dari Telegram

- Opsi 1: kirim foto dengan caption berisi judul berita.
- Opsi 2: kirim foto dulu, lalu kirim judul dalam pesan teks berikutnya (maksimal 15 menit).

Setelah proses selesai, bot akan membalas judul hasil AI dan link berita.

### 4. Jalankan Queue Worker

Proses AI Telegram berjalan asynchronous lewat queue. Gunakan `QUEUE_CONNECTION=database` dan jalankan worker:

```bash
php artisan queue:work --queue=default --tries=3
```

Jika `QUEUE_CONNECTION=sync`, proses akan kembali berjalan sinkron di request webhook dan berisiko timeout saat AI lambat.

### 5. Service Agar Queue Jalan Terus di Hosting

Gunakan salah satu metode berikut di server Linux production.

#### Opsi A: Supervisor

1. Salin template [deploy/supervisor/web-smk1kolaka-queue.conf](deploy/supervisor/web-smk1kolaka-queue.conf) ke:
	`/etc/supervisor/conf.d/web-smk1kolaka-queue.conf`
2. Sesuaikan path project bila berbeda dari `/var/www/web-smk1kolaka`.
3. Jalankan:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start web-smk1kolaka-queue:*
sudo supervisorctl status
```

#### Opsi B: systemd

1. Salin template [deploy/systemd/web-smk1kolaka-queue.service](deploy/systemd/web-smk1kolaka-queue.service) ke:
	`/etc/systemd/system/web-smk1kolaka-queue.service`
2. Sesuaikan path project bila berbeda dari `/var/www/web-smk1kolaka`.
3. Jalankan:

```bash
sudo systemctl daemon-reload
sudo systemctl enable web-smk1kolaka-queue
sudo systemctl start web-smk1kolaka-queue
sudo systemctl status web-smk1kolaka-queue
```

#### Opsi C: Shared Hosting (Fallback Cron)

Jika tidak punya akses root (tidak bisa Supervisor/systemd), pakai cron dengan contoh pada:
[deploy/hosting/queue-cron-fallback.txt](deploy/hosting/queue-cron-fallback.txt)

Untuk shared hosting (mis. cPanel):

1. Pastikan .env memakai `QUEUE_CONNECTION=database`.
2. Buat Cron Job per menit untuk `queue:work --stop-when-empty`.
3. Gunakan path PHP CLI hasil `which php` dari terminal hosting.
4. Pantau log di `storage/logs/queue-worker.log`.
