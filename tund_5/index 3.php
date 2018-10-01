<?php
 $name = "Tundmatu";
  $surName = "inimene";
  $fullName = $name . " " . $surName;
  $birthMonth = date("m");
  $birthYear = date("Y") - 15;
  $birthDay = date("d");
  $monthNamesET = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni","juuli", "august", "september", "oktoober", "november", "detsember"];
  
  //var_dump($_POST);
  if (isset($_POST["firstName"])){
	//$name = $_POST["firstName"];
	$name = test_input($_POST["firstName"]);
  }
  if (isset($_POST["surName"])){
	//$surName = $_POST["surName"];
	$name = test_input($_POST["surName"]);
  }
  
  function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function fullName() {
 //$fullName = $name . " " . $surName;
 $GLOBALS["fullName"] = $GLOBALS["name"]. " " . $GLOBALS["surName"];
 echo fullName;
}
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title> Katseline veeb</title>
  
</head>
<body>
  <h1> Teretulemast </h1>
  <p>See leht on valminud <a HREF="http://WWW.tlu.ee" target="_blank">
  TLÜ</a> õppetöö raames. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
  <hr>
  <p><a href="newuser.php">Loo kasutaja</a>!</p>
 
  
</body>
</html>	