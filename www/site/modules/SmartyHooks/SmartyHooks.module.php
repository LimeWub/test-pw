<?php

class SmartyHooks extends WireData implements Module
{

    /**
     * getModuleInfo is a module required by all modules to tell ProcessWire about them
     *
     * @return array
     *
     */
    public static function getModuleInfo()
    {
        return array(
            'title' => 'Smarty Hooks',
            'version' => 100,
            'summary' => '',
            'singular' => true,
            'autoload' => true,
        );
    }


    /**
     * Initialize the module
     *
     * ProcessWire calls this when the module is loaded. For 'autoload' modules, this will be called
     * when ProcessWire's API is ready. As a result, this is a good place to attach hooks.
     *
     */
    public function init()
    {
        $this->addHookAfter('TemplateEngineSmarty::initSmarty', $this, 'hookSmarty');
    }


    /**
     * Register the "chunck" function in smarty
     *
     * @param HookEvent $event
     */
    public function hookSmarty(HookEvent $event)
    {
        $smarty = $event->arguments('smarty'); // This is the smarty object for any $view API variable
        $smarty->registerPlugin('function', 'chunk', array($this, 'smartyCompilerChunk'));
    }


    /**
     * ProcessWire Chunks using smarty code
     *
     * Uses code from this: http://modules.processwire.com/modules/template-engine-factory/#chunks
     *
     * @requires ProcessWire Template Engine Smarty Module
     * @package 999
     * @author Myrto C Kontouli
     */

    function smartyCompilerChunk($args = array(), &$smarty)
    {
        $chunk_file = "";
        //if we don't have the expected option, error out and do nothing
        if (!(array_key_exists("file", $args) && (($chunk_file = trim(trim($args["file"]), "'")) !== ""))) {
            //error
            //same as {include}:
            $error = '"{chunk}" missing "file" attribute';
            throw new SmartyException($error);

            return;
        }
        //else

        $tplVars_obj = $smarty->tpl_vars;

        $tplVars = array();
        foreach ($tplVars_obj as $key => $var) {
            $tplVars[$key] = $var->value;
        }

        //get the factory from the template vars of smarty (if cleaner way is found, feel free to replace this)
//        $factory = $tplVars["factory"];
        $factory = $this->wire('factory');
        $chunk = $factory->chunk($chunk_file);

        // You can pass any variables to the chunk, they become available as locally scoped variables
        // We will pass the variables already in the template!
        //get all the parent template vars, put in an array (in case we want to load them, in the chunk, later)
        $chunk->set('tpl_vars', $tplVars);

        // Returns the markup from the associated template file (view) from the active TemplateEngine. By default, the chunk's template file is looked up at the same path as the chunk file, relative to the storage location of the active template engine, e.g. /site/templates/views/chunks/nav-item.tpl
        return $chunk->render();
    }
}