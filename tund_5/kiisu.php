<?php 
require ("functions.php");
set_include_path ('.:/usr/share/pear:/');
$notice = null;

if (isset($_POST["catName"])){
	$name = $_POST["catName"];
  }
if (isset($_POST["catColour"])){
	$colour = $_POST["catColour"];
  }
if (isset($_POST["catTail"])){
	$tail = $_POST["catTail"];
  }
  
?>

<!DOCTYPE html>
<html> 
  <head>
    <meta charset="utf-8">
	<title> Kasside tabel </title>
	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
  </head>
  <body>
    
    <h1>Lisa kassi andmed:</h1>
	
	<form method="POST">
	  <label>Nimi:</label>
	  <input name="catName" type="text" value="">
	   <label>Värvus:</label>
	  <input name="catColour" type="text" value=""> 
	  <label>Saba pikkus (cm):</label>
	  <input name="catTail" type="text" value="">
	  <br>
	  <br>
	  <input name="submitUserData" type="submit" value="Saada andmed">
	  </form>
	  
	  <?php
	  //if !empty($_POST["catName"]) and !empty($_POST["catColour"]) and !empty($_POST["catTail"])
	 // echo Palun täida kõik väljad!
	  ?>

</body>
</html>