<?php
require_once('../oop_conn.php');
    $get_topics_sql = "SELECT topic_id, topic_title,
                       DATE_FORMAT(topic_create_time, '%b %e %Y at %r') 
                       AS fmt_topic_create_time, topic_owner 
                       FROM forum_topics
                       ORDER BY topic_create_time DESC";
    $get_topics_res = mysqli_query($mysqli, $get_topics_sql)
    or die(mysqli_error($mysqli));

    if (mysqli_num_rows($get_topics_res) < 1) {
        $display_block = "<p><em>No topics exist.</em></p>";
    } else {
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

            $get_num_posts_sql = "SELECT COUNT(post_id) AS post_count 
                                FROM forum_posts 
                                WHERE topic_id = '".$topic_id."'";

            $get_num_posts_res = mysqli_query($mysqli, $get_num_posts_sql)
            or die(mysqli_error($mysqli));

            while ($posts_info = mysqli_fetch_array($get_num_posts_res)) {
                $num_posts = $posts_info['post_count'];
            }

            $display_block .= <<<END_OF_TEXT
            <tr>
                <td><a href="showtopic.php?topic_id=$topic_id">
                <strong>$topic_title</strong></a><br/>
                Created on $topic_create_time by $topic_owner</td>
                <td class="num_posts_col">$num_posts</td>
            </tr>
            END_OF_TEXT;
        }
        mysqli_free_result($get_topics_res);
        mysqli_free_result($get_num_posts_res);
        mysqli_close($mysqli);
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
            <li><a href="../index.html">Home</a></li>
            <li><a href="../blog.html">Blog</a></li>
            <li><a href="../seestore.php">Shop</a></li>
        </ul>
    </header>

    <body>
        <main class="topiclist">  
            <h1>Topics in Blog</h1>
            <?php echo $display_block; ?>
        </main>
    </body>
<?php include('../html/footer.html') ?>