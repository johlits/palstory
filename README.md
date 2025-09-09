# PalStory Superproject

This repository is the superproject that ties together multiple PalStory submodules.

Submodules included:
- `palstory-game` → https://github.com/johlits/palstory-game
- `palstory-lamp` → https://github.com/johlits/palstory-lamp
- `palstory-vscode` → https://github.com/johlits/palstory-vscode

## Quick Start

Clone with all submodules in one go:

```bash
# Recommended
git clone --recurse-submodules https://github.com/johlits/palstory.git
cd palstory
```

If you already cloned without `--recurse-submodules`, initialize them:

```bash
# From repo root
git submodule update --init --recursive
```

## Pulling Updates

Update the superproject and all submodules to the latest tracked commits:

```bash
# From repo root
# Pull superproject changes
git pull
# Update each submodule to the commit tracked by superproject
git submodule update --init --recursive
```

If you want to fetch submodules and fast-forward to their latest remote commits (not just the tracked commits), you can also run:

```bash
git submodule foreach --recursive git fetch
# Optionally checkout a specific branch in each submodule
# git submodule foreach --recursive "git checkout main && git pull"
```

## Working With Submodules

- Each submodule is a full Git repo inside this tree.
- Make changes inside the submodule directory and commit there.
- Then return to the superproject root and commit the submodule pointer update.

Example:

```bash
# Edit inside a submodule
cd palstory-game
# make changes ...
git add -A
git commit -m "Implement feature X"

# Back to superproject to record the new submodule commit pointer
cd ..
git add palstory-game
git commit -m "Bump palstory-game to latest"
```

## Adding New Submodules

```bash
git submodule add https://github.com/owner/repo.git path/to/submodule
# Commit .gitmodules and the new submodule path
git add .gitmodules path/to/submodule
git commit -m "Add submodule path/to/submodule"
```

## Troubleshooting

- If submodule folders appear empty or missing files, run:
  ```bash
  git submodule update --init --recursive
  ```
- To reset a submodule to the tracked commit:
  ```bash
  git submodule update --recursive --checkout
  ```
- On Windows CRLF/LF warnings are harmless. You can configure per repo:
  ```bash
  git config core.autocrlf true
  ```

## Project Notes

- See `palstory-wiki/backlog.md` for high-level goals and roadmap.
- See `palstory-wiki/README.md` for the full documentation index.
- `palstory-lamp` provides the local Docker LAMP stack.
- `palstory-game` contains the game code.
- `palstory-vscode` provides a VS Code helper extension.
 - Database snapshot: `palstory-wiki/story.dump.sql` is a complete DB snapshot provided for reference and emergency recovery only. Always initialize and evolve the database via migrations under `palstory-game/src/html/migrations/` using `migration_runner.php`. Do not import the dump for routine setup.

## CI/CD and Deployment (palstory-web)

The deployable web image is defined by `palstory-web/Dockerfile` (PHP 8.2 + Apache). At build time the Dockerfile clones `palstory-game` and copies `src/html/` into `/var/www/html/`, so the image is self-contained and suitable for platforms that do not support bind mounts (e.g., Railway, Render).

Key files:
- `palstory-web/Dockerfile` — builds the web container image
- `.github/workflows/build-and-push.yml` — builds and pushes the image to Docker Hub (and optionally GHCR) on push/tags
- `.github/workflows/deploy-railway.yml` — deploys to Railway (build from repo by default). Can be configured to deploy a prebuilt image too.
- `.github/workflows/deploy-railway-image.yml` — manually deploy a specific image tag to Railway

### Build and push (GitHub Actions)

Repository secrets (Settings → Secrets and variables → Actions):
- `DOCKERHUB_USERNAME`
- `DOCKERHUB_TOKEN`

Optional:
- Repository variable `PUBLISH_GHCR=true` to also push to GHCR
- Secret `DOCKERHUB_REPO` to override the default repo name (defaults to `<DOCKERHUB_USERNAME>/palstory`)

Triggers: pushing to `main` creates/updates these tags:
- `:sha-<shortsha>` (always)
- `:latest` (on main)
- `:vX.Y.Z` (when pushing a tag)

### Deploy to Railway (two options)

1) Build-from-repo (default in `deploy-railway.yml`)
- Workflow builds using `palstory-web/Dockerfile` and deploys to the specified service.
- Required secrets:
  - `RAILWAY_TOKEN` (Account API token)
  - `RAILWAY_SERVICE_NAME` (service name or ID)
  - Optional: `RAILWAY_PROJECT` (project name or ID)

2) Deploy prebuilt image (preferred for reproducibility)
- Use `deploy-railway-image.yml` manually with input `image: docker.io/<user>/palstory-web:<tag>`
- Or set secret `RAILWAY_IMAGE` and adjust `deploy-railway.yml` to deploy that image (already supported as an optional path).

Service variables to set on Railway (Web service):
- `DB_SERVER` — MySQL host
- `DB_PORT` — MySQL port (if not 3306)
- `DB_USERNAME`, `DB_PASSWORD`, `DB_NAME`
- `ADMIN_GAME`, `SUPER_ADMIN_GAME`
- Optional: `MIGRATE_TOKEN`, others as needed

Health checks and verification:
- Root (`/`) shows the Palstory Admin page.
- `/game/` loads the game UI.
- `/health.php` is a simple server health endpoint.

### Local quick test

From repo root:

```bash
docker build -f palstory-web/Dockerfile -t palstory-web:dev .
docker run --rm -p 8080:80 \
  -e DB_SERVER=host.docker.internal -e DB_USERNAME=... -e DB_PASSWORD=... -e DB_NAME=... \
  palstory-web:dev
# Visit http://localhost:8080 and /game/
```

