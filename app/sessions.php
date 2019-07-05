<?php
include($_SERVER['DOCUMENT_ROOT']."/rack-test/server-directives.php");
include($sourceRoot."/app/phpCAS/CAS.php");

class PotatoSession
{
    
    // Start Session
    static function sessionStart()
    {
        include($_SERVER['DOCUMENT_ROOT']."/rack-test/server-directives.php");
        $settings = parse_ini_file($sourceRoot . "/config/settings.ini");
        
        if ($settings['authentication'] == 0)
        {
            session_unset();
            session_destroy();
            session_start();
            $_SESSION['auth']=0;
            return;
        }
        else if ($settings['authentication'] == 1 )
        {
            $uname = $_SERVER['REMOTE_USER'];
        }
        else if ($settings['authentication'] == 2 )
        {
            phpCAS::setDebug();
            phpCAS::setVerbose(true);
            // initialize phpCAS
            phpCAS::client(CAS_VERSION_2_0, $settings['cas_domain'], (int)$settings['cas_port'], $settings['cas_dir']);
            phpCAS::setCasServerCACert($sourceRoot . $settings['cas_crt']);
            
            //phpCAS::setNoCasServerValidation(); // Debug Only. Not for production!
            
            // force CAS authentication
            phpCAS::forceAuthentication();
            $uname = phpCAS::getUser();
        }
        
        $CAS_REDIR = $settings['cas_redirect'];
        session_unset();
        session_destroy();
                
        $db = pg_connect('dbname=' . $settings['db_name'] . ' user=' . $settings['db_user'] . ' password=' . $settings['db_password']);
        
        $query = "SELECT * FROM users WHERE username='$uname'";
        $result = pg_query($query);
        $row = pg_fetch_row($result);
        
        // Verify User is Authorized
        if ($row[2] != 't' && $row[3] != 't' && $row[4] != 't')
        {
          $_SESSION['message'] = 'You are not authorized to access this resource';
          phpCAS::logoutWithRedirectService($CAS_REDIR);
          header("location:$CAS_REDIR");
        }
        
        // Find user level
        if ($row[2] == 't')
        {
            $level = 2;
        }
        else if ($row[3] == 't')
        {
            $level = 1;
        }
        else if ($row[4] == 't')
        {
            $level = 0;
        }
        
        // Start the session and set these variables
        session_start();
        $_SESSION['UID']=$row[1];
        $_SESSION['sessionID']=session_create_id();
        $_SESSION['level']=$level;
        $_SESSION['auth'] = 1;
        $_SESSION['REMOTE_USER'] = $row[0];
    }

    // Check Session (Intermediate Pages)
    static function sessionCheck()
    {
        include($_SERVER['DOCUMENT_ROOT']."/rack-test/server-directives.php");
        session_start();
        if (!isset($_SESSION['UID']) && !isset($_SESSION['sessionID']))
        {
            self::sessionStart();
        }

        return;
    }

    // Update Session
    static function sessionUpdate()
    {

    }

    // Kill Session
    static function sessionKill()
    {
        // Get Settings
        include($_SERVER['DOCUMENT_ROOT']."/rack-test/server-directives.php");
        $settings = parse_ini_file($sourceRoot . "/config/settings.ini");

        // Destroy Session
        session_start();
        session_unset();
        session_destroy();
        session_start();
        $_SESSION['message']="You have been logged out";

        // Logout of CAS
        phpCAS::client(CAS_VERSION_2_0, $settings['cas_domain'], (int)$settings['cas_port'], $settings['cas_dir']);
        phpCAS::logoutWithRedirectService($settings['cas_redirect']);

    }
}

?>
