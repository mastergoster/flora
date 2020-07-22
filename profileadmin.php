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
    if ($_SESSION['admin'] != $_GET["id"]) {
        die("403");
    }
}

$id = $_GET["id"];

$adherants = new Adherant();


$adherant = $adherants->listById($id);

if (!$adherant) {
    header('Location: admin.php');
    exit();
}

foreach ($adherant as $key => $value) {
    echo $key . " : " . $value . "<br>";
}


$lignesH = (new Heures())->listByUser($adherant["id"]);
echo "<h2>lignes</h2>";
if (count($lignesH) < 1) {
    echo "aucune ligne";
} else {
    foreach ($lignesH as $ligne) {
        echo $ligne["cearted_at"];
        if ($admin) { ?>
            <form method="post" action="newline.php?action=del">
                <input name="id" type="hidden" id="id" class="form-control" value="<?= $ligne["id"] ?>" required>
                <input type="submit" value="X">
            </form>

<?php
        }
        echo "<br>";
    }
}

if ((new Heures())->presence($id)) {
    $text = "sortir";
} else {
    $text = "entrer";
}
?>
<form method="post" action="newline.php?action=newline">
    <div class="form-label-group">
        <input name="id_user" type="hidden" id="id_user" class="form-control" value="<?= $id ?>" required>
    </div>
    <input type="submit" value="<?= $text ?>">
</form>
<?php
$forfaits = (new Forfait())->listByUser($adherant["id"]);
echo "<h2>FORFAIT en cours</h2>";
if (count($forfaits) < 1) {
    echo "aucun forfait";
} else {
    foreach ($forfaits as $forfait) {
        echo  $forfait["nom"] . "  " . $forfait["duree"] . "H  debut le " . $forfait["cearted_at"];
        if ($admin) { ?>
            <form method="post" action="newforfait.php?action=del">
                <input name="id" type="hidden" id="id" class="form-control" value="<?= $forfait["id"] ?>" required>
                <input type="submit" value="X">
            </form>
        <?php
        }
    }
}
if ($admin) {
    $forfaits = (new Forfait())->list();
    echo "<h2>Ajout FORFAIT</h2>";
    foreach ($forfaits as $forfait) {
        ?>
        <form method="post" action="newforfait.php?action=ajout">
            <input name="id_user" type="hidden" id="id_user" class="form-control" value="<?= $id ?>" required>
            <input name="id_forfait" type="hidden" id="id_forfait" class="form-control" value="<?= $forfait["id"] ?>" required>
            <input name="cearted_at" type="hidden" id="cearted_at" class="form-control" value="<?= date('d-m-Y') ?>" required>
        </form>
<?php
    }
}
?>


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