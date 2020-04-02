<?php 
	//header('Content-Type: application/json');
	
	require('db.php');
	
	$db = dbConnect();	
	$data = $db->query('SELECT * FROM room');
	$infos = $data->fetchAll();
	echo json_encode($infos);