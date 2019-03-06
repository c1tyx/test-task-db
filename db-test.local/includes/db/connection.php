<?php
require("constants.php");

$link = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if(mysqli_connect_errno()){
    echo 'error connect';
}