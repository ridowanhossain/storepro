<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'php_action/core.php';

echo "<h1>Fixing Spend Table (Attempt 3)...</h1>";

// spend table is ALREADY FIXED. Skipping.
echo "✅ Spend table already fixed. Moving to spend_report...<br><hr>";

// Fix spend_report
// The error says it must be defined as a key. This means 'id' is NOT a primary key yet.
// So we need to ADD PRIMARY KEY first.

$sql = "ALTER TABLE spend_report ADD PRIMARY KEY (id)";
if($connect->query($sql) === TRUE) {
    echo "✅ Successfully added PRIMARY KEY to spend_report.<br>";
} else {
    echo "⚠️ Error adding PRIMARY KEY (Maybe already exists but not auto?): " . $connect->error . "<br>";
}

// Now add AUTO_INCREMENT
$sql2 = "ALTER TABLE spend_report MODIFY COLUMN id INT(11) NOT NULL AUTO_INCREMENT";
if($connect->query($sql2) === TRUE) {
    echo "✅ Successfully added AUTO_INCREMENT to spend_report table.<br>";
} else {
    echo "❌ Error adding AUTO_INCREMENT: " . $connect->error . "<br>";
}

$connect->close();
echo "<h1>Done.</h1>";
?>
