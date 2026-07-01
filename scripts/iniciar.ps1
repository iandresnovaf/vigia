# Arranca el entorno del dashboard (MariaDB portable + servidor PHP).
# Uso:  powershell -ExecutionPolicy Bypass -File scripts\iniciar.ps1

$ErrorActionPreference = "Stop"
$PHP    = "C:\Users\andre\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.2_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe"
$MDBIN  = "C:\Users\andre\mariadb\mariadb-11.4.4-winx64\bin"
$MDDATA = "C:\Users\andre\mariadb\data"
$PUBLIC = "C:\Users\andre\dashboard-entorno\public"

function Puerto($p) { (Test-NetConnection 127.0.0.1 -Port $p -WarningAction SilentlyContinue).TcpTestSucceeded }

# 1) MariaDB
if (Puerto 3306) {
    Write-Host "MariaDB ya esta corriendo (3306)." -ForegroundColor Green
} else {
    Write-Host "Arrancando MariaDB..." -ForegroundColor Cyan
    Start-Process -FilePath "$MDBIN\mysqld.exe" `
        -ArgumentList "--datadir=$MDDATA","--port=3306","--bind-address=127.0.0.1" `
        -WindowStyle Hidden
    for ($i=0; $i -lt 20 -and -not (Puerto 3306); $i++) { Start-Sleep 1 }
    if (Puerto 3306) { Write-Host "MariaDB OK." -ForegroundColor Green }
    else { Write-Host "MariaDB no abrio el puerto 3306." -ForegroundColor Red }
}

# 2) (Opcional) cargar datos de prueba del dron
Write-Host "Cargando datos del dron (cron)..." -ForegroundColor Cyan
& $PHP "C:\Users\andre\dashboard-entorno\cron\fetch_dron.php"

# 3) Servidor web
if (Puerto 8000) {
    Write-Host "El puerto 8000 ya esta en uso." -ForegroundColor Yellow
} else {
    Write-Host "Servidor en http://127.0.0.1:8000  (Ctrl+C para detener)" -ForegroundColor Green
    & $PHP -S 127.0.0.1:8000 -t $PUBLIC
}
