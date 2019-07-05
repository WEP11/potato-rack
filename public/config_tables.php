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

echo    "<br><button id='cfg-add-$table' class='ui-button ui-widget ui-corner-all' data-form='$table'>
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
<div id='dialog-$table' title='Basic dialog'>
    <p class='validateTips'>All form fields are required.</p>

    <form id='form-$table'>
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
    <div id='dialog-$table' title='Basic dialog'>
    <p class='validateTips'>All form fields are required.</p>

    <form id='form-$table'>
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
<div id='dialog-$table' title='Basic dialog'>
    <p class='validateTips'>All form fields are required.</p>

    <form id='form-$table'>
        <fieldset>
            <label for='name'>Name</label>
            <input type='text' name='name' id='name' class='text ui-widget-content ui-corner-all'>

            <label for='manufacturer'>Manufacturer</label>
            <select name='manufacturer' id='manufacturer'>";

    $query = "SELECT * FROM organizations";
    $result = pg_query($query);
    if (!$result) {
        echo "Problem with query " . $query . "<br/>";
        echo pg_last_error();
        exit();
    }
    while ($row = pg_fetch_row($result)) {
        if ($row[6] == true)
        {
            echo "<option value=$row[0]>$row[1]</option>";
        }
    }

    echo "</select>";
    echo "
            <label for='size'>Size (U)</label>
            <input type='number' name='size' id='size min='1' max='5'>

            <label for='urlSupport'>Support URL</label>
            <input type='text' name='urlSupport' id='urlSupport' class='text ui-widget-content ui-corner-all'>

            <label for='urlSpec'>Specification URL</label>
            <input type='text' name='urlSpec' id='urlSpec' class='text ui-widget-content ui-corner-all'>

            <!-- Allow form submission with keyboard without duplicating the dialog button -->
            <input type='submit' tabindex='-1' style='position:absolute; top:-1000px'>
        </fieldset>
    </form>
</div>";

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

    echo "
<div id='dialog-$table' title='Basic dialog'>
    <p class='validateTips'>All form fields are required.</p>

    <form id='form-$table'>
        <fieldset>
            <label for='name'>Name</label>
            <input type='text' name='name' id='name' class='text ui-widget-content ui-corner-all'>
            
            <label for='alias'>Alias</label>
            <input type='text' name='alias' id='alias' class='text ui-widget-content ui-corner-all'>

            <label for='interface'>Network Inteface</label>
            <select name='interface' id='interface'>";

    $query = "SELECT * FROM network_interfaces";
    $result = pg_query($query);
    if (!$result) {
        echo "Problem with query " . $query . "<br/>";
        echo pg_last_error();
        exit();
    }
    while ($row = pg_fetch_row($result)) {
        $deviceQuery = "SELECT * FROM devices WHERE id=$row[2]";
        $deviceResult = pg_query($query);
        if (!$deviceResult) {
            echo "Problem with query " . $deviceQuery . "<br/>";
            echo pg_last_error();
            exit();
        }
        while ($deviceRow = pg_fetch_row($deviceResult)) {
            echo "<option value=$row[0]>$row[1] ($deviceRow[1])</option>";
        }
        
    }

    echo "</select>";
    echo "
            <!-- Allow form submission with keyboard without duplicating the dialog button -->
            <input type='submit' tabindex='-1' style='position:absolute; top:-1000px'>
        </fieldset>
    </form>
</div>";
    
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

    echo "
<div id='dialog-$table' title='Basic dialog'>
    <p class='validateTips'>All form fields are required.</p>

    <form id='form-$table'>
        <fieldset>
            <label for='address'>HW Address</label>
            <input type='text' name='address' id='address' class='text ui-widget-content ui-corner-all'>

            <label for='device'>Device</label>
            <select name='device' id='device'>";

        $deviceQuery = "SELECT * FROM devices";
        $deviceResult = pg_query($query);
        if (!$deviceResult) {
            echo "Problem with query " . $deviceQuery . "<br/>";
            echo pg_last_error();
            exit();
        }
        while ($deviceRow = pg_fetch_row($deviceResult)) {
            echo "<option value=$deviceRow[0]>$deviceRow[1]</option>";
        }

    echo "</select>";
    echo "
            <!-- Allow form submission with keyboard without duplicating the dialog button -->
            <input type='submit' tabindex='-1' style='position:absolute; top:-1000px'>
        </fieldset>
    </form>
</div>";
    
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
    echo "
<div id='dialog-$table' title='Basic dialog'>
    <p class='validateTips'>All form fields are required.</p>

    <form id='form-$table'>
        <fieldset>
            <label for='name'>Name</label>
            <input type='text' name='name' id='name' class='text ui-widget-content ui-corner-all'>

            <label for='description'>Description</label>
            <input type='text' name='description' id='description' class='text ui-widget-content ui-corner-all'>

            <label for='accountNumber'>Account Number</label>
            <input type='text' name='accountNumber' id='accountNumber' class='text ui-widget-content ui-corner-all'>

            <input type='checkbox' name='orgType' value='Customer'> Customer<br>
            <input type='checkbox' name='orgType' value='Developer'> Developer<br>
            <input type='checkbox' name='orgType' value='Manufacturer' Manufacturer<br> 

            <label for='url'>URL</label>
            <input type='text' name='url' id='url' class='text ui-widget-content ui-corner-all'>

            <label for='notes'>Notes</label>
            <input type='text' name='notes' id='notes' class='text ui-widget-content ui-corner-all'>
            <!-- Allow form submission with keyboard without duplicating the dialog button -->
            <input type='submit' tabindex='-1' style='position:absolute; top:-1000px'>
        </fieldset>
    </form>
