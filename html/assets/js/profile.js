
document.addEventListener('DOMContentLoaded', function () {

    function reloadpage() {
        setInterval(function () {
            document.location.reload(true);
        }, 3000);

    }

    function submitajax(e) {
        e.preventDefault();

        let formData = new FormData();
        formData.append("id_user", {{ user.id }});

fetch("/user/admin/newline", {
    method: "post",
    body: formData
}).then(response => response.json()).then(json => console.log(json)).then(reloadpage());
return false;
};
var formlist = document.getElementById('presence').addEventListener('click', submitajax);
});