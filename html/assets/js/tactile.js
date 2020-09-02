document.addEventListener('DOMContentLoaded', function () {
    function pavetactile(e) {
        this.style.backgroundColor = "red";
        var touche = this
        setTimeout(function () {
            touche.style.removeProperty("background-color");
        }, 100)
        if (this.innerText == "sup") {
            var value = document.getElementById("inputcode").value
            document.getElementById("inputcode").value = "" // value.substr(0, value.length - 1)


        } else {
            document.getElementById("inputcode").value += this.innerText
        }
        if (document.getElementById("inputcode").value.length == 4) {
            document.getElementById('okvalide').click();
        }


    }
    var pave = document.getElementsByClassName('touche');
    Array.from(pave).forEach(element => {
        element.addEventListener('mouseover', pavetactile);
    });
    // document.getElementById('okvalide').addEventListener('mouseover', (e) => {
    //     var background = document.getElementById('okvalide').style.backgroundColor;
    //     document.getElementById('okvalide').style.backgroundColor = "red";
    //     setTimeout(function () {
    //         document.getElementById('okvalide').style.backgroundColor = background;
    //     }, 100)
    //     document.getElementById('okvalide').click();
    // });

    var clicouille = document.getElementsByClassName('clicouille');
    Array.from(clicouille).forEach(element => {
        element.addEventListener('mouseover', (e) => {
            var background = element.style.backgroundColor;
            element.style.backgroundColor = "red";
            setTimeout(function () {
                element.style.backgroundColor = background;
            }, 100)
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
function viewscroll(saut) {
    var container = document.getElementsByClassName('container')[0];
    var position = container.scrollTop;
    container.scroll(0, position + saut);
}

var refresh = setInterval(function () {
    document.location.reload(true);
}, 60000);