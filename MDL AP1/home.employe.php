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

$bdd = new PDO("mysql:host=127.0.0.1;dbname=maison_des_ligues", "root", "root");

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modifier'])) {
  $numDemande = $_POST['numDemande'];
  $nouvelEtat = $_POST['nouvelEtat'];

  // Met à jour l'état de la demande dans la base de données
  $updateQuery = "UPDATE demande SET Idetat = (SELECT Idetat FROM etat WHERE Etatavancement = :nouvelEtat) WHERE Numdemande = :numDemande";
  $updateStmt = $bdd->prepare($updateQuery);
  $updateStmt->bindParam(':nouvelEtat', $nouvelEtat);
  $updateStmt->bindParam(':numDemande', $numDemande);
  $updateStmt->execute();

  // Rediriger vers la page actuelle pour afficher les changements
  header("Location: home.employe.php");
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
    <h1>Bienvenue, ceci est votre espace employé.</h1>
    <a href="logout.php">Déconnexion</a>
  </div>

  <?php
  // Récupérer l'identifiant de l'employé connecté
  $identifiant = $_SESSION['Identifiant'];

  $requete = $bdd->prepare("SELECT d.Numdemande, d.Idpriorite, d.Idetat, p.Niveaupriorite, d.Objetdemande, p.Idpriorite, e.Etatavancement FROM demande d INNER JOIN priorite p ON p.Idpriorite = d.Idpriorite INNER JOIN etat e ON e.Idetat = d.Idetat WHERE d.assignee = :assignee ORDER BY d.Numdemande");
  $requete->bindParam(':assignee', $identifiant);
  $requete->execute();
  ?>

  <table>
    <thead>
      <tr>
        <th>Numéro de demande</th>
        <th>ID de priorité</th>
        <th>Objet de la demande</th>
        <th>État de la demande</th>
        <th>Modifier l'état</th>
      </tr>
    </thead>
    <br>
    <?php
    while ($resultat = $requete->fetch()) {
      ?>
      <tr>
        <td><?php echo $resultat['Numdemande']; ?></td>
        <td><?php echo $resultat['Idpriorite']; ?></td>
        <td><?php echo $resultat['Objetdemande']; ?></td>
        <td><?php echo $resultat['Etatavancement']; ?></td>

        <td>
          <form method="POST" action="home.employe.php">
            <input type="hidden" name="numDemande" value="<?php echo $resultat['Numdemande']; ?>">
            <select name="nouvelEtat">
              <option value="en cours de réalisation" <?php if ($resultat['Etatavancement'] == 'en cours de réalisation') echo 'selected'; ?>>En cours de réalisation</option>
              <option value="en attente" <?php if ($resultat['Etatavancement'] == 'en attente') echo 'selected'; ?>>En attente</option>
              <option value="terminée" <?php if ($resultat['Etatavancement'] == 'terminée') echo 'selected'; ?>>Terminée</option>
            </select>
            <button type="submit" name="modifier">Modifier</button>
          </form>
        </td>
      </tr>
    <?php
    }
    ?>
  </table>

</body>
</html>
