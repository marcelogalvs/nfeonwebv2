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


//EXCLUSAO DE ORCAMENTO
if(isset($_POST['btnExcluir'])){
    $query=mysqli_query($conecta, "DELETE FROM vendas WHERE pkId = " . $_POST['txtCodigoOrcamento']) or die(mysql_error());
    $query=mysqli_query($conecta, "DELETE FROM vendas_itens WHERE fkIdVenda = " . $_POST['txtCodigoOrcamento']) or die(mysql_error());
    $query=mysqli_query($conecta, "DELETE FROM vendas_transp WHERE fkIdVenda = " . $_POST['txtCodigoOrcamento']) or die(mysql_error());
    $query=mysqli_query($conecta, "DELETE FROM vendas_pagamento WHERE fkIdVenda = " . $_POST['txtCodigoOrcamento']) or die(mysql_error());
    if($query){
        $msg = base64_encode('Exclusão efetuada com sucesso!');
        $type = base64_encode('info');
    } else {
        $msg = base64_encode('Erro ao efetuar exclusão! Por favor, tente novamente mais tarde.');
        $type = base64_encode('error');
    }
    header('Location:?msg='.$msg.'&type='.$type);
    exit;

}




//CRIACAO DE NOVO ORCAMENTO
if(isset($_POST['btnConfirmar'])){
    $query=mysqli_query($conecta, "INSERT INTO vendas (fkIdCliente, fkidCFOP, data, status) VALUES (1, '" . $_POST['txtCFOP'] . "',  '" . date('Y-m-d') . "', 'EM DIGITACAO')") or die(mysql_error());

    if($query){
        $buscaVenda=mysqli_query($conecta, "SELECT * FROM vendas ORDER BY pkId DESC LIMIT 1");
        $resultVenda=mysqli_fetch_assoc($buscaVenda);
        $pkIdVenda=base64_encode($resultVenda['pkId']);
        
        //INSERE TRANSPORTADORA NO PEDIDO AUTOMATICAMENTE
        mysqli_query($conecta, "INSERT INTO vendas_transp (fkIdVenda, fkIdTransportadora) VALUES (".base64_decode($pkIdVenda).", 1)");
        
        header('Location:insert.php?ref='.$pkIdVenda.'&msg='.$msg.'&type='.$type);
        exit;
    } else {
        $msg = base64_encode('Erro ao efetuar inclusão! Verifique os dados e tente novamente.');
        $type = base64_encode('error');
    }
    header('Location:index.php?msg='.$msg.'&type='.$type);
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
    
    <link href="../resources/dist/css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../resources/assets/libs/select2/dist/css/select2.min.css">
    <!-- Custom CSS -->
    
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
                                    <li class="breadcrumb-item active" aria-current="page">Vendas NF-e</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            
            

            
            <!-- Conteudo da Pagina  -->
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
                
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Arquivo de Cancelamento NF-e</h4>
                                <h6 class="card-subtitle">Cancele novas <code>NFe's </code> ou altere e imprima as informações de uma NF-e <code>cancelada</code>.</h6>
                                <div class="col-md-6 col-lg-2" style="float:left; margin-left:-10px;">
                                    <form name="frmBusca" method="post" onsubmit="this.form.submit()">
                                        <select class="form-control" name="txtBusca" onchange="this.form.submit()">
                                            <option></option>
                                            <option value="TODAS">TODAS</option>
                                            <option value="EM DIGITACAO">EM DIGITACAO</option>
                                            <option value="ENVIADO A SEFAZ">ENVIADO A SEFAZ</option>
                                        </select>
                                    </form>
                                </div>  
                                <button class="btn btn-primary" style="float:right;"  data-toggle="modal" data-target="#NewOrcamento" ><i class="fas fa-plus-circle"></i> Cadastrar Novo</button>                               
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col" width="90"></th>
                                            <th scope="col" width="6%">Data</th>
                                            <th scope="col" width="6%">Data Emissao</th>
                                            <th scope="col" width="4%">IdNFe</th>
                                            <th scope="col" width="4%">NFe</th>
                                            <th scope="col" width="14.2%">Cliente</th>
                                            <th scope="col">Contatos</th>
                                            <th scope="col">Total</th>
                                            <th scope="col">Chave</th>
                                            <th scope="col">XML</th>
                                            <th scope="col">PDF</th>
                                            <th scope="col">Operação</th>
                                            <th width="8%">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $query=mysqli_query($conecta, "SELECT A.*, B.razaoSocial, B.telefone, B.celular, B.email FROM vendas A, clientes B WHERE  B.pkId = A.fkIdCliente AND A.status = 'ENVIADO A SEFAZ' ORDER BY A.pkId DESC") or die(mysql_error());                                                
                                            if(mysqli_num_rows($query)>=1){
                                                while($result=mysqli_fetch_assoc($query)){
                                        ?>
                                        <tr style="font-size:12px">
                                            <td>
                                                <a title="Imprimir Pedido de Venda" target="_blank" href="relVenda.php?ref=<?php echo base64_encode($result["pkId"]) ?>" class="btn btn-success" style="width:25px; height:25px; padding-left:5px; padding-top:2px;"><i class="fa fa-print"></i></a>
                                                <?php 
                                                    if($result['status'] == 'ENVIADO A SEFAZ') {
                                                ?>
                                                <a title="Editar Pedido de Venda" href="view.php?ref=<?php echo base64_encode($result["pkId"]);?>" class="btn btn-primary btn-sm" style="width:25px; height:25px; padding-left:5px; padding-top:2px;"><i class="fas fa-edit"></i></a>
                                               
                                                <?php } ?>
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($result['data'])) ?></td>
                                            <td><?php echo $result['dataSaida']<>'0000-00-00' ? date('d/m/Y', strtotime($result['dataSaida'])) : '' ?></td>
                                            <td><?php echo $result['pkId'] ?></td>
                                            <td><?php echo $result['nf'] ?></td>
                                            <td><?php echo substr($result['razaoSocial'],0 ,33) ?></td>
                                            <td><?php echo $result['telefone']; if(!empty($result['celular'])) { echo "/". $result['celular']; } ?></td>
                                            <td style="font-weight: bold"><?php echo number_format($result['total'], 2, ',', '.') ?></td>
                                            <td><?php echo $result['chave'] ?></td>
                                            <td>
                                                <a href="<?php echo $result['xml']; ?>" target="_blank">
                                                    <?php echo substr($result['xml'], 8, 100); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="<?php echo $result['pdf']  ?>" target="_blank">
                                                    <?php echo substr($result['pdf'], 8, 100); ?>
                                                </a>
                                            </td>
                                            <td style="font-weight:bold"><?php echo $result['fkIdCFOP'] ?></td>
                                            <td>
                                                <?php 
                                                    if($result['status']=='ENVIADO A SEFAZ') { 
                                                        echo "<span class='label label-primary'>" . $result['status'] . "</span>"; 
                                                    } elseif ($result['status']=='CANCELADO') {
                                                        echo "<span class='label label-danger'>" . $result['status'] . "</span>"; 
                                                    } elseif ($result['status']=='EM DIGITACAO') {
                                                        echo "<span class='label label-success'>" . $result['status'] . "</span>"; 
                                                    } else {
                                                        if($result['status']=='ENCERRADO') {
                                                        echo "<span class='label label-danger'>" . $result['status'] . "</span>";
                                                        }
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php } } else { ?>
                                        <tr>
                                            <td colspan="11">
                                                <h6 class="card-subtitle text-center">Não foram encontrados registros.</h6>
                                            </td>
                                        </tr>
                                        
                                        <?php } ?>
                                    </tbody>
                                </table> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Conteudo PAgina  -->
            
            <!-- Modal Excluir  -->
            <!-- JANELA MODAL EXCLUSÃO -->
              
            <script>
                function setaDadosModal(id, nome) {
                    document.getElementById('idOrcamentoDel').value = id;
                    document.getElementById('idOrcamento').value = id;
                    document.getElementById('idOrcamentoNome').value = nome;
                }
            </script>
            
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="exampleModalLabel">Confirma exclusão do Pedido de Venda?</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post">
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Código:</label>
                                    <input type="text" class="form-control" id="idOrcamentoDel" disabled />
                                    <input type="hidden" name="txtCodigoOrcamento" class="form-control" id="idOrcamento" />
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="col-form-label">Nome do Cliente:</label>
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
             
            
            
            <!-- Fim Modal Excluir  -->
            
            
            <!-- JANELA NOVO ORCAMENTO -->
            <div class="modal fade" id="NewOrcamento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="exampleModalLabel">Confirma Nova Venda NF-e?</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post">
                                <div class="form-group">
                                    <h4 class="control-label text-justify m-b-20 m-t-20">Na próxima tela você selecionará um cliente e os produtos para essa venda, ok?</h4>
                                </div>
                                                               
                                <div class="form-group">
                                    <label for="message-text" class="col-form-label">Tipo de Venda (CFOP):</label>
                                    <select name="txtCFOP" class="form-control" required>
                                        <option></option>
                                        <?php 
                                            $queryCFOP=mysqli_query($conecta, "SELECT * FROM cfop ORDER BY pkId");
                                            while($resultCFOP=mysqli_fetch_assoc($queryCFOP)){
                                        ?>
                                        <option value="<?php echo $resultCFOP['pkId'] ?>"><?php echo $resultCFOP['pkId'] . " - " . substr($resultCFOP['descricao'], 0, 45) ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="message-text" class="col-form-label">Data NF-e:</label>
                                    <input type="text" name="txtData" class="form-control" value="<?php echo date('d/m/Y') ?>" disabled />
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
            
           
            
            
            <!-- footer -->
            <?php
                include('../footer.php');
            ?>
            <!-- End footer -->
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
    
    <script src="../resources/dist/js/custom.min.js"></script>
    <!-- This Page JS -->
    <script src="../resources/assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="../resources/assets/libs/select2/dist/js/select2.min.js"></script>
    <script src="../resources/dist/js/pages/forms/select2/select2.init.js"></script>  


    
</body>

</html>