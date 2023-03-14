<!-- This module handles updating account information of the client -->

<?php

    include "./connect_db.php";
    session_start();


    $client_name = $_POST['client_name'];
    $client_email = strtolower($_POST['client_email']);
    $client_number = $_POST['client_number'];
    $client_business_name = $_POST['client_business_name'];
    $client_address = ucfirst($_POST['client_address']);
    $client_pincode = $_POST['client_pincode'];
    $client_new_password = $_POST['client_new_password'];
    $client_new_password_confirm = $_POST['client_new_password_confirm'];
    $current_password = $_POST['current_password'];
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
            updateClientInfo();
        }else{ // Invalid password
            $_SESSION['account_update_status'] = "failed";
            $_SESSION['invalid_password'] = "true";
        }
        header("Location: ../dashboard.html");
    } else{ // Invalid username
        session_start();
        $_SESSION['login_status'] = "failed";
        $_SESSION['login_error_message'] = "Invalid userID or password!";
        header("Location: ../login_register.html");
    }

    mysqli_close($connection);

    
    function updateClientInfo(){
        global $client_name, $client_email, $client_number, $client_business_name,
               $client_address, $client_pincode, $client_new_password, $client_new_password_confirm;
        if($client_name != $_COOKIE['default_client_name']){
            updateValue("client_name", $client_name);
            $_SESSION['client_name'] = $client_name;
        }

        if($client_email != $_COOKIE['default_client_email'] and !dataExist("client_email", $client_email)){
            updateValue("client_email", $client_email);
            $_SESSION['client_email'] = $client_email;
        }

        if($client_number != $_COOKIE['default_client_number'] and !dataExist("client_phone_number", $client_number)){
            updateValue("client_phone_number", $client_number);
            $_SESSION['client_number'] = $client_number;
        }

        if($client_business_name != $_COOKIE['default_business_name']){
            updateValue("client_business_name", $client_business_name);
            $_SESSION['business_name'] = $client_business_name;
        }

        if($client_address != "" and $client_address != $_COOKIE['default_client_address']){
            updateValue("client_address", $client_address);
            $_SESSION['client_address'] = $client_address;
        }

        if($client_pincode != $_COOKIE['default_client_pincode']){
            updateValue("client_pincode", $client_pincode);
            $_SESSION['client_pincode'] = $client_pincode;
        }

        if($client_new_password != "" and $client_new_password == $client_new_password_confirm){
            updatePassword($client_new_password);
        }
    }

    function dataExist($field_name, $value){
        global $client_db_name, $connection, $table_name;

        $query = "SELECT * FROM $table_name WHERE $field_name=?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("s", $value);
        $stmt->execute();

        $result = $stmt->get_result();
        $row_count = mysqli_num_rows($result);

        if($row_count == 1)
            return true;
        
        return false;
    }


    function updatePassword($client_password){
        echo "Inside change password";
        $password_hash = password_hash($client_password, PASSWORD_DEFAULT);

        updateValue("client_password", $password_hash);
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
        $_SESSION['account_update_status'] = "success";
    }

?>