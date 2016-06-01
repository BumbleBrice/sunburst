<!DOCTYPE html>
<html lang="en">
    <head>
        <title>SUNBURST</title>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta name="description" content="Blur Menu with CSS3 Transitions" />
        <meta name="keywords" content="css3, transitions, menu, blur, navigation, typography, font, letters, text-shadow" />
        <meta name="author" content="Codrops" />
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
        <link rel="stylesheet" type="text/css" href="css/style3.css" />
        <link rel="stylesheet" type="text/css" href="css/contact.css" />
        <link href='http://fonts.googleapis.com/css?family=Josefin+Slab' rel='stylesheet' type='text/css' />
    </head>
    <body style="background-image: url(images/pattern.png), url(images/8.jpg);">
            <div class="wrapper">
                <nav>
                    <ul class="cmenu">
                        <li><a href="sunburst.php">SUnburst</a></li>
                        <li><a href="galerie.php">Galerie photo</a></li>
                        <li><a href="video.php">Video</a></li>
                        <li><a href="concert.php">Date de concert</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </nav>
            <main>
                    

<form class="form-style-4" action="" method="post">
    <label for="lastname">
    <span>Ton nom l'artiste?</span><input type="text" name="lastname" required="true" />
    </label>

    <label for="firstname">
    <span>Ton nom d'artiste?</span><input type="email" name="firstname" required="true" />
    </label>

    <label for="email">
    <span>Ton email l'ami?</span><input type="email" name="email" required="true" />
    </label>

    <label for="content">
    <span>Un blues en MI?</span><textarea name="content" onkeyup="adjust_textarea(this)" required="true"></textarea>
    </label>

    <label>
    <span class="glyphicon glyphicon-music" aria-hidden="true"></span><input type="submit" value="Envoye la musique" /><span class="glyphicon glyphicon-music" aria-hidden="true"></span>
</label>
</form>
    



                </main>
            </div>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    </body>
</html>