    <div class="encadre">
        <table class="listeLegere" border="1"> 
            <tbody>
            <tr>
                <th rowspan="2">Visiteur Medical</th>
                <th rowspan="2">mois</th>
                <th colspan=3>montant</th>
                <th rowspan="2" >option</th>
            </tr>
            <tr>
                <th>Forfait</th>
                <th>Hors-forfait</th>
                <th>total</th>
          </tr>
          </tbody>

        

<?php

$i = 0;

foreach ($lesFichesFraisValidees as $uneFicheFraisValidee) {

?>	
        
	<tr><td><?php echo $uneFicheFraisValidee['nom']." ".$uneFicheFraisValidee['prenom']; ?></td> 
            <td><?php echo $uneFicheFraisValidee['mois']; ?></td> 
            <td><?php echo $tab[$i]; ?></td> 
            <td><?php echo $tabHF[$i]; ?></td> 
            <td><?php echo $tab[$i] + $tabHF[$i]; ?></td> 
            <td><a href="#" id="misPaiement" 
                   name="<?php echo $uneFicheFraisValidee['id']; echo " "; echo $uneFicheFraisValidee['mois'] ?>" >
                   Valider</a></td> 
        </tr>
<?php
$i++;
}
?>
        
        </table>  
        
    </div>
</div>

