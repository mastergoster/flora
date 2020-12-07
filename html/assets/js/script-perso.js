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
	let replyMsg = document.getElementById("replyMsgUp");

	if (newMsg != null) {
		newMsgUp.addEventListener('click',() => {
			newMsg.classList.remove("d-none")
			newMsg.style.display = "block";
			newMsgUp.style.display = "none"
		});
	};

	if (newMsg != null) {
		newMsgDown.addEventListener('click',() => {
			// document.getElementById('destName').value="";
			// document.getElementById('destNessage').value="";
			// document.getElementById('destinataire').value="";
			newMsg.style.display = "none";
			newMsgUp.style.display = "inline-block"
		});
	};

	if (newMsg != null && replyMsg != null) {
		replyMsg.addEventListener('click',() => {
			newMsg.classList.remove("d-none")
			newMsg.style.display = "block";
			newMsgUp.style.display = "none"
		});
	};
});