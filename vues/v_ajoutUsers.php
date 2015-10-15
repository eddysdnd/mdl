<form action="index.php?uc=gererUtilisateurs&action=saisirUtilisateur" method="post">
      <div class="corpsForm">
         
          <fieldset>
            <legend>Nouvel utilisateur
            </legend>
            <p>
              <label for="idVisit">Id du Visiteur: </label>
              <input type="text" id="txtDateHF" name="idVisit" size="10" maxlength="10" value=""  />
            </p>
            <p>
              <label for="txtLibelleHF">Libellé</label>
              <input type="text" id="txtLibelleHF" name="libelle" size="70" maxlength="256" value="" />
            </p>
            <p>
              <label for="txtMontantHF">Montant : </label>
              <input type="text" id="txtMontantHF" name="montant" size="10" maxlength="10" value="" />
            </p>
          </fieldset>
      </div>
		</br></br>
        <input id="ajouter" type="submit" value="Ajouter" size="20" />
        <input id="effacer" type="reset" value="Effacer" size="20" />
        
      </form>
  </div>
  

