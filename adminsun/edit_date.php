<?php
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
$dateExist    = false;



// vérification des paramètres GET et appel des champs date correspondants
if(isset($_GET['id']) AND !empty($_GET['id']) AND is_numeric($_GET['id'])) {

    $iddate = intval($_GET['id']);

    // Prépare et execute la requète SQL pour récuperer notre date de manière dynamique
    $req = $pdo->prepare('SELECT * FROM date_concert WHERE id = :iddate');
    $req->bindParam(':iddate', $iddate, PDO::PARAM_INT);
    if($req->execute()) {
        // $editdate contient mon date extrait de la pdo
        $editdate = $req->fetch(PDO::FETCH_ASSOC);
        if(!empty($editdate) && is_array($editdate)){ // Ici l'date existe donc on fait le traitement nécessaire
            $dateExist = true; // Mon date existe.. donc bon paramètre GET et requête SQL ok

            $dateC = $editdate['dateC'];
            $heureC = $editdate['heureC'];
            $dateplace = $editdate['place'];
            $dateadress = $editdate['adress'];
            $datecity = $editdate['city'];
            $datetarif = $editdate['tarif'];

        }
    }
}



// Si le formulaire est soumis et que $dateExist est vrai (donc qu'on a un date)
if(!empty($_POST) && $dateExist == true) {
    foreach($_POST as $key => $value) {
        $post[$key] = trim(strip_tags($value));
    }

    if(!preg_match("#^[a-zA-Z0-9À-ú\.:\!\?\&',\s-]{3,25}#", $post['dateC'])){    
        $errors[] = 'La date doit comporter entre 3 et 25 caractères';
    }
    if(!preg_match("#^[a-zA-Z0-9À-ú\.:\!\?\&',\s-]{3,10}#", $post['heureC'])){    
        $errors[] = 'L\heure du concert doit comporter entre 3 et 10 caractères';
    }
    if(!preg_match("#^[A-Z]+[a-zA-Z0-9À-ú\.:\!\?\&',\s-]{3,25}#", $post['place'])){    
        $errors[] = 'Le lieu du concert doit comporter entre 3 et 25 caractères et commencer par une majuscule';
    }
    if(!preg_match("#^[A-Z]+[a-zA-Z0-9À-ú\.:\!\?\&',\s-]{3,35}#", $post['adress'])){    
        $errors[] = 'L\'adresse du concert doit comporter entre 3 et 35 caractères et commencer par une majuscule';
    }
    if(!preg_match("#^[A-Z]+[a-zA-Z0-9À-ú\.:\!\?\&',\s-]{3,30}#", $post['city'])){    
        $errors[] = 'La ville du concert doit comporter entre 3 et 30 caractères et commencer par une majuscule';
    }
    if(!preg_match("#^[a-zA-Z0-9À-ú\.:\!\?\&',\s-]{3,5}#", $post['tarif'])){    
        $errors[] = 'Le tarif doit comporter entre 3 et 5 caractères';
    }

    if(count($error) > 0) {
        $displayErr = true;

        $dateC = $post['dateC'];
        $heureC = $post['heureC'];
        $dateplace = $post['place'];
        $dateadress = $post['adress'];
        $datecity = $post['city'];
        $datetarif = $post['tarif'];
    }
    else {

        //var_dump($post);

        // insertion de la news dans la table "news"
        $upd = $pdo->prepare('UPDATE date_concert SET dateC = :dateC, heureC = :heureC, place = :place, adress = :adress, city = :city, tarif = :tarif WHERE id = :iddate');

        // On assigne les valeurs associées au champs de la table (au dessus) aux valeurs du formulaire
        // On passe l'id de l'article pour ne mettre à jour que l'article en cours d'édition (clause WHERE).

        $upd->bindValue(':iddate',		$iddate,  PDO::PARAM_STR);
        $upd->bindValue(':dateC',		$post['dateC'],  PDO::PARAM_STR);
        $upd->bindValue(':heureC',		$post['heureC'],  PDO::PARAM_STR);
        $upd->bindValue(':place',		$post['place'], PDO::PARAM_STR);
        $upd->bindValue(':adress',		$post['adress'], PDO::PARAM_STR);
        $upd->bindValue(':city',		$post['city'], PDO::PARAM_STR);
        $upd->bindValue(':tarif',		$post['tarif'], PDO::PARAM_STR);
    
        // Vue que la fonction "execute" retourne un booleen on peut si nécéssaire le mettre dans un if
        if($upd->execute()) { // execute : retourne un booleen -> true si pas de problème, false si souci.
            $formValid    = true;
            // On refait le SELECT pour afficher les infos à jour dans le formulaire
            // Puisque le premier SELECT est avant l'UPDATE
            $req = $pdo->prepare('SELECT * FROM date_concert WHERE id = :iddate');
            $req->bindParam(':iddate', $iddate, PDO::PARAM_INT);
            if($req->execute()) {
            // $editdate contient ma date extrait de la pdo
                $editdate = $req->fetch(PDO::FETCH_ASSOC);
            }
        }
        else {
            $errorUpdate  = true; // Permettre d'afficher l'erreur
        }

    }
}
include_once '../inc/header_admin.php';
?>

    

    <div id="page-desc_dates-wrapper">
            <div class="container-fluid">
        


                <?php if($dateExist == false): ?>
                <div clas="col-md-12">   
                <!-- message d'erreur si problème url -->
                    <div class="alert alert-danger" role="alert">
                        <i class="fa fa-times fa-2x" aria-hidden="true"></i> Vous devez choisir une date avant de le modifier
                    </div>
                    <a class="btn btn-default btn-md" href="view_dates.php" role="button">Liste des dates</a>
                </div>
                <?php endif; ?>
                
                <?php if($errorUpdate): ?>
                <div clas="col-md-12">   
                <!-- message d'erreur si problème url -->
                    <div class="alert alert-danger" role="alert">
                        <i class="fa fa-times fa-2x" aria-hidden="true"></i> Problème lors de la mise à jour de la date ! <br /> <?php //echo print_r($res->errorInfo()); ?>
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
                    <h1>Modification du concert au <strong><?php echo $editdate['place']; ?></strong> a la date du : <strong><?php echo $editdate['dateC']; ?></strong> effectuée</h1>
                    <div class="alert alert-success" role="alert">
                        <i class="fa fa-check fa-2x" aria-hidden="true"></i> Votre date a bien été modifié.
                    </div>
                    <a class="btn btn-default btn-md" href="view_date.php" role="button">Liste des dates</a>
                </div>
                <?php endif; ?>


                <?php if($dateExist == true): ?>
                <div class="row">
                    <div class="col-md-12">
                    <h1>Edition du concert au : <strong><?php echo $editdate['place']; ?></strong> a la date du : <strong><?php echo $editdate['dateC']; ?></strong></h1>

                        <form class="form-horizontal" method="POST" enctype="multipart/form-data">
                            <fieldset>
                                <legend>Merci de renseigner les champs obligatoires ;-) </legend>

                                    <div class="form-group input-group">
                                      <span class="input-group-addon" id="basic-addon1">Date</span>
                                      <input type="text" class="form-control" name="dateC" value="<?=$dateC; ?>" aria-describedby="basic-addon1">
                                    </div><br>
                                    <div class="form-group input-group">
                                      <span class="input-group-addon" id="basic-addon1">Heure</span>
                                      <input type="text" class="form-control" name="heureC" value="<?=$heureC; ?>" aria-describedby="basic-addon1">
                                    </div><br>
                                    <div class="form-group input-group">
                                      <span class="input-group-addon" id="basic-addon1">Lieu</span>
                                      <input type="text" class="form-control" name="place" value="<?=$dateplace; ?>" aria-describedby="basic-addon1">
                                    </div><br>
                                    <div class="form-group input-group">
                                      <span class="input-group-addon" id="basic-addon1">Adresse</span>
                                      <input type="text" class="form-control" name="adress" value="<?=$dateadress; ?>" aria-describedby="basic-addon1">
                                    </div><br>
                                    <div class="form-group input-group">
                                      <span class="input-group-addon" id="basic-addon1">Ville</span>
                                      <input type="text" class="form-control" name="city" value="<?=$datecity; ?>" aria-describedby="basic-addon1">
                                    </div><br>
                                    <div class="form-group input-group">
                                      <span class="input-group-addon" id="basic-addon1">Tarif</span>
                                      <input type="text" class="form-control" name="tarif" value="<?=$datetarif; ?>" aria-describedby="basic-addon1">
                                    </div><br>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label" for="singlebutton"></label>
                                        <div class="col-md-10">
                                            <input type="hidden" name="id" value="<?php echo $editdate['id']; ?>">
                                            <button type="submit" id="singlebutton" name="singlebutton" class="btn btn-primary">Modifier</button> <a href="view_date.php" class="btn btn-default">Ne rien changer et retourner à la liste des dates</a>
                                        </div>
                                    </div>
                            </fieldset>
                        </form>

                    </div>
                </div><!--row-->
            <?php endif; ?>

            </div><!--.container-fluid-->
        </div><!--#page-desc_dates-wrapper-->

    </div><!--#wrapper // start in sidebar.php -->
<?php

include_once '../inc/footer_admin.php';

?>
<!-- Page d'edition d'un date  -->