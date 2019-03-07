#!/usr/bin/env php
<?php
if ($argc !== 2) {
    die('missing function name');
}

$functionsHash = json_decode(file_get_contents(__DIR__."/../projects/php_function_list"), true);
$functionName = $argv[1];

if (isset($functionsHash[$functionName])) {
    $output = "";
    foreach($functionsHash[$functionName] as $functionInfo) {
        $output .= "{$functionInfo[0]}($functionInfo[1])\n";
    }
}

print $output;
