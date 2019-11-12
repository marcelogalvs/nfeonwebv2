<?php

$nivel=1;
$pagina='orcamento';

session_start();

if(!isset($_SESSION["username"])){
    include('../verifyConnection.php');    
}

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

include('../connectDb.php');

date_default_timezone_set('America/Sao_Paulo');
$dataAtual = date('d/m/Y', strtotime(date('Y-m-d H:i')));


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>NFE on WEB :: Vendas</title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="../bootstrap/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../bootstrap/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../bootstrap/bower_components/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="../bootstrap/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../bootstrap/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="../bootstrap/bower_components/morris.js/morris.css">
    <link rel="stylesheet" href="../bootstrap/bower_components/jvectormap/jquery-jvectormap.css">
    <link rel="stylesheet" href="../bootstrap/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="../bootstrap/bower_components/bootstrap-daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="../bootstrap/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> </head>

    <style>
        @page{size: auto;}
    </style>
    
<body class="hold-transition skin-blue sidebar-mini" onload="self.print();self.close();">
   
    <div class="wrapper">
        
        <div class="content-wrapper">
          
            <section class="content col-md-12">

              

              <div class="box box-primary">
                
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                       
                       <!-- DADOS DO EMITENTE -->
                       <?php
                            $buscaEmitente = mysqli_query($conecta, "SELECT * FROM emitente");
                            $resultEmitente = mysqli_fetch_assoc($buscaEmitente);
                       ?>
                       <tr>
                           <td width="200px">
                               <img style="margin: auto" src="../emitente/<?php echo $resultEmitente['logomarca']; ?>" width="150px" />                               
                           </td>
                           <td colspan="5">
                               <h4 style="text-align:center"><?php echo $resultEmitente['razaoSocial'] ?></h4>
                               <p style="text-align:center; font-size:12px; margin-top:-10px;"> <?php echo $resultEmitente['logradouro'] ?> <?php echo " " . $resultEmitente['endereco'] . ", " . $resultEmitente['numero'] . " - " . $resultEmitente['bairro'] . " - " . $resultEmitente['cidade'] . "/" . $resultEmitente['uf'] ?></p>
                               <p style="text-align:center; font-size:12px; margin-top:-10px;">CNPJ: <?php echo $resultEmitente['cnpj'] . " - I.E. " . $resultEmitente['inscricaoEstadual']?></p>
                           </td> 
                       </tr>
                       
                       <!-- DADOS DO CLIENTE -->
                       <?php
                           
                            $buscaCliente = mysqli_query($conecta, "SELECT A.*, B.obs FROM clientes A, vendas B WHERE B.fkIdCliente = A.pkId AND B.pkId = ".base64_decode($_GET['ref']));
                            $resultCliente = mysqli_fetch_assoc($buscaCliente);
                       ?>
                       <tr>
                           <td colspan="6">
                               <p style="text-align:center; font-size: 15px;">DADOS DO CLIENTE</p>
                               <p style="text-align:left">NOME: <?php echo $resultCliente['razaoSocial'] ?></p>
                               <p style="text-align:left; font-size:12px; margin-top:-5px;">ENDERECO: <?php echo " " . $resultCliente['endereco'] . ", " . $resultCliente['numero'] . " - " . $resultCliente['bairro'] . " - " . $resultCliente['cidade'] . "/" . $resultCliente['uf'] ?></p>
                               <p style="text-align:left; font-size:12px; margin-top:-5px;">TELEFONES: <?php echo $resultCliente['telefone'] . " | " . $resultCliente['celular']?></p>
                               <p style="text-align:left; font-size:12px; margin-top:-5px;">EMAIL: <?php echo $resultCliente['email']?></p>
                               <p style="text-align:left; font-size:12px; margin-top:-5px;">OBSERVAÇÕES DO ORÇAMENTO: <?php echo $resultCliente['obs']?></p>
                           </td> 
                       </tr>
                       
                       <tr>
                            <td colspan="6">
                                <h3 style="text-align:center;margin-top: 0px;">PEDIDO DE VENDA</h3>
                                <p style="font-size:11px; text-align: center">Válido por 15 dias</p>
                            </td>
                       </tr>
                       
                        <tr style="font-size:12px">
                            <th align="left" width="6%">CODIGO</th>
                            <th align="left">PRODUTO</th>
                            <th width="100" style="text-align:right; padding-right:25px">R$ UNIT</th>
                            <th style="text-align:right; padding-right:25px">QDE</th>
                            <th style="text-align:right; padding-right:25px">TOTAL</th>
                        </tr>

                        <?php
                            $query=mysqli_query($conecta, "SELECT A.*, B.nome AS NomeProduto FROM vendas_itens A, produtos B WHERE B.pkId = A.fkIdProduto AND A.fkIdVenda=".base64_decode($_GET['ref'])) or die(mysql_error());
                            while($result=mysqli_fetch_assoc($query)){
                        ?>
                        <tr>
                            <td style="width: 150px; font-size: 12px"><?php echo $result['fkIdProduto'] ?></td>
                            <td style="width: 400px; font-size: 12px"><?php echo $result['NomeProduto'] ?></td>
                            <td style="text-align:right; padding-right:25px; font-size: 12px"><?php echo number_format($result['preco'], 2, ',', '.') ?></td>
                            <td style="text-align:right; padding-right:25px; font-size: 12px"><?php echo number_format($result['quantidade'], 2, ',', '.') ?></td>
                            <td style="text-align:right; padding-right:25px; font-weight:bolder; font-size: 12px"><?php echo number_format($result['preco']*$result['quantidade'], 2, ',', '.') ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                    
                    <table class="table table-hover" align="right" style="margin-right: 30px;">
                        <tr>
                            <?php
                                $query=mysqli_query($conecta, "SELECT * FROM vendas WHERE pkId=".base64_decode($_GET['ref'])) or die(mysql_error());
                                $result=mysqli_fetch_assoc($query);
                            ?>
                            <br><br>
                            <td>
                                <h4 style="text-align:left">Totais:</h4>
                                <p style="text-align:right; font-size:13px; margin-top:-5px;">Sub Total: R$ <?php echo number_format($result['subTotal'], 2) ?></p>
                                <p style="text-align:right; font-size:13px; margin-top:-5px;">Desconto: R$ <?php echo number_format($result['desconto'], 2) ?></p>
                                <p style="text-align:right; font-size:13px; margin-top:-5px;">Total: R$ <?php echo number_format($result['total'], 2) ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->

            </section>
            <!-- fim conteudo -->
            
        </div>
                
        <div class="control-sidebar-bg"></div>
    </div>
    <script src="../bootstrap/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="../bootstrap/bower_components/jquery-ui/jquery-ui.min.js"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script> 
    <script src="../bootstrap/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../bootstrap/bower_components/raphael/raphael.min.js"></script>
    <script src="../bootstrap/bower_components/morris.js/morris.min.js"></script>
    <script src="../bootstrap/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
    <script src="../bootstrap/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="../bootstrap/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="../bootstrap/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
    <script src="../bootstrap/bower_components/moment/min/moment.min.js"></script>
    <script src="../bootstrap/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="../bootstrap/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="../bootstrap/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <script src="../bootstrap/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="../bootstrap/bower_components/fastclick/lib/fastclick.js"></script>
    <script src="../bootstrap/dist/js/adminlte.min.js"></script>
    <script src="../bootstrap/dist/js/pages/dashboard.js"></script>
    <script src="../bootstrap/dist/js/demo.js"></script>
    
  
    
</body>

</html>