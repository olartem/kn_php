<?php
$text = "чорний кіт і білий пес.";

$whiteCatCount = preg_match_all('/білий\s+кіт/', $text);
$blackCatCount = preg_match_all('/чорний\s+кіт/', $text);
$whiteDogCount = preg_match_all('/білий\s+пес/', $text);
$blackDogCount = preg_match_all('/чорний\s+пес/', $text);

echo "Кількість входжень:\n";
echo "Білий кіт: $whiteCatCount\n";
echo "Чорний кіт: $blackCatCount\n";
echo "Білий пес: $whiteDogCount\n";
echo "Чорний пес: $blackDogCount\n";

