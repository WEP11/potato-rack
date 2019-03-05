<div>
    <script>
    $( function() {
    $( "#cfg-tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    $( "#cfg-tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
    } );
    <?php
        // If the user isn't privileged, don't show the user management tab.
        if ($_SESSION['auth'] != 0)
        {
            if ($_SESSION['level'] < 2)
            {
                echo "$(document).ready(function() {
                        $('#cfg-tabs').tabs('option', {
                            'disabled': [10]
                        });
                        });";
            }
        }
    ?>
    </script>
    Set basic information for all devices. Or change settings.
    <br>
    <div id="cfg-tabs">
    <ul>
        <li><a href="config_tables.php?table=software">Applications</a></li>
        <li><a href="config_tables.php?table=buildings">Buildings</a></li>
        <li><a href="config_tables.php?table=hardware">Hardware</a></li>
        <li><a href="config_tables.php?table=hostnames">Hostnames</a></li>
        <li><a href="config_tables.php?table=network_interfaces">Network Interfaces</a></li>
        <li><a href="config_tables.php?table=organizations">Organizations</a></li>
        <li><a href="config_tables.php?table=operating_systems">OS</a></li>
        <li><a href="config_tables.php?table=roles">Roles</a></li>
        <li><a href="config_tables.php?table=rooms">Rooms</a></li>
        <li><a href="config_tables.php?table=service_levels">Service Levels</a></li>
        <li><a href="config_tables.php?table=users">User Management</a></li>
        
        
    </ul>
</div>
