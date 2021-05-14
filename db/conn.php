<?php


function get_connection()
{
    $servername = "localhost";
    $username = "root";
    $password = "123";
    $database = "xkcd";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}
