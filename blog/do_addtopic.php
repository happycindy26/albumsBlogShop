<?php
    include 'ch21_include.php';
    doDB();

    //check for required fields from the form
    if ((!$_POST['topic_owner']) || (!$_POST['topic_title']) || (!$_POST['post_text'])) {
        header("Location: addtopic.php");
        exit;
    }

    //create safe values for input into the database
    $forum_id = $_GET['forum_id'];
    $clean_topic_owner = mysqli_real_escape_string($mysqli, $_POST['topic_owner']);
    $clean_topic_title = mysqli_real_escape_string($mysqli, $_POST['topic_title']);
    $clean_post_text = mysqli_real_escape_string($mysqli, $_POST['post_text']);

    //create and issue the first query
    $add_topic_sql = "INSERT INTO forum_topics(topic_title,forum_id, topic_create_time, topic_owner)
                        VALUES ('".$clean_topic_title ."', '".$forum_id."', now(), '".$clean_topic_owner."')";

    $add_topic_res = mysqli_query($mysqli, $add_topic_sql)
    or die(mysqli_error($mysqli));

    //get the id of the last query
    $topic_id = mysqli_insert_id($mysqli);

    //create and issue the second query
    $add_post_sql = "INSERT INTO forum_posts(topic_id, post_text, post_create_time, post_owner)
                    VALUES ('".$topic_id."', '".$clean_post_text."', now(), '".$clean_topic_owner."')";

    $add_post_res = mysqli_query($mysqli, $add_post_sql)
    or die(mysqli_error($mysqli));
    //close connection to MySQL
    //mysqli_close($mysqli);

    //gather the topics
    $get_topics_sql = "SELECT topic_id, topic_title,
                       DATE_FORMAT(topic_create_time, '%b %e %Y at %r') 
                       AS fmt_topic_create_time, topic_owner 
                       FROM forum_topics
                       ORDER BY topic_create_time DESC";
    $get_topics_res = mysqli_query($mysqli, $get_topics_sql)
    or die(mysqli_error($mysqli));

    if (mysqli_num_rows($get_topics_res) < 1) {
        //there are no topics, so say so
        $display_block = "<p><em>No topics exist.</em></p>";
    } else {
        //create the display string
        $display_block = <<<END_OF_TEXT
        <table>
            <tr>
                <th>TOPIC TITLE</th>
                <th># of POSTS</th>
            </tr>
END_OF_TEXT;
    
        while ($topic_info = mysqli_fetch_array($get_topics_res)) {
            $topic_id = $topic_info['topic_id'];
            $topic_title = stripslashes($topic_info['topic_title']);
            $topic_create_time = $topic_info['fmt_topic_create_time'];
            $topic_owner = stripslashes($topic_info['topic_owner']);

            //get number of posts
            $get_num_posts_sql = "SELECT COUNT(post_id) AS post_count 
                                FROM forum_posts 
                                WHERE topic_id = '".$topic_id."'";

            $get_num_posts_res = mysqli_query($mysqli, $get_num_posts_sql)
            or die(mysqli_error($mysqli));

            while ($posts_info = mysqli_fetch_array($get_num_posts_res)) {
                $num_posts = $posts_info['post_count'];
            }

            //add to display
            $display_block .= <<<END_OF_TEXT
            <tr>
                <td><a href="showtopic.php?topic_id=$topic_id">
                <strong>$topic_title</strong></a><br/>
                Created on $topic_create_time by $topic_owner</td>
                <td class="num_posts_col">$num_posts</td>
            </tr>
            END_OF_TEXT;
        }
        //free results
        mysqli_free_result($get_topics_res);
        mysqli_free_result($get_num_posts_res);

        //close connection to MySQL
        mysqli_close($mysqli);

        //close up the table
        $display_block .= "</table>";
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
            <li><a href="../home.html">Home</a></li>
            <li><a href="../blog.html">Blog</a></li>
            <li><a href="../seestore.php">Shop</a></li>
        </ul>
    </header>

    <body>
        <main style="width: 50%; margin: 1rem auto">
        <h1>New Topic Added</h1>
        <p>The <strong><?php echo $_POST["topic_title"] ?></strong>topic has been created.</p>
        
        <?php echo $display_block; ?>
        </main>
    </body>
<?php include('../html/footer.html') ?>

<!-- INSERT INTO forum_topics(topic_id, forum_id, topic_title, topic_create_time, topci_owner)
VALUES(1, 1, "Love Jazz", NOW(), "test1@test.com"); -->

<!-- INSERT INTO forum_post(post_id, topic_id, post_text, post_create_time, post_owner)
VALUES(1, 1, "this is first post to reply the topic", NOW(), "test@test.com"); -->