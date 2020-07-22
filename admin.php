<?php
session_start();
include "class/Forfait.php";
include "class/Heures.php";
include 'class/Adherant.php';

if (!isset($_SESSION['admin'])) {
    header('Location: /');
    exit();
}
if ($_SESSION['admin'] == "admin") {
    $admin = true;
} else {
    header('Location: /');
    exit();
}


$adherantsClass = new Adherant();
$forfaits = (new Forfait())->list();
$heures = new Heures();


$adherants = $adherantsClass->list();
?>
<style>
    #green {
        width: 40px;
        height: 40px;
        border-radius: 20px;
        background: green;
    }

    #red {
        width: 40px;
        height: 40px;
        border-radius: 20px;
        background: red;
    }
</style>
<?php

echo "<h2>Adherants</h2>";
echo "<a href=\"newad.php\">Nouvelle adherent</a></br>";
foreach ($adherants as $adherant) {
    if ($heures->presence($adherant["id"])) {
        $color = "green";
    } else {
        $color = "red";
    }
    echo "<a href=\"profile.php?id=" . $adherant["id"] . "\">" . $adherant["nom"] . " " . $adherant["prenom"] . "</a><div id=\"" . $color . "\"></div></br>";
}
echo "<h2>FORFAIT</h2>";

foreach ($forfaits as $forfait) {
    echo "<a href=\"?id=" . $forfait["id"] . "\">" . $forfait["nom"] . " " . $forfait["duree"] . "H " . $forfait["prix"] . "€</a></br>";
}

?>
<form method="post" action="newforfait.php?action=new">
    <div class="form-label-group">
        <input name="nom" type="text" id="nom" class="form-control" placeholder="nom" required autofocus>
        <label for="nom">nom</label>
    </div>
    <div class="form-label-group">
        <input name="prix" type="text" id="prix" class="form-control" placeholder="prix" required autofocus>
        <label for="prix">prix</label>
    </div>
    <div class="form-label-group">
        <input name="duree" type="text" id="duree" class="form-control" placeholder="duree" required autofocus>
        <label for="duree">duree</label>
    </div>
    <input type="submit">
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function submitajax(e) {
            e.preventDefault();

            let form = e.target;

            fetch(form.action, {
                    method: form.method,
                    body: new FormData(form)
                })
                .then(response => response.json())
                .then(json => console.log(json))
                .then(document.location.reload(true));


            return false;
        };

        var formlist = document.getElementsByTagName('form');
        Array.from(formlist).forEach(element => {
            element.addEventListener('submit', submitajax);
        });


    });
</script>