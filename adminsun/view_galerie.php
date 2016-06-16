<?php session_start();
if (!empty($_SESSION) && isset($_SESSION['user']['role'])){

  if ($_SESSION['user']['role'] != 'admin') {
    header('Location: index.php');
  }
  
} else {
  header('Location: ../index.php');
}
require_once '../inc/connect.php';

include_once '../inc/header_admin.php';
?>


<h2 class="text-center">Les photos</h2>

<a type="button" class="btn btn-info" href="add_galerie.php">Ajouter un photo</a>
<div class="table-responsive">
  <table class="table table-striped table-bordered table-condensed">
      <thead>
        <tr>
          <th>id</th>
          <th>Photo</th>
          <th>déscription</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php        
      $res = $pdo->prepare('SELECT * FROM galerie ORDER BY id ASC');
  	  $res->execute();

  	  $photo = $res->fetchAll(PDO::FETCH_ASSOC); 
      foreach($photo as $pic){
      ?>
            <tr>
              <td class="text-center"><?php echo $pic['id']; ?></td>
              <td class="text-center"><?php echo '<img src="../images/'.$pic['picture'].'" alt="Photo du pics" width="50">'?></td>
              <td class="text-center"><?php echo $pic['desc_picture']; ?></td>
              <td class="text-center">
                <a type="button" class="btn btn-primary" href="edit_galerie.php?id=<?php echo $pic['id'];?>">Modifier</a>
                <a type="button" class="btn btn-danger" href="delete_galerie.php?id=<?php echo $pic['id'];?>">Supprimer</a>
              </td> 
            </tr>
      <?php } ?>
      </tbody>
  </table>
</div>
<?php


include_once '../inc/footer_admin.php'; ?>