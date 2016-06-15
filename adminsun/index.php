<?php 
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

<div class="alert alert-info" role="alert">Déscription actuel du groupe :</div>	


<?php        
      $res = $pdo->prepare('SELECT * FROM sunburst WHERE id = :id');
      $res->bindValue(':id' ,1  , PDO::PARAM_INT);
        
      if($res->execute()){


      $sunburst = $res->fetch(PDO::FETCH_ASSOC);
  	  echo $sunburst['desc_sun'];
  	}
?>
		<br><br><br><a type="button" class="btn btn-primary" href="edit_sun.php?id=<?php echo $sunburst['id'];?>">Modifier</a>
<?php


include_once '../inc/footer_admin.php'; ?>