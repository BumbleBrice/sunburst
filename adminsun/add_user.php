<?php 

session_start();

require_once '../inc/connect.php'; 
include_once '../inc/header_admin.php';


$post = array(); // Contiendra les données du formulaire nettoyées
$errors = array(); // contiendra nos éventuelles erreurs

$showErrors = false;
$success = false; 


if (!empty($_POST)) {
	
	foreach ($_POST as $key => $value) { // Nettoyage des données
		$post[$key] = trim(strip_tags($value)); // récupération du _POST dans un tableau
	}
	if(strlen($post['nickname']) < 2 || strlen($post['nickname']) > 50){ // on défini les propriétés de 'nickname'
        $errors[] = '<div class="alert alert-danger" role="alert">Votre pseudo doit comporter entre 2 et 50 caractères</div>';
    }
    if(!isset($post['password']) && !empty($post['password'])) {
    	$errors[] = '<div class="alert alert-danger" role="alert">Votre mot de passe n\'est pas valide</div>';
    }
	if(count($errors) > 0){  // On compte les erreurs, si il y en as (supérieur a 0), on passera la variable $showErrors à true.
        $showErrors = true; // valeur booleen // permettra d'afficher nos erreurs s'il y en a

        $nickname = $post['nickname']; 
        $password = $post['password'];
    }
    else { 
    	// On sécurise notre password en le hashant
    	// IMPORTANT : On ne stocke jamais de mot de passe en clair en pdo
    	$password = password_hash($post['password'], PASSWORD_DEFAULT);

		// Insertion dans la pdo 
    	$res = $pdo->prepare('INSERT INTO users (nickname, password) VALUES(:nickname, :password)');

        $res->bindValue(':nickname', $post['nickname'], PDO::PARAM_STR);
        $res->bindValue(':password', $password);
        
        

         if($res->execute()){
	        $success = true; // Pour afficher le message de réussite si tout est bon
	    }
	    else {
	        die(var_dump($res->errorInfo()));
	    }
    }
}

include_once '../inc/header_admin.php';
?>


<h1 class="text-center">Ajouter un utilisateur</h1>
<br>

<div class="container">

		<?php

		if($success == 'true' && $success == 'true'){ // On affiche la réussite si tout fonctionne
		    echo '<div class="alert alert-success" role="alert"> L\'utilisateur est bien créer ! </div>';
		}

		if($showErrors){
		    echo implode('<br>', $errors);
		}

		?>

		<div class="alert alert-info" role="alert"> Merci de remplir tous les champs correctement</div>

		<form method="post" class="pure-form pure-form-aligned">

			<div class="input-group">
			  <span class="input-group-addon" id="basic-addon1">Pseudo</span>
			  <input type="text" class="form-control" name="nickname" placeholder="Votre pseudo" aria-describedby="basic-addon1">
			</div>
			<br>
			<div class="input-group">
			  <span class="input-group-addon" id="basic-addon1">Password</span>
			  <input type="password" class="form-control" name="password" placeholder="Votre mot de passe" aria-describedby="basic-addon1">
			</div>
			<br>
		<br>
		<input type="submit" class="btn btn-success" value="S'inscrire">
		</form>
		
</div>
<?php

include_once '../inc/footer_admin.php';

?>