<!-- Faire un site d'interrogation sur les tournages de films dans Paris et d'affichage de leur emplacement.

Fonctionnement:
une page web avec un champ de recherche et un bouton
le formulaire déclenche une recherche dans la base des tournages de films à Paris
les éventuelles réponses sont affichées sous forme de liste
si l'utilisateur clique sur un lieu de tournage, une carte le positionne dans la ville à partir des coordonnées GPS fournies


parametres du form : 
    annee
    titre
    realisateur
    producteur
    adresse_du_lieu
    geo_point_2d  
    new max de rows
-->




<?php
    // base api url : https://opendata.paris.fr/api/records/1.0/search/?dataset=lieux-de-tournage-a-paris
    // echo 'newUrl : https://opendata.paris.fr/api/records/1.0/search/?dataset=lieux-de-tournage-a-paris&q=nom_producteur%3Dmarc%24annee_tournage%3D2019&facet=annee_tournage&facet=type_tournage&facet=nom_tournage&facet=nom_realisateur&facet=nom_producteur&facet=ardt_lieu&facet=date_debut&facet=date_fin <br>';
    $baseUrl = "https://opendata.paris.fr/api/records/1.0/search/?dataset=lieux-de-tournage-a-paris&q=";
    $newUrl = $baseUrl;
    // calcul du nombre de parametres saisis par l'user
    $nbParam = 0;
    if(isset($_POST['annee']) && !empty($_POST['annee'])){
        $nbParam++;
        $annee_film = $_POST['annee'];
    }
    else{
        $annee_film = '';
    }
    if(isset($_POST['titre']) && !empty($_POST['titre'])){
        $nbParam++;
        $titre_film = $_POST['titre'];
    }
    else{
        $titre_film = '';
    }
    if(isset($_POST['realisateur']) && !empty($_POST['realisateur'])){
        $nbParam++;
        $real_film = $_POST['realisateur'];
    }
    else{
        $real_film = '';
    }
    if(isset($_POST['producteur']) && !empty($_POST['producteur'])){
        $nbParam++;
        $prod_film = $_POST['producteur'];
    }
    else{
        $prod_film = '';
    }
    if(isset($_POST['adresse_du_lieu']) && !empty($_POST['adresse_du_lieu'])){
        $nbParam++;
        $adresse_lieu_film = $_POST['adresse_du_lieu'];
    }
    else{
        $adresse_lieu_film = '';
    }
    if($nbParam > 1){
        $u = true;
    }
    else{
        $u = false;
    }
    $check = false; // variable de controle pour savoir si on a deja ajoute un parametre
    $first = true; // variable de controle pour savoir si on est au premier parametre
    if(isset($_POST['annee']) && !empty($_POST['annee'])){
        $annee = $_POST['annee'];
        if($check && $u){
            $newUrl .= '&annee_tournage%253D'.$annee;
        }
        elseif(!$check && $u){
            $newUrl .= 'annee_tournage%253D'.$annee.'%2524%';
        }
        elseif($check && !$u){
            $newUrl .= 'annee_tournage%3D'.$annee;
        }
        else{
            $newUrl .= 'annee_tournage%3D'.$annee;
            $check = true;
        }
        $nbParam --;
        if($nbParam > 0){
            $newUrl .= '%24';
        }
    }
    if(isset($_POST['titre']) && !empty($_POST['titre'])){
        $titre = str_replace(' ', '%20', $_POST['titre']);
        if($check && $u){
            $newUrl .= '&nom_tournage%253D'.$titre;
        }
        elseif(!$check && $u){
            $newUrl .= 'nom_tournage%253D'.$titre.'%2524%';
        }
        elseif($check && !$u){
            $newUrl .= '&nom_tournage%3D'.$titre;
        }
        else {
            $newUrl .= 'nom_tournage%3D'.$titre;
            $check = true;
        }
        $nbParam --;
        if($nbParam == 1 ){
            $newUrl .= '%24';
        }
    }
    if(isset($_POST['realisateur']) && !empty($_POST['realisateur'])){
        $realisateur = str_replace(' ', '%20', $_POST['realisateur']);
        if($check && $u){
            $newUrl .= '&nom_realisateur%253D'.$realisateur;
        }
        elseif(!$check && $u){
            $newUrl .= 'nom_realisateur%253D'.$realisateur.'%2524%';
        }
        elseif($check && !$u){
            $newUrl .= '&nom_realisateur%3D'.$realisateur;
        }
        else {
            $newUrl .= 'nom_realisateur%3D'.$realisateur;
            $check = true;
        }
        $nbParam --;
        if($nbParam == 1){
            $newUrl .= '%24';
        }
    }
    if(isset($_POST['producteur']) && !empty($_POST['producteur'])){
        $producteur = str_replace(' ', '%20', $_POST['producteur']);
        if($check){
            $newUrl .= '&nom_producteur%3D'.$producteur;
        }
        else {
            $newUrl .= 'nom_producteur%3D'.$producteur;
            $check = true;
        }
        $nbParam --;
        if($nbParam > 0){
            $newUrl .= '%24';
        }
    }
    if(isset($_POST['adresse_du_lieu']) && !empty($_POST['adresse_du_lieu'])){
        $adresse_du_lieu = str_replace(' ', '%20', $_POST['adresse_du_lieu']);
        if($check){
            $newUrl .= '&adresse_du_lieu%3D'.$adresse_du_lieu;
        }
        else {
            $newUrl .= 'adresse_du_lieu%3D'.$adresse_du_lieu;
            $check = true;
        }
        $nbParam --;
        if($nbParam > 0){
            $newUrl .= '%24';
        }
    }
    // retait des 4 derniers caracteres si plusieurs parametres
    if($u){
        $newUrl = substr($newUrl, 0, -4);
    }

    if(isset($_POST['rows'])&&!empty($_POST['rows'])){
        if(is_numeric($_POST['rows']) && $_POST['rows'] > 0 && $_POST['rows'] <= 10000){
            $maxRows = $_POST['rows'];
        }
        else{
            $maxRows = 200;
        }
        $newUrl .= '&rows='.$maxRows;
    }
    else{
        $maxRows = 200;
    }
    // add geo_point_2d
    $newUrl .= '&geo_point_2d';
    // add facet
    $newUrl .= '&facet=annee_tournage&facet=type_tournage&facet=nom_tournage&facet=nom_realisateur&facet=nom_producteur&facet=ardt_lieu&facet=date_debut&facet=date_fin';
    // echo "URL : ".$newUrl;
    // echo "<br>";
    // echo "url : https://opendata.paris.fr/api/records/1.0/search/?dataset=lieux-de-tournage-a-paris&q=annee_tournage%253D2019%2524%26nom_realisateur%253DArnaud&facet=annee_tournage&facet=type_tournage&facet=nom_tournage&facet=nom_realisateur&facet=nom_producteur&facet=ardt_lieu&facet=date_debut&facet=date_fin";
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Recherche de tournages de films à Paris</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    </head>
    <body>
        <h1>Recherche de tournages de films à Paris</h1>
        <p>L'intégralité du site est fonctionnel à l'exception de la recherche sur plusieurs critères ( exemple : année et réalisateur ).
            Le résultat sera toujours vide. Les requêtes ne contenant qu'un argument + nombre max de résultats fonctionnent correctement. 
            <br> Vous pouvez cliquer sur le lien ci dessous pour visualiser le résultat de la requête au format json. 
            <br> Si l'api retourne bien les coordonnées gps, vous pourrez cliquer sur les adresses afin de les visualiser sur une carte.</p>
            <p>La partie dans laquelle je génére l'url a été complexifiée lorsque j'ai essayé de prendre en charge plus d'un argument à la fois.
                <br> Je n'ai pas réussis à le faire fonctionner mais je vous le laisse au cas ou cela vous intéresse.</p>
        <form action="filming_in_paris.php" method="post">
            <p>
                <label for="annee">Année</label> : <input type="text" name="annee" id="annee" value='<?php echo $annee_film; ?>'/><br />
                <label for="titre">Titre</label> : <input type="text" name="titre" id="titre" value='<?php echo $titre_film;?>'/><br />
                <label for="realisateur">Réalisateur</label> : <input type="text" name="realisateur" id="realisateur" value='<?php echo $real_film;?>'/><br />
                <label for="producteur">Producteur</label> : <input type="text" name="producteur" id="producteur" value='<?php echo $prod_film;?>'/><br />
                <label for="adresse_du_lieu">Adresse du lieu</label> : <input type="text" name="adresse_du_lieu" id="adresse_du_lieu" value='<?php echo $adresse_lieu_film;?>'/><br />
                <label for="rows">Nombre de maximal de réponses</label> : <input type="text" name="rows" id="rows" value='<?php echo $maxRows ?>'/><br />
                <input type="submit" value="Envoyer" />
            </p>
        </form>

        <table class="table table-striped">
            <?php
                function callAPI($method, $url, $data){
                    $curl = curl_init();
                    switch ($method){
                       case "POST":
                          curl_setopt($curl, CURLOPT_POST, 1);
                          if ($data)
                             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                          break;
                       case "PUT":
                          curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                          if ($data)
                             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
                          break;
                       default:
                          if ($data)
                             $url = sprintf("%s?%s", $url, http_build_query($data));
                    }
                       // OPTIONS:
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'APIKEY: 111111111111111111111',
                    'Content-Type: application/json',
                    ));
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                    // EXECUTE:
                    $result = curl_exec($curl);
                    if(!$result){die("Connection Failure");}
                    curl_close($curl);
                    return $result;
                }
            
                if(true){
                    $result = callAPI('GET', $newUrl, false);
                    $response = json_decode($result, true);
                    // echo si elles sont dispos, toutes les infos de la requête
                    echo "Correspondances : ".sizeof($response['records']);
                    echo "<br>";
                    if(isset($response['records']) && $response['records'] == []){
                        echo "<p>Aucun tournage correspondant à votre recherche n'a été trouvé</p>";
                    }
                    elseif(isset($response['records']) && $response['records'] != []){
                        echo "<tr><th>Année</th><th>Titre</th><th>Réalisateur</th><th>Producteur</th><th>Arrondissement</th><th>Adresse</th><th>Id du lieu</th><th>Date de début</th><th>Date de fin</th></tr>";
                        echo"Url : <a href='".$newUrl."' target='_blank'>".$newUrl."</a>";
                        $i = 0;
                        while ($i < $maxRows && $i < sizeof($response['records']) ){ //$response['records'] && $response['records'][$i] && $response['records'][$i]['fields'] && $response['records'][$i]['fields']['annee_tournage']){
                            echo "<tr>";
                            if(isset($response['records'][$i]['fields']['annee_tournage'])){
                                echo "<th>".$response['records'][$i]['fields']['annee_tournage']."</th>";
                            }
                            else{
                                echo "<th></th>";
                            }
                            if(isset($response['records'][$i]['fields']['nom_tournage'])){
                                echo "<th>".$response['records'][$i]['fields']['nom_tournage']."</th>";
                            }
                            else{
                                echo "<th></th>";
                            }
                            if(isset($response['records'][$i]['fields']['nom_realisateur'])){
                                echo "<th>".$response['records'][$i]['fields']['nom_realisateur']."</th>";
                            }
                            else{
                                echo "<th></th>";
                            }
                            if(isset($response['records'][$i]['fields']['nom_producteur'])){
                                echo "<th>".$response['records'][$i]['fields']['nom_producteur']."</th>";
                            }
                            else{
                                echo "<th></th>";
                            }
                            if(isset($response['records'][$i]['fields']['ardt_lieu'])){
                                echo "<th>".substr($response['records'][$i]['fields']['ardt_lieu'], -2, 3)."</th>";
                            }
                            else{
                                echo "<th></th>";
                            }
                            if(isset($response['records'][$i]['fields']['adresse_lieu'])){ // setup du lien vers la map
                                if(isset($response['records'][$i]['fields']['geo_shape']['coordinates'])){
                                    $long = $response['records'][$i]['fields']['geo_shape']['coordinates'][0];
                                    $lat = $response['records'][$i]['fields']['geo_shape']['coordinates'][1];
                                    $address = $response['records'][$i]['fields']['adresse_lieu'];
                                    $link = "map.php?long=".$long."&lat=".$lat."&address=".$address."";
                                    echo "<th><a href='".$link."' target='_blank'>".$response['records'][$i]['fields']['adresse_lieu']."</a></th>";
                                }
                                else{
                                    echo "<th>".$response['records'][$i]['fields']['adresse_lieu']."</th>";

                                }
                            }
                            else{
                                echo "<th></th>";
                            }
                            if(isset($response['records'][$i]['fields']['id_lieu'])){
                                echo "<th>".$response['records'][$i]['fields']['id_lieu']."</th>";
                            }
                            else{
                                echo "<th></th>";
                            }
                            if(isset($response['records'][$i]['fields']['date_debut'])){
                                echo "<th>".$response['records'][$i]['fields']['date_debut']."</th>";
                            }
                            else{
                                echo "<th></th>";
                            }
                            if(isset($response['records'][$i]['fields']['date_fin'])){
                                echo "<th>".$response['records'][$i]['fields']['date_fin']."</th>";
                            }
                            else{
                                echo "<th></th>";
                            }

                            echo "</tr>";
                            $i++;
                        }
                    }
                    else{
                        echo "<p>Erreur lors de la recherche. Veuillez réessayer</p>";
                    }
                }
            ?>            
        </table>
    </body>
</html>
