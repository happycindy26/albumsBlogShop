<?php //connect to database 
 $mysqli = mysqli_connect("localhost", "root", "", "retro"); 

 //create safe values for use 
 $safe_item_id = mysqli_real_escape_string($mysqli, $_GET['item_id']);
 //validate item
 $get_item_sql = "SELECT c.id as cat_id, c.cat_title, si.item_title,si.item_artist,
                         si.item_price, si.item_desc, si.item_image 
                  FROM store_items AS si 
                  LEFT JOIN store_categories AS c 
                  on c.id = si.cat_id
                  WHERE si.id = '".$safe_item_id."'";
 $get_item_res = mysqli_query($mysqli, $get_item_sql)
 or die(mysqli_error($mysqli));

 if (mysqli_num_rows($get_item_res) < 1) {
    //invalid item
    $display_block = "<p><em>Invalid item selection.</em></p>";
 } else {
        //valid item, get info
        while ($item_info = mysqli_fetch_array($get_item_res)) {
            $cat_id = $item_info['cat_id'];
            $cat_title = strtoupper(stripslashes($item_info['cat_title']));
            $item_title = stripslashes($item_info['item_title']);
            $item_price = $item_info['item_price'];
            $item_desc = stripslashes($item_info['item_desc']);
            $item_image = $item_info['item_image'];
        }
        //make breadcrumb trail & display of item
       
        //free result
        mysqli_free_result($get_item_res);
    }
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

<main class="showitem">
    <h1>Item Details</h1>
    <div class="itemDetail">
        <div>
            <div>   
                <a href="../seestore.php?cat_id=$cat_id">
                    <h3><?php echo $cat_title ?></h3>
                </a> 
                <h4><?php echo  $item_title ?></h4>
            </div>
            <img style="height: 10rem;" src="../imgs/<?php echo $item_image ?>" alt="$item_title" />
        </div>
        <div>
            <p><strong>Description:</strong><?php echo $item_desc ?></p>
            <p><strong>Price:</strong> $ <?php echo $item_price ?></p>

            <form method="post" action="addtocart.php">
                <label for="sel_item_qty">Select Quantity:</label>
                <select id="sel_item_qty" name="sel_item_qty">
                    <?php for ($i = 1; $i < 11; $i++): ?>
                    <option value="<?php echo $i ?>" >
                        <?php echo $i ?>
                    </option>
                    <?php endfor; ?>
                </select>
                <input type="hidden" name="sel_item_id" value="<?php echo $_GET['item_id'] ?>" />
                <br>
                <button type="submit" name="submit" value="submit">
                    Add to Cart
                </button>
            </form>
        </div>
    </div>
</main>  

<?php include('../html/footer.html') ?>

