<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $year = $_POST["year"];
    $month = $_POST["month"];
    $day = $_POST["day"];
    $task = $_POST["task"];

    $taskObj = [
        "year" => $year,
        "month" => $month,
        "day" => $day,
        "task" => $task
    ];

    $tasks = [];
    if (file_exists("tasks.json")) {
        $tasks = json_decode(file_get_contents("tasks.json"), true);
    }

    $tasks[] = $taskObj;

    usort($tasks, function($a, $b) {
        return strtotime("{$a['year']}-{$a['month']}-{$a['day']}") - strtotime("{$b['year']}-{$b['month']}-{$b['day']}");
    });

    file_put_contents("tasks.json", json_encode($tasks));

    header("Location: index.html");
    exit();
}


