<?php  
namespace Concrete\Package\Problog\Controller\Tools;

use Loader;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use \Concrete\Core\Controller\Controller as RouteController;

class NabAttribute extends RouteController
{

    /**
     * render Add Blog dialog
     */
    public function render()
    {
        if ($_REQUEST['akID']) {
            $akID = $_REQUEST['akID'];
            if ($_REQUEST['akID']) {
                if ($_REQUEST['akID'] == 'same_tags') {
                    $key = CollectionAttributeKey::getByHandle('tags');
                    $akID = $key->akID;
                }
            }
            $html = '<fieldset>';
            $db = loader::db();
            $r = $db->execute("SELECT * FROM atSelectOptions WHERE akID = ?",array($akID));
            while ($row = $r->fetchrow()) {
                $id = $row['ID'];
                $options[$id] = $row['value'];
            }
            if (is_array($options)) {
                foreach ($options as $key=>$option) {
                    $html .= '<input type="checkbox" name="fields[]" value="'.$option.'">'.$option.'<br/>';
                }
            }
            $html .= '</fieldset>';
            //print json_encode($options);
            print $html;
            exit;
        }
    }
}