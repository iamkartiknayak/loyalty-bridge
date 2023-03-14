<?php

    include "./connect_db.php";

    // customer_count
    $client_count_result = "SELECT * FROM $table_name";
    $clients_count = mysqli_num_rows(mysqli_query($connection, $client_count_result));
    // echo "$clients_count";


    // total_transaction_amount
    $query = "SELECT IFNULL(SUM(total_transaction_amount), 0) AS net_transaction_amount from $table_name";
    $stmt = $connection->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();
    $row=mysqli_fetch_assoc($result);

    $net_transaction_amount = $row['net_transaction_amount'];

    
    // total_redemption_amount
    $query = "SELECT IFNULL(SUM(total_redemption_amount), 0) AS net_redemption_amount from $table_name";
    $stmt = $connection->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();
    $row=mysqli_fetch_assoc($result);

    $net_redemption_amount = $row['net_redemption_amount'];

    echo "CC => $clients_count\nTTA => $total_transaction_amount\nTRA => $total_redemption_amount";

    session_start();
    $_SESSION['clients_count'] = $clients_count;
    $_SESSION['net_transaction_amount'] = $net_transaction_amount;
    $_SESSION['net_redemption_amount'] = $net_redemption_amount;
    $_SESSION['business_name'] = "LMS";
    $_SESSION['client_name'] = "Administrator";

?>