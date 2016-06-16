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

$post = array(); // Contiendra les données du formulaire nettoyées
$errors = array(); // contiendra nos éventuelles erreurs

$showErrors = false;
$success = false; 

$nickname = '';
$name = '';
$instru = '';
$desc_zicos = '';
$dirlink = "link-default.jpg";

$folder = '../images/'; // création de la variable indiquant le chemin du répertoire destination pour les fichiers uploadés (important  : le slash à la fin de la chaine de caractère).
$maxSize = 1000000 * 5; // 5Mo

if(!empty($_FILES) && isset($_FILES['picture'])) {

    if ($_FILES['picture']['error'] == UPLOAD_ERR_OK AND $_FILES['picture']['size'] <= $maxSize) {

        $nomFichier = $_FILES['picture']['name']; // récupère le nom de mon fichier au sein de la superglobale $_FILES (tableau multi-dimentionnel)
        $tmpFichier = $_FILES['picture']['tmp_name']; // Stockage temporaire du fichier au sein de la superglobale $_FILES (tableau multi-dimentionnel)
        
        $file = new finfo(); // Classe FileInfo
        $mimeType = $file->file($_FILES['picture']['tmp_name'], FILEINFO_MIME_TYPE); // Retourne le VRAI mimeType

        $mimTypeOK = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');

        if (in_array($mimeType, $mimTypeOK)) { // in_array() permet de tester si la valeur de $mimeType est contenue dans le tableau $mimTypeOK
                    

            $newFileName = explode('.', $nomFichier);
            $fileExtension = end($newFileName); // Récupère la dernière entrée du tableau (créé avec explode) soit l'extension du fichier

            // nom du fichier link au format : zicos-id-timestamp.jpg
            $finalFileName = 'musicien-'.time().'.'.$fileExtension; // Le nom du fichier sera donc zicos-id-timestamp.jpg (time() retourne un timestamp à la seconde)


                if(move_uploaded_file($tmpFichier, $folder.$finalFileName)) { // move_uploaded_file()  retourne un booleen (true si le fichier a été envoyé et false si il y a une erreur)
                    // Ici je suis sûr que mon image est au bon endroit
                    $dirlink = $finalFileName;
                    
                }
                else {
                    // Permet d'assigner un link par défaut
                    $dirlink = "link-default.jpg";
                }
        } // if (in_array($mimeType, $mimTypeOK))

        else {
            $error[] = 'Ce type de fichier est interdit, mime type incorrect !';
        } 


    } // end if ($_FILES['picture']['error'] == UPLOAD_ERR_OK AND $_FILES['picture']['size'] <= $maxSize)
    else {
        $error[] = 'Merci de choisir un fichier image (uniquement au format jpg) à uploader et ne dépassant pas 5Mo !';
    }
} // end if (!empty($_FILES) AND isset($_FILES['picture'])

else {
    // Permet d'assigner l link par défaut si la musicien n'en a aucun
    $dirlink = "link-default.jpg";
}

