<?php

include('server.php');
include('db.php');

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
<html>

<head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

    <div class="header">
        <h2>Home Page</h2>
    </div>
    <div class="content">
        <!-- notification message -->
        <?php if (isset($_SESSION['success'])) : ?>
            <div class="error success">
                <h3>
                    <?php
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                    ?>
                </h3>
            </div>
        <?php endif ?>

        <!-- logged in user information -->

        <?php if (isset($_SESSION['username'])) : ?>
            <p>Welcome <strong><?php echo $_SESSION['username']; ?></strong></p>
            <p> <a href="index.php?logout='1'" style="color: red;">logout</a> </p>
        <?php endif ?>

    </div>
    <div>
        <form method="post">
            <h2>Post Something:</h2><br>
            <textarea name="postitem" rows="5" cols="50"></textarea><br>
            <input type="hidden" value="<?= uniqid() ?>" name="token">
            <button type="submit" name="postbut" class="btn">Post</button>
        </form>
    </div>
    <div>
        <a href="userposts.php">These guys may also have some posts to check out!</a>
    </div>
    <br>
    <br>
    <div>
        <?php
        $get_id = "SELECT * FROM registered_users WHERE username='{$_SESSION['username']}'";
        $q_id = mysqli_query($db, $get_id);
        $f_id = mysqli_fetch_assoc($q_id);
        $session_id = $f_id['id'];

        if (isset($_POST['postbut'])) {
            $token = $_POST['token'];

            if ($_SESSION['last_token'] == $token) {
            } else {
                $post = htmlspecialchars($_POST['postitem']);
                $date_db = date('Y-m-d H:i:s');
                $date = date('l jS \of F Y h:i:s A');

                $sql = "INSERT INTO to_do (user_id, text, date) 
                VALUES($session_id, '$post', '$date_db')";
                mysqli_query($db, $sql);

                $_SESSION['last_token'] = $token;
            }
        }

        $res = mysqli_query($db, "SELECT * FROM to_do WHERE user_id='$session_id'");
        $req = mysqli_fetch_all($res, MYSQLI_ASSOC);

        ?>
        <?php foreach ($req as $request) {
            $id = $request['id']; ?>

            <form method="post" class="eachPost" name="<?= $id ?>">
                <p class="mainPost"> <?= $request['text'] ?> </p>
                <br>
                <p> <?= $_SESSION['username'] . ", " . $request['date'] ?> </p>
                <br>
                <hr>
                <button class="close" type="submit" name="delpost<?= $id ?>">x</button>
                <br>

                <?php
                $pst = 'delpost' . $id;
                if (isset($_POST[$pst])) {
                    $del = mysqli_query($db, "DELETE FROM to_do WHERE id='$id'");
                    header("location: index.php");
                } ?>

            </form>
        <?php
        }

        ?>
    </div>
</body>

</html>

<!--
<script>
const deletePost = postId => fetch('/delete_post.php?id='+postId, {
    method: 'DELETE'
})

const PostList = ({posts}) => {
    return (
        <div>
            { posts.map(post => {
                return (
                    <div>
                        <div>{ post.date }</div>
                        <div>{ post.text }</div>
                        <button onClick={() => deletePost(post.id)}>Delete</button>
                    </div>
                )
            }) }
        </div>
    )
}

ReactDOM.render(<PostList posts={posts}/>, document.querySelector('#posts-container'))
</script>
-->