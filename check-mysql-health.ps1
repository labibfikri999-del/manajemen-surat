# Script Cek Kesehatan MySQL
# Jalankan: powershell -ExecutionPolicy Bypass -File check-mysql-health.ps1

Write-Host "=== MYSQL HEALTH CHECK ===" -ForegroundColor Cyan
Write-Host ""

# 1. Cek proses MySQL
$mysqlProcess = Get-Process mysqld -ErrorAction SilentlyContinue
if ($mysqlProcess) {
    Write-Host "✅ MySQL Status: RUNNING (PID: $($mysqlProcess.Id))" -ForegroundColor Green
} else {
    Write-Host "❌ MySQL Status: STOPPED" -ForegroundColor Red
    exit
}

# 2. Cek port 3306
$port = netstat -ano | findstr :3306 | Select-Object -First 1
if ($port) {
    Write-Host "✅ Port 3306: LISTENING" -ForegroundColor Green
} else {
    Write-Host "⚠️  Port 3306: NOT LISTENING" -ForegroundColor Yellow
}

# 3. Cek error log (10 baris terakhir)
Write-Host "`n📄 Error Log (10 baris terakhir):" -ForegroundColor Cyan
Get-Content "C:\xampp\mysql\data\mysql_error.log" -Tail 10 | 
    Select-String -Pattern "ERROR|Warning|FATAL" -Context 0,1 |
    ForEach-Object { Write-Host $_.Line -ForegroundColor Yellow }

# 4. Cek disk space
Write-Host "`n💾 Disk Space:" -ForegroundColor Cyan
$drive = Get-PSDrive C
$freeGB = [math]::Round($drive.Free / 1GB, 2)
$usedGB = [math]::Round($drive.Used / 1GB, 2)
Write-Host "Drive C: - Used: ${usedGB}GB | Free: ${freeGB}GB"

if ($freeGB -lt 5) {
    Write-Host "⚠️  WARNING: Disk space kurang dari 5GB!" -ForegroundColor Red
} else {
    Write-Host "✅ Disk space cukup" -ForegroundColor Green
}

# 5. Cek ukuran database
$dataSize = (Get-ChildItem "C:\xampp\mysql\data\manajemensurat" -Recurse | 
    Measure-Object -Property Length -Sum).Sum / 1MB
Write-Host "`n📊 Database Size: $([math]::Round($dataSize, 2)) MB" -ForegroundColor Cyan

# 6. Cek file corrupt yang berpotensi masalah
Write-Host "`n🔍 Checking for problematic files..." -ForegroundColor Cyan
$problematicFiles = @(
    "C:\xampp\mysql\data\master-*.info",
    "C:\xampp\mysql\data\relay-*.info",
    "C:\xampp\mysql\data\*.dmp"
)

$found = $false
foreach ($pattern in $problematicFiles) {
    $files = Get-ChildItem $pattern -ErrorAction SilentlyContinue
    if ($files) {
        Write-Host "⚠️  Found: $($files.Count) file(s) matching $pattern" -ForegroundColor Yellow
        $found = $true
    }
}

if (!$found) {
    Write-Host "✅ No problematic files found" -ForegroundColor Green
}

Write-Host "`n=== CHECK COMPLETE ===" -ForegroundColor Cyan
