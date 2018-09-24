<?php
require("../../../config.php");
   // echo $GLOBALS ["serverHost"];
   // echo $GLOBALS ["serverUsername"];
   // echo $GLOBALS ["serverPassword"];
   $database = "if18_merilin_võ_1";


function saveAMsg($msg){
 // echo "Töötab!";
 $notice = ""; //see on teade, mis antakse salvestamise kohta
 //loome ühenduse andmebaasiserveriga
 $mysqli = new mysqli ($GLOBALS ["serverHost"], $GLOBALS ["serverUsername"], $GLOBALS ["serverPassword"], $GLOBALS ["database"]);
 //valmistame ette SQL päringu
 $stmt = $mysqli->prepare("INSERT INTO vpamsg (message) VALUES (?)");
 echo $mysqli->error;
 $stmt->bind_param ("s", $msg);  //s - string (tekst), i -tnteger (täisarv), d - decimal (murdarv)
 if ($stmt->execute()){
	 $notice = 'Sõnum: "' .$msg . '" on salvestatud!';
 } else {
	 $notice = "Sõnumi salvestamisel tekkis tõrge: " .$stmt->error;
 }
 $stmt->close();
 $mysqli->close();
 return $notice;
}
?>