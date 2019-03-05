<?php
// If the user isn't privileged, don't show anything.
if (isset($_SESSION['auth']))
{
    if ($_SESSION['level'] < 0)
    {
        exit("FORBIDDEN");
    }
}

include($_SERVER['DOCUMENT_ROOT']."/rack-test/server-directives.php");
$settings = parse_ini_file($sourceRoot . "/config/settings.ini");

$table = $_GET["table"];

// Forbid User Management to non super-users
if (isset($_SESSION['auth']))
{
    if ($_SESSION['level'] < 2 && $table == "users")
    {
        exit("FORBIDDEN");
    }
}

// Validate table
$valid_tables = array(
    "buildings",
    "devices",
    "firewall_rules",
    "hardware",
    "hostnames",
    "installed_software",
    "network_interfaces",
    "operating_systems",
    "organizations",
    "racks",
    "roles",
    "rooms",
    "service_levels",
    "software",
    "users"
);

if (!in_array($table, $valid_tables)) {
    exit("FORBIDDEN");
}

$db = pg_connect('dbname=' . $settings['db_name'] . ' user=' . $settings['db_user'] . ' password=' . $settings['db_password']);

$query = "SELECT * FROM $table";
$result = pg_query($query);
if (!$result) {
    echo "Problem with query " . $query . "<br/>";
    echo pg_last_error();
    exit();
}

echo    "<br><button id='cfg-add' class='ui-button ui-widget ui-corner-all' data-form='$table'>
                Add <span class='ui-icon ui-icon-plus'></span>
            </button><br><br>";
            
