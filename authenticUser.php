<?php
ob_start();
include('connectDb.php');

if(isset($_POST["txtUsername"]) and isset($_POST["txtPassword"])) {
	$query = mysqli_query($conecta, "SELECT * FROM usuarios WHERE login = '" . trim($_POST["txtUsername"]) . "' AND senha = '" . sha1(trim($_POST["txtPassword"])) . "' LIMIT 1") or die(mysql_error);

    if(mysqli_num_rows($query) > 0) {
	   
		session_start();
        
        $result = mysqli_fetch_assoc($query);
        
        $titleSystem = "BCare Studio Integrado do Movimento";
        
        setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' );
        date_default_timezone_set( 'America/Sao_Paulo' );
        $datahoje = strftime( '%d/%m/%Y', strtotime( date( 'Y-m-d' ) ) );

        $_SESSION['nomeUsuario'] = $result['nome'];
        $_SESSION['emailUsuario'] = $result['email'];
        $_SESSION['telefoneUsuario'] = $result['telefone'];
		$_SESSION["username"] = base64_encode($_POST["txtUsername"]);
        $_SESSION["nome"] = $result['nome'];
        $_SESSION['conecta'] = 'SIM';
        $_SESSION['acesso'] =$result['nivelAcesso'];
        $_SESSION['pkId'] =$result['pkId'];
        $_SESSION['dataHoje']=date("Y-m-d");
        header('Location: index.php');
		exit;
		
	} else {

		$type = base64_encode("error");
        $msg = base64_encode("Este login e senha são inválidos! Por favor digite novamente.");
		header('Location: userLogin.php?msg=' . $msg . '&type=' . $type);
		exit;
		
	}
}
	
session_unset();
session_destroy();
header('Location: index.php');
exit;

?>