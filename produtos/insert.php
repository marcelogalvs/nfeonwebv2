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
    
    
    $buscaID=mysqli_query($conecta, "SELECT * FROM produtos WHERE pkId = '" . $_POST['txtCodigo'] . "'");
    if(mysqli_num_rows($buscaID)>0){
        
        $msg = base64_encode('Erro ao efetuar cadastro! Código de Produto já utilizado! Por favor, tente utilizar outro código.');
        $type = base64_encode('error');
        header('Location:insert.php?msg='.$msg.'&type='.$type);
        exit;
        
    } else {
        if(empty($_POST["txtEstoqueAtual"])) {
            $estoqueAtual=0; 
        } else {
            $estoqueAtual=$_POST["txtEstoqueAtual"]; 
        }
        if(empty($_POST["txtEstoqueMinimo"])) {
            $estoqueMinimo=0; 
        } else {
            $estoqueMinimo=$_POST["txtEstoqueMinimo"]; 
        }
       
        $query = mysqli_query($conecta, "INSERT INTO produtos 
        (pkId, codigoBarras, nome , marca , estoqueAtual, estoqueMinimo, precoCusto, precoVenda, origem, unidadeComercial, unidadeTributaria, tributacaocsosn, tributacaoICMS, aliquotaICMS, tributacaoPIS, tributacaoCOFINS, fkIdNCM) 
        VALUES (
        '" . strtoupper(trim($_POST["txtCodigo"])) . "' , 
        '" . (trim($_POST["txtCodigoBarras"])) . "' , 
        '" . strtoupper(trim($_POST["txtNome"])) . "' , 
        '" . strtoupper(trim($_POST["txtMarca"])) . "' , 
        " . $estoqueAtual . " , 
        " . $estoqueMinimo . " , 
        '" . (trim($_POST['txtPrecoCusto'])) . "' , 
        '" . (trim($_POST["txtPrecoVenda"])) . "' , 
        '" . (trim($_POST["txtOrigem"])) . "' , 
        '" . (trim($_POST["txtUnidadeComercial"])) . "' , 
        '" . (trim($_POST["txtUnidadeTributaria"])) . "' , 
        '" . (trim($_POST["txtTributacaoCSOSN"])) . "' , 
        '" . (trim($_POST["txtTributacaoICMS"])) . "' , 
        '" . (trim($_POST["txtAliquotaICMS"])) . "' , 
        '" . (trim($_POST["txtTributacaoPIS"])) . "' , 
        '" . (trim($_POST["txtTributacaoCOFINS"])) . "' , 
        '" . (trim($_POST["txtNCM"])) . "')");


        if(query){
            $msg = base64_encode('Cadastro efetuado com sucesso!');
            $type = base64_encode('info');

        } else {

            $msg = base64_encode('Erro ao efetuar cadastro! Por favor, tente novamente mais tarde.');
            $type = base64_encode('error');

        }
        header('Location:index.php?msg='.$msg.'&type='.$type);
        exit;
        
    }    

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
        function formataPrecoCusto(){
            if(isNaN(document.frmCadastro.txtPrecoCusto.value) || (document.frmCadastro.txtPrecoCusto.value=="")){
                document.frmCadastro.txtPrecoCusto.value = '0.00';
            } else {
                var campo3 = parseFloat( document.frmCadastro.txtPrecoCusto.value).toFixed(2);
                document.frmCadastro.txtPrecoCusto.value = campo3.replace(",",".");    
            }
        }
        function formataPrecoVenda(){
            if(isNaN(document.frmCadastro.txtPrecoVenda.value) || (document.frmCadastro.txtPrecoVenda.value=="")){
                document.frmCadastro.txtPrecoVenda.value = '0.00';
            } else {
                var campo3 = parseFloat( document.frmCadastro.txtPrecoVenda.value).toFixed(2);
                document.frmCadastro.txtPrecoVenda.value = campo3.replace(",",".");
            }
        }
        function formataAliquotaICMS(){
            var campo3 = parseFloat( document.frmCadastro.txtAliquotaICMS.value).toFixed(2);
            document.frmCadastro.txtAliquotaICMS.value = campo3.replace(",",".");
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
                        <h4 class="page-title">Produtos</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="../">Home</a></li>
                                    <li class="breadcrumb-item"><a href=".">Produtos</a></li>
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
                                <h6 class="mb-0 text-white">Novo Cadastro</h6>
                            </div>
                            
                            <form method="post" name="frmCadastro">
                                <div class="form-body">
                                    <div class="card-body">
                                        <div class="row pt-3">
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label class="control-label">Cod Prod</label>
                                                    <input type="text" name="txtCodigo" class="form-control" autofocus>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Código Barras</label>
                                                    <input type="text" name="txtCodigoBarras" class="form-control" placeholder="Ex: 7890001233356">
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label class="control-label">Descricão Produto</label>
                                                    <input type="text" name="txtNome" class="form-control" placeholder="Ex: Sucao Maguary">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row pt-3">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Marca</label>
                                                    <input type="text" name="txtMarca" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label class="control-label">Est Atual</label>
                                                    <input type="text" name="txtEstoqueAtual" class="form-control text-right">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label class="control-label">Est Min</label>
                                                    <input type="text" name="txtEstoqueMinimo" class="form-control text-right" >
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">R$ Custo</label>
                                                    <input type="text" name="txtPrecoCusto" class="form-control text-right"  onblur="formataPrecoCusto(this);">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">R$ Venda</label>
                                                    <input type="text" name="txtPrecoVenda" class="form-control text-right" onblur="formataPrecoVenda(this);">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Un Coml</label>
                                                    <select name="txtUnidadeComercial" class="form-control">
                                                        <option value=""></option>
                                                        <option value="UN">UN - UNIDADE</option>
                                                        <option value="UN">CM - CENTIMETROS</option>
                                                        <option value="UN">BD - BALDE</option>
                                                        <option value="UN">CX - CAIXA</option>
                                                        <option value="UN">JG - JOGO</option>
                                                        <option value="UN">KG - KILOGRAMA</option>
                                                        <option value="UN">MT - METRO</option>
                                                        <option value="UN">MT - METRO QUADRADO</option>
                                                        <option value="UN">MT - METRO CUBICO</option>
                                                        <option value="UN">PC - PECA</option>
                                                        <option value="UN">PT - PACOTE</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Un Trib</label>
                                                    <select name="txtUnidadeTributaria" class="form-control">
                                                        <option value=""></option>
                                                        <option value="UN">UN - UNIDADE</option>
                                                        <option value="UN">CM - CENTIMETROS</option>
                                                        <option value="UN">BD - BALDE</option>
                                                        <option value="UN">CX - CAIXA</option>
                                                        <option value="UN">JG - JOGO</option>
                                                        <option value="UN">KG - KILOGRAMA</option>
                                                        <option value="UN">MT - METRO</option>
                                                        <option value="UN">MT - METRO QUADRADO</option>
                                                        <option value="UN">MT - METRO CUBICO</option>
                                                        <option value="UN">PC - PECA</option>
                                                        <option value="UN">PT - PACOTE</option>  
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    
                                        <div class="row pt-3">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Origem</label>
                                                    <select name="txtOrigem" class="form-control">
                                                        <option></option>
                                                        <?php
                                                            $buscaOrigem=mysqli_query($conecta, "SELECT * FROM origem ORDER BY pkId");
                                                            while($resultOrigem=mysqli_fetch_assoc($buscaOrigem)){
                                                        ?>
                                                        <option value="<?php echo $resultOrigem['pkId'] ?>"><?php echo $resultOrigem['pkId'] . " - ".  $resultOrigem['descricao'] ?></option>
                                                        <?php } ?> 
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Tributação CSOSN</label>
                                                    <select name="txtTributacaoCSOSN" class="form-control">
                                                        <option value=""></option>
                                                         <?php
                                                            $queryCSOSN=mysqli_query($conecta, "SELECT * FROM tributacaocsosn ORDER BY pkId");
                                                            if(mysqli_num_rows($queryCSOSN)>0){
                                                                while($resultCSOSN=mysqli_fetch_assoc($queryCSOSN)){
                                                        ?>
                                                        <option value="<?php echo $resultCSOSN['pkId'] ?>"><?php echo $resultCSOSN['pkId'] . " - ". $resultCSOSN['descricao'] ?></option>
                                                        <?php } } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Tributação ICMS</label>
                                                    <select name="txtTributacaoICMS" class="form-control" placeholder="Selecione" required>
                                                        <option></option>
                                                        <option value=""></option>
                                                        <option value="00">00 - TRIBUTADA INTEGRALMENTE</option>
                                                        <option value="20">20 - COM REDUCAO DA BASE DE CALCULO</option>
                                                        <option value="40">40 - ISENTA</option>
                                                        <option value="41">41 - NAO TRIBUTADA</option>
                                                        <option value="60">60 - ICMS COBRADO ANTERIORMENTE POR S.T.</option>
                                                        <option value="90">90 - OUTROS</option>
                                                    </select>
                                                </div>
                                            </div>
                                             <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Aliquota ICMS</label>
                                                    <input type="text" name="txtAliquotaICMS" class="form-control text-right"  onblur="formataAliquotaICMS(this);">
                                                </div>
                                            </div>
                                            
                                            
                                             <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Tributação PIS</label>
                                                    <select name="txtTributacaoPIS" class="form-control" placeholder="Selecione" required>
                                                    <option value=""></option>
                                                     <?php
                                                        $queryTributacao=mysqli_query($conecta, "SELECT * FROM tributacao ORDER BY pkId");
                                                        if(mysqli_num_rows($queryTributacao)>0){
                                                            while($resultTributacao=mysqli_fetch_assoc($queryTributacao)){
                                                    ?>
                                                    <option value="<?php echo $resultTributacao['pkId'] ?>"><?php echo $resultTributacao['pkId'] . " - ". $resultTributacao['descricao'] ?></option>
                                                    <?php } } ?>
                                                </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Tributação COFINS</label>
                                                    <select name="txtTributacaoCOFINS" class="form-control" placeholder="Selecione" required>
                                                        <option value=""></option>
                                                         <?php
                                                            $queryTributacao=mysqli_query($conecta, "SELECT * FROM tributacao ORDER BY pkId");
                                                            if(mysqli_num_rows($queryTributacao)>0){
                                                                while($resultTributacao=mysqli_fetch_assoc($queryTributacao)){
                                                        ?>
                                                        <option value="<?php echo $resultTributacao['pkId'] ?>"><?php echo $resultTributacao['pkId'] . " - ". $resultTributacao['descricao'] ?></option>
                                                        <?php } } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        
                                        <div class="row pt-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">NCM do Produto</label>
                                                    <select name="txtNCM" class="form-control select2" placeholder="Selecione" required>
                                                        <option value=""></option>
                                                         <?php
                                                            $queryNCM=mysqli_query($conecta, "SELECT * FROM ncm ORDER BY pkId ");
                                                            if(mysqli_num_rows($queryNCM)>0){
                                                                while($resultNCM=mysqli_fetch_assoc($queryNCM)){
                                                        ?>
                                                        <option value="<?php echo $resultNCM['pkId'] ?>"><?php echo $resultNCM['pkId'] . " - ". $resultNCM['descricao'] ?></option>
                                                        <?php } } ?>
                                                    </select>
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
</body> 

</html>