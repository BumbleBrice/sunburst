<?php 
session_start();
if (!empty($_SESSION) && isset($_SESSION['user']['role'])){

	if ($_SESSION['user']['role'] != 'admin') {
		header('Location: index.php');
	}
	
} else {
	header('Location: ../index.php');
}

require_once '../inc/connect.php';
require_once '../inc/fonctions.php';


$error = []; //Tableau qui contiendra les users d'erreurs 
$needConfirm = false; //Variable qui servira à confirmer la suppression



if (!empty($_GET) && isset($_GET['id'])) {
	//On vérifie l'id du user
	$id_zico = checkId($_GET['id']);
	//si l'id est incorrect (NULL)
	if(empty($id_zico)) {
		$error[] = 'la photo recherché n\'existe pas';
	}

	if (count($error) == 0) {

		if(isset($_GET['confirm']) && $_GET['confirm'] == 'ok') {
	 		$del = $pdo->prepare('DELETE FROM galerie WHERE id = :id');
	 		$del->bindValue(':id',$id_zico,PDO::PARAM_INT);
	 		if($del->execute()) {
	 				$_SESSION['del_zico'] = 'ok';
	 				header('Location: view_galerie.php');
	 				die;		
			} 
	 	}
	 	else {
				  $res = $pdo->prepare('SELECT * FROM galerie WHERE id = :id');
				  $res->bindValue(':id', $id_zico, PDO::PARAM_INT);
				  $res->execute();

				  $recette = $res->fetch(PDO::FETCH_ASSOC);
				$needConfirm = true;
		}
	}
}
if (count($error) > 0) : ?>
	<div><?=implode('<br>', $error);?></div>
<?php endif; ?>

<?php //Si un message à été effacé, on affiche la confirmation puis on efface la variable de session correspondante
	if(isset($_SESSION['del_zico']) && $_SESSION['del_zico'] == 'ok') {
		unset($_SESSION['del_zico']);
	}

include_once '../inc/header_admin.php';
?>
<div class="alert alert-danger" role="alert">
<p> ATTENTION ! Vous souhaitez surprimé la photo <?= $recette['desc_picture'] ?>!!! La sentence sera irrévocable !!!!</p>



</div>
<a type="button" class="btn btn-danger" href="delete_galerie.php?id=<?php echo $id_zico;?>&confirm=ok">Cliquez ici si vous souhaitez vraiment supprimer la photo</a>

<?php include_once '../inc/footer_admin.php'; ?>
