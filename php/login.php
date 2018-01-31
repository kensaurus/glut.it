<?php 
require_once "pdo.php";
require_once "util.php";
session_start();
if ( isset($_POST['cancel'] )) {
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
// $stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123

if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        $_SESSION['error'] = "User name and password are required";
		header("Location: login.php");
		return;
    }
	if ( strpos($_POST['email'],'@')== false){
			$_SESSION['error'] = "Email must have an at-sign (@)";
			header("Location: login.php");
			return;
	}
	else{
		$check = hash('md5', $salt.$_POST['pass']); //concatenate salt and hash
		$stmt = $pdo->prepare('SELECT user_id, name FROM users
		WHERE email = :em AND password = :pw');
		$stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($row !==false){
			error_log("Login success ".$_POST['email']);
			$_SESSION['name']=$row['name'];
			$_SESSION['user_id'] = $row['user_id'];
			// Redirect the browser to index.php
			header("Location: index.php");
			return;
		}		
		else {
            $_SESSION['error'] = "Incorrect password";
			error_log("Login fail ".$_POST['email']." ".$check);
			header("Location: login.php");
			return;
        }
    }
}

?>

<script>
function doValidate() {
	console.log('Validating...');
	try {
		addr = document.getElementById('email').value;
		pw = document.getElementById('id_1723').value;
		console.log("Validating addr="+addr+" pw="+pw);
		if (addr ==null|| addr=="" || pw == null || pw == "") {
			alert("Both fields must be filled out");
			return false;
		}
		if (addr.indexOf('@')==-1){
			alert("Invalid address");
			return false;
		}
		return true;
	} catch(e) {
		return false;
	}
	return false;
}
</script>

<!DOCTYPE html>
<html>
<head>

<?php require_once "bootstrap.php"; ?>
<title>Kenji Sakuramoto</title>
</head>
<body>
<div class="container">
<a><h1>Please log in</h1></a>
<?php flashMessages(); ?>
<form method="POST">
User Name<input type="text" name="email"><br/>
Password<input type="password" name="pass" id="id_1723"><br/>
<input type="submit" onclick="return doValidate();" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
</div>
</body>
