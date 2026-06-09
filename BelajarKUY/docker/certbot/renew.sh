#!/bin/sh
# =============================================================================
# BelajarKUY — perpanjang (renew) sertifikat Let's Encrypt. Certbot di HOST.
#
# PENTING: port 80 sekarang dipegang container `web`, jadi certbot TIDAK bisa
# pakai mode "standalone" untuk renew (akan bentrok port → renew GAGAL → situs
# down setelah 90 hari). Script ini renew lewat WEBROOT yang sama dengan yang
# dilayani Nginx container (./docker/certbot/www), lalu reload Nginx di dalam
# container supaya sertifikat baru langsung dipakai TANPA downtime.
#
# Sifatnya idempotent + self-healing:
#   - Memaksa authenticator renewal jadi "webroot" (sekali jalan langsung benar,
#     walau cert awal di-issue via standalone).
#   - Menyimpan deploy-hook reload Nginx ke config renewal.
#   - Dengan --keep-until-expiring: hanya benar-benar re-issue saat < ~30 hari
#     dari expiry, selain itu langsung exit (aman dijalankan tiap hari via cron).
#
# Jalankan sebagai root (certbot butuh akses /etc/letsencrypt):
#   sudo sh docker/certbot/renew.sh
# =============================================================================
set -e

# PATH eksplisit — cron sering jalan dengan PATH minimal tanpa docker/certbot.
export PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin

EMAIL="viygo79@gmail.com"

# Folder script ini (docker/certbot) + folder yang memuat docker-compose.yml.
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
COMPOSE_DIR="$(cd "$SCRIPT_DIR/../.." && pwd)"
WEBROOT="$SCRIPT_DIR/www"

mkdir -p "$WEBROOT"

certbot certonly \
  --webroot -w "$WEBROOT" \
  -d belajarkuy.site -d www.belajarkuy.site \
  --email "$EMAIL" --agree-tos --no-eff-email \
  --non-interactive --keep-until-expiring \
  --deploy-hook "docker compose -f '$COMPOSE_DIR/docker-compose.yml' exec -T web nginx -s reload"

echo "[renew] Selesai. Cert aktif sampai:"
certbot certificates 2>/dev/null | grep -E "Certificate Name|Expiry Date" || true
