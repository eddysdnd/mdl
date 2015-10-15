<?php
include("../modele/modele.php");
$instancePdoGsb = PdoGsb::getPdoGsb();




if((isset($_POST['numVisiteur']))&&(isset($_POST['Mois']))){
    $id = $_POST['numVisiteur'];
    $mois = $_POST['Mois'];
    
    $tabFiche = $instancePdoGsb->getLesFraisForfait($id, $mois);
    $tabFicheHF = $instancePdoGsb->getLesFraisHorsForfait($id,$mois);
    $nbJustificatifsHF = $instancePdoGsb->getNbjustificatifs($id,$mois);
?>


    <table class="listeLegere" border="1"> 
            <tbody>
                <tr>
<?php
    foreach($tabFiche as $ligneFraisForfait)
    {
?>
        <th><?php echo $ligneFraisForfait['libelle']; ?>
<?php
    }
    ?> 
    </tr>
    <tr>
<?php
    foreach($tabFiche as $ligneFraisForfait)
    {
?>
        <td><input type="text" value="<?php echo $ligneFraisForfait['quantite']; ?>" style="width:30px;" ></td>
<?php
    }
?>
     
      </tr>  
<?php
         
}
?>
      <div id="teste"></div>
      <table class="listeLegere">
  	 
             <tr>
                <th class="date">Date</th>
                <th class="libelle">Libellé</th>
                <th class='montant'>Montant</th> 
                <th>Action</th>
             </tr>
        <?php     
        $i = 0;
          foreach ( $tabFicheHF as $unFraisHorsForfait ) 
		  {
                        
			$date = $unFraisHorsForfait['date'];
			$libelle = $unFraisHorsForfait['libelle'];
			$montant = $unFraisHorsForfait['montant'];
                        $num = $unFraisHorsForfait['id'];
		?>
             <tr>
                    <td><?php echo $date ?></td>
             
                    <td><div id="etat<?php echo $i; ?>"></div><?php echo $libelle ?></td>
                    <td><?php echo $montant ?></td>                   
                    <td><input type="hidden" id="reporter<?php echo $i; ?>" value="<?php echo $num; ?>" ><a href="#" >
                            <img src="images/report.png" width="32px" height="32px" ></a>
                    <a href="#" onclick="javascipt:Supprimer('<?php echo $i; ?>','<?php echo $libelle; ?>');" ><img src="images/croix.jpg" width="32px" height="32px" ></a></td>
             </tr>
        <?php 
        $i++;
          }
          
		?>
    </table>
      
<?php
if(isset($_POST['idVisiteur'])){
   $id = $_POST['idVisiteur'];
$VisiteurAyantFiche = $instancePdoGsb->getMoisVisiteur($id);

 // on propose tous les visiteurs ayant une fiche frais du mois
foreach($VisiteurAyantFiche as $ligneVisiteur) { 
        $numAnnee =substr( $ligneVisiteur['mois'],0,4);
        $numMois =substr( $ligneVisiteur['mois'],4,2);?>
        <option value='<?php echo $ligneVisiteur['mois']; ?>'><?php echo $ligneVisiteur['mois']; ?></option>
        
 <?php
        }
}

    
    
    




?> 
