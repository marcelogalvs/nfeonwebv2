<?php

$dbUsername = 'root';
$dbPassword = '';
$dbDataBase = 'nfeonwebv2';

/*
$dbUsername = 'u908549878_rshop';
$dbPassword = 'remanumalu1';
$dbDataBase = 'u908549878_rshop';
*/

$conecta=mysqli_connect("localhost" , $dbUsername , $dbPassword)or die(mysql_error());

if($conecta) {
    
    mysqli_select_db($conecta, $dbDataBase);
	
} else {
	
	$type = base64_encode("error");
    $msg = base64_encode("Falha ao tentar fazer conexão com o banco de dados. Tente mais tarde!");
    header('userLogin.php?msg=' . $msg . '&type=' . $type);
    exit;
	
}

?>