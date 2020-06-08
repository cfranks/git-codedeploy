<?php 
namespace Concrete\Package\Fruitful\Theme\Fruitful;
use Concrete\Core\Page\Theme\Theme;

class PageTheme extends Theme {

	public function registerAssets() {

	}

   	protected $pThemeGridFrameworkHandle = 'bootstrap3';

    public function getThemeBlockClasses(){

    }

    public function getThemeAreaClasses()
    {
       	// this adds custom classes to be added to an area.
	    /*
		return array(
            'Main' => array('area-content-accent')
        );
		*/
    }

    public function getThemeDefaultBlockTemplates(){
    	// this sets a block to use a custom template by default.            
   
    }

    public function getThemeEditorClasses()
    {
      
		 
		 
    }

}
