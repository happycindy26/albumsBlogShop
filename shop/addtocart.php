<?php session_start(); 
if (isset($_POST['sel_item_id'])) { 
    //connect to database 
    $mysqli = mysqli_connect("localhost", "root", "", "retro"); 
    //create safe values for use 
    $safe_sel_item_id = mysqli_real_escape_string($mysqli, $_POST['sel_item_id']);
    $safe_sel_item_qty = mysqli_real_escape_string($mysqli, $_POST['sel_item_qty']);
    
    //validate item and get title and price
    $get_iteminfo_sql = "SELECT item_title 
                        FROM store_items 
                        WHERE id = '".$safe_sel_item_id."'";
    $get_iteminfo_res = mysqli_query($mysqli, $get_iteminfo_sql)
    or die(mysqli_error($mysqli));
//print_r($get_iteminfo_res);
    if (mysqli_num_rows($get_iteminfo_res) < 1) {
        //free result
        mysqli_free_result($get_iteminfo_res);
        //close connection to MySQL
        mysqli_close($mysqli);
        //invalid id, send away
        header("Location: ../seestore.php");
        exit;
    } else {
        //get info
        while ($item_info = mysqli_fetch_array($get_iteminfo_res)) {
            $item_title = stripslashes($item_info['item_title']);
        print_r($item_info);
        }
        //free result
        mysqli_free_result($get_iteminfo_res); 

        //check existing session info
        $track_info_sql = "SELECT session_id,sel_item_id, sel_item_qty
                           FROM store_shoppertrack
                           WHERE sel_item_id = '".$safe_sel_item_id."'";
        $track_info_res = mysqli_query($mysqli, $track_info_sql)
        or die(mysqli_error($mysqli));
print_r($track_info_res);

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
//AND session_id = '".$_COOKIE['PHPSESSID']."'
               
                $updatecart_sql = "UPDATE store_shoppertrack
                                SET sel_item_qty = sel_item_qty +'".$safe_sel_item_qty."'
                                WHERE sel_item_id= '".$safe_sel_item_id."'";
                $updatecart_res = mysqli_query($mysqli, $updatecart_sql)
                or die(mysqli_error($mysqli));
            }
        }

        //close connection to MySQL
        mysqli_close($mysqli);

        //redirect to showcart page
        header("Location: showcart.php");
        exit; 
    }
} else {
        //send them somewhere else
    header("Location: seestore.php");
    exit;
 }
 ?>



<!-- $exist_id_sql = "SELECT sel_item_id
                         FROM store_shoppertrack
                        WHERE sel_item_id='".$safe_sel_item_id."'";
        $exist_id_res = mysqli_query($mysqli, $exist_id_sql); -->
       <!-- print_r($exist_id_res); -->
       <!-- if (mysqli_num_rows($exist_id_res)) {
            $ids = mysqli_fetch_array($mysqli, $exist_id_res);
                $id = $ids['sel_item_id'];
        var_dump($id);  -->

        <!-- $updatecart_sql = "UPDATE store_shoppertrack
                            SET sel_item_qty +='".$safe_sel_item_qty."'
                            WHERE sel_item_id='".$id."' ";
            $updatecart_res = mysqli_query($mysqli, $updatecart_sql)
            or die(mysqli_error($mysqli)); -->