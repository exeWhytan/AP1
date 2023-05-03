<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Initialiser la session
session_start();
// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
if (!isset($_SESSION["Fonction"])) {
  header("Location: login.php");
  exit();
}

// Se connecter à la base de données
$bdd = new PDO("mysql:host=127.0.0.1;dbname=maison_des_ligues", "root", "root");

// Récupérer les priorités de la base de données
$priorites = $bdd->query("SELECT * FROM priorite")->fetchAll(PDO::FETCH_ASSOC);

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Récupérer les valeurs du formulaire
  $objetdemande = $_POST["objetdemande"];
  $idpriorite = $_POST["idpriorite"];

  // Vérifier si l'objet de la demande est vide
  if (!empty($objetdemande)) {
    // Préparer la requête d'insertion
    $requete = $bdd->prepare("INSERT INTO demande (Objetdemande, Idetat, Idpriorite, assignee) VALUES (:objetdemande, :idetat, :idpriorite, :assignee)");

    // Définir les valeurs des paramètres de la requête
    $requete->bindParam(":objetdemande", $objetdemande);
    $requete->bindValue(":idetat", 1); // ID de l'état initial de la demande
    $requete->bindParam(":idpriorite", $idpriorite);
    $requete->bindValue(":assignee", "personne");

    // Exécuter la requête d'insertion
    $requete->execute();

    // Rediriger vers la page d'accueil de l'utilisateur
    header("Location: home.utilisateur.php");
    exit();
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="style.css" />
  <script type="text/javascript">
    // Mettez le code JQuery ici
  </script>
</head>
<body>
<div class="sucess">
  <h1>Bienvenue, ceci est votre espace utilisateur.</h1>
  <a href="logout.php">Déconnexion</a>
</div>

<h2>Créer une demande</h2>

<table>
  <tr>
    <th>Objet de la demande</th>
    <th>Priorité</th>
    <th>Action</th>
  </tr>
  <tr>
    <form method="post" action="">
      <td><input type="text" name="objetdemande" id="objetdemande" autocomplete="off" required></td>
      <td>
        <select name="idpriorite" id="idpriorite" required>
          <?php foreach ($priorites as $priorite) { ?>
            <option value="<?php echo $priorite['Idpriorite']; ?>"><?php echo $priorite['Niveaupriorite']; ?></option>
          <?php } ?>
        </select>
      </td>
      <td><input type="submit" value="Envoyer"></td>
    </form>
  </tr>
</table>

</body>
</html>
