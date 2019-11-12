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

if(isset($_POST['btnEnviar'])){
    
      
    $dataVencimento = implode('-', array_reverse(explode('/', $_POST['txtVencimento'])));
    $databaixa = implode('-', array_reverse(explode('/', $_POST['txtBaixa'])));

    $query = mysqli_query($conecta, "INSERT INTO contas_pagar (nome , valor, vencimento, baixa, observacoes, status) VALUES (
    '" . trim(strtoupper($_POST["txtNome"])) . "' , 
    '" . trim($_POST["txtValor"]) . "' , 
    '" . $dataVencimento . "' , 
    '" . $databaixa . "', 
    '" . trim($_POST["txtOBS"]) . "' ,
    'ABERTOS'
    )");

    if(query){
        $msg = base64_encode('Cadastro efetuado com sucesso!');
        $type = base64_encode('info');

    } else {

        $msg = base64_encode('Erro ao efetuar cadastro! Por favor, tente novamente mais tarde.');
        $type = base64_encode('error');

    }

    $_POST['btnEnviar']=nothing;
    
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
                        <h4 class="page-title">Contas a Pagar</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="../">Home</a></li>
                                    <li class="breadcrumb-item"><a href=".">Contas a Pagar</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Novo</li>
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
                            <div class="card-header bg-info">
                                <h6 class="mb-0 text-white">Cadastro Novo</h6>
                            </div>
                            
                            <form method="post" name="frmCadastro">
                                <div class="form-body">
                                    <div class="card-body">
                                        <div class="row pt-3">
                                            
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label class="control-label">Descrição Conta</label>
                                                    <input type="text" name="txtNome" class="form-control" placeholder="Ex: Energia Elétrica">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Vencimento <small class="text-muted">dd/mm/aaaa</small></label>
                                                    <input type="text" name="txtVencimento" class="form-control date-inputmask" id="date-mask" placeholder="Digite a Data de Vencimento">
                                                </div>

                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Valor Conta</label>
                                                    <input type="text" name="txtValor" class="form-control" placeholder="Ex: 100,00"  onblur="formataNumero(this)">
                                                </div>
                                            </div>
                                            <script type="text/javascript">
                                                function formataNumero(){
                                                    var campo3 = parseFloat( document.frmCadastro.txtValor.value).toFixed(2);
                                                    document.frmCadastro.txtValor.value = campo3.replace(",",".");
                                                }
                                            </script>

                                        </div>
                                        <hr/>
                                        <h5 class="mb-0 m-t-30 text-blue">Sobre Pagamento</h5>
                                        <div class="row pt-3">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Baixa <small class="text-muted">dd/mm/aaaa</small></label>
                                                    <input type="text" name="txtBaixa" class="form-control date-inputmask" id="date-mask" placeholder="Digite a Data de Baixa">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label>OBS sobre pagamento</label>
                                                    <textarea name="txtOBS" class="form-control date-inputmask" rows="5" placeholder="Ex: Cheque emitido numero 1020 Banco do Brasil"></textarea>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="form-actions">
                                        <div class="card-body">
                                            <button type="submit" class="btn btn-success" name="btnEnviar"> <i class="fa fa-check"></i> Salvar</button>
                                            <a href="."><button type="button" class="btn btn-dark">Cancelar</button></a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Conteudo PAgina  -->
            
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
    <script src="../resources/dist/js/custom.min.js "></script>
    <script src="../resources/assets/libs/inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
    <script src="../resources/dist/js/pages/forms/mask/mask.init.js"></script>
    
    
    <script src="../assets/libs/inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
    <script src="../dist/js/pages/forms/mask/mask.init.js"></script>

</body> 

</html>