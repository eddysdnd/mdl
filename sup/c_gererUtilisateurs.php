<?php
include("vues/v_sommaire.php");
$idVisiteur = $_SESSION['idVisiteur'];
$action = $_REQUEST['action'];
switch($action){
	case 'saisirUtilisateur':{
		$idVisiteur = $_REQUEST['id'];
		$nom = $_REQUEST['nom'];
		$prenom = $_REQUEST['prenom'];
		$login = $_REQUEST['login'];
		$mdp = $_REQUEST['mdp'];
		$adresse = $_REQUEST['adresse'];
		$cp = $_REQUEST['cp'];
		$ville = $_REQUEST['ville'];
		$dateEmbauche = $_REQUEST['dateEmbauche'];
		$statut = $_REQUEST['statut'];
		break;
	}
	case 'suiviUtilisateurs':{
        $lesUtilisateurs = $pdo->getLesUtilisateurs();
        
        
        
        
        include("vues/v_suiviUtilisateurs.php");
        
        break;
    }
}

?>