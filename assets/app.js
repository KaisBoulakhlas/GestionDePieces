import './styles/app.css';

// start the Stimulus application
import './bootstrap';
import 'bootstrap';
import {
   ajaxCustomer,
   ajaxGetPricePiece,
   AjaxModal,
   ajaxProvider,
   ajaxSelectMachine,
   ajaxSelectUserAndWorkStation
} from './ajax'
import { handleChangeType } from "./dropdownEvents";
import {formPrototype, formPrototypeOrderLine} from "./formPrototype";
import $ from "jquery";

window.addEventListener('load', () => {
   ajaxSelectMachine();
   ajaxSelectUserAndWorkStation();
   handleChangeType();
   AjaxModal();
   ajaxProvider();
   ajaxCustomer();
   formPrototype('#pieces_useds_collection','#add_pieces_used','.btn-piece_used_delete');
   formPrototype('#order_line_purchase_collection','#add_order_line_purchase','.btn-order_line_purchase_delete');
   formPrototype('#estimate_line_collection','#add_estimate_line','.btn-estimate_line_delete');
   formPrototype('#order_line_sale_collection','#add_order_line_sale','.btn-order_line_sale_delete');


   ajaxGetPricePiece('.form_select_piece','.form_select_piece, .quantity_order_line', '.quantity_order_line','.total_price',"/price/");
   ajaxGetPricePiece('.form_select_piece_estimate_line','.form_select_piece_estimate_line, .quantity_estimate_line', '.quantity_estimate_line','.total_price_estimate_line',"/priceLivrable/");


   $('#select_month').on('change',function(e){
      let $optionValue = $(e.target).val();
      const url = "/orderPurchaseByMonth/" + $optionValue

      $.get(url, function(orderPurchase){
         console.log(orderPurchase)
      })
   })
})


