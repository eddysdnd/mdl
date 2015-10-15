    <div class="encadre">
        <table class="listeLegere" border="1"> 
            <tbody>
            <tr>
                <th rowspan="2">Id</th>
                <th rowspan="2">Nom</th>
                <th rowspan="2">Prenom</th>
                <th rowspan="2" >Login</th>
				<th rowspan="2" >Mot de passe</th>
				<th rowspan="2" >Adresse</th>
				<th rowspan="2" >Code postal</th>
				<th rowspan="2" >Ville</th>
				<th rowspan="2" >Date embauche</th>
				<th rowspan="2" >Statut</th>
            </tr>
          </tbody>

        

<?php

foreach ($lesUtilisateurs as $unUtilisateur) {

?>	
        
	<tr><td><?php echo $unUtilisateur['id']; ?></td> 
            <td><?php echo $unUtilisateur['nom']; ?></td>
            <td><?php echo $unUtilisateur['prenom']; ?></td>
            <td><?php echo $unUtilisateur['login']; ?></td>
            <td><?php echo $unUtilisateur['mdp']; ?></td>
			<td><?php echo $unUtilisateur['adresse']; ?></td>
			<td><?php echo $unUtilisateur['cp']; ?></td>
			<td><?php echo $unUtilisateur['ville']; ?></td>
			<td><?php echo $unUtilisateur['dateEmbauche']; ?></td>
			<td><?php echo $unUtilisateur['statut']; ?></td>
        </tr>
<?php
} 
?>
        </table>  
        
    </div>
</div>

