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

$table = $_POST['table'];

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

switch($table) {
    case "users":
        $uname = $_POST['username'];
        $level = $_POST['level'];
        
        switch($level){
            case "1":
                $isStaff = 1;
                $isAdmin = 0;
                $isSuper = 0;
            case "2":
                $isStaff = 0;
                $isAdmin = 1;
                $isSuper = 0;
            case "3":
                $isStaff = 0;
                $isAdmin = 0;
                $isSuper = 1;
        }
        
        $query = "INSERT INTO users (username, is_super, is_admin, is_staff) VALUES ('$uname', '$isSuper', '$isAdmin', '$isStaff')";
        pg_query($query);
        
    case "service_levels":
        $name = $_POST['name'];
        $desc = $_POST['description'];
        $notes = $_POST['notes'];
               
        $query = "INSERT INTO service_levels (name, description, notes) VALUES ('$name', '$desc', '$notes')";
        pg_query($query);
}

?>
