<?php
session_start();
include "class/Forfait.php";
include "class/Heures.php";
include 'class/Adherant.php';

function ConvertisseurTime($Time)
{
    $neg = false;
    if ($Time < 0) {
        $neg = true;
        $Time = abs($Time);
    }
    if ($Time < 3600) {
        $heures = 0;

        if ($Time < 60) {
            $minutes = 0;
        } else {
            $minutes = round($Time / 60);
        }

        $secondes = floor($Time % 60);
    } else {
        $heures = round($Time / 3600);
        $secondes = round($Time % 3600);
        $minutes = floor($secondes / 60);
    }

    $secondes2 = round($secondes % 60);
    if ($secondes2 > 0) {
        $minutes += 1;
    }
    if ($neg) {
        $heures = -$heures;
        $minutes = -$minutes;
    }
    return ["h" => (int)$heures, "m" => (int)$minutes];
}

if (!isset($_SESSION['admin'])) {
    header('Location: /');
    exit();
}
if ($_SESSION['admin'] == "admin") {
    $admin = true;
} else {
    if ($_SESSION['admin'] != $_GET["id"]) {
        header('Location: /');
        exit();
    }
}
$id = $_GET["id"];
$adherantClass = new Adherant();

$adherant = $adherantClass->listById($id);

if (!$adherant) {
    header('Location: /');
    exit();
}


//forfait
$forfaits = (new Forfait())->resteByUser($adherant["id"]);
if (count($forfaits) < 1) {
    $forfait = ["duree" => "aucun forfait", "h" => 0, "min" => 0];
} else {
    $forfait = ["duree" => "jusqu'au " . $forfaits["fin"], "h" => $forfaits["h"], "min" => $forfaits["min"]];
}
//heure
$presences = (new Heures())->listByUser($adherant["id"]);
$heures = [];
$aujourduit = [];
if (count($presences) < 1) {
} else {

    $now = new DateTime();

    for ($i = count($presences) - 1; $i >= 0; $i--) {
        if (substr($presences[$i]["cearted_at"], 0, 10) != $now->format('d-m-Y')) {
            $heures[substr($presences[$i]["cearted_at"], 0, 10)][] = substr($presences[$i]["cearted_at"], 11);
        } else {
            $aujourduit[] = substr($presences[$i]["cearted_at"], 11);
        }
    }
}
$consomationtotal = 0;
foreach ($heures as $date => $heurspoint) {
    if (count($heurspoint) % 2) {
        $heurspoint[] = "23:59:59";
    }
    rsort($heurspoint);
    $dureejour = 0;
    for ($i = 0; $i < count($heurspoint); $i += 2) {
        $heurpointfin = explode(":", $heurspoint[$i]);
        $heurpointdebut = explode(":", $heurspoint[$i + 1]);
        $heurpointfin = $heurpointfin[0] * 60 * 60 + $heurpointfin[1] * 60 + $heurpointfin[2];
        $heurpointdebut =  $heurpointdebut[0] * 60 * 60 + $heurpointdebut[1] * 60 + $heurpointdebut[2];
        $dureejour += $heurpointfin - $heurpointdebut;
    }
    $consomationtotal += $dureejour;
    $heures[$date] = ConvertisseurTime($dureejour);
    $presences = ["last" => join("/", explode("-", $date)) . " durée " . $heures[$date]["h"] . "h" . $heures[$date]["m"] . "min"];
}
$heures["consomationtotalens"] = $consomationtotal;
$restforfait = ConvertisseurTime(($forfait["h"] * 60 * 60 + $forfait["min"] * 60) - $consomationtotal);
$forfait["h"] = $restforfait["h"];
$forfait["min"] = $restforfait["m"];
if ($consomationtotal < 1) {
    $presences = ["last" => "tu n'es jamais venu"];
}

$dureeaujourduit = 0;
sort($aujourduit);
if (count($aujourduit) > 1) {
    for ($i = 0; $i < count($aujourduit) - 1; $i += 2) {
        $heurpointfin = explode(":", $aujourduit[$i + 1]);
        $heurpointdebut = explode(":", $aujourduit[$i]);
        $heurpointfin = $heurpointfin[0] * 60 * 60 + $heurpointfin[1] * 60 + $heurpointfin[2];
        $heurpointdebut =  $heurpointdebut[0] * 60 * 60 + $heurpointdebut[1] * 60 + $heurpointdebut[2];
        $dureeaujourduit += $heurpointfin - $heurpointdebut;
    }
}


