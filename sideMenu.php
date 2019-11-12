<?php
if($nivel==0){ $link=""; }
if($nivel==1){ $link="../"; }
?>

<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                
                <!-- MANUTENCAO -->
                <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">Vendas/Orçamentos</span></li>
                <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Vendas/Orçamentos </span></a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item"><a href="<?php echo $link ?>orcamentos" class="sidebar-link"><i class="mdi mdi-cart-plus"></i><span class="hide-menu"> Orçamentos </span></a></li>
                        <li class="sidebar-item"><a href="<?php echo $link ?>vendas-nfe" class="sidebar-link"><i class="mdi mdi-cash-multiple"></i><span class="hide-menu"> Vendas NFe </span></a></li>
                        <li class="sidebar-item"><a href="<?php echo $link ?>vendas-nfce" class="sidebar-link"><i class="mdi mdi-cash-multiple"></i><span class="hide-menu"> Vendas NFCe </span></a></li>
                        <li class="sidebar-item"><a href="<?php echo $link ?>devolucao" class="sidebar-link"><i class="mdi mdi-backspace"></i><span class="hide-menu"> Devolução </span></a></li>
                        <li class="sidebar-item"><a href="<?php echo $link ?>vendas-nfe-cancelar" class="sidebar-link"><i class="mdi mdi-delete-variant"></i><span class="hide-menu"> Cancelamento </span></a></li>
                    </ul>
                </li>
                
                <!-- RELATORIOS -->
                <li class="nav-small-cap"><i class="mdi mdi-printer"></i> <span class="hide-menu">Financeiro</span></li>
                <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-printer"></i><span class="hide-menu">Financeiro </span></a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item"><a href="<?php echo $link ?>contas-pagar" class="sidebar-link"><i class="mdi mdi-format-float-right"></i><span class="hide-menu"> Contas a Pagar </span></a></li>
                        <li class="sidebar-item"><a href="<?php echo $link ?>contas-receber" class="sidebar-link"><i class="mdi mdi-format-float-right"></i><span class="hide-menu"> Contas a Receber </span></a></li>
                        <li class="sidebar-item"><a href="<?php echo $link ?>caixa" class="sidebar-link"><i class="mdi mdi-format-float-right"></i><span class="hide-menu"> Caixa </span></a></li>
                    </ul>
                </li>
                
                <!-- CADASTROS -->
                <li class="nav-small-cap"><i class="mdi mdi-watermark"></i> <span class="hide-menu">Configurações</span></li>
                <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-watermark"></i><span class="hide-menu">Configurações </span></a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item"><a href="<?php echo $link ?>clientes" class="sidebar-link"><i class="mdi mdi-account-card-details"></i><span class="hide-menu"> Clientes </span></a></li>
                        <li class="sidebar-item"><a href="<?php echo $link ?>produtos" class="sidebar-link"><i class="mdi mdi-food-fork-drink"></i><span class="hide-menu"> Produtos </span></a></li>
                        <li class="sidebar-item"><a href="<?php echo $link ?>cfop" class="sidebar-link"><i class="mdi mdi-format-list-numbers"></i><span class="hide-menu"> CFOP </span></a></li>
                        <li class="sidebar-item"><a href="<?php echo $link ?>cnae" class="sidebar-link"><i class="mdi mdi-format-list-numbers"></i><span class="hide-menu"> CNAE </span></a></li>
                        <li class="sidebar-item"><a href="<?php echo $link ?>ncm" class="sidebar-link"><i class="mdi mdi-format-list-numbers"></i><span class="hide-menu"> NCM </span></a></li>
                        <li class="sidebar-item"><a href="<?php echo $link ?>origem" class="sidebar-link"><i class="mdi mdi-format-list-numbers"></i><span class="hide-menu"> Origem dos Produtos </span></a></li>
                        <li class="sidebar-item"><a href="<?php echo $link ?>csosn" class="sidebar-link"><i class="mdi mdi-format-list-numbers"></i><span class="hide-menu"> Tributação CSOSN </span></a></li>
                        <li class="sidebar-item"><a href="<?php echo $link ?>tributacao" class="sidebar-link"><i class="mdi mdi-format-list-numbers"></i><span class="hide-menu"> Tributação PIS/COFINS </span></a></li>
                        <li class="sidebar-item"><a href="<?php echo $link ?>emitente" class="sidebar-link"><i class="mdi mdi-account-card-details"></i><span class="hide-menu"> Emitente </span></a></li>
                        <li class="sidebar-item"><a href="<?php echo $link ?>usuarios/" class="sidebar-link"><i class="mdi mdi-account-multiple"></i><span class="hide-menu"> Usuários </span></a></li>
                    </ul>
                </li>
                
                
                <li class="nav-small-cap"><i class="mdi mdi-information"></i> <span class="hide-menu">Extras</span></li>
                <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-information"></i><span class="hide-menu">Extras</span></a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="mailto:marcelo_galvao@hotmail.com.br" aria-expanded="false"><i class="mdi mdi-phone"></i><span class="hide-menu">Suporte</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?php echo $link ?>manual-usuario/" aria-expanded="false"><i class="mdi mdi-cards-variant"></i><span class="hide-menu">Manual do Usuário</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="http://www.r2msolucoes.com.br" target="_blank" aria-expanded="false"><i class="mdi mdi-web"></i><span class="hide-menu">WebSite</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?php echo $link ?>logout.php" aria-expanded="false"><i class="mdi mdi-logout"></i><span class="hide-menu">Logout</span></a></li>
                    </ul>
                </li>
                
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>