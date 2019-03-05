<?php
// If the user isn't privileged, don't show anything.
if ($_SESSION['auth'] != 0)
{
    if ($_SESSION['level'] < 0)
    {
        exit("FORBIDDEN");
    }
}

include($_SERVER['DOCUMENT_ROOT']."/rack-test/server-directives.php");
$settings = parse_ini_file($sourceRoot . "/config/settings.ini");

$db = pg_connect('dbname=' . $settings['db_name'] . ' user=' . $settings['db_user'] . ' password=' . $settings['db_password']);

$query = "SELECT * FROM racks";
$result = pg_query($query);
if (!$result) {
    echo "Problem with query " . $query . "<br/>";
    echo pg_last_error();
    exit();
}

echo    "<table>
        <tr>
            <th>Name</th>
            <th>Size</th>
            <th>Room</th>
            <th>Notes</th>
        </tr>";

while ($row = pg_fetch_row($result)) {
        echo "<tr>";
        echo "<td>$row[1]</td>";
        echo "<td>$row[2]</td>";
        echo "<td>$row[3]</td>"; 
        echo "<td>$row[5]</td>";
        echo "</tr>";
}

echo "</table>";
?>
