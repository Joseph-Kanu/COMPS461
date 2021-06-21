<?php

//variables for connecting to the database
define("HOST", "localhost");
define("USER", "root");
define("PASS", "usbw");
define("DB", "userdb_32708");

//connection object used for interacting with the database
$con = new mysqli(HOST, USER, PASS, DB);

//check if the connection was successful
if (!$con) {
    echo '<h1 class="text-center">Database Connection Failed</h1>';
    die();
}