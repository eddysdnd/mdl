<?php
class PdoGsb{   		
      	private static $serveur='mysql:host=localhost';
      	private static $bdd='dbname=gsb_frais';   		
      	private static $user='root' ;    		
      	private static $mdp='' ;	
		private static $monPdo;
		private static $monPdoGsb=null;
			
	private function __construct(){
    	PdoGsb::$monPdo = new PDO(PdoGsb::$serveur.';'.PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp); 
		PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
	}
	public function _destruct(){
		PdoGsb::$monPdo = null;
	}

	public  static function getPdoGsb(){
		if(PdoGsb::$monPdoGsb==null){
			PdoGsb::$monPdoGsb= new PdoGsb();
		}
		return PdoGsb::$monPdoGsb;  
	}
/**
 * Retourne les informations d'un visiteur
 
*/
	public function getInfosVisiteur($login, $mdp){
		$req = "select visiteur.id as id, visiteur.nom as nom, visiteur.prenom as prenom, visiteur.statut as statut 
                from visiteur 
		where visiteur.login='$login' and visiteur.mdp='$mdp'";
		$rs = PdoGsb::$monPdo->query($req);
		$ligne = $rs->fetch();
		return $ligne;
	}
/**
 * Retourne toutes les lignes de frais hors forfait
*/
	public function getLesFraisHorsForfait($idVisiteur,$mois){
	    $req = "select * from lignefraishorsforfait where lignefraishorsforfait.idvisiteur ='$idVisiteur' 
		and lignefraishorsforfait.mois = '$mois' ";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		$nbLignes = count($lesLignes);
		for ($i=0; $i<$nbLignes; $i++){
			$date = $lesLignes[$i]['date'];
			$lesLignes[$i]['date'] =  dateAnglaisVersFrancais($date);
		}
		return $lesLignes; 
	}
/**
 * Retourne le nombre de justificatif d'un visiteur pour un mois donné
*/
	public function getNbjustificatifs($idVisiteur, $mois){
		$req = "select fichefrais.nbjustificatifs as nb from  fichefrais where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne['nb'];
	}
/**
 * Retourne toutes les lignes de frais au forfait
*/
	public function getLesFraisForfait($idVisiteur, $mois){
		$req = "select fraisforfait.id as idfrais, fraisforfait.libelle as libelle, 
		lignefraisforfait.quantite as quantite from lignefraisforfait inner join fraisforfait 
		on fraisforfait.id = lignefraisforfait.idfraisforfait
		where lignefraisforfait.idvisiteur ='$idVisiteur' and lignefraisforfait.mois='$mois' 
		order by lignefraisforfait.idfraisforfait";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes; 
	}
/**
 * Retourne tous les id de la table FraisForfait
*/
	public function getLesIdFrais(){
		$req = "select fraisforfait.id as idfrais from fraisforfait order by fraisforfait.id";
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}
	
	public function majFraisForfait($idVisiteur, $mois, $lesFrais){
		$lesCles = array_keys($lesFrais);
		foreach($lesCles as $unIdFrais){
			$qte = $lesFrais[$unIdFrais];
			$req = "update lignefraisforfait set lignefraisforfait.quantite = $qte
			where lignefraisforfait.idvisiteur = '$idVisiteur' and lignefraisforfait.mois = '$mois'
			and lignefraisforfait.idfraisforfait = '$unIdFrais'";
			PdoGsb::$monPdo->exec($req);
		}
	}
/**
 * met à jour le nombre de justificatifs de la table ficheFrais
*/
	public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs){
		$req = "update fichefrais set nbjustificatifs = $nbJustificatifs 
		where fichefrais.idvisiteur = '$idVisiteur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);	
	}
/**
 * Test si un visiteur possede une fiche de frais pour le mois passe en argument

*/	
	public function estPremierFraisMois($idVisiteur,$mois)
	{
		$ok = false;
		$req = "select count(*) as nblignesfrais from fichefrais 
		where fichefrais.mois = '$mois' and fichefrais.idvisiteur = '$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		if($laLigne['nblignesfrais'] == 0){
			$ok = true;
		}
		return $ok;
	}
