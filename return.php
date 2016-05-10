<?php
session_start();
include("api_paypal.php"); // On importe la page créée précédemment
$requete = construit_url_paypal(); // Construit les options de base
include("./service/Paypal.php");
if(is_array($_SESSION["products"])){
	$paypal = new \Service\Paypal();
	$call = $paypal->responsePayment($_GET['token'],$_GET['PayerID'],$_SESSION["products"]);
}
// On ajoute le reste des options
// La fonction urlencode permet d'encoder au format URL les espaces, slash, deux points, etc.)
$requete = $requete."&METHOD=DoExpressCheckoutPayment".
			"&TOKEN=".htmlentities($mesargs['token'], ENT_QUOTES). // Ajoute le jeton qui nous a été renvoyé
			"&PAYMENTREQUEST_0_AMT=".$_SESSION['prix']."".
			"&PAYMENTREQUEST_0_CURRENCYCODE=EUR".
			"&PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID=".urlencode("romane_b12-facilitator-1@myges.fr").
			"&PAYMENTREQUEST_0_PAYMENTREQUESTID=".urlencode("Projet Https :".$_SESSION['name_0']).
			"&L_PAYMENTREQUEST_0_NAME0=".urlencode($_SESSION['name_0']).
			"&L_PAYMENTREQUEST_0_QTY0=1".
			"&L_PAYMENTREQUEST_0_AMT0=".$_SESSION['prix']."".
			"&PayerID=".htmlentities($mesargs['PayerID'], ENT_QUOTES). // Ajoute l'identifiant du paiement qui nous a également été renvoyé
			"&PAYMENTACTION=sale";

// Initialise notre session cURL. On lui donne la requête à exécuter.
$ch = curl_init($requete);

// Modifie l'option CURLOPT_SSL_VERIFYPEER afin d'ignorer la vérification du certificat SSL. Si cette option est à 1, une erreur affichera que la vérification du certificat SSL a échoué, et rien ne sera retourné. 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
// Retourne directement le transfert sous forme de chaîne de la valeur retournée par curl_exec() au lieu de l'afficher directement. 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// On lance l'exécution de la requête URL et on récupère le résultat dans une variable
$resultat_paypal = curl_exec($ch);

if (!$resultat_paypal) // S'il y a une erreur, on affiche "Erreur", suivi du détail de l'erreur.
	{echo "<p>Erreur</p><p>".curl_error($ch)."</p>";}
// S'il s'est exécuté correctement, on effectue les traitements...
else
{
	$liste_param_paypal = recup_param_paypal($resultat_paypal); // Lance notre fonction qui dispatche le résultat obtenu en un array
	
	// On affiche tous les paramètres afin d'avoir un aperçu global des valeurs exploitables (pour vos traitements). Une fois que votre page sera comme vous le voulez, supprimez ces 3 lignes. Les visiteurs n'auront aucune raison de voir ces valeurs s'afficher.
	/*echo "<pre>";
	print_r($liste_param_paypal);
	echo "</pre>"; 
	*/
	// Si la requête a été traitée avec succès
	if ($liste_param_paypal['ACK'] == 'Success')
	{
		
		$requete = construit_url_paypal();
$requete = $requete."&METHOD=GetTransactionDetails".
			"&TransactionID=".$liste_param_paypal['PAYMENTINFO_0_TRANSACTIONID']."";
		$ch = curl_init($requete);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$resultat_paypal = curl_exec($ch);
		var_dump($resultat_paypal);
		echo 'Merci beaucoup ! <br>';
		// $liste_param_paypal = recup_param_paypal($resultat_paypal);
		// echo 'Nom : '.$liste_param_paypal['SHIPTONAME'].'<br>';
		// echo 'Mail : '.$liste_param_paypal['EMAIL'].'<br>';
		// echo 'Pays : '.$liste_param_paypal['SHIPTOCOUNTRYNAME'].'<br>';
		// echo 'Ville : '.$liste_param_paypal['SHIPTOCITY'].'<br>';
		// echo 'Code Postal : '.$liste_param_paypal['SHIPTOZIP'].'<br>';
		// echo 'Adresse : '.$liste_param_paypal['SHIPTOSTREET'].'<br>';
		// echo 'Heure de transaction : '.$liste_param_paypal['ORDERTIME'].'<br>';
		// echo 'But du projet : '.$liste_param_paypal['L_NAME0'].'<br>';
		// echo 'Valeur du projet : '.$liste_param_paypal['L_AMT0'].'<br>';
		// On affiche la page avec les remerciements, et tout le tralala...
		// Mise à jour de la base de données & traitements divers...
		//mysql_query("UPDATE commandes SET etat='OK' WHERE id_commande='".$liste_param_paypal['TRANSACTIONID']."'");
	}
	else // En cas d'échec, affiche la première erreur trouvée.
	{echo "<p>Erreur de communication avec le serveur PayPal.<br />".$liste_param_paypal['L_SHORTMESSAGE0']."<br />".$liste_param_paypal['L_LONGMESSAGE0']."</p>";}
}
// On ferme notre session cURL.
curl_close($ch);
?>
