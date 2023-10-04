<?php
if (isset($_POST["submit"])) {
    $uploadedFile = $_FILES["file"]["tmp_name"];
    $data = file_get_contents($uploadedFile);

    // Split data into rows and cells
    $rows = explode("-", $data);
    $header = explode("|", array_shift($rows));

    $tableData = [];

    foreach ($rows as $row) {
        $cells = explode("|", $row);
        $rowData = [];

        for ($i = 0; $i < count($header); $i++) {
            $rowData[$header[$i]] = $cells[$i];
        }

        $tableData[] = $rowData;
    }

    $jsonOutput = json_encode($tableData);

    // Save JSON data to a file (you can customize this part)
    file_put_contents("table_data.json", $jsonOutput);

    echo "File uploaded and parsed successfully. <a href='display.php'>View Table</a>";
}