if ((new Heures())->presence($id)) {
    $textpresence = "tu es au coworking";
    $textpresence2 = "arreter ma journée";
    $color = "green";
    $presence = true;
    $heurcomteur = end($aujourduit);

    $heurpointfin = explode(":", date("H:i:s"));
    $heurpointdebut = explode(":", $heurcomteur);
    $heurpointfin = $heurpointfin[0] * 60 * 60 + $heurpointfin[1] * 60 + $heurpointfin[2];
    $heurpointdebut =  $heurpointdebut[0] * 60 * 60 + $heurpointdebut[1] * 60 + $heurpointdebut[2];
    $dureeaujourduit += $heurpointfin - $heurpointdebut;
} else {
    $color = "";
    $textpresence = "tu n'es pas au coworking";
    $textpresence2 = "continuer ma journée";
    $presence = false;
}

$dureeaujourduitarray = ConvertisseurTime($dureeaujourduit);


?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoWorkInMoulins / panel / profile</title>
    <link rel="stylesheet" href="css/panel.css">
</head>

<body>
    <div class="container">
        <aside class="left-nav">
            <h1>Panel</h1>
            <nav>
                <ul>
                    <li>a venir ;)</li>
                    <li><a href="/deconnect.php">deconexion</a></li>
                </ul>
            </nav>
            <footer>FLORA by Apprendre.co v1.0</footer>
        </aside>
        <section class="body">
            <article>
                <h1><?= $adherant["prenom"] . " " . $adherant["nom"] ?></h1>
                <div>
                    <p class="muted">Membre actif</p>
                    <p>mail : <?= $adherant["mail"] ?> (validé)</p>
                    <p>telephone : <?= $adherant["tel"] ?> (validé)</p>
                    <p>mon code pin : <?= $adherant["pin"] ?></p>
                </div>
            </article>
            <article class="forfait">
                <h1>Forfait</h1>
                <div>
                    <p class="muted"><?= $forfait["duree"] ?></p>
                    <p class="big"><?= $forfait["h"] ?><span>H</span><?= $forfait["min"] ?><span>min</span></p>
                </div>
            </article>
            <article class="forfait">
                <h1>Présences</h1>
                <div>
                    <p>denière présence le :</p>
                    <p><?= $presences["last"] ?></p>
                </div>
            </article>
            <article id="presence" class="<?= $color ?>">
                <h1><?= $textpresence ?></h1>
                <div>
                    <p class="muted">aujourd'hui tu a passé :</p>
                    <p class="big" id="compteur"><?= $dureeaujourduitarray["h"] ?><span>H</span><?= $dureeaujourduitarray["m"] ?><span>min</span></p>
                    <p class="muted"><?= $textpresence2 ?></p>
                </div>
            </article>

        </section>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($presence) : ?>
                var compteur = document.getElementById("compteur");
                var heure = <?= $dureeaujourduitarray["h"] ?>;
                var min = <?= $dureeaujourduitarray["m"] ?>;
                var sec = 0;
                var refresh = setInterval(function() {
                    sec += 1;
                    if (sec == 60) {
                        min += 1;
                        sec = 0;
                        if (min == 60) {
                            heure += 1;
                            min = 0;
                        }
                    }
                    compteur.innerHTML = heure + '<span>H</span>' + min + '<span>min</span>';
                }, 1000);
            <?php endif; ?>

            function submitajax(e) {
                e.preventDefault();

                let formData = new FormData();
                formData.append("id_user", "<?= $id ?>");

                fetch("newline.php?action=newline", {
                        method: "post",
                        body: formData
                    })
                    .then(response => response.json())
                    .then(json => console.log(json))
                    .then(document.location.reload(true));


                return false;
            };

            var formlist = document.getElementById('presence').addEventListener('click', submitajax);


        });
    </script>
</body>

</html>