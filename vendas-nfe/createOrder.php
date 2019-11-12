<?php

$nivel=1;
$pagina='venda';

session_start();

if(!isset($_SESSION["username"])){
    include('../verifyConnection.php');    
}

include('../connectDb.php');

date_default_timezone_set('America/Sao_Paulo');
$dataAtual = date('d/m/Y', strtotime(date('Y-m-d H:i')));


if(!isset($_GET['ref'])){
    $msg = base64_encode('Erro ao gerar NFE! Por favor, verifique e tente novamente.');
    $type = base64_encode('error');
    header('Location:view.php?ref='.$_GEt['ref'].'&msg='.$msg.'&type='.$type);
    exit;
}


//BUSCA DADOS DO EMITENTE
$BuscaEmitente = mysqli_query($conecta, "SELECT A.*, B.descricao FROM emitente A, cnae B WHERE B.pkId = A.fkIdCNAE") or die(mysql_error());
$resultEmitente = mysqli_fetch_assoc($BuscaEmitente);

$_SESSION['pkIdEmitente']=$resultEmitente['pkId'];
$_SESSION['ultimaNfe']=$resultEmitente['ultimaNfeEmitida'];
$loginAPI=$resultEmitente['loginAPI'];
$senhaAPI=$resultEmitente['senhaAPI'];


$urlIntegracao    = 'http://www.agilcontabil.net/sistemaInstalado/ajax';

$dados['usuario']      = $loginAPI;
$dados['senha']        = $senhaAPI;
$dados['acao']         = 'emitirNfeA1';

//$dados['certificado']  = bin2hex(file_get_contents('/var/www/agilcontabil/modulos/fiscalNota/certificado-teste.pfx'));
$caminhoCertificado = '../emitente/' . $resultEmitente['certificado'];
$dados['certificado']  = bin2hex(file_get_contents($caminhoCertificado));
$dados['senhaCertificado'] = $resultEmitente['senhaCertificado'];
//$dados['idCsc'] = '1';
//$dados['csc'] = '11222d8eaa692076';
//$dados['mostrarXml'] = true;

$chars=array("/", ".", ",","-","(",")");

$cnpj = str_replace($chars,"", $resultEmitente['cnpj']);
$ie = str_replace($chars,"", $resultEmitente['inscricaoEstadual']);
$telefone = str_replace($chars,"", trim($resultEmitente['telefone']));
$cidade = str_replace($chars,"", $resultEmitente['cidade']);
$estado = str_replace($chars,"", $resultEmitente['uf']);
$cidade = $cidade ." - " . $estado;
$ibge = str_replace($chars,"", $resultEmitente['ibge']);
$cnae = str_replace($chars,"", $resultEmitente['fkIdCNAE']);
$crt = str_replace($chars,"", $resultEmitente['crt']);
$nome = str_replace($chars,"", $resultEmitente['razaoSocial']);
$logradouro = str_replace($chars,"", $resultEmitente['logradouro']);
$endereco = str_replace($chars,"", $resultEmitente['endereco']);
$endereco = $logradouro . " " . $endereco;
$numero = str_replace($chars,"", $resultEmitente['numero']);
$bairro = str_replace($chars,"", $resultEmitente['bairro']);
$cep = str_replace($chars,"", $resultEmitente['cep']);
$descricao = strtoupper(str_replace($chars,"", $resultEmitente['descricao']));
$data=date('Y-m-d H:m:s');

$modelo=$resultEmitente['modelo'];
if($resultEmitente['ambiente']=='HOMOLOGACAO') { $ambiente = 2; } else { $ambiente = 1; }


//BUSCA DADOS DO CLIENTE
$buscaCliente = mysqli_query($conecta, "SELECT A.pkId, B.*, C.descricao AS descricaoCFOP FROM vendas A, clientes B, cfop C WHERE C.pkId = A.fkIdCFOP AND B.pkId = A.fkIdCliente AND A.pkId=".base64_decode($_GET['ref']));
$resultCliente=mysqli_fetch_assoc($buscaCliente);

$cnpjCliente = str_replace($chars,"", $resultCliente['cnpj']);
$cpfCliente = str_replace($chars,"", $resultCliente['cpf']);
$ieCliente = str_replace($chars,"", $resultCliente['inscricaoEstadual']);
$telefoneCliente = str_replace($chars,"", trim($resultCliente['telefone']));
$cidadeCliente = str_replace($chars,"", $resultCliente['cidade']);
$estadoCliente = str_replace($chars,"", $resultCliente['uf']);
$cidadeCliente = $cidadeCliente ." - " . $estadoCliente;
$ibgeCliente = str_replace($chars,"", $resultCliente['ibge']);
$emailCliente = $resultCliente['email'];
$nomeCliente = str_replace($chars,"", $resultCliente['razaoSocial']);
$enderecoCliente = str_replace($chars,"", $resultCliente['endereco']);
$enderecoCliente = $logradouro . " " . $endereco;
$numeroCliente = str_replace($chars,"", $resultCliente['numero']);
$bairroCliente = str_replace($chars,"", $resultCliente['bairro']);
$cepCliente = str_replace($chars,"", $resultCliente['cep']);
$descricaoCFOP = str_replace($chars,"", $resultCliente['descricaoCFOP']);

