# Palstory Web (CI/CD ready)

This folder contains a self-contained web image for Palstory, based on `php:8.2-apache` that serves the app from `/var/www/html`.

The `Dockerfile` copies `palstory-game/src/html` into the image so it can run on platforms that do not support bind mounts (Railway, Render, etc.).

## Structure

- `palstory-web/Dockerfile` — builds the web image (PHP 8.2 + Apache)
- `palstory-game/src/html/` — app files copied into the container at build time
- `.dockerignore` — keeps the build context minimal and avoids leaking local files

## Deploy to Railway (recommended)

Two options:

1) Railway native GitHub integration (simplest)
- In Railway, create a Project.
- Add a Service and connect it to this GitHub repo.
- Set the build to use `palstory-web/Dockerfile` (Railway auto-detects Dockerfile at repo root; if needed, set Dockerfile path explicitly).
- Add a MySQL plugin (managed by Railway) or bring your own MySQL.
- In Service Variables, set the variables your app expects (examples):
  - `DB_SERVER`, `DB_USERNAME`, `DB_PASSWORD`, `DB_NAME`
  - `MIGRATE_TOKEN`, `ADMIN_GAME`, `SUPER_ADMIN_GAME`
- If using Railway MySQL, map Railway-provided variables (e.g., `MYSQLHOST`, `MYSQLUSER`, `MYSQLPASSWORD`, `MYSQLDATABASE`) to the names your app expects, or update the app to read Railway variable names directly.
- Enable auto-deploy on push.

2) GitHub Actions with Railway CLI (more control)
- Add `RAILWAY_TOKEN` as a GitHub Actions secret.
- Use the workflow at `.github/workflows/deploy-railway.yml`.
- Optionally set `RAILWAY_PROJECT` and `RAILWAY_SERVICE` secrets to deploy explicitly without prior `railway link`.

## Deploy to Render (alternative)

- Render can build from this repo and run the container.
- Render does not provide managed MySQL. You can:
  - Run MySQL as another Render service with a persistent disk, or
  - Use an external MySQL provider (e.g., PlanetScale, Aiven) and set env vars accordingly.

## Local build/test

Build from the repository root so the Dockerfile can access `palstory-game/src/html`:

```bash
# From repository root
docker build -f palstory-web/Dockerfile -t palstory-web:dev .
docker run --rm -p 8080:80 palstory-web:dev
# Visit http://localhost:8080
```

