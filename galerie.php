<?php 
require_once 'inc/header.php';
require_once 'inc/connect.php';
?> 
      <h1 class="amarante">Photos</h1>
<?php                   
    $res = $pdo->prepare('SELECT * FROM galerie ORDER BY rand()');
    $res->execute();

    $photoGal = $res->fetchAll(PDO::FETCH_ASSOC); 
    foreach($photoGal as $photo){
 
        ?>
        <div class="dox dox-2">
            <div class="image">
                <img alt="photo de <?php echo $photo['desc_picture']; ?>" src="images/<?php echo $photo['picture']; ?>">
            </div>
            
        </div>
	 
        <?php } ?>
<?php 
require_once 'inc/footer.php';
?>
