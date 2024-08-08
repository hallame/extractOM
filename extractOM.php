
/* 
 * This file is part of extractOM.
 * 
 * extractOM is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * extractOM is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with extractOM. If not, see <https://www.gnu.org/licenses/>.
 */




<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

function extractTranslationKeys($directory)
{
    $keys = [];
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
    foreach ($files as $file) {
        if ($file->isDir()) {
            continue;
        }
        if (in_array($file->getExtension(), ['php', 'blade.php'])) {
            $content = file_get_contents($file->getRealPath());
            echo "Processing file: " . $file->getRealPath() . "\n";

            // Regex pour capturer __() et @lang()
            preg_match_all("/__\(['\"](.+?)['\"]\)|@lang\(['\"](.+?)['\"]\)/", $content, $matches);

            if ($matches) {
                foreach ($matches[1] as $key) {
                    if (!empty($key)) {
                        $keys[$key] = '';
                        echo "Found key: " . $key . "\n";
                    }
                }
                foreach ($matches[2] as $key) {
                    if (!empty($key)) {
                        $keys[$key] = '';
                        echo "Found key: " . $key . "\n";
                    }
                }
            }
        }
    }
    return $keys;
}

$directories = [
    base_path('resources/views'),
    base_path('app'),
];

$allKeys = [];
foreach ($directories as $directory) {
    echo "Scanning directory: " . $directory . "\n";
    $keys = extractTranslationKeys($directory);
    $allKeys = array_merge($allKeys, $keys);
}


// Sauvegarder les clés dans un fichier fr.json
file_put_contents(base_path('resources/lang/fr.json'), json_encode($allKeys, JSON_PRETTY_PRINT));

echo "Clés de traduction extraites et sauvegardées dans resources/lang/fr.json\n";
