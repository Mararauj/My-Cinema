<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cinema</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <?php 
        require('cinema.php');
        if(isset($_GET['search'])){
            $film_h = false;
        }
        else {
            $film_h = true;
        }
    ?>
    <div style="display: flex; justify-content: space-between;">
        <div>
            <a href="index.php"><img style="width: 290px;" src="./img/cine.png" alt="logo cinema"></a>
        </div>
        <div>
            <form method="get" style="margin-top: 5%;">
                <select name="genre" style="width: 20%; margin-bottom: 5px;" class="form-select" aria-label="Default select example">
                    <option disabled selected value>Genre</option>
                    <?php
                        $res = $conn->query("SELECT id, name FROM genre");
                        while($s = $res->fetch()){
                            echo '<option value="' .$s['id']. '">'.$s['name'].'</option>';
                        }
                    ?>
                    
                </select>
                <select name="distributor" style="width: 40%; margin-bottom: 5px;" class="form-select" aria-label="Default select example">
                    <option disabled selected value>Distibuteur</option>
                    <?php
                        $res = $conn->query("SELECT id, name FROM distributor");
                        while($s = $res->fetch()){
                            echo '<option value="' .$s['id']. '">'.$s['name'].'</option>';
                        }
                    ?>
                </select>
                <input style="width: 45%; margin-bottom: 5px; border-radius: 5px;" name="search" type="text" id="search" placeholder="  Titre..">
                <div>
                    <button type="submit" name="submit" class="btn btn-secondary">Rechercher</button>
                </div>
            </form>
        </div>
    </div>
    <div class="container my-5">
        <?php if($film_h): ?>
            <div>
                <h2 style="color: white;">Films du jour</h2>
                <?php
                    $currentDate = date('Y-m-d');

                    $queryToday = "SELECT DISTINCT movie.title 
                                FROM movie 
                                JOIN movie_schedule ON movie.id = movie_schedule.id_movie
                                WHERE DATE(movie_schedule.date_begin) = '$currentDate'";

                    $resultToday = $conn->query($queryToday);

                    if ($resultToday->rowCount() > 0) {
                        echo '<div style="display: flex; justify-content: space-evenly;">';
                        while($s = $resultToday->fetch()) {
                            echo '<p style="color: white;">' . $s['title'] . '</p><br>';
                        }
                        echo '</div>';
                    } else {
                        echo '<h2 style="color: yellow; text-align: center;">⚠️ Aucun film aujourd\'hui</h2>';
                    }
                ?>
            </div>
            <div>
                <h2 style="color: white;">Films de la semaine</h2>
                <?php
                    $endDate = date('Y-m-d', strtotime('+7 days'));

                    $queryWeek = "SELECT DISTINCT movie.title 
                                FROM movie 
                                JOIN movie_schedule ON movie.id = movie_schedule.id_movie
                                WHERE DATE(movie_schedule.date_begin) BETWEEN '$currentDate' AND '$endDate'";

                    $resultWeek = $conn->query($queryWeek);

                    if ($resultWeek->rowCount() > 0) {
                        echo '<div style="display: flex; flex-wrap: wrap;">';
                        while($s = $resultWeek->fetch()) {
                            echo '<div class="col-md-6">';
                            echo '<p style="color: white;">' . $s['title'] . '</p>';
                            echo '</div>';
                        }
                        echo '</div>';
                    } else {
                        echo '<h2 style="color: yellow; text-align: center;">⚠️ Aucun film cette semaine</h2>';
                    }
                ?>
            </div>
        <?php endif; ?>

        <table class="table">
            <?php
            if(isset($_GET['search'])){
                echo '
                <form method="post">
                    <select name="p" id="page" onchange="this.form.submit()">
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
                $bla = $_GET['search'];
                $bla = trim(preg_replace('/[\t\n\r\s]+/', ' ', $bla));
                if($bla == '' || $bla == ' '){
                    $sea = 'Tous';
                }
                else{
                    $sea = $bla;
                }
                /*----------------------- Pagination---------------------------------*/
                $page = (!empty($_GET['page']) ? $_GET['page'] : 1);
                if(isset($_POST['p'])){
                    $perPage = $_POST['p'];
                }
                else{
                    $perPage = 10;
                }
                $debut = ($page-1)*$perPage;


                if(isset($_GET['genre']) && isset($_GET['distributor'])){
                    $distri = $_GET['distributor'];
                    $genre = $_GET['genre'];

                    $d = $conn->query("SELECT name FROM distributor WHERE id='$distri'");
                    $d = $d->fetch();
                    $g = $conn->query("SELECT name FROM genre WHERE id='$genre'");
                    $g = $g->fetch();
                    echo '<h5 style="color: white; margin-bottom: 5px; text-align: center;"> Genre : ' . $g[0] . '</h5>';
                    echo '<h5 style="color: white; margin-bottom: 5px; text-align: center;"> Distributor : ' . $d[0] . '</h5>';                    
                    echo '<h5 style="color: white; margin-bottom: 30px; text-align: center;"> Titre : ' . $sea . '</h5>';
                    $sql = $conn->query("SELECT COUNT(movie.title) AS nb FROM movie JOIN movie_genre ON movie.id=movie_genre.id_movie WHERE movie.title LIKE '%$bla%' AND movie_genre.id_genre='$genre' AND movie.id_distributor='$distri'");
                    $sql = $sql->fetch();
                    $nb = $sql['nb'];

                    $res = $conn->query("SELECT movie.title FROM movie JOIN movie_genre ON movie.id=movie_genre.id_movie WHERE movie.title LIKE '%$bla%' AND movie_genre.id_genre='$genre' AND movie.id_distributor='$distri' ORDER BY movie.title ASC LIMIT $debut,$perPage");
                }
                elseif(isset($_GET['genre'])){
                    $genre = $_GET['genre'];

                    $g = $conn->query("SELECT name FROM genre WHERE id='$genre'");
                    $g = $g->fetch();
                    echo '<h5 style="color: white; margin-bottom: 5px; text-align: center;"> Genre : ' . $g[0] . '</h5>';
                    echo '<h5 style="color: white; margin-bottom: 30px; text-align: center;"> Titre : ' . $sea . '</h5>';

                    $sql = $conn->query("SELECT COUNT(movie.title) AS nb FROM movie JOIN movie_genre ON movie.id=movie_genre.id_movie WHERE movie.title LIKE '%$bla%' AND movie_genre.id_genre='$genre'");
                    $sql = $sql->fetch();
                    $nb = $sql['nb'];

                    $res = $conn->query("SELECT movie.title FROM movie JOIN movie_genre ON movie.id=movie_genre.id_movie WHERE movie.title LIKE '%$bla%' AND movie_genre.id_genre='$genre' ORDER BY movie.title ASC LIMIT $debut,$perPage");
                    
                }
                elseif(isset($_GET['distributor'])){
                    $distri = $_GET['distributor'];

                    $d = $conn->query("SELECT name FROM distributor WHERE id='$distri'");
                    $d = $d->fetch();
                    echo '<h5 style="color: white; margin-bottom: 5px; text-align: center;"> Distributor : ' . $d[0] . '</h5>';
                    echo '<h5 style="color: white; margin-bottom: 30px; text-align: center;"> Titre : ' . $sea . '</h5>';
                    
                    $sql = $conn->query("SELECT COUNT(movie.title) AS nb FROM movie JOIN movie_genre ON movie.id=movie_genre.id_movie WHERE movie.title LIKE '%$bla%' AND movie.id_distributor='$distri'");
                    $sql = $sql->fetch();
                    $nb = $sql['nb'];

                    $res = $conn->query("SELECT movie.title FROM movie JOIN movie_genre ON movie.id=movie_genre.id_movie WHERE movie.title LIKE '%$bla%' AND movie.id_distributor='$distri' ORDER BY movie.title ASC LIMIT $debut,$perPage");
                }
                else{
                    echo '<h5 style="color: white; margin-bottom: 30px; text-align: center;"> Titre : ' . $sea . '</h5>';
                    $sql = $conn->query("SELECT COUNT(title) AS nb FROM movie WHERE title LIKE '%$bla%'");
                    $sql = $sql->fetch();
                    $nb = $sql['nb'];

                    $res = $conn->query("SELECT title FROM movie WHERE title LIKE '%$bla%' ORDER BY title ASC LIMIT $debut,$perPage");

                }
 
                $nbPage = ceil($nb/$perPage);
                
                if($res->rowCount() > 0){
                    $cc = 1;
                    echo '<div style="display: flex; justify-content: space-evenly;">';
                    while($s = $res->fetch()) {
                        if($cc == 1){
                            echo '<div>';
                        }
                        
                        echo '<p style="color: white;">' . $s['title'] . '</p><br>';
                        if($cc % intdiv($perPage,2) == 0 || $cc == $res->rowCount()){
                            echo '</div>';
                            if($cc != $res->rowCount()){
                                echo '<div>';
                            }
                        }
                        $cc++;
                    }
                    echo '</div>';
                    for($i=1;$i<=$nbPage;$i++){
                        if($i == $page){
                            echo "$i /";
                        }
                        else {
                            if(isset($_GET['genre']) && isset($_GET['distributor'])){
                                echo "<a style='color: red;' href=\"?search=$bla&genre=$genre&distributor=$distri&page=$i\">$i</a> /"; 
                            }
                            elseif(isset($_GET['genre'])){
                                echo "<a style='color: red;' href=\"?search=$bla&genre=$genre&page=$i\">$i</a> /";                                 
                            }
                            elseif(isset($_GET['distributor'])){
                                echo "<a style='color: red;' href=\"?search=$bla&distributor=$distri&page=$i\">$i</a> /"; 

                            }
                            else{
                                echo "<a style='color: red; text-align: center;' href=\"?search=$bla&page=$i\">$i</a> /";             
                            }
                        } 
                    }
                }
                else {
                    echo '<h2 style="color: yellow; text-align: center;">⚠️ Aucun film trouvé</h2>';
                }
                
            }
             
    ?>

        </table>
        <a style="color: grey; text-decoration:none" onclick="myFunction()" href="">Admin</a>
        <script>
        function myFunction() {
            let pass = prompt("Entrez le mot de passe admin:");
                if (pass == "root") {
                    open("admin.php");
                    window.close();
                }
        }   
        </script>
    </div>

    
</body>
</html>