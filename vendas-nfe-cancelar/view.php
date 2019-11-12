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
    
        
    if(empty($_POST['txtProtocolo'])){
        $msg = base64_encode('Erro ao Gerar NFe! Número do Protocolo de Entrega não foi informado! Por favor verifique e tente novamente.');
        $type = base64_encode('error');
        header('Location:view.php?msg='.$msg.'&type='.$type.'&ref='.$_GET['ref']);
        exit;
    }
    
    mysqli_query($conecta, "UPDATE vendas SET protocolo = '" . $_POST['txtProtocolo'] . "', obs='" . $_POST['txtMotivo'] . "', status='CANCELADA SEFAZ' WHERE pkId=".base64_decode($_GET['ref']));
    
    
    // VERIFICA SE É CLIENTE CONSUMIDOR FINAL
    header('Location:createOrderCancel.php?ref='.$_GET['ref']);
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
                        <h4 class="page-title">Vendas NF-e | Cancelamento</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="../">Home</a></li>
                                    <li class="breadcrumb-item"><a href=".">Vendas NF-e</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Cancelamento</li>
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
                                            
                                            <h3><b class="text-danger"><?php echo $result['nome'] ?></b></h3>
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
                                            <div class="btn-group" style="float:right; margin-right:10px; margin-bottom: 10px">
                                                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Opções NF-e
                                                </button>
                                                <div class="dropdown-menu">
                                                    <button class="dropdown-item" data-toggle="modal" data-target="#NFeModal" onclick="setaDadosModalNFe('<?php echo base64_decode($_GET['ref']) . "', '" . $result['nome'] ?>', '<?php echo $result['total'] ?>', '<?php echo $result['chave'] ?>')"  title="Enviar NFe a Sefaz")><i class="mdi mdi-web"></i>  Enviar NF-e Sefaz</button>
                                                </div>
                                            </div>
                                        </div>
                                        <table class="table table-hover" style="font-size:12px;">
                                            <thead>
                                                <tr>
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
                                <div class="col-md-12" style="margin-top:10px;">
                                    
                                    <!-- PAGAMENTOS -->
                                    <div class="table-responsive" style="clear: both;">
                                        <?php
                                            $buscaPagamentos=mysqli_query($conecta, "SELECT * FROM vendas_pagamento WHERE fkIdVenda=".base64_decode($_GET['ref']));
                                            if(mysqli_num_rows($buscaPagamentos)>=1){
                                        ?>
                                            <div class="form-group">
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
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="pull-right m-t-10 text-right">
                                        <p>vat (10%) : $138 </p>-->
                                        <hr>
                                        <h2><b>Total :</b> $ <?php echo isset($result['total']) ? $result['total'] : '0,00' ?></h2>
                                    </div>
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
        function setaDadosModalNFe(id, nome, total, chave) {
            document.getElementById('idVendaNFe').value = id;
            document.getElementById('idVendaPagamentoNFe').value = id;
            document.getElementById('idVendaNomeNFe').value = nome;
            document.getElementById('totalVendaNFe').value = total;
            document.getElementById('totalVenda2NFe').value = total;
            document.getElementById('chave').value = chave;
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
                            
                            <div class="form-group col-md-7" style="float:left">
                                <label for="message-text" class="col-form-label">Chave NFe:</label>
                                <input type="text" class="form-control" name="txtChave" id="chave" disabled />
                            </div>
                            
                            <div class="form-group col-md-5" style="float:left">
                                <label for="message-text" class="col-form-label">Numero Protocolo:</label>
                                <input type="text" class="form-control" name="txtProtocolo" />
                            </div>
                            
                            <div class="form-group col-md-12" style="float:left">
                                <label for="message-text" class="col-form-label">Motivo Cancelamento:</label>
                                <input type="text" class="form-control" name="txtMotivo" />
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