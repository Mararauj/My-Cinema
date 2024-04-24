<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add</title>
    <link rel="stylesheet" href="back.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    
<h1 style="text-align: center; color: white;">Veuillez ajouter un abonnement :</h1>
<?php

require('cinema.php');
$id = $_GET['addid'];

$ln = $_GET['lastname'];
$fn = $_GET['firstname'];


echo '<p style="text-align: center; color: white;">Membre n°'.$id.'</p>';
echo '<p style="text-align: center; color: white;">Nom,prenom : '. $ln .' '. $fn .'</p>';
?>
<form method="post">
    <select class="form-select" name="abo" aria-label="Default select example">
            <option disabled selected value>----Veuillez choisir l'abonnement----</option>
            <option value="1">VIP</option>
            <option value="2">GOLD</option>
            <option value="3">Classic</option>
            <option value="4">Pass Day</option>
    </select>
    <button type="submit" name="button" class="btn btn-primary">Add</button>
    </form>

    <?php
        if(isset($_POST['abo'])){
            $ab = $_POST['abo'];
            $res = $conn->query("INSERT INTO membership (id_subscription, id_user, date_begin) VALUES('$ab', '$id',NOW())");
            
            if($res){
                echo "yes";
                header("location:utilisateurs.php?search=".$_GET['search']);
            }
            else {
                echo "erreur";
            }
            
        }
        if(isset($_POST['button'])){
            header("location:utilisateurs.php?search=".$_GET['search']);
        }
        
    ?>
</body>
</html>
