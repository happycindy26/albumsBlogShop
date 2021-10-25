<?php
    include 'ch21_include.php';
    doDB();
    // $types = array("JavaScript" => "Everything you wanted to know about js", 
    //             "PHP" => "The joys of server-side scripting", 
    //             "CSS" => "Css quirks and work arounds", 
    //             "HTML" => "The miracle of semantic markup");
    // foreach($types as $key => $val) {
    //     echo $key . " ". $val;
    //     $add_type_sql = "INSERT INTO forum_type(type_name, description)
    //     VALUES('".$key."', '".$val."')";
    //     $add_forum_res = mysqli_query($mysqli, $add_type_sql)
    //     or die(mysqli_error($mysqli));
    // };

    // $add_forum_res = mysqli_query($mysqli, $add_type_sql)
    // or die(mysqli_error($mysqli));
    // $forum_id = mysqli_insert_id($mysqli);
   
    if(isset($_POST['submit'])) {
        $safe_category_name = mysqli_real_escape_string($mysqli, $_POST['category'] );
        // $cate_name = $_POST['category'];
// print_r($safe_category_name);
// print_r($cate_name);     
        $get_cate_topic_sql = "SELECT  topic_id, topic_title, DATE_FORMAT(topic_create_time, '%b %e %Y at %r') AS fmt_topic_create_time, topic_owner, forum_type.forum_id, forum_type.description
                           FROM forum_topics 
                           JOIN forum_type 
                           WHERE forum_topics.forum_id = forum_type.forum_id
                            AND forum_type.type_name = '".$safe_category_name."'
                           ORDER BY topic_create_time DESC";
        $get_topics_res = mysqli_query($mysqli, $get_cate_topic_sql)
        or die(mysqli_error($mysqli));
// var_dump($get_topics_res);
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
//  print_r($topic_info);
            $forum_id = $topic_info['forum_id'];
            $forum_description = $topic_info['description'];
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

    
    <main class="categorymain">
    <h1>Topics in this category - <?php echo $safe_category_name; ?> </h1>
    <p><?php echo $forum_description; ?></p>
    <?php echo $display_block; ?>
    <p>Would you like to <a href="addtopic.php?forum_id=<?php echo $forum_id ?>">add a topic</a>?</p>
    </main>
</body>
<?php include('../html/footer.html') ?>
