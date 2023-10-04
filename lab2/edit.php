<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $editIndex = $_POST["editIndex"];
    $json = file_get_contents("table_data.json");
    $data = json_decode($json, true);

    if (isset($data[$editIndex])) {
        $editedRow = [];
        foreach ($data[$editIndex] as $key => $value) {
            $editedRow[$key] = isset($_POST[$key]) ? $_POST[$key] : $value;
        }
        $data[$editIndex] = $editedRow;

        // Save the updated data to the JSON file
        $jsonOutput = json_encode($data);
        file_put_contents("table_data.json", $jsonOutput);

        // Redirect back to the display page
        header("Location: display.php");
        exit();
    }
}

