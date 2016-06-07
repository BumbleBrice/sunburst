<?php 
require_once '../inc/connect.php';

include_once '../inc/header_admin.php';

$post = array(); // Contiendra les données du formulaire nettoyées
$errors = array(); // contiendra nos éventuelles erreurs

$showErrors = false;
$success = false; 

$dateC = '';
$heureC = '';
$place = '';
$adress = '';
$city = '';
$tarif = '';


if (!empty($_POST)) {
    
    foreach ($_POST as $key => $value) { // Nettoyage des données
        $post[$key] = trim(strip_tags($value)); // récupération du _POST dans un tableau
    }
    //if(strlen($post['nickname']) < 2 || strlen($post['nickname']) > 50){ // on définit les propriétés de 'nickname'
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
    else { 
        // Insertion dans la pdo 
        $res = $pdo->prepare('INSERT INTO `date_concert` (`dateC`, `heureC`, `place`, `adress`, `city`, `tarif`) VALUES (:dateC, :heureC, :place, :adress, :city, :tarif)');

        $res->bindValue(':dateC',       $post['dateC'],  PDO::PARAM_STR);
        $res->bindValue(':heureC',      $post['heureC'],  PDO::PARAM_STR);
        $res->bindValue(':place',       $post['place'], PDO::PARAM_STR);
        $res->bindValue(':adress',      $post['adress'], PDO::PARAM_STR);
        $res->bindValue(':city',        $post['city'], PDO::PARAM_STR);
        $res->bindValue(':tarif',       $post['tarif'], PDO::PARAM_STR);

        
    
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


<h1 class="text-center">Ajouter une date</h1>
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

        <div class="form-group input-group">
          <span class="input-group-addon" id="basic-addon1">Date</span>
          <input type="text" class="form-control" name="dateC" placeholder="Date du concert" aria-describedby="basic-addon1">
        </div><br>
        <div class="form-group input-group">
          <span class="input-group-addon" id="basic-addon1">Heure</span>
          <input type="text" class="form-control" name="heureC" placeholder="Heure du début du concert" aria-describedby="basic-addon1">
        </div><br>
        <div class="form-group input-group">
          <span class="input-group-addon" id="basic-addon1">Lieu</span>
          <input type="text" class="form-control" name="place" placeholder="Nom de la salle de concert" aria-describedby="basic-addon1">
        </div><br>
        <div class="form-group input-group">
          <span class="input-group-addon" id="basic-addon1">Adresse</span>
          <input type="text" class="form-control" name="adress" placeholder="Adresse du concert" aria-describedby="basic-addon1">
        </div><br>
        <div class="form-group input-group">
          <span class="input-group-addon" id="basic-addon1">Ville</span>
          <input type="text" class="form-control" name="city" placeholder="Ville du concert" aria-describedby="basic-addon1">
        </div><br>
        <div class="form-group input-group">
          <span class="input-group-addon" id="basic-addon1">Tarif</span>
          <input type="text" class="form-control" name="tarif" placeholder="Veuillez indiquez le tarif en chiffre uniquement" aria-describedby="basic-addon1">
        </div><br>
            <input type="submit" class="btn btn-success" value="Ajouter la date">
        </form> 
  

    </form>

</div>
<?php

include_once '../inc/footer_admin.php';

?>