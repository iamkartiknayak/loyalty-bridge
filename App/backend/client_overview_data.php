<!-- This module retrieves and updates transaction values of a client in session -->

<?php

    $client_db_name = $_SESSION['db_name'];
    $connection = mysqli_connect("localhost", "root", "", $client_db_name);
    
    $query = "SELECT IFNULL(SUM(total_purchase_sum), 0) AS net_purchase_sum from customer_details";
    $stmt = $connection->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();
    $row=mysqli_fetch_assoc($result);

    $total_transaction_amount = $row['net_purchase_sum'];

    $query = "SELECT IFNULL(SUM(total_redemption_sum), 0) AS net_redemption_sum from customer_details";
    $stmt = $connection->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();
    $row=mysqli_fetch_assoc($result);

    $total_redemption_amount = $row['net_redemption_sum'];

    $_SESSION['total_transaction_amount'] = $total_transaction_amount;
    $_SESSION['total_redemption_amount'] = $total_redemption_amount;

    mysqli_close($connection);

?>