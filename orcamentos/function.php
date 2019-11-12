<?php
include('../connectDb.php');

function retorna($produto, $conecta){

    $query = mysqli_query($conecta, "SELECT * FROM produtos WHERE pkId = '" . $_GET['ref'] . "' LIMIT 1");
	if(mysqli_num_rows($query) > 0){
		$result = mysqli_fetch_assoc($query);
		$json['preco'] = $result['precoVenda'];
        $json['estoque'] = $result['estoqueAtual'];
	}else{
		$json['preco'] = '0.00';
		$json['estoque'] = '000';
	}
	return json_encode($json);
}

if(isset($_GET['ref'])){
	echo retorna($_GET['ref'], $conecta);
}
?>