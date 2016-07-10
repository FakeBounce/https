<?php
session_start();
include("./service/Paypal.php");
if(is_array($_SESSION["vendors"])){
    $paypal = new \Service\Paypal();
    $call = $paypal->payment($_SESSION["vendors"]);
}