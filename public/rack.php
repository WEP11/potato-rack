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

$table = 'racks';

// Add a Rack Button
echo "<br><button id='cfg-add-$table' class='ui-button ui-widget ui-corner-all' data-form='$table'>
                Add <span class='ui-icon ui-icon-plus'></span>
            </button><br><br>";

// Generate table
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

// Add Rack Dialog
echo "
<div id='dialog-$table' title='Add OS'>
    <p class='validateTips'>All form fields are required.</p>

    <form id='form-$table'>
        <fieldset>
            <label for='name'>Name</label>
            <input type='text' name='name' id='name' class='text ui-widget-content ui-corner-all'><br>
            
            <label for='size'>Size (U)</label>
            <input type='number' name='size' id='size min='1'><br>
            
            <input type='checkbox' name='isBottom' value='isBottom'> Is the rack numbered from the bottom?<br>
            
            <label for='room'>Room</label>
            <select name='room' id='room'>";

    $query = "SELECT * FROM rooms";
    $result = pg_query($query);
    if (!$result) {
        echo "Problem with query " . $query . "<br/>";
        echo pg_last_error();
        exit();
    }
    while ($row = pg_fetch_row($result)) {
        // Get Building Code
        $bldgQuery = "SELECT * FROM buildings WHERE id=$row[2]";
        $bldgResult = pg_query($bldgQuery);
        
        while ($bldgRow = pg_fetch_row($bldgResult)) {
            // Write Option using building_code-room
            echo "<option value=$row[0]>$bldgRow[2]-$row[1]</option>";
        }
    }

    echo "</select><br>";
    echo "
            <label for='notes'>Notes</label>
            <input type='text' name='notes' id='notes' class='text ui-widget-content ui-corner-all'><br>

            <input type='hidden' id='table' name='table' value='$table'>
            <!-- Allow form submission with keyboard without duplicating the dialog button -->
            <input type='submit' tabindex='-1' style='position:absolute; top:-1000px'>
        </fieldset>
    </form>
</div>";

// Javascript Links
echo "<script type='text/javascript' src='js/config_tables.js'></script>";

// Dialog Javascript
echo "<script>

$( function() {
    
    function new_table_entry() {
        valid = writeFormData('$table');
    }
    
    dialog = $( '#dialog-$table' ).dialog({
        autoOpen: false,
        modal: true,
        minWidth: 200,
        buttons: {
            'Add Entry': new_table_entry,
            Cancel: function() {
                $( '#dialog-$table' ).dialog( 'close' );
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
