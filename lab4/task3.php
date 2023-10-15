<?php
$inputFile = 'text3.txt';
$outputFile = 'text_modified3.txt';

$text = file_get_contents($inputFile);
$modifiedText = preg_replace('/грудня|лютого|березня|квітня|травня|червня|липня|серпня|вересня|жовтня|листопад/',
    'січня', $text);
echo $modifiedText;

