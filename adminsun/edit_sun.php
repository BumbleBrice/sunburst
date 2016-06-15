<?php
if (!empty($_SESSION) && isset($_SESSION['user']['role'])){

    if ($_SESSION['user']['role'] != 'admin') {
        header('Location: index.php');
    }
    
} else {
    header('Location: ../index.php');
}

session_start();

// connection à la base
require_once '../inc/connect.php';

$post = array();
$error = array();

$errorUpdate  = false; // erreur lors de la mise à jour de la table
$displayErr   = false; 
$formValid    = false;
$sunburstExist    = false;


// vérification des paramètres GET et appel des champs sunburst correspondants
if(isset($_GET['id']) AND !empty($_GET['id']) AND is_numeric($_GET['id'])) {

    $idsunburst = intval($_GET['id']);

    // Prépare et execute la requète SQL pour récuperer notre sunburst de manière dynamique
      $res = $pdo->prepare('SELECT * FROM sunburst WHERE id = :id');
      $res->bindValue(':id' ,1  , PDO::PARAM_INT);
        
      if($res->execute()){
        // $editsunburst contient mon sunburst extrait de la pdo
        $editsunburst = $res->fetch(PDO::FETCH_ASSOC);
        if(!empty($editsunburst) && is_array($editsunburst)){ // Ici l'sunburst existe donc on fait le traitement nécessaire
            $sunburstExist = true; // Mon sunburst existe.. donc bon paramètre GET et requête SQL ok

            //nom du fichier existant
            // Si l'utilsateur existe, j'instancie la variable $idlink qui me permet de stcocker l'id sunburst dans le nom du fichier
        }
    }
}

// Si le formulaire est soumis et que $sunburstExist est vrai (donc qu'on a un sunburst)
if(!empty($_POST) && $sunburstExist == true) {
    foreach($_POST as $key => $value) {
        $post[$key] = trim(strip_tags($value));
    }

    if(!preg_match("#^[A-Z]+[a-zA-Z0-9À-ú\.:\!\?\&',\s-]{4,500}#", $post['desc_sun'])){    
        $error[] = 'La déscription du groupe SUNBURST doit comporter entre 5 et 500 caractères et commencer par une majuscule';
    }
        if(count($error) > 0) {
        $displayErr = true;

        $sunburstDesc = $post['desc_sun'];

    }
    else {

        //var_dump($post);

        // insertion de la news dans la table "news"
        $upd = $pdo->prepare('UPDATE sunburst SET desc_sun = :desc_sunb WHERE id = :idsunburst');

        // On assigne les valeurs associées au champs de la table (au dessus) aux valeurs du formulaire
        // On passe l'id de l'article pour ne mettre à jour que l'article en cours d'édition (clause WHERE).

        $upd->bindValue(':idsunburst' ,1  , PDO::PARAM_INT);
        $upd->bindValue(':desc_sunb',		$post['desc_sun'],  PDO::PARAM_STR);
   
    
        // Vue que la fonction "execute" retourne un booleen on peut si nécéssaire le mettre dans un if
        if($upd->execute()) { // execute : retourne un booleen -> true si pas de problème, false si souci.
            $formValid    = true;
            // On refait le SELECT pour afficher les infos à jour dans le formulaire
            // Puisque le premier SELECT est avant l'UPDATE
            $req = $pdo->prepare('SELECT * FROM sunburst WHERE id = :idsunburst');
            $req->bindParam(':idsunburst', $idsunburst, PDO::PARAM_INT);
            if($req->execute()) {
            // $editsunburst contient ma sunburst extrait de la pdo
            $editsunburst = $req->fetch(PDO::FETCH_ASSOC);
            }
        }
        else {
            $errorUpdate  = true; // Permettre d'afficher l'erreur
        }

    }
}
include_once '../inc/header_admin.php';
?>

    

    <div id="page-content-wrapper">
            <div class="container-fluid">
        


                <?php if($sunburstExist == false): ?>
                <div clas="col-md-12">   
                <!-- message d'erreur si problème url -->
                    <div class="alert alert-danger" role="alert">
                        <i class="fa fa-times fa-2x" aria-hidden="true"></i> Vous devez choisir un sunburst avant de le modifier
                    </div>
                    <a class="btn btn-default btn-md" href="view_sunbursts.php" role="button">Retour page administration</a>
                </div>
                <?php endif; ?>
                
                <?php if($errorUpdate): ?>
                <div clas="col-md-12">   
                <!-- message d'erreur si problème url -->
                    <div class="alert alert-danger" role="alert">
                        <i class="fa fa-times fa-2x" aria-hidden="true"></i> Problème lors de la mise à jour de votre profil ! <br /> <?php //echo print_r($res->errorInfo()); ?>
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
                    <h1>Modification de la déscription de la déscription du groupe Sunburst effectuée</h1>
                    <div class="alert alert-success" role="alert">
                        <i class="fa fa-check fa-2x" aria-hidden="true"></i> Votre déscription a bien été modifié.
                    </div>
                    <a class="btn btn-default btn-md" href="index.php" role="button">Retour page administration</a>
                </div>
                <?php endif; ?>


                <?php if($sunburstExist == true): ?>
                <div class="row">
                    <div class="col-md-12">
                    <h1>Edition de la déscription du groupe suburst :</h1>

                        <form class="form-horizontal" method="POST" enctype="multipart/form-data">
                            <fieldset>
                                <legend>Merci de renseigner les champs obligatoires ;-) </legend>

                                    <div class="form-group input-group">
                                        <span class="input-group-addon" id="basic-addon1">Déscription du groupe</span>
                                        <textarea id="content" name="desc_sun" rows="15" class="form-control input-md" ><?php echo $editsunburst['desc_sun']; ?></textarea>
                                    </div><br>

                                    <div class="form-group">
                                        <label class="col-md-2 control-label" for="singlebutton"></label>
                                        <div class="col-md-10">
                                            <input type="hidden" name="id" value="<?php echo $editsunburst['id']; ?>">
                                            <button type="submit" id="singlebutton" name="singlebutton" class="btn btn-primary">Modifier</button> <a href="index.php" class="btn btn-default">Ne rien changer et retourner à la page administration</a>
                                        </div>
                                    </div>
                            </fieldset>
                        </form>

                    </div>
                </div><!--row-->
            <?php endif; ?>

            </div><!--.container-fluid-->
        </div><!--#page-content-wrapper-->

    </div><!--#wrapper // start in sidebar.php -->
<?php

include_once '../inc/footer_admin.php';

?>
