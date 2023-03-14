<!-- This module handles the transactions made by the client -->

<?php

    if (isset($_POST["customer_phone_number"]) and isset($_POST["customer_purchase_sum"])){
        session_start();
        $newCustomer = false;
        
        $client_database_name = $_SESSION['db_name'];
        $connection = mysqli_connect("localhost", "root", "", $client_database_name);
        
        if($connection === false){
            die("ERROR: Could not connect" . mysqli_connect_error());
        }

        $customer_phone_number = $_POST['customer_phone_number'];
        $customer_purchase_sum = $_POST['customer_purchase_sum'];
        $customer_date_of_purchase=date("Y/m/d");

        // Add transaction details to customer_details table
        $loyalty_coin_value = $_SESSION['loyalty_coin_value'];
        $credit_percentage = $_SESSION['credit_percentage'];
        $debit_percentage = $_SESSION['debit_percentage'];
        $transactionType = $_COOKIE["transactionType"];


        if($transactionType == "credit"){ // Credit Points Logic
            if($customer_purchase_sum >= 500){
                $customer_loyalty_points = $customer_purchase_sum * ($credit_percentage / 100); // CPS = ₹500 | CP = 10% | LCC = 0.10 * 500 => 50 | LCV = ₹0.50 || TLCV = LCC * LCV => 50 * 0.5 => ₹25
            } else{
                $customer_loyalty_points = 0;
            }
    
            $query = "SELECT * FROM customer_details WHERE phone_number = '$customer_phone_number'";
            $result = mysqli_query($connection, $query);
            $row_count = mysqli_num_rows($result);
    
            if($row_count == 0){ // New customer
                $insert_customer_details = "INSERT INTO customer_details(
                    phone_number,
                    total_loyalty_points,
                    total_purchase_sum
                ) VALUES (
                    '$customer_phone_number',
                    '$customer_loyalty_points',
                    '$customer_purchase_sum'
                );";
    
                mysqli_query($connection, $insert_customer_details);    
                $newCustomer = true;
    
            } else if ($row_count == 1){ //Existing customer
                while($row=mysqli_fetch_assoc($result)){
                    $updated_loyalty_points = abs($row['total_loyalty_points']) + $customer_loyalty_points;
                    $updated_total_purchase_sum = abs($row['total_purchase_sum']) + $customer_purchase_sum;
    
                    $update_loyalty_points = "UPDATE customer_details SET total_loyalty_points='$updated_loyalty_points' WHERE phone_number = '$customer_phone_number'";
                    $update_total_purchase_sum = "UPDATE customer_details SET total_purchase_sum='$updated_total_purchase_sum' WHERE phone_number = '$customer_phone_number'";
                    
                    mysqli_query($connection ,$update_loyalty_points);
                    mysqli_query($connection ,$update_total_purchase_sum);
                }
            }
                
            // Add transaction details to transactions table
            $transaction_query = "SELECT * FROM transactions";
            $transaction_number= mysqli_num_rows(mysqli_query($connection, $transaction_query)) + 1;
            $customer_loyalty_points = "+ $customer_loyalty_points";
    
            $insert_transaction_details = "INSERT INTO transactions(
                transaction_number,
                customer_phone_number,
                purchase_sum,
                loyalty_points,
                date_of_purchase
                ) VALUES (
                    '$transaction_number',
                    '$customer_phone_number',
                    '$customer_purchase_sum',
                    '$customer_loyalty_points',
                    '$customer_date_of_purchase'
                );";
    
            mysqli_query($connection, $insert_transaction_details);
            mysqli_close($connection);    
    
            if($newCustomer == true){
                include "./connect_db.php";
                $customer_count_result = mysqli_query($connection, "SELECT client_customer_count FROM clients_table WHERE client_db_name = '$client_database_name'");
                $customer_count = mysqli_fetch_assoc($customer_count_result)['client_customer_count'] + 1;
    
                $_SESSION['client_customer_count'] += 1;
                $_COOKIE['customerCount'] = $_SESSION['client_customer_count'];
                $update_customer_count = "UPDATE clients_table SET client_customer_count='$customer_count' WHERE client_db_name = '$client_database_name'";
                mysqli_query($connection, $update_customer_count);
                mysqli_close($connection);
            }

            // Update Credit transaction in clients_table
            // Update total_transaction_amount
            include "./connect_db.php";

            global $client_database_name;

            $query = "SELECT total_transaction_amount FROM clients_table WHERE client_db_name=?";
            $stmt = $connection->prepare($query);
            $stmt->bind_param("s", $client_database_name);
            $stmt->execute();

            $result =  $stmt->get_result();
            $row = mysqli_fetch_assoc($result);
            $updated_total_transaction_amount = $row['total_transaction_amount'] + $customer_purchase_sum;

            $query = "UPDATE clients_table SET total_transaction_amount=? WHERE client_db_name=?";
            $stmt = $connection->prepare($query);
            $stmt->bind_param("is",$updated_total_transaction_amount, $client_database_name);
            $stmt->execute();

            mysqli_close($connection);

        } else { // Redemption Logic
            $query = "SELECT * FROM customer_details WHERE phone_number = '$customer_phone_number'";
            $result = mysqli_query($connection, $query);
            $row_count = mysqli_num_rows($result);
            
            if($row_count == 1){ //Existing customer
                while($row=mysqli_fetch_assoc($result)){
                    $retrieved_loyalty_points = ($debit_percentage / 100) * $customer_purchase_sum;
                    $updated_loyalty_points = abs($row['total_loyalty_points']) - $retrieved_loyalty_points;
                    $redemption_sum = $row['total_redemption_sum'] + ($retrieved_loyalty_points * $loyalty_coin_value);
                    $discounted_purchase_sum =   $customer_purchase_sum - ($retrieved_loyalty_points * $loyalty_coin_value);
                    $updated_total_purchase_sum = abs($row['total_purchase_sum']) + $discounted_purchase_sum;
    
                    $update_loyalty_points = "UPDATE customer_details SET total_loyalty_points='$updated_loyalty_points' WHERE phone_number = '$customer_phone_number'";
                    $update_total_purchase_sum = "UPDATE customer_details SET total_purchase_sum='$updated_total_purchase_sum' WHERE phone_number = '$customer_phone_number'";
                    $update_redemption_sum = "UPDATE customer_details SET total_redemption_sum='$redemption_sum' WHERE phone_number = '$customer_phone_number'";
                    
                    mysqli_query($connection ,$update_loyalty_points);
                    mysqli_query($connection ,$update_total_purchase_sum);
                    mysqli_query($connection ,$update_redemption_sum);
                }
            }

            $transaction_query = "SELECT * FROM transactions";
            $transaction_number= mysqli_num_rows(mysqli_query($connection, $transaction_query)) + 1;
            $retrieved_loyalty_points_with_sign = "- $retrieved_loyalty_points";
    
            $insert_transaction_details = "INSERT INTO transactions(
                transaction_number,
                customer_phone_number,
                purchase_sum,
                loyalty_points,
                date_of_purchase
                ) VALUES (
                    '$transaction_number',
                    '$customer_phone_number',
                    '$discounted_purchase_sum',
                    '$retrieved_loyalty_points_with_sign',
                    '$customer_date_of_purchase'
                );";
    
            mysqli_query($connection, $insert_transaction_details);
            mysqli_close($connection);


            // Update Redemption transaction in clients_table
            // Update total_transaction_amount
            include "./connect_db.php";

            global $client_database_name;
            $query = "SELECT total_transaction_amount FROM clients_table WHERE client_db_name=?";
            $stmt = $connection->prepare($query);
            $stmt->bind_param("s", $client_database_name);
            $stmt->execute();

            $result =  $stmt->get_result();
            $row = mysqli_fetch_assoc($result);
            $updated_total_transaction_amount = $row['total_transaction_amount'] + $discounted_purchase_sum;

            $query = "UPDATE clients_table SET total_transaction_amount=? WHERE client_db_name=?";
            $stmt = $connection->prepare($query);
            $stmt->bind_param("is",$updated_total_transaction_amount, $client_database_name);
            $stmt->execute();

            // Update total_redemption_amount
            $query = "SELECT total_redemption_amount FROM clients_table WHERE client_db_name=?";
            $stmt = $connection->prepare($query);
            $stmt->bind_param("s", $client_database_name);
            $stmt->execute();

            $result =  $stmt->get_result();
            $row = mysqli_fetch_assoc($result);
            $updated_total_redemption_amount = $row['total_redemption_amount'] + ($retrieved_loyalty_points * $loyalty_coin_value);

            $query = "UPDATE clients_table SET total_redemption_amount=? WHERE client_db_name=?";
            $stmt = $connection->prepare($query);
            $stmt->bind_param("is",$updated_total_redemption_amount, $client_database_name);
            $stmt->execute();

            mysqli_close($connection);
        }

        include "./client_overview_data.php";
    }

?>