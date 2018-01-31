<?php
require_once "pdo.php";
session_start();

if (!isset($_SESSION['user_id'])){
	die("ACCESS DENIED");
}

if ( isset($_POST['delete']) && isset($_GET['user_id']) ) {
    $sql = "DELETE FROM profile WHERE user_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':id' => $_GET['user_id']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['user_id']) ) {
  $_SESSION['error'] = "Missing ID";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT last_name, first_name FROM profile where user_id = :id");
$stmt->execute(array(":id" => $_GET['user_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for ID';
    header( 'Location: index.php' ) ;
    return;
}

?>

<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Kenji Sakuramoto</title>
</head>
<body>
<div class="container">
<p>Confirm: Deleting <?= htmlentities($row['first_name']." ".$row['last_name']) ?></p>

<form method="post">
<input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
<input type="submit" value="Delete" name="delete">
<a href="index.php">Cancel</a>
</form>
</div></body></html>
