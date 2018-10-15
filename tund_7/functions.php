<?php
require("../../../config.php"); //<-andmebaasi server kasutaja ja parool
   // echo $GLOBALS ["serverHost"];
   // echo $GLOBALS ["serverUsername"];
   // echo $GLOBALS ["serverPassword"];
   $database = "if18_merilin_v�_1";
   
   //Alustan sessiooni
   session_start();
   
   function userprofile(){
	  $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]); 
	  $stmt = $mysqli->prepare("SELECT description, bgcolor, txtcolor FROM vpuserprofiles WHERE userid = ? "); // stmt on SQL K�SK
	  echo $mysqli->error;
	  $stmt->bind_param("i", $_SESSION["userId"]);
	  $stmt->bind_result($description, $bgcolor, $txtcolor);
	  $stmt->execute();
	  if ($stmt->fetch()){ //massiiv
		  $profile = [ 
	"description" => $description,
    "bgcolor" => $bgcolor,
	"txtcolor" => $txtcolor
	];
		  
	  } else {
		  $profile = [ 
	"description" => "Pole iseloomustust",
    "bgcolor" => "#FFFFFF",
	"txtcolor" => "#000000"
	];
		  
	  }
	  $stmt->close();
	   $mysqli->close();
	   return $profile;
   }
   
   function readallvalidatedmessagesbyuser(){
	   $msghtml = "";
	   $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	   $stmt = $mysqli->prepare("SELECT id, firstname, lastname FROM vpusers3");
	   echo $mysqli->error;
	   $stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb);
	   
	   $stmt2 = $mysqli->prepare("SELECT message, valid FROM vpamsg WHERE validator=?");
	   
	   $stmt2->bind_param("i", $idFromDb);
	   $stmt2->bind_result($msgFromDb, $validFromDb);
	   
	   $stmt->execute();
	   
	   $stmt->store_result(); //j�tab saadu pikemalt meelde, nii saab ka j�rgmine p�ring seda kasutada
	   
	   while($stmt->fetch()){ //while ts�kkel -> kuni saab fetchida
		   //panen valideerija nime paika
		   $msghtml .="<h3>" .$firstnameFromDb ." " .$lastnameFromDb ."</h3> \n";
		   $stmt2->execute(); //p��ab fetchida k�iki v�imalikke tulemusi
		   while($stmt2->fetch()){
			   $msghtml .= "<p><b>";
			   if($validFromDb == 0){
				   $msghtml .= "Keelatud: ";	   
			   } else {
				   $msghtml .= "Lubatud: ";
			   }
			   $msghtml .= "</b>" .$msgFromDb ."</p> \n";
		   }
		   
	   }
	   $stmt->close();
	   $stmt2->close();
	   $mysqli->close();
	   return $msghtml;
   }
   
   function listusers(){
	   $notice = "";
	   $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	   $stmt = $mysqli->prepare("SELECT firstname, lastname, email FROM vpusers3 WHERE id !=?");
	   //$stmt = $mysqli->prepare("SELECT firstname, lastname, email, description FROM vpusers3, vpuserprofiles WHERE vpuserprofiles.userid=vpusers3.id");
	
	   $mysqli->error;
	   $stmt->bind_param("i", $_SESSION["userId"]);
	   $stmt->bind_result($firstname, $lastname, $email);
	   //$stmt->bind_result($firstname, $lastname, $email, $description);
	   if($stmt->execute()){
	     $notice .= "<ol> \n";
	     while($stmt->fetch()){
		   $notice .= "<li>" .$firstname ." " .$lastname .", kasutajatunnus: " .$email ."</li> \n";
		   //$notice .= "<li>" .$firstname ." " .$lastname .", kasutajatunnus: " .$email ."<br>" .$description ."</li> \n";
	      }
	      $notice .= "</ol> \n";
	     } else {
		     $notice = "<p>Kasutajate nimekirja lugemisel tekkis tehniline viga! " .$stmt->error;
	}
	
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
  function allvalidmessages(){
	$html = "";
	$valid = 1;
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT message FROM vpamsg WHERE valid=? ORDER BY validated DESC");
	echo $mysqli->error;
	$stmt->bind_param("i", $valid);
	$stmt->bind_result($msg);
	$stmt->execute();
	while($stmt->fetch()){
		$html .= "<p>" .$msg ."</p> \n";
	}
	$stmt->close();
	$mysqli->close();
	if(empty($html)){
		$html = "<p>Kontrollitud s�numeid pole.</p>";
	}
	return $html;
  }
function validatemsg($editId, $validation){
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("UPDATE vpamsg SET validator=?, valid=?, validated=now() WHERE id=?");
	$stmt->bind_param("iii", $_SESSION["userId"], $validation, $editId);
	if($stmt->execute()){
	  echo "�nnestus";
	  header("Location: validatemsg.php");
	  exit();
	} else {
	  echo "Tekkis viga: " .$stmt->error;
	}
	$stmt->close();
	$mysqli->close();
  }
   
   
   function readmsgforvalidation($editId){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]); //mille poole andmebaas p��rdub
	$stmt = $mysqli->prepare("SELECT message FROM vpamsg WHERE id = ?"); //prepare = SQL k�sk FROM- mis andmetabelist WHERE- t�psed parameetrid mida soovite.
	$stmt->bind_param("i", $editId); //? vahetatakse �igete andmete vastu
	$stmt->bind_result($msg);
	$stmt->execute(); //k�su t�itmine
	if($stmt->fetch()){ //saadakse andmed k�tte
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
		//Kui p�ring �nnestus
		if($stmt->fetch()){
			//kasutaja on olemas
			if(password_verify($password, $passwordFromDb)){
				//Kui salas�na klapib
				$notice = "Logisite sisse!";
				//m��ran sessiooni muutujad
				$_SESSION["userId"] = $idFromDb;
				$_SESSION["userFirstName"] = $firstnameFromDb;
				$_SESSION["userLastName"] = $lastnameFromDb;
				$_SESSION["userEmail"] = $email;
				//Liigume kohe vaid sisselogitutele m�eldud pealehele
				
				 $stmt->close();
                 $mysqli->close();
	             header("Location: main.php");
				exit();
			} else {
				
			$notice = "Vale salas�na!";
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
}//Sisselogimine l�ppeb

  //Kasutaja salvestamine
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
	echo "Kuup�ev: ".$birthDate;
	//viga oli j�rgmises reas - muutujate j�rjekord ei vastanud SQL k�sus loetletud v�ljade andmej�rjekorrale ning kuup�eva v�ljale�ritati e-maili kirjutada.
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