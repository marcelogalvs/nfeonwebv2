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
    
    
    $query = mysqli_query($conecta, "UPDATE clientes SET
    tipoCliente = '" . (trim(strtoupper($_POST["txtTipoCliente"]))) . "' ,
    cpf = '" . (trim(strtoupper($_POST["txtCPF"]))) . "' ,
    cnpj = '" . (trim(strtoupper($_POST["txtCNPJ"]))) . "' ,
    razaoSocial = '" . (trim(strtoupper($_POST["txtRazaoSocial"]))) . "' ,
    nomeFantasia = '" . (trim(strtoupper($_POST["txtNomeFantasia"]))) . "' ,
    endereco = '" . (trim(strtoupper($_POST["txtEndereco"]))) . "',
    numero = '" . (trim(strtoupper($_POST["txtNumero"]))) . "',
    bairro = '" . (trim(strtoupper($_POST['txtBairro']))) . "' ,
    cidade = '" . (trim(strtoupper($_POST["txtCidade"]))) . "' ,
    uf = '" . (trim(strtoupper($_POST["txtEstado"]))) . "' ,
    cep = '" . (trim($_POST["txtCep"])) . "' ,
    ibge = '" . (trim($_POST["txtCodigoIBGE"])) . "' ,
    telefone = '" . (trim($_POST["txtTelefone"])) . "' ,
    celular = '" . (trim($_POST["txtCelular"])) . "' ,
    email = '" . (trim($_POST["txtEmail"])) . "' ,
    inscricaoEstadual = '" . (trim(strtoupper($_POST["txtIE"]))) . "',
    inscricaoMunicipal = '" . (trim(strtoupper($_POST["txtIM"]))) . "',
    observacoes = '" . (trim(strtoupper($_POST["txtOBS"]))) . "'
    WHERE pkId = " . base64_decode($_GET['ref']));

    if(query){
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
                        <h4 class="page-title">Clientes</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="../">Home</a></li>
                                    <li class="breadcrumb-item"><a href=".">Clientes</a></li>
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
                                <h6 class="mb-0 text-white">Alteração de Cadastro</h6>
                            </div>
                            
                             <?php

                                $query = mysqli_query($conecta, "SELECT * FROM clientes WHERE pkId=".base64_decode($_GET['ref'])) or die(mysql_error());
                                $result=mysqli_fetch_assoc($query);
                            ?>
                            
                            <form method="post" name="frmCadastro">
                                <div class="form-body">
                                    <div class="card-body">
                                        <div class="row pt-3">
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label class="control-label">Id Cliente</label>
                                                    <input type="text" name="txtCodigo" class="form-control" disabled value="<?php echo $result['pkId']?>">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Tipo Cliente</label>
                                                    <select name="txtTipoCliente" class="form-control" autofocus>
                                                        <option value="<?php echo $result['tipoCliente']?>">
                                                            <?php
                                                                if($result['tipoCliente']=='FISICA') {
                                                                    echo "PESSOA FÍSICA";
                                                                } else {
                                                                    echo "PESSOA JURÍDICA";
                                                                }
                                                            ?>

                                                        </option>
                                                        <option></option>
                                                        <option value="FISICA">PESSOA FÍSICA</option>
                                                        <option value="JURIDICA">PESSOA JURÍDICA</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">CPF</label>
                                                    <input type="text" name="txtCPF" class="form-control"  value="<?php echo $result['cpf']?>">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">CNPJ</label>
                                                    <input type="text" name="txtCNPJ" class="form-control"  value="<?php echo $result['cnpj']?>">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row pt-3">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Nome Cliente</label>
                                                    <input type="text" name="txtRazaoSocial" class="form-control" value="<?php echo $result['razaoSocial']?>">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row pt-3">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label class="control-label">Endereço</label>
                                                    <input type="text" name="txtEndereco" class="form-control" value="<?php echo $result['endereco']?>">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label class="control-label">Numero</label>
                                                    <input type="text" name="txtNumero" class="form-control"  value="<?php echo $result['numero']?>">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Bairro</label>
                                                    <input type="text" name="txtBairro" class="form-control" value="<?php echo $result['bairro']?>" >
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Cidade</label>
                                                    <input type="text" name="txtCidade" class="form-control" value="<?php echo $result['cidade']?>" >
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label class="control-label">Cep</label>
                                                    <input type="text" name="txtCep" class="form-control" value="<?php echo $result['cep']?>" >
                                                </div>
                                            </div>
                                           
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label class="control-label">Estado</label>
                                                    <select name="txtEstado" class="form-control">
                                                        <option value="<?php echo $result['uf'] ?>"><?php echo $result['uf'] ?></option>
                                                        <option value=""></option>
                                                        <option value="SP" selected>SP</option>
                                                        <option value="RJ">RJ</option>
                                                        <option value="MG">MG</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    
                                        <div class="row pt-3">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Codigo IBGE</label>
                                                    <input type="text" name="txtCodigoIBGE" class="form-control" value="<?php echo (!empty($result['ibge']) ? $result['ibge'] : "3554102" ); ?>" >
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Telefone</label>
                                                    <input type="text" name="txtTelefone" class="form-control" value="<?php echo $result['telefone']?>" >
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Celular</label>
                                                    <input type="text" name="txtCelular" class="form-control" value="<?php echo $result['celular']?>" >
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Email</label>
                                                    <input type="text" name="txtEmail" class="form-control" value="<?php echo $result['email']?>">
                                                </div>
                                            </div>
                                            
                                             <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Insc Est</label>
                                                    <input type="text" name="txtIE" class="form-control" value="<?php echo $result['inscricaoEstadual']?>">
                                                </div>
                                            </div>
                                            
                                             <div class="col-md-1">
                                                <div class="form-group">
                                                    <label class="control-label">Insc Mun</label>
                                                    <input type="text" name="txtIM" class="form-control" value="<?php echo $result['inscricaoMunicipal']?>">
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