import $ from "jquery";

export const AjaxModal = () => {
    const modalGeneric = $('#modalGeneric')
    modalGeneric.on('shown.bs.modal', function (e) {
        const url = $(e.relatedTarget).data("url")
        $.get(url, function(res){
            $('#modal-generic-content').html($(res).find('.modal-content'))
        })
    })

    modalGeneric.on('hidden.bs.modal', function (e) {
        $('#modal-generic-content').html(
            `<div class="container p-5 text-center">
               <div class="spinner-border text-primary" role="status">
                   <span class="sr-only">Loading...</span>
               </div>
           </div>`
        )
    })
}
export const ajaxSelectMachine = () => {

    const $workStation = $('#operation_workStation');
    const operationMachine  = document.querySelector('#operation_machine')
    const operationMachineAdd  = document.querySelector('#container_add_operation #operation_machine')
    operationMachineAdd ? operationMachineAdd.setAttribute('disabled','disabled') : null
    $workStation.change(function() {
        const $form = $(this).closest('form');
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
