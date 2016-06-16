<?php  session_start();
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


<h2 class="text-center">Les pistes audio</h2>

<a type="button" class="btn btn-info" href="add_audio.php">Ajouter une piste audio</a>
<div class="table-responsive">
  <table class="table table-striped table-bordered table-condensed">
      <thead>
        <tr>
          <th>id</th>
          <th>Ecoute</th>
          <th>Déscription</th>

          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php        
      $res = $pdo->prepare('SELECT * FROM mp3 ORDER BY id ASC');
  	  $res->execute();

  	  $musique = $res->fetchAll(PDO::FETCH_ASSOC); 
      foreach($musique as $mp3){
      ?>
            <tr>
              <td class="text-center"><?php echo $mp3['id']; ?></td>
              <td class="text-center"><audio src="../audio/<?php echo $mp3['link']; ?>" controls></audio></td>
              <td class="text-center"><?php echo $mp3['desc_mp3']; ?></td>
              <td class="text-center">
                <a type="button" class="btn btn-primary" href="edit_audio.php?id=<?php echo $mp3['id'];?>">Modifier</a>
                <a type="button" class="btn btn-danger" href="delete_audio.php?id=<?php echo $mp3['id'];?>">Supprimer</a>
              </td> 
            </tr>
      <?php } ?>
      </tbody>
  </table>
</div>
<?php


include_once '../inc/footer_admin.php'; ?>