if ($table == "software")
{
    
    echo   "<table>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Notes</th>
            </tr>";

    while ($row = pg_fetch_row($result)) {
            echo "<tr>";
            echo "<td>$row[1]</td>"; //Name
            echo "<td>$row[2]</td>"; //zbbreviation
            echo "<td>$row[3]</td>"; //notes
            echo "</tr>";
    }
    
    echo "</table>";
    echo "
<div id='dialog' title='Basic dialog'>
    <p class='validateTips'>All form fields are required.</p>

    <form>
        <fieldset>
            <label for='name'>Name</label>
            <input type='text' name='name' id='name' class='text ui-widget-content ui-corner-all'>
            <label for='description'>Description</label>
            <input type='text' name='description' id='description' class='text ui-widget-content ui-corner-all'>
            <label for='notes'>Notes</label>
            <input type='text' name='notes' id='notes' class='text ui-widget-content ui-corner-all'>
            <!-- Allow form submission with keyboard without duplicating the dialog button -->
            <input type='submit' tabindex='-1' style='position:absolute; top:-1000px'>
        </fieldset>
    </form>
</div>";
    echo "<script type='text/javascript' src='js/config_tables.js'></script>";
}
else if ($table == "buildings")
{
    echo    "<table>
            <tr>
                <th>Name</th>
                <th>Abbreviation</th>
            </tr>";

    while ($row = pg_fetch_row($result)) {
            echo "<tr>";
            echo "<td>$row[1]</td>"; //Name
            echo "<td>$row[2]</td>"; //zbbreviation
            echo "</tr>";
    }
    
    echo "</table>";
    
    echo "
    <div id='dialog' title='Basic dialog'>
    <p class='validateTips'>All form fields are required.</p>

    <form>
        <fieldset>
            <label for='building'>Building</label>
            <input type='text' name='building' id='building' class='text ui-widget-content ui-corner-all'>
            <label for='abbreviation'>Abbreviation</label>
            <input type='text' name='abbreviation' id='abbreviation' class='text ui-widget-content ui-corner-all'>

            <!-- Allow form submission with keyboard without duplicating the dialog button -->
            <input type='submit' tabindex='-1' style='position:absolute; top:-1000px'>
        </fieldset>
    </form>
</div>";
    echo "<script type='text/javascript' src='js/config_tables.js'></script>";
}
else if ($table == "hardware")
{
    echo    "<table>
            <tr>
                <th>Name</th>
                <th>Manufacturer</th>
                <th>Size (U)</th>
                <th>Support URL</th>
                <th>Specification URL</th>
            </tr>";

    while ($row = pg_fetch_row($result)) {
            echo "<tr>";
            echo "<td>$row[1]</td>";
            echo "<td>$row[2]</td>";
            echo "<td>$row[3]</td>";
            echo "<td>$row[4]</td>";
            echo "<td>$row[5]</td>";
            echo "</tr>";
    }
    
    echo "</table>";
    
    echo "
<div id='dialog' title='Basic dialog'>
    <p class='validateTips'>All form fields are required.</p>

    <form>
        <fieldset>
            <label for='name'>Name</label>
            <input type='text' name='name' id='name' class='text ui-widget-content ui-corner-all'>
            <label for='description'>Description</label>
            <input type='text' name='description' id='description' class='text ui-widget-content ui-corner-all'>
            <label for='notes'>Notes</label>
            <input type='text' name='notes' id='notes' class='text ui-widget-content ui-corner-all'>
            <!-- Allow form submission with keyboard without duplicating the dialog button -->
            <input type='submit' tabindex='-1' style='position:absolute; top:-1000px'>
        </fieldset>
    </form>
</div>";
    echo "<script type='text/javascript' src='js/config_tables.js'></script>";
}
else if ($table == "hostnames")
{
    echo    "<table>
            <tr>
                <th>Name</th>
                <th>Alias</th>
                <th>Network Interface</th>
            </tr>";

    while ($row = pg_fetch_row($result)) {
            echo "<tr>";
            echo "<td>$row[1]</td>";
            echo "<td>$row[2]</td>";
            echo "<td>$row[3]</td>";
            echo "</tr>";
    }
    
    echo "</table>";
    
}
else if ($table == "network_interfaces")
{
    echo    "<table>
            <tr>
                <th>HW Address</th>
                <th>Device</th>
            </tr>";

    while ($row = pg_fetch_row($result)) {
            echo "<tr>";
            echo "<td>$row[1]</td>";
            echo "<td>$row[2]</td>";
            echo "<td>$row[3]</td>";
            echo "<td>$row[4]</td>";
            echo "<td>$row[5]</td>";
            echo "</tr>";
    }
    
    echo "</table>";
    
}
else if ($table == "organizations")
{
    echo    "<table>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Account Number</th>
                <th>Customer</th>
                <th>Developer</th>
                <th>Manufacturer</th>
                <th>URL</th>
                <th>Notes</th>
            </tr>";

    while ($row = pg_fetch_row($result)) {
            echo "<tr>";
            echo "<td>$row[1]</td>";
            echo "<td>$row[2]</td>";
            echo "<td>$row[3]</td>";
            echo "<td>$row[4]</td>";
            echo "<td>$row[5]</td>";
            echo "<td>$row[6]</td>";
            echo "<td>$row[7]</td>";
            echo "<td>$row[8]</td>";
            echo "</tr>";
    }
    
    echo "</table>";
    
}
else if ($table == "operating_systems")
{
    echo    "<table>
            <tr>
                <th>Name</th>
                <th>Developer</th>
                <th>Notes</th>
            </tr>";

    while ($row = pg_fetch_row($result)) {
            echo "<tr>";
            echo "<td>$row[1]</td>";
            echo "<td>$row[2]</td>";
            echo "<td>$row[3]</td>";
            echo "</tr>";
    }
    
    echo "</table>";
    
}
else if ($table == "roles")
{
    echo    "<table>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Notes</th>
            </tr>";

    while ($row = pg_fetch_row($result)) {
            echo "<tr>";
            echo "<td>$row[1]</td>";
            echo "<td>$row[2]</td>";
            echo "<td>$row[3]</td>";
            echo "</tr>";
    }
    
    echo "</table>";
    
}
else if ($table == "rooms")
{
    echo    "<table>
            <tr>
                <th>Room</th>
                <th>Building</th>
            </tr>";

    while ($row = pg_fetch_row($result)) {
            echo "<tr>";
            echo "<td>$row[1]</td>";
            echo "<td>$row[2]</td>";
            echo "</tr>";
    }
    
    echo "</table>";
    
}
else if ($table == "service_levels")
{
    echo    "<table>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Notes</th>
            </tr>";

    while ($row = pg_fetch_row($result)) {
            echo "<tr>";
            echo "<td>$row[1]</td>";
            echo "<td>$row[2]</td>";
            echo "<td>$row[3]</td>";
            echo "</tr>";
    }
    
    echo "</table>";
    
}
else if ($table == "users")
{
    // And again Forbid User Management to non super-users
    if ($_SESSION['auth'] != 0)
    {
        if ($_SESSION['level'] < 2)
        {
            exit("FORBIDDEN");
        }
    }
    
    echo    "<table>
            <tr>
                <th>Username</th>
                <th>Super User</th>
                <th>Administrator</th>
                <th>Staff Member</th>
            </tr>";

    while ($row = pg_fetch_row($result)) {
            echo "<tr>";
            echo "<td>$row[1]</td>";
            echo "<td>$row[2]</td>";
            echo "<td>$row[3]</td>";
            echo "<td>$row[4]</td>";
            echo "</tr>";
    }
    
    echo "</table>";
    
}




?>
