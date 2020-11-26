// Masquer le plan quand la souris n'est plus dessus
let overlay = document.getElementById("planOverlay");
let plan = document.getElementById("mapOpen");

plan.addEventListener("mouseout", () => {overlay.style.display = "flex";});


// Masquer le bouton "retour en haut"
let toUp = document.getElementById("scroll_to_top");

document.addEventListener('DOMContentLoaded', function() {
	window.onscroll = function(ev) {
		document.toUp.className = (window.pageYOffset > 100) ? "cVisible" : "cInvisible";
	};
});