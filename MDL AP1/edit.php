<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Initialiser la session
session_start();
// Vérifier si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
if (!isset($_SESSION["Fonction"])) {
  header("Location: login.php");
  exit();
}

$bdd = new PDO("mysql:host=127.0.0.1;dbname=maison_des_ligues", "root", "root");

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Récupérer les données du formulaire
  $numDemande = $_POST["numDemande"];
  $assignee = $_POST["assignee"];
  $priorite = $_POST["priorite"];

  // Met à jour l'assignation et la priorité de la demande dans la base de données
  $updateQuery = "UPDATE demande SET Idpriorite = :priorite, assignee = :assignee WHERE Numdemande = :numDemande";
  $updateStmt = $bdd->prepare($updateQuery);
  $updateStmt->bindParam(':priorite', $priorite);
  $updateStmt->bindParam(':assignee', $assignee);
  $updateStmt->bindParam(':numDemande', $numDemande);
  $updateStmt->execute();

  // Rediriger vers la page d'accueil responsable après la mise à jour
  header("Location: home.responsable.php");
  exit();
}

// Vérifier si un ID de demande a été passé en paramètre
if (isset($_GET["id"])) {
  $numDemande = $_GET["id"];

  // Récupérer les informations de la demande à modifier depuis la base de données
  $requeteDemande = $bdd->prepare("SELECT * FROM demande WHERE Numdemande = ?");
  $requeteDemande->execute([$numDemande]);
  $resultat = $requeteDemande->fetch();

  // Vérifier si la demande existe
  if (!$resultat) {
    // Rediriger vers la page d'accueil responsable si la demande n'existe pas
    header("Location: home.responsable.php");
    exit();
  }
} else {
  // Rediriger vers la page d'accueil responsable si aucun ID de demande n'a été passé
  header("Location: home.responsable.php");
  exit();
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

  <div class="success">
    <h1>Modifier la demande</h1>
    <a href="logout.php">Déconnexion</a>
  </div>

  <?php
  // Vérifier si le formulaire a été soumis
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $numDemande = $_POST["numDemande"];
    $assignee = $_POST["assignee"];
    $priorite = $_POST["priorite"];

    // Met à jour l'assignation et la priorité de la demande dans la base de données
    $updateQuery = "UPDATE demande SET Idetat = 3, assignee = :assignee, Idpriorite = :priorite WHERE Numdemande = :numDemande";
    $updateStmt = $bdd->prepare($updateQuery);
    $updateStmt->bindParam(':assignee', $assignee);
    $updateStmt->bindParam(':priorite', $priorite);
    $updateStmt->bindParam(':numDemande', $numDemande);
    $updateStmt->execute();

    // Rediriger vers la page d'accueil responsable après la mise à jour
    header("Location: home.responsable.php");
    exit();
  }
  ?>

  <form method="POST" action="edit.php">
    <input type="hidden" name="numDemande" value="<?php echo $numDemande; ?>">
    <label for="assignee">Assignée à :</label>
    <select name="assignee" id="assignee">
      <?php
      $requeteEmployes = $bdd->query("SELECT * FROM users WHERE Idrole = 2");
      while ($employe = $requeteEmployes->fetch()) {
        $selected = ($employe['Identifiant'] == $resultat['assignee']) ? 'selected' : '';
        echo "<option value='" . $employe['Identifiant'] . "' $selected>" . $employe['Prenom'] . " " . $employe['Nom'] . "</option>";
      }
      ?>
    </select>
    <br>
    <label for="priorite">Priorité :</label>
    <select name="priorite" id="priorite">
      <?php
      $requetePriorites = $bdd->query("SELECT * FROM priorite");
      while ($priorite = $requetePriorites->fetch()) {
        $selected = ($priorite['Idpriorite'] == $resultat['Idpriorite']) ? 'selected' : '';
        echo "<option value='" . $priorite['Idpriorite'] . "' $selected>" . $priorite['Niveaupriorite'] . "</option>";
      }
      ?>
    </select>
    <br>
    <button type="submit">Enregistrer</button>
  </form>

</body>
</html>
