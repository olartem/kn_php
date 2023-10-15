<?php
$inFile = 'in.html';
$outFile = 'out.html';

$html = file_get_contents($inFile);
$matches = [];
if (preg_match_all('/<a\s+href\s*=\s*"(.*?)"/', $html, $matches)) {
    $links = $matches[1];
    $table = '<table border="1">';
    foreach ($links as $index => $link) {
        $table .= '<tr><td>' . ($index + 1) . '</td><td>' . $link . '</td></tr>';
    }
    $table .= '</table>';
    $html = preg_replace('/<\/body>/', $table . '</body>', $html);
    file_put_contents($outFile, $html);
    echo file_get_contents('out.html');
}

