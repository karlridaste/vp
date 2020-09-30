<?php
$database="if20_karl_ri_1";
function signup($firstname, $lastname, $email, $gender, $birthdate, $password){
	$result=null;
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("INSERT INTO vpusers(firstname, lastname, birthdate, gender, email, password) VALUES(?,?,?,?,?,?)");
	echo $conn->error;
	
	//krüpteerime parooli
	$options = ["cost" => 12, "salt" =>substr(sha1(rand()), 0, 22)];
	$pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
	
	$stmt->bind_param("sssiss", $firstname, $lastname, $birthdate, $gender, $email, $pwdhash);
	if($stmt -> execute()){
		$result="ok";
	}else{
		$result=$stmt->error;
	}
	$stmt->close();
	$conn->close();
	return $result;
}//funktsioon signup lõppeb
function signin($email, $password){
	$result=null;
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt=$conn->prepare("SELECT password FROM vpusers WHERE email = ?");
	echo $conn->error;
	$stmt ->bind_param("s",$email);
	$stmt ->bind_result($passwordfromdb);
	if($stmt->execute()){
		if($stmt->fetch()){
			//kasutaja on olemas kui tuli vaste andmebaasist
			
			if(password_verify($password, $passwordfromdb)){
				//parool õige või vale 
				$stmt->close();
				$conn->close();
				header("Location: home.php");
				exit
			}else{
				//kahjuks vale parool
				//Tee koopia lehest home nt page, see on see kuhu kasutaja satub, korista ära lingid filmide ja mõtete juurde kogu jura semestrist ja aeg jne pilk jne, email ja parool 
				//siis kontrolli ja pane funktsioon käima ja hüpatakse home leheküljele 
				
			}
		}else{
			//kasutajat pole olemas
			$result="Kasutajat (".$email.") pole olemas!";
		}
			
	}else{
		$result=$stmt->error;
	}
	$stmt->close();
	$conn->close();
	return $result;
	
}