<?php
require_once dbConnect;

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['signup']))
{
    $name=trim($_POST['name']);
    $email=trim($_POST['email']);
    $password=$_POST['password'];
    $confirmpassword=$_POST['confirmpassword'];

}
