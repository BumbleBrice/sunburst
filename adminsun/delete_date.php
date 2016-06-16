<?php session_start();
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
	$id_date = checkId($_GET['id']);
	//si l'id est incorrect (NULL)
	if(empty($id_date)) {
		$error[] = 'le date recherché n\'existe pas';
	}

	if (count($error) == 0) {

		if(isset($_GET['confirm']) && $_GET['confirm'] == 'ok') {
	 		$del = $pdo->prepare('DELETE FROM date_concert WHERE id = :id');
	 		$del->bindValue(':id',$id_date,PDO::PARAM_INT);
	 		if($del->execute()) {
	 				$_SESSION['del_date'] = 'ok';
	 				header('Location: view_date.php');
	 				die;		
			} 
	 	}
	 	else {
				  $res = $pdo->prepare('SELECT * FROM date_concert WHERE id = :id');
				  $res->bindValue(':id', $id_date, PDO::PARAM_INT);
				  $res->execute();

				  $date = $res->fetch(PDO::FETCH_ASSOC);
				$needConfirm = true;
		}
	}
}
if (count($error) > 0) : ?>
	<div><?=implode('<br>', $error);?></div>
<?php endif; ?>

<?php //Si un message à été effacé, on affiche la confirmation puis on efface la variable de session correspondante
	if(isset($_SESSION['del_date']) && $_SESSION['del_date'] == 'ok') {
		unset($_SESSION['del_date']);
	}

include_once '../inc/header_admin.php';
?>
<div class="alert alert-danger" role="alert">
<p> ATTENTION ! Vous souhaitez surprimé le concert du <?= $date['dateC'] ?> au <?= $date['place'] ?>!!! La sentence sera irrévocable !!!!</p>



</div>
<a type="button" class="btn btn-danger" href="delete_date.php?id=<?php echo $id_date;?>&confirm=ok">Cliquez ici si vous souhaitez vraiment supprimer cette date</a>

<?php include_once '../inc/footer_admin.php'; ?>
