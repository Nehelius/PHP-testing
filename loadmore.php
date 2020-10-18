<?php
include('db.php');
include('server.php');


$res_id = mysqli_real_escape_string($db, $_GET['user_id']);
$res_users = mysqli_query($db, "SELECT * FROM registered_users WHERE id=$res_id");
$found_user = mysqli_fetch_assoc($res_users);

$res = mysqli_query($db, "SELECT * FROM to_do WHERE user_id=$res_id ORDER BY id ASC LIMIT {$_SESSION['start']}, 5");
$moreposts = mysqli_fetch_all($res, MYSQLI_ASSOC);

$sql_count = mysqli_query($db, "SELECT COUNT(user_id) as cnt FROM to_do WHERE user_id=$res_id");
$count_posts = mysqli_fetch_array($sql_count, MYSQLI_ASSOC);

$_SESSION['count'] = $_SESSION['count'] - 5;
$_SESSION['start'] = $_SESSION['start'] + 5;
?>
<br>
<br>
<h2>More posts from <?= $found_user['username'] ?></h2>
<br>
<br>
<hr>
<?php

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

if ($_SESSION['count'] >= 1) {
?>
    <br>
    <button value="<?= $res_id ?>" name="load_more" class="load-more-button">Load more (<?= $_SESSION['count'] ?> remaining)</button>
<?php
}
