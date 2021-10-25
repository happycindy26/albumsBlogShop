<?php session_start();
$total = $_SESSION['total'];
$mysqli = mysqli_connect("localhost", "root", "", "retro");
$display_block = "<h1>Your order so far:</h1>"; 
//check for cart items based on user session id
 $get_cart_sql = "SELECT st.id, si.item_title, si.cur_quant, si.id, si.item_price,
                         st.sel_item_qty, st.sel_item_id 
                  FROM store_shoppertrack AS st 
                  LEFT JOIN store_items AS si 
                  ON si.id = st.sel_item_id 
                  WHERE session_id ='".$_COOKIE['PHPSESSID']."'";
//  print_r($_COOKIE['PHPSESSID']);
 $get_cart_res = mysqli_query($mysqli, $get_cart_sql)
 or die(mysqli_error($mysqli));

if (mysqli_num_rows($get_cart_res) < 1) {
    //print message
    $display_block .= "<p>You have no items in your cart.
    Please <a href=\"seestore.php\">continue to shop</a>!</p>";
} else {
    //get info and build cart display
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
        $new_qty = $cart_info['cur_quant'] - $cart_info['sel_item_qty'];
        $total_price = sprintf("%.02f", $item_price * $item_qty);
     
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

<main class="checkout"  >
    <div>
        <?php echo $display_block ?>
    </div>
    <div>
        <h1>Checkout</h1>
        <form method="post" action="thankyou.php">
            <label for="name">Name:</label>
            <br>
            <input type="text" name="name" value="" id="name">
            <br>
            <label for="email">Email:</label>
            <br>
            <input type="eamil" name="email" value="" id="email">
            <br>
            <label for="phone">Phone:</label>
            <br>
            <input type="phone" name="phone" value="" id="phone">
            <br>
            <label for="address">Address:</label>
            <br>
            <input type="text" name="address" value="" id="address">
            <br>
            <label for="cardname">Name on Card:</label>
            <br>
            <input type="text" name="cardname" value="" id="cardname">
            <br>
            <label for="month">Expiry date of card:</label>
            <select name='month' id='month'>
            <option value=''>Month</option>
            <option value='01'>January</option>
            <option value='02'>February</option>
            <option value='03'>March</option>
            <option value='04'>April</option>
            <option value='05'>May</option>
            <option value='06'>June</option>
            <option value='07'>July</option>
            <option value='08'>August</option>
            <option value='09'>September</option>
            <option value='10'>October</option>
            <option value='11'>November</option>
            <option value='12'>December</option>
        </select> 
        <select name='year' id='year'>
            <option value=''>Year</option>
            <option value='21'>2021</option>
            <option value='22'>2022</option>
            <option value='23'>2023</option>
            <option value='24'>2024</option>
            <option value='20'>2025</option>
        </select> 
        <input class="inputCard" type="hidden" name="expiry" id="expiry" maxlength="4"/>
        <br>
        <input type="hidden" name="session" value="$_COOKIE['PHPSESSID']">
        <input type="submit" name="submit" value="Submit" >
        </form>
    </div>
</main>
<?php include('../html/footer.html'); ?>