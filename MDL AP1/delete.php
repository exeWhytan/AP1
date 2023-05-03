<?php
// Connexion à la base de données
$bdd = new PDO("mysql:host=127.0.0.1;dbname=maison_des_ligues", "root", "root");

// Vérification si l'identifiant de la demande est passé en paramètre
if (isset($_GET['id'])) {
    $idDemande = $_GET['id'];

    // Requête de suppression de la demande
    $requete = $bdd->prepare("DELETE FROM demande WHERE Numdemande = :id");
    $requete->bindParam(':id', $idDemande);

    // Exécution de la requête
    if ($requete->execute()) {
        // Redirection vers la page d'accueil après la suppression
        header("Location: home.responsable.php");
        exit();
    } else {
        // En cas d'erreur lors de la suppression
        echo "Une erreur est survenue lors de la suppression de la demande.";
    }
} else {
    // Si l'identifiant de la demande n'est pas spécifié, redirection vers la page d'accueil
    header("Location: home.responsable.php");
    exit();
}
?>
