import './styles/app.css';

// start the Stimulus application
import './bootstrap';
import 'bootstrap';
import { ajaxModalHome,ajaxSelectMachine,ajaxSelectUserAndWorkStation} from './ajax'
import { handleChangeType } from "./dropdownEvents";
import $ from "jquery";


window.addEventListener('load', () => {
   ajaxModalHome();
   ajaxSelectMachine();
   ajaxSelectUserAndWorkStation();
   handleChangeType();
})

