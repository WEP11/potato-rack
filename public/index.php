<?php
    include($_SERVER['DOCUMENT_ROOT']."/rack-test/server-directives.php");
    include($sourceRoot."/app/sessions.php");
    
    $userInstance = new PotatoSession;
    $userInstance::sessionStart();
?>
<html>
    <head>
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

        <script>
            $( function() {
                $( "#tabs" ).tabs({
                    beforeLoad: function( event, ui ) {
                        ui.jqXHR.fail(function() {
                            ui.panel.html(
                            "Couldn't load this tab. We'll try to fix this as soon as possible.");
                    });
                    }
                });
            } );
            <?php
                // If the user isn't privileged, don't show the Configuration or networking tabs.
                if ($_SESSION['auth'] != 0)
                {
                    if ($_SESSION['level'] < 1)
                    {
                        echo "$(document).ready(function() {
                                $('#tabs').tabs('option', {
	                                'disabled': [3,2]
	                            });
	                            });";
                    }
                }
            ?>
            
        </script>
    </head>
    
    <body>
        <div id="header">
            <img src="images/potato-big.png">
            <h1>Potato Rack</h1>
        </div>

        <div id="tabs">
            <ul>
                <li><a href="rack.php">Racks</a></li>
                <li><a href="device.php">Devices</a></li>
                <li><a href="network.php">Networking</a></li>
                <li><a href="config.php">Config</a></li>
            </ul>
        </div>

        <div id="footer">
            <div id="sessionInfo">
                <?php
                    if ($_SESSION['auth'] == 0)
                    {
                        echo "<i>Non authenticated session</i>";
                    }
                    else 
                    {
                        echo "<b>Logged in as: </b>&nbsp;" . $_SESSION['UID'] . "<br>";
                        echo "<a href='logout.php'>LOGOUT</a>";
                    }
                ?>
            </div>
            <div id="about">
                <a><img src="https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png" ></a>
            </div>
        </div>
    </body>
</html>
