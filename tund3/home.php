
<?php
//var_dump($_POST);
require("../../../config.php");
$database="if20_karl_ri_1";
if(isset($_POST["ideasubmit"]) and !empty($_POST["ideainput"])){
	//loome ühenduse andmebaasiga
	$conn = new mysqli($serverhost, $serverusername, $serverpassword, $database);
	//valmistan ette sql käsu andmete kirjutamiseks
    $stmt = $conn->prepare("INSERT INTO myideas (idea) VALUES(?)");
	echo $conn->error;
	//i - integer d- decimal s- string 
	$stmt->bind_param("s", $_POST["ideainput"]);
	$stmt->execute();
	$stmt->close();
	$conn->close();
}
//loen andmebaasist senised mõtted
$ideahtml = "";
$conn = new mysqli($serverhost, $serverusername, $serverpassword, $database);
$stmt = $conn->prepare("SELECT idea FROM myideas");
//seon tulemuse muutujaga
$stmt->bind_result($ideafromdb);
$stmt->execute();
while($stmt->fetch()){
	$ideahtml .= "<p>" . $ideafromdb . "</p>";
}
$stmt->close();
$conn->close();


$username = "Karl Oskar";
$fulltimenow = date("d.m.Y H:i:s");
$hournow=date("H");
$weekdaynameset = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
$monthnameset = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
$partofday="lihtsalt aeg";
//echo $weekdaynameset[1];
$weekdaynow = date("N");




if($hournow < 7){
	$partofday="uneaeg";
}
if ($hournow>=6 and $hournow < 8){
	$partofday="Hommikuste protseduuride aeg";
}
if($hournow >= 8 and $hournow <18){
	$partofday="Peoaeg!";
}
if ($hournow > 22){
	$partofday="Uneaeg";
}
//vaatame semestri kulgemist.
$semesterstart = new DateTime("2020-8-31");
$semesterend = new DateTime("2020-12-13");
//Selgitame välja nende vahe ehk erinevuse
$semesterduration = $semesterstart->diff($semesterend);
//leiame selle päevade arvuna 
$semesterdurationdays = $semesterduration->format("%r%a");
//leiame tänase päevaga samamoodi saad kasutada et semester polegi peale hakanud.
$today = new DateTime("now");
// Leia tänase päevaga pikkus, seejärel if fromsemesterstartdays < 0{siis pole semester veel peale hakanud}
$durationday = $semesterstart->diff($today);

// Kui päevad on läbi siis ütle et õppetöö on läbi ja pane echo käsuga välja kuskile 
//samuti leia protsent palju jäänud on oh boy dis gon be fun
//kodutöö Kolmapäev 16 september 2020 kell xx.xx
//loeme kataloogist pltide nimekirja
$allfiles = scandir ("../vp_pics/");
$picfiles = array_slice($allfiles, 2);
$imghtml = "";
$piccount = count($picfiles);
//$i += 3 töötaks ainult 2 korda
for($i = 0;$i < $piccount;$i ++){
	//<img src="pildifail" alt="tekst">
	$imghtml .= '<img src="../vp_pics/'.$picfiles[$i].'" alt= "Tallinna ülikool">';
	}
	require("header.php");
?>

<img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse logo">
<h1><?php echo $hournow; ?> </h1>
  <h1 style="color:red;"> Autobiograafia </h1>
  <p>Tänapäeval on autod liigselt lihtsasti kasutatavad. Loodan samuti, et keegi ei näe mind ja sind ja teisi jne </p>
  <h2><?php echo $username ." Minu lemmik mees"; ?> </h2>
  <p>See on tõepoolest vaid õppimise jaoks loodud, eks ole nii!</p>
  <a href="https://www.tlu.ee">Tallinna Ülikooli</a>
  <p>SIIN VEEBILEHEKÜLJEL PUUDUB PÄRIS SISU, LOODUD VAID ÕPPE EESMÄRGIL</p>
  <p>Lehe avamise hetkel oli kellaaeg ja päev meie serveris: <?php echo $weekdaynameset[$weekdaynow-1] . ", " .  $fulltimenow; ?>.</p>
  <p><?php echo "Parajasti on ". $partofday. ".";?></p>
 
  <hr>
  <?php echo $imghtml;?>
  <hr>
  <form method="POST">
  <label>kirjutage oma esimene pähe tulev mõte</label>
  <input type="text" name="ideainput" placeholder="mõttekoht">
  <input type="submit" name = "ideasubmit" value="Saada mõte teele!">
  </form>
  <hr>
  <?php echo $ideahtml; ?>
</body>
</html>









