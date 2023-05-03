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
    <h1>Bienvenue, ceci est votre espace responsable.</h1>
    <a href="logout.php">Déconnexion</a>
  </div>

  <?php
  $bdd = new PDO("mysql:host=127.0.0.1;dbname=maison_des_ligues", "root", "root");
  $requete = $bdd->query("SELECT d.Numdemande, d.Idpriorite, d.assignee, p.Niveaupriorite, d.Objetdemande, e.Etatavancement FROM demande d INNER JOIN priorite p ON p.Idpriorite = d.Idpriorite INNER JOIN etat e ON e.Idetat = d.Idetat WHERE d.Idpriorite = p.Idpriorite");
  ?>

  <table>
    <thead>
      <tr>
        <th>Numéro de demande</th>
        <th>ID de priorité</th>
        <th>Objet de la demande</th>
        <th>Etat de la demande</th>
        <th>Assignée à :</th>
        <th>Autre</th>
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
          <?php
          if ($resultat['assignee'] == '') {
            ?>
            <form method="POST" action="edit.php">
              <input type="hidden" name="numDemande" value="<?php echo $resultat['Numdemande']; ?>">
              <select name="assignee">
                <option value="">Sélectionnez un employé</option>
                <?php
                // Récupérer la liste des employés depuis la base de données
                $employesQuery = $bdd->query("SELECT * FROM employes");
                while ($employe = $employesQuery->fetch()) {
                  echo '<option value="' . $employe['id'] . '">' . $employe['nom'] . '</option>';
                }
                ?>
              </select>
              <button type="submit" name="assigner">Assigner</button>
            </form>
            <?php
          } else {
            echo $resultat['assignee'];
          }
          ?>
        </td>
        <td>
          <a href="edit.php?id=<?= $resultat['Numdemande'] ?>">Modifier</a>
          <a href="delete.php?id=<?= $resultat['Numdemande'] ?>">Supprimer</a>
        </td>
      </tr>
    <?php
    }
    ?>
  </table>

</body>
</html>
