<?php
        $server_name = "localhost";
        $user_name = "root";
        $password = "";
        $database_name = "clients_db";

        $database_created = FALSE;

        $connection = mysqli_connect($server_name, $user_name, $password);
        if($connection){
            $query = "CREATE DATABASE $database_name";

            $result = mysqli_query($connection, $query);
            if($result) {
                echo "Database created!<br>";
                $database_created  = TRUE;
            } else {
                echo "ERROR creating DB : " . mysqli_error($connection);
            }
            mysqli_close($connection);
        } else {
            echo "ERROR: Could not connect : " . mysqli_connect_error();
        }


        if($database_created){
            $table_name = "clients_table";
            $connection = mysqli_connect($server_name, $user_name, $password, $database_name);

            if($connection){
                $query = "CREATE TABLE $table_name(
                    client_number INT NOT NULL PRIMARY KEY,
                    client_name VARCHAR(32) NOT NULL,
                    client_email VARCHAR(32) NOT NULL,
                    client_password VARCHAR(255) NOT NULL,
                    client_phone_number VARCHAR(10) NOT NULL,
                    client_business_name VARCHAR(32) NOT NULL,
                    client_db_name VARCHAR(16) NOT NULL,
                    client_pincode INT NOT NULL,
                    client_address VARCHAR(32) NOT NULL,
                    client_customer_count INT NOT NULL,
                    client_date_of_registration DATE NOT NULL,
                    credit_percentage DECIMAL(5, 2) NOT NULL,
                    debit_percentage DECIMAL(5, 2) NOT NULL,
                    loyalty_coin_value DECIMAL(5, 2) NOT NULL,
                    total_transaction_amount INT NOT NULL,
                    total_redemption_amount INT NOT NULL,
                    account_status VARCHAR(10) NOT NULL
                );";
    
                $result = mysqli_query($connection, $query);
                if($result) {
                    echo "Table created!<br>";
                } else {
                    echo "ERROR creating Table : " . mysqli_error($connection);
                }
                mysqli_close($connection);
            } else {
                echo "ERROR: Could not connect : ".mysqli_connect_error();
            }            
        }
?>