if (!empty($_POST)) {
	
	foreach ($_POST as $key => $value) { // Nettoyage des données
		$post[$key] = trim(strip_tags($value)); // récupération du _POST dans un tableau
	}
	//if(strlen($post['nickname']) < 2 || strlen($post['nickname']) > 50){ // on définit les propriétés de 'nickname'
    if(!preg_match("#^[A-Z]+[a-zA-Z0-9À-ú\.:\!\?\&',\s-]{3,15}#", $post['nickname'])){    
        $errors[] = 'Votre Pseudo de musicien doit comporter entre 3 et 15 caractères et commencer par une majuscule';
    }
    if(!preg_match("#^[A-Z]+[a-zA-Z0-9À-ú\.:\!\?\&',\s-]{3,15}#", $post['name'])){    
        $errors[] = 'Votre nom de musicien doit comporter entre 3 et 15 caractères et commencer par une majuscule';
    }
    if(!preg_match("#^[A-Z]+[a-zA-Z0-9À-ú\.:\!\?\&',\s-]{3,140}#", $post['instru'])){    
        $errors[] = 'Votre instrument de musiqie doit comporter entre 3 et 20 caractères et commencer par une majuscule';
    }
    //if(strlen($post['content']) < 2 ){ // on défini les propriétés de 'content'
    if(!preg_match("#^[a-zA-Z0-9À-ú\.:\!\?\&',\s-]{10,140}#", $post['desc_zicos'])){
        $errors[] = 'La musicien doit comporter au minimum 10 et 140 caractères'; 
	}
	else {
	    $reqEmail = $pdo->prepare('SELECT nickname FROM zicos WHERE nickname = :nickname'); // Vérification au cas où l'email est déjà dans la pdo
        $reqEmail->bindValue(':nickname', $post['nickname']);
        $reqEmail->execute();
       
        if($reqEmail->rowCount() != 0){ // Si l'email n'est pas dans la pdo alors, on peu crée l'utilisateur
             $errors[] = 'Le musicien existe déjà !';
        }
	} 

	if(count($errors) > 0){  // On compte les erreurs, s'il y en a (supérieur a 0), on passera la variable $showErrors à true.
        $showErrors = true; // valeur booleen // permettra d'afficher nos erreurs s'il y en a

        $nickname = $post['nickname'];
        $name = $post['name'];
        $instru = $post['instru'];
        $desc_zicos = $post['desc_zicos'];
    }
    else { 
    	// Insertion dans la pdo 
    	$res = $pdo->prepare('INSERT INTO `zicos` (`nickname`, `name`, `instru`, `desc_zicos`, `picture`) VALUES (:nickname, :name, :instru, :desc_zicos, :linkzicos)');

        $res->bindValue(':nickname',		$post['nickname'], 	PDO::PARAM_STR);
        $res->bindValue(':name', 	 		$post['name'],	PDO::PARAM_STR);
        $res->bindValue(':instru', 			$post['instru'],	PDO::PARAM_STR);
        $res->bindValue(':desc_zicos', 		$post['desc_zicos'],	PDO::PARAM_STR);
        $res->bindValue(':linkzicos',  		$dirlink,          PDO::PARAM_STR);
        
    
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


<h1 class="text-center">Ajouter un musicien ou une musicienne</h1>
<br>


<div class="container">

<?php 
if($success){ // On affiche la réussite si tout fonctionne
    echo '<div class="alert alert-success" role="alert"> La musicien a bien été créée ! </div>';
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
            <span class="input-group-addon" id="basic-addon1">Pseudo</span>
            <input type="text" class="form-control" name="nickname" placeholder="Surnom du musicien" aria-describedby="basic-addon1" value="<?=$nickname;?>">
        </div>
        <br>
		<div class="input-group">
            <span class="input-group-addon" id="basic-addon1">Nom</span>
            <input type="text" class="form-control" name="name" placeholder="Nom du musicien" aria-describedby="basic-addon1" value="<?=$name;?>">
        </div>
        <br>
        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">Instrument</span>
            <input type="text" class="form-control" name="instru" placeholder="Quel instrument joue-t-il?" aria-describedby="basic-addon1" value="<?=$instru;?>">
        </div>
        <br>

        <div class="input-group">
            <span class="input-group-addon" id="basic-addon1">Descriptif du musicien</span>
            <textarea id="content" name="desc_zicos" rows="15" class="form-control input-md" placeholder="Descriptif du musicen"><?=$desc_zicos;?></textarea>
        </div>
        <br>

        <div class="form-group">
            <div class="row">
                <div class="col-md-10">
                    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $maxSize; ?>">
                    <input type="file" class="filestyle" name="picture" data-buttonName="btn-primary">
                </div>

                <div class="col-md-2">
                    <input type="submit" class="btn btn-success" value="Ajouter le musicien">
                </div>
            </div>
        </div><!--.form-group-->

	</form>

</div>
<?php

include_once '../inc/footer_admin.php';

?>