<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if(isset($_GET["email"]) || isset($_GET["senha"]) ){
    
	if( !empty($_GET["email"])  || !empty($_GET["senha"])  ){
        
        $conexao = new mysqli("localhost", "root", "", "bcarestudio");
        
		$email= mysqli_real_escape_string($conexao,$_GET["email"]);
        $senha= mysqli_real_escape_string($conexao, $_GET["senha"]);
        
		$query="SELECT * FROM alunos where email='$email' and senha='$senha'  ";
        $result = $conexao->query($query);
        
        $outp = "";
        
		if( $rs=$result->fetch_array()) {
			if ($outp != "") {$outp .= ",";}
			$outp .= '{"codigo":"' . $rs["pkId"] . '",';
            $outp .= '"email":"' . $rs["email"] . '",';
            $outp .= '"nascimento":"' . $rs["nascimento"] . '",';
            $outp .= '"nome":"' . $rs["nome"] . '"}';
            
            
            $outp ='{"msg": {"logado": "sim", "texto": "logado com sucesso!", "nome": "' . $rs['nome'] . '", "nascimento": "' . $rs['nascimento'] . '", "pkId": "' . $rs['pkId'] . '"}, "dados": '.$outp.'}';
            
		}else{
            
            $outp ='{"msg": {"logado": "nao", "texto": "login ou senha invalidos!"}, "dados": {'.$outp.'}}';
           
            
        }
      
		//$conn->close();
       
        echo utf8_encode($outp);
        
        
	}
}
?>