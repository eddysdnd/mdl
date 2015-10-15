<?php
$action = $_REQUEST['action'];
$idVisiteur = $_SESSION['idVisiteur'];

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
        

?>