import './styles/app.css';

// start the Stimulus application
import './bootstrap';
import $ from 'jquery';
import 'bootstrap';
import { toggleActiveClassNav } from './nav';

window.addEventListener('load', () => {
    toggleActiveClassNav();
})

$(document).ready(function () {
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



});

