#!/usr/bin/env php
<?php
if ($argc !== 2) {
    die('missing target directories');
}

$targetDirectory = $argv[1];

//find all php files
$cmd = "find $targetDirectory -type f -name \"*.php\" -not -path \"*.git*\"";
$output = shell_exec($cmd);
$fileList = explode("\n", trim($output));
$functionFinder = '/function[\s\n]+(\S+)[\s\n]*\(/';
$functionsHash = [];
foreach($fileList as $file) {
    $content = file_get_contents($file);
    # Apply the Regular Expression to the PHP File Contents
    preg_match_all( $functionFinder , $content , $matches, \PREG_OFFSET_CAPTURE);
    if (count($matches) > 1) {
        $matches = $matches[1];
        foreach($matches as $match) {
            $functionName = $match[0];
            $lineNumber = $match[1];
            //exclude constructor
            if (!in_array($functionName, ['__construct'])) {
                $functionsHash[$functionName][] = [$file, $lineNumber];
            }
        }
    }
}

file_put_contents(__DIR__."/../projects/php_function_list", json_encode($functionsHash));
