<?php

/* $servername = "127.0.0.1:3306";
$username = "root";
$password = "";
$db_name = "user_registry"; */

// Online credencials

$servername = "sql210.epizy.com";
$username = "epiz_25509785";
$password = "nIRVRsJrsXRu";
$db_name = "epiz_25509785_registry";

$db = mysqli_connect($servername, $username, $password, $db_name);

if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$_SESSION['start'] = 0;
$_SESSION['count'] = 0;
