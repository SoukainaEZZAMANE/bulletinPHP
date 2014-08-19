<?php
//* les variables de connexion
$host="localhost";
$dbname="bulletin";
$password="";
$username="root";
$options=array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8');
try{
	$db= new PDO("mysql:host={$host};dbname={$dbname};charset=utf8",$username,$password,$options);
}
catch(PDOException $ex){
	die("Impossible de se connecter à la base de données: ".$ex->getMessage());
}
$db->SetAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$db->SetAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
header("Content-type:text/html, charset=utf-8");

?>
