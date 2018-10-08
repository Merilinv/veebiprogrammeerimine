<?php
require("../../../config.php"); //<-andmebaasi server kasutaja ja parool
   // echo $GLOBALS ["serverHost"];
   // echo $GLOBALS ["serverUsername"];
   // echo $GLOBALS ["serverPassword"];
   $database = "if18_merilin_võ_1";
   
   //Alustan sessiooni
   session_start();
   
   function readmsgforvalidation($editId){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]); //mille poole andmebaas pöördub
	$stmt = $mysqli->prepare("SELECT message FROM vpamsg WHERE id = ?"); //prepare = SQL käsk FROM- mis andmetabelist WHERE- täpsed parameetrid mida soovite.
	$stmt->bind_param("i", $editId); //? vahetatakse õigete andmete vastu
	$stmt->bind_result($msg);
	$stmt->execute(); //käsu täitmine
	if($stmt->fetch()){ //saadakse andmed kätte
		$notice = $msg;
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
  function readallunvalidatedmessages(){
	$notice = "<ul> \n";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT id, message FROM vpamsg WHERE valid IS NULL ORDER BY id DESC");
	echo $mysqli->error;
	$stmt->bind_result($id, $msg);
	$stmt->execute();
	
	while($stmt->fetch()){
		$notice .= "<li>" .$msg .'<br><a href="validatemessage.php?id=' .$id .'">Valideeri</a>' ."</li> \n";
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
function signin($email, $password) {
	$notice = "";
	$mysqli = new mysqli($GLOBALS ["serverHost"], $GLOBALS ["serverUsername"], $GLOBALS ["serverPassword"], $GLOBALS ["database"]);
	$stmt = $mysqli->prepare("SELECT id, firstname, lastname, password FROM vpusers3 WHERE email=?");
	echo $mysqli->error;
	$stmt->bind_param("s",$email);
	$stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb, $passwordFromDb);
	if($stmt->execute()){
		//Kui päring õnnestus
		if($stmt->fetch()){
			//kasutaja on olemas
			if(password_verify($password, $passwordFromDb)){
				//Kui salasõna klapib
				$notice = "Logisite sisse!";
				//määran sessiooni muutujad
				$_SESSION["userId"] = $idFromDb;
				$_SESSION["userFirstName"] = $firstnameFromDb;
				$_SESSION["userLastName"] = $lastnameFromDb;
				$_SESSION["userEmail"] = $email;
				//Liigume kohe vaid sisselogitutele mõeldud pealehele
				
				 $stmt->close();
                 $mysqli->close();
	             header("Location: main.php");
				exit();
			} else {
				
			$notice = "Vale salasõna!";
			}
		} else {
			$notice = "Sellist kasutajat (" .$email .") ei leitud!";
		}
	} else {
		$notice = "Sosselogimisel tekkis tehniline viga!" .$stmt->error;
	}
	
	$stmt->close();
    $mysqli->close();
    return $notice;
}//Sisselogimine lõppeb

  //Kasutaja salvestamine
function signup($name,$surName,$email,$gender, $birthDate, $password){
	$notice = "";
	$mysqli = new mysqli($GLOBALS ["serverHost"], $GLOBALS ["serverUsername"], $GLOBALS ["serverPassword"], $GLOBALS ["database"]);
	$stmt = $mysqli->prepare("INSERT INTO vpusers3(firstname, lastname, birthdate, gender, email, password) VALUES(?,?,?,?,?,?)");
	echo $mysqli->error;
	//krüpteerin parooli, kasutades juhuslikku soolamisfraasi (salting string)
	$options = [
	"cost" => 12,
    "salt" => substr(sha1(rand()), 0, 22),
	];
	$pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
	echo "Kuupäev: ".$birthDate;
	//viga oli järgmises reas - muutujate järjekord ei vastanud SQL käsus loetletud väljade andmejärjekorrale ning kuupäeva väljaleüritati e-maili kirjutada.
	//$stmt->bind_param("sssiss", $name, $surName, $email, $gender, $birthDate, $pwdhash);
	$stmt->bind_param("sssiss", $name, $surName, $birthDate, $gender, $email, $pwdhash);

	if($stmt->execute()){
		$notice = "OK";
	} else {
		$notice = "error"; //.$stmt->error;
	}
	$stmt->close();
    $mysqli->close();
    return $notice;
}


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