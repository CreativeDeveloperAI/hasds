<?php

$filamentDir = __DIR__.'/app/Filament';
$langArPath = __DIR__.'/lang/ar/messages.php';
$langEnPath = __DIR__.'/lang/en/messages.php';

$arMessages = require $langArPath;
$enMessages = require $langEnPath;

$directory = new RecursiveDirectoryIterator($filamentDir);
$iterator = new RecursiveIteratorIterator($directory);
$regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

foreach ($regex as $file => $v) {
    $content = file_get_contents($file);
    $changed = false;

    // Find all instances of ->label('Arabic Text') or ->pluralLabel('Arabic Text') etc.
    // Also matching TextColumn::make('...')->label('...')
    // We only target Arabic strings. We can use a regex to detect arabic letters: \p{Arabic}

    // Pattern to match ->label('...') or similar where the text contains Arabic
    $pattern = '/->(label|pluralLabel|heading|description|placeholder|helperText)\(\s*\'([^\']*?\p{Arabic}[^\']*?)\'\s*\)/u';

    $content = preg_replace_callback($pattern, function ($matches) use (&$arMessages, &$enMessages, &$changed) {
        $methodName = $matches[1];
        $arabicText = $matches[2];

        $key = 'ui_'.substr(md5($arabicText), 0, 8);

        if (! isset($arMessages[$key])) {
            $arMessages[$key] = $arabicText;
            $enMessages[$key] = 'TBD';
        }

        $changed = true;

        return "->{$methodName}(__('messages.{$key}'))";
    }, $content);

    // Also sometimes Filament forms use make('اسم الحقل')
    // Example: TextInput::make('اسم الحقل') - though rare, usually it's make('name')->label('اسم الحقل')

    // Sometimes there are static $title = 'Arabic Text'; in Pages
    $patternTitle = '/(protected|public)\s+static\s+(?:\?string\s+)?\$(title|heading)\s*=\s*\'([^\']*?\p{Arabic}[^\']*?)\'\s*;/u';
    $content = preg_replace_callback($patternTitle, function ($matches) use (&$arMessages, &$enMessages, &$changed) {
        $visibility = $matches[1];
        $prop = $matches[2];
        $arabicText = $matches[3];

        $key = 'ui_'.substr(md5($arabicText), 0, 8);
        if (! isset($arMessages[$key])) {
            $arMessages[$key] = $arabicText;
            $enMessages[$key] = 'TBD';
        }
        $changed = true;

        // Return without static property, and add a getter later, or just replace with property if it's dynamic?
        // In Filament Pages, we can override public function getTitle(): string
        // We'll leave it as is but we must handle it manually if it fails. Let's just do it cleanly.
        // For simplicity, we just use the getter replacement if it's a known property
        return $matches[0]; // skip for now, we'll handle labels only here, pages usually inherit from resources
    }, $content);

    if ($changed) {
        file_put_contents($file, $content);
    }
}

file_put_contents($langArPath, "<?php\n\nreturn ".var_export($arMessages, true).";\n");
file_put_contents($langEnPath, "<?php\n\nreturn ".var_export($enMessages, true).";\n");

echo "Schemas, Tables, and labels refactored successfully.\n";
