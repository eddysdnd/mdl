<?php
require_once("/controleurs/controleur.php");
include("vues/v_entete.php") ;
session_start();
$pdo = PdoGsb::getPdoGsb();
$estConnecte = estConnecte();
if(!isset($_REQUEST['uc']) || !$estConnecte){
     $_REQUEST['uc'] = 'connexion';
}	 
$uc = $_REQUEST['uc'];
switch($uc){
	case 'connexion':{
			connexion();break;
	}
	case 'gererFrais' :{
			gererFrais();break;
	}
	case 'etatFrais' :{
			etatFrais();break; 
	}
	case 'gererUtilisateurs' :{
			gererUtilisateurs();break; 
	}

}
?>