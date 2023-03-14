<!-- This module handles the client session login -->

<?php

    include "./connect_db.php";

    $client_ID = $_POST['client_ID'];
    $client_password =  $_POST['client_password'];

    if($client_ID == "lmsudupi22@gmail.com" && $client_password == "hello123"){
        include "./fetch_admin_dashboard_data.php";
        header("Location: ../admin_dashboard.html");
        exit;
    }

    if(is_numeric($client_ID)){
        $comparator = "client_phone_number";
    } else {
        $comparator = "client_email";
    }

    $query = "SELECT * FROM $table_name WHERE $comparator=?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $client_ID);
    $stmt->execute();

    $result = $stmt->get_result();
    $row_count = mysqli_num_rows($result);

    if($row_count == 1){ // Valid Username
        $row=mysqli_fetch_assoc($result);
        if(password_verify($client_password, $row['client_password']))
        { // Valid Password
            if($row['account_status'] == "active"){
                session_start();
                $_SESSION['client_name'] = $row['client_name'];
                $_SESSION['client_email'] = $row['client_email'];
                $_SESSION['client_number'] = $row['client_phone_number'];
                $_SESSION['business_name'] = $row['client_business_name'];
                $_SESSION['client_address'] = $row['client_address'];
                $_SESSION['client_pincode'] = $row['client_pincode'];
                $_SESSION['client_customer_count'] = $row['client_customer_count'];
                $_SESSION['db_name'] = $row['client_db_name'];
                $_SESSION['credit_percentage'] = $row['credit_percentage'];
                $_SESSION['debit_percentage'] = $row['debit_percentage'];
                $_SESSION['loyalty_coin_value'] = $row['loyalty_coin_value'];
    
                include "./client_overview_data.php";
                header("Location: ../dashboard.html");
            } else {
                session_start();
                $_SESSION['login_status'] = "failed";
                $_SESSION['login_error_message'] = "Account is disabled";
                header("Location: ../login_register.html");
            }
        }else{ // Invalid password
            session_start();
            $_SESSION['login_status'] = "failed";
            $_SESSION['login_error_message'] = "Invalid userID or password!";
            header("Location: ../login_register.html");
        }
    } else{ // Invalid username
        session_start();
        $_SESSION['login_status'] = "failed";
        $_SESSION['login_error_message'] = "Invalid userID or password!";
        header("Location: ../login_register.html");
    }
    
    mysqli_close($connection);

?>