<?php
require_once "pdo.php";
require_once "util.php";
require_once "bootstrap.php";
session_start();

if (!isset($_SESSION['user_id'])){
	die("ACCESS DENIED");
}
if (isset($_POST['cancel'])){
	header("Location: index.php");
	return;
}

if ( isset($_POST['first_name']) || isset($_POST['last_name']) || isset($_POST['email']) || isset($_POST['headline']) || isset($_POST['summary'])) {
	//util.php
	$msg = validateProfile();
	if (is_string($msg)){
		$_SESSION['error'] = $msg;
		header("Location: add.php");
		return;
	}
	$stmt = $pdo->prepare('INSERT INTO profile
		(user_id, first_name, last_name, email, headline, summary) 
		VALUES (:uid, :fn, :ln, :em, :he, :su)');
	$stmt->execute(array(
		':uid' => $_SESSION['user_id'],
		':fn' => $_POST['first_name'],
		':ln' => $_POST['last_name'],
		':em' => $_POST['email'],
		':he' => $_POST['headline'],
		':su' => $_POST['summary'])
	);
	$profile_id = $pdo->lastInsertId();
	$rank = 1;
	validatePos();
	$stmt = $pdo->prepare('INSERT INTO position
		(profile_id, rank, year, description)
		VALUES ( :pid, :rank, :year, :desc)');
	$stmt->execute(array(
		':pid' => $profile_id,
		':rank' => $rank,
		':year' => $_POST['year'],
		':desc' => $_POST['description'])
	);
	$rank++;
	$_SESSION['success'] = "Profile added ";
	header("Location: index.php");
	return;
}

if(isset($_POST['delete']) && isset($_POST['user_id'])){
	$sql = "DELETE FROM profile WHERE user_id = :id";
	echo "<pre>\n$sql\n</pre>\n";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array(':id'=>$_POST['user_id']));
}
?>

<html>
<head>
<title>Kenji Sakuramoto</title>
</head>
<body>
<div class="container">
<h1>Adding Profile for <?= $_SESSION['name'] ?></h1>
<?php
flashMessages();
?>
<form method="post">
<p>First Name:
<input type="text" name="first_name" value=></p>
<p>Last Name:
<input type="text" name="last_name"></p>
<p>Email:
<input type="text" name="email"></p>
<p>Headline:<br>
<input type="text" name="headline"></p>
<p>Summary:<br>
<textarea name="summary" rows ="5" cols="60"></textarea>
</p>
<p>Position: <input type="submit" value="+" id="addPos"/>
<div id="position_fields">
</div>
<p><input type="submit" value="Add"/>
<input type="submit" value="Cancel" name="cancel"/>
</form>
</div></body></html>

<script>
countPos=0;
$(document).ready(function(){
	window.console && console.log('Document ready...');
	$('#addPos, #removePos').click(function(event){
		event.preventDefault();
		if($(event.target).attr('id')=='addPos'){
			if(countPos>=9){
				alert('Maximum entries exceeded...');
				return;
			}
			countPos++;
			window.console && console.log("Adding position"+countPos);
			$('#position_fields').append(
				'<div id="position'+countPos+'"> \
				Year: <input type="text" name="year"> \
				<input type="submit" value="-" id="removePos" onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
				<textarea id="desc'+countPos+'" rows="5" cols="60" name="description"></textarea> \
				</div>');			
		}
		else if($(event.target).attr('id')=='removePos'){
			countPos--;
			window.console && console.log("Remove position"+countPos);
		}
	});
});
</script>
