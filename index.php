<?php

$nivel=0;
$pagina='inicial';

session_start();

date_default_timezone_set('America/Sao_Paulo');
$_SESSION['dataHoje']=date("Y-m-d");

if(!isset($_SESSION["username"])){
    include('verifyConnection.php'); 
}

include('connectDb.php');
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
    <link href="resources/assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="resources/assets/extra-libs/c3/c3.min.css" rel="stylesheet">
    <link href="resources/dist/css/style.min.css" rel="stylesheet">
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
        <?php include('header.php'); ?>
        <!-- End Topbar header -->

        <!-- Menu  -->
        <?php include('sideMenu.php'); ?>
        <!-- End Menu -->

        <!-- Indicador de Pagina  -->
        <div class="page-wrapper">
                        
            <!-- Conteudo da Pagina  -->
            <div class="container-fluid">
                 <div class="row">
                    <div class="col-sm-12 col-lg-12">
                        <div class="card">
                            <div class="card-body border-bottom">
                                <h4 class="card-title">Resumo</h4>
                                <h5 class="card-subtitle">Totais relevantes</h5>
                            </div>
                            <div class="card-body">
                                <div class="row m-t-10">
                                    <!-- col -->
                                    <div class="col-md-6 col-sm-12 col-lg-3">
                                        <div class="d-flex align-items-center">
                                            <div class="m-r-10"><span class="text-orange display-5"><i class="mdi mdi-account"></i></span></div>
                                            <div><span class="text-muted">Clientes Ativos</span>
                                                <?php 
                                                    $buscaClientes=mysqli_query($conecta, "SELECT * FROM clientes ORDER BY pkId DESC LIMIT 6");
                                                ?>
                                                <h3 class="font-medium m-b-0"><?php echo str_pad(mysqli_num_rows($buscaClientes), 6, 0, STR_PAD_LEFT) ?></h3></div>
                                        </div>
                                    </div>
                                    <!-- col -->
                                    <!-- col -->
                                    <div class="col-md-6 col-sm-12 col-lg-3">
                                        <div class="d-flex align-items-center">
                                            <div class="m-r-10"><span class="text-primary display-5"><i class="mdi mdi-basket"></i></span></div>
                                            <?php
                                                $buscaVendas=mysqli_query($conecta, "SELECT SUM(total) AS TotalVendas FROM vendas");
                                                $resultVendas=mysqli_fetch_assoc($buscaVendas);
                                            ?>
                                            <div><span class="text-muted">Total Vendas</span>
                                                <h3 class="font-medium m-b-0">$ <?php echo str_replace(",", ".", number_format($resultVendas['TotalVendas'], 2)) ?></h3></div>
                                        </div>
                                    </div>
                                    <!-- col -->
                                    <!-- col -->
                                    <div class="col-md-6 col-sm-12 col-lg-3">
                                        <div class="d-flex align-items-center">
                                            <div class="m-r-10"><span class="display-5"><i class="mdi mdi-cash-usd"></i></span></div>
                                            <div><span class="text-muted">Contas a Receber</span>
                                                <h3 class="font-medium m-b-0">$ 23,568.90</h3></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-sm-12 col-lg-3">
                                        <div class="d-flex align-items-center">
                                            <div class="m-r-10"><span class="display-5"><i class="mdi mdi-cash-usd"></i></span></div>
                                            <div><span class="text-muted">Contas a Pagar</span>
                                                <h3 class="font-medium m-b-0">$ 23,568.90</h3></div>
                                        </div>
                                    </div>
                                    <!-- col -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            
                <!-- Colunas -->
                
                <!-- COLUNAS DE TOTAIS -->
                <!--<div class="row">
                    
                    
                    <div class="col-sm-12 col-lg-4">
                        <div class="card card-hover">
                            <div class="card-body">
                                <h4 class="card-title">Clientes Ativos</h4>
                                <div class="d-flex">
                                    <?php 
                                        $buscaClientes=mysqli_query($conecta, "SELECT * FROM clientes ORDER BY pkId DESC LIMIT 6");
                                    ?>
                                    <h2><?php echo str_pad(mysqli_num_rows($buscaClientes), 5, 0, STR_PAD_LEFT) ?> <small><i class="ti-arrow-up text-success"></i></small></h2>
                                </div>
                                <div class="m-t-20 m-b-30 text-center">
                                    <div id="active-users"></div>
                                </div>
                                <h5>Últimos Clientes</h5>
                                <ul class="list-group list-group-flush">
                                    <?php while($resultClientes=mysqli_fetch_assoc($buscaClientes)){ ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center"><?php echo $resultClientes['razaoSocial'] ?> <span class="badge badge-light badge-pill">1</span></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-lg-4">
                        <div class="card card-hover">
                            <div class="card-body">
                                <h4 class="card-title">Device Visit</h4>
                                <div id="visitor" style="height:267px; width:100%;" class="m-t-20"></div>
                                <div class="row m-t-30 m-b-15">
                                    <div class="col-4 birder-right text-left">
                                        <h4 class="m-b-0">60%<small><i class="ti-arrow-up text-success"></i></small></h4>Desktop
                                    </div>
                                    <div class="col-4 birder-right text-center">
                                        <h4 class="m-b-0">28%<small><i class="ti-arrow-down text-danger"></i></small></h4>Mobile
                                    </div>
                                    <div class="col-4 text-right">
                                        <h4 class="m-b-0">12%<small><i class="ti-arrow-up text-success"></i></small></h4>Tablet
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-lg-4">
                        <div class="card card-hover">
                            <div class="card-body">
                                <h4 class="card-title">Visitors By Countries</h4>
                                <div id="visitfromworld" style="width:100%; height:232px"></div>
                                <div class="row m-b-15">
                                    <div class="col-3">India</div>
                                    <div class="col-7">
                                        <div class="progress m-t-5">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 48%" aria-valuenow="48" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-2">28%</div>
                                </div>
                                <div class="row m-b-15">
                                    <div class="col-3">UK</div>
                                    <div class="col-7">
                                        <div class="progress m-t-5">
                                            <div class="progress-bar bg-cyan" role="progressbar" style="width: 33%" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-2">21%</div>
                                </div>
                                <div class="row m-b-15">
                                    <div class="col-3">USA</div>
                                    <div class="col-7">
                                        <div class="progress m-t-5">
                                            <div class="progress-bar bg-purple" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-2">18%</div>
                                </div>
                                <div class="row">
                                    <div class="col-3">China</div>
                                    <div class="col-7">
                                        <div class="progress m-t-5">
                                            <div class="progress-bar bg-orange" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-2">12%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->

                
                <!-- TABELA DE VENDAS -->
                <div class="row">
                    
                    
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-md-flex align-items-center">
                                    <div>
                                        <h4 class="card-title">Resumo de Vendas</h4>
                                        <h5 class="card-subtitle">Orçamentos / Vendas do mês</h5>
                                    </div>
                                    <div class="ml-auto d-flex no-block align-items-center">
                                        <div class="dl">
                                            <select class="custom-select">
                                                <option value="0" selected>Monthly</option>
                                                <option value="1">Daily</option>
                                                <option value="2">Weekly</option>
                                                <option value="3">Yearly</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
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
                                                $query=mysqli_query($conecta, "SELECT A.*, B.razaoSocial, B.telefone, B.celular, B.email FROM vendas A, clientes B WHERE  B.pkId = A.fkIdCliente AND A.status = 'ENVIADO A SEFAZ' ORDER BY A.pkId DESC limit 20") or die(mysql_error());                                                
                                                if(mysqli_num_rows($query)>=1){
                                                    while($result=mysqli_fetch_assoc($query)){
                                            ?>
                                            <tr style="font-size:12px">
                                                
                                                <td><?php echo date('d/m/Y', strtotime($result['data'])) ?></td>
                                                <td><?php echo $result['dataSaida']<>'0000-00-00' ? date('d/m/Y', strtotime($result['dataSaida'])) : '' ?></td>
                                                <td><?php echo $result['pkId'] ?></td>
                                                <td><?php echo $result['nf'] ?></td>
                                                <td><?php echo substr($result['razaoSocial'],0 ,33) ?></td>
                                                <td><?php echo $result['telefone']; if(!empty($result['celular'])) { echo "/". $result['celular']; } ?></td>
                                                <td style="font-weight: bold"><?php echo number_format($result['total'], 2, ',', '.') ?></td>
                                                <td><?php echo bin2hex($result['chave']) ?></td>
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
            </div>
            <!-- End Conteudo PAgina  -->
            
            <!-- footer -->
            <?php
                include('footer.php');
            ?>
            <!-- End footer -->
        </div>
    </div>
    
    <script src="resources/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="resources/assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="resources/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="resources/dist/js/app.min.js"></script>
    <script src="resources/dist/js/app.init.horizontal-fullwidth.js"></script>
    <script src="resources/dist/js/app-style-switcher.horizontal.js"></script>
    <script src="resources/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="resources/assets/extra-libs/sparkline/sparkline.js"></script>
    <script src="resources/dist/js/waves.js"></script>
    <script src="resources/dist/js/sidebarmenu.js"></script>
    <script src="resources/dist/js/custom.min.js"></script>
    <script src="resources/assets/libs/chartist/dist/chartist.min.js"></script>
    <script src="resources/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="resources/assets/extra-libs/c3/d3.min.js"></script>
    <script src="resources/assets/extra-libs/c3/c3.min.js"></script>
    <script src="resources/assets/libs/chart.js/dist/Chart.min.js"></script>
    <script src="resources/dist/js/pages/dashboards/dashboard1.js"></script>
</body>

</html>