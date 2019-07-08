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
error_log($table);
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
        
        break;
        
    case "service_levels":
        $name = $_POST['name'];
        $desc = $_POST['description'];
        $notes = $_POST['notes'];
               
        $query = "INSERT INTO service_levels (name, description, notes) VALUES ('$name', '$desc', '$notes')";
        pg_query($query);
        
        break;

    case "software":
        $name = $_POST['name'];
        $desc = $_POST['description'];
        $notes = $_POST['notes'];

        $query = "INSERT INTO software (name, description, notes) VALUES ('$name', '$desc', '$notes')";
        pg_query($query);
        
        break;

    case "buildings":
        $name = $_POST['building'];
        $abbrev = $_POST['abbreviation'];

        $query = "INSERT INTO buildings (name, short_name) VALUES ('$name', '$abbrev')";
        pg_query($query);
        
        break;

    case "hardware":
        $name = $_POST['name'];
        $manu = $_POST['manufacturer'];
        $size = $_POST['size'];
        $urlSupt = $_POST['urlSupport'];
        $urlSpec = $_POST['urlSpec'];

        $query = "INSERT INTO hardware (name, manufacturer, size, support_url, spec_url) VALUES ('$name', $manu, '$size', '$urlSupt', '$urlSpec')";
        pg_query($query);
        
        break;

    case "hostnames":
        $name = $_POST['name'];
        $alias = $_POST['alias'];
        $interface = $_POST['interface'];

        $query = "INSERT INTO hostnames (name, is_alias, network_interface) VALUES ('$name', '$alias', '$interface')";
        pg_query($query);
        
        break;

    case "network_interfaces":
        $address = $_POST['address'];
        $device = $_POST['device'];

        $query = "INSERT INTO network_interfaces (mac_address, device) VALUES ('$address', '$device')";
        pg_query($query);
        
        break;

    case "organizations":
        $name = $_POST['name'];
        $desc = $_POST['description'];
        $acct = $_POST['accountNumber'];
        
        if(isset($_POST['isCustomer'])){
            $isCustomer = 'true';
        }
        else {
            $isCustomer = 'false';
        }
        
        if(isset($_POST['isDeveloper'])){
            $isDeveloper = 'true';
        }
        else {
            $isDeveloper = 'false';
        }
        
        if(isset($_POST['isManufacturer'])){
            $isManufacturer = 'true';
        }
        else {
            $isManufacturer = 'false';
        }
        
        $url = $_POST['url'];
        $notes = $_POST['notes'];

        $query = "INSERT INTO organizations (name, description, account_number, is_customer, is_developer, is_manufacturer, url, notes) VALUES ('$name', '$desc', '$acct', $isCustomer, $isDeveloper, $isManufacturer, '$url', '$notes')";
        pg_query($query);
        
        break;

    case "operating_systems":
        $name = $_POST['name'];
        $developer = $_POST['developer'];
        $notes = $_POST['notes'];

        $query = "INSERT INTO operating_systems (name, developer, notes) VALUES ('$name', $developer, '$notes')";
        pg_query($query);
        
        break;

    case "roles":
        $name = $_POST['name'];
        $desc = $_POST['description'];
        $notes = $_POST['notes'];

        $query = "INSERT INTO roles (name, description, notes) VALUES ('$name', '$desc', '$notes')";
        pg_query($query);
        
        break;

    case "rooms":
        $name = $_POST['room'];
        $building = $_POST['building'];

        $query = "INSERT INTO rooms (name, bldg) VALUES ('$name', '$building')";
        pg_query($query);
        
        break;
        
    case "racks":
        $name = $_POST['name'];
        $size = $_POST['size'];
        $room = $_POST['room'];
        $notes = $_POST['notes'];

        if(isset($_POST['isBottom'])){
            $isNumBottom = 'true';
        }
        else {
            $isNumBottom = 'false';
        }
        
        $query = "INSERT INTO racks (name, size, room, is_numberedfrombottom, notes) VALUES ('$name', '$size', $room, $isNumBottom, '$notes')";
        pg_query($query);
        
        break;
        
    case "devices":
        
        break;
}

?>
