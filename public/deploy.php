<?php

/**
 * GIT DEPLOYMENT SCRIPT
 *
 * Used for automatically deploying from GitHub.
 * Protected by a SECRET_TOKEN.
 */

// 1. KUNCI RAHASIA (Ganti dengan password panjang/unik)
// Nanti kita masukkan ini di GitHub Secrets
define('SECRET_TOKEN', 'RAHASIA_SUPER_AMAN_123');

// 2. Cek Token
if (!isset($_GET['key']) || $_GET['key'] !== SECRET_TOKEN) {
    http_response_code(403);
    die('⛔ Akses Ditolak: Token salah.');
}

// 3. Konfigurasi
$gitBranch = 'main'; // sesuaikan dengan branch Anda (main/master)
$logFile   = '../storage/logs/deploy.log';

// 4. Perintah Git
$commands = [
    'echo $PWD',
    'whoami',
    'git pull origin ' . $gitBranch,
    'git status',
    // 'php artisan migrate --force', // Aktifkan jika perlu
];

// 5. Eksekusi
$output = '';
foreach ($commands as $command) {
    // Jalankan perintah di root folder (naik satu level dari public)
    $tmp = shell_exec("cd .. && $command 2>&1");
    
    $output .= "<span style=\"color: #6BE585;\">\$</span> <span style=\"color: #DBDBDB;\">{$command}\n</span>";
    $output .= htmlentities(trim($tmp)) . "\n\n";
}

// 6. Log & Tampilkan
file_put_contents($logFile, date('Y-m-d H:i:s') . "\n" . $output . "\n-------------------\n", FILE_APPEND);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deployment Status</title>
    <style>
        body { background-color: #0d1117; color: #c9d1d9; font-family: monospace; padding: 20px; }
        pre { white-space: pre-wrap; word-wrap: break-word; }
    </style>
</head>
<body>
    <h3>🚀 Deployment Status</h3>
    <pre><?php echo $output; ?></pre>
</body>
</html>
