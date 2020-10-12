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
                        <li><button name="user_id" value="<?= $user['id'] ?>" class="user-button"><?= $user['username'] ?></button></li>
                <?php endif;
                }
                ?>

            </ul>
        </form>
        <div class="postArea">
        </div>
    </div>
    <script>
        const buttons = document.querySelectorAll('.user-button');
        const postArea = document.querySelector('.postArea');

        function handleClick(e) {
            e.preventDefault();

            const userId = e.target.value;

            fetch("/registration/postshtml.php?user_id=" + userId)
                .then(res => res.text())
                .then(html => {
                    postArea.innerHTML = html
                });
        }

        buttons.forEach(button => {
            button.addEventListener('click', handleClick);
        })
    </script>
</body>

</html>