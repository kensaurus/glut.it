<?php
require_once "pdo.php";
require_once "util.php";
require_once "bootstrap.php";
session_start();

if (!isset($_SESSION['user_id'])){
	die("ACCESS DENIED");
}

if (isset($_POST['cancel'])){
	header('Location: index.php');
	return;
}

if(!isset($_REQUEST['profile_id'])){
	$_SESSION['error']="Missing profile_id";
	header('Location: index.php');
	return;
}

// Clear out the old position entries
$stmt = $pdo->prepare('DELETE FROM position
WHERE profile_id=:pid');
$stmt->execute(array( ':pid' => $_REQUEST['profile_id']));

// Insert the position entries
$rank = 1;
for($i=1; $i<=9; $i++) {
	if ( ! isset($_POST['year'.$i]) ) continue;
	if ( ! isset($_POST['desc'.$i]) ) continue;
	$year = $_POST['year'.$i];
	$desc = $_POST['desc'.$i];
	$stmt = $pdo->prepare('INSERT INTO Position
	(profile_id, rank, year, description)
	VALUES ( :pid, :rank, :year, :desc)');
	$stmt->execute(array(
	':pid' => $_REQUEST['profile_id'],
	':rank' => $rank,
	':year' => $year,
	':desc' => $desc)
	);
	$rank++;
}

//Incoming data
if ( isset($_POST['first_name']) || isset($_POST['last_name']) || isset($_POST['email']) || isset($_POST['headline']) || isset($_POST['summary'])) {
	$msg = validateProfile();
	if (is_string($msg)){
		$_SESSION['error'] = $msg;
		header("Location: edit.php?profile_id=".$_REQUEST["profile_id"]);
		return;
	}
	else{
    $sql = "UPDATE profile SET first_name=:fn, last_name=:ln, email=:em, headline=:he, summary=:su 
	WHERE user_id=:id AND profile_id=:pid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
		':pid' => $_REQUEST['profile_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
		':em' => $_POST['email'],
		':id' => $_SESSION['user_id'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary']));
    $_SESSION['success'] = 'Record edited';
    header( 'Location: index.php' ) ;
    return;
		}
}

$stmt = $pdo->prepare("SELECT * FROM profile where profile_id=:pid and user_id=:id");
$stmt->execute(array(':pid' => $_REQUEST['profile_id'], ':id' => $_SESSION['user_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = 'Could not load profile';
    header( 'Location: index.php' ) ;
    return;
}

$fn = htmlentities($row['first_name']);
$ln = htmlentities($row['last_name']);
$em = htmlentities($row['email']);
$he = htmlentities($row['headline']);
$su = htmlentities($row['summary']);
$id = $row['user_id'];

$positions = loadPos($pdo, $_REQUEST['profile_id']);
?>

<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Kenji Sakuramoto</title>
</head>
<body>
<div class="container">
<p>Editing profile for <?= htmlentities($_SESSION['name']); ?></p>
<?php flashMessages(); ?>
<form method="post" action="edit.php">
<p>First Name:
<input type="text" name="first_name" value="<?=$fn?>"></p>
<p>Last Name:
<input type="text" name="last_name" value="<?=$ln?>"></p>
<p>Email:
<input type="text" name="email" value="<?=$em?>"></p>
<p>Headline:
<input type="text" name="headline" value="<?=$he?>"></p>
<p>Summary: <br>
<textarea name="summary" rows ="5" cols="60" value="<?=$su?>"></textarea>
<?php
$pos = 0;
echo('<p>Position: <input type="submit" value="+" id="addPos">'."\n");
echo('<div id="position_fields>'."\n");
foreach ($positions as $position){
	$pos++;
	echo('<div id="position'.$pos.'">'."\n");
	echo('<p>Year: <input type="text" name="year'.$pos.'" value="'.$position['year'].'" />'."\n");
	echo('<input type ="button" value="-" ');
	echo('onclick="$(\'#position'.$pos.'\').remove();return false;">'."\n");
	echo(htmlentities($position['description'])."\n");
	echo("\n</textarea>\n</div>\n");
}
echo("</div></p>\n");
?>

</div>
<input type="hidden" name="user_id" value="<?=$id?>">
<input type="hidden" name="profile_id" value="<?= htmlentities($_GET['profile_id']); ?>">
<p><input type="submit" value="Save"/>
<a href="index.php">Cancel</a></p>
</form>
</div></body></html>

<script>
countPos=<?=$positions?>;
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
