<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

Route::get('/debug-zip', function () {
    $disabled_functions = explode(',', ini_get('disable_functions'));

    return [
        'PHP_OS' => PHP_OS,
        'ZipArchive_Class_Exists' => class_exists('ZipArchive'),
        'shell_exec_Exists' => function_exists('shell_exec'),
        'shell_exec_Disabled_INI' => in_array('shell_exec', $disabled_functions),
        'exec_Exists' => function_exists('exec'),
        'Storage_Path' => storage_path('app/public'),
        'Path_Exists' => File::exists(storage_path('app/public')),
        'File_Count' => File::exists(storage_path('app/public')) ? count(File::allFiles(storage_path('app/public'))) : 'Path not found',
        'Zip_Command_Check' => function_exists('shell_exec') ? shell_exec('zip -v') : 'shell_exec disabled',
    ];
});
