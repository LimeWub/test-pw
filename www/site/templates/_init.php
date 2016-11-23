<?php
/*
 * COMMON VAR SETTING
 */



/*
 * UTILITY FUNCTIONS
 */

/*
 * Load array of data into view
 */
function loadVars($view,$data){ 
    foreach ($data as $key => $value){
        $view->set($key,$value);
    }
}
