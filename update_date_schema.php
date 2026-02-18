<?php
require_once 'php_action/db_connect.php';

$queries = [
    "ALTER TABLE `company` MODIFY `c_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP",
    "ALTER TABLE `orders` MODIFY `order_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP",
    "ALTER TABLE `order_item` MODIFY `order_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP",
    "ALTER TABLE `pement_details` MODIFY `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP",
    "ALTER TABLE `pro` MODIFY `pdate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP",
    "ALTER TABLE `product` MODIFY `pdate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP",
    "ALTER TABLE `spend` MODIFY `spend_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP",
    "ALTER TABLE `spend_report` MODIFY `paid_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP",
    "ALTER TABLE `sr` MODIFY `c_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP"
];

echo "<h2>Starting Database Schema Update...</h2>";

foreach ($queries as $sql) {
    if ($connect->query($sql) === TRUE) {
        echo "<p style='color: green;'>Successfully updated table: " . explode('`', $sql)[1] . "</p>";
    } else {
        echo "<p style='color: red;'>Error updating table " . explode('`', $sql)[1] . ": " . $connect->error . "</p>";
    }
}

echo "<h3>Update Complete!</h3>";
?>
