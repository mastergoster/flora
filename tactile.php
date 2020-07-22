<?php
include "class/Heures.php";
include 'class/Adherant.php';

$adherantsClass = new Adherant();

$adherants = $adherantsClass->list();
$heures = new Heures();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>tactile</title>
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <link rel="stylesheet" href="css/styletactile.css">
</head>

<body class="unselectable">
    <form action="newline.php?action=newline" method="post" class="form">
        <div class="container">
            <?php foreach ($adherants as $adherant) :
                $color = "";
                if ($heures->presence($adherant["id"])) {
                    $color = "color: green;";
                } ?>
                <label class="option_item clicouille">
                    <input type="radio" name="id_user" class="checkbox" value="<?= $adherant['id'] ?>" required>
                    <div class="option_inner instagram">
                        <div class="tickmark"></div>
                        <div class="icon"><i class="fas fa-user" style="font-size: 50px; <?= $color ?>"></i></div>
                        <div class="name"><?= $adherant['nom'] . " " . $adherant['prenom'] ?></div>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>

        </div>

        <input name="pin" type="password" id="inputcode" class="form-control" value="" required>

        <div id="tactile">
            <div class="line">
                <div class="touche">1</div>
                <div class="touche">2</div>
                <div class="touche">3</div>
            </div>
            <div class="line">
                <div class="touche">4</div>
                <div class="touche">5</div>
                <div class="touche">6</div>
            </div>
            <div class="line">
                <div class="touche">7</div>
                <div class="touche">8</div>
                <div class="touche">9</div>
            </div>
            <div class="line">
                <div class="touche">sup</div>
                <div class="touche">0</div>
                <input id="okvalide" class="touchespecial" type="submit" value="0K">
            </div>
        </div>
    </form>
    <div id="popup" class="popup">
        <p>Merci</p>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function pavetactile(e) {
                if (this.innerText == "sup") {
                    var value = document.getElementById("inputcode").value
                    document.getElementById("inputcode").value = value.substr(0, value.length - 1)


                } else {
                    document.getElementById("inputcode").value += this.innerText

                }


            }
            var pave = document.getElementsByClassName('touche');
            Array.from(pave).forEach(element => {
                element.addEventListener('mouseover', pavetactile);
            });
            document.getElementById('okvalide').addEventListener('mouseover', (e) => {
                document.getElementById('okvalide').click();
            });

            var clicouille = document.getElementsByClassName('clicouille');
            Array.from(clicouille).forEach(element => {
                element.addEventListener('mouseover', (e) => {
                    element.click();
                });
            });

        });
        document.addEventListener('DOMContentLoaded', function() {
            function validate(data) {


                if (data.permission) {
                    document.getElementById("popup").style.backgroundColor = "#c0392b";
                    document.getElementById("popup").innerHTML = "<p>Erreur</p>";
                }
                document.getElementById("popup").style.display = "flex";
                setInterval(function() {
                    document.location.reload(true);
                }, 2000);

            }

            function submitajax(e) {
                e.preventDefault();

                let form = e.target;

                fetch(form.action, {
                        method: form.method,
                        body: new FormData(form)
                    })
                    .then(response => response.json())
                    //.then(json => console.log(json))
                    .then(json => validate(json));



                return false;
            };

            var formlist = document.getElementsByTagName('form');
            Array.from(formlist).forEach(element => {
                element.addEventListener('submit', submitajax);
            });


        });
        var refresh = setInterval(function() {
            document.location.reload(true);
        }, 60000);
    </script>
</body>

</html>