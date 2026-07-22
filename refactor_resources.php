<?php

$filamentDir = __DIR__.'/app/Filament';

$directory = new RecursiveDirectoryIterator($filamentDir);
$iterator = new RecursiveIteratorIterator($directory);
$regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

$arMessages = [];
$enMessages = [];

foreach ($regex as $file => $v) {
    $content = file_get_contents($file);
    $changed = false;

    // We will extract and replace static properties with translation methods
    $propertiesToReplace = [
        'navigationLabel' => 'getNavigationLabel',
        'modelLabel' => 'getModelLabel',
        'pluralModelLabel' => 'getPluralModelLabel',
        'navigationGroup' => 'getNavigationGroup',
        'title' => 'getTitle',
        'heading' => 'getHeading',
    ];

    foreach ($propertiesToReplace as $prop => $method) {
        if (preg_match('/protected\s+static\s+(?:\?string|string\|null\|\\\UnitEnum|string\|BackedEnum\|null)\s+\$'.$prop.'\s*=\s*\'([^\']+)\';/u', $content, $matches)) {
            $arabicText = $matches[1];
            $key = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', str_replace('\\', '', $file).'_'.$prop));
            $key = substr(md5($key), 0, 8); // short key
            $key = "resource_{$key}";

            $arMessages[$key] = $arabicText;
            $enMessages[$key] = 'TBD'; // Placeholder

            // Remove property
            $content = str_replace($matches[0], '', $content);

            // Add method at the end of class before last brace
            $methodString = "\n    public static function {$method}(): ?string\n    {\n        return __('messages.{$key}');\n    }\n";
            $content = preg_replace('/}(?!.*})/', $methodString."}\n", $content);

            $changed = true;
        }
    }

    if ($changed) {
        file_put_contents($file, $content);
    }
}

file_put_contents(__DIR__.'/lang/ar/messages.php', "<?php\n\nreturn ".var_export($arMessages, true).";\n");
file_put_contents(__DIR__.'/lang/en/messages.php', "<?php\n\nreturn ".var_export($enMessages, true).";\n");

echo "Resources refactored successfully.\n";
