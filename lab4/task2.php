<?php
$url = 'https://pnu.edu.ua/phone_book_pnu/'; // Replace with the URL of the webpage you want to extract data from

$html = file_get_contents($url);

$pattern = '/<table[^>]*>.*?<td>(.*?)<\/td>.*?<td>(.*?)<\/td>.*?<td>(.*?)<\/td>.*?<td>(.*?)<\/td>/s';
preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);

foreach ($matches as $match) {
    $positionName = strip_tags($match[1]);
    $phones = strip_tags($match[2]);


    // Split positionName into Position, Surname, Name
    $positionNameParts = explode("\n", $positionName);

    if(count($positionNameParts) >= 2)
    {
        $surname = trim($positionNameParts[1]);
        $name = trim($positionNameParts[2]);

        echo "Surname: $surname\n";
        echo "Name: $name\n";
        echo "Phones: $phones\n";
    }
}

