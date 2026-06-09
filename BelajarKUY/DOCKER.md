# BelajarKUY — Docker Deployment

Containerised stack for **belajarkuy.site**.

## Architecture

| Service       | Image / build      | Role                                                  |
|---------------|--------------------|-------------------------------------------------------|
| `web`         | Nginx (target `web`) | Serves `public/`, proxies PHP → `app:9000`, WS → `reverb:8080` |
| `app`         | PHP 8.5-FPM (target `app`) | HTTP requests; runs migrations + cache warmup on boot |
| `queue`       | same image         | `queue:work` (Redis queue)                            |
| `scheduler`   | same image         | `schedule:work`                                       |
| `reverb`      | same image         | Laravel Reverb WebSocket server                       |
| `mysql`       | `mysql:8.4`        | Database                                              |
| `redis`       | `redis:7-alpine`   | Cache / sessions / queue / Reverb scaling             |
| `meilisearch` | `getmeili/meilisearch:v1.16` | Laravel Scout search                        |

The `app`, `queue`, `scheduler`, and `reverb` containers all share **one** image
(built once) and a shared `app_storage` volume so logs/uploads stay consistent.

## Production deploy — belajarkuy.site (alur saat ini)

```bash
# 0. (sekali) clone repo ke server, masuk ke folder yang berisi docker-compose.yml
cd /opt/belajarkuy

# 1. Pakai env produksi sebagai .env aktif (berisi secret asli; gitignored).
cp .env.production .env

# 2. Taruh kunci service-account GCS (TIDAK pernah masuk image — di-mount runtime).
#    -> storage/app/gcp/gcp-key.json   (lihat GCP_KEY_FILE_PATH di .env)

# 3. Pastikan SSL host sudah ada: /etc/letsencrypt/live/belajarkuy.site/ (cert + chain).

# 4. Build & start seluruh stack.
docker compose up -d --build

# 5. (sekali) pasang auto-renew SSL — lihat section "SSL / HTTPS" di bawah.
sudo sh docker/certbot/renew.sh
```

Migrasi jalan otomatis saat container `app` boot. Situs live di **https://belajarkuy.site**.

### Checklist "aman 100%" sebelum go-live

- [ ] `.env` = salinan `.env.production`, `APP_ENV=production`, `APP_DEBUG=false`.
- [ ] `.dockerignore` mengecualikan `.env*` & `storage/app/gcp` → secret **tidak** ikut ke image (sudah ✔).
- [ ] Port host yang terbuka ke internet **hanya** 80 & 443. MySQL/Redis/Meili tidak di-expose; phpMyAdmin hanya `127.0.0.1` (akses via SSH tunnel).
- [ ] GCP firewall hanya allow 22 (SSH, idealnya IP whitelist), 80, 443.
- [ ] Auto-renew SSL (`renew.sh`) terpasang di cron + deploy-hook reload Nginx.
- [ ] Midtrans masih **sandbox** (`MIDTRANS_PRODUCTION=false`). Ganti ke production key sebelum terima pembayaran asli.
- [ ] Rotasi secret apa pun yang pernah bocor (DB/APP_KEY/Resend/Google/Cloudinary/Reverb/Meili).

## First-time setup (dev / dari nol)

```bash
# 1. Create your .env (see .env.docker.example for the Docker-specific values)
cp .env.example .env
#    Edit .env: set DB_PASSWORD, DB_ROOT_PASSWORD, MEILISEARCH_KEY,
#    REVERB_APP_SECRET, APP_URL=http://belajarkuy.site, plus your
#    MIDTRANS / GOOGLE / CLOUDINARY / RESEND / GCP keys.

# 2. Generate the app key
docker compose run --rm app php artisan key:generate

# 3. (Optional) GCS — drop the key at storage/app/gcp/service-account.json

# 4. Build & start
docker compose up -d --build
```

Migrations run automatically when the `app` container boots
(`RUN_MIGRATIONS=true`). To seed:

```bash
docker compose exec app php artisan db:seed --force
# build the Meilisearch indexes:
docker compose exec app php artisan scout:import "App\Models\Course"
```

The site is then reachable at **http://belajarkuy.site** (port 80).

## Everyday commands

```bash
docker compose logs -f app          # tail app logs
docker compose exec app php artisan tinker
docker compose exec app php artisan migrate --force
docker compose restart queue        # after deploying new jobs
docker compose up -d --build        # redeploy after a git pull
```

## SSL / HTTPS — setup saat ini: **Certbot di HOST** (`/etc/letsencrypt`)

Keputusan deploy:
- **Certbot dijalankan di host**, cert disimpan di `/etc/letsencrypt`. Container `web`
  me-mount `/etc/letsencrypt:/etc/letsencrypt:ro` (read-only) — lihat
  `docker-compose.yml`.
- **Port 80 & 443 bebas** di server GCP, jadi container `web` bind langsung
  (`80:80`, `443:443`). Tidak ada Nginx pancingan / proxy lain di host.
- `docker/nginx/conf.d/app.conf` **sudah** versi SSL (redirect 80→443, ACME
  webroot, TLS 1.2/1.3, HSTS + security headers, OCSP stapling).

### Penerbitan awal (sudah dilakukan)

Cert `belajarkuy.site` + `www.belajarkuy.site` sudah terbit via Let's Encrypt.

### Perpanjangan (WAJIB di-setup — kalau tidak, cert mati dalam 90 hari)

> ⚠️ Cert awal di-issue saat port 80 kosong (mode *standalone*). Sekarang port 80
> dipegang container `web`, jadi `certbot renew` standalone akan **bentrok port
> dan GAGAL**. Renew harus lewat **webroot** + reload Nginx container.

Script `docker/certbot/renew.sh` menangani ini (idempotent, self-healing):

```bash
# Jalankan sekali manual untuk verifikasi (sekaligus mengonversi config renewal
# ke mode webroot + menyimpan deploy-hook reload Nginx):
sudo sh docker/certbot/renew.sh

# Pasang cron harian (certbot hanya re-issue saat < ~30 hari dari expiry):
sudo crontab -e
# tambahkan (sesuaikan path absolut ke repo di server):
0 3 * * * sh /opt/belajarkuy/docker/certbot/renew.sh >> /var/log/certbot-renew.log 2>&1
```

Cara kerja: certbot menulis file tantangan ACME ke `docker/certbot/www/.well-known/...`
→ Nginx container menyajikannya di port 80 → Let's Encrypt verifikasi → cert baru
ditulis ke `/etc/letsencrypt` → `--deploy-hook` reload Nginx container tanpa downtime.

### Kalau cert harus diterbitkan ulang dari nol

```bash
# Hentikan container web dulu agar port 80 bebas untuk standalone:
docker compose stop web
sudo certbot certonly --standalone -d belajarkuy.site -d www.belajarkuy.site \
  --email viygo79@gmail.com --agree-tos --no-eff-email
docker compose up -d web
# lalu pasang renew webroot seperti di atas.
```

## Notes
- **Drivers:** cache/session/queue use Redis; broadcasting uses Reverb. These are
  injected by `docker-compose.yml`, overriding whatever is in `.env`.
- **Reverb scaling** is enabled (`REVERB_SCALING_ENABLED=true`) so broadcasting
  works across the separate app/queue/reverb containers via Redis.
- **Frontend env (`VITE_*`)** is baked at build time — changing `REVERB_*`
  for the browser requires `docker compose build app`.
- Open the MySQL (`3306`) or Meili (`7700`) port to the host only if needed
  (uncomment the `ports:` blocks); they are reachable container-to-container by
  service name regardless.
