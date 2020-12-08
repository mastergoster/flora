document.addEventListener('DOMContentLoaded', function () {

	// Masquer le plan quand la souris n'est plus dessus
	let overlay = document.getElementById("planOverlay");
	let plan = document.getElementById("mapOpen");

	if (overlay != null || plan != null) {
	plan.addEventListener("mouseout", () => {overlay.style.display = "flex";});
	};


	// Masquer le bouton "retour en haut"
	let toUp = document.getElementById("scroll_to_top");

	if (toUp != null) {
	document.addEventListener('scroll',() => {
		if(window.pageYOffset > 1150) {
			toUp.classList.add("hidden");
		} else {
			toUp.classList.remove("hidden");
		}
	});
	};


	// Afficher la zone de nouveau message (messages.twig)
	let newMsg = document.getElementById("newMessageUser");
	let newMsgUp = document.getElementById("newMsgUp");
	let newMsgDown = document.getElementById("newMsgDown");
	let replysMsg = document.getElementsByClassName("replyMsgUp");
	let pageUp = document.getElementsByClassName("main-panel");
	let destinataire = document.getElementById("destinataire");


	if (newMsg != null) {
		newMsgUp.addEventListener('click',() => {
			newMsg.classList.remove("d-none")
			newMsg.style.display = "block";
			newMsgUp.style.display = "none"
		});
	};

	if (newMsg != null) {
		newMsgDown.addEventListener('click',() => {
			newMsg.style.display = "none";
			newMsgUp.style.display = "inline-block"
		});
	};

	if (newMsg != null && replysMsg != null) {
		
		Array.from(replysMsg).forEach(element => {
			element.addEventListener('click', function() {
				// console.log(this.title)
				newMsg.classList.remove("d-none")
				newMsg.style.display = "block"
				newMsgUp.style.display = "none"
				pageUp[0].scroll(0,0)
			});
		});

	};
});