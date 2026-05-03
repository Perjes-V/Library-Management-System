# ============================================================
# Laravel Deployment Automation Script (v8 - Menu Safe)
# Author: Zabala, Robert
# ============================================================

Set-Location $PSScriptRoot
$LogFile = "deploy_log.txt"

# ============================================================
# PATH DETECTION
# ============================================================

$WampPhpRoot = "C:\wamp64\bin\php"

if (Test-Path $WampPhpRoot) {
    $PhpExe = Get-ChildItem $WampPhpRoot -Filter "php.exe" -Recurse -ErrorAction SilentlyContinue |
        Sort-Object Name -Descending |
        Select-Object -First 1 -ExpandProperty FullName
}
else {
    $PhpExe = $null
}

if (-not $PhpExe) {
    $phpCmd = Get-Command php -ErrorAction SilentlyContinue
    if ($phpCmd) { $PhpExe = $phpCmd.Source }
}

$ComposerExe = Get-Command composer -ErrorAction SilentlyContinue
if ($ComposerExe) { $ComposerExe = $ComposerExe.Source }

# ============================================================
# LOGGING
# ============================================================

function Write-Log {
    param([string]$Message, [string]$Status = "INFO")

    $time = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    $line = "[$time] [$Status] $Message"

    switch ($Status) {
        "SUCCESS" { Write-Host $line -ForegroundColor Green }
        "ERROR"   { Write-Host $line -ForegroundColor Red }
        "WARN"    { Write-Host $line -ForegroundColor Yellow }
        default   { Write-Host $line -ForegroundColor Cyan }
    }

    Add-Content $LogFile $line
}

# ============================================================
# SAFE RUNNER
# ============================================================

function Run-Command {
    param(
        [string]$File,
        [string]$Args,
        [string]$Success,
        [string]$Fail
    )

    try {
        $result = & $File $Args.Split(' ') 2>&1

        if ($LASTEXITCODE -ne 0) {
            throw "$Fail : $result"
        }

        Write-Log $Success "SUCCESS"
    }
    catch {
        Write-Log $_ "ERROR"
        exit 1
    }
}

# ============================================================
# ENV
# ============================================================

function Ensure-Env {
    if (!(Test-Path ".env")) {
        if (Test-Path ".env.example") {
            Copy-Item ".env.example" ".env"
            Write-Log ".env created from template" "WARN"
        }
        else {
            Write-Log ".env.example missing" "ERROR"
            exit 1
        }
    }
}

# ============================================================
# WAMP AUTO START (SAFE)
# ============================================================

function Start-WAMP {

    Write-Log "Checking WAMP..." "INFO"

    $paths = @(
        "C:\wamp64\wampmanager.exe",
        "C:\wamp64bit\wampmanager.exe"
    )

    $wamp = $paths | Where-Object { Test-Path $_ } | Select-Object -First 1

    if (-not $wamp) {
        Write-Log "WAMP not found. Skipping auto-start." "WARN"
        return
    }

    try {
        Start-Process -FilePath $wamp -WindowStyle Normal
        Write-Log "WAMP started successfully" "SUCCESS"

        Start-Sleep -Seconds 8
    }
    catch {
        Write-Log "Failed to start WAMP: $_" "ERROR"
    }
}

# ============================================================
# SMART DB CHECK (OPTIONAL UPGRADE)
# ============================================================

function Wait-ForDatabase-Smart {

    Write-Log "Checking database readiness..." "INFO"

    for ($i = 0; $i -lt 15; $i++) {

        & $PhpExe artisan migrate:status > $null 2>&1

        if ($LASTEXITCODE -eq 0) {
            Write-Log "Database ready" "SUCCESS"
            return
        }

        Start-Sleep -Seconds 2
    }

    Write-Log "Database not ready yet (continuing anyway)" "WARN"
}

# ============================================================
# TASKS
# ============================================================

function Test-Environment {

    if (-not $PhpExe -or -not $ComposerExe) {
        Write-Log "PHP or Composer missing" "ERROR"
        exit 1
    }

    Write-Log "Environment OK" "SUCCESS"
    Ensure-Env
}

function Install-Dependencies {

    if (Test-Path "vendor") {
        Write-Log "Dependencies already installed" "SUCCESS"
        return
    }

    Run-Command $ComposerExe "install --no-interaction --prefer-dist" `
        "Dependencies installed" `
        "Composer install failed"
}

function Set-LaravelKey {

    $env = Get-Content ".env" -Raw

    if ($env -match "APP_KEY=base64:.+") {
        Write-Log "APP_KEY exists" "SUCCESS"
        return
    }

    Run-Command $PhpExe "artisan key:generate --force" `
        "APP_KEY generated" `
        "Key generation failed"
}

function Initialize-Application {

    Set-LaravelKey
    Wait-ForDatabase-Smart

    Run-Command $PhpExe "artisan migrate --force" `
        "Database migrated" `
        "Migration failed"
}

function Optimize-Application {

    Run-Command $PhpExe "artisan config:cache" "Config cached" "Config failed"
    Run-Command $PhpExe "artisan route:cache" "Routes cached" "Routes failed"
    Run-Command $PhpExe "artisan view:cache" "Views cached" "Views failed"
}

function Start-Server {

    Write-Log "Starting Laravel server..." "INFO"
    & $PhpExe artisan serve
}

function Reset-Database {

    $confirm = Read-Host "Type YES to reset DB"

    if ($confirm -ne "YES") {
        Write-Log "Cancelled" "WARN"
        return
    }

    Run-Command $PhpExe "artisan migrate:fresh --seed" `
        "Database reset successful" `
        "Reset failed"
}

# ============================================================
# MENU (UNCHANGED STRUCTURE)
# ============================================================

function Show-Menu {
    Write-Host ""
    Write-Host "=========== LARAVEL DEPLOY MENU ===========" -ForegroundColor Cyan
    Write-Host "[1] Full Deploy"
    Write-Host "[2] Environment Check"
    Write-Host "[3] Reset Database"
    Write-Host "[4] Exit"
}

# ============================================================
# MAIN
# ============================================================

Set-Content $LogFile "===== START $(Get-Date) ====="

while ($true) {

    Show-Menu
    $choice = Read-Host "Select option"

    switch ($choice) {

        "1" {
            Test-Environment
            Start-WAMP
            Install-Dependencies
            Initialize-Application
            Optimize-Application
            Start-Server
        }

        "2" { Test-Environment }

        "3" { Reset-Database }

        "4" { exit }

        default { Write-Host "Invalid option" -ForegroundColor Red }
    }

    Read-Host "Press Enter to continue"
}