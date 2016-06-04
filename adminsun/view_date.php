<?php 
require_once '../inc/connect.php';

include_once '../inc/header_admin.php';
?>


<h2 class="text-center">Toute les dates de concert</h2>

<a type="button" class="btn btn-info" href="add_dates.php">Ajouter un date</a>
<div class="table-responsive">
  <table class="table table-striped table-bordered table-condensed">
      <thead>
        <tr>
          <th>id</th>
          <th>Date</th>
          <th>Heure</th>
          <th>Lieu</th>
          <th>Adresse</th>
          <th>Ville</th>
          <th>Tarif</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php        
      $res = $pdo->prepare('SELECT * FROM date_concert ORDER BY id ASC');
  	  $res->execute();

  	  $date_C = $res->fetchAll(PDO::FETCH_ASSOC); 
      foreach($date_C as $date){
      ?>
            <tr>
              <td class="text-center"><?php echo $date['id']; ?></td>
              <td class="text-center"><?php echo $date['dateC']; ?></td>
              <td class="text-center"><?php echo $date['heureC']; ?></td>
              <td class="text-center"><?php echo $date['place']; ?></td>
              <td class="text-center"><?php echo $date['adress']; ?></td>
              <td class="text-center"><?php echo $date['city']; ?></td>
              <td class="text-center"><?php echo $date['tarif']; ?></td>
              <td class="text-center">
                <a type="button" class="btn btn-primary" href="edit_date.php?id=<?php echo $date['id'];?>">Modifier</a>
                <a type="button" class="btn btn-danger" href="delete_date.php?id=<?php echo $date['id'];?>">Supprimer</a>
              </td> 
            </tr>
      <?php } ?>
      </tbody>
  </table>
</div>
<?php


include_once '../inc/footer_admin.php'; ?>