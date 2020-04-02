<?php 
	session_start();

	// Permet d'empêche d'accéder à la page si on est pas connecté

	if (! ( isset($_SESSION['idLogin']) && isset($_SESSION['user']) && $_SESSION['isConnected']) ) {
		header("Location: connexion.php");
		exit();
	}

	$user = $_SESSION['user'];
	$path = $_SESSION['path'];
	$idLogin = $_SESSION['idLogin'];
?>

<!DOCTYPE html>
<html>
<head>
	<title>The Courtroom</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
  	<script src="https://code.jquery.com/jquery-3.4.1.min.js" defer></script>
  	<!-- Je passe le contenu de la variable php idLogin vers une variable idLogin javascript pour 
  		l'utiliser dans mon script -->
  	<script type="text/javascript"> var idLogin = <?php echo $idLogin; ?> </script>
  	<script src="./js/script.js" defer></script>
</head>
<body id="body-chat">
	<div id="header-bar"> </div>
	<div id="wrapper-chat">
		<div id="wrapper-nav">
			<h3> Channels </h3>
			<nav id="room-nav">
		
			</nav>
			<div id="profile">
				<img id="profile-picture" src="<?php echo $path; ?>">
				<div id="profile-info">
					<h4> <?php echo $user; ?> </h4>
					<a href="deconnexion.php">Logout</a>
				</div>
			</div>
		</div>

		<div id="chatbox">
			<div id="chat-log">
		
			</div>
			<form id="chat-form">
				<p id="chat-content"> 
					<input class="input" type="text" name="message" id="chat-content-input" placeholder="Message" autocomplete="off" maxlength="200" required="">
				</p>
			</form>
		</div>
	</div>	
</body>
</html>