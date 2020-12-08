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
	// let replyIdDest = document.getElementById("replyIdDest");
	// let replyNameDest = document.getElementById("replyNameDest");
	let newDestinataire = document.getElementById("newDestinataire");


	if (newMsg != null) {
		newMsgUp.addEventListener('click',() => {
			// replyIdDest.value = ""
			// replyNameDest.value = ""
			// replyIdDest.disabled = "disabled"
			// replyNameDest.typey = "hidden"
			newMsg.classList.remove("d-none")
			newMsg.style.display = "block";
			newMsgUp.style.display = "none"
		});
	};

	if (newMsg != null) {
		newMsgDown.addEventListener('click',() => {
			newMsg.style.display = "none";
			newMsgUp.style.display = "inline-block"
			replyIdDest.value = ""
			replyNameDest.value = this.dataset.lastnameExpeditor.toUpperCase() + " " + this.dataset.firstnameExpeditor
		});
	};

	if (newMsg != null && replysMsg != null) {
		
		Array.from(replysMsg).forEach(element => {
			element.addEventListener('click', function() {
				// replyIdDest.value = this.dataset.idExpeditor
				console.log(this.dataset.idExpeditor)
				// replyNameDest.value = this.dataset.lastnameExpeditor.toUpperCase() + " " + this.dataset.firstnameExpeditor
				newMsg.classList.remove("d-none")
				newMsg.style.display = "block"
				newMsgUp.style.display = "none"
				pageUp[0].scroll(0,0)
			});
		});

	};
});