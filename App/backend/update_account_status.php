<?php

    include "./connect_db.php";

    $client_number = $_GET['client_number'];
    $account_status = $_GET['status'];

    $query = "UPDATE $table_name SET account_status=? WHERE client_phone_number=?";

    $stmt = $connection->prepare($query);
    $stmt->bind_param("ss", $account_status, $client_number);
    $stmt->execute();

?>