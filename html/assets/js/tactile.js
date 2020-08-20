document.addEventListener('DOMContentLoaded', function () {
    function pavetactile(e) {
        if (this.innerText == "sup") {
            var value = document.getElementById("inputcode").value
            document.getElementById("inputcode").value = "" // value.substr(0, value.length - 1)


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
document.addEventListener('DOMContentLoaded', function () {
    function validate(data) {
        if (data.permission) {
            document.getElementById("popup").style.backgroundColor = "#c0392b";
            document.getElementById("popup").innerHTML = "<p>Erreur</p>";
        }
        document.getElementById("popup").style.display = "flex";
        setInterval(function () {
            document.location.reload(true);
        }, 2000);

    }

    function submitajax(e) {
        e.preventDefault();

        let form = e.target;

        fetch(form.action, {
            method: form.method,
            body: new FormData(form)
        }).then(response => response.json())
            // .then(json => console.log(json))
            .then(json => validate(json));


        return false;
    };

    var formlist = document.getElementsByTagName('form');
    Array.from(formlist).forEach(element => {
        element.addEventListener('submit', submitajax);
    });


});
var refresh = setInterval(function () {
    document.location.reload(true);
}, 60000);