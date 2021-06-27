import './styles/app.css';

// start the Stimulus application
import './bootstrap';
import 'bootstrap';
import {AjaxModal, ajaxSelectMachine, ajaxSelectUserAndWorkStation} from './ajax'
import { handleChangeType } from "./dropdownEvents";
import {formPrototype} from "./formPrototype";

window.addEventListener('load', () => {
   ajaxSelectMachine();
   ajaxSelectUserAndWorkStation();
   handleChangeType();
   AjaxModal();
   formPrototype();

})


