<?php  
session_start();

$login_emp = $_SESSION['login_emp'];
$prenom_emp = $_SESSION['prenom_emp'];
$nom_emp = $_SESSION['nom_emp'];
$id_emp = $_SESSION['id_emp']
?>

<?php
if (isset($_POST['deconnect'])) {
    $_SESSION = array();
    session_destroy();
    header("Location:../index.html");
}
?>
<?php
    include 'include/database.php';
    global $bdd;

    // $allUser = $bdd->query('SELECT * FROM employe ORDER BY id_emp DESC');

    // $alUser = $bdd->query("SELECT prenom FROM employe WHERE prenom  ".$recherche." ORDER BY id_emp DESC");

   
?>
<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/font.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>Page | Employé</title>
</head>

<body>
    <style>
        body {
            background: url("../img/chef.jpg") no-repeat center center fixed;
            background-size: cover;
        }
    </style>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand text-center" href="#">
                <img src="../img/defar.png" alt="" width="40" height="40" class="d-inline-block align-text-top" style="border-radius: 25px;">
            </a>
            <a class="navbar-brand" href ="employe.php">
                <b> Face Recognition |</b> Chat privé | <?php echo " ".$prenom_emp." ".$nom_emp;?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse d-flex justify-content-end" id="navbarSupportedContent">
                <form class="d-flex" method="POST">
                    <input class="form-control me-2" type="search" placeholder="Ecrire a ..." aria-label="Search" name="recherche">
                    <button class="btn btn-outline-primary" type="submit" name="search">Search</button>
                </form>
            </div>  
        </div>
    </nav>
    <div class="container-fluid">
       <div class="row align-items-start">
           <div class="col">
               <form class="text-left" method="POST" >
                   <textarea name="boxchat" class="form-control" ></textarea>
                   <input type="submit" class="btn btn-primary" name="send" value="Notifier">
               </form>
               <section>
                    <?php
                    if (isset($_POST['search'])) {
                        if (!empty($_POST['recherche'])) {

                            $recherche = htmlspecialchars($_POST['recherche']);

                            $requser = $bdd->prepare("SELECT * FROM employe WHERE prenom = ? ORDER BY id_emp DESC");
                            $requser->execute(array($recherche));
                            $userexist = $requser->rowCount();
                            if ($userexist > 0) {
                                while ($user = $requser->fetch()) {
                                    echo"<p>".$user['prenom']." ".$user['nom']."</p>";
                                }
                            }else{
                                echo " Aucun utilisateur trouvee";
                            }  
                        }else{
                            echo "Veuiller entre le nom de la personne à rechercher";
                        }        
                    }
                    ?>
               </section>
           </div>
       </div>
    </div>
</body>
</html>