document.addEventListener('DOMContentLoaded', function () {

    function webClock() {
        // Time base
        const utc = new Date().getTime();
        const offset = 36E5;
        const standard = new Date(utc + offset);
    
        const q = standard.getUTCDay();
        const d = standard.getUTCDate();
        const m = standard.getUTCMonth();
        const y = standard.getUTCFullYear();
        const h = standard.getUTCHours();
        const mn = standard.getUTCMinutes();
        const s = standard.getUTCSeconds();
    
        const Q = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
    
        const M = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
    
        // [8] single value
        const Uh = h % 10;
        const Th = (h % 100 - h % 10) / 10;
    
        const Umn = mn % 10;
        const Tmn = (mn % 100 - mn % 10) / 10;
    
        const Us = s % 10;
        const Ts = (s % 100 - s % 10) / 10;
    
        // Full clock
        const fullClock = document.querySelectorAll('.full-clock');
        fullClock.forEach((_elem, i) => {
            fullClock[i].innerHTML = `${Q[q]} ${d} ${M[m]} ${y} - ${Th}${Uh} : ${Tmn}${Umn} : ${Ts}${Us}`;
        });
    }
    
    var clockTimer;
    
    // function clockCtrl() {
        const options = {
            root: undefined,
            rootMargin: '0px',
            threshold: 1
        };
    
        const elems = document.querySelectorAll('.clk-marker');
        function callback(elems, observer) {
            if (elems[0].isIntersecting) {
                clockTimer = setInterval(webClock, 1000);
                // observer.unobserve(elems[0].target);
            } else {
                clearInterval(clockTimer);
            }
        }
        const observer = new IntersectionObserver(callback, options);
        observer.observe(elems[0]);
    // }
    
});

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
        element.addEventListener('click', pavetactile);
    });
    document.getElementById('okvalide').addEventListener('mouseover', (e) => {
        var background = document.getElementById('okvalide').style.backgroundColor;
        document.getElementById('okvalide').style.backgroundColor = "red";
        setTimeout(function () {
            document.getElementById('okvalide').style.backgroundColor = background;
        }, 100)
        document.getElementById('okvalide').click();
    });

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
            // document.getElementById("bg-image").style.filter = "blur(1rem)"
            //document.getElementById("bg-image").style.transition = "all 0.5s ease"
            //document.getElementById("iFrameTV").style.display = "none"
            // document.getElementById("iFrameTV").style.transition = "all 1s ease"
            document.getElementById('codeTactile').style.left = "683px"
            document.getElementById('codeTactile').style.transition = "all 1.5s ease"
            closeTactile.style.right = "10px"
            closeTactile.style.transition = "all 1.5s ease"
            element.click();
        });
    });


    closeTactile.addEventListener('click', () => {
        document.getElementById("inputcode").value = ""
        //document.getElementById("bg-image").style.filter = ""
        //document.getElementById("bg-image").style.transition = "all 0.5s ease"
        //document.getElementById("iFrameTV").style.display = "flex"
        // document.getElementById("iFrameTV").style.transition = "all 1s ease"
        document.getElementById('codeTactile').style.left = "0px"
        document.getElementById('codeTactile').style.transition = "all 1.5s ease-in"
        closeTactile.style.right = "-80px"
        closeTactile.style.transition = "all 1s ease-in"
        document.getElementById('okvalide').style.display = "none"
        Array.from(checkbox).forEach(element => {
            element.checked = false
        });
    });

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

    sortHtml();

});

var form = new FormData();
form.append('function', 'tactileusers');
var refresh = setInterval(function () {
    fetch('/ajax', {
        method: "POST",
        body: form
    })
        .then(response => response.json())
        .then(data => {
            liste = new Object();
            [].slice.call(document.getElementById("container").children).forEach(function (element) {
                var name = element.getElementsByClassName("name")[0].innerText.split("\n")
                liste[element.id] = {
                    "id": element.id,
                    "presence": element.classList.contains("green") ? true : false,
                    "lastname": name[1],
                    "firstname": name[0],
                    "email": element.children.user_email.value
                }
            });
            compare(data, liste);
        });
}, 6000);


function compare(expected, actual) {
    for (let index = 0; index < Object.keys(expected).length + 10; index++) {
        if (typeof (expected[index]) == 'undefined') {
            if (document.getElementById(index)) {
                let elem = document.getElementById(index);
                elem.parentNode.removeChild(elem)
            }
        } else if (typeof (actual[index]) == 'undefined') {
            var label = document.createElement("label");
            label.innerHTML = '<input type="radio" name="user_email" class="checkbox" value="" required><div class="option_inner instagram"><div class="tickmark"></div><div class="icon"><i class="fas fa-user" style="font-size: 50px;"></i></div><div class="name"><br></div></div>';
            label.classList.add('option_item');
            label.classList.add('clicouille');
            label.classList.add('clicDisplay');
            if (expected[index].presence) {
                label.classList.add('green');
            }
            label.id = index;
            label.children.user_email.value = expected[index].email
            label.getElementsByClassName("name")[0].innerText = expected[index].firstname + "\n" + expected[index].lastname.toUpperCase()
            document.getElementById("container").appendChild(label)
        } else if (expected[index].presence !== actual[expected[index].id].presence) {
            if (expected[index].presence == true) {
                document.getElementById(index).classList.add("green")
            } else {
                document.getElementById(index).classList.remove("green")
            }
        }
    }
    sortHtml();
}
function sortHtml() {
    let parentNode = document.getElementById("container");
    var e = parentNode.children;
    [].slice
        .call(e)
        .sort(function (a, b) {
            let spacea = a.classList.contains("green") ? "AAAA" : ""
            let spaceb = b.classList.contains("green") ? "AAAA" : ""
            return (spacea + a.getElementsByClassName("name")[0].innerText).localeCompare(spaceb + b.getElementsByClassName("name")[0].innerText);
        })
        .forEach(function (val, index) {
            parentNode.appendChild(val);
        });
}
var liste = new Object();



function viewscroll(saut) {
    var container = document.getElementsByClassName('container')[0];
    var position = container.scrollTop;
    container.scroll(0, position + saut);
}
