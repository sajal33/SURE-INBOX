<?php
require('connection.php');

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//v_code is for verification code
function sendMail($email,$v_code){

    require ("PHPMailer/PHPMailer.php"); //all three are classes
    require ("PHPMailer/SMTP.php");
    require ("PHPMailer/Exception.php");

    $mail = new PHPMailer(true); // created object ;true se handle exceptions
    try {
        //Server settings
        
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'agarwalsajal33@gmail.com';                     //SMTP username
        $mail->Password   = 'cxrx lybe socy krwh';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom('agarwalsajal33@gmail.com', 'Secure Inbox');
        $mail->addAddress($email);     //Add a recipient
    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Email Verification';
    
    $mail->Body = "Thanks for registration! Click the link below to verify the email address: <a href='http://localhost/SECURE_INBOX/verify.php?email=$email&v_code=$v_code'>Verify</a>";

        $mail->send();
    return true;
    } catch (Exception $e) {
    return false;    
}

}

//session ki help se ham ekk variable ko multiple pages/ multiple php file par ascess kar sakte h
//session server par aaye kisi bhi user ko uniquely identify karta h


#for login
# HAS TO FETCH TWO THINGS EMAIL AND PASSWORD 
if(isset($_POST['login'])){
    
    $query = "SELECT * FROM `registered_users` WHERE `email`='$_POST[email_username]' OR `username`='$_POST[email_username]'";

    $result= mysqli_query($con,$query);

    if($result){
        if(mysqli_num_rows($result)==1){
            $result_fetch=mysqli_fetch_assoc($result);

            if($result_fetch['is_verified']==1){
                if(password_verify($_POST['password'],$result_fetch['password']))
                //   verify hashed password using password_verify function 
                // 1.pass string (non encrypted) matched with encrypred
                    {
                        //if password matched
                         //echo"Welcome";
                        $_SESSION['logged_in']= true;  //index in which user is logged in
                        $_SESSION['username']=$result_fetch['username'];    //stores the username
                        header("location: index.php");
                       
                    }
                    else{
                        //if incorrect password
                        echo"
                        <script>
                        alert('Incorrect password');
                        window.location.href='index.php';
                        </script>
                            ";
                    }
            }

          else{
            echo"
            <script>
            alert('Email Not Verified');
            window.location.href='index.php';
            </script>
                ";
          }

        }
        else{
            echo"
            <script>
            alert('Email or Username Not Registered');
            window.location.href='index.php';
            </script>
                ";
        }
    }
        else {
            echo"
            <script>
            alert('Cannot Run Query');
            window.location.href='index.php';
            </script>
                ";
        }
    

}


#for registration
if(isset($_POST['register']))
{
    $user_exit_query="SELECT * FROM `registered_users` WHERE `username` ='$_POST[username]' OR `email`='$_POST[email]'";
    $result=mysqli_query($con,$user_exit_query);


    if($result)
    {
        if(mysqli_num_rows($result)>0)
        {
            // if any user has already taken username or email
             $result_fetch= mysqli_fetch_assoc($result);
             if($result_fetch['username']==$_POST['username'])
             {
                // error for username already registered
                    echo"
                <script>
                alert('$result_fetch[username] - Username already taken');
                window.location.href='index.php';
                </script>
                    ";
                
             }  
             
        
        else{
            #error when email is already registered
            echo"
            <script>
            alert('$result_fetch[email] - E-mail already registered');
            window.location.href='index.php';
            </script>
                ";
            

        }
    }
    else{
        #it will be executed when user has not taken username or email  before


        //for password encryption
        $password=password_hash($_POST['password'],PASSWORD_BCRYPT);

        $v_code= bin2hex(random_bytes(16));

       $query= " INSERT INTO `registered_users`(`full_name`, `username`, `email`, `password`,`verification_code`,`is_verified`) VALUES ('$_POST[fullname]','$_POST[username]','$_POST[email]','$password','$v_code','0')";

       if(mysqli_query($con,$query) && sendMail($_POST['email'],$v_code)){
        #if data is inserted successfully
        echo"
    <script>
    alert('Registration Successful');
    window.location.href='index.php';
    </script>
        ";

       }
       else{
        #if data cannot be inserted
        echo"
    <script>
    alert('Server Down');
    window.location.href='index.php';
    </script>
        ";
       }
    }
    }
    else{
        echo"
    <script>
    alert('Cannot Run Query');
    window.location.href='index.php';
    </script>
        ";
    }
}
?>