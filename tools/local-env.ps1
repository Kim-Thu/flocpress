# tools/local-env.ps1
# Dùng: . .\tools\local-env.ps1   (chấm đầu tiên là dot-source, bắt buộc)

$ErrorActionPreference = 'Stop'

$PreferredPhp = '8.2.29'   # đổi cho khớp Local → Site → Setup → PHP version
$ToolsDir     = $PSScriptRoot
$CacheFile    = Join-Path $ToolsDir '.local-paths.json'

function Find-LocalPhp {
    param([string]$Preferred)

    $root = Join-Path $env:LOCALAPPDATA `
        'Programs\Local\resources\extraResources\lightning-services'

    if (-not (Test-Path $root)) { throw "Không tìm thấy Local tại: $root" }

    $candidates = Get-ChildItem -Path $root -Recurse -Filter 'php.exe' -ErrorAction SilentlyContinue |
        ForEach-Object {
            $m = [regex]::Match($_.FullName, 'php-(\d+\.\d+\.\d+)')
            [pscustomobject]@{
                Path = $_.FullName
                Ver  = if ($m.Success) { [version]$m.Groups[1].Value } else { [version]'0.0.0' }
            }
        }

    if (-not $candidates) { throw 'Không tìm thấy php.exe trong Local.' }

    $match = $candidates |
        Where-Object { $_.Ver.ToString().StartsWith("$Preferred.") } |
        Sort-Object Ver -Descending | Select-Object -First 1

    if (-not $match) {
        $match = $candidates | Sort-Object Ver -Descending | Select-Object -First 1
    }

    return $match.Path
}

if (Test-Path $CacheFile) {
    $php = (Get-Content $CacheFile -Raw | ConvertFrom-Json).Php
    if (-not (Test-Path $php)) {
        Remove-Item $CacheFile -Force
        throw 'Cache PHP đã cũ. Chạy lại script.'
    }
} else {
    $php = Find-LocalPhp -Preferred $PreferredPhp
    @{ Php = $php } | ConvertTo-Json | Set-Content $CacheFile -Encoding UTF8
}

$global:FlocPhp   = $php
$global:FlocTools = $ToolsDir
$env:PATH = (Split-Path $php) + ';' + $env:PATH

function global:wp {
    & $global:FlocPhp (Join-Path $global:FlocTools 'wp-cli.phar') @args
}

function global:composer {
    & $global:FlocPhp (Join-Path $global:FlocTools 'composer.phar') @args
}

Write-Host "PHP  : $php" -ForegroundColor Green
Write-Host "Lệnh : php, wp, composer" -ForegroundColor Green