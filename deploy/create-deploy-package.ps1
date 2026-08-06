#Requires -Version 5.1
<#
.SYNOPSIS
  本番反映用 zip を作成します（files-manifest.txt のファイルのみ）

.EXAMPLE
  .\deploy\create-deploy-package.ps1
  .\deploy\create-deploy-package.ps1 -OutputZip "C:\temp\hochiki-deploy.zip"
#>
param(
    [string]$ProjectRoot = (Split-Path -Parent $PSScriptRoot),
    [string]$OutputZip = ""
)

$manifestPath = Join-Path $PSScriptRoot "files-manifest.txt"
if (-not (Test-Path $manifestPath)) {
    Write-Error "Manifest not found: $manifestPath"
    exit 1
}

$lines = Get-Content $manifestPath -Encoding UTF8 | Where-Object {
    $_ -and $_ -notmatch '^\s*#' -and $_ -notmatch '^\s*;'
}

$stamp = Get-Date -Format "yyyyMMdd-HHmm"
if (-not $OutputZip) {
    $OutputZip = Join-Path $PSScriptRoot "hochiki-deploy-$stamp.zip"
}

$tempDir = Join-Path $env:TEMP "hochiki-deploy-$stamp"
if (Test-Path $tempDir) { Remove-Item $tempDir -Recurse -Force }
New-Item -ItemType Directory -Path $tempDir | Out-Null

$missing = @()
foreach ($rel in $lines) {
    $rel = $rel.Trim()
    if (-not $rel) { continue }
    $src = Join-Path $ProjectRoot $rel
    if (-not (Test-Path $src)) {
        $missing += $rel
        continue
    }
    $dest = Join-Path $tempDir $rel
    $destParent = Split-Path $dest -Parent
    if (-not (Test-Path $destParent)) {
        New-Item -ItemType Directory -Path $destParent -Force | Out-Null
    }
    Copy-Item $src $dest -Force
}

# SQL / 手順書も同梱
$deployExtrasDir = Join-Path $tempDir "deploy"
New-Item -ItemType Directory -Path $deployExtrasDir -Force | Out-Null
foreach ($extra in @("migrate-alert.sql", "migrate-hochiki-minimal.sql", "check-db.sql", "DB_MIGRATION.md", "php.ini.snippet", "setting.properties.hochiki.snippet", "DEPLOY_GUIDE.md", "files-manifest.txt")) {
    $src = Join-Path $PSScriptRoot $extra
    if (Test-Path $src) {
        Copy-Item $src (Join-Path $deployExtrasDir $extra) -Force
    }
}

if (Test-Path $OutputZip) { Remove-Item $OutputZip -Force }
Compress-Archive -Path (Join-Path $tempDir '*') -DestinationPath $OutputZip -Force
Remove-Item $tempDir -Recurse -Force

Write-Host "Created: $OutputZip"
Write-Host "Files: $($lines.Count)"
if ($missing.Count -gt 0) {
    Write-Warning "Missing files:"
    $missing | ForEach-Object { Write-Warning "  $_" }
}
