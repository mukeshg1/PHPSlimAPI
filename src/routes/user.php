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



//Login Users
$app->post('/login', function(Request $request, Response $respose){
    session_start();
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
                $_SESSION['user'] = $loginemail;?>

<html>
            <head>
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> 
            </head>
            <body>
                <div class="card-header">
                    Welcome, <?php echo $loginemail ?>!
                    <a href='logout.php'>Logout</php></a>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">Name</div>
                                <div class="col-md-6"><?php echo $result["firstname"]. " " . $result["lastname"]?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">Gender</div>
                                <div class="col-md-6"><?php echo $result["gender"]?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">Birth date</div>
                                <div class="col-md-6"><?php echo $result["birthdate"]?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">Mobile Number</div>
                                <div class="col-md-6"><?php echo $result["phone"]?></div>
                            </div>     
                            <div class="row">
                                <div class="col-md-6">Email</div>
                                <div class="col-md-6"><?php echo $result["email"]?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">Address</div>
                                <div class="col-md-6"><?php echo $result["addr"]?></div>
                            </div>  
                            <div class="row">
                                <div class="col-md-6">About you</div>
                                <div class="col-md-6"><?php echo $result["about"]?></div>
                            </div> 
                        </div>
                    </div>
                </div>   
            </body>
            </html>

                <?php
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
