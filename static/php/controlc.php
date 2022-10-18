<?php
session_start();

 include 'include/database.php';
 global $bdd;


       $Login = htmlspecialchars($_POST['Login']);
       $Mot_de_Passe = htmlspecialchars($_POST['Mot_de_Passe']);
       $Type = htmlspecialchars($_POST['Type']);
       $test = "Actif";
       
       if(!empty($Login) AND !empty($Mot_de_Passe)) {

          $requser = $bdd->prepare("SELECT * FROM utilisateur WHERE Login = ? AND Mot_de_Passe= ? AND Type= ? AND Etat_Type = ? LIMIT 1");
          $requser->execute(array($Login, $Mot_de_Passe, $Type, $test));
          $userexist = $requser->rowCount();

          if($userexist == 1) {
            
            $userinfo = $requser->fetch();
                
              if ($Type == "client") {
                $_SESSION['Login'] = $Login;
                echo"Vérifié, cliquez sur <a href='accueilClient.html'>Acceuil</a> et accéder à votre page d'accueil";
              }elseif ($Type == "prestataire") {
                $_SESSION['Login'] = $Login;
                
                echo"Vérifié, cliquez sur <a href='accueilGestionnaire.html'>Acceuil</a> et accéder à votre page d'accueil";
              }elseif ($Type == "gestionnaire") {
                $_SESSION['Login'] = $Login;
                
                echo"Vérifié, cliquez sur <a href='accueilGestionnaire.html'>Acceuil</a> et accéder à votre page d'accueil";
              }else{
                echo"YabaY GrOsSE ErrEUr";
              }
                  
          }else {
            echo"Ce compte n'exite pas ou est desactivé";
          }
        }else {
          echo"Veuillez remplir tous les champs !!!";
        }
?> 