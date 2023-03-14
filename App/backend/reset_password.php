<!-- This module handles resetting client account password -->

<?php

    include './connect_db.php';

    $client_email = $_POST['client_reset_email'];
    $client_phone_number = $_POST['client_reset_phone_number'];

    $query = "SELECT * from $table_name WHERE client_phone_number=? AND client_email=?";
    
    $stmt = $connection->prepare($query);
    $stmt->bind_param("ss", $client_phone_number, $client_email);
    $stmt->execute();

    $result = $stmt->get_result();
    $row_count = mysqli_num_rows($result);

    if($row_count == 1){
        while($row=mysqli_fetch_assoc($result)){
            $generatedPassword = generateRandomPassword();
            $password_hash = password_hash($generatedPassword, PASSWORD_DEFAULT);

            $query = "UPDATE $table_name SET client_password=? WHERE client_phone_number=?";    
            $stmt = $connection->prepare($query);
            $stmt->bind_param("ss", $password_hash, $client_phone_number);
            
            if($stmt->execute()){
                // echo "<br>Password for {$row['client_email']}  has been updated to : $generatedPassword";
                session_start();
                $_SESSION['mailID'] = $row['client_email'];
                $_SESSION['newPassword'] = $generatedPassword;
                header("Location: ../reset_password.html");
            } else {
                echo "Couldn't update the password! Something went wrong";
            }
        }
    } else{ // Invalid user credentials
        session_start();
        $_SESSION['reset_status'] = "failed";
        $_SESSION['reset_error_message'] = "Invalid user credentials!";
        header("Location: ../login_register.html");
    }

    mysqli_close($connection);

    
    function generateRandomPassword(){
        $characters = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789*!@#$%&";
        $generatedPassword = array();
        $characterLength = strlen($characters) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $characterLength);
            $generatedPassword[] = $characters[$n];
        }
        return implode($generatedPassword);
    }

?>