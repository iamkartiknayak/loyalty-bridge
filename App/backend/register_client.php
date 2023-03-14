<!-- This module handles registration of the client -->

<?php

    include "./connect_db.php";
        
    $client_count_result = "SELECT * FROM $table_name";
    $client_number = mysqli_num_rows(mysqli_query($connection, $client_count_result)) + 1;

    $client_name =  ucwords($_POST['client_name']);
    $client_email = strtolower($_POST['client_email']);
    $client_password =  $_POST['client_password'];  
    $client_phone_number = $_POST['client_phone_number'];
    $client_business_name = $_POST['client_business_name'];
    $client_db_name = "client{$client_number}_db";
    $client_pincode = $_POST['client_pincode'];
    $client_customer_count = "0";
    $client_date_of_registration = date("Y-m-d");
    $account_status = "active";
    
    $password_hash = password_hash($client_password, PASSWORD_DEFAULT);

    $existing_data_names = "";
    $client_email_exists = FALSE;
    $client_number_exists = FALSE;
    
    if(isset($client_name) and isset($client_email) and  isset($client_password) and isset($client_phone_number)
            and isset($client_business_name) and isset($client_pincode)){
            $data_exists = clientDataExists();
            if (!$data_exists){
                $query = "INSERT INTO $table_name (
                    client_number,
                    client_name,
                    client_email,
                    client_password,
                    client_phone_number,
                    client_business_name,
                    client_db_name,
                    client_pincode,
                    client_customer_count,
                    client_date_of_registration,
                    account_status
                ) VALUES (
                    ?,?,
                    ?,?,
                    ?,?,
                    ?,?,
                    ?,?,?
                )";

                $stmt = $connection->prepare($query);
                $stmt->bind_param("issssssiiss",
                $client_number,
                $client_name,
                $client_email,
                $password_hash,
                $client_phone_number,
                $client_business_name,
                $client_db_name,
                $client_pincode,
                $client_customer_count,
                $client_date_of_registration,
                $account_status
                );

                $result = $stmt->execute();
                if($result){ // Client registered successfully
                    createClientDatabase();
                    session_start();
                    $_SESSION['register_status'] = "success";
                    header("Location: ../login_register.html");
                    
                } else{
                    echo "\nSomething went wrong!".mysqli_error($connection);
                }
            } else { // User data already exist
                session_start();
                $_SESSION['register_status'] = "failed";
                $_SESSION['register_error_message'] = $existing_data_names;
                passEnteredDetails();
            }
        } else { // If some field are not entered
            session_start();
            $_SESSION['register_status'] = "failed";
            $_SESSION['register_error_message'] = "Enter all fields";
            passEnteredDetails();
    }

    mysqli_close($connection);


    function passEnteredDetails(){
        global $client_name, $client_business_name, $client_pincode, $client_email, 
                $client_phone_number, $client_email_exists, $client_number_exists;

        $_SESSION['register_client_name'] = $client_name;
        $_SESSION['register_client_business_name'] = $client_business_name;
        $_SESSION['register_client_pincode'] = $client_pincode;

        if($client_email_exists)
            $_SESSION['register_client_email'] = "";
        else
            $_SESSION['register_client_email'] = $client_email;

        if($client_number_exists)
            $_SESSION['register_client_number'] = "";
        else
            $_SESSION['register_client_number'] = $client_phone_number;

        header("Location: ../login_register.html");
        exit();
    }


    function createClientDatabase(){
        global $client_db_name, $server_name, $user_name, $password;

        $database_created = FALSE;

        $connection = mysqli_connect($server_name, $user_name, $password);
        if($connection){
            $query = "CREATE DATABASE $client_db_name";

            $result = mysqli_query($connection, $query);
            if($result) {
                $database_created  = TRUE;
            } else {
                echo "ERROR creating DB : " . mysqli_error($connection);
            }

            mysqli_close($connection);

        } else {
            echo "ERROR: Could not connect : " . mysqli_connect_error();
        }

        if($database_created){
            $connection = mysqli_connect($server_name, $user_name, $password, $client_db_name);

            if($connection){
                $customer_table = "CREATE TABLE customer_details(
                    phone_number VARCHAR(10) NOT NULL PRIMARY KEY,
                    total_loyalty_points INT NOT NULL,
                    total_purchase_sum INT NOT NULL,
                    total_redemption_sum INT NOT NULL
                ) ENGINE=INNODB";

                $transaction_table = "CREATE TABLE transactions(
                    transaction_number INT NOT NULL,
                    customer_phone_number VARCHAR(10) NOT NULL,
                    purchase_sum INT NOT NULL,
                    loyalty_points VARCHAR(10) NOT NULL,
                    date_of_purchase DATE NOT NULL,
                    FOREIGN KEY (customer_phone_number) 
                    REFERENCES customer_details(phone_number)
                )ENGINE=INNODB";


                $customer_table_created = mysqli_query($connection, $customer_table);
                $transaction_table_created = mysqli_query($connection, $transaction_table);
                mysqli_close($connection);
                
            } else {
                echo "ERROR: Could not connect : " . mysqli_connect_error();
            }            
        }
    }


    function clientDataExists(){
        global $client_phone_number, $client_email, $existing_data_names,
                $client_number_exists, $client_email_exists;

        $client_number_exists = clientPhoneNumberExists();
        $client_email_exists = clientEmailExists();

        if($client_number_exists or $client_email_exists){
            if($client_number_exists)
                $existing_data_names  .= "$client_phone_number";
            
            if($client_email_exists)
                $existing_data_names .= " $client_email";

            $existing_data_names .= " exist!";
            return TRUE;
        } 
        return FALSE;
    }


    function clientEmailExists(){
        global $connection, $table_name, $client_email;

        $query = "SELECT * from $table_name where client_email=?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("s",$client_email);
        $stmt->execute();

        $result = $stmt->get_result();
        if(mysqli_num_rows($result) > 0) 
            return TRUE;
    
        return FALSE;
    }


    function clientPhoneNumberExists(){
        global $connection, $table_name, $client_phone_number;

        $query = "SELECT * from $table_name where client_phone_number=?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("s", $client_phone_number);
        $stmt->execute();

        $result = $stmt->get_result();
        if(mysqli_num_rows($result) > 0) 
            return TRUE;
    
        return FALSE;
    }

?>