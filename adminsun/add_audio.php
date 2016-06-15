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

$post = array(); // Contiendra les données du formulaire nettoyées
$errors = array(); // contiendra nos éventuelles erreurs

$showErrors = false;
$success = false; 

$desc_mp3 = '';
$dirlink = "link-default.mp3";

$folder = '../audio/'; // création de la variable indiquant le chemin du répertoire destination pour les fichiers uploadés (important  : le slash à la fin de la chaine de caractère).
$maxSize = 100000000000 * 5; // 5Mo
echo var_dump($_FILES);
if(!empty($_FILES) && isset($_FILES['link'])) {

    if ($_FILES['link']['error'] == UPLOAD_ERR_OK AND $_FILES['link']['size'] <= $maxSize) {

        $nomFichier = $_FILES['link']['name']; // récupère le nom de mon fichier au sein de la superglobale $_FILES (tableau multi-dimentionnel)
        $tmpFichier = $_FILES['link']['tmp_name']; // Stockage temporaire du fichier au sein de la superglobale $_FILES (tableau multi-dimentionnel)
        
        $file = new finfo(); // Classe FileInfo
        $mimeType = $file->file($_FILES['link']['tmp_name'], FILEINFO_MIME_TYPE); // Retourne le VRAI mimeType

        $mimTypeOK = array('audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/x-wav');

        if (in_array($mimeType, $mimTypeOK)) { // in_array() permet de tester si la valeur de $mimeType est contenue dans le tableau $mimTypeOK
                    

            $newFileName = explode('.', $nomFichier);
            $fileExtension = end($newFileName); // Récupère la dernière entrée du tableau (créé avec explode) soit l'extension du fichier

            // nom du fichier link au format : link-id-timestamp.jpg
            $finalFileName = 'mp3-'.time().'.'.$fileExtension; // Le nom du fichier sera donc link-timestamp.jpg (time() retourne un timestamp à la seconde)


                if(move_uploaded_file($tmpFichier, $folder.$finalFileName)) { // move_uploaded_file()  retourne un booleen (true si le fichier a été envoyé et false si il y a une erreur)
                    // Ici je suis sûr que mon image est au bon endroit
                    $dirlink = $finalFileName;
                    
                }
                else {
                    // Permet d'assigner un link par défaut
                    $dirlink = "link-default.mp3";
                }
        } // if (in_array($mimeType, $mimTypeOK))

        else {
            $error[] = 'Ce type de fichier est interdit, mime type incorrect !';
        } 


    } // end if ($_FILES['link']['error'] == UPLOAD_ERR_OK AND $_FILES['link']['size'] <= $maxSize)
    else {
        $error[] = 'Merci de choisir un fichier son (uniquement au format mp3 ou wav) à uploader et ne dépassant pas 50Mo !';
    }
} // end if (!empty($_FILES) AND isset($_FILES['link'])

else {
    // Permet d'assigner l link par défaut si la photo n'en a aucun
    $dirlink = "link-default.mp3";
}

if (!empty($_POST)) {
	
	foreach ($_POST as $key => $value) { // Nettoyage des données
		$post[$key] = trim(strip_tags($value)); // récupération du _POST dans un tableau
	}
	
    //if(strlen($post['content']) < 2 ){ // on défini les propriétés de 'content'
    if(!preg_match("#^[a-zA-Z0-9À-ú\.:\!\?\&',\s-]{3,140}#", $post['desc_mp3'])){
        $errors[] = 'La photo doit comporter au minimum 3 et 140 caractères'; 
	}
	

    else { 
    	// Insertion dans la pdo 
    	$res = $pdo->prepare('INSERT INTO `mp3` (`desc_mp3`, `link`) VALUES (:desc_mp3, :linkmp3)');

       
        $res->bindValue(':desc_mp3', 	$post['desc_mp3'],	   PDO::PARAM_STR);
        $res->bindValue(':linkmp3',  	$dirlink,              PDO::PARAM_STR);
        
    
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


<h1 class="text-center">Ajouter un mp3</h1>
<br>


<div class="container">

<?php 
if($success){ // On affiche la réussite si tout fonctionne
    echo '<div class="alert alert-success" role="alert"> La photo a bien été créée ! </div>';
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
            <span class="input-group-addon" id="basic-addon1">Descriptif de la photo</span>
            <textarea id="content" name="desc_mp3" rows="5" class="form-control input-md" placeholder="Descriptif de la photo"><?=$desc_mp3;?></textarea>
        </div>
        <br>

        <div class="form-group">
            <div class="row">
                <div class="col-md-10">
                    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $maxSize; ?>">
                    <input type="file" class="filestyle" name="link" data-buttonName="btn-primary">
                </div>

                <div class="col-md-2">
                    <input type="submit" class="btn btn-success" value="Ajouter la musique">
                </div>
            </div>
        </div><!--.form-group-->

	</form>

</div>
<?php

include_once '../inc/footer_admin.php';

?>