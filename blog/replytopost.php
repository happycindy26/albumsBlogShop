<?php
require_once('../oop_conn.php');
if (!$_POST) {
    // showing the form; check for required item in query string
    if (!isset($_GET['post_id'])) {
        header("Location: topiclist.php");
        exit;
    }
    //create safe values for use
    $safe_post_id = mysqli_real_escape_string($mysqli, $_GET['post_id']);

    //still have to verify topic and post
    $verify_sql = "SELECT ft.topic_id, ft.topic_title 
                    FROM forum_posts AS fp 
                    LEFT JOIN forum_topics AS ft 
                    ON fp.topic_id = ft.topic_id 
                    WHERE fp.post_id = '".$safe_post_id."'";

    $verify_res = mysqli_query($mysqli, $verify_sql)
    or die(mysqli_error($mysqli));

    if (mysqli_num_rows($verify_res) < 1) {
    //this post or topic does not exist
        header("Location: topiclist.php");
        exit;
    } else {
        //get the topic id and title
        while($topic_info = mysqli_fetch_array($verify_res)) {
            $topic_id = $topic_info['topic_id'];
            $topic_title = stripslashes($topic_info['topic_title']);
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Retro Records</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../style.css"> 
</head>

<body>
    <header class="header">
        <a href="index.html" class="logo"><img src="../imgs/99.png" alt="log"></a>
        <input class="menu-btn" type="checkbox" id="menu-btn" />
        <label class="menu-icon" for="menu-btn"><span class="navicon"></span></label>
        <ul class="menu">
            <li><a href="../index.html">Home</a></li>
            <li><a href="../blog.html">Blog</a></li>
            <li><a href="../seestore.php">Shop</a></li>
        </ul>
    </header>
    <main class="replytopost">
        <h1>Post Your Reply in <?php echo $topic_title; ?></h1>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <p><label for="post_owner">Your Email Address:</label><br/>
            <input type="email" id="post_owner" name="post_owner" size="40"
            maxlength="150" required="required"></p>
            <p><label for="post_text">Post Test:</label><br/>
            <textarea id="post_text" name="post_text" rows="8" cols="40"
            required="required"></textarea></p>
            <input type="hidden" name="topic_id" value="<?php echo $topic_id; ?>">
            <button type="submit" name="submit" value="submit">Add Post</button>
        </form>
    </main>
</body>
<?php include('../html/footer.html') ?>

<?php
        }
    //free result
    mysqli_free_result($verify_res);

    //close connection to MySQL
    mysqli_close($mysqli);

    } else if ($_POST) {
    //check for required items from form
    if ((!$_POST['topic_id']) || (!$_POST['post_text']) ||
    (!$_POST['post_owner'])) {
    header("Location: topiclist.php");
    exit;
    }

    //create safe values for use
    $safe_topic_id = mysqli_real_escape_string($mysqli, $_POST['topic_id']);
    $safe_post_text = mysqli_real_escape_string($mysqli, $_POST['post_text']);
    $safe_post_owner = mysqli_real_escape_string($mysqli, $_POST['post_owner']);

    //add the post
    $add_post_sql = "INSERT INTO forum_posts (topic_id,post_text, post_create_time,post_owner) 
                    VALUES ('".$safe_topic_id."', '".$safe_post_text."', now(),'".$safe_post_owner."')";
    $add_post_res = mysqli_query($mysqli, $add_post_sql)
    or die(mysqli_error($mysqli));

    mysqli_close($mysqli);
    //redirect user to topic
    header("Location: showtopic.php?topic_id=".$_POST['topic_id']);
    exit;
    }
    ?>













        <!-- }
        //free result
        mysqli_free_result($verify_res);
        //close connection to MySQL
        mysqli_close($mysqli);

    } else if ($_POST) {
        //check for required items from form
        if ((!$_POST['topic_id']) || (!$_POST['post_text']) || (!$_POST['post_owner'])) {
            header("Locati topiclist.php");
            exit;
        }
        //create safe values for use
        $safe_topic_id = mysqli_real_escape_string($mysqli, $_POST['topic_id']);
        $safe_post_text = mysqli_real_escape_string($mysqli, $_POST['post_text']);
        $safe_post_owner = mysqli_real_escape_string($mysqli, $_POST['post_owner']);

        //add the post
        $add_post_sql = "INSERT INTO forum_posts (topic_id,post_text, post_create_time,post_owner) 
                        VALUES('".$safe_topic_id."', '".$safe_post_text."', now(),'".$safe_post_owner."')";
        $add_post_res = mysqli_query($mysqli, $add_post_sql)
        or die(mysqli_error($mysqli));

        //close connection to MySQL
        mysqli_close($mysqli);

        //redirect user to topic
        header("Locati showtopic.php?topic_id=".$_POST['topic_id']);
        exit;
    }
 ?>



    <body>
        <main style="min-heig 70vh">
        <h1>Post Your Reply in <?php echo $topic_title; ?></h1>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <p><label for="post_owner">Your Email Addre</label><br/>
            <input type="email" id="post_owner" name="post_owner" size="40"
                maxlength="150" required="required"></p>
            <p><label for="post_text">Post Te</label><br/>
            <textarea id="post_text" name="post_text" rows="8" cols="40"
                required="required"></textarea></p>
            <input type="hidden" name="topic_id" value="<?php echo $topic_id; ?>">
            <button type="submit" name="submit" value="submit">Add Post</button>
        </form>
        </main>
    </body>
 -->












