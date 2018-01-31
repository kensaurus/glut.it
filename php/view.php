<?php
session_start();
$failure = false;
$success = false;
require_once "pdo.php";
if ( isset($_POST['logout'] ) ) {
    header("Location: login.php");
    return;
}

// if ( !isset($_SESSION['name'])) {
	// die("Not Logged In");
// }

if ( isset($_POST['year']) && isset($_POST['mileage']) ) {
	if (strlen($_POST['make'])<1){
		$failure = "Make is required";
	}
	else{
		if (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])){
			$failure = "Mileage and year must be numeric";
		}
		else{
			$stmt = $pdo->prepare('INSERT INTO autos(make, year, mileage) VALUES ( :mk, :yr, :mi)');
			$stmt->execute(array(
			':mk' => htmlentities($_POST['make']),
			':yr' => htmlentities($_POST['year']),
			':mi' => htmlentities($_POST['mileage']))
		);
			$success = "Record inserted.";
		}
	}
}

if(isset($_POST['delete']) && isset($_POST['auto_id'])){
	$sql = "DELETE FROM autos WHERE auto_id = :id";
	echo "<pre>\n$sql\n</pre>\n";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array(':id'=>$_POST['auto_id']));
}
?>


<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Kenji Sakuramoto</title>
</head>
<body>
<div class="container">
<h1>Resume Registry for 
<?php
echo($_SESSION['name']."</h1>\n");
if (isset($_SESSION['success'])){
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");	
	unset($_SESSION['success']);
}
// if ( $failure !== false ) {
    // echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
// }
// if ( $success !== false ) {
    // echo('<p style="color: green;">'.htmlentities($success)."</p>\n");
// }
?>

<h1>Automobiles</h1>
<ul>
<?php
$stmt = $pdo->query("SELECT auto_id, make, year, mileage FROM autos");
while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
	echo ("<li>");
	echo($row['year']." / ".$row['make']." / ".$row['mileage']." ");
	echo('<form method="post"><input type="hidden" ');
	echo('name="auto_id" value="'.$row['auto_id'].'">'."\n");
	echo ('<input type="submit" value="Delete" name="delete"');
	echo ("</li>\n");	
}
?>

<p><a href = "add.php">Add New</a> | <a href ="logout.php">Logout</a></p>
</ul>
</div>
</body>

