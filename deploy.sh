#!/usr/bin/env bash
# ──────────────────────────────────────────────────────────────────────────────
# Deploy apkasi — JALANKAN DI SERVER PRODUKSI (yang dilayani domain), di folder repo.
#   Pakai:  ./deploy.sh            (default branch: dev)
#           ./deploy.sh main       (branch lain)
#
# Skrip ini menyamakan kode dgn remote, bersihkan cache, migrasi, lalu restart
# container. `git reset --hard` dipakai SENGAJA: menuntaskan masalah "git pull
# bilang up-to-date tapi file tetap lama". .env AMAN (di-gitignore, tak tersentuh).
#
# Kalau nama container beda, override:
#   APKASI_APP_CONTAINER=xxx APKASI_NGINX_CONTAINER=yyy ./deploy.sh
# ──────────────────────────────────────────────────────────────────────────────
set -euo pipefail
cd "$(dirname "$0")"

BRANCH="${1:-dev}"
APP="${APKASI_APP_CONTAINER:-apkasi_app}"
NGINX="${APKASI_NGINX_CONTAINER:-apkasi_nginx}"

echo "▶ 1/4  Sinkron kode → origin/${BRANCH}"
git fetch origin
git checkout "${BRANCH}" 2>/dev/null || git checkout -b "${BRANCH}" "origin/${BRANCH}"
git reset --hard "origin/${BRANCH}"

echo "▶ 2/5  Bersihkan cache Laravel (container: ${APP})"
docker exec "${APP}" php artisan optimize:clear

echo "▶ 3/5  Symlink storage (foto upload destinasi/gedung/hotel -> /storage)"
docker exec "${APP}" php artisan storage:link --relative --force || docker exec "${APP}" php artisan storage:link --force

echo "▶ 4/5  Migrasi database"
docker exec "${APP}" php artisan migrate --force

echo "▶ 5/5  Restart container: ${APP}, ${NGINX}"
docker restart "${APP}" "${NGINX}"

echo ""
echo "✅ Selesai. Commit aktif sekarang:"
git log --oneline -1
echo ""
echo "Verifikasi cepat (harus keluar angka > 0):"
echo "  curl -s http://127.0.0.1:8279/peta-hotel | grep -c 'onMap ? false'"
