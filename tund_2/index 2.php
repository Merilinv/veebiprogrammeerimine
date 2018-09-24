<?php
  //echo "Siin on minu esimene PHP";
  $name = "Merilin";
  $surname = "Võrk";
  $todayDate = date("d.m.Y");
  $hourNow = date("H");
  $weekDayNow = date("N");
  echo $weekDayNow;
  var_dump($weekDayNamesET);
  echo $weekDayNamesET[0];
  $weekdayNamesET = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
  //echo $weekdayNamesET;
  //var_dump($weekdayNamesET);
  //echo $weekdayNamesET[1];
  //echo $weekdayToday;
  $hourNow = date("G");
  //echo $hourNow;
  $partOfDay = "";
  if ($hourNow < 8){
	    $partOfDay = "Varajane hommik";  
  }
  if ($hourNow >=8 and $hourNow <16){
	    $partOfDay = "kooliaeg";
  }
  if ($hourNow >= 16){
	    $partOfDay= "Vaba aeg";
  }
  
  //juhusliku pildi valimine
  $picURL = "http://www.cs.tlu.ee/~rinde/media/fotod/TLU_600x400/tlu";
  $picEXT = ".jpg";
  $picNUM = mt_rand (2,43);
  //echo $picNUM;
  $picFILE = $picURL . $picNUM . $picEXT;
  
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>
  <?php
  echo $name;
  echo " ";
  echo $surname;
  ?>
  , õppetöö</title>
</head>
<body>
  <h1>
  <?php 
  echo $name . " " . $surname; 
  ?>
  </h1>
  <p>See leht on valminud <a HREF="http://WWW.tlu.ee" target="_blank">
  TLÜ</a> õppetöö raames. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
  
  <p><b><i>Mina olen Tallinna Ülikooli värske tudeng Merilin! Mulle meeldib maalida ja sporti teha, jäätis meeldib mulle ka...</i></b></p>
  
  <p>Teised lehed: <a href="photo.php">photo.php</a>, <a href="page.php">page.php</a>.</p> 
  
  <?php 
     //echo "<p>Tänane kuupäev on: " .$todayDate ."</p> \n";
	 echo "<p>Täna on " .$weekDayNamesET[$weekDayNow - 1] .", " .$todayDate ."</p> \n";
	 echo "<p>Lehe avamise hetkel oli kell " .date("H:i:s") .", käes on " .$partOfDay .".</p> \n";
	?>
	
	 <!--<img src=
  "http://greeny.cs.tlu.ee/~rinde/veebiprogrammeerimine2018s/tlu_terra_600x400_1.jpg" alt="TLÜ Terra õppehoone">-->
  <img src=
  "../../../~rinde/veebiprogrammeerimine2018s/tlu_terra_600x400_1.jpg" alt="TLÜ Terra õppehoone">

  <p> Mul on sõber, kes ka teeb <a href= "../../~darikre"target="_blank">veebi</a> </p>
  
</body>
</html>	