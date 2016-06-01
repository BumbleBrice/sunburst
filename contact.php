
<?php 
require_once 'header.php';
?>      

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
            </div>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    </body>
</html>