<?php session_start(); 
require_once('../oop_conn.php');
if (isset($_POST['sel_item_id'])) { 
    $safe_sel_item_id = mysqli_real_escape_string($mysqli, $_POST['sel_item_id']);
    $safe_sel_item_qty = mysqli_real_escape_string($mysqli, $_POST['sel_item_qty']);
    
    $get_iteminfo_sql = "SELECT item_title 
                        FROM store_items 
                        WHERE id = '".$safe_sel_item_id."'";
    $get_iteminfo_res = mysqli_query($mysqli, $get_iteminfo_sql)
    or die(mysqli_error($mysqli));

    if (mysqli_num_rows($get_iteminfo_res) < 1) {
        mysqli_free_result($get_iteminfo_res);
        mysqli_close($mysqli);
        header("Location: ../seestore.php");
        exit;
    } else {
        while ($item_info = mysqli_fetch_array($get_iteminfo_res)) {
            $item_title = stripslashes($item_info['item_title']);
        }
        mysqli_free_result($get_iteminfo_res); 

        //check existing session info
        $track_info_sql = "SELECT session_id,sel_item_id, sel_item_qty
                           FROM store_shoppertrack
                           WHERE sel_item_id = '".$safe_sel_item_id."'";
        $track_info_res = mysqli_query($mysqli, $track_info_sql)
        or die(mysqli_error($mysqli));

        if (mysqli_num_rows($track_info_res) < 1) {
            //add info to cart table
            $addtocart_sql = "INSERT INTO store_shoppertrack
                                            (session_id, sel_item_id, sel_item_qty, date_added)
                            VALUES ('".$_COOKIE['PHPSESSID']."',
                                '".$safe_sel_item_id."',
                                '".$safe_sel_item_qty."', now())";
            $addtocart_res = mysqli_query($mysqli, $addtocart_sql)
            or die(mysqli_error($mysqli));    
        } else {
            while($items = mysqli_fetch_array($track_info_res)) {
                $updatecart_sql = "UPDATE store_shoppertrack
                                SET sel_item_qty = sel_item_qty +'".$safe_sel_item_qty."'
                                WHERE sel_item_id= '".$safe_sel_item_id."'";
                $updatecart_res = mysqli_query($mysqli, $updatecart_sql)
                or die(mysqli_error($mysqli));
            }
        }
        mysqli_close($mysqli);
        header("Location: showcart.php");
        exit; 
    }
} else {
    header("Location: seestore.php");
    exit;
 }
 ?>



