<?php 
namespace Concrete\Package\BlockDesigner\Controller\SinglePage\Dashboard\Blocks;

defined('C5_EXECUTE') or die(_("Access Denied."));

use \Concrete\Core\Page\Controller\DashboardPageController;
use Loader;
use Block;
use BlockType;
use BlockTypeList;
use TaskPermission;
use Environment;
use Exception;
use Core;

class BlockOrder extends DashboardPageController
{
    public $helpers = array('form');
    public $packageHandle = 'block_designer';

    private function getBlockTypeSets()
    {
        $db = \Database::get();
        $blockTypeSets = $db->GetAll('SELECT * FROM BlockTypeSets ORDER BY btsDisplayOrder');
        $options = array();
        foreach ($blockTypeSets as $blockTypeSet) {
            $blockTypeSet['name'] = t($blockTypeSet['btsName']);
            $blockTypeSet['blocks'] = array();
            $options[$blockTypeSet['btsID']] = $blockTypeSet;
        }
        return $options;
    }

    private function getBlockTypes($btsID = false)
    {
        $db = \Database::get();
        $whereValues = array('0');
        if(!$btsID){
            $queryString = 'SELECT *, bt.btID FROM BlockTypes bt LEFT JOIN BlockTypeSetBlockTypes btsbt ON btsbt.btID = bt.btID WHERE btIsInternal = ? ORDER BY btDisplayOrder';
        }
        else {
            $whereValues[] =$btsID;
            $queryString = 'SELECT *, bt.btID FROM BlockTypes bt LEFT JOIN BlockTypeSetBlockTypes btsbt ON btsbt.btID = bt.btID WHERE btIsInternal = ? AND btsbt.btsID = ? ORDER BY btDisplayOrder';
        }
        $blockTypes = $db->GetAll($queryString, $whereValues);
        $options = array();
        foreach ($blockTypes as $blockType) {
            $blockType['name'] = t($blockType['btName']);
            $options[$blockType['btID']] = $blockType;
        }
        return $options;
    }

    public function on_start(){
        $al = \Concrete\Core\Asset\AssetList::getInstance();
        $al->register('css', 'block-designer-order-view', 'css/block_order.view.css', array(), $this->packageHandle);
        $al->register('javascript', 'block-designer-order-view', 'js/block_order.view.js', array(), $this->packageHandle);
    }

    public function view()
    {
        $this->requireAsset('css', 'block-designer-order-view');
        $this->requireAsset('javascript', 'block-designer-order-view');
        $blockTypeSets = $this->getBlockTypeSets();
        $blockTypeSets['other'] = array(
            'btsID'  => 'other',
            'name'   => t("Other"),
            'blocks' => array(),
        );
        if ($blockTypes = ($this->getBlockTypes())) {
            $btl = new BlockTypeList();
            $btInstalledArray = $btl->get();
            $ci = Core::make('helper/concrete/urls');
            foreach ($btInstalledArray as $k => $_bt) {
                $btIcon = $ci->getBlockTypeIconURL($_bt);
                $btID = $_bt->getBlockTypeID();
                if (isset($blockTypes[$btID])) {
                    $blockTypes[$btID]['icon'] = $btIcon;
                }
            }
            foreach ($blockTypes as $blockType) {
                if (isset($blockTypeSets[$blockType['btsID']])) {
                    $blockTypeSets[$blockType['btsID']]['blocks'][] = $blockType;
                } else {
                    $blockTypeSets['other']['blocks'][] = $blockType;
                }
            }
        }
        $this->set('blockTypeSets', $blockTypeSets);
    }

    public function update()
    {
        if (isset($_POST['btsID'], $_POST['order']) && is_array($_POST['order']) && !empty($_POST['order'])) {
            $db = \Database::get();
            if ($_POST['btsID'] == 'other') {
                $blockTypes = $this->getBlockTypes();
            } else {
                $blockTypes = $this->getBlockTypes($_POST['btsID']);
            }
            $i = 0;
            foreach ($_POST['order'] as $v) {
                if (isset($blockTypes[$v])) {
                    $db->Execute('UPDATE BlockTypes SET btDisplayOrder = ? WHERE btID = ?', array($i, $v));
                    $i++;
                }
            }
        }
        exit;
    }
}
