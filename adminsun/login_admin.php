<!-- Page de connexion a l'administration, sachant qu'il n'y aura qu'un seul statu possible (seulement 2 administrateurs - mon pere et moi) gestion simple d'acces au page admin (pas de gestion de perte de mot de passe, donc pas de token de perte de mot de passe) gestion de session logué ou non...
 -->

 <?php
session_start();//permet de demarrer la session
if (isset($_SESSION['user']['role'])){
    header('Location: ../index.php');
}
require_once 'inc/header.php'
require_once 'inc/connect.php';



$post =array();
$error = array();
$mdpValide = false;
$errorSession = false;



if(!empty($_POST)){//01

	$post = array_map('strip_tags', $_POST);
	$post = array_map('trim', $post);


// On vérifie que l'adresse email est au bon format
	/*if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL)){*/
	if(!preg_match ( "#^[a-zA-Z0-9]{8,20}$#" , $post['name'] )){
		$error[] = 'Le nom ou le password est invalide';
	}	
	

	if(!preg_match ( "#^[a-zA-Z0-9]{8,20}$#" , $post['password'] )){

		$error[] = 'Le nom ou le password est invalide';
	}
	/*if(empty($post['password'])){
	
		$error[] = 'vous devez saisir un mot de passe valide entre 8 et 20 caractéres';
	}*/


			if(count($error) == 0){//02
			$select = $pdo->prepare('SELECT * FROM users  WHERE name = :checkName');//

			$select->bindValue(':checkName', $post['name']);
		if($select->execute()){//03

				$user = $select->fetch();//contient notre utilisateur relatif à l'adresse email
			//var_dump($user);
		if(!empty($user)){//04
																

		// on vérifie le mot de passe saisi et le mot de passe hashé
		if(password_verify($post['password'], $user['password'])){
		//ici le mot de passe est valide
		$mdpValide = true;

		$_SESSION['user'] = [

				'id'        => $user['id'],
				'nickname'  => $user['nickname'],
				'role'      => $user['role']
				];
		//je redirige vers la page "infos_users.php"


		header('Location: index.php');
		die;
																								
		}
		else {
		// Le mot de passe est invalide
		$error[] = 'Le couple identifiant/mot de passe est invalide';

							}							
		}//fin 04

		else {
		//utilisateur inconnu
		$error[] = 'Le couple identifiant/mot de passe est invalide';
																	}
					}//fin 03
		}//fin 02

}//fin 01





	if(count($error)!=0){

		foreach ($error as $key => $value) {

			echo $value.'<br>';
			# code...
		}
	}

?>

<h1 class="text-center">Login</h1>
<br>
	
<form class="form-horizontal" method="post">

	<div class="form-group">
		<label class="col-md-4 control-label" for="name">name</label>  
		<div class="col-md-4">
			<input id="name" name="name" type="name" placeholder="Votre nom" class="form-control input-md" required>
		</div>
	</div>

	<div class="form-group">
		<label class="col-md-4 control-label" for="password">Mot de passe</label>  
		<div class="col-md-4">
			<input id="password" name="password" type="password" placeholder="Votre password" class="form-control input-md" required>
		</div>
	</div>		

	<div class="form-group">
		<div class="col-md-4 col-md-offset-4">
			<button type="submit" class="btn btn-primary">Je me connecte</button>
			<a class="btn btn-warning" href="lost_password.php">Mot de passe oublié ?</a><br>
		</div>
	</div>

</form>







