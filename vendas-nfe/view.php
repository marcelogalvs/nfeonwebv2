<?php

$nivel=1;
$pagina='usuarios';

session_start();

date_default_timezone_set('America/Sao_Paulo');
$_SESSION['dataHoje']=date("Y-m-d");

if(!isset($_SESSION["username"])){
    include('verifyConnection.php'); 
}

include('../connectDb.php');



//GERA NFE SEFAZ
if(isset($_POST['btnEnviarNFE'])){
   
    //TESTE SE EXISTE PRODUTOS NO PEDIDO
    $buscaItensVenda=mysqli_query($conecta, "SELECT * FROM vendas_itens WHERE fkIdVenda=".base64_decode($_GET['ref']));
    if(mysqli_num_rows($buscaItensVenda)<=0){
        $msg = base64_encode('Erro ao Gerar NFe! Nenhum produto foi informado! Por favor verifique e tente novamente.');
        $type = base64_encode('error');
        header('Location:view.php?msg='.$msg.'&type='.$type.'&ref='.$_GET['ref']);
        exit;
    }
    
    //TESTE SE EXISTE PAGAMENTOS NO PEDIDO
    $buscaPagamentosNfe=mysqli_query($conecta, "SELECT * FROM vendas_pagamento WHERE fkIdVenda=".base64_decode($_GET['ref']));
    if(mysqli_num_rows($buscaPagamentosNfe)<=0){
        $msg = base64_encode('Erro ao Gerar NFe! Nenhum pagamento foi informado! Por favor verifique e tente novamente.');
        $type = base64_encode('error');
        header('Location:view.php?msg='.$msg.'&type='.$type.'&ref='.$_GET['ref']);
        exit;
    }
    
    
    // VERIFICA SE É CLIENTE CONSUMIDOR FINAL
    $buscaTipoCliente = mysqli_query($conecta, "SELECT A.*, B.razaoSocial FROM vendas A, clientes B WHERE B.pkId = A.fkIdCliente AND A.pkId=".base64_decode($_GET['ref']));
    $resultBuscaCliente=mysqli_fetch_assoc($buscaTipoCliente);
    if($resultBuscaCliente['status']=='ENVIADO A SEFAZ'){
        $msg = base64_encode('NFe já foi enviada a Sefaz! Por favor verifique e tente novamente.');
        $type = base64_encode('error');
        header('Location:view.php?msg='.$msg.'&type='.$type.'&ref='.$_GET['ref']);
        exit;
    }
    header('Location:createOrder.php?ref='.$_GET['ref']);
    exit;
}




