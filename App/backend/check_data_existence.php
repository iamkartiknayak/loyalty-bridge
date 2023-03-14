<!-- This module checks if userID(Email or Phone) already exists in the database to prevent redundant registration -->

<?php
    
  include "./connect_db.php";

  if(!empty($_POST["clientEmailID"])) {
    $query = "SELECT * FROM $table_name WHERE client_email=?";

    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $_POST["clientEmailID"]);
    $stmt->execute();

    $result = $stmt->get_result();
    $row_count = mysqli_num_rows($result);

    if($row_count>0) { // Email already registered
      echo "<style>

      #clientEmailID {
        border-bottom-color: red;
      }
        
      #emailExistErrorIcon {
        visibility: visible;
      }
      </style>";

      echo "<script>$('#registerButton').prop('disabled',true);</script>";
    } else { // Email not registered
      echo "<style>

      #clientEmailID {
        border-bottom-color: #151111;
      }

        #emailExistErrorIcon {
        visibility: hidden;
      }
      </style>";

      echo "<script>
        $('#registerButton').prop('disabled',false);
      </script>";
    }
  }

  if(!empty($_POST["clientPhoneNumber"])) {
    $query = "SELECT * FROM $table_name WHERE client_phone_number=?";

    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $_POST["clientPhoneNumber"]);
    $stmt->execute();

    $result = $stmt->get_result();
    $row_count = mysqli_num_rows($result);
    
    if($row_count>0) { // Number already registered
      echo "<style>

      #clientPhoneNumber{
        border-bottom-color: red;
      } 
      
      #numberExistErrorIcon{
        visibility: visible;
      }
      </style>";

      echo "<script>
        $('#registerButton').prop('disabled',true);
      </script>";
    } else { // Number not registered
      echo "<style>
      
      #clientPhoneNumber{
        border-bottom-color: #151111;
      } 
      
      #numberExistErrorIcon{
        visibility: hidden;
      }
      </style>";

      echo "<script>
        $('#registerButton').prop('disabled',false);
      </script>"; 
    }
  }

  mysqli_close($connection);

?>