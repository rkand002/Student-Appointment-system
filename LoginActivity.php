hello
<header style="position: relative; width:100%; background-color: #002B5F ; border-color: #002B5F;font-size:1.4vh;margin-bottom:1%;">
		<div class=" row" style="margin:0;background-color: #002B5F ; border-color: #002B5F;margin-bottom:2vh;padding-right:0px;margin-bottom:0vh;">
		<table class=" table table-responsive" style="padding-top:0vh;border:0;">
				<tr><td><a style="padding-top:0px;margin-top:0px;float:left;" class="navbar-header" href="../admin/AdminHome.php" >
 				<img class="img-responsive"  src="../images/APNS.jpg" alt="APN-PLACE Logo" style="overflow:hidden;">
 				</a>
 				</td>	
 		    <td><a id="Username"  style="float:right;color:white;font-size:2vh;margin-top:0%;"><b>Hi <?php echo  $_SESSION["name"]?> !</b></a></td>
			<td><a id="HomePage" href="../admin/AdminHome.php" style="float:right;color:white;font-size:2vh;margin-top:0%;"><span class="glyphicon glyphicon-home" style="color:white;" ></span><b>Home</b></a></td>
			<td><a id="Myprofile" href="#" style="float:right;color:white;font-size:2vh;margin-top:0%;"><span class=""></span><b>My Profile</b></a></td>				
			<td><a id="logout" href="#" style=" float:right;background-color:transparent; border: none;outline-style: none;outline-width: 0px;color: white;font-size:2vh;"><span class="glyphicon glyphicon-log-out" style="margin-top:0;"></span><b> Logout</b></a></td>
           

</tr>

<tr>
<td colspan="6" style="border-top:0px;">
<form class="pull-right" action="../sphider-1.3.6/search.php" method="get" target="_blank">
<input style="padding-left:2vh;" type="text" delay="1500" autocomplete="on" columns="1" action="include/js_suggest/suggest.php" value="" size="20" id="query" name="query"><div id="querySuggestList" class="SuggestFramework_List" style="position: absolute; z-index: 1; width: 100px; word-wrap: break-word; cursor: default; display: none;"></div>	
<input type="submit" value="Search" class="btn btn-info btn-xs"> 
<input type="hidden" value="1" name="search">
</form>
</td>

</tr>





</table></div>
</header>