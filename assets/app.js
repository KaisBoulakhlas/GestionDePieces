import './styles/app.css';

// start the Stimulus application
import './bootstrap';
import 'bootstrap';
import {ajaxGetPricePiece, AjaxModal, ajaxProvider, ajaxSelectMachine, ajaxSelectUserAndWorkStation} from './ajax'
import { handleChangeType } from "./dropdownEvents";
import {formPrototype, formPrototypeOrderLine} from "./formPrototype";

window.addEventListener('load', () => {
   ajaxSelectMachine();
   ajaxSelectUserAndWorkStation();
   handleChangeType();
   AjaxModal();
   ajaxProvider();
   formPrototype('#pieces_useds_collection','#add_pieces_used','.btn-piece_used_delete');
   formPrototype('#order_line_purchase_collection','#add_order_line_purchase','.btn-order_line_purchase_delete');
   formPrototype('#estimate_line_collection','#add_estimate_line','.btn-estimate_line_delete');


   ajaxGetPricePiece('.form_select_piece','.form_select_piece, .quantity_order_line', '.quantity_order_line','.total_price',"/price/");
   ajaxGetPricePiece('.form_select_piece_estimate_line','.form_select_piece_estimate_line, .quantity_estimate_line', '.quantity_estimate_line','.total_price_estimate_line',"/priceLivrable/");

})


