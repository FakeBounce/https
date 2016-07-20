# https
Projet https

Le projet à été réalisé en PHP, sans framework.

Données à envoyer à l'application:
Un tableau qui contient les vendeurs et leurs produits vendus.

Il se présentera comme ceci: 
[Nom_du_tableau][Nom_vendeur]=>[Tableau_produit]
Le Nom_vendeur étant une clé et le Tableau_produit sa valeur, contenant les informations des produits (tableau associatif)
Dans le tableau des produits, 4 variables sont nécéssaires : name (nom du produit) , quantity (quantité du produit), price (prix du produit) et requestid (qui correspond au nom de la transaction affichée sur le compte Paypal) 

Exemple :

    $tableau = array();
    $tableau = [[test@test.fr]=>[['name'=>'produit_1','quantity'=>10,'price'=>15]....],[vendeur2@test.fr]=>[['name'=>'produit_2','quantity'=>10,'price'=>20]];

Les fonctions se trouvent dans le dossier services, fichier Paypal.php
On peut appeler la function "payment" qui prend en paramètre le tableau ci-dessus pour être redirigé vers le site de Paypal avec les informations de commande.

Il faut d'abord instancier la classe bien sûr, voici un exemple :

    $paypal = new Paypal();
    $call = $paypal->payment($mon_tableau);

La fonction "responsePayment", qui prend en paramètre le token paypal, le PayerId et les produits vendu par le vendeur, va afficher les informations de la transaction.
/!\ Cette fonction ne marche que si le vendeur correspond à celui qui utilise l'API (dans le cas où il y aurait plusieurs vendeurs sur votre plateforme).
/!\ Je suis monsieur Maxime, je ne peux recevoir les informations de paiement de Monsieur Bertrand 

On instancie quand même la classe avant, exemple :

    	$paypal = new Paypal();
    	$call = $paypal->responsePayment($token,$Vendeur_ID,$mon_tableau);
