<?php require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST'):
    $data = json_decode(file_get_contents('php://input', true));
    //myPrint_r($_POST);
    //exit;
    if(isset($data->auth)) :
        $sql = "SELECT * FROM users WHERE login= :login AND password = :password";
        $rq = $connect->prepare($sql);
        $rq->execute([
            'login' => $data->login,
            'password' => $data->password
        ]);
        $nb_users = $rq->rowCount();
        

        //myPrint_r($users);

        if($nb_users > 0) :
            $user = $rq->fetchObject();
            //création SESSION
            $_SESSION['id_user'] = $user->id;
            $_SESSION['token'] = md5(date("DdMYHis"));
            $_SESSION['expiration'] = time() + 60 * 60;
            $response['token'] = $_SESSION['token'];
            $response['message'] = "Connecté";
            $response['code'] = 'ok';
        else: 
            $response['message'] = "erreur de log ou pass";
            $response['code'] = "pas ok";
        endif;
        
    endif;
//end method POST
//method GET
else :
   //deconnexion
   unset($_SESSION['token']);
   unset($_SESSION['id_user']);
   unset($_SESSION['expiration']);
   $response['message'] = "Déconnexion";
   $response['code'] = "ok";
endif; 
echo json_encode($response);
?>
