<?php
if($nivel==0) {
    echo
    '<a href="." class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="imagens/logo-usuario.png" width="40px" height="40px" alt="" /> </span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">
            <img style="margin-left:-15px;" src="imagens/logo_nfeonweb-paginas.png" height="30px" alt="" />
        </span>
    </a>
    ';
}

if($nivel==1) {
    echo
    '<a href="../" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="../imagens/logo-usuario.png" width="40px" height="40px" alt="" /> </span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">
            <img style="margin-left:-15px;" src="../imagens/logo_nfeonweb-paginas.png" height="30px" alt="" />
        </span>
    </a>
    ';
}

if($nivel==2) {
    echo
    '<a href="../../" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="../../imagens/logo-usuario.png" width="40px" height="40px" alt="" /> </span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">
            <img style="margin-left:-15px;" src="../../imagens/logo_nfeonweb-paginas.png" height="30px" alt="" />
        </span>
    </a>
    ';
}
?>