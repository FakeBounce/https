<?php
session_start();
if(empty($_SESSION['vendors']))
{
	$_SESSION['vendors'] = array();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang='en'>
<head>
    <meta charset='utf-8'>
    <meta content='IE=Edge,chrome=1' http-equiv='X-UA-Compatible'>
    <meta content='width=device-width, initial-scale=1.0' name='viewport'>
    <title>PizzaShop</title>
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.1/html5shiv.js" type="text/javascript"></script>
    <![endif]-->
    <link href="css/application.css" media="all" rel="stylesheet" type="text/css" />
</head>
<body>

<?php
    if(isset($_GET['id']) && isset($_GET['name']) && isset($_GET['price']) && isset($_GET['desc']) && isset($_GET['qt']) && isset($_GET['vendor']) && isset($_GET['requestid'])){
        $product = array(
            'name' =>  $_GET['name'],
            'price' =>  $_GET['price'],
            'desc' =>  $_GET['desc'],
            'quantity' =>  $_GET['qt'],
            'requestid' =>  $_GET['requestid']
        );
		if($_GET['vendor'] == "vendor1@gmail.com")
		{
			if(empty($_SESSION['vendors']['vendor1@gmail.com']))
			{
				$_SESSION['vendors']['vendor1@gmail.com'] = array();
			}
			array_push($_SESSION['vendors']['vendor1@gmail.com'], $product);
		}
		if($_GET['vendor'] == "vendor2@gmail.com")
		{
			if(empty($_SESSION['vendors']['vendor2@gmail.com']))
			{
				$_SESSION['vendors']['vendor2@gmail.com'] = array();
			}
			array_push($_SESSION['vendors']['vendor2@gmail.com'], $product);
		}
		if($_GET['vendor'] == "romane_b12-facilitator-1@myges.fr")
		{
			if(empty($_SESSION['vendors']['romane_b12-facilitator-1@myges.fr']))
			{
				$_SESSION['vendors']['romane_b12-facilitator-1@myges.fr'] = array();
			}
			array_push($_SESSION['vendors']['romane_b12-facilitator-1@myges.fr'], $product);
		}
    }
?>

<div class='container' id='content'>
    <div class='row pizza-row'>
        <div class='span2'>
            <div class='image'>
                <img alt="0000000000000000000000000000000?d=identicon&amp;f=y" src="http://www.gravatar.com/avatar/0000000000000000000000000000000?d=identicon&amp;f=y" />
            </div>
            <div class='details'>
                5$ -
                <a href="index.php?id=1&name=Pizza1&price=5&desc=Pizza1&qt=1&vendor=vendor1@gmail.com&requestid=pizza1" class="btn btn-small" data-disable-with="Procesing.." data-method="post" rel="nofollow">Acheter</a>
            </div>
        </div>
        <div class='span2'>
            <div class='image'>
                <img alt="0000000000000000000000000000001?d=identicon&amp;f=y" src="http://www.gravatar.com/avatar/0000000000000000000000000000001?d=identicon&amp;f=y" />
            </div>
            <div class='details'>
                6$ -
                <a href="index.php?id=2&name=Pizza2&price=6&desc=Pizza2&qt=1&vendor=vendor1@gmail.com&requestid=pizza2" class="btn btn-small" data-disable-with="Procesing.." data-method="post" rel="nofollow">Acheter</a>
            </div>
        </div>
        <div class='span2'>
            <div class='image'>
                <img alt="0000000000000000000000000000002?d=identicon&amp;f=y" src="http://www.gravatar.com/avatar/0000000000000000000000000000002?d=identicon&amp;f=y" />
            </div>
            <div class='details'>
                7$ -
                <a href="index.php?id=3&name=Pizza3&price=7&desc=Pizza3&qt=1&vendor=vendor2@gmail.com&requestid=pizza3" class="btn btn-small" data-disable-with="Procesing.." data-method="post" rel="nofollow">Acheter</a>
            </div>
        </div>
        <div class='span2'>
            <div class='image'>
                <img alt="0000000000000000000000000000003?d=identicon&amp;f=y" src="http://www.gravatar.com/avatar/0000000000000000000000000000003?d=identicon&amp;f=y" />
            </div>
            <div class='details'>
                10$ -
                <a href="index.php?id=4&name=Pizza4&price=10&desc=Pizza4&qt=1&vendor=romane_b12-facilitator-1@myges.fr&requestid=pizza4" class="btn btn-small" data-disable-with="Procesing.." data-method="post" rel="nofollow">Acheter</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">Mon panier</div>
            <div class="panel-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Description</th>
                            <th>Prix</th>
                            <th>Quantite</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
					if(isset($_SESSION["vendors"]))
					{
						foreach($_SESSION["vendors"] as $vendor){
							foreach($vendor as $product){
						?>
								<tr>
									<td><?php echo $product['name']; ?></td>
									<td><?php echo $product['desc']; ?></td>
									<td><?php echo $product['price']; ?></td>
									<td><?php echo $product['quantity']; ?></td>
								</tr>
						<?php
							}
						}
					}
                    ?>
                    </tbody>
                </table>
            </div>
            <a href="paiement.php">Paiement</a>
        </div>
    </div>
</div>

<script src="js/application.js" type="text/javascript"></script>
</body>
</html>