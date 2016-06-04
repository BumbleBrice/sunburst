<?php 

session_start();

require_once '../inc/connect.php'; 

if (empty($_SESSION) || !isset($_SESSION['user'])){
    header('Location: ../index.php');
}

$post = array(); // Contiendra les données du formulaire nettoyées
$errors = array(); // contiendra nos éventuelles erreurs

$showErrors = false;
$success = false; 

$title = '';
$content = '';
$dirlink = "link-default.jpg";



if (!empty($_POST)) {
	
	foreach ($_POST as $key => $value) { // Nettoyage des données
		$post[$key] = trim(strip_tags($value)); // récupération du _POST dans un tableau
	}
	//if(strlen($post['title']) < 2 || strlen($post['title']) > 50){ // on définit les propriétés de 'title'
    if(!preg_match("#^[A-Z]+[a-zA-Z0-9À-ú\.:\!\?\&',\s-]{5,140}#", $post['title'])){    
        $errors[] = 'Votre nom de recette doit comporter entre 5 et 140 caractères et commencer par une majuscule';
    }
    //if(strlen($post['content']) < 2 ){ // on défini les propriétés de 'content'
    if(!preg_match("#^[a-zA-Z0-9À-ú\.:\!\?\&',\s-]{20,}#", $post['content'])){
        $errors[] = 'La recette doit comporter au minimum 20 caractères'; 
	}
	else {
	    $reqEmail = $pdo->prepare('SELECT title FROM recipes WHERE title = :title'); // Vérification au cas où l'email est déjà dans la pdo
        $reqEmail->bindValue(':title', $post['title']);
        $reqEmail->execute();
       
        if($reqEmail->rowCount() != 0){ // Si l'email n'est pas dans la pdo alors, on peu crée l'utilisateur
             $errors[] = 'La recette existe déjà !';
        }
	} 

	if(count($errors) > 0){  // On compte les erreurs, s'il y en a (supérieur a 0), on passera la variable $showErrors à true.
        $showErrors = true; // valeur booleen // permettra d'afficher nos erreurs s'il y en a

        $title = $post['title'];
        $content = $post['content'];
    }
    else { 
    	// Insertion dans la pdo 
    	$res = $pdo->prepare('INSERT INTO recipes (title, content, date_publish, link, id_user) VALUES(:title, :content, NOW(), :linkrecipe, :id_user )');

        $res->bindValue(':title',		 $post['title'], 	PDO::PARAM_STR);
        $res->bindValue(':content', 	 $post['content'],	PDO::PARAM_STR);
        $res->bindValue(':linkrecipe',   $dirlink,          PDO::PARAM_STR);
        $res->bindValue(':id_user',   $_SESSION['user']['id'],   	    PDO::PARAM_INT);
        
    
	    if($res->execute()){
	        $success = true; // Pour afficher le message de réussite si tout est bon
	    }
	    else {
	        die;
	    }
    }
}

include_once '../inc/header_admin.php';

?>


<h1 class="text-center">Ajouter une recette</h1>
<br>


<div class="container">

<?php 
if($success){ // On affiche la réussite si tout fonctionne
    echo '<div class="alert alert-success" role="alert"> La recette a bien été créée ! </div>';
}
?>

<?php if($showErrors == true): ?>
    <div class="alert alert-danger" role="alert">
        <p style="color:red">Veuillez corriger les erreurs suivantes :</p>
            <ul style="color:red">
            <?php foreach($errors as $err): ?>
                <li><?=$err;?></li>
            <?php endforeach;?>
            </ul>
    </div>
<?php endif; ?>

	<div class="alert alert-info" role="alert">Merci de remplir tous les champs correctement</div>	

	<form method="post" class="pure-form pure-form-aligned" enctype="multipart/form-data">

        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">Titre</span>
            <input type="text" class="form-control" name="title" placeholder="Nom de la recette" aria-describedby="basic-addon1" value="<?=$title;?>">
        </div>
        <br>

        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">Descriptif de la recette</span>
            <textarea id="content" name="content" rows="15" class="form-control input-md" placeholder="Descriptif complet de la recette pour le client"><?=$content;?></textarea>
        </div>
        <br>

        <div class="form-group">
            <div class="row">
                <div class="col-md-10">
                    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $maxSize; ?>">
                    <input type="file" class="filestyle" name="picture" data-buttonName="btn-primary">
                </div>

                <div class="col-md-2">
                    <input type="submit" class="btn btn-success" value="Ajouter la recette">
                </div>
            </div>
        </div><!--.form-group-->

	</form>

</div>
<?php

include_once '../inc/footer_admin.php';

?>
<!-- Page d'ajout des dates de concerts champ dans la table : date, heure, lieu, adresse, ville, tarif(int), € gérer en glyph bootstrap ou fontawesome -->