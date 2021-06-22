import $ from "jquery";

export const ajaxModalHome = () => {
    $('#new_range').submit(function (e) {
        const newUrl = "/home/range/add";
        e.preventDefault();

        $.ajax({
            type: $(this).attr('method'),
            url: newUrl,
            data: $(this).serialize(),
            beforeSend: function(){
                $('#new_range .modal-body').replaceWith(
                    `<div class="container p-5 text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>`
                );
            },
            success: function () {
                $('.spinner-border').css('display','none');
                $('#addRangeModal').modal('hide');
                window.location = '/home';
            },
            error: function (xhr) {
                console.log(xhr, "Erreur lors de l'ajout");
            }
        });
    });

    $('#new_workstation').submit(function (e) {
        const newUrl = "workstation/add";
        e.preventDefault();

        $.ajax({
            type: $(this).attr('method'),
            url: newUrl,
            data: $(this).serialize(),
            beforeSend: function(){
                $('#new_workstation .modal-body').replaceWith(
                    `<div class="container p-5 text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>`
                );
            },
            success: function () {
                $('.spinner-border').css('display','none');
                $('#addWorkStationModal').modal('hide');
                window.location = '/workstations';
            },
            error: function (xhr) {
                console.log(xhr, "Erreur lors de l'ajout");
            }
        });
    });

    $('#new_range_realisation').submit(function (e) {
        const newUrl = "/rangeRealisation/add";
        e.preventDefault();

        $.ajax({
            type: $(this).attr('method'),
            url: newUrl,
            data: $(this).serialize(),
            beforeSend: function(){
                $('#new_range_realisation .modal-body').replaceWith(
                    `<div class="container p-5 text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>`
                );
            },
            success: function () {
                $('.spinner-border').css('display','none');
                $('#addRangeRealisationModalLabel').modal('hide');
                window.location = '/home';
            },
            error: function (xhr) {
                console.log(xhr, "Erreur lors de l'ajout");
            }
        });
    });

    $('form[id^="edit_range"]').submit(function (e) {
        const newUrl = "home/range/edit/" + $(this).attr('data-whatever');
        e.preventDefault();
        $.ajax({
            type: $(this).attr('method'),
            url: newUrl,
            data: $(this).serialize(),
            beforeSend: function(){
                $('form[id^="edit_range"] .modal-body').replaceWith(
                    `<div class="container p-5 text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>`
                );
            },
            success: function () {
                $('.spinner-border').css('display','none');
                $('div[aria-labelledby="editRangeModalLabel"]').modal('hide');
                window.location="/home";
            },
            error: function (xhr) {
                console.log(xhr, "Erreur lors de la modification");
            }
        });
    });
    $('form[id^="edit_workstation"]').submit(function (e) {
        const newUrl = "workstation/edit/" + $(this).attr('data-whatever');
        e.preventDefault();
        $.ajax({
            type: $(this).attr('method'),
            url: newUrl,
            data: $(this).serialize(),
            beforeSend: function(){
                $('form[id^="edit_workstation"] .modal-body').replaceWith(
                    `<div class="container p-5 text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>`
                );
            },
            success: function () {
                $('.spinner-border').css('display','none');
                $('div[aria-labelledby="editWorkStationModalLabel"]').modal('hide');
                window.location="/workstations";
            },
            error: function (xhr) {
                console.log(xhr, "Erreur lors de la modification");
            }
        });
    });

    $('form[id^="edit_realisation_range"]').submit(function (e) {
        const newUrl = "rangeRealisation/edit/" + $(this).attr('data-whatever');
        e.preventDefault();
        $.ajax({
            type: $(this).attr('method'),
            url: newUrl,
            data: $(this).serialize(),
            beforeSend: function(){
                $('form[id^="edit_realisation_range"] .modal-body').replaceWith(
                    `<div class="container p-5 text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>`
                );

            },
            success: function () {
                $('.spinner-border').css('display','none');
                $('div[aria-labelledby="editRangeRealisationModalLabel"]').modal('hide');
                window.location = '/home';
            },
            error: function (xhr) {
                console.log(xhr, "Erreur lors de la modification");
            }
        });

        const collapse = document.querySelector('.collapse');
        collapse.className.replace('collapse','collapse show');

        window.scroll({
            top: 200,
            behavior: 'smooth'
        });
    });
}

export const ajaxSelectMachine = () => {

    const $workStation = $('#operation_workStation');
    const operationMachine  = document.querySelector('#operation_machine')
    const operationMachineAdd  = document.querySelector('#container_add_operation #operation_machine')
    operationMachineAdd ? operationMachineAdd.setAttribute('disabled','disabled') : null
    $workStation.change(function() {
        const $form = $(this).closest('form');
        const time =$('#operation_time');
        let data = {};
        data[$workStation.attr('name')] = $workStation.val();
        const selectWorkStation = document.querySelector("#container_add_operation #operation_workStation")
        let index = selectWorkStation && selectWorkStation.selectedIndex;
        if(index > 0){
            operationMachineAdd ? operationMachineAdd.removeAttribute('disabled') : null
        }

        $.ajax({
            url : $form.attr('action'),
            type: $form.attr('method'),
            data : data,
            success: function(html) {
                console.log(data)
                const operationMachine = $('#operation_machine');
                operationMachine.replaceWith(
                    $(html).find('#operation_machine')
                );
            },
            error: function(xhr) {
                console.log(xhr)
            }
        }).done(function() {
            if(index === 0){
                operationMachineAdd ? operationMachineAdd.setAttribute('disabled','disabled') : null
            }
        });

    });
}

export const ajaxSelectUserAndWorkStation = () => {
    $(document).on('change', '#realisation_userWorkStation, #realisation_workstation', function () {
        let $field = $(this)
        let $userWorkStationField = $('#realisation_userWorkStation')
        let $form = $field.closest('form')
        let target = '#' + $field.attr('id').replace('workstation', 'machine').replace('userWorkStation', 'workstation')
        // Les données à envoyer en Ajax
        let data = {}
        data[$userWorkStationField.attr('name')] = $userWorkStationField.val()
        data[$field.attr('name')] = $field.val()
        // On soumet les données
        $.post($form.attr('action'), data).then(function (data) {
            // On récupère le nouveau <select>
            let $input = $(data).find(target)
            // On remplace notre <select> actuel
            $(target).replaceWith($input)
        })
    })
}
