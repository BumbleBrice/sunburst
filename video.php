<?php 
require_once 'inc/header.php';
require_once 'inc/connect.php';
?> 
<div class="container">
    <div class="row">
        <div class="col-md-6">
        <h1 class="amarante">Nous voir</h1>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/ToD7t0kPGzE?rel=0" frameborder="0" allowfullscreen></iframe>
        </div>
        <div class="col-md-6">
           <h1 class="amarante">Nous écouter</h1>
<?php        
      $res = $pdo->prepare('SELECT * FROM mp3 ORDER BY id ASC');
      $res->execute();

      $musique = $res->fetchAll(PDO::FETCH_ASSOC); 
      foreach($musique as $mp3){ ?>
                <div><h2><?php echo $mp3['desc_mp3']; ?></h2><audio src="audio/<?php echo $mp3['link']; ?>" controls></audio></div>
     <?php   } ?>
               
        </div>
        <!--  <div><h2>David Bowie - Oh ! You Pretty Things</h2><audio src="audio/bowie.mp3" controls></audio></div>
                <div><h2>Motôrhead - Ace of Spades</h2><audio src="audio/motor.mp3" controls></audio></div>
                <div><h2>U2 - In a Little While</h2><audio src="audio/u2.mp3" controls></audio></div> -->
</div>
<?php 
require_once 'inc/footer.php';
?>