<?php
require("../../../config.php");
   // echo $GLOBALS ["serverHost"];
   // echo $GLOBALS ["serverUsername"];
   // echo $GLOBALS ["serverPassword"];
   $database = "if18_merilin_v�_1";
   
function signup($name,$surName,$email,$gender, $birthDate, $password){
	$notice = "";
	$mysqli = new mysqli($GLOBALS ["serverHost"], $GLOBALS ["serverUsername"], $GLOBALS ["serverPassword"], $GLOBALS ["database"]);
	$stmt = $mysqli->prepare("INSERT INTO vpusers3(firstname, lastname, birthdate, gender, email, password) VALUES(?,?,?,?,?,?)");
	echo $mysqli->error;
	//kr�pteerin parooli, kasutades juhuslikku soolamisfraasi (salting string)
	$options = [
	"cost" => 12,
    "salt" => substr(sha1(rand()), 0, 22),
	];
	$pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
	$stmt->bind_param("sssiss",$name,$surName,$email,$gender, $birthDate, $pwdhash);
	if($stmt->execute()){
		$notice = "OK";
	} else {
		$notice = "error";
	}
	$stmt->close();
    $mysqli->close();
    return $notice;
}


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

 function readallmessages(){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT message FROM vpamsg3");
	echo $mysqli->error;
	$stmt->bind_result($msg);
	$stmt->execute();
	while($stmt->fetch()){
		$notice .= "<p>" .$msg ."</p> \n";
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
  function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>