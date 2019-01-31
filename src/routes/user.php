<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

//Get All Users
$app->get('/user', function(Request $request, Response $respose){
    $sql = "SELECT * FROM user";

    try{
        $db = new db();
        $db = $db->connect();

        $stmt = $db->query($sql);
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);
    }
    catch(PDOException $e){
        echo '{"error: '.$e->getMessage().'}';
    }
});

// Add users
$app->post('/user/add', function(Request $request, Response $response){
    $firstname =  $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname =  $_POST['lastname'];
    $gender =  $_POST['gender'];
    $birthdate = $_POST['birthdate'];
    $phone =  $_POST['phone'];
    $email =  $_POST['email'];
    $addr =  $_POST['addr'];
    $password1 =  $_POST['password1'];
    $password2 = $_POST['password2'];
    $about =  $_POST['about'];
    $sql = "INSERT INTO user (firstname, middlename, lastname, gender, birthdate, phone, email, addr, password1, password2, about) VALUES ( :firstname,
    :middlename, :lastname, :gender, :birthdate, :phone, :email, :addr, :password1, :password2, :about)";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $password1 = password_hash($password1, PASSWORD_BCRYPT);
        $password2 = password_hash($password2, PASSWORD_BCRYPT);
        $arr = array(':firstname'=> $firstname,':middlename' => $middlename,':lastname' =>  $lastname,':gender' => $gender,':birthdate' => $birthdate,':phone' => $phone,':email' => $email,
                     ':addr' => $addr,':password1' => $password1,':password2' => $password2,':about' => $about);
        $stmt->execute($arr);
        echo '{"notice": "User Added"}';
    } catch(PDOException $e){
        echo '{"error": '.$e->getMessage().'}';
    }
});
