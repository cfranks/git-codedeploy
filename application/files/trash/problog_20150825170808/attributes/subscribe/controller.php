<?php  
namespace Concrete\Package\Problog\Attribute\Subscribe;

use \Concrete\Core\Attribute\DefaultController;

class Controller extends DefaultController
{
    protected $searchIndexFieldDefinition = array(
        'type' => 'string',
        'options' => array('default' => null, 'notnull' => false)
    );

    public function form()
    {

    }
}
