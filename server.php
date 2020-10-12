<?php
session_start();

$username = "";
$email    = "";
$errors = array();

/* $db = mysqli_connect("sql210.epizy.com", "epiz_25509785", "nIRVRsJrsXRu", "epiz_25509785_registry"); */
$db = mysqli_connect("127.0.0.1:3306", "root", "", "user_registry");

// Register the User

if (isset($_POST['reg_user'])) {
    $username = htmlspecialchars(mysqli_real_escape_string($db, $_POST['username']));
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    if (strlen($password) < 8) {
        array_push($errors, "Your Password Must Contain At Least 8 Characters!");
    } elseif (!preg_match("#[0-9]+#", $password)) {
        array_push($errors, "Your Password Must Contain At Least 1 Number!");
    } elseif (!preg_match("#[A-Z]+#", $password)) {
        array_push($errors, "Your Password Must Contain At Least 1 Capital Letter!");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Invalid email format");
    }

    $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) { // if user exists
        if ($user['username'] === $username) {
            array_push($errors, "Username already exists");
        }

        if ($user['email'] === $email) {
            array_push($errors, "email already exists");
        }
    }

    if (count($errors) == 0) {
        $password_enc = password_hash($password, PASSWORD_DEFAULT); //encrypt the password

        $query = "INSERT INTO registered_users (username, email, password) 
  			  VALUES('$username', '$email', '$password_enc')";
        mysqli_query($db, $query);
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are now logged in";
        header('location: index.php');
    }
}

// Log in

if (isset($_POST['login_user'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);


    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    if (count($errors) == 0) {
        /* $sql = "SELECT * FROM epiz_25509785_registry.registered_users WHERE username='$username'"; */
        $sql = "SELECT * FROM registered_users WHERE username='$username'";
        $result = $db->query($sql);
        if ($result->num_rows === 1) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $username;
                $_SESSION['success'] = "You are now logged in";
                header('location: index.php');
            } else {
                array_push($errors, "Wrong username/password combination");
            }
        }
    }
}

// Posting
