<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilisateurs</title>
    <link rel="stylesheet" href="back.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>
<body>
    <?php 
        require('cinema.php');
    ?>
    <a style="color: grey; text-decoration:none" onclick="myFunction()" href="">Revenir à la page admin</a>
        <script>
            function myFunction() {
                window.close();
                open("admin.php");
            }
        </script>
    <h1 style="text-align: center; color: white;">Utilisateurs</h1>
    <form style="color: white; text-align: center;" method="get" >
        <label for="search">Rechercher un utilisateur :</label>
        <input type="text" name="search" id="search" placeholder="Recherche...">
        <input type="submit" value="Rechercher">
    </form>
    
    <table style="--bs-table-bg:transparent" class="table table-dark table-sm">
   
  
    <?php
            if(isset($_GET['search'])){
                echo '
                <form method="post">
                    <select name="p" id="page" onchange="this.form.submit()">
                        <option>Page</option>';
                    $n = 10;
                    for($i=0;$i<=10;$i++){
                        $selected = ($perPage == $n) ? 'selected' : '';
                        echo '<option value="' . $n . '" ' . $selected . '>' . $n . '</option>';
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

                $bla = $_GET['search'];
                $bla = trim(preg_replace('/[\t\n\r\s]+/', ' ', $bla));
                $res = $conn->query("SELECT LEFT(user.birthdate, 10) AS naiss,user.id AS id,user.firstname AS firstname,user.lastname AS lastname, subscription.name AS subscription, LEFT(membership.date_begin , 10) AS dat FROM user 
                        LEFT JOIN membership ON
                        user.id = membership.id_user
                        LEFT JOIN subscription ON
                        subscription.id = membership.id_subscription
                        WHERE user.firstname LIKE '%$bla%' OR user.lastname LIKE '%$bla%' OR CONCAT(user.firstname,' ',user.lastname) LIKE '%$bla%' OR CONCAT(user.lastname,' ',user.firstname) LIKE '%$bla%' LIMIT $debut, $perPage");
                $sql = $conn->query("SELECT COUNT(*) AS nb FROM user 
                LEFT JOIN membership ON
                user.id = membership.id_user
                LEFT JOIN subscription ON
                subscription.id = membership.id_subscription
                WHERE user.firstname LIKE '%$bla%' OR user.lastname LIKE '%$bla%' OR CONCAT(user.firstname,' ',user.lastname) LIKE '%$bla%' OR CONCAT(user.lastname,' ',user.firstname) LIKE '%$bla%'");
                $sql = $sql->fetch();
                $nb = $sql['nb'];
                if($bla == ''){
                    echo '<h3 style="color: white; margin-bottom: 30px; margin-top : 30px; text-align: center;">Tous les utilisateurs recherchés</h3>'; 
                }
                else{
                    echo '<h3 style="color: white; margin-bottom: 30px; margin-top : 30px; text-align: center;"> Utilisateur recherché : ' . $bla . '</h3>'; 
                }
                
                if($res->rowCount() > 0){
                    echo '
                    <thead>
                    <tr>
                        <th scope="col">Identifiant</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Prenom</th>
                        <th scope="col">Date de naissance</th>
                        <th scope="col">Abonnement</th>
                        <th scope="col">Depuis</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>';
                    while($s = $res->fetch()) {
                        if($s['subscription'] == null){
                            $s['subscription'] = "Aucun abonnement";
                            $b = '<button class="btn btn-primary"><a class="text-dark" style="text-decoration: none;" href="add.php?addid=' . $s['id'] . '&search=' . $_GET['search'] . '&lastname= ' . $s['lastname'] . '&firstname=' . $s['firstname'] . '">Add</a></button>';
                        }
                        else{
                            $b = '<button class="btn btn-secondary"><a class="text-dark" style="text-decoration: none;" href="historique.php?historiqueid=' . $s['id'] . '&search=' . $_GET['search'] . '&lastname= ' . $s['lastname'] . '&firstname=' . $s['firstname'] . '">Historique</a></button>
                                    <button class="btn btn-warning"><a class="text-dark" style="text-decoration: none;" href="update.php?updateid=' . $s['id'] . '&search=' . $_GET['search'] . '&lastname= ' . $s['lastname'] . '&firstname=' . $s['firstname'] . '">Update</a></button>
                                    <button class="btn btn-danger"><a class="text-dark" style="text-decoration: none;" href="delete.php?deleteid=' . $s['id'] . '&search=' . $_GET['search'] . '">Delete</a></button>';
                        }
                        echo '
                            <tr>
                                <td>' . $s['id'] . '</td>
                                <td>' . $s['lastname'] . '</td>
                                <td>' . $s['firstname'] .'</td>
                                <td>' . $s['naiss'] .'</td>
                                <td>' . $s['subscription'] .'</td>
                                <td>' . $s['dat'] .'</td>
                                <td>
                                    ' . $b . '
                                <td>

                            </tr>';
                    }
                    echo '</tbody></table>';
                    $nbPage = ceil($nb / $perPage);
                    echo '<div style="text-align: center; margin-top: 20px;">';
                    for($i=1;$i<=$nbPage;$i++){
                        
                        if($i == $page){
                            echo "$i /";
                        }
                        else{
                            echo "<a style='color: red; margin-right: 5px;' href=\"?search=$bla&page=$i\">$i/</a>";
                        }
                    }
                    echo '</div>';
                }
                else {
                    echo '<h2 style="color: red; text-align: center; padding-top: 50px;">⚠️ Aucun utilisateur trouvé</h2>';
                    }
                
            }
    ?>

</body>
</html>