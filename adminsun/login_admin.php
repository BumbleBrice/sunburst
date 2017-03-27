
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Administration Sunburst</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <!-- lien CDN Font Awesome -->
    <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="../css/style_admin.css">
</head>
<body>
	

	<main class="container">


<?php
session_start();//permet de demarrer la session




require_once '../inc/connect.php';

$post = [];
$error = [];
$mdpValide = false;
$errorSession = false;
$msgErr = '<div class="alert alert-success">Le nom ou le password est invalide</div>';



if(!empty($_POST)){//01

	$post = array_map('strip_tags', $_POST);
	$post = array_map('trim', $post);

	// On vérifie que l'adresse email est au bon format
	if(!preg_match ( "#^[a-zA-Z0-9]{3,20}$#" , $post['nickname'] )){
		$error[] = $msgErr;
	}
	if(!preg_match ( "#^[a-zA-Z0-9]{8,20}$#" , $post['password'] )){
		$error[] = $msgErr;
	}
	if(count($error) == 0){//02
		$select = $pdo->prepare('SELECT * FROM users  WHERE nickname = :checkName');//

		$select->bindValue(':checkName', $post['nickname']);
		if($select->execute()){//03

			$user = $select->fetch();//contient notre utilisateur relatif à le nom
			if(!empty($user)){//04
				// on vérifie le mot de passe saisi et le mot de passe hashé
				if(password_verify($post['password'], $user['password'])){
					//ici le mot de passe est valide
					$mdpValide = true;

					$_SESSION['user'] = [
							'id'        => $user['id'],
							'nickname'  => $user['nickname'],
							'role'		=> $user['role'],
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
		}
	}
?>

<h1 class="text-center">Login page administration</h1>
<h2 class="text-center alert alert-danger"><a href="../index.php">Si vous n'êtes pas membre de l'administration, merci de retourner sur le site</a></h2>
<br>
	
<form class="form-horizontal" method="post" action="">

	<div class="form-group">

		<div class="col-md-4 col-md-offset-4">
			<input id="nickname" name="nickname" type="text" placeholder="Votre nom" class="form-control input-md" required>
		</div>
	</div>

	<div class="form-group">
 	
		<div class="col-md-4 col-md-offset-4">
			<input id="password" name="password" type="password" placeholder="Votre password" class="form-control input-md" required>
		</div>
	</div>		

	<div class="form-group">
		<div class="col-md-4 col-md-offset-4">
			<button type="submit" class="btn btn-primary">Je me connecte</button>
		</div>
	</div>

</form>







