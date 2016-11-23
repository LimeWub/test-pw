<?php
/**
* ProcessWire Chunks using smarty code
*
* Uses code from this: http://modules.processwire.com/modules/template-engine-factory/#chunks
* @requires ProcessWire Template Engine Smarty Module
* @package 999
* @author Myrto C Kontouli 
*/

function smarty_compiler_chunk($args=array(),&$smarty){
     
    $chunk_file = "";
    //if we don't have the expected option, error out and do nothing
    if (!(array_key_exists("file",$args) && (($chunk_file = trim(trim($args["file"]),"'")) !== "")))
    {
        //error
        //same as {include}:
        $error = '"{chunk}" missing "file" attribute';
        throw new SmartyException($error);
        return;
    }
    //else
    
    $tplVars_obj = $smarty->tpl_vars;
    
    $tplVars = array();
    foreach($tplVars_obj as $key => $var){
        $tplVars[$key] = $var->value;
    }
    
    //get the factory from the template vars of smarty (if cleaner way is found, feel free to replace this)
    $factory = $tplVars["factory"];

    $chunk = $factory->chunk($chunk_file);
    
    // You can pass any variables to the chunk, they become available as locally scoped variables
    // We will pass the variables already in the template!
    //get all the parent template vars, put in an array (in case we want to load them, in the chunk, later)
    $chunk->set('tpl_vars', $tplVars);
 
    // Returns the markup from the associated template file (view) from the active TemplateEngine. By default, the chunk's template file is looked up at the same path as the chunk file, relative to the storage location of the active template engine, e.g. /site/templates/views/chunks/nav-item.tpl
   return $chunk->render();
}
