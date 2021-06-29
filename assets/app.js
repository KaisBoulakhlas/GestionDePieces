import './styles/app.css';

// start the Stimulus application
import './bootstrap';
import 'bootstrap';
import {AjaxModal, ajaxSelectMachine, ajaxSelectUserAndWorkStation} from './ajax'
import { handleChangeType } from "./dropdownEvents";
import {formPrototype, formPrototypeOrderLine} from "./formPrototype";

window.addEventListener('load', () => {
   ajaxSelectMachine();
   ajaxSelectUserAndWorkStation();
   handleChangeType();
   AjaxModal();
   formPrototype();
   formPrototypeOrderLine();

   //Ajax
   $('body').on('change','.form_select_piece, .quantity_order_line', function(e){
      let optionSelected = $(e.target).parents('.item').find('option:selected').val();
      const quantity = parseInt($(e.target).parents('.item').find('.quantity_order_line').val());
      const $totalPrice = $(e.target).parents('.item').find('.total_price')
      const url = "/price/" + optionSelected

      $.get(url, function(priceCatalog){
         $totalPrice.html(parseFloat(priceCatalog) * quantity + "€");
      })

   })
})


