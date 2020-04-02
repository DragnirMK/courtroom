	<?php 
	//header('Content-Type: application/json');

	require('db.php');

	/*
		* Recupère les 10 derniers messages de la salle de chat (idRoom) correspondante
	*/
	
	$db = dbConnect();	

	$chatData = $db->prepare('(SELECT * FROM chat,login WHERE idRoom = ? AND chat.idLogin = login.idLogin ORDER BY idMessage DESC LIMIT 10) ORDER BY timeSend');
	$chatData->execute(array(htmlspecialchars($_POST['room'])));

	$chatInfos = $chatData->fetchAll();

	echo json_encode($chatInfos);
