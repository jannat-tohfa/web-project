<?php
session_start();
include('includes/dbconnection.php');

function sendemail_verify($firstname,$lastname,$email,$VerifiicationCode)
    {

    }




if(isset($_POST['submit']))
{
    $firstname = $_post['firstname'];
    $lastname = $_post['lastname'];
    $mobilenumber = $_post['mobilenumber'];
    $email = $_post['email'];
    $password = $_post['password'];
    $repeatpassword = $_post['repeatpassword'];
    $VerifiicationCode = md5(rand());

    $check_email_quiry = "SELECT Email FROM tbluser WHERE Email='$email' LIMIT 1 ";
    $check_email_quiry_run = mysqli_query($con, $check_email_quiry);

    if(mysqli_nb_rows($check_email_quiry_run) > 0 )
    {
        $_SESSION['status'] = "email already exists";
        header("Location:signup.php");

    }
    else
    {
   $query = "INSERT INTO users (FirstName,lastName,MobileNumber,Email,Password,VerificationCode) VALUES ('$firstname','$lastname','$mobilenumber','$email','$password',' $VerifiicationCode' )";
    $query_run = mysqli_query($con, $query);
    if($query_run)
    {    sendemail_verify("$firstname","$lastname","$email","$VerifiicationCode");
        $_SESSION['status'] = "Registration success! please verify your email ";
        header("Location:signup.php");
    }
    else{
        $_SESSION['status'] = "Registration failed";
        header("Location:signup.php");

    }
   }
}

?>