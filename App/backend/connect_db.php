<!-- This module connects to the clients_table -->

<?php

    $server_name = "localhost";
    $user_name = "root";
    $password = "";
    $database_name = "clients_db";
    $table_name = "clients_table";

    try {
        $connection = mysqli_connect($server_name, $user_name, $password, $database_name);
    } catch(Exception $e) {
        header("Location: ../server_down.html");
        exit;
    }

?>