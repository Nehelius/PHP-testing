<?php include('server.php') ?>
<?php

$user_res = mysqli_query($db, "SELECT * FROM registered_users");

$users = mysqli_fetch_all($user_res, MYSQLI_ASSOC);

if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>User Posts</title>
</head>

<body>
    <div class="login">
        <?php if (isset($_SESSION['username'])) : ?>
            <p>Welcome <strong><?= $_SESSION['username']; ?></strong></p>
            <p> <a href="index.php?logout='1'" style="color: red;">logout</a> </p>
            <p> <a href="index.php">Back to your home page</a> </p>
        <?php endif ?>
    </div>
    <div class="container">
        <form method="get" class="userList">
            <h3>Some other users:</h3>
            <ul>
                <?php
                foreach ($users as $user) {
                    if ($user['username'] != $_SESSION['username']) : ?>
                        <li><button name="user_id" value="<?= $user['id'] ?>" class="userBut"><?= $user['username'] ?></button></li>
                <?php endif;
                }
                ?>

            </ul>
        </form>
        <div class="postArea">
            <?php

            if (isset($_GET['user_id'])) {
                foreach ($users as $u) {
                    if ($u['id'] === $_GET['user_id']) {
                        $found_user = $u;
                        break;
                    }
                }

                if (isset($found_user)) {
            ?>
                    <h2>Posts from <?= $u['username'] ?></h2>
                    <br>
                    <br>
                    <hr>
                    <?php
                    /* $stmt = mysqli_stmt_init($db);
                    $stmt = mysqli_stmt_prepare($stmt, "SELECT * FROM to_do WHERE user_id=?");
                    $stmt = mysqli_stmt_bind_param($stmt, "i", $_GET["user_id"]);
                    $stmt = mysqli_stmt_execute($stmt);
                    $stmt = mysqli_stmt_bind_result($stmt, $) */

                    $res = mysqli_query($db, "SELECT * FROM to_do WHERE user_id='" . mysqli_real_escape_string($db, $_GET['user_id']) . "'");
                    $posts = mysqli_fetch_all($res, MYSQLI_ASSOC);

                    foreach ($posts as $p) { ?>
                        <div>
                            <p class="mainPost"> <?= $p['text'] ?> </p>
                            <br>
                            <p> <?= $u['username'] . ", " . $p['date'] ?> </p>
                            <br>
                        </div>
                        <hr>
                    <?php
                    }
                } else {
                    ?>
                    <h2>No user found</h2>
            <?php
                }
            }
            ?>
        </div>
    </div>
</body>

</html>