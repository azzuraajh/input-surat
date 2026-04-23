# Input Surat

Backend Laravel untuk pengelolaan surat masuk/keluar dengan autentikasi web berbasis session.

## Arsitektur

Arsitektur runtime sekarang dibikin lewat satu pintu utama:

`user -> api-gateway (nginx) -> app (Laravel) -> db (MySQL)`

- `api-gateway` menjadi entrypoint utama dari luar container.
- `app` menjalankan Laravel dan hanya diekspos internal ke gateway.
- `db` menyimpan user, session, cache database, dan data surat.

## Fitur Auth

- Halaman login ada di `/login`
- Semua route `letters` sekarang dilindungi middleware `auth`
- Logout tersedia dari layout utama
- Seeder admin default disiapkan lewat `AdminUserSeeder`

Default admin untuk stack Docker:

- Email: `admin@input-surat.local`
- Password: `change-me-now`

## Menjalankan Dengan Docker

```bash
docker compose up --build
```

Setelah container siap:

- Akses aplikasi di `http://localhost:8080`
- Health check gateway tersedia di `http://localhost:8080/gateway-health`

Saat startup, container app akan:

1. memastikan `.env` tersedia
2. membuat `APP_KEY` bila belum ada
3. menjalankan migrasi
4. membuat user admin default
5. menjalankan Laravel pada port internal `8000`

## Menjalankan Lokal Tanpa Docker

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

## Testing

```bash
php artisan test
```