/**
 * Retourne le dernier mois en cours d'un visiteur
*/	
	public function dernierMoisSaisi($idVisiteur){
		$req = "select max(mois) as dernierMois from fichefrais where fichefrais.idvisiteur = '$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		$dernierMois = $laLigne['dernierMois'];
		return $dernierMois;
	}
/**
 * Crée un utilisateur
*/
	public function creeNouvelUtilisateur($idVisiteur,$nom,$prenom,$login,$mdp,$adresse,$cp,$ville,$dateEmbauche,$statut){
		$req ="insert into visiteur (id,nom,prenom,login,mdp,adresse,cp,ville,dateEmbauche,statut)
		values ('$idVisiteur,$nom,$prenom,$login,$mdp,$adresse,$cp,$ville,$dateEmbauche,$statut)";
		PdoGsb::$monPdo->exec($req);
	}
	
	public function getLesUtilisateurs(){
                $requete = "SELECT V.id, V.nom, V.prenom, V.login, V.mdp, V.adresse, V.cp, V.ville, V.dateEmbauche, V.statut
                from Visiteur V";
                $res = PdoGsb::$monPdo->query($requete);
                $lesUtilisateurs = $res->fetchAll();
                return $lesUtilisateurs;
        }	
	
	
/**
 * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un visiteur et un mois donnés
*/
	public function creeNouvellesLignesFrais($idVisiteur,$mois){
		$dernierMois = $this->dernierMoisSaisi($idVisiteur);
		$laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur,$dernierMois);
		if($laDerniereFiche['idEtat']=='CR'){
				$this->majEtatFicheFrais($idVisiteur, $dernierMois,'CL');
				
		}
		$req = "insert into fichefrais(idvisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
		values('$idVisiteur','$mois',0,0,now(),'CR')";
		PdoGsb::$monPdo->exec($req);
		$lesIdFrais = $this->getLesIdFrais();
		foreach($lesIdFrais as $uneLigneIdFrais){
			$unIdFrais = $uneLigneIdFrais['idfrais'];
			$req = "insert into lignefraisforfait(idvisiteur,mois,idFraisForfait,quantite) 
			values('$idVisiteur','$mois','$unIdFrais',0)";
			PdoGsb::$monPdo->exec($req);
		 }
	}
/**
 * Crée un nouveau frais hors forfait pour un visiteur un mois donné
*/
	public function creeNouveauFraisHorsForfait($idVisiteur,$mois,$libelle,$date,$montant){
		$dateFr = dateFrancaisVersAnglais($date);
		$req = "insert into lignefraishorsforfait 
		values('','$idVisiteur','$mois','$libelle','$dateFr','$montant')";
		PdoGsb::$monPdo->exec($req);
	}

	public function supprimerFraisHorsForfait($idFrais){
                $req = "delete from lignefraishorsforfait where id ='".$idFrais."'";                
                PdoGsb::$monPdo->exec($req);
	}
/**
 * Retourne les mois pour lesquel un visiteur a une fiche de frais
*/
	public function getLesMoisDisponibles($idVisiteur){
		$req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idvisiteur ='$idVisiteur' 
		order by fichefrais.mois desc ";
		$res = PdoGsb::$monPdo->query($req);
		$lesMois =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{
			$mois = $laLigne['mois'];
			$numAnnee =substr( $mois,0,4);
			$numMois =substr( $mois,4,2);
			$lesMois["$mois"]=array(
		     "mois"=>"$mois",
		    "numAnnee"  => "$numAnnee",
			"numMois"  => "$numMois"
             );
			$laLigne = $res->fetch(); 		
		}
		return $lesMois;
	}
/**
 * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donné
*/	
	public function getLesInfosFicheFrais($idVisiteur,$mois){
		$req = "select ficheFrais.idEtat as idEtat, ficheFrais.dateModif as dateModif, ficheFrais.nbJustificatifs as nbJustificatifs, 
			ficheFrais.montantValide as montantValide, etat.libelle as libEtat from  fichefrais inner join Etat on ficheFrais.idEtat = Etat.id 
			where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne;
	}
