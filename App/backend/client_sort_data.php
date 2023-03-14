<!-- This module handles sorting data in clients table W.R.T => (Date, Amount) in ascending, descending order and also enables realtime client search -->

<?php

    $sortCategory = $_COOKIE["sortCategory"];
    $sortType = $_COOKIE["sortType"];
    $searchTerm = $_COOKIE["searchTerm"];

    if(is_numeric($searchTerm)){
        $comparator = "client_phone_number";
    } else {
        $comparator = "client_business_name";
    }

    if($sortType == "descending"){
        $query = "SELECT client_number, client_name, client_phone_number, client_business_name, client_date_of_registration, total_transaction_amount, total_redemption_amount, account_status FROM clients_table WHERE $comparator LIKE '$searchTerm%' ORDER BY $sortCategory DESC";
    }
    else{
        $query = "SELECT client_number, client_name, client_phone_number, client_business_name, client_date_of_registration, total_transaction_amount, total_redemption_amount, account_status FROM clients_table WHERE $comparator LIKE '$searchTerm%' ORDER BY $sortCategory";
    }
?>