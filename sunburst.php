<?php 
require_once 'inc/header.php';
require_once 'inc/connect.php';
?> 
      <h1 class="amarante">SUNBURST le groupe</h1>
<?php        
      $res = $pdo->prepare('SELECT * FROM sunburst WHERE id = :id');
      $res->bindValue(':id' ,1  , PDO::PARAM_INT);
        
      if($res->execute()){


      $sunburst = $res->fetch(PDO::FETCH_ASSOC);
?>
    <p class="story"><?php echo $sunburst['desc_sun']; ?></p>
<?php } ?>

        <?php                   
            $res = $pdo->prepare('SELECT * FROM zicos ORDER BY id ASC');
            $res->execute();

            $musicien = $res->fetchAll(PDO::FETCH_ASSOC); 
            foreach($musicien as $zico){
 
        ?>
        <div class="box box-2">
            <h3><?php echo $zico['nickname']; ?></h3>
            <div class="image">
                <img alt="photo du musicien" src="images/<?php echo $zico['picture']; ?>">
            </div>
            <div class="description">
                <ul>
                    <li><?php echo $zico['name']; ?></li>
                    <li><?php echo $zico['instru']; ?></li>
                    <li><?php echo $zico['desc_zicos']; ?></li>
                </ul>
            </div>
        </div>
        <?php } ?>

<?php 
require_once 'inc/footer.php';
?>