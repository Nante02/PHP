<?php 
require "vendor/autoload.php";
$serverName = "localhost";
$userName = "root";
$password = "";
$idRemove;
$idUpdate;
$formUpdate = '<form action="" method="post">
<div class="form-element" style="margin : 10px;"
    <label for="NomModif">Nom : </label>
    <input type="text" name="NomModif" id="NomModif" placeholder="Nom" required>
</div>
<div class="form-element" style="margin : 10px;">
    <label for="PrenomModif">Prénom : </label>
    <input type="text" name="PrenomModif" id="PrenomModif" placeholder="Prénom" required>
</div>
<div class="form-element" style="margin : 10px;">
    <label for="MoyenneModif">Moyenne : </label>
    <input type="text" name="MoyenneModif" id="MoyenneModif" placeholder="Moyenne" required>
</div>
<div class="boutton" style="margin : 10px;">
    <input type="submit" name = "modifier" value="Enregistrer">
</div>
</form>';

try {
    // connection par PDO (language:host=nomDuServer;dbname=nomDB,userName,pass)
    $conn = new PDO("mysql:host=$serverName;dbname=test", $userName, $password);
    // $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOExeption $e) {
    echo "Connection failed , Erreur : " . $e->getMessage();
}
$sql = "SELECT * FROM note";
$result = $conn->query($sql);
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercice sur PHP mysql</title>
    <style>
       
    </style>
</head>
<body>
    <form action="" method="post">
        <div class="form-element" style="margin : 10px;">
            <label for="Nom">Nom : </label>
            <input type="text" name="Nom" id="Nom" placeholder="Nom" required>
        </div>
        <div class="form-element" style="margin : 10px;">
            <label for="Prenom">Prénom : </label>
            <input type="text" name="Prenom" id="Prenom" placeholder="Prénom" required>
        </div>
        <div class="form-element" style="margin : 10px;">
            <label for="Moyenne">Moyenne : </label>
            <input type="text" name="Moyenne" id="Moyenne" placeholder="Moyenne" required>
        </div>
        <div class="boutton" style="margin : 10px;">
            <input type="submit" name = "submit" value="Enregistrer">
        </div>
    </form>
    <!-- //////////////////////////////// -->
    <?php
        if (isset($_POST["submit"])){
            $nom = $_POST["Nom"];
            $prenom = $_POST["Prenom"];
            $moyenne = $_POST["Moyenne"];
            echo $nom.$prenom.$moyenne;

            $insersion = $conn->prepare("INSERT INTO note(Nom,Prenom,Moyenne) VALUE (:nom,:prenom,:moyenne) ");
            $insersion -> execute(array(
                "nom" => $nom,
                "prenom" => $prenom,
                "moyenne" => $moyenne
            ));
            header("Location: index.php");

        }
    ?>
    <!-- ///////////////////////////////// -->
    <hr>
    <h1>Affichage de donnée</h1>
    <table border= "1px">
        <thead>
            <tr>
                <td>ID</td>
                <td>NOM</td>
                <td>PRENOM</td>
                <td>MOYENNE</td>
            </tr>
        </thead>
        <tbody>
            <?php
                while ($row = $result->fetch()){
            ?>
            <tr>
                <td><?php echo $row["id"];?></td>
                <td><?php echo $row["Nom"];?></td>
                <td><?php echo $row["Prenom"];?></td>
                <td><?php echo $row["Moyenne"];?></td>
                <td>
                    <form action="" method="get">
                    <!-- <input type="submit" value="supp" name=<?php echo '"'.$row["id"].'"'?> style="background-color : red;"> -->
                    <!-- <input type="submit" name="" value="modif"> -->
                    <button type="submit" value = <?php echo '"'.$row["id"].'"'?> name="supp" style="background-color : red;"> suppr</button>
                    <button type="submit" value = <?php echo '"'.$row["id"].'"'?> name="modif" style="background-color : yellow;"> modif</button>    
                </form>
                </td>
            </tr>
            <!-- /////////////////////////////////////////// -->
                <?php 
                }
                if (isset($_GET["supp"])){
                    $idRemove = $_GET["supp"];
                    $conn->exec("DELETE FROM note WHERE id= $idRemove");
                    header("Location :index.php");
                    
                }?>
                <!-- /////////////////////////////////////////// -->
        </tbody>
    </table>
    <?php
    if (isset($_GET["modif"])){
        $idUpdate = $_GET["modif"];
        echo $formUpdate;
        if (isset($_POST["modifier"])){
            $nom = $_POST["NomModif"];
            $prenom = $_POST["PrenomModif"];
            $moyenne = $_POST["MoyenneModif"];
            echo $nom.$prenom.$moyenne;

            $insersion = $conn->prepare("UPDATE note SET Nom = :nom, Prenom = :prenom, Moyenne = :moyenne WHERE id = $idUpdate ");
            $insersion -> execute(array(
                "nom" => $nom,
                "prenom" => $prenom,
                "moyenne" => $moyenne
            ));
            header("Location: index.php");

        }
    }
    ?>
</body>
</html>