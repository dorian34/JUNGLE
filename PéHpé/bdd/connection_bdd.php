<?php
	//***************************************************//
	//		Connection/Creation de base de données		//
	//***************************************************//
	

/*nom de la base de donnée*/	
$dbname='jungle';

/*verification du support de sqlite 3*/
if(!class_exists('SQLite3')){
  die("!!!!!! SQLite 3 NOT supported. !!!!!!");
}

/*verifie si la base n'existe pas deja*/
$dbname=mysql_query("SHOW DATABASES LIKE ".$dbname."");
if (!$dbname){
	echo 'La base n\'existe pas.';
	/*Tentative de creation de la base*/
	try{
		$jungleBase = new SQLite3($dbname);
		echo "SQLite 3 supported.";
		
		/*Tentative de creation des tables*/
		//$db->exec('CREATE TABLE bar (bar STRING)');
		//$db->exec('CREATE TABLE bar (bar STRING)');
	}catch(Exception $e){ 
		echo "Erreur : ".$e->getMessage();
	}
}
else echo 'Cette base existe déjà.';

?>