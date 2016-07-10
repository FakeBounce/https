# https
Projet https

Le projet à été réalisé en PHP, sans framework.

Données à envoyer à l'application:
Un tableau qui contient les vendeurs et leurs produits vendus.

Il se présentera comme ceci: 
[Nom_du_tableau][Nom_vendeur]=>[Tableau_produit]
Le Nom_vendeur étant une clé et le Tableau_produit sa valeur, contenant les informations des produits (tableau associatif)

Exemple :
[Vendeurs][test@test.fr]=>[['nom_produit'=>'produit_1','quantite'=>10]....]

Les fonctions se trouvent dans le dossier services, fichier Paypal.php
On peut appeler la function "payment" qui prend en paramètre le tableau ci-dessus pour être redirigé vers le site de Paypal avec les informations de commande.

La fonction "responsePayment", qui prend en paramètre le token paypal, le PayerId et les produits vendu par le vendeur, va afficher les informations de la transaction.
