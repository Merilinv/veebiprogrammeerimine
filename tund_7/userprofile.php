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
	header("Location:  index 3.php");
	exit();
  }
  $profile = userprofile();
  $mydescription = $profile["description"];
  $mybgcolor = $profile["bgcolor"];
  $mytxtcolor =$profile["txtcolor"];
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
	 <label>Kasutaja kirjeldus (max2000 märki) : </label>
	 <br>
     <textarea rows="10" cols="80" name="description"><?php echo $mydescription; ?></textarea>
	 <br>
	 <input name="submitDescription" type="submit" value="Salvesta kirjeldus">
	</form>
	<br>
	<p>
	<label>Minu valitud taustavärv: </label><input name="bgcolor" type="color" value="<?php echo $mybgcolor; ?>"><br>
	<label>Minu valitud tekstivärv: </label><input name="txcolor" type="color" value="<?php echo $mytxtcolor; ?>"><br>
	<br>
	<input name="submitprofile" type="submit" value="Salvesta värvivalik">
     
</body>
</html>





