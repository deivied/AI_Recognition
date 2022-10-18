<?php
session_start();
?>
<!DOCTYPE html>
<html>
  
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
  <link rel="stylesheet" href="../css/font.css">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" type="text/css" href="../css/login.css">
  <title>Page | Login</title>
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
        <a class="navbar-brand" href ="#">
          <b> Face Recognition |</b> CONNEXION
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
  <div class="container-fluid ">
    <div class="login-wrapper">
      <form action="" method="POST" class="form">
        <img src="../img/avatar.png" alt="">
        <h2>Login</h2>
        <div class="input-group">
          <input type="text" name="login" id="login" >
          <label for="Login">Login</label>
        </div>
        <div class="input-group">
          <input type="password" name="mdp" id="mdp" >
          <label for="loginPassword">Mot de passe</label>
        </div>
        <div class="form-group">
          <label for="profil" style="color:#ff652f;">Profil</label>
          <select class="form-control" id="profil" name="profil">
            <option value="employee">employee</option>
            <option value="admin">admin</option>
          </select>
        </div>
        <input type="submit" value="Login" name="connect" class="submit-btn">
        <a href="#forgot-pw" class="forgot-pw">Mot de passe oublié?</a>
        <p style="color:#ff652f;">
          <?php   
            include 'include/database.php';
            global $bdd;

            if (isset($_POST['connect'])) {                                      
              extract($_POST);
              $etat = "actif";
              if(!empty($login) AND !empty($mdp) AND !empty($profil)) {


                $requser = $bdd->prepare("SELECT * FROM employe WHERE telephone = ? AND mdp= ? AND profil= ? AND etat= ? LIMIT 1");
                $requser->execute(array($login, $mdp, $profil, $etat));
                $userexist = $requser->rowCount();


                  if($userexist == 1) {
                    $userinfo = $requser->fetch();

                    if ($profil == "admin") {
                      $_SESSION['telephone'] = $userinfo['telephone'];
                      $_SESSION['prenom'] = $userinfo['prenom'];
                      $_SESSION['nom'] = $userinfo['nom'];
                      $_SESSION['mdp'] = $userinfo['mdp'];
                      $_SESSION['id'] = $userinfo['id_emp'];

                      header("Location: admin.php");
                                                                                                  
                    }
                    if ($profil == "employee") {
                      $_SESSION['login_emp'] = $userinfo['telephone'];
                      $_SESSION['prenom_emp'] = $userinfo['prenom'];
                      $_SESSION['nom_emp'] = $userinfo['nom'];
                      $_SESSION['mdp_emp'] = $userinfo['mdp'];
                      $_SESSION['id_emp'] = $userinfo['id_emp'];

                      header("Location: employe.php");                                  
                    }else{
                      echo "Ce type de compte n'existe pas : agent ou admin";
                    } 
                  }else{
                    echo "Ce compte n'exite pas ou est désactivé verifier encore";
                  }
              }else{
                echo "Veuiller remplir tous les champs";                                                    
              }
            }   
          ?>
        </p>
      </form>

      <div id="forgot-pw">
        <form action="" class="form">
          <img src="img/avatar.png" alt="">
          
          <h2>Changer Mot de Passe</h2>
          <div class="input-group">
            <input type="email" name="email" id="email" required>
            <label for="email">Email</label>
          </div>
          <input type="button" value="Submit" name="submit" class="submit-btn">
        </form>
      </div>
    </div>
  </div>
</body>
</html>