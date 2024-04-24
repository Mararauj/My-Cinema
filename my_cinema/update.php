<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update</title>
    <link rel="stylesheet" href="back.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    
<h1 style="text-align: center; color: white;">Veuillez selectionner l'abonnement :</h1>
<?php

require('cinema.php');
$id = $_GET['updateid'];

$ln = $_GET['lastname'];
$fn = $_GET['firstname'];


echo '<p style="text-align: center; color: white;">Membre n°'.$id.'</p>';
echo '<p style="text-align: center; color: white;">Nom,prenom : '. $ln .' '. $fn .'</p>';
?>
<form method="post">
    <select class="form-select" name="abo" aria-label="Default select example">

    <?php
        
        $res = $conn->query("SELECT id_subscription AS sub FROM membership WHERE id_user='$id'");
        $s = $res->fetch();
        $s = $s['sub'];

        if($s==1){
            echo '
    
            <option disabled selected value>VIP</option>
            <option value="2">GOLD</option>
            <option value="3">Classic</option>
            <option value="4">Pass Day</option>
        
        ';
        }
        elseif($s==2){
            echo '
        
            <option disabled selected value>GOLD</option>
            <option value="1">VIP</option>
            <option value="3">Classic</option>
            <option value="4">Pass Day</option>
        
        ';

        }
        elseif($s==3){
            echo '
            <option disabled selected value>CLASSIC</option>
            <option value="1">VIP</option>
            <option value="2">GOLD</option>
            <option value="4">Pass Day</option>
       
        ';
        }
        else{
            echo '
            <option disabled selected value>Pass Day</option>
            <option value="1">VIP</option>
            <option value="2">GOLD</option>
            <option value="3">Classic</option>
        
        ';

        }
    ?>
    </select>
    <button type="submit" name="button" class="btn btn-warning">Update</button>
    </form>
    <?php
        if(isset($_POST['abo'])){
            $ab = $_POST['abo'];
            $res = $conn->query("UPDATE membership SET id_subscription='$ab', date_begin=NOW() WHERE id_user='$id'");
            
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
