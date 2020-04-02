<?php 
	date_default_timezone_set('Europe/Paris');

	require('db.php');

	/*
		* Envoi le contenu du message dans la base de données
		* Le timestamp est généré par php
	*/
		
	$db = dbconnect();
	if (isset($_GET['login']) && isset($_GET['content']) && isset($_GET['room'])) {
		$timeStamp = date('Y-m-d H:i:s', time());
		echo $timeStamp;
		$data = $db->prepare('INSERT INTO chat(timeSend,idLogin,content,idRoom) VALUES(?,?,?,?)');
		$data->execute(array($timeStamp, htmlspecialchars($_GET['login']), htmlspecialchars($_GET['content']), htmlspecialchars($_GET['room'])));
	}
