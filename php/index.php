<?php
require_once "pdo.php";
require_once "util.php";
require_once "bootstrap.php";
session_start();

$stmt = $pdo->query("SELECT * FROM profile");
$profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head><title>Kenji Sakuramoto</title></head>
<body>
<div class="container">
<h2>Resume Registry</h2>
<table border="1">

<?php
flashMessages();
if(isset($_SESSION['name'])){
	if (!$profiles===false){
		echo ("<tr><td>"."Name"."</td><td>"."Headline"."</td><td>"."Action");
		foreach($profiles as $profile){
			echo "<tr><td>";
			echo (htmlentities($profile['first_name'])." ".htmlentities($profile['last_name']));
			echo "</td><td>";
			echo (htmlentities($profile['headline']));
			echo "</td><td>";
			echo ('<a href="edit.php?profile_id='.$profile['profile_id'].'">Edit </a>'.'/ '.'<a href="delete.php?profile_id='.$profile['profile_id'].'">Delete</a>');
			echo ("</td></tr>\n");
		}
	}
	else{
		echo "No rows found\n";
	}
	echo('</table><br><a href="add.php">Add New Entry</a></br>');
	echo('<a href="logout.php">Logout</a>');
}
else{
	echo('<a href = "login.php">Please log in</a></br>');
}
?>
</table>


</div>
</body>
</html>

