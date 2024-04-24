<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique</title>
    <link rel="stylesheet" href="back.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<a style="color: grey; text-decoration:none" onclick="myFunction()" href="">Revenir en arrière</a>
<script>
    function myFunction() {
        window.close();
        open("utilisateurs.php?search=<?php echo $_GET['search']?>");
    }
</script>
<h1 style="text-align: center; color: white;">Historique du membre :</h1>

<?php
require('cinema.php');

$id = $_GET['historiqueid'];
$ln = $_GET['lastname'];
$fn = $_GET['firstname'];


echo '<p style="text-align: center; color: white;">Membre n°'.$id.'</p>';
echo '<p style="text-align: center; color: white;">Nom, prenom : '. $ln .' '. $fn .'</p>';
?>
<form method="post">
    <div class="mb-3">
        <label for="seance" style="color: white;" class="form-label">Séance</label>
        <select id="seance" class="form-select" name="seance" required>
            <option disabled selected value> ---- Choisissez la séance ---- </option>
            <?php
                $res = $conn->query("SELECT movie_schedule.id AS id, movie.title AS title, room.name AS room, LEFT(movie_schedule.date_begin,16) AS dat 
                FROM movie_schedule 
                JOIN movie ON movie_schedule.id_movie=movie.id
                JOIN room ON movie_schedule.id_room=room.id
                ORDER BY dat DESC");
                while($s = $res->fetch()) {
                    echo '<option>'.$s['id'] . ' : ' .$s['title']. ', ' . $s['room'] . ', ' . $s['dat'] .'</option>';
                }
            ?>
        </select>
    </div>
    <button type="submit" class="btn btn-info">Ajouter à l'historique</button>
</form>
<?php
if(isset($_POST['seance'])){
    $r = $conn->query("SELECT id FROM membership WHERE id_user='$id' ");
    $r = $r->fetch();
    $i = substr($_POST['seance'], 0, strpos($_POST['seance']," :"));
    $i = intval($i);
    $r = $conn->query("INSERT INTO membership_log(id_membership, id_session) VALUES($r[0],$i)");
    
}
echo '
<table style="--bs-table-bg:transparent" class="table table-dark table-sm">
    <thead>
    <tr>
        <th scope="col">Titre du film</th>
        <th scope="col">Salle</th>
        <th scope="col">Date et heure</th>
    </tr>
    </thead>';

    echo '
    <form method="post">
        <select class="float-end" name="p" id="page" onchange="this.form.submit()">
            <option>Page</option>';
        $n = 10;
        for($i=0;$i<=10;$i++){
            echo '<option value="'.$n . '">'. $n .'</option>';
            $n += 5;
        }

    echo '
        </select>
    </form>
    ';
    $page = (!empty($_GET['page']) ? $_GET['page'] : 1);
    if(isset($_POST['p'])){
        $perPage = $_POST['p'];
    }
    else{
        $perPage = 10;
    }
    $debut = ($page-1)*$perPage;

  $res = $conn->query("SELECT movie.title AS title, room.name AS rname, LEFT(movie_schedule.date_begin,16) AS dat FROM movie_schedule 
    JOIN room ON movie_schedule.id_room=room.id 
    JOIN movie ON movie_schedule.id_movie=movie.id 
    JOIN membership_log ON movie_schedule.id=membership_log.id_session
    JOIN membership ON membership_log.id_membership=membership.id
    WHERE membership.id_user='$id' 
    ORDER BY dat DESC LIMIT $debut, $perPage");
    $sql = $conn->query("SELECT COUNT(*) AS nb FROM movie_schedule 
    JOIN room ON movie_schedule.id_room=room.id 
    JOIN movie ON movie_schedule.id_movie=movie.id 
    JOIN membership_log ON movie_schedule.id=membership_log.id_session
    JOIN membership ON membership_log.id_membership=membership.id
    WHERE membership.id_user='$id'");
    $sql = $sql->fetch();
    $nb = $sql['nb'];

  while($s = $res->fetch()) {
        echo '<tbody>
                <tr>
                    <td>' . $s['title'] . '</td>
                    <td>' . $s['rname'] . '</td>
                    <td>' . $s['dat'] .'</td>
                </tr>';
    }
    echo '</tbody>
        </table>';
    $nbPage = ceil($nb / $perPage);
    echo '<div style="text-align: center; margin-top: 20px;">';
    for($i=1;$i<=$nbPage;$i++){
        
        if($i == $page){
            echo "$i /";
        }
        else{
            echo "<a style='color: red; margin-right: 5px;' href=\"?historiqueid=$id&search=&lastname=$ln&firstname=$fn&page=$i\">$i/</a>";
        }
    }
    echo '</div>';


 
?>
</body>
</html>
