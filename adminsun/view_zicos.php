<?php 
require_once '../inc/connect.php';

include_once '../inc/header_admin.php';
?>


<h2 class="text-center">Les musiciens</h2>

<a type="button" class="btn btn-info" href="add_zicos.php">Ajouter un musicien</a>
<div class="table-responsive">
  <table class="table table-striped table-bordered table-condensed">
      <thead>
        <tr>
          <th>id</th>
          <th>Pseudo</th>
          <th>Photo</th>
          <th>Nom</th>
          <th>Instrument</th>
          <th>déscription</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php        
      $res = $pdo->prepare('SELECT * FROM zicos ORDER BY id ASC');
  	  $res->execute();

  	  $musicien = $res->fetchAll(PDO::FETCH_ASSOC); 
      foreach($musicien as $zico){
      ?>
            <tr>
              <td class="text-center"><?php echo $zico['id']; ?></td>
              <td class="text-center"><?php echo $zico['nickname']; ?></td>
              <td class="text-center"><?php echo '<img src="../images/'.$zico['picture'].'" alt="Photo du zicos" width="50">'?></td>
              <td class="text-center"><?php echo $zico['name']; ?></td>
              <td class="text-center"><?php echo $zico['instru']; ?></td>
              <td class="text-center"><?php echo $zico['desc_zicos']; ?></td>
              <td class="text-center">
                <a type="button" class="btn btn-primary" href="edit_zicos.php?id=<?php echo $zico['id'];?>">Modifier</a>
                <a type="button" class="btn btn-danger" href="delete_zicos.php?id=<?php echo $zico['id'];?>">Supprimer</a>
              </td> 
            </tr>
      <?php } ?>
      </tbody>
  </table>
</div>
<?php


include_once '../inc/footer_admin.php'; ?>