//INCLUI PRODUTO NA VENDA
if(isset($_POST['btnIncluir'])){
    if(empty($_POST['txtCodigoCliente'])){
        $msg = base64_encode('Erro ao efetuar cadastro! Produto deve ser selecionado! Por favor, verifique e tente novamente.');
        $type = base64_encode('error');
        header('Location:view.php?ref='.$_GET['ref'].'&msg='.$msg.'&type='.$type);
        exit;
    }
    
    if(empty($_POST['txtQuantidade'])){
        $msg = base64_encode('Erro ao efetuar cadastro! Quantidade de produto deve ser informado! Por favor, verifique e tente novamente.');
        $type = base64_encode('error');
        header('Location:view.php?ref='.$_GET['ref'].'&msg='.$msg.'&type='.$type);
        exit;
    }
    $query = mysqli_query($conecta, "INSERT INTO vendas_itens 
    (fkIdVenda, fkIdProduto, preco, quantidade) VALUES (
    '" . base64_decode($_GET['ref']) . "' , 
    '" . (trim(strtoupper($_POST["txtCodigoCliente"]))) . "' , 
    '" . (trim(strtoupper($_POST["txtPreco"]))) . "' , 
    '" . (trim(strtoupper($_POST["txtQuantidade"]))) . "'  
    )");
  
    if($query){
        
        $atualizaTotal = mysqli_query($conecta, "UPDATE vendas SET subTotal = (SELECT SUM(preco*quantidade) AS total FROM vendas_itens WHERE fkIdVenda =". base64_decode($_GET['ref']).") , total = (SELECT SUM(preco*quantidade) AS total FROM vendas_itens WHERE fkIdVenda =". base64_decode($_GET['ref']).") WHERE pkId =". base64_decode($_GET['ref']));
        
        $msg = base64_encode('Produto incluido com sucesso!');
        $type = base64_encode('info');
        header('Location:?ref='.$_GET['ref'].'&msg='.$msg.'&type='.$type);
        exit;
        
    } else {

        $msg = base64_encode('Erro ao efetuar cadastro! Por favor, tente novamente mais tarde.');
        $type = base64_encode('error');
        header('Location:?ref='.$_GET['ref'].'&msg='.$msg.'&type='.$type);
        exit;
        
    }
  
}


//TROCA DE CLIENTE DO ORCAMENTO
if(isset($_POST['btnConfirmar'])){
        
    mysqli_query($conecta, "UPDATE vendas SET fkIdCliente = " . $_POST['txtCliente']. " WHERE pkId =". base64_decode($_GET['ref']));

    $msg = base64_encode('Cliente alterado com sucesso!');
    $type = base64_encode('info');
    header('Location:?ref='.$_GET['ref'].'&msg='.$msg.'&type='.$type);
    exit;
  
}



//CADASTRO DE NOVO CLIENTE
if(isset($_POST['btnSalvarCliente'])){
    $query = mysqli_query($conecta, "INSERT INTO clientes 
    (tipoCliente, cpf, cnpj, razaoSocial, endereco, numero, bairro, cidade, uf, cep, ibge, telefone, celular, email, inscricaoEstadual, status) VALUES (
    '" . (trim(strtoupper($_POST["txtTipoCliente"]))) . "' , 
    '" . (trim(strtoupper($_POST["txtCPF"]))) . "' , 
    '" . (trim(strtoupper($_POST["txtCNPJ"]))) . "' , 
    '" . (trim(strtoupper($_POST["txtRazaoSocial"]))) . "' , 
    '" . (trim(strtoupper($_POST["txtEndereco"]))) . "', 
    '" . (trim(strtoupper($_POST["txtNumero"]))) . "', 
    '" . (trim(strtoupper($_POST['txtBairro']))) . "' , 
    '" . (trim(strtoupper($_POST["txtCidade"]))) . "' , 
    '" . (trim(strtoupper($_POST["txtEstado"]))) . "' , 
    '" . (trim($_POST["txtCep"])) . "' , 
    '" . (trim($_POST["txtCodigoIBGE"])) . "' , 
    '" . (trim($_POST["txtTelefone"])) . "' , 
    '" . (trim($_POST["txtCelular"])) . "' , 
    '" . (trim($_POST["txtEmail"])) . "' , 
    '" . (trim($_POST["txtIE"])) . "' , 'ATIVO')");

    if($query){
        
        $buscaCliente=mysqli_query($conecta, "SELECT * FROM clientes ORDER BY pkId DESC LIMIT 1");
        $resultBuscaCliente=mysqli_fetch_assoc($buscaCliente);
        
        mysqli_query($conecta, "UPDATE orcamentos SET fkIdCliente = ".$resultBuscaCliente['pkId']." WHERE pkId=".base64_decode($_GET['ref']));
        
        $msg = base64_encode('Cliente cadastrado com sucesso! Cliente novo incluido no Orçamento');
        $type = base64_encode('info');
        header('Location:view.php?ref='.$_GET['ref'].'&msg='.$msg.'&type='.$type);
        exit;
        
    } else {

        $msg = base64_encode('Erro ao efetuar cadastro! Por favor, tente novamente mais tarde.');
        $type = base64_encode('error');
        header('Location:?ref='.$_GET['ref'].'&msg='.$msg.'&type='.$type);
        exit;
        
    }
    
}




//EXCLUSAO PAGAMENTO
if(isset($_POST['btnExcluirPagamento'])){
 
    mysqli_query($conecta, "DELETE FROM vendas_pagamento WHERE pkId=".$_POST['txtCodigoPagamento']);
    $msg = base64_encode('Pagamento excluído com sucesso!');
    $type = base64_encode('info');
        
    header('Location:?ref='.$_GET['ref'].'&msg='.$msg.'&type='.$type);
    exit;
}




//EXCLUSAO PRODUTO DA LISTA
if(isset($_POST['txtCodigoProduto'])){

    mysqli_query($conecta, "DELETE FROM vendas_itens WHERE fkIdVenda=".base64_decode($_GET['ref'])." AND fkIdProduto='".$_POST['txtCodigoProduto']."'");
    $msg = base64_encode('Produto excluído com sucesso!');
    $type = base64_encode('info');
    
    $atualizaTotal = mysqli_query($conecta, "UPDATE vendas SET subTotal = (SELECT SUM(preco*quantidade) AS total FROM vendas_itens WHERE fkIdVenda =". base64_decode($_GET['ref']).") , total = (SELECT SUM(preco*quantidade) AS total FROM vendas_itens WHERE fkIdVenda =". base64_decode($_GET['ref']).") WHERE pkId =". base64_decode($_GET['ref']));
    
    header('Location:?ref='.$_GET['ref'].'&msg='.$msg.'&type='.$type);
    exit;
}



//IMPRESSAO DO ORCAMENTO
if(isset($_POST['btnImprimir'])){
    $atualizaTotal = mysqli_query($conecta, "UPDATE vendas SET obs = '". $_POST['txtOBS'] ."' WHERE pkId =". base64_decode($_GET['ref']));
    $link='relVenda.php?ref='.$_GET['ref'];
    echo "<script>window.open('".$link."', '_blank');</script>";
    $msg = base64_encode('Orçamento Impresso com sucesso! Atualize a página antes de imprimir novamente...');
    $type = base64_encode('info');
    $page = '?ref='.$_GET['ref'].'&msg='.$msg.'&type='.$type;
    $sec = "1";
}



//LANCARE PAGAMENTO DO PEDDO
if(isset($_POST['btnLancar'])){
    
    
    if(empty($_POST['txtParcelas'])){
        $msg = base64_encode('Erro ao efetuar lançamento! Numero de Parcelas deve ser preenchido! Por favor, verifique e tente novamente.');
        $type = base64_encode('error');
        header('Location:view.php?ref='.$_GET['ref'].'&msg='.$msg.'&type='.$type);
        exit;
    }
    
    //APAGA TODOS OS LANCAMENTO DE PAGAMENTOS
   /* $buscaPagamentosLancados=mysqli_query($conecta, "SELECT * FROM vendas_pagamento WHERE fkIdVenda=".base64_decode($_GET['ref']));
    if(mysqli_num_rows($buscaPagamentosLancados)>0){
        mysqli_query($conecta, "DELETE FROM vendas_pagamento WHERE fkIdVenda=".base64_decode($_GET['ref']));
    }*/
    
    $data=date("Y-m-d");
    $obs = $_POST['txtOBS'];
    
    for($x=1; $x <= $_POST['txtParcelas']; $x++) {
        
        if($_POST['txtParcelas']>=1){
            $obs="Parcela " . $x . " de " . $_POST['txtParcelas'];
        }
        
        mysqli_query($conecta, "INSERT INTO vendas_pagamento 
        (fkIdVenda, fkIdCliente, data, valor, formaPagamento, obs) VALUES (
        '" . base64_decode($_GET['ref']) . "' , 
        '" . $_SESSION['idCliente'] . "' , 
        '" . $data . "',
        '" . (trim(strtoupper($_POST["txtValor"]))) . "' , 
        '" . (trim(strtoupper($_POST["txtFormaPagamento"]))) . "' , 
        '" . (trim(strtoupper($obs))) . "'
        )");
        

        if($_POST['txtParcelas']>1){
            $data=date("Y-m-d", strtotime("+30 days",strtotime($data)));
        }
        
        if($_POST['txtFormaPagamento']<>'DINHEIRO'){
             mysqli_query($conecta, "INSERT INTO contas_receber 
            (fkIdVenda, fkIdCliente, data, valor, formaPagamento, obs, status) VALUES (
            '" . base64_decode($_GET['ref']) . "' , 
            '" . $_SESSION['idCliente'] . "' , 
            '" . $data . "',
            '" . (trim(strtoupper($_POST["txtValor"]))) . "' , 
            '" . (trim(strtoupper($_POST["txtFormaPagamento"]))) . "' , 
            '" . (trim(strtoupper($obs))) . "',
            'PENDENTE'
            )");
        } else {
            mysqli_query($conecta, "INSERT INTO contas_receber 
            (fkIdVenda, fkIdCliente, data, valor, formaPagamento, obs, status) VALUES (
            '" . base64_decode($_GET['ref']) . "' , 
            '" . $_SESSION['idCliente'] . "' , 
            '" . $data . "',
            '" . (trim(strtoupper($_POST["txtValor"]))) . "' , 
            '" . (trim(strtoupper($_POST["txtFormaPagamento"]))) . "' , 
            '" . (trim(strtoupper($obs))) . "',
            'RECEBIDO'
            )");
        }
    }
    
    unset($_SESSION['idCliente']);   
  
    $msg = base64_encode('Forma de pagamento lançado com sucesso!');
    $type = base64_encode('info');
    header('Location:view.php?ref='.$_GET['ref'].'&msg='.$msg.'&type='.$type);
    exit;
  
}


?>


<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>NFe On Web</title>
    <link href="../resources/assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="../resources/assets/extra-libs/c3/c3.min.css" rel="stylesheet">
    <link href="../resources/dist/css/style.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
    
 
<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    
    <div id="main-wrapper">
        <!-- Topbar header -->
        <?php include('../header.php'); ?>
        <!-- End Topbar header -->

        <!-- Menu  -->
        <?php include('../sideMenu.php'); ?>
        <!-- End Menu -->

        <!-- Indicador de Pagina  -->
        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-5 align-self-center">
                        <h4 class="page-title">Vendas NF-e</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="../">Home</a></li>
                                    <li class="breadcrumb-item"><a href=".">Vendas NF-e</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Alteração</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="container-fluid">
                
                <div class="row">

                    <!-- BLOCOS DE ALERTA -->
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
                    <?php if(!empty($_GET["msg"]) and !empty($_GET["type"])) { ?>
                        <script type="text/javascript">
                            setTimeout(function() {
                                $('#divAlerta').fadeOut('slow');
                            }, 3000);
                        </script>
                    
                        <?php if(base64_decode($_GET['type'])=='info') { ?>
                            <div class="col-lg-12 col-md-12" id="divAlerta">
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Oba! . . . Legal...</h3> <?php echo base64_decode($_GET["msg"]); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if(base64_decode($_GET['type'])=='error') { ?>
                            <div class="col-lg-12 col-md-12" id="divAlerta">
                                <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    <h3 class="text-danger"><i class="fa fa-check-circle"></i> Ops . . . Algo errado ocorreu...</h3> <?php echo base64_decode($_GET["msg"]); ?>
                                </div>
                            </div>
                        <?php } ?>
                    
                    <?php } ?>

                </div>
                
                <!-- Conteudo Orçamento -->
                
                <?php
                    $query=mysqli_query($conecta, "SELECT A.*, B.razaoSocial AS nome, B.endereco, B.telefone, B.celular, B.email FROM vendas A, clientes B WHERE B.pkId=A.fkIdCliente AND A.pkId=".base64_decode($_GET['ref']));
                    $result=mysqli_fetch_assoc($query);
                ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-body printableArea">
                            <h3><b>VENDA NFe </b> <span class="pull-right">#<?php echo str_pad(base64_decode($_GET['ref']), 8, 0, STR_PAD_LEFT) ?></span></h3><p>Data Orçamento : <i class="fa fa-calendar"></i> <?php echo date('d/m/Y', strtotime($result['data'])) ?></p>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-left">
                                        <address>
                                            
                                            <h3><button class="btn btn-info btn-sm" data-toggle="modal" data-target="#TrocaCliente" title="Trocar Cliente" ><i class="fas fa-recycle"></i></button>
                                                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#NovoCliente" title="Novo Cliente" ><i class="fas fa-plus"></i></button>
                                                &nbsp;<b class="text-danger"><?php echo $result['nome'] ?></b>
                                                
                                            </h3>
                                            <p class="text-muted m-l-5">
                                                <i class="fa fa-map-marker"></i> <?php echo $result['endereco'] ?>
                                                <br/> <i class="fa fa-phone"></i> <?php echo $result['telefone'] . "/" . $result['celular'] ?>
                                                <br/> <i class="fa fa-envelope"></i> <?php echo $result['email'] ?></p>
                                                <!--<button class="btn btn-info" data-toggle="modal" data-target="#TrocaCliente" ><i class="fas fa-recycle"></i> Trocar Cliente</button>
                                                <button class="btn btn-danger" data-toggle="modal" data-target="#TrocaCliente" ><i class="fas fa-plus"></i> Novo Cliente</button>-->
                                        </address>
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-top:-50px;">
                                    <div class="table-responsive m-t-40" style="clear: both;">
                                        <div class="text-right">
                                            <div class="btn-group" style="float:right">
                                                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Opções NF-e
                                                </button>
                                                <div class="dropdown-menu">
                                                    <button class="dropdown-item" data-toggle="modal" data-target="#PagamentoModal" onclick="setaDadosModalPagamento('<?php echo base64_decode($_GET['ref']) . "', '" . $result['nome'] ?>', '<?php echo $result['total'] ?>')"  title="Excluir")><i class="mdi mdi-cash-usd"></i>  Forma Pagamento</button>
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#ImpressaoModal"><i class="mdi mdi-printer"></i> Impressão Pedido Venda</a>
                                                    <div class="dropdown-divider"></div>
                                                    <button class="dropdown-item" data-toggle="modal" data-target="#NFeModal" onclick="setaDadosModalNFe('<?php echo base64_decode($_GET['ref']) . "', '" . $result['nome'] ?>', '<?php echo $result['total'] ?>')"  title="Enviar NFe a Sefaz")><i class="mdi mdi-web"></i>  Enviar NF-e Sefaz</button>
                                                </div>
                                            </div>
                                            <button class="btn btn-info m-b-10" style="float:right; margin-right:10px" data-toggle="modal" data-target="#InsertProduto" ><i class="fas fa-plus"></i> Adicionar Produto</button>
                                        </div>
                                        <table class="table table-hover" style="font-size:12px;">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="6%">#</th>
                                                    <th width="3%">Item</th>
                                                    <th width="5%">Id Prod</th>
                                                    <th>Produto</th>
                                                    <th>NCM</th>
                                                    <th width="5%" class="text-right">Un Cml</th>
                                                    <th width="5%" class="text-right">R$ Unit</th>
                                                    <th width="5%" class="text-right">Qtde</th>
                                                    <th width="5%" class="text-right">Total</th>
                                                    <th width="5%" class="text-right">PIS</th>
                                                    <th width="5%" class="text-right">COFINS</th>
                                                    <th width="5%" class="text-right">ICMS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $buscaProdutos=mysqli_query($conecta, "SELECT A.*, 
                                                    B.nome AS nomeProduto, B.unidadeComercial AS unidadeComercial, B.tributacaoPIS, B.tributacaoCOFINS, B.tributacaoICMS,
                                                    C.pkId AS pkIdNCM 
                                                    FROM 
                                                    vendas_itens A, produtos B, ncm C 
                                                    WHERE 
                                                    C.pkId=B.fkIdNCM AND B.pkId=A.fkIdProduto AND A.fkIdVenda=".base64_decode($_GET['ref']));
                                
                                                    if(mysqli_num_rows($buscaProdutos)>=1){
                                                        while($resultProdutos=mysqli_fetch_assoc($buscaProdutos)){
                                                ?>
                                                <tr>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#DeleteModal" onclick="setaDadosModal('<?php echo $resultProdutos['fkIdProduto'] . "', '" . $resultProdutos['nomeProduto'] ?>')"  title="Excluir"><i class="fas fa-eraser"></i></button>
                                                    </td>
                                                    <td class="text-left"><?php echo $resultProdutos['pkId'] ?></td>
                                                    <td class="text-left"><?php echo $resultProdutos['fkIdProduto'] ?></td>
                                                    <td><?php echo $resultProdutos['nomeProduto'] ?></td>
                                                    <td><?php echo $resultProdutos['pkIdNCM'] ?></td>
                                                    <td class="text-right"><?php echo $resultProdutos['unidadeComercial'] ?></td>
                                                    <td class="text-right"><?php echo $resultProdutos['preco'] ?></td>
                                                    <td class="text-right"><?php echo $resultProdutos['quantidade'] ?></td>
                                                    <td class="text-right" style="font-weight:bolder"><?php echo number_format($resultProdutos['preco']*$resultProdutos['quantidade'], 2) ?></td>
                                                    <td class="text-right"><?php echo $resultProdutos['tributacaoPIS'] ?></td>
                                                    <td class="text-right"><?php echo $resultProdutos['tributacaoCOFINS'] ?></td>
                                                    <td class="text-right"><?php echo $resultProdutos['tributacaoICMS'] ?></td>
                                                </tr>
                                                <?php } } else { ?>
                                                <tr>
                                                    <td colspan="5">
                                                        <h6 class="card-subtitle text-center">Não foram encontrados registros.</h6>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="pull-right m-t-10 text-right">
                                        <!--<p>SubTotal: $13,848</p>
                                        <p>vat (10%) : $138 </p>-->
                                        <hr>
                                        <h2><b>Total :</b> $ <?php echo isset($result['total']) ? $result['total'] : '0,00' ?></h2>
                                    </div>
                                    <!--<div class="clearfix"></div>
                                    <hr>
                                    <div class="text-right">
                                        <button class="btn btn-danger" type="submit"> <span><i class="fa fa-shopping-cart"></i> Gerar Pedido Venda</span> </button>
                                        <button class="btn btn-default btn-outline" type="button" data-toggle="modal" data-target="#ImpressaoModal"> <span><i class="fa fa-print"></i> Imprimir</span> </button>
                                    </div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fim Orcamento -->
            </div>
          
            <!-- footer -->
            <?php
                include('../footer.php');
            ?>
            <!-- End footer -->
        </div>
    </div>
        
    
    
     
    <!-- Modal NFE  -->
    <!-- JANELA MODAL NFE SEFAZ -->
     <script>
        function setaDadosModalNFe(id, nome, total) {
            document.getElementById('idVendaNFe').value = id;
            document.getElementById('idVendaPagamentoNFe').value = id;
            document.getElementById('idVendaNomeNFe').value = nome;
            document.getElementById('totalVendaNFe').value = total;
            document.getElementById('totalVenda2NFe').value = total;
        }
    </script>
    <div class="modal fade" id="NFeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Envio NF-e a Sefaz</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" name="frmNFe">
                        <div class="row">
                            <div class="form-group col-md-1" style="float:left">
                                <label for="recipient-name" class="col-form-label">PedVenda:</label>
                                <input type="text" class="form-control" id="idVendaNFe" disabled />
                                <input type="hidden" name="txtCodigoVenda" class="form-control" id="idVendaPagamentoNFe" />
                                <input type="hidden" name="txtTotalVenda" class="form-control" id="totalVendaNFe" />
                            </div>
                            <div class="form-group col-md-8" style="float:left">
                                <label for="message-text" class="col-form-label">Cliente:</label>
                                <input type="text" class="form-control" id="idVendaNomeNFe" disabled />
                            </div>
                            <div class="form-group col-md-3" style="float:left">
                                <label for="message-text" class="col-form-label">TOTAL DO PEDIDO:</label>
                                <input type="text" class="form-control text-right" style="font-size: 22px; font-weight: bolder; color: #ff541b; background: #2962FF" id="totalVenda2NFe" disabled />
                            </div>
                        </div>
                         <!-- TRAZ PAGAMENTO REGISTRADO DESTA VENDA -->
                            <?php
                                $buscaPagamentos=mysqli_query($conecta, "SELECT * FROM vendas_pagamento WHERE fkIdVenda=".base64_decode($_GET['ref']));
                                if(mysqli_num_rows($buscaPagamentos)>=1){
                            ?>
                                <div class="form-group m-t-30">
                                    <h6>FORMA DE PAGAMENTO LANÇADA</h6>
                                    <table class="table table-hover" style="font-size:12px;">
                                        <tr>
                                            <th width="3%">Id</th>
                                            <th width="5%">Data</th>
                                            <th width="5%" class="text-right">Valor</th>
                                            <th>Forma Pagamento</th>
                                            <th>Obs</th>
                                        </tr>
                                        <?php
                                            while($resultPagamentos=mysqli_fetch_assoc($buscaPagamentos)){
                                        ?>
                                        <tr>
                                            <td width="3%"><?php echo $resultPagamentos['pkId'] ?></td>
                                            <td width="8%"><?php echo date('d/m/Y', strtotime($resultPagamentos['data'])) ?></td>
                                            <td width="5%" class="text-right"><?php echo $resultPagamentos['valor'] ?></td>
                                            <td width="20%"><?php echo $resultPagamentos['formaPagamento'] ?></td>
                                            <td width="58%"><?php echo $resultPagamentos['obs'] ?></td>
                                        </tr>                                    
                                        <?php } ?>
                                    </table>
                                </div>
                            <?php } ?>
                            <!-- FINAL TRAZ PAGAMENTO REGISTRADO DESTA VENDA -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger" name="btnEnviarNFE"><i class="mdi mdi-web"></i> Enviar NFe</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
           
    
    
   <!-- JANELA MODAL IMPRESSAO -->
   <div class="modal fade" id="ImpressaoModal">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h3 class="modal-title" id="exampleModalLabel">Confirma Impressão do Pedido?</h3>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
               </div>
               <div class="modal-body">
                   <form method="post" name="frmImpressao">
                       <div class="form-group">
                           <label for="message-text" class="col-form-label text-justify">Anote abaixo as informações / observações que deverão ser impressas neste pedido.</label>
                       </div>
                       <div class="form-group">
                           <label for="message-text" class="col-form-label">Observações:</label>
                           <textarea name="txtOBS" class="form-control" rows="5" autofocus></textarea>
                       </div>

                       <div class="modal-footer">
                           <button type="submit" class="btn btn-danger" name="btnImprimir">Confirmar</button>
                           <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                       </div>
                   </form>
               </div>
           </div>
       </div>
   </div>
            
    
    
     
    <!-- Modal Excluir  -->
    <!-- JANELA MODAL EXCLUSÃO -->
    <script>
        function setaDadosModal(id, nome) {
            document.getElementById('idOrcamentoDel').value = id;
            document.getElementById('recipient-idDel').value = id;
            document.getElementById('idOrcamentoNome').value = nome;
        }
    </script>
    <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Confirma exclusão do Produto?</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Código:</label>
                            <input type="text" class="form-control" id="idOrcamentoDel" disabled />
                            <input type="hidden" name="txtCodigoProduto" class="form-control" id="recipient-idDel" />
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Descrição Produto:</label>
                            <input type="text" name="txtNome" class="form-control" id="idOrcamentoNome" disabled />
                        </div>


                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger" name="btnExcluir">Excluir</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
      
    
    <script type="text/javascript">
        
       function calculaParcela(){
            var total = document.frmValores.txtTotal.value;
            var parcelas = document.frmValores.txtParcelas.value;
            var valorParcela=parseFloat(total)/parseFloat(parcelas);
            document.frmValores.txtValor.value = parseFloat((document.frmValores.txtTotal.value) / (document.frmValores.txtParcelas.value)).toFixed(2);
        }
        
      
    </script>
    
    
    
    
    <!-- JANELA FORMA DE PAGAMENTO -->
    <!-- JANELA FORMA DE PAGAMENTO -->
    <!-- JANELA FORMA DE PAGAMENTO -->
    <script>
        function setaDadosModalPagamento(id, nome, total) {
            document.getElementById('idVenda').value = id;
            document.getElementById('idVendaPagamento').value = id;
            document.getElementById('idVendaNome').value = nome;
            document.getElementById('totalVenda').value = total;
            document.getElementById('totalVenda2').value = total;
            document.getElementById('txtValor').value = total;
        }
    </script>
    <div class="modal fade" id="PagamentoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Forma Pagamento do Pedido</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" name="frmValores">
                        <div class="row">
                            <div class="form-group col-md-2" style="float:left">
                                <label for="recipient-name" class="col-form-label">Código Pedido Venda:</label>
                                <input type="text" class="form-control" id="idVenda" disabled />
                                <input type="hidden" name="txtCodigoVenda" class="form-control" id="idVendaPagamento" />
                                <input type="hidden" name="txtTotal" class="form-control" id="totalVenda" />
                            </div>
                            <div class="form-group col-md-8" style="float:left">
                                <label for="message-text" class="col-form-label">Cliente:</label>
                                <input type="text" class="form-control" id="idVendaNome" disabled />
                            </div>
                            <div class="form-group col-md-2" style="float:left">
                                <label for="message-text" class="col-form-label">TOTAL:</label>
                                <input type="text" class="form-control text-right" id="totalVenda2" disabled />
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="form-group col-md-3" style="float:left">
                                <label for="recipient-name" class="col-form-label">Forma de Pagamento:</label>
                                <select class="form-control" name="txtFormaPagamento">
                                    <option></option>
                                    <option value="DINHEIRO" selected>DINHEIRO</option>
                                    <option value="CARTAO DEBITO">CARTAO DEBITO</option>
                                    <option value="CARTAO CREDITO">CARTAO CREDITO</option>
                                    <option value="CHEQUE">CHEQUE</option>
                                    <option value="OUTRAS">OUTRAS</option>
                                </select>
                            </div>
                            <div class="form-group col-md-1" style="float:left">
                                <label for="message-text" class="col-form-label">Parcelas:</label>
                                <input type="number" class="form-control" value="1" name="txtParcelas" onchange="calculaParcela(this);" />
                            </div>
                            <div class="form-group col-md-2" style="float:left">
                                <label for="message-text" class="col-form-label">R$ Parcela:</label>
                                <input type="text" class="form-control text-right"  name="txtValor"/>
                            </div>
                            <div class="form-group col-md-4" style="float:left">
                                <label for="message-text" class="col-form-label">Obs:</label>
                                <input type="text" name="txtOBS" class="form-control"/>
                            </div>
                            <div class="form-group col-md-2" style="float:left">
                                <button type="submit" style="margin-top:35px" name="btnLancar" class="btn btn-info" value="Lançar">Lançar Pagamentos</button>
                            </div>
                        </div>
                    </form>
                        
                    <!-- TRAZ PAGAMENTOS REGISTRADO DESTA VENDA -->
                    <?php
                        $buscaPagamentos=mysqli_query($conecta, "SELECT * FROM vendas_pagamento WHERE fkIdVenda=".base64_decode($_GET['ref']));
                        if(mysqli_num_rows($buscaPagamentos)>=1){
                    ?>
                        <div class="form-group m-t-30">
                            <h6>FORMA DE PAGAMENTO LANÇADA</h6>
                            <table class="table table-hover" style="font-size:12px;">
                                <tr>
                                    <th class="text-center" width="6%">#</th>
                                    <th width="3%">Id</th>
                                    <th width="5%">Data</th>
                                    <th width="5%" class="text-right">Valor</th>
                                    <th>Forma Pagamento</th>
                                    <th>Obs</th>
                                </tr>
                                <?php
                                    while($resultPagamentos=mysqli_fetch_assoc($buscaPagamentos)){
                                ?>
                                <tr>
                                    <td class="text-center" width="6%">
                                         <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#DeletePagamentoModal" onclick="setaDadosModalDeletePagameto('<?php echo $resultPagamentos['pkId'] . "', '" . $resultPagamentos['data'] . "', '" . $resultPagamentos['valor'] ?>')"  title="Excluir Pagamento"><i class="fas fa-eraser"></i></button>
                                    </td>
                                    <td width="3%"><?php echo $resultPagamentos['pkId'] ?></td>
                                    <td width="8%"><?php echo date('d/m/Y', strtotime($resultPagamentos['data'])) ?></td>
                                    <td width="5%" class="text-right"><?php echo $resultPagamentos['valor'] ?></td>
                                    <td width="20%"><?php echo $resultPagamentos['formaPagamento'] ?></td>
                                    <td width="58%"><?php echo $resultPagamentos['obs'] ?></td>
                                </tr>                                    
                                <?php } ?>
                            </table>
                        </div>
                    <?php } ?>
                    <!-- FINAL TRAZ PAGAMENTO REGISTRADO DESTA VENDA -->

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
                
    
    
    
    
    
    <!-- Modal Excluir  -->
    <!-- JANELA MODAL EXCLUSÃO DE PAGAMENTO -->
    <script>
        function setaDadosModalDeletePagameto(id, nome, valor) {
            document.getElementById('idPagamento').value = id;
            document.getElementById('idPagamentoDel').value = id;
            document.getElementById('dataParcela').value = nome;
            document.getElementById('valorParcela').value = valor;
        }
    </script>
    <div class="modal fade" id="DeletePagamentoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Confirma exclusão do Pagamento?</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Código:</label>
                            <input type="text" class="form-control" id="idPagamento" disabled />
                            <input type="hidden" name="txtCodigoPagamento" class="form-control" id="idPagamentoDel" />
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Data:</label>
                            <input type="text" class="form-control" id="dataParcela" disabled />
                        </div>
                        <div class="form-group">                            
                            <label for="message-text" class="col-form-label">Valor Parcela:</label>
                            <input type="text" class="form-control" id="valorParcela" disabled />
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger" name="btnExcluirPagamento">Excluir</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
                
    
    
    
    
    
    
    <!-- JANELA TROCA CLIENTE -->
    <div class="modal fade" id="TrocaCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Troca de Cliente Venda</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="form-group">
                            <h4 class="control-label text-justify m-b-20 m-t-20">Aqui você pode selecionar outro cliente para esta venda, ok?</h4>
                        </div>

                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Cliente:</label>
                            <select class="form-control" name="txtCliente">
                                <option></option>
                                <?php
                                    $queryCliente=mysqli_query($conecta, "SELECT * FROM clientes ORDER BY razaoSocial");
                                    while($resultCliente=mysqli_fetch_assoc($queryCliente)){
                                ?>
                                <option value="<?php echo $resultCliente['pkId'] ?>"><?php echo $resultCliente['razaoSocial'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-info" name="btnConfirmar">Confirmar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <!-- JANELA MODAL NOVO CLIENTE -->
       <div class="modal fade" id="NovoCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
           <div class="modal-dialog" role="document">
               <div class="modal-content">
                   <div class="modal-header">
                       <h3 class="modal-title" id="exampleModalLabel">Cadastro de Novo Cliente</h3>
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                       </button>
                   </div>
                   <div class="modal-body">
                       <form method="post" name="frmCliente">

                           <div class="box-body">

                               <div class="row" style="">
                                   <div class="form-group col-md-3">
                                       <label>Tipo:</label>
                                       <select name="txtTipoCliente" class="form-control" required>
                                           <option value="">Selecione</option>
                                           <option value="FISICA">PESSOA FÍSICA</option>
                                           <option value="JURIDICA">PESSOA JURÍDICA</option>
                                       </select>
                                   </div>

                                   <div class="form-group col-md-3">
                                       <label>CPF:</label>
                                       <input type="text" name="txtCPF" class="form-control" data-inputmask="'mask': '999.999.999-99'" data-mask="" placeholder="Digite o CFP do cliente">
                                   </div>

                                   <div class="form-group col-md-3">
                                       <label>CNPJ:</label>
                                       <input type="text" name="txtCNPJ" class="form-control" data-inputmask="'mask': '99.999.999/9999-99'" data-mask="" placeholder="Digite o CNPJ do cliente">
                                   </div>

                                   <div class="form-group col-md-3">
                                       <label>I.E.:</label>
                                       <input type="text" name="txtIE" class="form-control" data-inputmask="'mask': '999.999.999.999'" data-mask="" placeholder="Inscrição Estadual">
                                   </div>

                                   <div class="form-group col-md-2" style="clear:both">
                                       <label>IdCliente:</label>
                                       <input type="text" name="txtCodigo" class="form-control" placeholder="Id Cliente" disabled>
                                   </div>

                                   <div class="form-group col-md-10">
                                       <label>Razão Social:</label>
                                       <input type="text" name="txtRazaoSocial" class="form-control" placeholder="Digite a nome/razão social (obrigatório)" required>
                                   </div>

                                   <div class="form-group col-md-9">
                                       <label>Endereço:</label>
                                       <input type="text" name="txtEndereco" class="form-control" placeholder="Digite o endereco">
                                   </div>

                                   <div class="form-group col-md-3">
                                       <label>Numero:</label>
                                       <input type="text" name="txtNumero" class="form-control" placeholder="Informe o nº">
                                   </div>

                                   <div class="form-group col-md-5">
                                       <label>Bairro:</label>
                                       <input type="text" name="txtBairro" class="form-control" placeholder="Digite o bairro">
                                   </div>

                                   <div class="form-group col-md-4">
                                       <label>Cidade:</label>
                                       <input type="text" name="txtCidade" class="form-control" placeholder="Digite a cidade" value="TAUBATE">
                                   </div>

                                   <div class="form-group col-md-3">
                                       <label>Cep:</label>
                                       <input type="text" name="txtCep" class="form-control" data-inputmask="'mask': '99.999-999'" data-mask="" value="12090-000" placeholder="Digite o cep">
                                   </div>

                                   <div class="form-group col-md-3">
                                       <label>UF:</label>
                                       <select name="txtEstado" class="form-control">
                                           <option value=""></option>
                                           <option value="SP" selected>SP</option>
                                           <option value="RJ">RJ</option>
                                           <option value="MG">MG</option>
                                       </select>
                                   </div>

                                   <div class="form-group col-md-3">
                                       <label>CodIBGE:</label>
                                       <input type="text" name="txtCodigoIBGE" class="form-control" placeholder="Codigo IBGE" value="3554102">
                                   </div>

                                   <div class="form-group col-md-3">
                                       <label>Telefone:</label>
                                       <input type="text" name="txtTelefone" class="form-control" data-inputmask="'mask': '(99) 9999-9999'" data-mask="" placeholder="Telefone">
                                   </div>

                                   <div class="form-group col-md-3">
                                       <label>Celular:</label>
                                       <input type="text" name="txtCelular" class="form-control" data-inputmask="'mask': '(99) 99999-9999'" data-mask="" placeholder="Celular">
                                   </div>

                                   <div class="form-group col-md-6">
                                       <label>Email:</label>
                                       <input type="text" name="txtEmail" class="form-control" placeholder="Digite um email válido">
                                   </div>


                               </div>

                           </div>

                           <div class="box-footer">
                               <button type="submit" class="btn btn-primary" name="btnSalvarCliente"><i class="fa fa-save"></i> Salvar Dados</button>
                               <a href="." class="btn btn-default" name="btnCancelar"><i class="fa fa-ban"></i> Cancelar</a>
                           </div>
                       </form>

                   </div>
               </div>
           </div>
       </div>    
    <!-- FIM JANELA MODAL -->

    
   

     <!-- -------------------------------------------------------------------------------------------------------- CADASTRO DE PRODUTOS NO ORCAMENTO -------------------- -->
         <div class="modal fade" id="InsertProduto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <form method="post" name="frmInsertProduto">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="exampleModalLabel">Inclusão de Produtos</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="margin-bottom:30px;">

                                <div class="form-group col-md-12">
                                    <label>Produto:</label><br>
                                    <select name="txtCodigoCliente" id="txtCodigoCliente" class="form-control select2" style="width: 100%" placeholder="Selecione" required>
                                        <option value=""></option>
                                         <?php
                                            $queryProdutos=mysqli_query($conecta, "SELECT * FROM produtos ORDER BY nome ");
                                            if(mysqli_num_rows($queryProdutos)>0){
                                                while($resultProdutos=mysqli_fetch_assoc($queryProdutos)){
                                        ?>
                                        <option value="<?php echo $resultProdutos['pkId'] ?>"><?php echo $resultProdutos['nome'] ?></option>
                                        <?php } } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6" style="float:left">
                                    <label>Preço:</label>
                                    <input type="text" name="txtPreco" id="txtPreco" class="form-control text-right" placeholder="Preco Venda" value="0.00" required onblur="formataPreco(this);" >
                                </div>

                                <div class="form-group col-md-6" style="float:left">
                                    <label>Quantidade:</label>
                                    <input type="text" name="txtQuantidade" value="1" class="form-control text-right" placeholder="Quantidade" required onblur="formataQuantidade(this);" >
                                </div>

                                <div class="form-group col-md-6" style="float:left">
                                    <label>Qde Estoque:</label>
                                    <input type="text" name="txtEstoque" id="txtEstoque" class="form-control text-right" placeholder="Preco Venda" value="000" disabled >
                                </div>


                        </div>
                        <br><br>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-primary" name="btnIncluir" value="Incluir Produto"/>
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- AJAX PARA ATUALIZAR PRECO E ESTOQUE -->
           
    <script type="text/javascript">
        
       function formataPreco(valor){
           var preco=(document.frmInsertProduto.txtPreco.value);
           preco=parseFloat(preco).toFixed(2);
           document.frmInsertProduto.txtPreco.value = preco;
       }
        
      
    </script>
    
        <script type='text/javascript'>
            $(document).ready(function(){
                $('#txtCodigoCliente').change(function(){
                    var $IdProduto = $("#txtCodigoCliente option:selected").val();
                    //var $preco = $("#txtPreco").val();
                    $.getJSON('function.php',{ 
                        ref: $( this ).val() 
                    },function( json ){
                        $("#txtPreco").val( json.preco );
                        $("#txtEstoque").val( json.estoque );
                    });
                });
            });
        </script>
    
    <script src="../resources/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../resources/assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="../resources/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../resources/dist/js/app.min.js"></script>
    <script src="../resources/dist/js/app.init.horizontal-fullwidth.js"></script>
    <script src="../resources/dist/js/app-style-switcher.horizontal.js"></script>
    <script src="../resources/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../resources/assets/extra-libs/sparkline/sparkline.js"></script>
    <script src="../resources/dist/js/waves.js"></script>
    <script src="../resources/dist/js/sidebarmenu.js"></script>
    <script src="../resources/dist/js/custom.min.js"></script>
    <script src="../resources/assets/libs/chartist/dist/chartist.min.js"></script>
    <script src="../resources/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="../resources/assets/extra-libs/c3/d3.min.js"></script>
    <script src="../resources/assets/extra-libs/c3/c3.min.js"></script>
    <script src="../resources/assets/libs/chart.js/dist/Chart.min.js"></script>
    <script src="../resources/dist/js/pages/dashboards/dashboard1.js"></script>
    <script src="../resources/dist/js/custom.min.js "></script>
    <script src="../resources/assets/libs/inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
    <script src="../resources/dist/js/pages/forms/mask/mask.init.js"></script>
    
    
    <script src="../resources/assets/libs/inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
    <script src="../resources/dist/js/pages/forms/mask/mask.init.js"></script>

</body> 

</html>