function getXhr(){
    var xhr = null; 
    if(window.XMLHttpRequest) // Firefox et autres
        xhr = new XMLHttpRequest(); 
    else if(window.ActiveXObject){ // Internet Explorer 
        try {
            xhr = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }
    }
    else { // XMLHttpRequest non supporté par le navigateur 
        alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest..."); 
        xhr = false; 
    } 
    return xhr;
}

function recupMois(){
    if(document.getElementById('lstVisiteur').value == -1)
        {
            document.getElementById('lstMois').innerHTML = "<option value='-1'>Choisir un mois</option>"
        }
        else
            {
                var xhr = getXhr();
                xhr.onreadystatechange = function(){
                    if(xhr.readyState == 4 && xhr.status == 200){
                        document.getElementById('lstMois').innerHTML = xhr.responseText;
                    }
                }
                xhr.open("POST","./vues/ajaxServeur.php",true);
                xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                select = document.getElementById('lstVisiteur');
                idVisiteur = select.options[select.selectedIndex].value;
                xhr.send("idVisiteur="+idVisiteur);
            }
    
}

function Affichage(){

                var xhr = getXhr();
                xhr.onreadystatechange = function(){
                    if(xhr.readyState == 4 && xhr.status == 200){
                        document.getElementById('Affichage').innerHTML = xhr.responseText;
                    }
                }
                xhr.open("POST","./vues/ajaxServeur.php",true);
                xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                selectId = document.getElementById('lstVisiteur');
                idVisiteur = selectId.options[selectId.selectedIndex].value;
                selectMois = document.getElementById('lstMois');
                mois = selectMois.options[selectMois.selectedIndex].value;
                xhr.send("numVisiteur="+idVisiteur+"&Mois="+mois);
    
}

function Supprimer(i,libelle){
    var xhr = getXhr();
                xhr.onreadystatechange = function(){  
                    alert(xhr.readyState+xhr.status);
                    if(xhr.readyState == 4 && xhr.status == 200){
                        document.getElementById('etat'+i).innerHTML = xhr.responseText;
                    }
                }
                xhr.open("POST","vues/ajaxServeur2.php",true);
                xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                test = document.getElementById('reporter'+i).value;
                xhr.send("idHorsForfait="+test+"&lib="+libelle);
                
    
}

