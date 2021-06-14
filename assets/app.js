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

});

