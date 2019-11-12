<?php
if($nivel==0){ $link=""; }
if($nivel==1){ $link="../"; }
?>

<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <div class="navbar-header">
            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
            <!-- Logo -->
            <a class="navbar-brand" href="<?php echo $link ?>">
                <!-- Logo icon -->
                <b class="logo-icon">
                    <!-- Dark Logo icon -->
                    <img src="<?php echo $link ?>imagens/logo_nfeonweb-paginas.png" alt="" class="dark-logo" />
                    <!-- Light Logo icon -->
                    <img src="<?php echo $link ?>imagens/logo_nfeonweb-paginas.png" alt="" class="light-logo" />
                </b>
                <!--End Logo icon -->
                <!-- Logo text -->
                <span class="logo-text">
                     <!-- dark Logo text -->
                     <img src="<?php echo $link ?>imagens/logo_nfeonweb-paginas.png" alt="" class="dark-logo" />
                     <!-- Light Logo text -->    
                     <img src="<?php echo $link ?>imagens/logo_nfeonweb-paginas.png" class="light-logo" alt="" />
                </span>
            </a>
            <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
        </div>

        <div class="navbar-collapse collapse" id="navbarSupportedContent">
            <ul class="navbar-nav float-left mr-auto">
                <li class="nav-item d-none d-md-block"><a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)" data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-24"></i></a></li>
            </ul>
            <ul class="navbar-nav float-right">
                <!-- Messages -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="font-24 mdi mdi-comment-processing"></i>

                    </a>
                    <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown" aria-labelledby="2">
                        <span class="with-arrow"><span class="bg-danger"></span></span>
                        <ul class="list-style-none">
                            <li>
                                <div class="drop-title text-white bg-danger">
                                    <h4 class="m-b-0 m-t-5">5 Novas</h4>
                                    <span class="font-light">Mensagens</span>
                                </div>
                            </li>
                            <li>
                                <div class="message-center message-body">
                                    <!-- Message -->
                                    <a href="javascript:void(0)" class="message-item">
                                        <span class="user-img"> <img src="<?php echo $link ?>resources/assets/images/users/1.jpg" alt="user" class="rounded-circle"> <span class="profile-status online pull-right"></span> </span>
                                        <div class="mail-contnet">
                                            <h5 class="message-title">Jose Roberto</h5> <span class="mail-desc">Pode ver meu email?</span> <span class="time">9:30 AM</span> </div>
                                    </a>
                                </div>
                            </li>
                            <li>
                                <a class="nav-link text-center link text-dark" href="javascript:void(0);"> <b>Ver todas as mensagens</b> <i class="fa fa-angle-right"></i> </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- End Messages -->
                
                <!-- User profile -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?php echo $link ?>resources/assets/images/users/1.jpg" alt="user" class="rounded-circle" width="31"></a>
                    <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                        <span class="with-arrow"><span class="bg-primary"></span></span>
                        <div class="d-flex no-block align-items-center p-15 bg-primary text-white m-b-10">
                            <div class=""><img src="<?php echo $link ?>resources/assets/images/users/1.jpg" alt="user" class="img-circle" width="60"></div>
                            <div class="m-l-10">
                                <h4 class="m-b-0"><?php echo $_SESSION['nomeUsuario'] ?></h4>
                                <p class=" m-b-0"><?php echo $_SESSION['emailUsuario'] ?></p>
                            </div>
                        </div>
                        <a class="dropdown-item" href="<?php echo $link ?>usuarios/view.php?ref=<?php echo base64_encode($_SESSION['pkId']) ?>"><i class="ti-user m-r-5 m-l-5"></i> Minha Conta</a>
                        <a class="dropdown-item" href="<?php echo $link ?>mensagens/"><i class="ti-email m-r-5 m-l-5"></i> Mensagens</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?php echo $link ?>logout.php"><i class="fa fa-power-off m-r-5 m-l-5"></i> Logout</a>
                    </div>
                </li>
                <!-- User profile -->
            </ul>
        </div>
    </nav>
</header>