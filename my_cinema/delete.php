<?php
require('cinema.php');

if(isset($_GET['deleteid'])){
    $id = $_GET['deleteid'];

    $res = $conn->query("SET FOREIGN_KEY_CHECKS=0");
    $res = $conn->query("DELETE FROM membership WHERE id_user=$id");
    $res = $conn->query("SET FOREIGN_KEY_CHECKS=1");
    if($res){
        echo "yes";
        header("location:utilisateurs.php?search=".$_GET['search']);
    }
    else{
        echo "erreur";
    }
}

?>