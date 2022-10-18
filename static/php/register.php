<?php  
session_start();
$login_ad = $_SESSION['login'];
$prenom_ad = $_SESSION['prenom'];
$nom_ad = $_SESSION['nom'];
$id_ad = $_SESSION['id_emp']
?>

<?php  
if (isset($_POST['deconnect'])) {
    $_SESSION = array();
    session_destroy();
    header("Location:../index.html");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/font.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>Page | Inscription </title>
</head>
<script type="text/javascript" src="js/verifchamp.js"></script> 
<body>
  <style>
      body {
          background: url("img/chef.jpg") no-repeat center center fixed;
          background-size: cover;
      }
  </style>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand text-center" href="#">

          <img src="img/defar.png" alt="" width="40" height="40" class="d-inline-block align-text-top" style="border-radius: 25px;">  
        </a>
        <a class="navbar-brand" href ="admin.php">
          <b> Face Recognition |</b> ACCUEIL | <?php echo " ".$prenom_ad." ".$nom_ad;?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse d-flex justify-content-end" id="navbarSupportedContent">
            <form class="d-flex">
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-primary" type="submit">Search</button>
            </form>
        </div>
      </div>
  </nav>
  <div class="text-center pt-0">
      <div class="container">
          <div class="row">
            <div class="mx-auto col-lg-6 col-10">
              <h1 class="mt-0 text-success">
                Inscription
              </h1>
              <p class="mb-3">
                Bienvenue sur notre plateforme, Inscrire un nouveau employé.
              </p>
              <form class="text-left" method="POST" >
                <div class="form-group">
                  <label for="prenom">Prénom:</label> 
                  <input type="text" required class="form-control" id="prenom" name="prenom" placeholder="Votre prenom">
                </div>
                <div class="form-group">
                  <label for="nom">Nom:</label> 
                  <input type="text" required class="form-control" id="nom" placeholder="Votre nom" name="nom" >
                </div>
                <div class="form-group">
                  <label for="telephone">Téléphone:</label>
                  <input  type="tel" required="" class="form-control" id="tel" maxlength="10" name="tel" placeholder="771001111">
                </div>
                <div class="form-group">
                  <label for="telephone">Email:</label>
                  <input  type="email" required="" class="form-control" id="email"  name="email" placeholder="pseudo@exemple.com">
                </div>
                 <div class="form-group">
                  <label for="telephone">Type:</label>
                  <select class="form-control" id="profil" name="profil">
                    <option value="employee">employee</option>
                    <option value="admin">admin</option>
                  </select>
                </div>
                <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="password">Mot de passe</label>
                  <input type="password" required class="form-control" id="pass" name="pass" placeholder="Secret">
                </div>
                <div class="form-group col-md-6">
                  <label for="password2">Confirmation mot de passe:</label>
                  <input type="password" required class="form-control" id="pass2" name="pass2" placeholder="Confirmez SVP" >
                </div>
                </div>
                <div class="form-group">
                  <p style="color: red;" id="msg" class="text-right">
                    <?php  

                      include 'include/database.php';
                      global $bdd;

                        if (isset($_POST['inscription'])) {
                          
                          $Prenom = htmlspecialchars($_POST['prenom']);
                          $Nom = htmlspecialchars($_POST['nom']);
                          $Num_Tel = htmlspecialchars($_POST['tel']);
                          $Email = htmlspecialchars($_POST['email']);
                          $Mot_de_Passe = htmlspecialchars($_POST['pass']);
                          $Mot_de_Passe2 =htmlspecialchars($_POST['pass2']);
                          $profil =htmlspecialchars($_POST['profil']);

                          if(!empty($Prenom) AND !empty($Nom) AND !empty($Email) AND !empty($Num_Tel) AND !empty($Mot_de_Passe) AND !empty($Mot_de_Passe2) AND !empty($profil) ){

                            if (preg_match("/^7{1}[7-8]{1}[0-9]{7}/", $Num_Tel) AND !strlen($Num_Tel) != 9 ) {

                              if ($Mot_de_Passe == $Mot_de_Passe2) {

                                //verification d'existence du Login dans les table employe
                                $exist = $bdd->prepare("SELECT telephone FROM employe WHERE telephone = ? LIMIT 1");
                                $exist->execute(array($Num_Tel));
                                $etat_exist = $exist->rowCount();

                                if ($etat_exist==0) {
                                  $requetes = $bdd-> prepare("INSERT INTO `employe` (`prenom`, `nom`, `telephone`, `email`, `profil`, `mdp`) VALUES ('".$Prenom."', '".$Nom."', '".$Num_Tel."', '".$Email."', '".$profil."', '".$Mot_de_Passe."')");
                              
                                  $requetes -> execute(array($Prenom,$Nom,$Num_Tel,$Email,$profil,$Mot_de_Passe));
                                
                                  header("Location : admin.php");
                                }else{
                                  echo "comptes non créés car exixtent déjà";
                                }
                              }else{
                                echo "Le deux mot de passe ne sont pas identique ";
                              }
                            }else{
                              echo "Le format ".$Num_Tel." de telephone n'est pas respecter, Ex:771001100 ou 782002200";
                            }
                          }else{
                            echo " Veuillez remplir tout les champs";
                          } 
                        }
                          
                                
                    ?>    
                  </p>
                </div>
                <p class="text-right">
                  Déjà inscrit: <a href="admin.php"> ACCUEL </a>
                </p>
                <input type="submit" class="btn btn-success" id="inscription" name="inscription" onclick="formsend()" value="Inscription">
              </form>
           </div>
         </div>
      </div>
  </div>
</body>
</html>