<?php

$enumDir = __DIR__.'/app/Enums';
$files = glob($enumDir.'/*.php');

foreach ($files as $file) {
    $content = file_get_contents($file);
    // Replace }; followed by }
    $content = str_replace("    };\n    }\n}", "    }\n}", $content);
    // Let's do a regex to be safe
    $content = preg_replace('/(return __\([^)]+\);)\s*\};\s*\}/s', "$1\n    }", $content);
    file_put_contents($file, $content);
}

echo 'Fixed syntax errors in Enums.';
