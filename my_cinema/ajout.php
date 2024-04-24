<?php
require ('cinema.php');

function ajoutauto($conn) {
    $jours = 7;
    $sj = 15;
    $films = $conn->query('SELECT id, title FROM movie')->fetchAll(PDO::FETCH_ASSOC);
    $salles = $conn->query('SELECT id, name FROM room')->fetchAll(PDO::FETCH_ASSOC);

    $j = 1;
    $heures = array("09:00:00","09:30:00","10:00:00", "14:00:00", "18:30:00", "20:00:00", "21:00:00");

    for ($i = 0; $i < $jours; $i++) {
        $debut = date('Y-m-d', strtotime('+' . $i * $j . ' days'));

        foreach ($heures as $horaire) {
           
            $film = $films[array_rand($films)];
            $salle = $salles[array_rand($salles)];

            $conn->query("INSERT INTO movie_schedule(id_movie, id_room, date_begin) VALUES ('{$film['id']}', '{$salle['id']}', '{$debut} {$horaire}')");
        }
    }
}

ajoutauto($conn);
header("location:films.php");

?>