</div>";
    
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

    echo "
<div id='dialog-$table' title='Basic dialog'>
    <p class='validateTips'>All form fields are required.</p>

    <form id='form-$table'>
        <fieldset>
            <label for='name'>Name</label>
            <input type='text' name='name' id='name' class='text ui-widget-content ui-corner-all'>
            
            <label for='developer'>Developer</label>
            <select name='developer' id='developer'>";

    $query = "SELECT * FROM organizations";
    $result = pg_query($query);
    if (!$result) {
        echo "Problem with query " . $query . "<br/>";
        echo pg_last_error();
        exit();
    }
    while ($row = pg_fetch_row($result)) {
        if ($row[5] == true)
        {
            echo "<option value=$row[0]>$row[1]</option>";
        }
    }

    echo "</select>";
    echo "
            <label for='notes'>Notes</label>
            <input type='text' name='notes' id='notes' class='text ui-widget-content ui-corner-all'>

            <!-- Allow form submission with keyboard without duplicating the dialog button -->
            <input type='submit' tabindex='-1' style='position:absolute; top:-1000px'>
        </fieldset>
    </form>
</div>";

    
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
    echo "
<div id='dialog-$table' title='Basic dialog'>
    <p class='validateTips'>All form fields are required.</p>

    <form id='form-$table'>
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

    echo "
<div id='dialog-$table' title='Basic dialog'>
    <p class='validateTips'>All form fields are required.</p>

    <form id='form-$table'>
        <fieldset>
            <label for='room'>Room</label>
            <input type='text' name='room' id='room' class='text ui-widget-content ui-corner-all'>
            
            <label for='building'>Building</label>
            <select name='building' id='building'>";

    $query = "SELECT * FROM buildings";
    $result = pg_query($query);
    if (!$result) {
        echo "Problem with query " . $query . "<br/>";
        echo pg_last_error();
        exit();
    }
    while ($row = pg_fetch_row($result)) {
        echo "<option value=$row[0]>$row[1] ($row[2])</option>";
    }

    echo "</select>";
    echo "
            <!-- Allow form submission with keyboard without duplicating the dialog button -->
            <input type='submit' tabindex='-1' style='position:absolute; top:-1000px'>
        </fieldset>
    </form>
</div>";
    
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
    echo "
<div id='dialog-$table' title='Basic dialog'>
    <p class='validateTips'>All form fields are required.</p>

    <form id='form-$table'>
        <fieldset>
            <label for='name'>Name</label>
            <input type='text' name='name' id='name' class='text ui-widget-content ui-corner-all'><br>
            <label for='description'>Description</label>
            <input type='text' name='description' id='description' class='text ui-widget-content ui-corner-all'><br>
            <label for='notes'>Notes</label>
            <input type='text' name='notes' id='notes' class='text ui-widget-content ui-corner-all'>
            
            <input type='hidden' id='table' name='table' value='$table'>
            <input type='submit' tabindex='-1' style='position:absolute; top:-1000px'>
        </fieldset>
    </form>
</div>";
    
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
    
    echo "
<div id='dialog-$table' title='Basic dialog'>
    <p class='validateTips'>All form fields are required.</p>

    <form id='form-$table'>
        <fieldset>
            <label for='username'>Username</label>
            <input type='text' name='username' id='username' class='text ui-widget-content ui-corner-all'>
            <br><br><br>
            <label for='radio-1'>Superuser</label>
            <input type='radio' name='level' id='3' value='3'><br>
            <label for='radio-2'>Administrator</label>
            <input type='radio' name='level' id='2' value='2'><br>
            <label for='radio-3'>Staff</label>
            <input type='radio' name='level' id='1' value='1'><br>
            <input type='hidden' id='table' name='table' value='$table'>
            <!-- Allow form submission with keyboard without duplicating the dialog button -->
            <input type='submit' tabindex='-1' style='position:absolute; top:-1000px'>
        </fieldset>
    </form>
</div>";
    
}

echo "<script type='text/javascript' src='js/config_tables.js'></script>";
echo "<script>

$( function() {
    
    function new_table_entry() {
        valid = writeFormData('$table');
    }
    
    dialog = $( '#dialog-$table' ).dialog({
        autoOpen: false,
        modal: true,
        buttons: {
        'Add Entry': new_table_entry,
        Cancel: function() {
            dialog.dialog( 'close' );
        }
        },
        close: function() {
            $( '#dialog-$table' ).dialog( 'close' );
        }
    });

    $( '#cfg-add-$table' ).on( 'click', function() {
      $( '#dialog-$table' ).dialog( 'open' );
    });
} );
</script>";

?>
