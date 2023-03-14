<!-- This module displays customer details in the tabular format from database in realtime -->

<?php

    include "./customer_sort_data.php";
    session_start();
    
    if(isset($_SESSION["db_name"])){
        $connection = mysqli_connect("localhost", "root", "", $_SESSION["db_name"]);
        $result = mysqli_query($connection, $query);
        if(mysqli_num_rows(mysqli_query($connection, "SELECT * FROM customer_details")) == 0 or 
            mysqli_num_rows(mysqli_query($connection, "SELECT * FROM transactions")) == 0  or mysqli_num_rows($result) == 0) {
            ?>
            <div class="no-data">
                <!-- <img class="no-data-image" src="../img/dashboard/image1.png" alt="Empty Clipboard Image"> -->
                <h1>No data found!</h1>
            </div>
            <?php
        } else{
            // $result = mysqli_query($connection, $query);
            ?>
            <table id="genTable">
            <tr>
                <div class="table-heading">
                    <th class="data-title"><i class="uil uil-phone"></i>Phone Number</th>
                    <th class="data-title"><i class="uil uil-coins"></i>Net Loyalty Points</th>
                    <th class="data-title"><i class="uil uil-bitcoin-circle"></i>Loyalty Points</th>
                    <th class="data-title"><i class="uil uil-rupee-sign"></i>Purchase Amount</th>
                    <th class="data-title"><i class="uil uil-calendar-alt"></i>Date of Purchase</th>
                </div>
            </tr>
            <?php
            while($row=mysqli_fetch_assoc($result)){
                ?> 
                <tr>
                    <td class="data-list"><i class="uil uil-phone"></i>+91 <?php echo $row["customer_phone_number"]?></td>
                    <td class=""><i class="uil uil-coins"></i><?php echo $row["total_loyalty_points"]?></td>
                    <td class=""><i class="uil uil-bitcoin-circle"></i><?php echo $row["loyalty_points"]?></td>
                    <td class=""><i class="uil uil-rupee-sign"></i><?php echo $row["purchase_sum"]?></td>
                    <td class=""><i class="uil uil-calendar-alt"></i><?php echo $row["date_of_purchase"]?></td>
                </tr>
                <?php
            }
        }
        
        mysqli_close($connection);
    }
    
    ?>
</table>
