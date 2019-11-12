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
$dados['acao']         = 'cancelarNfeA1';
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
$ambiente = str_replace($chars,"", $resultEmitente['ambiente']);
$modelo=$resultEmitente['modelo'];

if($resultEmitente['ambiente']=='HOMOLOGACAO') { $ambiente = 2; } else { $ambiente = 1; }

//BUSCA DADOS DO CLIENTE
$buscaCliente = mysqli_query($conecta, "SELECT * FROM vendas WHERE pkId=".base64_decode($_GET['ref']));
$resultCliente=mysqli_fetch_assoc($buscaCliente);

$chave = str_replace($chars,"", $resultCliente['chave']);
$justificativa = str_replace($chars,"", $resultCliente['obs']);
$protocolo = str_replace($chars,"", $resultCliente['protocolo']);

$dados['xml'] = bin2hex(file_get_contents('../vendas-nfe/'.$resultCliente['xml']));
$dados['justificativa'] = "cancelamento devido testes, testes testes";

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
//var_dump($resposta);
//echo "<pre>";
//echo $resposta;
//echo "</pre>";
//
//
//echo "<pre>";
//print_r(json_decode($resposta,false));
//echo "</pre>"; 


$array = json_decode($resposta,true);
$xml = ($array["xml"]);


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
    
    $nomeArquivo='pdf'.str_pad($_SESSION['pkIdEmitente'], 10, 0, STR_PAD_LEFT)."-".date(str_replace(":", "", "dmYH:i:s"));
    $caminhoPDF="pdf/".$mes."/".$nomeArquivo.".pdf";
    file_put_contents("pdf/".$mes."/".$nomeArquivo.".pdf", $pdf);
    
    $nomeArquivoXML='xml'.str_pad($_SESSION['pkIdEmitente'], 10, 0, STR_PAD_LEFT)."-".date(str_replace(":", "", "dmYH:i:s"));
    $caminhoXML="xml/".$mes."/".$nomeArquivoXML.".xml";
    file_put_contents("xml/".$mes."/".$nomeArquivoXML.".xml", $xml);
    
    mysqli_query($conecta, "UPDATE emitente SET ultimaNfeEmitida = " . $nfe . " WHERE pkId =".$_SESSION['pkIdEmitente']);
    mysqli_query($conecta, "UPDATE vendas SET STATUS = 'CANCELADO', xml = '" . $caminhoXML. "', pdf = '" . $caminhoPDF . "' WHERE pkId=".base64_decode($_GET['ref']));
       
    $msg = base64_encode('NFe Cancelamento transmitida com sucesso a Sefaz. Arquivos XML e PDF gerados! Voce pode já imprimir o DANFE.');
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
