<?php session_start();
if (!empty($_SESSION) && isset($_SESSION['user']['role'])){

    if ($_SESSION['user']['role'] != 'admin') {
        header('Location: index.php');
    }
    
} else {
    header('Location: ../index.php');
}
require_once '../inc/connect.php';

$post = array();
$error = array();

$errorUpdate  = false; // erreur lors de la mise à jour de la table
$displayErr   = false; 
$formValid    = false;
$mp3Exist    = false;

$folder = '../audio/'; // création de la variable indiquant le chemin du répertoire destination pour les fichiers uploadés (important  : le slash à la fin de la chaine de caractère).
$maxSize = 1000000 * 5; // 5Mo


// vérification des paramètres GET et appel des champs user correspondants
if(isset($_GET['id']) AND !empty($_GET['id']) AND is_numeric($_GET['id'])) {

    $idMp3 = intval($_GET['id']);

    // Prépare et execute la requète SQL pour récuperer notre user de manière dynamique
    $req = $pdo->prepare('SELECT * FROM mp3 WHERE id = :idMp3');
    $req->bindParam(':idMp3', $idMp3, PDO::PARAM_INT);
    if($req->execute()) {
        // $editMp3 contient mon utilisateur extrait de la bdd
        $editMp3 = $req->fetch(PDO::FETCH_ASSOC);
        if(!empty($editMp3) && is_array($editMp3)){ // Ici l'utilisateur existe donc on fait le traitement nécessaire
            $mp3Exist = true; // Mon user existe.. donc bon paramètre GET et requête SQL ok

            // Si l'utilsateur existe, j'instancie la variable $idAvatar qui me permet de stcocker l'id user dans le nom du fichier
            $idAvatar = $editMp3['id'];
    /*        $editMp3 = $editMp3['desc_picture'];*/
        }
    }
}

//var_dump($_FILES);

// UPLOAD DE FICHIER : Contrôle de l'upload de fichier et de la supergloable $_FILES
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

            // nom du fichier avatar au format : user-id-timestamp.jpg
            $finalFileName = 'mp3-'.time().'.'.$fileExtension; // Le nom du fichier sera donc user-id-timestamp.jpg (time() retourne un timsestamp à la seconde)



                if(move_uploaded_file($tmpFichier, $folder.$finalFileName)) { // move_uploaded_file()  retourne un booleen (true si le fichier a été envoyé et false si il y a une erreur)
                    // Ici je suis sur que mon image est au bon endroit
                    $dirlink = $folder.$finalFileName;
                    
                    $success = 'Votre fichier a été uplaodé avec succés !';
                    $showSuccess = true;
                }
                else {
                    // Permet d'assigner un avatar par defaut
                    $dirlink = "audio/default.mp3";
                }
        } // if (in_array($mimeType, $mimTypeOK))

        else {
            $error[] = 'Le type de fichier est interdit mime type incorrect !';
        } 


    } // end if ($_FILES['picture']['error'] == UPLOAD_ERR_OK AND $_FILES['picture']['size'] <= $maxSize)
    else {
        $error[] = 'Merci de chosir un fichier image (uniquement au format .mp3 ou .wav) à uploader et ne dépassant pas 5Mo !';
    }
} // end if (!empty($_FILES) AND isset($_FILES['picture'])

else {
    // Permet d'assigner l'avatar par defaut si l'utilisateur n'en a aucun
    $dirlink = "../audio/mp3-default.jpg";
}


// Si le formulaire est soumis et que $userExist est vrai (donc qu'on a un utilisateur)
if(!empty($_POST) && $mp3Exist == true) {
    foreach($_POST as $key => $value) {
        $post[$key] = trim(strip_tags($value));
    }

    if(!preg_match("#^[a-zA-Z0-9À-ú\.:\!\?\&',\s-]{3,140}#", $post['desc_mp3'])){
        $errors[] = 'Le morceau doit comporter au minimum 3 et 140 caractères'; 
    }

    if(count($error) > 0) {
        $displayErr = true;
    }
    else {

        //var_dump($post);

        // insertion de la news dans la table "mp3"
        $upd = $pdo->prepare('UPDATE mp3 SET desc_mp3 = :desc_mp3, link = :linkmp3 WHERE id = :idMp3');

        // On assigne les valeurs associées au champs de la table (au dessus) aux valeurs du formulaire
        // On passe l'id de l'article pour ne mettre à jour que l'article en cours d'édition (clause WHERE).
        $upd->bindValue(':idMp3',       $idMp3,               PDO::PARAM_INT);
        $upd->bindValue(':desc_mp3',    $post['desc_mp3'],    PDO::PARAM_STR);
        $upd->bindValue(':linkmp3',     $dirlink,             PDO::PARAM_STR);

    
        // Vue que la fonction "execute" retourne un booleen on peut si nécéssaire le mettre dans un if
        if($upd->execute()) { // execute : retourne un booleen -> true si pas de problème, false si souci.
            $formValid    = true;
            // On refait le SELECT pour afficher les infos à jour dans le formulaire
            // Puisque le premier SELECT est avant l'UPDATE
            $req = $pdo->prepare('SELECT * FROM mp3 WHERE id = :idMp3');
            $req->bindParam(':idMp3', $idMp3, PDO::PARAM_INT);
            if($req->execute()) {
            // $editMp3 contient mon utilisateur extrait de la bdd
                $editMp3 = $req->fetch(PDO::FETCH_ASSOC);
            }
        }
        else {
            $errorUpdate  = true; // Permettre d'afficher l'erreur
        }

    }
}

