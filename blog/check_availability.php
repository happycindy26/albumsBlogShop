<?php
require_once('../oop_conn.php');
if(!empty($_POST["user_name"])) {
    $result = mysqli_query($mysqli,"SELECT count(*) FROM forum_topics WHERE user_name='" . $_POST["user_name"] . "'");
    $row = mysqli_fetch_row($result);
    $user_count = $row[0];
    if($user_count>0) {
        echo "<span class='status-not-available'> Username Not Available.</span>";
    } else {
        echo "<span class='status-available'> Username Available.</span>";
    }
}
?>