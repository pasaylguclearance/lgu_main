# Brings the lgu_main Docker stack up after Windows sign-in.
# Registered as a Scheduled Task (see README "Auto-start on boot").
# Safe to run by hand at any time.

$ErrorActionPreference = 'Continue'
$projectDir = Split-Path -Parent $PSScriptRoot
$dockerBin  = 'C:\Program Files\Docker\Docker\resources\bin'
$desktopExe = 'C:\Program Files\Docker\Docker\Docker Desktop.exe'
$log        = Join-Path $projectDir 'storage\logs\start-stack.log'

function Log($msg) {
    $line = "{0:yyyy-MM-dd HH:mm:ss}  {1}" -f (Get-Date), $msg
    Add-Content -Path $log -Value $line -Encoding utf8
    Write-Host $line
}

if (-not ($env:PATH -split ';' | Where-Object { $_ -eq $dockerBin })) { $env:PATH += ";$dockerBin" }
Set-Location $projectDir
Log "start-stack: begin"

# 1. Make sure Docker Desktop is running
if (-not (Get-Process -Name 'Docker Desktop' -ErrorAction SilentlyContinue)) {
    Log "Docker Desktop not running - launching"
    Start-Process $desktopExe
}

# 2. Wait for the engine (up to 5 minutes)
$ready = $false
for ($i = 0; $i -lt 60; $i++) {
    $null = & docker info 2>$null
    if ($LASTEXITCODE -eq 0) { $ready = $true; break }
    Start-Sleep -Seconds 5
}
if (-not $ready) {
    Log "ERROR: Docker engine did not become ready within 5 minutes"
    exit 1
}
Log "Docker engine ready"

# 3. Bring the stack up (no-op if already running; creates it if it was stopped/removed)
$out = & docker compose up -d 2>&1
Log ($out -join "`n")

# 4. Report
$ps = & docker compose ps --format '{{.Name}}: {{.Status}}' 2>&1
Log ($ps -join "`n")
Log "start-stack: done"
