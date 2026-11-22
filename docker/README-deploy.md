# Deploying M.R.T web

Pipeline: **push to `main`** → CI gate (Pint, PHPStan L6, Pest) → build image →
push to `ghcr.io/koalaforge/marsoc-tadulako` (`latest` + short sha) → scp
`docker-compose.yml`, `deploy.sh`, `cleanup.sh` to the server → `deploy.sh`
(pull, graceful stop, start, wait healthy) → health check through the proxy →
Discord notifications. Modeled on `OrkutWhatsapp5.0`; the Laravel runtime
(FrankenPHP + supervisor) follows `koalabotbe`.

## What runs

| Thing | Value |
|---|---|
| Container | `marsoc-tadulako` (fixed `container_name`) |
| Networks | `proxy-network` + `backend` (both `external: true`) |
| Redis | the existing container **`redis`** on `backend` (`REDIS_HOST=redis`). Not defined in this compose. |
| Web | FrankenPHP on `:8080` (TLS is terminated by `caddy-proxy`) |
| Worker | `queue:work redis --queue=media,default` under supervisor (image conversions) |
| State | named volume `marsoc-storage` → `/app/storage` (uploads, editor images, logs, media SQLite) |
| Health | `GET /up` + supervisor status of both programs |

## One-time server setup

1. Confirm the shared pieces exist: `docker network ls` shows `proxy-network` and
   `backend`; `docker ps` shows `redis` and `caddy-proxy`.
2. `mkdir -p /root/marsoc-tadulako` and create `/root/marsoc-tadulako/.env` from
   `.env.production.example` (generate `APP_KEY` with
   `docker run --rm ghcr.io/koalaforge/marsoc-tadulako:latest php artisan key:generate --show`
   after the first image exists, or any base64 32-byte key).
3. Install the proxy site: copy `docker/proxy/marsoc-tadulako.caddy` to the proxy's
   `sites/` directory, replace `{$DOMAIN}`, then
   `docker exec caddy-proxy caddy reload --config /etc/caddy/Caddyfile`.
4. GitHub → repo `koalaforge/marsoc-tadulako` → Settings:
   - **Secrets**: `SSH_HOST`, `SSH_USER`, `SSH_KEY`, `SSH_PORT`, `GHCR_TOKEN`
     (read-only `read:packages` PAT), `DISCORD_WEBHOOK_URL` (optional).
   - **Variables**: `PRODUCTION_DOMAIN` (e.g. `example.com`, no scheme) — enables the
     post-deploy `https://<domain>/up` check.
5. First deploy (push to `main`, or run the workflow manually). Migrations
   (MongoDB indexes + media SQLite) run automatically while the container starts.
6. Create the admin and (optionally) load the sample content:

   ```bash
   docker exec -it marsoc-tadulako php artisan mrt:create-admin
   docker exec marsoc-tadulako php artisan db:seed --force     # optional sample data
   ```

   Remove the sample data later with
   `docker exec marsoc-tadulako php artisan content:purge-synthetic --force`.

## Rollback

Actions → **Deploy to Production** → *Run workflow* → set `rollback_tag` to a previous
short sha (visible in the Discord message or in GHCR). The build is skipped and that
tag is deployed. `ghcr-cleanup.yml` keeps the newest 10 versions.

## Useful commands (on the server, in `/root/marsoc-tadulako`)

```bash
docker compose ps
docker logs -f marsoc-tadulako
docker exec marsoc-tadulako supervisorctl status
docker exec marsoc-tadulako php artisan about
IMAGE_TAG=<sha> ./deploy.sh            # manual deploy of a specific tag
```

## Notes

- Never prune the `marsoc-storage` volume: it holds every uploaded photo and the media
  database. `deploy.sh` prunes images and build cache only.
- The `redis` container is shared. Keys are namespaced by `REDIS_PREFIX`/`CACHE_PREFIX`
  and kept on separate logical DBs (`REDIS_DB`, `REDIS_CACHE_DB`) so this app cannot
  collide with koalastore or the WhatsApp bots.
- Laravel trusts the proxy's `X-Forwarded-*` headers (`bootstrap/app.php`), otherwise
  URLs and Filament/Livewire assets would be generated as `http://` behind HTTPS.
