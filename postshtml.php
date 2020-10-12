<?php

include('db.php');


if (isset($_GET['user_id'])) {

    $res_users = mysqli_query($db, "SELECT * FROM registered_users WHERE id=" . mysqli_real_escape_string($db, $_GET['user_id']));
    $found_user = mysqli_fetch_assoc($res_users);

    if (isset($found_user)) {
?>
        <h2>Posts from <?= $found_user['username'] ?></h2>
        <br>
        <br>
        <hr>
        <?php
        /* $stmt = mysqli_stmt_init($db);
                    $stmt = mysqli_stmt_prepare($stmt, "SELECT * FROM to_do WHERE user_id=?");
                    $stmt = mysqli_stmt_bind_param($stmt, "i", $_GET["user_id"]);
                    $stmt = mysqli_stmt_execute($stmt);
                    $stmt = mysqli_stmt_bind_result($stmt, $) */

        $res = mysqli_query($db, "SELECT * FROM to_do WHERE user_id=" . mysqli_real_escape_string($db, $_GET['user_id']));
        $posts = mysqli_fetch_all($res, MYSQLI_ASSOC);

        foreach ($posts as $p) { ?>
            <div>
                <p class="mainPost"> <?= $p['text'] ?> </p>
                <br>
                <p> <?= $found_user['username'] . ", " . $p['date'] ?> </p>
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