	<?php 
	//header('Content-Type: application/json');

	require('db.php');

	/*
		* Recupère le nombre total de messages dans la table Chat
	*/
	
	$db = dbConnect();	

	$chatData = $db->query('SELECT COUNT(*) FROM chat');

	$chatInfos = $chatData->fetch();

	echo json_encode($chatInfos);
