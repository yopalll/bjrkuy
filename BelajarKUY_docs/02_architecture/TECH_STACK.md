# 🏗️ BelajarKUY — Tech Stack Detail

> Detail lengkap technology stack yang digunakan.

---

## Core Stack

| Komponen | Teknologi | Versi | Alasan |
|----------|-----------|-------|--------|
| Framework | Laravel | 12.x | Latest stable, PHP modern, ecosystem mature |
| PHP | PHP | 8.3+ | Required by Laravel 12, typed properties, enums |
| Database | MySQL | 8.x | Reliable, widely supported |
| Frontend | Blade + TailwindCSS | v4 | SSR rendering, utility-first CSS |
| JS Interactivity | Alpine.js | ^3.x | Lightweight, "Tailwind for JS", TALL stack |
| Build | Vite | Latest | Fast HMR, native Laravel support |
| Payment | Midtrans Snap | v2 | Payment gateway Indonesia, sandbox gratis |
| Media Storage | Cloudinary | Latest | Auto-compress, resize, CDN, free tier besar |
| Video Hosting | YouTube (Unlisted) | — | Backup video hosting, 100% gratis |
| Search Engine | Meilisearch + Laravel Scout | Latest | Keystroke search, typo-tolerant, free self-host |
| Real-time | Laravel Reverb | Latest | WebSocket server bawaan Laravel, gratis |
| Email (Prod) | Resend | Latest | Modern transactional email, 3k free/bulan |
| Email (Dev) | Mailtrap | — | Email testing tanpa bocor ke inbox asli |

---

## Composer Packages (Required)

```json
{
    "require": {
        "php": "^8.3",
        "laravel/framework": "^12.0",
        "laravel/breeze": "^2.0",
        "laravel/socialite": "^5.0",
        "laravel/scout": "^10.0",
        "laravel/tinker": "^2.9",
        "midtrans/midtrans-php": "^2.5",
        "intervention/image": "^3.0",
        "cloudinary-labs/cloudinary-laravel": "^2.0",
        "meilisearch/meilisearch-php": "^1.0"
    },
    "require-dev": {
        "fakerphp/faker": "^1.23",
        "laravel/pint": "^1.18",
        "mockery/mockery": "^1.6",
        "nunomaduro/collision": "^8.0",
        "phpunit/phpunit": "^11.0"
    }
}
```

### Package Explanation

| Package | Fungsi |
|---------|--------|
| `laravel/breeze` | Authentication scaffolding (login, register, password reset) |
| `laravel/socialite` | Google OAuth login |
| `laravel/scout` | Full-text search driver untuk Meilisearch |
| `midtrans/midtrans-php` | Midtrans payment SDK |
| `intervention/image` | Image resize & manipulation (thumbnail) |
| `cloudinary-labs/cloudinary-laravel` | Upload media ke Cloudinary CDN |
| `meilisearch/meilisearch-php` | Meilisearch PHP client untuk Laravel Scout |
| `laravel/pint` | Code formatting (PSR-12) |

---

## NPM Packages (Required)

```json
{
    "devDependencies": {
        "@tailwindcss/vite": "^4.0",
        "autoprefixer": "^10.4",
        "axios": "^1.7",
        "laravel-vite-plugin": "^1.0",
        "postcss": "^8.4",
        "tailwindcss": "^4.0",
        "vite": "^6.0"
    },
    "dependencies": {
        "sweetalert2": "^11.0",
        "alpinejs": "^3.0",
        "laravel-echo": "^1.0",
        "pusher-js": "^8.0"
    }
}
```

### Package Explanation

| Package | Fungsi |
|---------|--------|
| `tailwindcss` | Utility-first CSS framework |
| `alpinejs` | Lightweight JS for interactivity (dropdown, modal, toggle, live search) |
| `sweetalert2` | Beautiful alert/confirmation dialogs |
| `axios` | HTTP client untuk AJAX requests |
| `laravel-echo` | Client-side WebSocket listener (untuk Reverb/Pusher) |
| `pusher-js` | WebSocket client (required oleh Laravel Echo) |

---

## External Services

| Service | Fungsi | URL | Free Tier |
|---------|--------|-----|-----------|
| Midtrans Sandbox | Payment testing | https://dashboard.sandbox.midtrans.com | Gratis (sandbox) |
| Google Cloud Console | OAuth credentials | https://console.cloud.google.com | Gratis |
| Cloudinary | Media storage & CDN | https://cloudinary.com | 25 Credits/bulan |
| Meilisearch Cloud | Search engine (opsional) | https://cloud.meilisearch.com | Free tier tersedia |
| Resend | Transactional email (prod) | https://resend.com | 3.000 email/bulan |
| Mailtrap | Email testing (dev) | https://mailtrap.io | Gratis |

---

## Environment Variables

```env
# === APP ===
APP_NAME=BelajarKUY
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID
APP_TIMEZONE=Asia/Jakarta

# === DATABASE ===
# Default: SQLite (zero setup) — ganti ke mysql untuk production parity
DB_CONNECTION=sqlite
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=belajarkuy
# DB_USERNAME=root
# DB_PASSWORD=

# === MIDTRANS (SANDBOX ONLY — ADR-004) ===
# is_production HARDCODED di config/midtrans.php — tidak dari env
MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=
MIDTRANS_MERCHANT_ID=

# === GOOGLE OAUTH ===
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URL=http://localhost:8000/auth/google-callback

# === CLOUDINARY ===
CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME
CLOUDINARY_CLOUD_NAME=
CLOUDINARY_API_KEY=
CLOUDINARY_API_SECRET=
CLOUDINARY_UPLOAD_PRESET=belajarkuy_unsigned

# === MEILISEARCH ===
SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://127.0.0.1:7700
MEILISEARCH_KEY=masterKey

# === BROADCASTING (Reverb) ===
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=belajarkuy
REVERB_APP_KEY=belajarkuy-key
REVERB_APP_SECRET=belajarkuy-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"

# === MAIL (Resend — production/demo) ===
MAIL_MAILER=smtp
MAIL_HOST=smtp.resend.com
MAIL_PORT=465
MAIL_USERNAME=resend
MAIL_PASSWORD=re_xxxxxxxxxxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@belajarkuy.my.id
MAIL_FROM_NAME=BelajarKUY

# === MAIL (Mailtrap — uncomment untuk local dev) ===
# MAIL_MAILER=smtp
# MAIL_HOST=sandbox.smtp.mailtrap.io
# MAIL_PORT=2525
# MAIL_USERNAME=
# MAIL_PASSWORD=
# MAIL_FROM_ADDRESS=noreply@belajarkuy.test
# MAIL_FROM_NAME=BelajarKUY
```

---

## Server Requirements (Production)

| Requirement | Minimum |
|-------------|---------|
| PHP | 8.3 dengan extensions: BCMath, Ctype, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, cURL |
| MySQL | 8.0 |
| Meilisearch | 1.x (self-host atau cloud) |
| Web Server | Nginx / Apache |
| SSL | Required (HTTPS) — Midtrans production memerlukan HTTPS |
| Storage | 10GB minimum (media di Cloudinary, bukan local) |
| RAM | 2GB minimum |
