<?php session_start();
$total = $_SESSION['total'];
$mysqli = mysqli_connect("localhost", "root", "", "retro");
if(isset($_POST['submit'])) {
    $safe_sess = mysqli_real_escape_string($mysqli, $_POST['session']);
    $safe_name = mysqli_real_escape_string($mysqli, $_POST['name']);
    $safe_email = mysqli_real_escape_string($mysqli, $_POST['email']);
    $safe_phone = mysqli_real_escape_string($mysqli, $_POST['phone']);
    $safe_address = mysqli_real_escape_string($mysqli, $_POST['address']);
    $safe_cardname = mysqli_real_escape_string($mysqli, $_POST['cardname']);
    $safe_month = mysqli_real_escape_string($mysqli, $_POST['month']);
    $safe_year = mysqli_real_escape_string($mysqli, $_POST['year']);
}
    $add_order_sql = "INSERT INTO store_orders(order_date, order_name, order_address, order_tel, order_email, item_total)
                        VALUES(NOW(), '".$safe_name."', '".$safe_address."','".$safe_phone."','".$safe_email."','".$total."' )";
    $add_order_res = mysqli_query($mysqli, $add_order_sql)
    or die(mysqli_error($mysqli));

    $safe_id = mysqli_insert_id($mysqli);

    $get_cart_sql = "SELECT st.id, si.item_title, si.item_price,
                            st.sel_item_qty, st.sel_item_id
                    FROM store_shoppertrack AS st 
                    LEFT JOIN store_items AS si 
                    ON si.id = st.sel_item_id 
                    WHERE session_id ='".$_COOKIE['PHPSESSID']."'";
    //  print_r($_COOKIE['PHPSESSID']);
    $get_cart_res = mysqli_query($mysqli, $get_cart_sql)
    or die(mysqli_error($mysqli));

    while ($cart_info = mysqli_fetch_array($get_cart_res)) {
        $id = $cart_info['id'];
        $item_title = stripslashes($cart_info['item_title']);
        $item_price = $cart_info['item_price'];
        $item_qty = $cart_info['sel_item_qty'];
        $total_price = sprintf("%.02f", $item_price * $item_qty);
        
        $add_order_item_sql = "INSERT INTO store_orders_items(order_id, sel_item_id, sel_item_qty, sel_item_price)
        VALUES('".$safe_id."', '".$id."', '".$item_qty."', '".$item_price."' )";

        $add_order_item_res = mysqli_query($mysqli, $add_order_item_sql)
        or die(mysqli_error($mysqli));
        
        $update_order_quantity_sql = "UPDATE store_items
        SET cur_quant = cur_quant - '".$item_qty."'
           WHERE id = '".$id."'";
        $update_order_quantity_res = mysqli_query($mysqli, $update_order_quantity_sql)
        or die(mysqli_error($mysqli));

    $delete_order_item_sql = "DELETE FROM store_shoppertrack
     WHERE session_id ='".$_COOKIE['PHPSESSID']."'";
    $delete_order_item_res = mysqli_query($mysqli, $delete_order_item_sql)
    or die(mysqli_error($mysqli));

    
    }

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

<main class="thankyou" >
<h1>Thank you for shopping with RetroRecords</h1>
<p><a href="../seestore.php">Back to shop</a></p>
</main>

<?php include('../html/footer.html') ?>