<?php
require("../../../config.php");
   // echo $GLOBALS ["serverHost"];
   // echo $GLOBALS ["serverUsername"];
   // echo $GLOBALS ["serverPassword"];
   $database = "if18_merilin_v�_1";


function saveAMsg($msg){
 // echo "T��tab!";
 $notice = ""; //see on teade, mis antakse salvestamise kohta
 //loome �henduse andmebaasiserveriga
 $mysqli = new mysqli ($GLOBALS ["serverHost"], $GLOBALS ["serverUsername"], $GLOBALS ["serverPassword"], $GLOBALS ["database"]);
 //valmistame ette SQL p�ringu
 $stmt = $mysqli->prepare("INSERT INTO vpamsg (message) VALUES (?)");
 echo $mysqli->error;
 $stmt->bind_param ("s", $msg);  //s - string (tekst), i -tnteger (t�isarv), d - decimal (murdarv)
 if ($stmt->execute()){
	 $notice = 'S�num: "' .$msg . '" on salvestatud!';
 } else {
	 $notice = "S�numi salvestamisel tekkis t�rge: " .$stmt->error;
 }
 $stmt->close();
 $mysqli->close();
 return $notice;
}
?>