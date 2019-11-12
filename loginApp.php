<?php

    include("connectDbApp.php");

    header("Access-Control-Allow-Origin: *");

    header('Access-Control-Allow-Credentials: true');

    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

    header('Content-Type:application/json; charset=UTF-8');


    

    $postJson = json_decode(file_get_contents('php://input'), true);

    $query=mysqli_query($con, "SELECT * FROM alunos WHERE email='". $postJson[login]. "' AND senha = '" . $postJson[senha] . "'");

    if(mysqli_num_rows($query) >= 1){
        $data=mysqli_fetch_assoc($query);
        $dataUser=array(
            'pkId' => $data['pkId'],
            'nome' => $data['nome'],
            'nascimento' => $data['nascimento']
        );
        echo json_decode($dataUser); exit;
        $result = json_encode(array('success'=>true, 'result'=>$dataUser));
    } else {
        $result = json_encode(array('success'=>false, 'msg'=>'error, tente novamente'));
    }
   

    echo $result;


?>