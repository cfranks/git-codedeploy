<?php  
namespace Concrete\Package\Problog\Attribute\ComposerOptimizer;

use \Concrete\Core\Attribute\DefaultController;
use Loader;

class Controller extends DefaultController
{
    protected $searchIndexFieldDefinition = array(
        'type' => 'string',
        'options' => array('default' => null, 'notnull' => false)
    );

    public function form()
    {
        $blogify = Loader::helper('blogify');
        $settings = $blogify->getBlogSettings();
        $this->set('settings',$settings);
    }
}
