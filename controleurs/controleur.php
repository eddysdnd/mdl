<?php
require_once("modele/modele.php");

$pdo = PdoGsb::getPdoGsb();
function connexion() {
	global $pdo;
	if(!isset($_REQUEST['action'])){
		$_REQUEST['action'] = 'demandeConnexion';
	}
	$action = $_REQUEST['action'];
	switch($action){
		case 'demandeConnexion':{
			include("vues/v_connexion.php");
			break;
		}
		case 'valideConnexion':{
			$login = $_REQUEST['login'];
			$mdp = $_REQUEST['mdp'];
			$visiteur = $pdo->getInfosVisiteur($login,$mdp);
			if(!is_array( $visiteur)){
				ajouterErreur("Login ou mot de passe incorrect");
				include("vues/v_erreurs.php");
				include("vues/v_connexion.php");
			}
			else{
				$id = $visiteur['id'];
				$nom =  $visiteur['nom'];
				$prenom = $visiteur['prenom'];
							$statut = $visiteur['statut'];
				connecter($id,$nom,$prenom, $statut);
				include("vues/v_sommaire.php");
			}
			break;
		}
		default :{
			include("vues/v_connexion.php");
			break;
		}
	}
}
function gererFrais() {
	global $pdo;
	include("vues/v_sommaire.php");
	$idVisiteur = $_SESSION['idVisiteur'];
	$mois = getMois(date("d/m/Y"));
	$numAnnee =substr( $mois,0,4);
	$numMois =substr( $mois,4,2);
	$action = $_REQUEST['action'];
	switch($action){
		case 'saisirFrais':{
			if($pdo->estPremierFraisMois($idVisiteur,$mois)){
				$pdo->creeNouvellesLignesFrais($idVisiteur,$mois);
			}
			break;
		}
		case 'validerMajFraisForfait':{
			$lesFrais = $_REQUEST['lesFrais'];
			if(lesQteFraisValides($lesFrais)){
				$pdo->majFraisForfait($idVisiteur,$mois,$lesFrais);
			}
			else{
				ajouterErreur("Les valeurs des frais doivent être numériques");
				include("vues/v_erreurs.php");
			}
		  break;
		}
		case 'validerCreationFrais':{
			$dateFrais = $_REQUEST['dateFrais'];
			$libelle = $_REQUEST['libelle'];
			$montant = $_REQUEST['montant'];
			valideInfosFrais($dateFrais,$libelle,$montant);
			if (nbErreurs() != 0 ){
				include("vues/v_erreurs.php");
			}
			else{
				$pdo->creeNouveauFraisHorsForfait($idVisiteur,$mois,$libelle,$dateFrais,$montant);
			}
			break;
		}
		case 'supprimerFrais':{
			$idFrais = $_REQUEST['idFrais'];
					$pdo->supprimerFraisHorsForfait($idFrais);
			break;
		}
	}
	$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,$mois);
	$lesFraisForfait= $pdo->getLesFraisForfait($idVisiteur,$mois);
	include("vues/v_listeFraisForfait.php");
	include("vues/v_listeFraisHorsForfait.php");
}

function etatFrais() {
	$action = $_REQUEST['action'];
	$idVisiteur = $_SESSION['idVisiteur'];
	global $pdo;
	include("vues/v_sommaire.php");


	switch($action){
		case 'selectionnerMois':{
			$lesMois=$pdo->getLesMoisDisponibles($idVisiteur);
			$lesCles = array_keys( $lesMois );
			$moisASelectionner = $lesCles[0];
			include("vues/v_listeMois.php");
			break;
		}
		case 'voirEtatFrais':{
			$leMois = $_REQUEST['lstMois']; 
			$lesMois=$pdo->getLesMoisDisponibles($idVisiteur);
			$moisASelectionner = $leMois;
			include("vues/v_listeMois.php");
			$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,$leMois);
			$lesFraisForfait= $pdo->getLesFraisForfait($idVisiteur,$leMois);
			$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur,$leMois);
			$numAnnee =substr( $leMois,0,4);
			$numMois =substr( $leMois,4,2);
					$moisSelected = $numAnnee.$numMois;
			$libEtat = $lesInfosFicheFrais['libEtat'];
			$montantValide = $lesInfosFicheFrais['montantValide'];
			$nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
			$dateModif =  $lesInfosFicheFrais['dateModif'];
			$dateModif =  dateAnglaisVersFrancais($dateModif);
			include("vues/v_etatFrais.php");
			break;
		}
		case 'suiviFrais':{
			$lesFichesFraisValidees = $pdo->getFicheFraisValidee();
			$i=0;
			$j=0;
			$tab = array();
			$tabHF = array();
			foreach ($lesFichesFraisValidees as $uneFicheFraisValidee) {
				$total = 0;
				$totalHF = 0;
				$unMois = $uneFicheFraisValidee['mois'];
				$unVisiteur = $uneFicheFraisValidee['id'];
				
				$montantHF = $pdo->getMontantHorsForfait($unVisiteur,$unMois);
				
				$lesMontantQuantite = $pdo->getMontantQuantite($unMois, $unVisiteur); 
				foreach ($lesMontantQuantite as $unMontantQuantite) {
						 $total += ($unMontantQuantite['montant']) * ($unMontantQuantite['quantite']);
								
				}
				$tab[$i] = $total;
				$tabHF[$i] = $montantHF;
				$i++;
					 
			}
			
			
			
			include("vues/v_suiviFrais.php");
			
			break;
		}
		case 'validerFrais':{
			$VisiteurAyantFiche = $pdo->getVisiteurAyantFiche();
			include("vues/v_validerFrais.php");
			break;
		}
	}
			

}
function gererUtilisateurs() {
	include("vues/v_sommaire.php");
	$idVisiteur = $_SESSION['idVisiteur'];
	$action = $_REQUEST['action'];
	global $pdo;
	
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
}
?>