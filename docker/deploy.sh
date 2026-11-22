#!/bin/bash
# ============================================================
# M.R.T web — near-zero-downtime rolling deploy
# ============================================================
#   1. Pre-pull the new image while the old container keeps serving
#   2. Gracefully stop the running container (queue worker drains)
#   3. Start the new one (migrations run inside start-container)
#   4. Wait until Docker reports it healthy
#   5. Report the actual downtime, prune stale images
#
# Usage:
#   IMAGE_TAG=<sha|latest> ./deploy.sh   # IMAGE_TAG picks the image (rollback = older sha)
#
# Environment:
#   HEALTH_TIMEOUT  Max seconds to wait for healthy (default: 180)
#   COMPOSE_FILE    Path to docker-compose.yml (default: ./docker-compose.yml)
#   SERVICE         Compose service name (default: marsoc-tadulako)
# ============================================================

set -euo pipefail

HEALTH_TIMEOUT="${HEALTH_TIMEOUT:-180}"
COMPOSE_FILE="${COMPOSE_FILE:-docker-compose.yml}"
SERVICE="${SERVICE:-marsoc-tadulako}"
export IMAGE_TAG="${IMAGE_TAG:-latest}"

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

log_info()  { echo -e "${GREEN}[INFO]${NC}  $(date '+%H:%M:%S') $*"; }
log_warn()  { echo -e "${YELLOW}[WARN]${NC}  $(date '+%H:%M:%S') $*"; }
log_error() { echo -e "${RED}[ERROR]${NC} $(date '+%H:%M:%S') $*"; }
log_step()  { echo -e "${BLUE}[STEP]${NC}  $(date '+%H:%M:%S') $*"; }

now_ms() {
    date +%s%3N 2>/dev/null || python3 -c 'import time; print(int(time.time()*1000))'
}

resolve_container_id() {
    docker compose -f "$COMPOSE_FILE" ps -q "$SERVICE" 2>/dev/null | head -n1
}

wait_for_healthy() {
    local target="$1" elapsed=0 health

    while [ "$elapsed" -lt "$HEALTH_TIMEOUT" ]; do
        health=$(docker inspect --format='{{.State.Health.Status}}' "$target" 2>/dev/null || echo "unknown")

        case "$health" in
            healthy)
                return 0
                ;;
            unhealthy)
                log_error "${SERVICE} is unhealthy!"
                docker logs --tail 40 "$target" 2>&1 || true
                return 1
                ;;
            *)
                sleep 2
                elapsed=$((elapsed + 2))
                ;;
        esac
    done

    log_error "${SERVICE} did not become healthy within ${HEALTH_TIMEOUT}s"
    docker logs --tail 40 "$target" 2>&1 || true
    return 1
}

main() {
    log_step "=== M.R.T web deploy (image tag: ${IMAGE_TAG}) ==="

    for network in proxy-network backend; do
        if ! docker network inspect "$network" >/dev/null 2>&1; then
            log_error "External network '${network}' does not exist on this host."
            exit 1
        fi
    done

    if ! docker ps --format '{{.Names}}' | grep -qx 'redis'; then
        log_warn "No running container named 'redis' - the app expects it on the backend network."
    fi

    if [ ! -f .env ]; then
        log_error ".env is missing in $(pwd) - create it from .env.production.example first."
        exit 1
    fi

    log_step "Pre-pulling image (old container keeps serving)..."
    docker compose -f "$COMPOSE_FILE" pull "$SERVICE" 2>&1

    local was_running=false existing_id
    existing_id=$(resolve_container_id)
    if [ -n "$existing_id" ] && docker ps -q --no-trunc | grep -q "$existing_id"; then
        was_running=true
    fi

    local start
    start=$(now_ms)

    if [ "$was_running" = true ]; then
        log_info "Stopping ${SERVICE} (graceful drain)..."
        docker compose -f "$COMPOSE_FILE" stop "$SERVICE" 2>&1
    fi

    log_info "Starting ${SERVICE}..."
    docker compose -f "$COMPOSE_FILE" up -d --no-deps "$SERVICE" 2>&1

    local container_id
    container_id=$(resolve_container_id)
    if [ -z "$container_id" ]; then
        log_error "Could not resolve the container for '${SERVICE}' after start"
        exit 1
    fi

    log_info "Waiting for healthy (timeout: ${HEALTH_TIMEOUT}s)..."
    if ! wait_for_healthy "$container_id"; then
        log_error "Deploy failed. To roll back, re-run the workflow with the previous image tag."
        exit 1
    fi

    local ms=$(( $(now_ms) - start ))
    log_info "${SERVICE} healthy. Downtime: $((ms / 1000)).$((ms % 1000))s"

    log_info "Pruning old images (>7 days)..."
    docker image prune -af --filter "until=168h" 2>&1 | tail -1
    log_info "Pruning build cache (>7 days)..."
    docker builder prune -af --filter "until=168h" 2>&1 | tail -1
    # Volumes are deliberately NOT pruned: marsoc-storage holds every upload
    # and the media SQLite DB.

    echo ""
    log_step "=== Deploy summary ==="
    docker compose -f "$COMPOSE_FILE" ps
    log_info "Deploy complete."
}

main "$@"
