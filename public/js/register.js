$(document).ready(function() {

    // pentru custom css
    $(".card-header").addClass("fw-bold");

    // pentru adaugarea valoarea checkbox intrun input citabil userului
    $(".btn-check").click(function() {
        let $tip_user_id_checbox = $(this).attr("id");
        if ($tip_user_id_checbox === "student0-info") {
            $("input[name='tip_user'").val("student 0");
        } else if ($tip_user_id_checbox === "student1-info") {
            $("input[name='tip_user'").val("student 1");
        } else if ($tip_user_id_checbox === "profesor-info") {
            $("input[name='tip_user'").val("profesor");
        } else if ($tip_user_id_checbox === "universitate-info") {
            $("input[name='tip_user'").val("universitate");
        }
        $all_inputs_collapse_not_required();
        $id_button = $(this).attr("id") + "-collapse";
        if ($id_button !== "student0-info-collapse") {
            $("#" + $id_button).find("input").each(function(index, element) {
                $(this).prop("required", true);
                $("input[id*='prezent']").each(function(index, element) {
                    $(this).prop("required", false);
                });
            });
        }
        $(".multi-collapse-register").each(function(index, element) {
            if ($(this).attr("id") !== $id_button) {
                $(this).removeClass("show");
            }
        });
        color_with_badge($click_buton_finish);
    });

    // LUCRUL CU VALIDAREA INPUTURILOR INAINTE DE TRIMITERE (pentru user friendly interface)

    // aici fac not required inputurile ascunse, deoarece la un submit, sistemul ma poate pune sa introduc si aceste inputuri,
    // care sunt ascunse de collapse. Inputurile ascunse de collapse vor fi ulterior ignorate si nu puse in baza de date.s
    function $all_inputs_collapse_not_required() {
        $(".form2").each(function(index, element) {
            $(this).find("input").each(function(index, element) {
                $(this).prop("required", false);
            });
        });
    };
    $all_inputs_collapse_not_required();

    // verific daca am tastat pe submit pentru a nu colora de-odata badg-urile
    $click_buton_finish = 0;
    $("button[name='finish-register']").click(function() {
        $click_buton_finish = 1;
        color_with_badge($click_buton_finish);
    });

    // functia pentru verificarea inputurilor nevalide si accentuarea compartimentlui unde se afla aceste inptuuri
    function color_with_badge($click_buton_finish) {
        $(".tab-pane").each(function(index, element) {
            $invalid_inputs_count = 0;
            $(this).find("input, select").each(function(index, element) {
                if ($(this).is(":invalid")) {
                    $invalid_inputs_count++;
                }
            });
            if ($click_buton_finish == 1) {
                if ($invalid_inputs_count > 0) {
                    $("#" + $(this).attr("id") + "-tab").children(".badge").removeClass("d-none");
                    $("#main-alert").removeClass("d-none");
                    $("#" + $(this).attr("id") + "-tab").children(".badge").html($invalid_inputs_count);
                } else {
                    $("#" + $(this).attr("id") + "-tab").children(".badge").addClass("d-none");
                    $("#main-alert").addClass("d-none");
                    $("#" + $(this).attr("id") + "-tab").children(".badge").html(0);
                }
            }
        });
    };

    // modificarea in timp real a situatiei cu validarea cand modific vreun input
    $("input").keyup(function() {
        color_with_badge($click_buton_finish);
    });
    $("input").change(function() {
        color_with_badge($click_buton_finish);
    });
    $("button[name='introdu-nr-student'], button[name='introdu-nr-profesor']").click(function(e) {
        color_with_badge($click_buton_finish);
    });

    // sa nu sa se seteze valoare mai mica decat 0 pentru unele inputuri
    $("input[name='nr-institutii-profesor'], input[name='nr-institutii-student']").keyup(function(e) {
        if ($(this).val() < 1) {
            $(this).val(1);
        }
    });

    // validare >> selectul pentru start sa nu fie mare decat cel final
    $(".range-container input[name*='finish'], .range-container input[name*='start'], input[name='Data_nastere']").change(function(e) {
        let $value_start;
        let $value_finish;
        let $id_input_finish;
        $value_start = $(this).parent().parent().parent().find("input[name*='start']").val();
        $value_finish = $(this).parent().parent().parent().find("input[name*='finish']").val();
        $id_input_finish = $(this).parent().parent().parent().find("input[name*='finish']").attr("id");

        if ($value_finish < $value_start) {
            $(".range-container " + this).removeClass("d-none");
            $("#" + $id_input_finish).get(0).setCustomValidity('Invalid');
        } else {
            $(".range-container " + this).addClass("d-none");
            $("#" + $id_input_finish).get(0).setCustomValidity('');
        }
        // aici tot modific in timp real situatia cu validarea pentru select
        color_with_badge($click_buton_finish);
    });

    // validarea>> verific timp real daca parolele ce le intrdoc sunt egale
    $("input[name='confirma-parola'], input[name='parola']").keyup(function(e) {
        let $parola = $("input[name='parola']").val();
        let $confirma_parola = $("input[name='confirma-parola']").val();
        if ($parola !== $confirma_parola) {
            $(".alert-parole").removeClass("d-none");
            $("#confirma-parola").get(0).setCustomValidity('Invalid');

        } else if (($parola == $confirma_parola) && ($confirma_parola.length >= 4) && ($confirma_parola.length <= 12)) {

            $("#confirma-parola").get(0).setCustomValidity('');
            $(".alert-parole").addClass("d-none");
        } else if ($parola === $confirma_parola)
            $(".alert-parole").addClass("d-none");
        color_with_badge($click_buton_finish);
    });

    // introduc in input start de la universitatea valoarea de la data nasterii
    $("input#Data_nastere").change(function(e) {
        e.preventDefault();
        $("input[name='an-start-institutie']").val($(this).val());
        let $value_start;
        let $value_finish;
        let $id_input_finish;
        $value_start = $("input[name='an-start-institutie']").parent().parent().parent().find("input[name*='start']").val();
        $value_finish = $("input[name='an-finishА-institutie']").parent().parent().parent().find("input[name*='finish']").val();
        $id_input_finish = $(this).parent().parent().parent().find("input[name*='finish']").attr("id");

        if ($value_finish < $value_start) {
            $(".range-container .alert-start-final").removeClass("d-none");
            $("#" + $id_input_finish).get(0).setCustomValidity('Invalid');
        } else {
            $(".range-container .alert-start-final").addClass("d-none");
            $("#" + $id_input_finish).get(0).setCustomValidity('');
        }
        // aici tot modific in timp real situatia cu validarea pentru select
        color_with_badge($click_buton_finish);
    });

    // pentru lucrul cu avatarul
    $("button[name='fake-upload']").click(function(e) {
        $("input[type='file']").click();
    });
    $("input[type='file']").change(function(e) {
        $file_name = URL.createObjectURL(e.target.files[0]);
        console.log($file_name);
        $("#avatar-register").load("/register #avatar-register>*").prop("src", $file_name);
        $("button[name='sterge-avatar']").prop("disabled", false);
    });
    $("button[name='sterge-avatar']").click(function(e) {
        $("input[type='file']").val("");
        $("#avatar-register").load("/register #avatar-register>*").prop("src", "/img/no-avatar.png");
        $(this).prop("disabled", true);
    });

});