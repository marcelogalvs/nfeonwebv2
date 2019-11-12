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


if(isset($_POST['btnExcluir'])){
    $query=mysqli_query($conecta, "DELETE FROM tributacaocsosn WHERE pkId = " . $_POST['txtCodigo']) or die(mysql_error());
    if($query){
        $msg = base64_encode('Exclusão efetuada com sucesso!');
        $type = base64_encode('info');
    } else {
        $msg = base64_encode('Erro ao efetuar exclusão! Por favor, tente novamente mais tarde.');
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
                        <h4 class="page-title">Tributação CSOSN</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="../">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Tributação CSOSN</li>
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
                                <h4 class="card-title">Arquivo de Trbutação CSOSN</h4>
                                <h6 class="card-subtitle">Cadastre novas <code>Tributações CSOSN </code> ou altere as informações de uma tributação CSOSN <code>cadastrada</code>.</h6>
                                <div class="col-md-6 col-lg-2" style="float:left; margin-left:-10px;">
                                    <form name="frmBusca" method="post" onsubmit="this.form.submit()">
                                        <input type="text" class="form-control" name="txtBusca" placeholder="Digite para filtrar" />
                                    </form>
                                </div>  
                                <a href="insert.php">
                                    <button class="btn btn-primary" style="float:right;"><i class="fas fa-plus-circle"></i> Cadastrar Novo</button>
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col" width="6%"></th>
                                            <th scope="col" width="6%">pkId</th>
                                            <th scope="col">Tributação CSOSN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if(isset($_POST['txtBusca'])){
                                                $busca=mysqli_query($conecta, "SELECT * FROM tributacaocsosn WHERE descricao LIKE '%". $_POST['txtBusca'] ."%' ORDER BY pkId");
                                            } else {
                                                $busca=mysqli_query($conecta, "SELECT * FROM tributacaocsosn ORDER BY pkId");
                                            }
                                            if(mysqli_num_rows($busca)>=1){
                                                while($result=mysqli_fetch_assoc($busca)){
                                        ?>
                                        <tr>
                                            <td>
                                                <a href="view.php?ref=<?php echo base64_encode($result['pkId']) ?>"><button class="btn btn-primary btn-sm" type="submit" title="Editar" style="width:30px;"><i class="fas fa-edit"></i></button></a>
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#exampleModal" data-whatever="<?php echo $result['pkId'] ?>" data-whatevernome="<?php echo $result['descricao'] ?>" title="Excluir"><i class="fas fa-eraser"></i></button>
                                            </td>
                                            <td><?php echo $result['pkId'] ?></td>
                                            <td><?php echo $result['descricao'] ?></td>
                                        </tr>
                                        <?php }} else { ?>
                                        <tr>
                                            <td colspan="3">
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
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="exampleModalLabel">Confirma exclusão da Tributação?</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post">
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Código:</label>
                                    <input type="text" class="form-control" id="recipient-id" disabled />
                                    <input type="hidden" name="txtCodigo" class="form-control" id="recipient-idDel" />
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="col-form-label">Descrição Tributação CSOSN:</label>
                                    <input type="text" name="txtNome" class="form-control" id="recipient-name" disabled />
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
                $('#exampleModal').on('show.bs.modal', function (event) {
                  var button = $(event.relatedTarget) // Button that triggered the modal
                  var recipient = button.data('whatever') // Extract info from data-* attributes
                  var recipientNome = button.data('whatevernome') // Extract info from data-* attributes
                  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                  var modal = $(this)
                  modal.find('#recipient-id').val(recipient)
                  modal.find('#recipient-idDel').val(recipient)
                  modal.find('#recipient-name').val(recipientNome)
                })
            </script>

    
            
            
            <!-- Fim Modal Excluir  -->
            
            
            <!-- footer -->
            <?php
                include('../footer.php');
            ?>
            <!-- End footer -->
        </div>
    </div>
    
    <!-- DESABILITADO PARA FUNCIONAMENTO DO MODAL
         <script src="../resources/assets/libs/jquery/dist/jquery.min.js"></script>
    -->
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
    
</body>

</html>