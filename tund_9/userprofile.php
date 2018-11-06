<?php
    require("functions.php");
  //kui pole sisse loginud
  
  //kui pole sisselogitud
  if(!isset($_SESSION["userId"])){
	header("Location: index_3.php");
    exit();	
  }
  
  //väljalogimine
  if(isset($_GET["logout"])){
	session_destroy();
	header("Location:  index_3.php");
	exit();
  }  
  
  $mydescription = "Pole tutvustust lisanud!";
  $mybgcolor = "#FFFFFF";
  $mytxtcolor = "#000000"; 
  
  if(isset($_POST["submitProfile"])){
		$notice = storeuserprofile($_POST["description"], $_POST["bgcolor"], $_POST["txtcolor"], $_POST["profilepic"]);
		if(!empty($_POST["description"])){
		$mydescription = $_POST["description"];
		}
		$mybgcolor = $_POST["bgcolor"];
		$mytxtcolor = $_POST["txtcolor"];
		$myprofilepic = $_POST["profilepic"];
  } else {
		$myprofile = showmyprofile();
		if($myprofile->description != ""){
			$mydescription = $myprofile->description;
		}
		if($myprofile->bgcolor != ""){
			$mybgcolor = $myprofile->bgcolor;
		}
		if($myprofile->txtcolor != ""){
			$mytxtcolor = $myprofile->txtcolor;
		}
		if($myprofile->profilepic != ""){
			$myprofilepic = $myprofile->profilepic;
		}
	  }
	  
  
  $pageTitle = "";
  require("header.php");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Kasutaja profiil</title>
</head>
<body>
  <h1>Kasutaja <?php echo $_SESSION["userFirstName"] ." " . $_SESSION["userLastName"]; ?> profiil</h1>
  <p>Siin on minu <a href="http://www.tlu.ee">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
  <hr>
	<ul>
	  <li><a href="?logout=1">Logi välja</a>!</li>
	  <li><a href="main.php">Tagasi pealehele</a></li>
	</ul>
  <hr>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	 <label>Minu kirjeldus (max2000 märki) : </label>
	 <br>
     <textarea rows="10" cols="80" name="description"><?php echo $mydescription; ?></textarea>
	 <br>
	 <label>Lisa profiilipilt:</label>
	 <input type="file" value="<?php echo $myprofilepic; ?>" name="profilepic"><br>
	 <br>
	 <label>Minu valitud taustavärv: </label><input name="bgcolor" type="color" value="<?php echo $mybgcolor; ?>"><br>
	 <label>Minu valitud tekstivärv: </label><input name="txtcolor" type="color" value="<?php echo $mytxtcolor; ?>"><br>
	 <br>
	 <input name="submitProfile" type="submit" value="Salvesta profiil">
  </form>
</body>
</html>





