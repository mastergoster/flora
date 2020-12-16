document.addEventListener('DOMContentLoaded', function () {

    function pavetactile(e) {
        this.style.backgroundColor = "#18bfef";
        var touche = this
        let champsInput = document.getElementById("inputcode")
        setTimeout(function () {
            touche.style.removeProperty("background-color");
        }, 100)
        if (this.innerText == "X") {
            champsInput.value = "" // value.substr(0, value.length - 1)
        } else {
            if (champsInput.value.length < 4) {
                champsInput.value += this.innerText
            }
        }
        if (champsInput.value.length == 4) {
            document.getElementById('okvalide').style.display = "flex";
            // document.getElementById('okvalide').click();
        }
        if (champsInput.value.length < 4) {
            document.getElementById('okvalide').style.display = "none";
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
            element.style.backgroundColor = "#18bfef";
            setTimeout(function () {
                element.style.backgroundColor = background;
            }, 100)
            element.click();
        });
    });

    let clicDisplay = document.getElementsByClassName('clicDisplay');
    let checkbox = document.getElementsByClassName('checkbox');
    let closeTactile = document.getElementById('closeTactile');
    Array.from(clicDisplay).forEach(element => {
        element.addEventListener('mouseover', (e) => {
            document.getElementById("bg-image").style.filter = "blur(1rem)"
            // document.getElementById("bg-image").style.transition = "all 0.5s ease"
            //document.getElementById("iFrameTV").style.display = "none"
            // document.getElementById("iFrameTV").style.transition = "all 1s ease"
            document.getElementById('codeTactile').style.left = "683px"
            // document.getElementById('codeTactile').style.transition = "all 1.5s ease"
            closeTactile.style.right = "10px"
            // closeTactile.style.transition = "all 1.5s ease"
            element.click();
        });
    });


    closeTactile.addEventListener('click', () => {
        document.getElementById("inputcode").value = ""
        document.getElementById("bg-image").style.filter = ""
        // document.getElementById("bg-image").style.transition = "all 0.5s ease"
        //document.getElementById("iFrameTV").style.display = "flex"
        // document.getElementById("iFrameTV").style.transition = "all 1s ease"
        document.getElementById('codeTactile').style.left = "0px"
        // document.getElementById('codeTactile').style.transition = "all 1.5s ease-in"
        closeTactile.style.right = "-80px"
        // closeTactile.style.transition = "all 1s ease-in"
        document.getElementById('okvalide').style.display = "none"
        Array.from(checkbox).forEach(element => {
            element.checked = false
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