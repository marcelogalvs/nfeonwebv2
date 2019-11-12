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
    
     
    $query = mysqli_query($conecta, "UPDATE produtos SET 
    codigoBarras    = '" . strtoupper(trim($_POST["txtCodigoBarras"])) . "',
    nome            = '" . strtoupper(trim($_POST["txtNome"])) . "', 
    marca           = '" . strtoupper(trim($_POST["txtMarca"])) . "', 
    estoqueAtual    = '" . strtoupper(trim($_POST["txtEstoqueAtual"])) . "',
    estoqueMinimo   = '" . strtoupper(trim($_POST["txtEstoqueMinimo"])) . "',
    precoCusto      = '" . strtoupper(trim($_POST["txtPrecoCusto"])) . "',
    precoVenda      = '" . strtoupper(trim($_POST["txtPrecoVenda"])) . "',
    origem          = '" . strtoupper(trim($_POST["txtOrigem"])) . "',
    unidadeComercial= '" . strtoupper(trim($_POST["txtUnidadeComercial"])) . "',
    unidadeTributaria = '" . strtoupper(trim($_POST["txtUnidadeTributaria"])) . "',
    tributacaoCSOSN = '" . strtoupper(trim($_POST["txtTributacaoCSOSN"])) . "',
    tributacaoICMS  = '" . strtoupper(trim($_POST["txtTributacaoICMS"])) . "',
    aliquotaICMS    = '" . strtoupper(trim($_POST["txtAliquotaICMS"])) . "', 
    tributacaoPIS   = '" . strtoupper(trim($_POST["txtTributacaoPIS"])) . "', 
    tributacaoCOFINS= '" . strtoupper(trim($_POST["txtTributacaoCOFINS"])) . "', 
    fkIdNCM         = '" . strtoupper(trim($_POST["txtNCM"])) . "'
    WHERE pkId = '" . base64_decode($_GET['ref']) . "'");

    if($query){
        $msg = base64_encode('Cadastro alterado com sucesso!');
        $type = base64_encode('info');

    } else {

        $msg = base64_encode('Erro ao efetuar alteração! Por favor, tente novamente mais tarde.');
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
                        <h4 class="page-title">Produtos</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="../">Home</a></li>
                                    <li class="breadcrumb-item"><a href=".">Produtos</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Alteração</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <!-- Conteudo da Pagina  -->
            <div class="container-fluid">
                
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header bg-info">
                                <h6 class="mb-0 text-white">Alteração de Cadastro</h6>
                            </div>
                            
                            <?php
                            
                                                        
                            $query=mysqli_query($conecta, "SELECT A.*, 
                            B.pkId AS fkIdOrigem, B.descricao AS descricaoOrigem,
                            C.pkId as fkTribuicaoCSOSN, C.descricao AS descricaoTributacaoCSOSN,
                            D.pkId as fkIdTributacaoPIS, D.descricao AS tributacaoPIS,
                            E.pkId as fkIdNCM, E.descricao AS descricaoNCM
                            FROM produtos A, origem B, tributacaocsosn C, tributacao D, ncm E
                            WHERE 
                            E.pkId = A.fkIdNCM AND
                            D.pkId = A.tributacaoPIS AND
                            C.pkId = A.tributacaoCSOSN AND
                            B.pkId = A.origem AND A.pkId='".base64_decode($_GET['ref'])."'");
                            
//                                $query=mysqli_query($conecta, "SELECT A.*, 
//                                B.nome AS nomeGrupo, B.pkId as pkIdGrupo, 
//                                C.pkId AS pkIdTributacao, C.descricao AS descricaoTributacao, C.descricao AS descricaoTributacaoCSOSN, 
//                                D.pkId AS pkIdTributacaoCSOSN, 
//                                E.pkId as pkIdOrigem, E.descricao as descricaoOrigem , 
//                                F.pkId as pkIdNCM, F.descricao AS descricaoNCM
//                                FROM produtos A, grupos B , tributacao C, tributacaocsosn D, origem E, ncm F
//                                WHERE 
//                                F.pkId = A.fkIdNCM AND
//                                E.pkId = A.origem AND
//                                D.pkId = A.tributacaoCSOSN AND
//                                C.pkId = A.tributacaoPIS AND
//                                B.pkId = A.fkIdGrupo AND 
//                                A.pkId = '" . base64_decode($_GET['ref']) . "'");
                            
                                $result = mysqli_fetch_assoc($query);
                            ?>
                            
                            <form method="post">
                                <div class="form-body">
                                    <div class="card-body">
                                        <div class="row pt-3">
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label class="control-label">Cod Prod</label>
                                                    <input type="text" name="txtCodigo" class="form-control" value="<?php echo $result['pkId'] ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Código Barras</label>
                                                    <input type="text" name="txtCodigoBarras" class="form-control" value="<?php echo $result['codigoBarras'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label class="control-label">Descricão Produto</label>
                                                    <input type="text" name="txtNome" class="form-control" value="<?php echo $result['nome'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row pt-3">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Marca</label>
                                                    <input type="text" name="txtMarca" class="form-control" value="<?php echo $result['marca'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label class="control-label">Est Atual</label>
                                                    <input type="text" name="txtEstoqueAtual" class="form-control text-right" value="<?php echo $result['estoqueAtual'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label class="control-label">Est Min</label>
                                                    <input type="text" name="txtEstoqueMinimo" class="form-control text-right" value="<?php echo $result['estoqueMinimo'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">R$ Custo</label>
                                                    <input type="text" name="txtPrecoCusto" class="form-control text-right" value="<?php echo $result['precoCusto'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">R$ Venda</label>
                                                    <input type="text" name="txtPrecoVenda" class="form-control text-right" value="<?php echo $result['precoVenda'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Un Coml</label>
                                                    <select name="txtUnidadeComercial" class="form-control">
                                                        <option value=""></option>
                                                        <option value="UN" <?php echo $result['unidadeComercial']=='UN' ? "selected" : ""?>>UN - UNIDADE</option>
                                                        <option value="UN" <?php echo $result['unidadeComercial']=='CM' ? "selected" : ""?>>CM - CENTIMETROS</option>
                                                        <option value="UN" <?php echo $result['unidadeComercial']=='BD' ? "selected" : ""?>>BD - BALDE</option>
                                                        <option value="UN" <?php echo $result['unidadeComercial']=='CX' ? "selected" : ""?>>CX - CAIXA</option>
                                                        <option value="UN" <?php echo $result['unidadeComercial']=='JG' ? "selected" : ""?>>JG - JOGO</option>
                                                        <option value="UN" <?php echo $result['unidadeComercial']=='KG' ? "selected" : ""?>>KG - KILOGRAMA</option>
                                                        <option value="UN" <?php echo $result['unidadeComercial']=='MT' ? "selected" : ""?>>MT - METRO</option>
                                                        <option value="UN" <?php echo $result['unidadeComercial']=='M2' ? "selected" : ""?>>MT - METRO QUADRADO</option>
                                                        <option value="UN" <?php echo $result['unidadeComercial']=='M3' ? "selected" : ""?>>MT - METRO CUBICO</option>
                                                        <option value="UN" <?php echo $result['unidadeComercial']=='PC' ? "selected" : ""?>>PC - PECA</option>
                                                        <option value="UN" <?php echo $result['unidadeComercial']=='PT' ? "selected" : ""?>>PT - PACOTE</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Un Trib</label>
                                                    <select name="txtUnidadeTributaria" class="form-control">
                                                        <option value=""></option>
                                                        <option value="UN" <?php echo $result['unidadeComercial']=='UN' ? "selected" : ""?>>UN - UNIDADE</option>
                                                        <option value="UN" <?php echo $result['unidadeComercial']=='CM' ? "selected" : ""?>>CM - CENTIMETROS</option>
                                                        <option value="UN" <?php echo $result['unidadeComercial']=='BD' ? "selected" : ""?>>BD - BALDE</option>
                                                        <option value="UN" <?php echo $result['unidadeComercial']=='CX' ? "selected" : ""?>>CX - CAIXA</option>
                                                        <option value="UN" <?php echo $result['unidadeComercial']=='JG' ? "selected" : ""?>>JG - JOGO</option>
                                                        <option value="UN" <?php echo $result['unidadeComercial']=='KG' ? "selected" : ""?>>KG - KILOGRAMA</option>
                                                        <option value="UN" <?php echo $result['unidadeComercial']=='MT' ? "selected" : ""?>>MT - METRO</option>
                                                        <option value="UN" <?php echo $result['unidadeComercial']=='M2' ? "selected" : ""?>>MT - METRO QUADRADO</option>
                                                        <option value="UN" <?php echo $result['unidadeComercial']=='M3' ? "selected" : ""?>>MT - METRO CUBICO</option>
                                                        <option value="UN" <?php echo $result['unidadeComercial']=='PC' ? "selected" : ""?>>PC - PECA</option>
                                                        <option value="UN" <?php echo $result['unidadeComercial']=='PT' ? "selected" : ""?>>PT - PACOTE</option>  
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    
                                        <div class="row pt-3">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Origem</label>
                                                    <select name="txtOrigem" class="form-control">
                                                        <option value="<?php echo $result['fkIdOrigem'] ?>"><?php echo $result['fkIdOrigem'] . " - " . $result['descricaoOrigem'] ?></option>
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
                                                        <option value="<?php echo $result['fkTribuicaoCSOSN'] ?>"><?php echo $result['fkTribuicaoCSOSN'] . " - " . $result['descricaoTributacaoCSOSN'] ?></option>
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
                                                       <?php
                                                            if($result['tributacaoICMS']='00') { $tributacaoICMS='00 - TRIBUTADA INTEGRALMENTE'; }
                                                            if($result['tributacaoICMS']='20') { $tributacaoICMS='20 - COM REDUCAO DA BASE DE CALCULO'; }
                                                            if($result['tributacaoICMS']='40') { $tributacaoICMS='40 - ISENTA'; }
                                                            if($result['tributacaoICMS']='41') { $tributacaoICMS='41 - NAO TRIBUTADA'; }
                                                            if($result['tributacaoICMS']='60') { $tributacaoICMS='60 - ICMS COBRADO ANTERIORMENTE POR S.T.'; }
                                                            if($result['tributacaoICMS']='90') { $tributacaoICMS='90 - OUTROS'; }
                                                        ?>
                                                        <option value="<?php echo $result['tributacaoICMS'] ?>"><?php echo $tributacaoICMS ?></option>
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
                                                    <input type="text" name="txtAliquotaICMS" class="form-control text-right" value="<?php echo $result['aliquotaICMS'] ?>">
                                                </div>
                                            </div>
                                            
                                            
                                             <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Tributação PIS</label>
                                                    <select name="txtTributacaoPIS" class="form-control" placeholder="Selecione" required>
                                                    <option value="<?php echo $result['fkIdTributacaoPIS'] ?>"><?php echo $result['fkIdTributacaoPIS'] . " - " . $result['tributacaoPIS'] ?></option>
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
                                                        <option value="<?php echo $result['fkIdTributacaoPIS'] ?>"><?php echo $result['fkIdTributacaoPIS'] . " - " . $result['tributacaoPIS'] ?></option>
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
                                        
                                        <div class="row pt-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">NCM do Produto</label>
                                                    <select name="txtNCM" class="form-control select2" placeholder="Selecione" required>
                                                        <option value="<?php echo $result['fkIdNCM'] ?>"><?php echo $result['fkIdNCM'] . " - " . $result['descricaoNCM'] ?></option>
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