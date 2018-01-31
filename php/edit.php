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

	//Load the Profile
	$stmt = $pdo->prepare('SELECT * FROM Profile
		WHERE profile_id = :pid AND user_id = :id');
	$stmt->execute(array( ':pid' => $_REQUEST['profile_id'],
		':id' => $_SESSION['user_id']));
	$profile = $stmt->fetch(PDO::FETCH_ASSOC);
	if($profile === false) {
		$_SESSION['error'] = 'Could not load profile';
		header('Location: index.php');
		return;
	}
	
	//post request
	//validating data
	if ( isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) 
     && isset($_POST['headline']) && isset($_POST['summary']) ) {
  
		$msg = validateProfile();
     	if( is_string($msg) ) {
     		$_SESSION['error'] = $msg;
     		header("Location: edit.php?profile_id=".$_REQUEST["profile_id"]);
     		return;
     	}

     	//Validate position entries if present
     	$msg = validatePos();
     	if( is_string($msg) ) {
     		$_SESSION['error'] = $msg;
     		header("Location: edit.php?profile_id=".$_REQUEST["profile_id"]);
     		return;
     	}

     	$stmt = $pdo->prepare('UPDATE Profile SET 
     		first_name = :fn, last_name = :ln,
     		email = :em, headline = :he, summary = :su
     		WHERE profile_id = :pid AND user_id = :id');
     	$stmt->execute(array(
     		':pid' => $_REQUEST['profile_id'],
     		':id' => $_SESSION['user_id'],
	        ':fn' => $_POST['first_name'],
	        ':ln' => $_POST['last_name'],
	        ':em' => $_POST['email'],
	    	':he' => $_POST['headline'],
	    	':su' => $_POST['summary'])
     	);

		//Clear out the old position entries
		$stmt = $pdo->prepare('DELETE FROM Position 
			WHERE profile_id = :pid');
		$stmt->execute(array( ':pid' => $_REQUEST['profile_id']));
		//print_r($_POST);
		//Insert the position entries
		$rank = 1;
		for ($i=1; $i <= 9; $i++) { 
			if(! isset($_POST['year'.$i]) ) {
				$yy = $_POST['year'.$i.'_'];
				error_log("year $i is empty, skipping: $yy");
				continue;
			}
			if(! isset($_POST['desc'.$i]) ) {
				error_log("desc $i is empty, skipping");
				continue; 
			}
			$year = $_POST['year'.$i];
			$desc = $_POST['desc'.$i];

			$stmt = $pdo->prepare('INSERT INTO Position
				(profile_id, rank, year, description)
				VALUES ( :pid, :rank, :year, :desc)');
			$stmt->execute(array(
				':pid' => $_REQUEST['profile_id'],
				':rank' => $rank,
				':year' => trim($year),
				':desc' => trim($desc))
			);
			$rank++;
		}
 	
		$_SESSION['success'] = "Profile updated";
		header("Location: index.php");
		return;
	}
	//load up the positions rows
	$positions = loadPos($pdo, $_REQUEST['profile_id']);
?>

<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Kenji Sakuramoto</title>
</head>
<body>
<div class="container">
<h1>Editing Profile for <?= htmlentities($_SESSION['name']) ?> </h1>
<?php flashMessages(); ?>
<form method="post" action="edit.php">
<input type="hidden" name="profile_id" value=" <?= htmlentities($_GET['profile_id']); ?>"/>
<p>First Name:
<input type="text" name="first_name" value="<?= htmlentities($profile['first_name']); ?>"></p>
<p>Last Name:
<input type="text" name="last_name" value="<?= htmlentities($profile['last_name']); ?>"></p>
<p>Email:
<input type="text" name="email" value="<?= htmlentities($profile['email']); ?>"></p>
<p>Headline:
<input type="text" name="headline" value="<?= htmlentities($profile['headline']); ?>"></p>
<p>Summary:<br>
<input type="text" name="summary" rows="8" cols="80" value="<?= htmlentities($profile['summary']); ?>"></p>

<?php
	$pos = 0;
	echo('<p>Position: <input type="submit" id="addPos" value="+">'."\n");
	echo('<div id="position_fields">'."\n");
	foreach ($positions as $position) {
		$pos++;
		echo('<div id="position'.$pos.'">'."\n");
		echo('<p>Year: <input type="text" name="year'.$pos.'" ');
		echo(' value="'.$position['year'].'" />'."\n");
		echo('<input type="button" value="-" ');
		echo('onclick="$(\'#position'.$pos.'\').remove();return false;">'."\n");
		echo("</p>\n");
		echo('<textarea name="desc'.$pos.'" row="8" cols="80">'."\n");
		echo(htmlentities($position['description'])."\n");
		echo("\n</textarea>\n</div>\n");
	}
	echo("</div></p>\n");
 ?>
<p>
<input type="hidden" name="profile_id" value="<?= ($_GET['profile_id']); ?>"/>
<input type="submit" value="Save"/>
<input type="submit" name="cancel" value="Cancel"/>
</form>
</div></body></html>

<script>
	countPos = <?= $pos ?>;
	$(document).ready(function(){
		window.console && console.log('Document ready called');
		$('#addPos').click(function(event){
			event.preventDefault();
			if ( countPos >= 9 ) {
				alert("Maximum of nine position entries exceeded");
				return;
			}
			countPos++;
			window.console && console.log("Adding position "+countPos);
			$('#position_fields').append(
				'<div id="position'+countPos+'"> \
				<p>Year: <input type="text" name="year'+countPos+'"/> \
				<input type="button" value="-" \
				onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
				<textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
				</div>');
		});
	});
</script>
