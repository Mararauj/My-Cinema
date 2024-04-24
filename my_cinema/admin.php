<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="back.css">
</head>
<body>
    <a style="color: grey; text-decoration:none" onclick="myFunction()" href="">Revenir à la page utilisateur</a>
    <script>
        function myFunction() {
            window.close();
            open("index.php");
        }
    </script>
    <h1 style="text-align: center;">Page Administrateur</h1>
    <div>
        <ul style="display: flex; list-style-type: none; justify-content: space-evenly;">
            <li><button onclick="f()">Utilisateurs</button></li>
            <li><button onclick="f1()">Films</button></li>
            <script>
            function f() {
                window.close();
                open("utilisateurs.php");
            }
            function f1() {
                window.close();
                open("films.php");
            }
    </script>
        </ul>
    </div>
    
    
</body>
</html>