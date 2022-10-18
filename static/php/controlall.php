<?php
session_start();

	include 'include/database.php';
 	global $bdd;


			$Prenom = htmlspecialchars($_POST['Prenom']);
			$Nom = htmlspecialchars($_POST['Nom']);
			$Num_Tel = htmlspecialchars($_POST['Num_Tel']);
			$Email = htmlspecialchars($_POST['Email']);
			$Mot_de_Passe = htmlspecialchars($_POST['Mot_de_Passe']);
			$Mot_de_Passe2 =htmlspecialchars($_POST['Mot_de_Passe2']);
			$profil =htmlspecialchars($_POST['profil']);

				
	
			if(!empty($Prenom) AND !empty($Nom) AND !empty($Email) AND !empty($Num_Tel) AND !empty($Mot_de_Passe) AND !empty($Mot_de_Passe2) AND !empty($profil) ){

				if (preg_match("/^7{1}[7-8]{1}[0-9]{7}/", $Num_Tel) AND !strlen($Num_Tel) != 9 ) {

					if ($Mot_de_Passe == $Mot_de_Passe2) {

						//verification d'existence du Login dans les table employe
						$exist = $bdd->prepare("SELECT telephone FROM employe WHERE telephone = ? LIMIT 1");
						$exist->execute(array($Num_Tel));
						$etat_exist = $exist->rowCount();

						if ($etat_exist==0) {
							$requetes = $bdd-> prepare("INSERT INTO `employe` (`prenom`, `nom`, `telephone`, `email`, `profil`, `Mot_de_Passe`) VALUES ('".$Prenom."', '".$Nom."', '".$Num_Tel."', '".$Email."', '".$profil."', '".$Mot_de_Passe."')");
					
							$requetes -> execute(array($Prenom,$Nom,$Num_Tel,$Email,$Mot_de_Passe,$profil));
							echo "Vos comptes ont été crée avec succés id : ".$id_emp.', profil : '.$profil.', Login : '.$Num_Tel;
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
						
?>		