include_once '../inc/header_admin.php';
?>

    

    <div id="page-desc_zicos-wrapper">
            <div class="container-fluid">
        


                <?php if($mp3Exist == false): ?>
                <div clas="col-md-12">   
                <!-- message d'erreur si problème url -->
                    <div class="alert alert-danger" role="alert">
                        <i class="fa fa-times fa-2x" aria-hidden="true"></i> Vous devez choisir un morceau avant de le modifier
                    </div>
                    <a class="btn btn-default btn-md" href="view_audio.php" role="button">Liste des morceaux</a>
                </div>
                <?php endif; ?>
                
                <?php if($errorUpdate): ?>
                <div clas="col-md-12">   
                <!-- message d'erreur si problème url -->
                    <div class="alert alert-danger" role="alert">
                        <i class="fa fa-times fa-2x" aria-hidden="true"></i> Problème lors de la mise à jour du morceau ! <br /> <?php //echo print_r($res->errorInfo()); ?>
                    </div>
                    <a class="btn btn-default btn-md" href="index.php" role="button">Page d'accueil</a>
                </div>
                <?php endif; ?>


                <?php if($displayErr): ?>
                <!-- affichage du tableau d'erreur $error si le formulaire est mal renseigné -->
                <div clas="col-md-12">
                    <div class="alert alert-danger" role="alert">
                        <i class="fa fa-times fa-2x" aria-hidden="true"></i> <?php echo implode('<br> <i class="fa fa-times fa-2x" aria-hidden="true"></i> ', $error); ?>
                    </div>                    
                </div>
                <?php endif; ?>


                <?php if($formValid): ?>
                <!-- message de confirmation après une modification de news -->
                <div clas="col-md-12">
                    <h1>Modification du morceau <strong><?php echo $editMp3['desc_mp3']; ?></strong> effectuée</h1>
                    <div class="alert alert-success" role="alert">
                        <i class="fa fa-check fa-2x" aria-hidden="true"></i> Votre morceau a bien été modifié.
                    </div>
                    <a class="btn btn-default btn-md" href="view_audio.php" role="button">Liste des morceaux</a>
                </div>
                <?php endif; ?>


                <?php if($mp3Exist == true): ?>
                <div class="row">
                    <div class="col-md-12">
                    <h1>Edition du morceau : <strong><?php echo $editMp3['desc_mp3']; ?></strong></h1>

                        <form class="form-horizontal" method="POST" enctype="multipart/form-data">
                            <fieldset>
                                <legend>Merci de renseigner les champs obligatoires ;-) </legend>

                                    
                                    <div class="form-group input-group">
                                        <span class="input-group-addon" id="basic-addon1">Déscription</span>
                                        <textarea id="desc_mp3" name="desc_mp3" rows="5" class="form-control input-md" ><?=$editMp3['desc_mp3']; ?></textarea>
                                    </div><br>
     
                                    <div class="form-group">
                                        <label class="col-md-2 control-label" for="mp3"></label> 
                                        <div class="col-md-10">
                                        <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $maxSize; ?>">
                                        <input type="file" class="filestyle" name="picture" data-buttonName="btn-primary">
                                        </div>
                                    </div><!--.form-group-->

                                    <div class="form-group">
                                        <label class="col-md-2 control-label" for="singlebutton"></label>
                                        <div class="col-md-10">
                                            <input type="hidden" name="id" value="<?php echo $editMp3['id']; ?>">
                                            <button type="submit" id="singlebutton" name="singlebutton" class="btn btn-primary">Modifier</button> <a href="view_audio.php" class="btn btn-default">Ne rien changer et retourner à la liste des morceaux</a>
                                        </div>
                                    </div>
                            </fieldset>
                        </form>

                    </div>
                </div><!--row-->
            <?php endif; ?>

            </div><!--.container-fluid-->
        </div><!--#page-desc_zicos-wrapper-->

    </div><!--#wrapper // start in sidebar.php -->
<?php

include_once '../inc/footer_admin.php';

?>
<!-- Page d'edition d'un musicien  -->