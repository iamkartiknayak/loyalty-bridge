<!-- This module handles sorting data in customer table W.R.T => (Date, Coins, Amount) in ascending, descending order and also enables realtime customer search -->

<?php

    $sortCategory = $_COOKIE["sortCategory"];
    $sortType = $_COOKIE["sortType"];
    $searchNumber = $_COOKIE["searchNumber"];


    if($sortType == "descending"){
        $query = "SELECT customer_phone_number, total_loyalty_points, loyalty_points, purchase_sum, date_of_purchase FROM customer_details 
        INNER JOIN transactions ON customer_details.phone_number = transactions.customer_phone_number WHERE customer_phone_number LIKE '$searchNumber%' ORDER BY $sortCategory DESC";
    }
    else{
        $query = "SELECT customer_phone_number, total_loyalty_points, loyalty_points,  purchase_sum, date_of_purchase FROM customer_details 
        INNER JOIN transactions ON customer_details.phone_number = transactions.customer_phone_number WHERE customer_phone_number LIKE '$searchNumber%' ORDER BY $sortCategory";
    }
?>