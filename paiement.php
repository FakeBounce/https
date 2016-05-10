<?php
session_start();
include("./service/Paypal.php");
if(is_array($_SESSION["products"])){
    $paypal = new \Service\Paypal();
    $call = $paypal->payment($_SESSION["products"]);
    var_dump($call);
}