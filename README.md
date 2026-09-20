# lgu_main — Pasay LGU Clearance System

Laravel 5.8 / PHP 7.4 / MariaDB application (replicated from `policeclearance_main`), packaged to run locally with Docker.

## Requirements

- Docker Desktop (WSL 2 backend)

## First run

1. Put the database dump in `docker/db-init/` (any `*.sql` file there is imported automatically the first time the database volume is created):

   ```
   docker/db-init/01_pasay_police_clearance.sql
   ```

2. Build and start everything:

   ```powershell
   docker compose up -d --build
   ```

   The first start takes several minutes: the image is built, `composer install` runs, and the ~224 MB dump is imported. Follow progress with `docker compose logs -f`.

3. Open the app:

   | Service      | URL                          | Credentials              |
   |--------------|------------------------------|--------------------------|
   | Application  | http://localhost:8000        | users from the DB dump   |
   | phpMyAdmin   | http://localhost:8080        | `root` / `root`          |
   | MariaDB      | `localhost:3306`             | `lgu` / `lgu` (db `pasay_police_clearance`) |

## Everyday commands

```powershell
docker compose up -d          # start
docker compose down           # stop (data is kept in the dbdata volume)
docker compose logs -f app    # app logs
docker compose exec app bash  # shell inside the app container
docker compose exec app php artisan migrate   # run new migrations
docker compose exec app php artisan tinker
```

The project folder is bind-mounted into the container, so editing files on Windows takes effect immediately.

## Resetting the database

```powershell
docker compose down -v        # deletes the dbdata volume
docker compose up -d          # re-imports docker/db-init/*.sql
```

## Backup

```powershell
docker compose exec db mariadb-dump -uroot -proot pasay_police_clearance > backup.sql
```

## Configuration

Copy `.env.example` to `.env` (the container does this automatically on first run). The DB settings in `.env.example` already point at the `db` service.

## Notes

- `_downloads/topaz` contains the Topaz SigPlus signature-pad SDK installer for client workstations; it is not needed inside the container.
- `pasay_police_clearance.bak` / `pnpclearance.rar` / `pnpclearance.sql` are legacy database exports kept from the original repository.
