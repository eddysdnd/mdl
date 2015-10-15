<?php
include("../modele/modele.php");

$instancePdoGsb = PdoGsb::getPdoGsb();


if(isset($_POST['idHorsForfait'])){
    
    $idHF = $_POST['idHorsForfait'];
    $libelle = $_POST['lib'];
    $instancePdoGsb->supprimerFraisHorsForfait($idHF,$libelle);
    echo "REFUSEE - ";
}
?>
