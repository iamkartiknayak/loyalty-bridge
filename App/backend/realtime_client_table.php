<!-- This module displays client details in the tabular format from database in realtime -->

<?php

    include "./client_sort_data.php";
    session_start();

    $connection = mysqli_connect("localhost", "root", "", "clients_db");
    $result = mysqli_query($connection, $query);
    if(mysqli_num_rows(mysqli_query($connection, "SELECT * FROM clients_table")) == 0 || mysqli_num_rows($result) == 0) {
        ?>
        <div class="no-data">
            <!-- <img class="no-data-image" src="../img/dashboard/image1.png" alt="Empty Clipboard Image"> -->
            <h1>No data found!</h1>
        </div>
        <?php
    } else{
        $result = mysqli_query($connection, $query);
        ?>
        <table id="genTable">
        <tr>
            <div class="table-heading">
                <th class="data-title"><i class="uil uil-user"></i></i>Client Name</th>
                <th class="data-title"><i class="uil uil-phone"></i></i>Phone Number</th>
                <th class="data-title"><i class="uil uil-shop"></i>Business Name</th>
                <th class="data-title"><i class="uil uil-transaction"></i></i>Net Transaction</th>
                <th class="data-title"><i class="uil uil-gift"></i></i>Net Redemption</th>
                <th class="data-title"><i class="uil uil-calendar-alt"></i>Date of Registration</th>
                <th class="data-title"><i class="uil uil-info-circle"></i>Account Status</th>
            </div>
        </tr>
        <?php
        while($row=mysqli_fetch_assoc($result)){
            ?> 
            <tr>
                <td class=""><i class="uil uil-user"></i><?php echo $row["client_name"]?></td>
                <td class=""><i class="uil uil-phone"></i><?php echo $row["client_phone_number"]?></td>
                <td class=""><i class="uil uil-shop"></i><?php echo $row["client_business_name"]?></td>
                <td class=""><i class="uil uil-transaction"></i><?php echo $row["total_transaction_amount"]?></td>
                <td class=""><i class="uil uil-gift"></i><?php echo $row["total_redemption_amount"]?></td>
                <td class=""><i class="uil uil-calendar-alt"></i><?php echo $row["client_date_of_registration"]?></td>
                <td class="" style="display: flex">
                    <?php
                    if($row['account_status'] == "active") {
                    ?><i class="uil uil-check-circle"></i>
                    <?php
                        echo '<p><a target="myFrame2" href="./backend/update_account_status.php?client_number='.$row["client_phone_number"].'& status=inactive">Active</a></p>';
                    }else{
                    ?><i class="uil uil-times-circle"></i>
                    <?php
                        echo '<p><a target="myFrame2" href="./backend/update_account_status.php?client_number='.$row["client_phone_number"].'& status=active">Inactive</a></p>';
                    }
                    ?>
                </td>
            </tr>
            <?php
            
        }
    }
    
    mysqli_close($connection);
    
    ?>
</table>
