# lgu_main — Pasay LGU Clearance System

Laravel 5.8 / PHP 7.4 / MariaDB application (replicated from `policeclearance_main`), packaged to run locally with Docker.

## Requirements

- Docker Desktop (WSL 2 backend). On a fresh Windows install run `wsl --install --no-distribution`
  from an admin prompt and reboot first, otherwise the Docker engine fails to start.
- If `docker` is "not recognized" in PowerShell, add `C:\Program Files\Docker\Docker\resources\bin` to PATH
  or run `$env:PATH += ";C:\Program Files\Docker\Docker\resources\bin"` in the session.

## First run

1. Put the database dump in `docker/db-init/` (any `*.sql` file there is imported automatically the first time the database volume is created):

   ```
   docker/db-init/01_pasay_police_clearance.sql
   ```

   > The original export writes empty strings as `CONVERT(0x USING utf8mb4)`, which MariaDB rejects
   > (`Unknown column '0x'`) and the import silently stops part-way (only ~9 of 15 tables). If you
   > re-export the dump, replace every `CONVERT(0x USING utf8mb4)` with `''` before importing.

2. Build and start everything:

   ```powershell
   docker compose up -d --build
   ```

   The first start takes several minutes: the image is built, `composer install` runs, and the ~224 MB dump is imported. Follow progress with `docker compose logs -f`.

3. Open the app:

   | Service      | URL                          | Credentials              |
   |--------------|------------------------------|--------------------------|
   | Application  | http://localhost:8200        | `superadmin@gmail.com` / `P@ssw0rd` (seeded by migration) |
   | phpMyAdmin   | http://localhost:8201        | `root` / `root`          |
   | MariaDB      | `localhost:33200`             | `lgu` / `lgu` (db `pasay_police_clearance`) |

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

## Auto-start on boot

The stack comes up by itself after Windows sign-in, via two layers:

1. Docker Desktop is set to *Start when you sign in* (Settings → General), and the containers use
   `restart: unless-stopped`, so they return as soon as the engine is up.
2. A Scheduled Task **LGU Docker Stack** (trigger: at logon, 20 s delay) runs `docker/start-stack.ps1`,
   which launches Docker Desktop if needed, waits for the engine, and runs `docker compose up -d`.
   This also recovers the stack if it was stopped with `docker compose down`. It logs to
   `storage/logs/start-stack.log`.

To (re)register the task on another machine:

```powershell
$script  = "$PWD\docker\start-stack.ps1"
$action  = New-ScheduledTaskAction -Execute powershell.exe -Argument "-NoProfile -ExecutionPolicy Bypass -WindowStyle Hidden -File `"$script`""
$trigger = New-ScheduledTaskTrigger -AtLogOn -User "$env:USERDOMAIN\$env:USERNAME"; $trigger.Delay = 'PT20S'
Register-ScheduledTask -TaskName 'LGU Docker Stack' -Action $action -Trigger $trigger -Force
```

Docker Desktop needs a signed-in user session; if the PC should be usable without anyone logging in,
enable Windows auto-login (`netplwiz`) for this account.

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
