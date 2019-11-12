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
    $atualizaTotal = mysqli_query($conecta, "UPDATE orcamentos SET obs = '". $_POST['txtOBS'] ."' WHERE pkId =". base64_decode($_GET['ref']));
    $link='relOrcamento.php?ref='.$_GET['ref'];
    echo "<script>window.open('".$link."', '_blank');</script>";
    $msg = base64_encode('Orçamento Impresso com sucesso! Atualize a página antes de imprimir novamente...');
    $type = base64_encode('info');
    $page = '?ref='.$_GET['ref'].'&msg='.$msg.'&type='.$type;
    $sec = "1";
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
    
 <script type="text/javascript">        
        
    $(document).ready(function(){
        $('#txtCodigoProduto').blur(function(){
            var $IdProduto = $("#txtCodigoProduto").val();
            $.getJSON('fnBuscaProdExpress.php',{ 
            ref: $( this ).val() 
            },function( json ){
                $("#txtPreco").val( json.preco )
                $("#txtNome").val( json.nome )
            });
        });
    });


    $(document).ready(function(){
        $('#txtNomeProduto').change(function(){
            var $IdProduto = $("#txtNomeProduto").val();
            $.getJSON('fnBuscaProdExpress.php',{ 
            ref: $( this ).val() 
            },function( json ){
                $("#txtPreco").val( json.preco )
            });
        });
    });

    function formataNumero(){
        var campo2 = parseFloat( document.frmCadProduto.txtPreco.value).toFixed(2);
        var campo3 = parseFloat( document.frmCadProduto.txtQuantidade.value).toFixed(2);
        document.getElementById('txtPreco').value = campo2.replace(',', ".");
        document.getElementById('txtSubTotal').value = campo3 * campo2;
    }
     
    function formataPreco(){
        var campo2 = parseFloat( document.frmInsertProduto.txtPreco.value).toFixed(2);
        document.getElementById('txtPreco').value = campo2.replace(',', ".");
    }

    function calculaTotal(){            
        if(document.frmFechamento.txtDesconto.value==""){
            var desconto=0;
        } else {
            var desconto = parseFloat(document.frmFechamento.txtDesconto.value).toFixed(2);
        }
        var total = document.frmFechamento.txtTotal.value;
        document.frmFechamento.txtDesconto.value = parseFloat(desconto).toFixed(2);
        document.frmFechamento.txtTotalPago.value = parseFloat(total-desconto).toFixed(2);
    }

    function buscaTotal(){
        var total = document.frmFechamento.txtTotal.value;
        document.frmFechamento.txtTotalPago.value = parseFloat(document.frmFechamento.txtTotal.value).toFixed(2);
        document.frmFechamento.txtDesconto.value = parseFloat(0).toFixed(2);
    }
     
</script>


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
                                    <li class="breadcrumb-item active" aria-current="page">Lançamento Produtos</li>
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
                                            
                                            <button class="btn btn-info m-b-10" style="float:right; margin-right:10px" data-toggle="modal" data-target="#InsertProduto" ><i class="fas fa-plus"></i> Adicionar Produto</button>
                                           <!-- <button class="btn btn-danger" type="submit"> <span><i class="fa fa-shopping-cart"></i> Gerar Pedido Venda</span> </button>
                                            <button class="btn btn-default btn-outline" type="button" data-toggle="modal" data-target="#ImpressaoModal"> <span><i class="fa fa-print"></i> Imprimir</span> </button>-->
                                        </div>
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="6%">#</th>
                                                    <th width="6%">Id Produto</th>
                                                    <th>Descrição Produto</th>
                                                    <th class="text-right">R$ Unit</th>
                                                    <th class="text-right">Qtde</th>
                                                    <th class="text-right">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $buscaProdutos=mysqli_query($conecta, "SELECT A.*, B.nome AS nomeProduto FROM vendas_itens A, produtos B WHERE B.pkId=A.fkIdProduto AND A.fkIdVenda=".base64_decode($_GET['ref']));
                                                    if(mysqli_num_rows($buscaProdutos)>=1){
                                                        while($resultProdutos=mysqli_fetch_assoc($buscaProdutos)){
                                                ?>
                                                <tr>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#DeleteModal" onclick="setaDadosModal('<?php echo $resultProdutos['fkIdProduto'] . "', '" . $resultProdutos['nomeProduto'] ?>')"  title="Excluir"><i class="fas fa-eraser"></i></button>
                                                    </td>
                                                    <td class="text-center"><?php echo $resultProdutos['fkIdProduto'] ?></td>
                                                    <td><?php echo $resultProdutos['nomeProduto'] ?></td>
                                                    <td class="text-right"><?php echo $resultProdutos['preco'] ?></td>
                                                    <td class="text-right"><?php echo $resultProdutos['quantidade'] ?></td>
                                                    <td class="text-right"><?php echo number_format($resultProdutos['preco']*$resultProdutos['quantidade'], 2) ?></td>
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
                                    <div class="clearfix"></div>
                                    
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
    
    
    <!-- Modal Excluir  -->
  
    <!-- JANELA MODAL IMPRESSAO -->
   
    <div class="modal fade" id="ImpressaoModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Confirma Impressão do Orçamento?</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" name="frmImpressao">
                        <div class="form-group">
                            <label for="message-text" class="col-form-label text-justify">Anote abaixo as informações / observações que deverão ser impressas neste orçamento.</label>
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