//BUSCA PRODUTOS DA VENDA
$buscaProdutos=mysqli_query($conecta, "SELECT A.*, 
B.quantidade AS Quantidade, B.preco as Preco,  
C.pkId AS pkIdProduto, C.nome AS NomeProduto, C.fkIdNCM as ncmProduto, C.unidadeComercial AS UnidadeComercial, C.tributacaoCSOSN as tributacaoCSOSN, C.codigoBarras as codigoBarras, C.origem as origemProduto
FROM vendas A, vendas_itens B, produtos C 
WHERE 
C.pkId = B.fkIdProduto AND B.fkIdVenda = A.pkId AND A.pkId=".base64_decode($_GET['ref']));
$result=mysqli_fetch_assoc($buscaProdutos);
$x=0;
$nfe= intval($_SESSION['ultimaNfe'])+1;

$dadosNota = 
        array (
            'numeroNotaEmitir' => $nfe,
            'empresa' =>
            array (
              'serie' => '55',                                    //SERIE DA NFE
              'cnpj' => $cnpj,
              'cpf' => '', 
              'inscricaoEstadual' => $ie,
              'inscricaoMunicipal' => '',
              'telefone' => str_replace(" ", "", $telefone),
              'email' => '',
              'municipio' => $cidade,
              'codigoMunicipio' => $ibge,
              'uf' => $estado,
              'cnae' => $cnae,
              'tipoAtividade' => $crt,
              'modeloCertificadoDigital' => 'A1',
              'versaoNfe' => '4.0',
              'ibptAutomatico' => 'nao',
              'codigoRegimeTributarioIssqn' => '7',
              'aliquotaInternaIcms' => '',
              'aliquotaInterestadualIcms' => '',
              'razaoSocial' => $nome,
              'nomeFantasia' => $nome,
              'logradouro' => $endereco,
              'numero' => $numero,
              'complemento' => '',
              'bairro' => $bairro,
              'cep' => $cep,
              'codigoRegimeTributario' => $crt,
              'informacaoAdicionalFisco' => '',
              'informacaoComplementar' => '', 
              'cpfContador' => '',
              'cnpjContador' => ''
            ),
            
            'cliente' => 
            array (
              'cnpj' => $cnpjCliente,
              'cpf' => $cpfCliente,
              'inscricaoEstadual' => $ieCliente,
              'consumidorFinal' => '1',
              'indicadorIEDestinatario' => '9',
              'inscricaoMunicipal' => '',
              'inscricaoSuframa' => '',
              'telefone' => str_replace(" ", "", $telefoneCliente),
              'email' => $emailCliente,
              'logradouro' => $enderecoCliente,
              'numero' => $numeroCliente,
              'complemento' => '',
              'bairro' => $bairroCliente,
              'municipio' => $cidadeCliente,
              'codigoMunicipio' => $ibgeCliente,
              'uf' => $estadoCliente,
              'codigoPais' => '1058',
              'nomePais' => 'Brasil',
              'aliquotaInternaIcms' => '',
              'razaoSocial' => $nomeCliente,
              'nomeFantasia' => '',
              'cep' => $cepCliente,
              'codigoRegimeTributario' => $crt,
            ),
            
            'tpEmis' => '1',
            'numero' => '',
            'codigoNumerico' => rand(9999999,99999999),
            'dataSaida' => $data,// '2019-03-19 14:52:53',
            'dataEmissao' => $data,// '2019-03-19 14:52:53',
            'modelo' => $result['serie'],
            'ambiente' => $ambiente,
            'tipo' => '1',
            'frete' => '9',
            'finalidade' => '1',
            'informacaoAdicionalFisco' => '',
            'informacaoComplementar' => '',
            'notaFiscalReferencia' => '',
            'idNaturezaOperacao' => '1',
            'naturezaOperacao' => $descricaoCFOP,
            'numeroVenda' => '',
            'tipoPagamento' => '99',
            'itens' => []
		);

$buscaProdutos=mysqli_query($conecta, "SELECT A.*, 
B.quantidade AS Quantidade, B.preco as Preco,  
C.pkId AS pkIdProduto, C.nome AS NomeProduto, C.fkIdNCM as ncmProduto, C.unidadeComercial AS UnidadeComercial, C.tributacaoCSOSN as tributacaoCSOSN, C.codigoBarras as codigoBarras, C.origem as origemProduto
FROM vendas A, vendas_itens B, produtos C 
WHERE 
C.pkId = B.fkIdProduto AND B.fkIdVenda = A.pkId AND A.pkId=".base64_decode($_GET['ref']));


while($resultProdutos=mysqli_fetch_assoc($buscaProdutos)){

    $quantidade=number_format($resultProdutos['Quantidade'], 8);
    $preco=number_format($resultProdutos['Preco'], 8);
    $valorTotal = number_format($resultProdutos['Preco']*$resultProdutos['Quantidade'], 8);
    $ncm = str_replace($chars,"", $resultProdutos['ncmProduto']);
    $nomeProduto = str_replace($chars,"", $resultProdutos['NomeProduto']);
    $unidadeComercial = str_replace($chars,"", $resultProdutos['UnidadeComercial']);
    $cfop = str_replace($chars,"", $resultProdutos['fkIdCFOP']);
    $csosn = str_replace($chars,"", intval($resultProdutos['tributacaoCSOSN']));
    $codigoBarras = str_replace($chars,"", $resultProdutos['codigoBarras']);
    $origem = str_replace($chars,"", $resultProdutos['origemProduto']);
    $desconto = number_format($resultProdutos['desconto'], 8);
    $subTotal = number_format($resultProdutos['subTotal'], 8);
    $total = number_format($resultProdutos['total'], 8);

    //echo $quantidade . " - " . $preco . " - " . $valorTotal . " - " . $ncm . " - " . $unidadeComercial . " - " . $nomeProduto . " - " . $cfop . " - " . $csosn . " - " . $codigoBarras . " - " . $origem . " - " . $subTotal . " - " . $desconto. " - " . $total;

	$dadosNota["itens"][] = array (
		'desconto' => $desconto,
		'frete' => '0.00000000',
		'outro' => '0.00000000',
		'quantidade' => $quantidade,
		'valorUnitario' => $preco,
		'valorTotal' => $valorTotal,
		'informacaoAdicional' => '',
		'ncmProduto' => $ncm,
		'cest' => '',
		'itemListaServico' => '',
		'codigoServico' => '',
		'tipoItem' => '00',
		'eanProduto' => 'SEM GTIN',
		'codigoProduto' => '1',
		'nomeProduto' => $nomeProduto,
		'cfop' => $cfop,
		'codigoAnp' => '',
		'ufAnp' => '',
		'unidadeMedidaProduto' => $unidadeComercial,
		'origemProduto' => $origem,
		'icmsCst' => $csosn,
		'icmsModBc' => '0',
		'icmsBc' => '1.00000000',
		'icmsRedBc' => '0.00000000',
		'icmsAliquota' => '0.00000000',
		'icmsValor' => '0.00000000',
		'icmsModBcSt' => '',
		'icmsMva' => '0.00000000',
		'icmsRedBcSt' => '0.00000000',
		'icmsBcSt' => '0.00000000',
		'icmsCredito' => '0.00000000',
		'icmsStAliquota' => '0.00000000',
		'icmsStValor' => '0.00000000',
		'ipiCst' => '',
		'ipiBc' => '0.00000000',
		'ipiAliquota' => '0.00000000',
		'ipiValor' => '0.00000000',
		'iiBc' => '0.00000000',
		'iiDespAdu' => '0.00000000',
		'iiValor' => '0.00000000',
		'iiIof' => '0.00000000',
		'pisCst' => '08',
		'pisAliquota' => '0.00000000',
		'pisBc' => '0.00000000',
		'pisValor' => '0.00000000',
		'cofinsCst' => '08',
		'cofinsBc' => '0.00000000',
		'cofinsValor' => '0.00000000',
		'cofinsAliquota' => '0.00000000',
		'iss' => '',
		'issAliquota' => '',
		'issItemListaServico' => '',
		'issRetido' => '',
		'issIndicador' => '',
		'issCodigoServico' => '',
		'issIndicadorIncentivo' => ''
	);//array do item
    $x=$x+1;
	
}//loop dos produtos


$dados["nfe"] = bin2hex(json_encode($dadosNota));

//TESTE  DE PREENCHIMENTO PARA ENVIO A SEFAZ
/*$dados["nfe"] = (($dadosNota)); 

echo "<pre>";
var_dump($dados);
echo "</pre>";*/


 

  //Inicia comunicação com servidor agilcontabil.net
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_URL, $urlIntegracao);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dados));

  //recebe a resposta
  $resposta = curl_exec($ch);
  
  //finaliza comunicação
  curl_close($ch);

