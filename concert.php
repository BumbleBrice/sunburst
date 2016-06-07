<?php 
require_once 'inc/header.php';
require_once 'inc/connect.php';
?>

      <h1 class="amarante">Toutes nos dates de concert</h1>
<div class="table-responsive">
  <table class="rwd-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Heure</th>
          <th>Lieu</th>
          <th>Adresse</th>
          <th>Ville</th>
          <th>Tarif</th>
        </tr>
      </thead>
      <tbody>
      <?php        
      $res = $pdo->prepare('SELECT * FROM date_concert ORDER BY id DESC');
      $res->execute();

      $date_C = $res->fetchAll(PDO::FETCH_ASSOC); 
      foreach($date_C as $zico){
      ?>
        <tr>
          <td class="text-center"><?php echo $zico['dateC']; ?></td>
          <td class="text-center"><?php echo $zico['heureC']; ?></td>
          <td class="text-center"><?php echo $zico['place']; ?></td>
          <td class="text-center"><?php echo $zico['adress']; ?></td>
          <td class="text-center"><?php echo $zico['city']; ?></td>
          <td class="text-center"><?php echo $zico['tarif']; ?></td>
          <td class="text-center">€</td>
        </tr>
        <?php }?>
      </tbody>
  </table>
</div>

       


<?php 
require_once 'inc/footer.php';
?>