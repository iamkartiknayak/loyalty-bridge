<!-- This module handles the logout of client session -->

<?php

    session_start();
    session_unset();
    session_destroy();

    header("location: ../login_register.html");
    exit();
    
?>