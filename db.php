<?php
	
	/*
		* fonction dbConnect()
		* Permet de se connecter à la base de données
	*/
		
	function dbConnect() {
			try {
				$db = new PDO('mysql:host=localhost;dbname=vlp3247a;charset=utf8', 'gasp', 'gasp', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
			} catch (Exception $e) {
				die('Erreur : ' . $e->getMessage());
			}
			return $db;
	}