/**
 * Modifie l'état et la date de modification d'une fiche de frais
 */
	public function majEtatFicheFrais($idVisiteur,$mois,$etat){
		$req = "update ficheFrais set idEtat = '$etat', dateModif = now() 
		where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);
	} 
	
        public function getFicheFraisValidee(){
                $requete = "SELECT V.id, V.nom, V.prenom, F.mois, F.montantValide
                from Visiteur V, FicheFrais F
                WHERE V.id = F.idVisiteur
                AND F.idetat = 'VA'";
                $res = PdoGsb::$monPdo->query($requete);
                $lesFiches = $res->fetchAll();
                return $lesFiches;
        }
        
        public function getMontantQuantite($unMois, $unVisiteur) {
		$requete = "SELECT lignefraisforfait.quantite,
                            lignefraisforfait.idFraisForfait, fraisforfait.montant
                            from lignefraisforfait
                            JOIN fraisforfait ON ( lignefraisforfait.idFraisForfait = fraisforfait.id )
                            WHERE lignefraisforfait.idVisiteur = '".$unVisiteur."'
                            AND lignefraisforfait.mois = '".$unMois."'";
		$res = PdoGsb::$monPdo->query($requete);
		$lesMontantQuantite = $res->fetchAll();
		return $lesMontantQuantite;
        }
        
        public function getVisiteurAyantFiche(){
            $requete = "SELECT id, nom, prenom
                        from visiteur
                        WHERE id
                        IN (SELECT idvisiteur
                        from lignefraisforfait) 
                        order by nom ASC";
            $res = PdoGsb::$monPdo->query($requete);
            $VisiteurAyantFiche = $res->fetchAll();
            return $VisiteurAyantFiche;
        }
        
        public function getMoisVisiteur($id){
            $requete = "SELECT mois from fichefrais WHERE fichefrais.idVisiteur = '".$id."' and idEtat='CR'" ;
            $res = PdoGsb::$monPdo->query($requete);
            $dateMois = $res->fetchAll();
            return $dateMois;
            }
            
       
        
        public function reporterFicheFraisHorsForfait($idFraisHorsForfait) {
            $requete = "select mois from lignefraishorsforfait where id ='" .$idFraisHorsForfait. "'";
            $resultat = PdoGsb::$monPdo->query($requete);
            $res = $resultat->Fetch();
            $numAnnee = substr($res[0],0,4);
            $numMois = substr($res[0],4,6);
            
            if ($numMois != 12) {
                $numMois = $numMois + 1;
                
            }
            else {                
		$numMois = "01";
                $numAnnee = $numAnnee + 1;                    
            }
            
            $mois = $numAnnee.$numMois;
            
            $req = "update lignefraishorsforfait set mois ='" .$mois. "' where id = '" .$idFraisHorsForfait. "'";
            PdoGsb::$monPdo->query($req);
        }
        
        function rembourserFicheFrais($unVisiteur, $unMois) {
                $requete = "UPDATE fichefrais SET idEtat='RB' 
                WHERE idVisiteur = '" . $unVisiteur . "' and mois = '" . $unMois . "'" ;
                PdoGsb::$monPdo->query($requete);
        }
        
        function validerFicheFrais($unVisiteur, $unMois) {
                $requete = "UPDATE fichefrais SET idEtat='VA' 
                WHERE idVisiteur = '" . $unVisiteur . "' and mois = '" . $unMois . "'" ;
                PdoGsb::$monPdo->query($requete);
        }
        
        function getMontantHorsForfait($unVisiteur, $unMois) {
            $requete = "SELECT sum(montant) as montant from `lignefraishorsforfait` 
                        where substring(libelle, 1, 6) 
                        not like 'REFUSE' 
                        and idVisiteur = '" . $unVisiteur . "' 
                        and mois = '" . $unMois . "'";
            $res = PdoGsb::$monPdo->query($requete);
            $montantHF = $res->fetch();
            return $montantHF['montant'];
        }
}
?>
