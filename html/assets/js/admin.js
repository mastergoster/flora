document.addEventListener('DOMContentLoaded', function () {
    function tableNewLine(datas) {
        if (datas.err) {
            alert("error")
        } else {
            document.getElementById("recap").insertRow([2]).innerHTML =
                "<th scope=\"row\">" + datas.id + "</th>" +
                "<td>" + datas.description + "</td>" +
                "<td>" + datas.credit + "</td>" +
                "<td>" + datas.debit + "</td>" +
                "<td>" + datas.date + "</td>" +
                "<td>" + datas.createdAt + "</td>"

        }
    }
    function eraseForm(e) {
        var form = e.target
        form.description.value = ""
        form.credit.value = "0"
        form.debit.value = "0"
        current_datetime = new Date()
        if ((current_datetime.getMonth() + 1).length === 1) {
            month = current_datetime.getMonth() + 1
        } else {
            month = "0" + (current_datetime.getMonth() + 1)
        }
        form.date.value = current_datetime.getFullYear() + "-" + month + "-" + current_datetime.getDate()
        //console.log(current_datetime.getFullYear() + "-" + month + "-" + current_datetime.getDate())
    }
    function submitajax(e) {
        e.preventDefault();

        let form = e.target;
        fetch(form.action, {
            method: form.method,
            body: new FormData(form)
        }).then(response => response.json())
            .then(json => tableNewLine(json))
            .then(eraseForm(e));
        return false;
    };

    var formlist = document.getElementsByTagName('form');
    Array.from(formlist).forEach(element => {
        element.addEventListener('submit', submitajax);
    });


});