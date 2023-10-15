<?php
$names = ['Артем Олійник', 'артем Олійник', 'Артем олійник', 'артем олійник', 'Aoopasd'];

foreach ($names as $name)
{
    $valid = preg_match_all('/(([А-Я]|І)|([а-я]|і)+)\s(([А-Я]|І)|([а-я]|і)+)/', $name);
    echo "Name: " . $name . "\n";
    echo $valid . "\n";
}

