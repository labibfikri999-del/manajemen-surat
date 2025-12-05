# Script Backup Database MySQL XAMPP
# Jalankan: powershell -ExecutionPolicy Bypass -File backup-database.ps1

$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$backupDir = "C:\xampp\mysql_backups"
$backupFile = "$backupDir\manajemensurat_$timestamp.sql"

# Buat folder backup jika belum ada
if (!(Test-Path $backupDir)) {
    New-Item -ItemType Directory -Path $backupDir | Out-Null
}

Write-Host "Memulai backup database..." -ForegroundColor Yellow

# Backup menggunakan mysqldump
& "C:\xampp\mysql\bin\mysqldump.exe" `
    -u root `
    --databases manajemensurat `
    --add-drop-database `
    --result-file="$backupFile"

if ($LASTEXITCODE -eq 0) {
    $size = (Get-Item $backupFile).Length / 1KB
    Write-Host "`n✅ Backup berhasil!" -ForegroundColor Green
    Write-Host "File: $backupFile" -ForegroundColor Cyan
    Write-Host "Size: $([math]::Round($size, 2)) KB" -ForegroundColor Cyan
    
    # Hapus backup lama (lebih dari 7 hari)
    Get-ChildItem $backupDir -Filter "*.sql" | 
        Where-Object { $_.LastWriteTime -lt (Get-Date).AddDays(-7) } | 
        Remove-Item -Force
    
    Write-Host "`n📦 Backup lama (>7 hari) sudah dihapus" -ForegroundColor Gray
} else {
    Write-Host "`n❌ Backup gagal!" -ForegroundColor Red
}
