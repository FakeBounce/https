<?php
session_start();
include("./service/Paypal.php");
if(is_array($_SESSION["vendors"])){
	$paypal = new \Service\Paypal();
	$call = $paypal->responsePayment($_GET['token'],$_GET['PayerID'],$_SESSION["vendors"]);
}
?>
