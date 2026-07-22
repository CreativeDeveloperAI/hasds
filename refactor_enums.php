<?php

$enumDir = __DIR__.'/app/Enums';
$langArPath = __DIR__.'/lang/ar/enums.php';
$langEnPath = __DIR__.'/lang/en/enums.php';

$files = glob($enumDir.'/*.php');

$arTranslations = [];
$enTranslations = [];

foreach ($files as $file) {
    $content = file_get_contents($file);
    $className = basename($file, '.php');

    $arTranslations[$className] = [];
    $enTranslations[$className] = [];

    // Find getLabel method
    if (preg_match('/public function getLabel\(\): \?string\s*\{\s*return match \(\$this\) \{([^}]+)\};\s*\}/s', $content, $matches)) {
        $matchBody = $matches[1];
        // Match cases: self::Case => 'Arabic Text',
        preg_match_all('/self::([A-Za-z0-9_]+)\s*=>\s*\'([^\']+)\',/u', $matchBody, $caseMatches, PREG_SET_ORDER);

        foreach ($caseMatches as $caseMatch) {
            $caseName = $caseMatch[1];
            $arabicText = $caseMatch[2];

            // Find the string value of the enum case
            if (preg_match('/case\s+'.$caseName.'\s*=\s*\'([^\']+)\';/', $content, $valueMatch)) {
                $enumValue = $valueMatch[1];
                $arTranslations[$className][$enumValue] = $arabicText;
                $enTranslations[$className][$enumValue] = ucfirst(str_replace('_', ' ', $enumValue)); // simple placeholder
            } elseif (preg_match('/case\s+'.$caseName.'\s*=\s*([0-9]+);/', $content, $valueMatch)) {
                $enumValue = $valueMatch[1];
                $arTranslations[$className][$enumValue] = $arabicText;
                $enTranslations[$className][$enumValue] = $caseName; // simple placeholder
            }
        }

        // Replace getLabel body
        $newBody = "return __('enums.{$className}.' . \$this->value);";
        $newContent = preg_replace('/(public function getLabel\(\): \?string\s*\{)(.*?)(    \})/s', "$1\n        $newBody\n$3", $content);
        file_put_contents($file, $newContent);
    }
}

file_put_contents($langArPath, "<?php\n\nreturn ".var_export($arTranslations, true).";\n");
file_put_contents($langEnPath, "<?php\n\nreturn ".var_export($enTranslations, true).";\n");

echo "Enums refactored successfully.\n";
