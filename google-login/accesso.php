<?php
include 'dbconnection.php';
//include 'MySession.php';

session_start();





$username = $_POST['name'];
$password = $_POST['password'];
$username = filter_var($username, FILTER_SANITIZE_STRING);
$password = filter_var($password, FILTER_SANITIZE_STRING);
$_SESSION["utente"] = $username;

if(!$username || !$password){
	$error = 'Username e password sono obbligatori';
}

// Connessione al database
global $mysqli;

$password = hash('sha256', $password);
$mysqli-> real_escape_string($username);
$mysqli-> real_escape_string($password);
$query = $mysqli->query("SELECT * FROM login WHERE user = '$username' AND password = '$password';");
if($query->num_rows) {
	echo "Accesso consentito";
} else {
	echo "Accesso rifiutato";
}
$mysqli->close();

session_write_close();

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<h1>Risultati accesso</h1>
	<?php if ($error): ?>
		<p style="color: red"><?php echo $error ?></p>
	<?php else: header('Location: /home.php?user='.$username); ?>
	<?php endif ?>
        <?php exit();?>	
</body>
</html>

