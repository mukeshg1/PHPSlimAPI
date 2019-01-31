<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);

//Login Users
$app->post('/login', function(Request $request, Response $respose){
    session_start();
    $loginemail = false;
    $loginpassword = false;
    if(isset($_POST['loginemail']))
    {
        $loginemail = $_POST['loginemail'];
    }
    if(isset($_POST['loginpassword']))
    {
        $loginpassword = $_POST['loginpassword'];
    }
    $sql = "select * from user where email = :loginemail ";

    try{
        $db = new db(); 
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $arr = array(':loginemail'=>$loginemail);
        $stmt->execute($arr);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        
        $result = $stmt->fetch();
        if ($result)
        {
            $pass = $result['password1'];
            if (password_verify($loginpassword,$pass))
            {
                echo "<br>Login working.";
            }
            else
            {
                
                echo '{"notice": "Invalid Email or Password"}';
            }
            
        }
        else
        {
            echo '{"notice":"User doesnot exist. Please register and then proceed."}';
        }
        
        
        
    }
    catch(PDOException $e){
        echo '{"error: '.$e->getMessage().'}';
    }
});