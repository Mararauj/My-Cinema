<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Films</title>
    <link rel="stylesheet" href="back.css">
</head>
<body>
    <a style="color: grey; text-decoration:none" onclick="myFunction()" href="">Revenir à la page admin</a>
        <script>
            function myFunction() {
                window.close();
                open("admin.php");
            }
        </script>
    <h1 style="text-align: center; color: white; padding-bottom:40px;">Ajout de séances</h1>

<?php
    require('cinema.php');
    $res = $conn->query("SELECT title FROM movie ORDER BY title ASC");
?>

    
    <div class="d-grid gap-2 col-6 mx-auto">
        <button type="button"class="btn btn-light"><a class="text-dark" style="text-decoration: none;" href="ajout.php" onclick="popup()">Ajout de séances automatique</a></button>
        <script>
            function popup() {
                alert("Les séances ont bien été ajoutées !");
            }
        </script>
    </div>

    <h3 style="text-align: center; color: white; padding-top:40px;">Ajout de séance manuellement</h3>
    <form method="post">
        <div class="mb-3">
            <label for="titre" class="form-label">Titre du film</label>
            <select id="titre" class="form-select" name="titre" required>
                <option disabled selected value> ---- Choisissez un film ---- </option>
                <?php
                while($s = $res->fetch()) {
                    echo '<option>'.$s['title'].'</option>';
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="salle" class="form-label">Salle</label>
            <select id="salle" class="form-select" name="salle" required>
                <option disabled selected value> ---- Choisissez une salle ---- </option>
                <?php
                $res = $conn->query("SELECT name FROM room ORDER BY seats ASC");
                while($s = $res->fetch()) {
                    echo '<option>'.$s['name'].'</option>';
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="date">  
                Entrer la date:  
                <input type="date" id="date" name="date"required>  
            </label> 
        </div>
        <div class="mb-3">
            <label for="heure">Entrer l'heure:</label>
            <input type="time" id="heure" name="heure" required>
        </div>
        
        <button type="submit" class="btn btn-light">Ajouter</button>
        <?php
        if(isset($_POST['titre']) && isset($_POST['salle']) && $_POST['date']!='' && $_POST['heure']!=''){
            $titre = $_POST['titre'];
            $salle = $_POST['salle'];
            $date = $_POST['date'] . ' ' . $_POST['heure'] . ':00';
            $im = $conn->query('SELECT id FROM movie WHERE title="'.$titre.'";');
            $im= $im->fetch();
            $ir = $conn->query("SELECT id FROM room WHERE name='$salle'");
            $ir= $ir->fetch();
            $im = $im[0];
            $ir = $ir[0];

            $in = $conn->query("INSERT INTO movie_schedule(id_movie,id_room,date_begin) VALUES('$im','$ir','$date');");
            if($in){
                echo '<script type="text/javascript">window.alert("La séance a bien été ajoutée");</script>';
            }
            else{
                echo 'Erreur';
            }
        }
        ?>
    </form>
</body>
</html>