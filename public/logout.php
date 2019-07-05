<?php
    include($_SERVER['DOCUMENT_ROOT']."/rack-test/server-directives.php");
    include($sourceRoot."/app/sessions.php");

    $userInstance = new PotatoSession;
    $userInstance::sessionKill();

?>