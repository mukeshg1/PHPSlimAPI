<?php
    session_start();

    $dbconn = mysqli_connect('127.0.0.1', 'root', '', 'registration') or
    exit("Database Error! Try after sometime.");

    if(isset($_POST['loginemail']))
    {
        $loginemail = mysqli_real_escape_string($dbconn, $_POST['loginemail']);
    }
    if(isset($_POST['loginpassword']))
    {
        $loginpassword = mysqli_real_escape_string($dbconn, $_POST['loginpassword']);
    }
   

    $sql = "select * from user where email = '$loginemail' ";
    if(!$dbconn)
    {
        echo "Error connecting to database..";
    }
    $result = mysqli_query($dbconn,$sql);
    $rows = mysqli_fetch_array($result);
    if($rows)
    {
        $pass = $rows['password1'];
        if (password_verify($loginpassword, $pass ))
        {
            $_SESSION['user'] = $loginemail;
            /*echo "login successfull..<br>";
            echo "Welcome, " . $rows["email"]. "<br>Name: " . $rows["firstname"]. " " . $rows["lastname"]. "<br>";*/

            ?>
            <html>
            <head>
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> 
            </head>
            <body>
                <div class="card-header">
                    Welcome, <?php echo $loginemail ?>!
                    <button><a href='logout.php'>Logout</php></a></button>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">Name</div>
                                <div class="col-md-6"><?php echo $rows["firstname"]. " " . $rows["lastname"]?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">Gender</div>
                                <div class="col-md-6"><?php echo $rows["gender"]?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">Birth date</div>
                                <div class="col-md-6"><?php echo $rows["birthdate"]?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">Mobile Number</div>
                                <div class="col-md-6"><?php echo $rows["phone"]?></div>
                            </div>     
                            <div class="row">
                                <div class="col-md-6">Email</div>
                                <div class="col-md-6"><?php echo $rows["email"]?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">Address</div>
                                <div class="col-md-6"><?php echo $rows["addr"]?></div>
                            </div>  
                            <div class="row">
                                <div class="col-md-6">About you</div>
                                <div class="col-md-6"><?php echo $rows["about"]?></div>
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
            require 'index.html';
            echo "email address or password is invalid.";
        }
    }
    else
    {
        require 'index.html';
        echo "Invalid username or password";
    }
?>

