<?php

include_once('./db/conn.php');


function create_user($email)
{
    $conn = get_connection();

    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

    $token = gen_token();
    $query = "INSERT INTO user(email, token) VALUES(?, ?)";
    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();

        // dupl key error for email [1062]
        if ($stmt->errno === 1062)
            return "User with same email exists";

        $stmt->close();
    } else {
        return "Server error occurred";
    }

    $conn->close();
    return "User added successfully";
}

function gen_token()
{
    $TOKEN_MIN = 24564;
    $TOKEN_MAX =  98996;

    return random_int($TOKEN_MIN, $TOKEN_MAX);
}

function check_token($email, $token)
{
    $conn = get_connection();

    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

    $query = "SELECT * FROM user WHERE email = ? AND token = ?";
    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1)
            return "Token validated";

        $stmt->close();
    } else {
        return "Server error occurred";
    }

    $conn->close();
    return "Token is invalid";
}

function send_verification_mail($email, $token)
{
    $to = "atulpatare99@gmail.com";
    $subject = "My subject";
    $txt = "Hello world!";

    mail($to, $subject, $txt);
}
