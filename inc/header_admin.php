
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Administration Sunburst</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <!-- lien CDN Font Awesome -->
    <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="../css/style_admin.css">
</head>
<body>
	<div id="navbarBootstrap">
        <nav class="navbar navbar-default" role="navigation">
            <div class="container">
              	<div class="collapse navbar-collapse navbar-ex1-collapse navbar-center">
                    <ul class="nav navbar-nav"> 
                        <li><a href="../index.php">Retour site</a>
                        </li>
                        <li><a href="index.php">Administration</a>
                        </li>
                        <li class="dropdown">
    						<a href="view<_zicos" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Musiciens<span class="caret"></span></a>
    						<ul class="dropdown-menu">
                                <li><a href="view_zicos.php">Liste des musiciens</a></li>
                                 <li><a href="add_zicos.php">Ajouter un musicien</a></li>
    						</ul>
    					</li>
                        <li class="dropdown">
                            <a href="view_galerie" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Galerie<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="view_galerie.php">Liste des photo</a></li>
                                 <li><a href="add_galerie.php">Ajouter une photo</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="view_date.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Date des concert<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="view_date.php">Voir la liste des concert</a></li>
                                <li><a href="add_date.php">Ajouter une date</a></li>
                            </ul>
                        </li>

						<li><a href="view_video.php">Video et mp3</a></li>
						<li><a href="contact.php">Lire les messages</a></li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>
    </div> <!-- navbarBootstrap -->

	<main class="container">

