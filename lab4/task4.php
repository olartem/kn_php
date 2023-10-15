<?php

function extractDates($input) {
    $datePattern = '#[0-9]{2}-((грудня|лютого|січня|березня|квітня|травня|червня|липня|серпня|вересня|жовтня|листопада)|[0-9]{2})(-[0-9]{4})?#';
    preg_match_all($datePattern, $input, $matches);
    return $matches[0];
}

function findCommentsInFile($filename) {
    $fileContent = file_get_contents($filename);
    $commentPattern = '#\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+#';
    preg_match_all($commentPattern, $fileContent, $matches);
    return $matches[0];
}


$filename = 'text4.txt';


$comments = findCommentsInFile($filename);

foreach ($comments as $comment) {
    $dates = extractDates($comment);
    foreach ($dates as $date) {
       echo "$date\n";
    }
}

