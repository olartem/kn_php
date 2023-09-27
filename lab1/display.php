<?php
if (file_exists("tasks.json")) {
    $tasks = json_decode(file_get_contents("tasks.json"), true);

    if (!empty($tasks)) {
        echo "<table>";
        echo "<thead><tr><th>Date</th><th>Task</th></tr></thead>";
        echo "<tbody>";
        foreach ($tasks as $task) {
            $date = $task['year'] . '-' . $task['month'] . '-' . $task['day'];
            echo "<tr>";
            echo "<td>$date</td>";
            echo "<td>{$task['task']}</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "No tasks found.";
    }
} else {
    echo "No tasks found.";
}
