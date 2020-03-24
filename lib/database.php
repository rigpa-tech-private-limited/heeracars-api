<?php
include 'credentials.php';
Class DbConnection{
    function getdbconnect(){
        $conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME) or die("Couldn't connect");
        return $conn;
    }
}
?>
