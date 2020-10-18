<?php

include('db.php');
include('server.php');

if (isset($_GET['user_id'])) {

    $sql_escape = mysqli_real_escape_string($db, $_GET['user_id']);
    $sql_count = mysqli_query($db, "SELECT COUNT(user_id) as cnt FROM to_do WHERE user_id=$sql_escape");
    $count_posts = mysqli_fetch_array($sql_count, MYSQLI_ASSOC);
    $_SESSION['count'] = $count_posts['cnt'];

    $res_users = mysqli_query($db, "SELECT * FROM registered_users WHERE id=$sql_escape");
    $found_user = mysqli_fetch_assoc($res_users);

    if (isset($found_user)) {
        $_SESSION['count'] = $_SESSION['count'] - 5;
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
        $sql_escape = mysqli_real_escape_string($db, $_GET['user_id']);

        $_SESSION['start'] = 0;

        $res = mysqli_query($db, "SELECT * FROM to_do WHERE user_id= $sql_escape ORDER BY id ASC LIMIT {$_SESSION['start']}, 5");
        $posts = mysqli_fetch_all($res, MYSQLI_ASSOC);

        $_SESSION['start'] = $_SESSION['start'] + 5;

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

        if ($_SESSION['count'] >= 1) {
        ?>
            <br>
            <button value="<?= $sql_escape ?>" name="load_more" class="load-more-button">Load more (<?= $_SESSION['count'] ?> remaining)</button>
        <?php
        }

        /* if (isset($_POST['load_more'])) {
        ?>
            <h2>More posts from <?= $found_user['username'] ?></h2>
            <br>
            <br>
            <hr>
            <?php
            $start = $start + 5;
            $sql_escape = mysqli_real_escape_string($db, $_GET['user_id']);
            $res = mysqli_query($db, "SELECT * FROM to_do WHERE user_id=$sql_escape LIMIT $start, $limit");
            $moreposts = mysqli_fetch_all($res, MYSQLI_ASSOC);

            foreach ($moreposts as $mp) {
            ?>
                <div>
                    <p class="mainPost"> <?= $mp['text'] ?> </p>
                    <br>
                    <p> <?= $found_user['username'] . ", " . $mp['date'] ?> </p>
                    <br>
                </div>
                <hr>
        <?php
            }
        } */
    } else {
        ?>
        <h2>No user found</h2>
<?php
    }
}
?>