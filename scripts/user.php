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
            return 409;

        $stmt->close();
    } else {
        return 500;
    }

    $conn->close();
    return 200;
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

        if ($stmt->num_rows == 1) {

            // update the verified status
            $update = "UPDATE user set verified = true WHERE email = ?";
            if ($upt_stmt = $conn->prepare($update)) {

                $upt_stmt->bind_param("s", $email);
                $upt_stmt->execute();

                // dupl key error for email [1062]
                if ($upt_stmt->errno === 1062)
                    return "User with same email exists";

                $upt_stmt->close();
            }

            return "Token validated";
        }

        $stmt->close();
    } else {
        return "Server error occurred";
    }

    $conn->close();
    return "Token is invalid";
}

function send_verification_mail($email, $token)
{
    $header = "From: noreply@example.com\r\n";
    $header = "MIME-Version: 1.0\r\n";
    $header = "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $header = "X-Priority: 1\r\n";
    $to = 'atulpatare99@gmail.com';
    $subject = "test message";
    $message = "Another test";

    $res = mail($to, $subject, $message, $header);
    echo ($res);
}
