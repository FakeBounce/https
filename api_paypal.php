<?php
  function construit_url_paypal()
  {
	$api_paypal = 'https://api-3t.sandbox.paypal.com/nvp?'; // Site de l'API PayPal. On ajoute déjà le ? afin de concaténer directement les paramètres.
	// $api_paypal = 'https://api-3t.paypal.com/nvp?'; // Site de l'API PayPal. On ajoute déjà le ? afin de concaténer directement les paramètres.
	
	$version = 98.0; // Version de l'API
	
	// $user = 'technique-facilitator-1_api1.parisbeaute.fr'; // Utilisateur API
	// $pass = '2WNQGZJVMJYBLW8H'; // Mot de passe API
	// $signature = 'ANX7BRhzqkmraBXCbve-hEqYl8ZQA44mhRljkqWZKG3oI6Af.IuMcH-z'; // Signature de l'API
	 
	 //Vrai
	// $user = 'romane_b12_api1.myges.fr'; // Utilisateur API
	// $pass = 'GNPXF3WLQKNJ4MH4'; // Mot de passe API
	// $signature = 'AFcWxV21C7fd0v3bYYYRCpSSRl31AGKqrjDgGTTcbzDUtxadTnbD6awv'; // Signature de l'API
	
	//Sandbox
		  $user = 'romane_b12-facilitator-1_api1.myges.fr'; // Utilisateur API
	  $pass = '4RP8G3KM6R4YWNPA'; // Mot de passe API
	  $signature = 'AQU0e5vuZCvSg-XJploSa.sGUDlpA0Pcla5gynr5-NWk7PjD5tWFa8cT'; // Signature de l'API

	$api_paypal = $api_paypal.'VERSION='.$version.'&USER='.$user.'&PWD='.$pass.'&SIGNATURE='.$signature; // Ajoute tous les paramètres

	return 	$api_paypal; // Renvoie la chaîne contenant tous nos paramètres.
  }
  
    function recup_param_paypal($resultat_paypal)
  {
	$liste_parametres = explode("&",$resultat_paypal); // Crée un tableau de paramètres
	foreach($liste_parametres as $param_paypal) // Pour chaque paramètre
	{
		list($nom, $valeur) = explode("=", $param_paypal); // Sépare le nom et la valeur
		$liste_param_paypal[$nom]=urldecode($valeur); // Crée l'array final
	}
	return $liste_param_paypal; // Retourne l'array
  }
  ?>