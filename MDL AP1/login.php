<?php
echo "*************POST**********************";
echo"<br>";
if($_POST) {
    echo 'Contenu de la variable $_POST : >';
    print_r($_POST);
}

/*************************CONNEXION A LA BDD*************************************** */

error_reporting(E_ALL);
ini_set("display_errors", 1);

$host = 'localhost';
$db   = 'maison_des_ligues';
$user = 'root';
$pass = 'root';
$dsn = "mysql:host=$host;dbname=$db";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} 
catch (\PDOException $e) {
    print"ERREUR:".$e;
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
/*************************PHASE DE CONNEXION******************************************** */

if (extension_loaded ('PDO')) {
echo 'PDO OK'; 
} else {
echo 'PDO KO'; 
}
$stmt = $pdo->prepare("SELECT count(*) FROM users WHERE Identifiant=? AND Password=?");
$stmt->execute(array($_POST['Identifiant'],$_POST['Password'])); 


echo"resultat de la requete:"."<br> ";
while ($row = $stmt->fetch()) 
    {
        //print_r($row);
        //echo $row['count()'];
    
        if ($row['count(*)']==0)
        {
            print_r("authentification refusé"."<br> ");
        }
        else 
        {
            
            print_r("authentification accepté"."<br> "); 
            $stmt2 = $pdo->prepare("SELECT r.Fonction FROM users u INNER JOIN role r ON u.Idrole=r.Idrole WHERE Identifiant=? AND Password=?");
            $stmt2->execute(array($_POST['Identifiant'],$_POST['Password']));
            $row = $stmt2->fetch();
            print_r("Bonjour chère ".$row['Fonction']); 
            session_start();


/*************************REDIRECTION VERS L'ESPACE CORRESPONDANT*************************************** */

            $_SESSION["Fonction"]="employe"; //creation de la session employé
            $_SESSION["Fonction"] = "responsable"; // creation de la session responsable 
            $_SESSION["Fonction"] = "utilisateur"; // creation de la session utilisateur
            $_SESSION['Identifiant'] = $_POST['Identifiant']; // Enregistrer l'identifiant de l'utilisateur dans la session
            
      if ($row['Fonction']=="employe") { // si le role de la personne est employé rediriger vers employé
       header("Location: home.employe.php");
      }
      elseif ($row['Fonction']=="utilisateur") {// si le role de la utilisateur est employé rediriger vers utilisateur
       header("Location:home.utilisateur.php");
             $_SESSION['Identifiant'] = $_POST['Identifiant']; // Enregistrer l'identifiant de l'utilisateur dans la session
       }
       else {
        header("Location:home.responsable.php"); // si la personne est ni un employé , ni un utilisateur , rediriger vers l'espace responsable
       }
      
    }
}

?>