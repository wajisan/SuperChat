<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SuperChat</title>
<link type="text/css" rel="stylesheet" href="css/bootstrap.css" />
</head>
<nav class="navbar navbar-inverse">

<? 
session_start();
if (isset($_SESSION['name']) || isset($_POST['name'])){?>
<ul style="float: right!important;margin-right: 10px;">
        <li><button id="logout" href="#" class="logout btn">Logout</button></li>
</ul>
<? }?>

</nav>
 <?
function loginChat(){
    echo'
    <form autocomplete="off" action="index.php" method="post" class="col-sm-2 control-label">
	<div id="loginchat" class="form-group">
	 <h2>SuperChat</h2>
        <label for="name" class="">Name:</label>
        <input type="text" name="name" id="name" class="form-control"/>
        <button type="submit" name="enter" id="enter" value="Enter" class="btn btn-default"> Enter </button>
    </div>
	</form>
    ';
}
if(isset($_GET['logout']))
{
    $fd = fopen("chat.html", 'a');
    fwrite($fd, "<div class='msgln'><i>User ". $_SESSION['name'] ." has exit.</i><br></div>");
    fclose($fd);
    session_destroy();
    header("Location: index.php"); 
}

if(isset($_POST['enter']))
{
    if($_POST['name'] != "")
        $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
    else
        echo '<span class="error">Please type in a name</span>';
}

if(!isset($_SESSION['name']))
    loginChat();
else
{
	if (isset($_POST['text']))
	{
		$text = $_POST['text'];
		$fp = fopen("chat.html", 'a');
		fwrite($fp, "<div class='msgln'>(".date("H:i").") <b>".$_SESSION['name']."</b>: ".stripslashes(htmlspecialchars($text))."<br></div>");
		fclose($fp);
	}

?>

<div class="container-fluid">
    <div id="menu">
        <h3 class="welcome">Welcome, <b><?php echo $_SESSION['name']; ?></b></h3><br>
        <div style="clear:both"></div>
    </div>
     
    <div id="chatbox" class="panel-body">
	<?php
		if(file_exists("chat.html") && filesize("chat.html") > 0)
		{
			$handle = fopen("chat.html", "r");
			$contents = fread($handle, filesize("chat.html"));
			fclose($handle);
			echo $contents;
		}
	?>
	</div>
     
    <form autocomplete="off" name="message" action="" class="panel-footer">
		<div class="input-group"> 
		<input type="text" class="form-control" placeholder="Type here..." id="usermsg"> 
		<span class="input-group-btn"> 
			<button class="btn btn-default" name="submitmsg" id="submitmsg" value="Send"> Send </button> 
		</span> 
		</div>
    </form>	
</div>



<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#logout").click(function(){
		var logout = confirm("Logout ?");
		if(logout==true){
			window.location = 'index.php?logout=true';
		}		
	});
	
	$("#submitmsg").click(function(){
		var clientmsg = $("#usermsg").val();
		$.post("index.php", {text: clientmsg});	
		$("#usermsg").val("");
		return false;
	});
	
	function loadLog(){		
		var scroll1 = $("#chatbox").attr("scrollHeight") - 20;
		$.ajax({
			url: "chat.html",
			cache: false,
			success: function(html){		
				$("#chatbox").html(html);	
				var scroll2 = $("#chatbox").attr("scrollHeight") - 20;
				if(scroll2 > scroll1){
					$("#chatbox").animate({ scrollTop: scroll2 }, 'normal');
				}				
		  	},
		});
	}
	setInterval (loadLog, 1500);
	$("#chatbox").animate({ scrollTop: $("#chatbox").attr("scrollHeight") }, 'normal');
});
</script>

<?php 
}
?>
</body>
</html>