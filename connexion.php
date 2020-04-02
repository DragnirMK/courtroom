<?php 
	session_start();

	require('db.php');

	/*
		* fonction checkLogin()
		* Interroge la base de données et vérifie si l'utilisateur et le mot de passe sont correct
		* La fonction "addslashes" prévient la faille injection SQL
		* La fonction "htmlspecialchars" prévient la faille XSS
		* Si le mot de passe est vérifié, alors les infos du idLogin, user et path sont envoyés dans des 
		* variables $_SESSION
	*/

	function checkLogin() {
		if (isset($_POST['user']) && isset($_POST['password'])) {
			$db = dbConnect();
			$userRequest = $db->prepare('SELECT * FROM login WHERE user = ?');
			$user = addslashes($_POST['user']);
			$userRequest->execute(array(htmlspecialchars($user)));

			if ($userRequest->rowCount() === 0) return -1;

			$result = $userRequest->fetch();
			$hashedPassword = $result['password'];

			if (password_verify(htmlspecialchars($_POST['password']), $hashedPassword)) {
				$_SESSION['idLogin'] = $result['idLogin'];
				$_SESSION['user'] = $result['user'];
				$_SESSION['path'] = $result['path'];
				return 0;
			} 
		}

		return -2;		
	}

	/*
		* fonction postLogIn()
		* Appelle la fonction checkLogin() après l'envoi du formulaire de connexion
		* Si elle réussit, alors on indique que l'on est connecté dans une variable $_SESSION
		* et l'utilisateur est redirigé vers affiche.php
		* Sinon l'utilisateur reste sur la page de connexion
	*/

	function postLogIn() {
		$result = checkLogin();
		if ($result == 0) {
			$_SESSION['isConnected'] = true;
			header("Location: affiche.php");
		} 
	}

	postLogIn();
?>

<!--
	Nom : VALENTIN Pierre S4C
	
	Cette application de messagerie instantanée s'inspire de l'univers du jeu vidéo "Ace Attorney".
	Le jeu, les personnages et les images appartiennent à la société Capcom.

	COMPTES POUR ACCEDER AU SITE

	Utilisateur :	Phoenix Wright
	Mot de passe:	AceAttorney

	Utilisateur :	Miles Edgeworth
	Mot de passe:	AceProsecutor

	Utilisateur :	Judge
	Mot de passe:	Law&Order

	Utilisateur :	Dick Gumshoe
	Mot de passe:	BestDetective

	Utilisateur :	Apollo Justice
	Mot de passe:	NeverGiveUp

	Utilisateur :	Athena Cykes
	Mot de passe:	MoodMatrix
!-->

<!DOCTYPE html>
<html>
<head>
	<title>Welcome on The Courtroom</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body id="body-connexion">
	<header id="logo"> 
		<img src="./img/logo.png">
	</header>
	<div id="wrapper">
		<h2 id="title"> Welcome back ! </h2>
		<h3 id="subtitle"> Are you working on a new case ? </h3>
		<form id="connexion-form" action="connexion.php" method="post">
			<p id="login-input">
				<h5> Username </h5>
				<input class="input" type="text" id="user-login" name="user" required="">
			</p>
			<p id="password-input">
				<h5> Password </h5>
				<input class="input" type="password" id="user-password" name="password" required="">
			</p>
			<button type="submit" class="login-submit"> Login </button>		
		</form>
	</div>
</body>
</html>