//mostra a resposta da emissão da nota
//o xml da nota fiscal emitida está dentro da variavel $resposta["xml"] e deve ser gravado em sua base de dados

/*var_dump($resposta);
echo "<pre>";
echo $resposta;
echo "</pre>";


echo "<pre>";
print_r(json_decode($resposta,false));
echo "</pre>"; 

exit;*/

$array = json_decode($resposta,true);

$mes="";

if(date("m", strtotime($data))==1) { $mes = 'jan';}
if(date("m", strtotime($data))==2) { $mes = 'fev';}
if(date("m", strtotime($data))==3) { $mes = 'mar';}
if(date("m", strtotime($data))==4) { $mes = 'abr';}
if(date("m", strtotime($data))==5) { $mes = 'mai';}
if(date("m", strtotime($data))==6) { $mes = 'jun';}
if(date("m", strtotime($data))==7) { $mes = 'jul';}
if(date("m", strtotime($data))==8) { $mes = 'ago';}
if(date("m", strtotime($data))==9) { $mes = 'set';}
if(date("m", strtotime($data))==10) { $mes = 'out';}
if(date("m", strtotime($data))==11) { $mes = 'nov';}
if(date("m", strtotime($data))==12) { $mes = 'dez';}

if($array["result"]=="sucesso"){
    //gravar numero da ultima nota emitida no banco
    
    //gravar xml no banco
    $xml = hex2bin($array["xml"]);
    //gravar pdf no hd
    $pdf = hex2bin($array["pdf"]); 
    
    $chave = hex2bin($array['chave']);
    
    $numeroChave = $array['chave'];
    
    $nomeArquivo='pdf'.str_pad($_SESSION['pkIdEmitente'], 10, 0, STR_PAD_LEFT)."-".date(str_replace(":", "", "dmYH:i:s"));
    $caminhoPDF="pdf/".$mes."/".$nomeArquivo.".pdf";
    file_put_contents("pdf/".$mes."/".$nomeArquivo.".pdf", $pdf);
    
    $nomeArquivoXML='xml'.str_pad($_SESSION['pkIdEmitente'], 10, 0, STR_PAD_LEFT)."-".date(str_replace(":", "", "dmYH:i:s"));
    $caminhoXML="xml/".$mes."/".$nomeArquivoXML.".xml";
    file_put_contents("xml/".$mes."/".$nomeArquivoXML.".xml", $xml);
    
    mysqli_query($conecta, "UPDATE emitente SET ultimaNfeEmitida = " . $nfe . " WHERE pkId =".$_SESSION['pkIdEmitente']);
    mysqli_query($conecta, "UPDATE vendas SET STATUS = 'ENVIADO A SEFAZ', xml = '" . $caminhoXML. "', pdf = '" . $caminhoPDF . "', chave = '" . $numeroChave . "', dataSaida='" . date('Y-m-d') . "' WHERE pkId=".base64_decode($_GET['ref']));
    
    
    //BAIXA NO ESTOQUE
    $estoque=0;
    $query=mysqli_query($conecta, "SELECT * FROM vendas_itens WHERE fkIdVenda = ".base64_decode($_GET['ref']));
    while($resultProd=mysqli_fetch_assoc($query)){
        $atualiza=mysqli_query($conecta, "SELECT * FROM produtos WHERE pkId='" . $resultProd['fkIdProduto'] . "'");
        $resultAtualiza=mysqli_fetch_assoc($atualiza);
        $estoque=$resultAtualiza['estoqueAtual'];
        $estoque=$estoque-intval($resultProd['quantidade']);
        mysqli_query($conecta, "UPDATE produtos SET estoqueAtual=" . $estoque . " WHERE pkId='" . $resultAtualiza['pkId'] ."'");
        $estoque=0;
    }
    
    
    
    $msg = base64_encode('NFe transmitida com sucesso a Sefaz. Arquivos XML e PDF gerados! Voce pode já imprimir o DANFE.');
    $type = base64_encode('info');
    header('Location:view.php?msg='.$msg.'&type='.$type.'&ref='.$_GET['ref']);
    exit;

    
}else{
    //nao emitiu
    //echo "<pre>";
    //print_r(json_decode($resposta,false));
    //echo "</pre>";
    header('Content-Type: text/html; charset=utf-8'); 
    $msg = base64_encode('NFe não foi transmitida. Favor verificar seu preenchimento. Erro: ' . $resposta);
    $type = base64_encode('error');
    header('Location:view.php?msg='.$msg.'&type='.$type.'&ref='.$_GET['ref']);
    exit;
}
/*
$dom = new DOMDocument();
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($xml);
$out = $dom->saveXML();
pre(htmlentities($out));
<object data="data:application/pdf;base64,<?php echo base64_encode(hex2bin($array["pdf"])); ?>" type="application/pdf" width="100%" height="800px"></object>
*/
?>

<html>
    
    <body onload="self.print();self.close();">
      <object data="data:application/pdf;base64,<?php echo base64_encode(hex2bin($array["pdf"])); ?>" type="application/pdf" width="100%" height="800px"></object>  
    </body>
</html>
