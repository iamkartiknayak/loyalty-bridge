<!-- This module updates business rules of client -->

<?php

    include "./connect_db.php";
    session_start();

    $loyalty_coin_value = $_POST['loyalty_coin_value'];
    $credit_percentage = $_POST['credit_percentage'];
    $debit_percentage = $_POST['debit_percentage'];
    $current_password = $_POST['conversion_page_current_password'];
    $client_db_name = $_SESSION['db_name'];
    
    $query = "SELECT * FROM $table_name WHERE client_db_name=?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $client_db_name);
    $stmt->execute();

    $result = $stmt->get_result();
    $row_count = mysqli_num_rows($result);

    if($row_count == 1){
            $row=mysqli_fetch_assoc($result);
            if(password_verify($current_password, $row['client_password']))
            {
                updateBusinessInfo();
            }else{ // Invalid password
                $_SESSION['business_update_status'] = "failed";
            }
            header("Location: ../dashboard.html");

        // }
    } else{ // Invalid username
        session_start();
        $_SESSION['login_status'] = "failed";
        $_SESSION['login_error_message'] = "Invalid userID or password!";
        header("Location: ../login_register.html");
    }

    mysqli_close($connection);


    function updateBusinessInfo(){
        global $loyalty_coin_value, $credit_percentage, $debit_percentage;

        if($loyalty_coin_value != $_COOKIE['loyalty_coin_value']){
            updateValue("loyalty_coin_value", $loyalty_coin_value);
            $_SESSION['loyalty_coin_value'] = $loyalty_coin_value;
        }

        if($credit_percentage != $_COOKIE['credit_percentage']){
            updateValue("credit_percentage", $credit_percentage);
            $_SESSION['credit_percentage'] = $credit_percentage;
        }

        if($debit_percentage != $_COOKIE['debit_percentage']){
            updateValue("debit_percentage", $debit_percentage);
            $_SESSION['debit_percentage'] = $debit_percentage;
        }
    }


    function updateValue($field_name, $value){
        global $client_db_name, $connection, $table_name;

        if($field_name == "client_pincode"){
            $data_type = "i";
        } else {
            $data_type = "s";
        }

        $query = "UPDATE $table_name SET $field_name = ? WHERE client_db_name='$client_db_name'";

        $stmt = $connection->prepare($query);
        $stmt->bind_param($data_type, $value);
        $stmt->execute();

        $result = $stmt->get_result();
        $_SESSION['business_update_status'] = "success";
    }

?>