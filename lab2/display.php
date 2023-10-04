<!DOCTYPE html>
<html>
<head>
    <title>Table Display</title>
</head>
<body>
<h1>Table Data</h1>
<table border="1">
    <thead>
    <?php
    // Display table header
    $json = file_get_contents("table_data.json");
    $data = json_decode($json, true);
    if (!empty($data)) {
        $headerRow = $data[0];
        foreach ($headerRow as $key => $value) {
            echo "<th>$key</th>";
        }
    }
    ?>
    <th>Action</th>
    </thead>
    <tbody>
    <?php
    // Display table rows
    foreach ($data as $index => $row) {
        echo "<tr>";
        foreach ($row as $key => $value) {
            echo "<td>$value</td>";
        }
        echo "<td>";
        echo "<button onclick='editRow($index)'>Edit</button>";
        echo "<form action='display.php' method='post' style='display: inline; margin-left: 10px;'>";
        echo "<input type='hidden' name='deleteIndex' value='$index'>";
        echo "<input type='submit' value='Delete'>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }

    // Handle row deletion
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["deleteIndex"])) {
        $deleteIndex = $_POST["deleteIndex"];
        if (isset($data[$deleteIndex])) {
            unset($data[$deleteIndex]);
            $jsonOutput = json_encode(array_values($data));
            file_put_contents("table_data.json", $jsonOutput);
        }

        // Redirect back to the display page
        header("Location: display.php");
        exit();
    }
    ?>
    </tbody>
</table>

<!-- Row Editing Form -->
<div id="editForm" style="display: none">
    <h2>Edit Row</h2>
    <form action="edit.php" method="post">
        <input type="hidden" name="editIndex" id="editIndex">
        <?php
        if (!empty($headerRow)) {
            foreach ($headerRow as $key => $value) {
                echo "<label for='$key'>$key:</label>";
                echo "<input type='text' name='$key' id='$key'><br>";
            }
        }
        ?>
        <input type='button' value='Cancel' onclick='cancelEdit()'>
        <input type="submit" value="Save Changes">
    </form>
</div>

<script>
    // Function to open the editing form with row data
    function editRow(index) {
        document.getElementById("editIndex").value = index;
        var rowData = <?php echo json_encode($data); ?>;
        var selectedRow = rowData[index];

        <?php
        foreach ($headerRow as $key => $value) {
            echo "document.getElementById('$key').value = selectedRow['$key'];";
        }
        ?>

        // Show the editing form
        document.querySelector("#editForm").style.display = "block";
    }

    // Function to cancel editing and hide the form
    function cancelEdit() {
        document.querySelector("#editForm").style.display = "none";
    }
</script>
</body>
</html>
