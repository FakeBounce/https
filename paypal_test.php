<?php
 
session_start();
include("api_paypal.php");

$_SESSION['name_0'] = 'test1';
$_SESSION['name_1'] = 'test2';
$_SESSION['prix_0'] = 5;
$_SESSION['prix_1'] = 10;
$_SESSION['desc'] = 'description';
$requete = construit_url_paypal();
$requete = $requete."&METHOD=SetExpressCheckout".
			"&CANCELURL=".urlencode("http://www.localhost/cancel.php").
			"&RETURNURL=".urlencode("http://www.localhost/return.php").
			"&PAYMENTREQUEST_0_AMT=".$_SESSION['prix_0']."".
			"&PAYMENTREQUEST_0_CURRENCYCODE=EUR".
			"&PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID=".urlencode("romane_b12-facilitator-1@myges.fr").
			"&PAYMENTREQUEST_0_PAYMENTREQUESTID=".urlencode("Projet https :".$_SESSION['name_0']).
			"&L_PAYMENTREQUEST_0_NAME0=".urlencode($_SESSION['name_0']).
			"&L_PAYMENTREQUEST_0_QTY0=1".
			"&L_PAYMENTREQUEST_0_AMT0=".$_SESSION['prix_0']."".
			"&PAYMENTREQUEST_1_AMT=".$_SESSION['prix_1']."".
			"&PAYMENTREQUEST_1_CURRENCYCODE=EUR".
			"&PAYMENTREQUEST_1_SELLERPAYPALACCOUNTID=".urlencode("romane_b12-facilitator-1@myges.fr").
			"&PAYMENTREQUEST_1_PAYMENTREQUESTID=".urlencode("Projet https :".$_SESSION['name_1']).
			"&L_PAYMENTREQUEST_1_NAME0=".urlencode($_SESSION['name_1']).
			"&L_PAYMENTREQUEST_1_QTY0=1".
			"&L_PAYMENTREQUEST_1_AMT0=".$_SESSION['prix_1']."".
			"&LOCALECODE=FR".
			"&HDRIMG=".urlencode("https://media-divers.scrapmalin.com/produit/original/pochoir-a4-basketteur-R1-191401-1.jpg").
			"&ALLOWNOTE=1".
			"&BRANDNAME=www.lorempixel.com".
			"&LOGOIMG=".urlencode("https://media-divers.scrapmalin.com/produit/original/pochoir-a4-basketteur-R1-191401-1.jpg")
			;
echo $requete;exit;
			$ch = curl_init($requete);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$resultat_paypal = curl_exec($ch);

if (!$resultat_paypal)
	{echo "<p>Erreur</p><p>".curl_error($ch)."</p>";
	var_dump($resultat_paypal);}
else
{
	$liste_param_paypal = recup_param_paypal($resultat_paypal); // Lance notre fonction qui dispatche le résultat obtenu en un array

	// Si la requête a été traitée avec succès
	if ($liste_param_paypal['ACK'] == 'Success')
	{
		// Redirige le visiteur sur le site de PayPal
		header("Location: https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=".$liste_param_paypal['TOKEN']."&useraction=commit");
		//header("Location: https://www.paypal.com/webscr&cmd=_express-checkout&token=".$liste_param_paypal['TOKEN']."&useraction=commit");
                exit();
	}
	else // En cas d'échec, affiche la première erreur trouvée.
	{echo "<p>Erreur de communication avec le serveur PayPal.<br />".$liste_param_paypal['L_SHORTMESSAGE0']."<br />".$liste_param_paypal['L_LONGMESSAGE0']."</p>";print_r($_SESSION);}		
}
curl_close($ch);

/* return.php

*/




/*
			
$quer='https://api-3t.sandbox.paypal.com/nvp?';
$a = array();
$a[0]='VERSION=98.0';
$a[1]='USER=technique-facilitator-1_api1.parisbeaute.fr';
$a[2]='PWD=2WNQGZJVMJYBLW8H';
$a[3]='SIGNATURE=ANX7BRhzqkmraBXCbve-hEqYl8ZQA44mhRljkqWZKG3oI6Af.IuMcH-z';
$a[4]='METHOD=SetExpressCheckout';
$a[5]='AMT=10.00';
$a[6]='RETURNURL=http://marueconnectee.com';
$a[7]='CANCELURL=http://localhost/mrc/index.php';
$a[8]='PAYMENTREQUEST_0_PAYMENTACTION=Sale';
$a[9]='L_PAYMENTREQUEST_0_NAME0=10DecafKonaBlendCoffee';
$a[10]='L_PAYMENTREQUEST_0_NUMBER0=623083';
$a[11]='L_PAYMENTREQUEST_0_AMT0=9.95';
$a[12]='L_PAYMENTREQUEST_0_QTY0=2';
$a[13]='L_PAYMENTREQUEST_0_NAME1=CoffeeFilterbags';
$a[14]='L_PAYMENTREQUEST_0_NUMBER1=623084';
$a[15]='L_PAYMENTREQUEST_0_AMT1=39.70';
$a[16]='L_PAYMENTREQUEST_0_QTY1=2';
$a[17]='PAYMENTREQUEST_0_ITEMAMT=99.30';
$a[18]='PAYMENTREQUEST_0_TAXAMT=2.58';
$a[19]='PAYMENTREQUEST_0_SHIPPINGAMT=3.00';
$a[20]='PAYMENTREQUEST_0_HANDLINGAMT=2.99';
$a[21]='PAYMENTREQUEST_0_SHIPDISCAMT=-3.00';
$a[22]='PAYMENTREQUEST_0_INSURANCEAMT=1.00';
$a[23]='PAYMENTREQUEST_0_AMT=105.87';
$a[24]='PAYMENTREQUEST_0_CURRENCYCODE=USD';
$a[25]='ALLOWNOTE=1';

for($i=0;$i<(count($a));$i++)
{
	if($i!=0)
	$quer.='&'.$a[$i];
	else
	$quer.=$a[$i];
}

$content.= '<a href="'.$requete.'" target="_blank">Blabla</a>';

/*
https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=EC%2d73J43170P22761628&useraction=commit

https://api-3t.sandbox.paypal.com/nvp?VERSION=98.0&USER=technique-facilitator-1_api1.parisbeaute.fr&PWD=2WNQGZJVMJYBLW8H&SIGNATURE=ANX7BRhzqkmraBXCbve-hEqYl8ZQA44mhRljkqWZKG3oI6Af.IuMcH-z&METHOD=GetExpressCheckoutDetails&token=EC%2d76259746RA570934F

https://api-3t.sandbox.paypal.com/nvp?VERSION=98.0&USER=technique-facilitator-1_api1.parisbeaute.fr&PWD=2WNQGZJVMJYBLW8H&SIGNATURE=ANX7BRhzqkmraBXCbve-hEqYl8ZQA44mhRljkqWZKG3oI6Af.IuMcH-z&METHOD=DoExpressCheckoutPayment&TOKEN=EC%2d76259746RA570934F&PAYMENTACTION=sale&PAYERID=SK3UL25TRYUZ6&AMT=10.00&CURRENCYCODE=USD

*/
?>