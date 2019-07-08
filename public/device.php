<?php
include($_SERVER['DOCUMENT_ROOT']."/rack-test/server-directives.php");
include($sourceRoot."/app/sessions.php");

$userInstance = new PotatoSession;
$userInstance::sessionCheck();

// If the user isn't privileged, don't show anything.
if ($_SESSION['auth'] != 0)
{
    if ($_SESSION['level'] < 0)
    {
        exit("FORBIDDEN");
    }
}

$settings = parse_ini_file($sourceRoot . "/config/settings.ini");

$db = pg_connect('dbname=' . $settings['db_name'] . ' user=' . $settings['db_user'] . ' password=' . $settings['db_password']);

$query = "SELECT * FROM devices";
$result = pg_query($query);
if (!$result) {
    echo "Problem with query " . $query . "<br/>";
    echo pg_last_error();
    exit();
}

echo    "<table>
        <tr>
            <th>Name</th>
            <th>Rack</th>
            <th>Room</th>
            <th>Role</th>
            <th>Hardware</th>
            <th>Size (U)</th>
            <th>OS</th>
            <th>Serial</th>
            <th>Asset</th>
            <th>Customer</th>
            <th>Service Level</th>
            <th>Notes</th>
        </tr>";

while ($row = pg_fetch_row($result)) {
        echo "<tr>";
        echo "<td>$row[1]</td>";
        echo "<td>$row[3]</td>"; // Adjust to show Rack position + rack
        echo "<td>$row[3]</td>"; // Query room from rack
        echo "<td>$row[8]</td>";
        echo "<td>$row[5]</td>";
        echo "<td>$row[5]</td>"; // Query HW for size
        echo "<td>$row[6]</td>";
        echo "<td>$row[10]</td>";
        echo "<td>$row[11]</td>";
        echo "<td>$row[12]</td>";
        echo "<td>$row[13]</td>";
        echo "<td>$row[17]</td>";
        echo "</tr>";
}

echo "</table>";
?>
