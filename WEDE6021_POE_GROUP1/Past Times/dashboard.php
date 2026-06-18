<?php
include 'connect.php';

$result = $conn->query("SELECT * FROM users");

echo "<table border=1>";
echo "<tr><th>Name</th><th>Email</th></tr>";

while($row = $result->fetch_assoc()) {
    echo "<tr>
    <td>{$row['name']}</td>
    <td>{$row['email']}</td>
    </tr>";
}

echo "</table>";
?>
