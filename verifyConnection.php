<?php
ob_start();
session_start();

if(isset($_SESSION["username"])) {	
    echo "<script>window.location='index.php';</script>";
    exit;
} else {
	$type = base64_encode('alert-danger');
    $msg = base64_encode('Desculpe! Usuário não encontrado.');
    if($nivel==0){
        header('Location: userLogin.php');
        exit;	
    } 
    if($nivel==1){
        header('Location: ../userLogin.php');
        exit;
    }
    if($nivel==2){
        header('Location: ../../userLogin.php');
        exit;
    }
}

?>