<html>

<body>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        Email: <input type="email" name="email">
        <input type="submit">
    </form>

    <?php
    include_once('./scripts/user.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $email = $_POST['email'];
        if (empty($email)) {
            echo "Name is empty";
        } else {
            $res = create_user($email);

            if ($res === 200)
                header('Location: ' . 'email.php');

            else if ($res === 500)
                echo ("<p> Server error occurred </p> ");

            else if ($res === 409)
                echo ("<p> This email id is already registered with us! </p> ");
        }
    }
    ?>

</body>

</html>