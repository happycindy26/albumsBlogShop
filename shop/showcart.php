<?php 
require_once('../oop_conn.php');
session_start(); 
$total=0;
$display_block = "<h1>Your Shopping Cart</h1>"; 
//check for cart items based on user session id
 $get_cart_sql = "SELECT st.id, si.item_title, si.item_price,
                         st.sel_item_qty 
                  FROM store_shoppertrack AS st 
                  LEFT JOIN store_items AS si 
                  ON si.id = st.sel_item_id 
                  WHERE session_id ='".$_COOKIE['PHPSESSID']."'";
//  print_r($_COOKIE['PHPSESSID']);
 $get_cart_res = mysqli_query($mysqli, $get_cart_sql)
 or die(mysqli_error($mysqli));

if (mysqli_num_rows($get_cart_res) < 1) {
    $display_block .= "<p>You have no items in your cart.
    Please <a href=\"../seestore.php\">continue to shop</a>!</p>";
} else {
    $display_block .= <<<END_OF_TEXT
    <table>
        <tr>
            <th>Title</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Total Price</th>
            <th>Action</th>
        </tr>
    END_OF_TEXT;
    
    while ($cart_info = mysqli_fetch_array($get_cart_res)) {
        $id = $cart_info['id'];
        $item_title = stripslashes($cart_info['item_title']);
        $item_price = $cart_info['item_price'];
        $item_qty = $cart_info['sel_item_qty'];
        $total_price = sprintf("%.02f", $item_price * $item_qty);
        $total = sprintf("%.02f", $total + $total_price);
        // $total+=$total_price;
        $display_block .= <<<END_OF_TEXT
        <tr>
            <td>$item_title <br></td>
            <td>\$ $item_price <br></td>
            <td>$item_qty <br></td>
            <td>\$ $total_price</td>
            <td><a href="removefromcart.php?id=$id">remove</a></td>
        </tr>
        END_OF_TEXT;
    }
    $display_block .= <<<END_OF_TEXT
        <tr>    
            <td colspan="3">Total<br></td>
            <td>\$ $total</td>
            <td></td>
        </tr>
        END_OF_TEXT;
    $display_block .= "</table>";
 }
 $_SESSION['total'] = $total;
 mysqli_free_result($get_cart_res);
 mysqli_close($mysqli);
 $display_block .= "<p><a href=\"../seestore.php\">back to Shop</a></p>
                    <p><a href=\"checkout.php\">Check out</a></p>";
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
    <main class="showcart">
        <?php echo $display_block; ?>
    </main>
<?php include('../html/footer.html') ?>



