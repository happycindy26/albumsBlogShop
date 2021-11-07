<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Retro Records</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../style.css"> 
    <style>
        /* body{width:50%;} */
        /* #frmCheckUsername {border-top:#F0F0F0 2px solid;background:#FAF8F8;padding:10px;} */
        /* .demoInputBox{padding:7px; border:#F0F0F0 1px solid; border-radius:4px;} */
        .status-available{color:#2FC332;}
        .status-not-available{color:#D60202;}
    </style>
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

    <main class="addtopic" >
        <h1>Add a Topic</h1>
        <div id="frmCheckUsername">
            <label for="user_name">User Name:</label><br/>
            <input type="user_name" id="user_name" name="user_name" onchange="checkAvailability()" required ><span id="user-availability-status"></span><br/>
        </div>
        <form method="post" action="do_addtopic.php?forum_id=<?php echo $_GET['forum_id'] ?>">
            <label for="topic_owner">Your Email Address:</label><br/>
            <input type="email" id="topic_owner" name="topic_owner" size="40"
                    maxlength="150" required="required" /><br>

            <label for="topic_title">Topic Title:</label><br/>
            <input type="text" id="topic_title" name="topic_title" size="40"
                    maxlength="150" required="required" /><br/>
            <label for="post_text">Post Text:</label><br/>
            <textarea id="post_text" name="post_text" rows="8"
                        cols="40" ></textarea><br>
            <button type="submit" name="submit" value="submit">Add Topic</button>
        </form>
    </main>
    
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
    <script src="jquery.js"></script>
<script>
function checkAvailability() {
	// $("#loaderIcon").show();
	jQuery.ajax({
	url: "check_availability.php",
	data:'user_name='+$("#user_name").val(),
	type: "POST",
	success:function(data){
		$("#user-availability-status").html(data);
		// $("#loaderIcon").hide();
	},
	error:function (){}
	});
}
</script>

</body>
</html>
