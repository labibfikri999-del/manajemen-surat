<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use ZipArchive;
use File;

class BackupController extends Controller
{
    public function backupDb()
    {
        try {
            // Manual list of tables to avoid Doctrine dependency
            // Ensure table names match Migration definitions exactly
            $tables = [
                'users', 
                'instansis', 
                'dokumens', 
                'arsip_digital',  // confirmed singular
                'surat_masuks',   // assuming plural based on filename, checked via schema check below
                'surat_keluar',   // confirmed singular
                'klasifikasis', 
                'tipe_lampirans',
                'migrations'
            ];
            
            $data = [];

            foreach ($tables as $table) {
                // Check if table exists before querying to avoid crashes
                // We use Schema facade which we imported
                if (Schema::hasTable($table)) {
                    $rows = DB::table($table)->get()->toArray();
                    $data[$table] = $rows;
                }
            }

            $filename = 'backup-database-' . date('Y-m-d_H-i-s') . '.json';
            
            return response()->streamDownload(function () use ($data) {
                echo json_encode($data, JSON_PRETTY_PRINT);
            }, $filename, ['Content-Type' => 'application/json']);

        } catch (\Throwable $e) {
            \Log::error('Backup DB Error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membuat backup database. Silakan cek log.'], 500);
        }
    }

    public function backupFiles()
    {
        try {
            $filename = 'backup-files-' . date('Y-m-d_H-i-s') . '.zip';
            $tempFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'backup_' . time() . '.zip';
            $sourcePath = storage_path('app/public');

            if (!File::exists($sourcePath)) {
                 return response()->json(['error' => 'Folder penyimpanan tidak ditemukan.'], 404);
            }

            // Method 1: Try PHP ZipArchive
            if (class_exists('ZipArchive')) {
                $zip = new ZipArchive;
                if ($zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                    $files = File::allFiles($sourcePath);
                    if (count($files) === 0) {
                         $zip->addFromString('info.txt', 'Tidak ada file dokumen. Backup kosong.');
                    } else {
                        foreach ($files as $file) {
                             $zip->addFile($file->getRealPath(), $file->getRelativePathname());
                        }
                    }
                    $zip->close();
                    return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
                }
            }

            // Method 2: Fallback to PowerShell (Windows)
            // Compress-Archive -Path "C:\Source\*" -DestinationPath "C:\Dest\file.zip" -Force
            // We use simple shell_exec
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Escape paths for PowerShell
                // Note: PowerShell might have issues with trailing backslashes in some cases, verify path.
                $source = $sourcePath . DIRECTORY_SEPARATOR . '*'; 
                $dest = $tempFile;
                
                $cmd = 'powershell -Command "Compress-Archive -Path \'' . $source . '\' -DestinationPath \'' . $dest . '\' -Force"';
                
                // execute
                shell_exec($cmd);

                if (File::exists($tempFile)) {
                     return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
                }
            }

            // If we reached here, both methods failed
            return response()->json([
                'error' => 'Gagal membuat backup ZIP. Ekstensi ZipArchive tidak aktif dan fallback PowerShell gagal.'
            ], 500);

        } catch (\Throwable $e) {
            \Log::error('Backup Files Error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membuat backup file: ' . $e->getMessage()], 500);
        }
    }
}
