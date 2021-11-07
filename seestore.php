<?php
 require_once('oop_conn.php');
 $display_block = "<main class=\"seestore\">
                        <div class=\"store\">
                            <h1>My Categories</h1>
                            <p>Select a category to see its items.</p>
                        </div>";

 //show categories first
 $get_cats_sql = "SELECT id, cat_title, cat_desc 
                  FROM store_categories 
                  ORDER BY cat_title";
 $get_cats_res = mysqli_query($mysqli, $get_cats_sql)
 or die(mysqli_error($mysqli));

 if (mysqli_num_rows($get_cats_res) < 1) {
    $display_block = "<p><em>Sorry, no categories to browse.</em></p>";
 } else {
        $display_block .= "<div class=\"category\">";
        while ($cats = mysqli_fetch_array($get_cats_res)) {
            $cat_id = $cats['id'];
            $cat_title = strtoupper(stripslashes($cats['cat_title']));
            $cat_desc = stripslashes($cats['cat_desc']);

            $display_block .= "<div class=\"catedetails\">
            <a href=\"".$_SERVER['PHP_SELF']."?cat_id=".$cat_id."\">".$cat_title."</a>
            <br/>
            <p>".$cat_desc."</p>";

            if (isset($_GET['cat_id']) && ($_GET['cat_id'] == $cat_id)) {
                // create safe value for use
                $safe_cat_id = mysqli_real_escape_string($mysqli,
                $_GET['cat_id']);

                //get items
                $get_items_sql = "SELECT id, item_title, item_price
                                  FROM store_items 
                                  WHERE cat_id = '".$safe_cat_id."' 
                                  ORDER BY item_title";
                $get_items_res = mysqli_query($mysqli, $get_items_sql)
                or die(mysqli_error($mysqli));
// print_r($get_cats_res);
                if (mysqli_num_rows($get_items_res) < 1) {
                    $display_block = "<p><em>Sorry, no items in this
                    category.</em></p>";
                } else {
                    $display_block .= "<ul>";
                    while ($items = mysqli_fetch_array($get_items_res)) {
                        $item_id = $items['id'];
                        $item_title = stripslashes($items['item_title']);
                        $item_price = $items['item_price'];
// print_r($items);
                        $display_block .= "<li><a href=\"shop/showitem.php?item_id=".
                                            $item_id."\">".$item_title."</a>
                                            (\$".$item_price.")</li>";
                    }
                    $display_block .= "</ul>";
                }
                //free results
                mysqli_free_result($get_items_res);
            }
            $display_block .= "</div>";
        }
        $display_block .= "</div>";
    }
    $display_block .= "</main>";

 //free results
 mysqli_free_result($get_cats_res);
 //close connection to MySQL
 mysqli_close($mysqli);
 
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Retro Records</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="style.css"> 
    <style>
        .catedetails {
            margin: 1rem;
        }
    </style>
</head>

<body>
    <header class="header">
        <a href="index.html" class="logo"><img src="imgs/99.png" alt="log"></a>
        <input class="menu-btn" type="checkbox" id="menu-btn" />
        <label class="menu-icon" for="menu-btn"><span class="navicon"></span></label>
        <ul class="menu">
            <li><a href="index.html">Home</a></li>
            <li><a href="blog.html">Blog</a></li>
            <li><a href="seestore.php">Shop</a></li>
        </ul>
    </header>

    <?php echo $display_block; ?>
           
    <footer class="footer"> 
        <div class="icons">
            <i class="fab fa-facebook-square"></i>
            <i class="fab fa-twitter-square"></i>
            <i class="fab fa-youtube-square"></i>
        </div>
        <div class="info">
            <p class="">&copy; Retro Records Newtown Pty Limited 2021. All Rights Reserved</p>  
            <p>info@retrorecordsnewtown.com.au</p>
            <p>legals</p>
        </div>  
    </footer>      
    <script src="script.js" ></script>
</body>
</html>