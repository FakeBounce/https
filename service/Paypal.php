<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 06/05/2016
 * Time: 15:09
 */


namespace Service;


class Paypal
{
    private $user;
    private $email;
    private $password;
    private $signature;
    private $api_paypal;
    private $version;
    private $cancelUrl;
    private $returnUrl;
    private $siteUrl;
    private $culture;
    private $currencyCode;

    public function __construct(){

        $this->user = 'romane_b12-facilitator-1_api1.myges.fr';
        $this->email = 'sageot.m-facilitator@gmail.com';
        $this->api_paypal = 'https://api-3t.sandbox.paypal.com/nvp?';
        $this->password = '4RP8G3KM6R4YWNPA';
        $this->signature = 'AQU0e5vuZCvSg-XJploSa.sGUDlpA0Pcla5gynr5-NWk7PjD5tWFa8cT';
        $this->version = 98.0;
        $this->cancelUrl = "http://localhost/https/cancel.php";
        $this->returnUrl = "http://localhost/https/return.php";
        $this->siteUrl = "www.google.fr";
        $this->currencyCode = "EUR";
        $this->culture = "FR";

    }

    private function constructUrlPaypalPayment() {
        $url = $this->api_paypal.'VERSION='.$this->version.'&USER='.$this->user.'&PWD='.$this->password.'&SIGNATURE='.$this->signature;
        $url .= "&METHOD=SetExpressCheckout".
            "&CANCELURL=".urlencode($this->cancelUrl).
            "&RETURNURL=".urlencode($this->returnUrl).
            "&PAYMENTACTION=Sale".
            "&LOCALECODE=".$this->culture.
            "&ALLOWNOTE=1".
            "&BRANDNAME=".$this->siteUrl;
        return $url;
    }

    private function constructUrlPaypalReturn($token,$payerId) {
        $url = $this->api_paypal.'VERSION='.$this->version.'&USER='.$this->user.'&PWD='.$this->password.'&SIGNATURE='.$this->signature;
        $url .= "&METHOD=DoExpressCheckoutPayment".
            "&TOKEN=".htmlentities($token,ENT_QUOTES).
            "&PayerID=".htmlentities($payerId, ENT_QUOTES). // Ajoute l'identifiant du paiement qui nous a également été renvoyé
            "&PAYMENTACTION=sale";
        return $url;
    }

    private function constructUrlTransaction($transactionId){
        $url = $this->api_paypal.'VERSION='.$this->version.'&USER='.$this->user.'&PWD='.$this->password.'&SIGNATURE='.$this->signature;
        $url .= "&METHOD=GetTransactionDetails".
            "&TransactionID=".$transactionId;

        return $url;
    }

    public function getParam($resultat_paypal)
    {
        $liste_param_paypal = array();
        $liste_parametres = explode("&",$resultat_paypal); // Crée un tableau de paramètres
        foreach($liste_parametres as $param_paypal) // Pour chaque paramètre
        {
            list($nom, $valeur) = explode("=", $param_paypal); // Sépare le nom et la valeur
            $liste_param_paypal[$nom]=urldecode($valeur); // Crée l'array final
        }
        return $liste_param_paypal; // Retourne l'array
    }

    private function addProductsToUrl($products){
        $url = "";
        $urlEnd = "";
        if(is_array($products)){
            $i = 0;
            $total = 0;
            foreach($products as $product){
                $url .=
                "&L_PAYMENTREQUEST_0_NAME".$i."=".urlencode($product['name']).
                "&L_PAYMENTREQUEST_0_QTY".$i."=".urlencode($product['quantity']).
                "&L_PAYMENTREQUEST_0_AMT".$i."=".urlencode($product['price']).
                "&L_PAYMENTREQUEST_0_NUMBER".$i."=".$i;
                $i++;
                $total += ($product['quantity'] * $product['price']);

            }

            $urlEnd = "&PAYMENTREQUEST_0_AMT=".urlencode($total).
                "&PAYMENTREQUEST_0_ITEMAMT=".urlencode($total).
                "&PAYMENTREQUEST_0_CURRENCYCODE=".urlencode($this->currencyCode).
                "&PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID=".urlencode($this->email).
                "&PAYMENTREQUEST_0_PAYMENTREQUESTID=".urlencode("blabla").
                $url;

        }

        return $urlEnd;
    }

    public function payment($products){
        $url = $this->constructUrlPaypalPayment().$this->addProductsToUrl($products);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $resultat_paypal = curl_exec($ch);

        if (!$resultat_paypal) {
            return curl_error($ch);
            //echo "<p>Erreur</p><p>" . curl_error($ch) . "</p>";
            //var_dump($resultat_paypal);
        }

        $liste_param_paypal = $this->getParam($resultat_paypal);
        if ($liste_param_paypal['ACK'] == 'Success')
        {
            header("Location: https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=".$liste_param_paypal['TOKEN']."&useraction=commit");
            exit();
        }
        curl_close($ch);
        return "<p>Erreur de communication avec le serveur PayPal.<br />".$liste_param_paypal['L_SHORTMESSAGE0']."<br />".$liste_param_paypal['L_LONGMESSAGE0']."</p>";

    }

    public function responsePayment($token,$payerId,$products){
        $url = $this->constructUrlPaypalReturn($token,$payerId).$this->addProductsToUrl($products);;
        // Initialise notre session cURL. On lui donne la requête à exécuter.
        $ch = curl_init($url);

        // Modifie l'option CURLOPT_SSL_VERIFYPEER afin d'ignorer la vérification du certificat SSL. Si cette option est à 1, une erreur affichera que la vérification du certificat SSL a échoué, et rien ne sera retourné.
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // Retourne directement le transfert sous forme de chaîne de la valeur retournée par curl_exec() au lieu de l'afficher directement.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // On lance l'exécution de la requête URL et on récupère le résultat dans une variable
        $resultat_paypal = curl_exec($ch);


        if (!$resultat_paypal) // S'il y a une erreur, on affiche "Erreur", suivi du détail de l'erreur.
        {
            return curl_error($ch);
            //echo "<p>Erreur</p><p>".curl_error($ch)."</p>";
        }
        else
        {
            $liste_param_paypal = $this->getParam($resultat_paypal); // Lance notre fonction qui dispatche le résultat obtenu en un array
            var_dump($liste_param_paypal);
            if ($liste_param_paypal['ACK'] == 'Success')
            {

                $requete = $this->constructUrlTransaction($liste_param_paypal['PAYMENTINFO_0_TRANSACTIONID']);
                $ch = curl_init($requete);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $resultat_paypal = curl_exec($ch);
                $liste_param_paypal = $this->getParam($resultat_paypal);
                var_dump($liste_param_paypal);exit;
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
        }
        curl_close($ch);
        return "<p>Erreur de communication avec le serveur PayPal.<br />".$liste_param_paypal['L_SHORTMESSAGE0']."<br />".$liste_param_paypal['L_LONGMESSAGE0']."</p>";
    }
}