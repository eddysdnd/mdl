<div id="menu">
     <div id="infosUtil">
    
        <h2>
    
</h2>
    
      </div>  
        <ul id="menuList">

                        <?php
                       if($_SESSION['statut'] == "visiteur"){
                           
                               ?>
            			<li >
                            
				  Visiteur :<br>
				<?php echo $_SESSION['prenom']."  ".$_SESSION['nom']  ?>
			</li>
           <li class="smenu">
              <a href="index.php?uc=gererFrais&action=saisirFrais" title="Saisie fiche de frais ">Saisie fiche de frais</a>
           </li>
           <li class="smenu">
              <a href="index.php?uc=etatFrais&action=selectionnerMois" title="Consultation de mes fiches de frais">Mes fiches de frais</a>
           </li>
 	   <li class="smenu">
              <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
           </li>
           <?php
                       }
                       elseif ($_SESSION['statut'] == "comptable")
                       {
           ?>
           		<li >
                            
				  Comptable :<br>
				<?php echo $_SESSION['prenom']."  ".$_SESSION['nom']  ?>
			</li>
           <li class="smenu">
              <a href="index.php?uc=etatFrais&action=validerFrais" title="Saisie fiche de frais ">Valider Fiche-frais</a>
           </li>
           <li class="smenu">
              <a href="index.php?uc=etatFrais&action=suiviFrais" title="Consultation de mes fiches de frais">Suivi du remboursement</a>
           </li>
 	   <li class="smenu">
              <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
           </li>
           <?php
                       }
					   else
					   {
           ?>
						
		              		<li >
                            
				  Administrateur :<br>
				<?php echo $_SESSION['prenom']."  ".$_SESSION['nom']  ?>
			</li>
           <li class="smenu">
              <a href="index.php?uc=gererUtilisateurs&action=gererUtilisateurs" title="Ajout d'utilisateurs ">Ajout d'utilisateurs</a>
           </li>
           <li class="smenu">
              <a href="index.php?uc=gererUtilisateurs&action=suiviUtilisateurs" title="Consultation des utilisateurs">Modification/Suppression d'un utilisateur</a>
           </li>
 	   <li class="smenu">
              <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
           </li>
           <?php
                       }
           ?>
         </ul>
   </div>