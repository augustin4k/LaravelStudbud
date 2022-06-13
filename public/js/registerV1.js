$(document).ready(function() {
    function showInvalidity() {
        let error = [];
        error["form1"] = 0;
        error["form2"] = 0;
        error["form3"] = 0;
        error["form4"] = 0;
        for (const key in error) {
            $("." + key + " input").each(function(index, element) {
                if ($(this).is(":invalid")) {
                    error[key]++;
                }
            });
            $("." + key).find(".alert-danger").each(function(index, element) {
                error[key]++;
            });
            let idParentForm = $("." + key).parent().attr("id");
            console.log(idParentForm);
            if (error[key] > 0)
                $("button[data-bs-target='#" + idParentForm + "'] .badge").html(error[key]);
        }
    }

    $("input").keyup(function(e) {
        showInvalidity(clickSubmit);
    });
    $('button, input').click(function(e) {
        showInvalidity(clickSubmit);
    });
    $("input, select").change(function(e) {
        showInvalidity(clickSubmit);
    });


});