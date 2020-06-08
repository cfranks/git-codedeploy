<?php 
namespace Concrete\Package\BlockDesigner\Controller\SinglePage\Dashboard\Blocks\BlockDesigner;

defined('C5_EXECUTE') or die(_("Access Denied."));

use \Concrete\Core\Page\Controller\DashboardPageController;

use Loader;
use Config;
use Package;
use BlockType;
use BlockTypeList;
use Environment;

class BlockConfig extends DashboardPageController
{
    public function view()
    {
        $env = Environment::get();
        $env->clearOverrideCache();
        $blockTypesFinal = array();
        $blockTypes = array();
        if ($availableBlocks = BlockTypeList::getAvailableList()) {
            foreach($availableBlocks as &$availableBlock){
                $availableBlock->installed = false;
                $blockTypes[] = $availableBlock;
            }
        }
        if ($installedBlocks = BlockTypeList::getInstalledList()) {
            foreach($installedBlocks as &$installedBlock){
                $installedBlock->installed = true;
                $blockTypes[] = $installedBlock;
            }
        }
        if (!empty($blockTypes)) {
            foreach ($blockTypes as $bt) {
                $pkgID = $bt->getPackageID();
                $btHandle = $bt->getBlockTypeHandle();
                $pkgHandle = false;
                if ($pkgID > 0) {
                    $pkg = Package::getByID($pkgID);
                    $pkgHandle = $pkg->getPackageHandle();
                }
                $path = dirname($env->getPath(DIRNAME_BLOCKS . '/' . $btHandle . '/' . FILENAME_CONTROLLER, $pkgHandle));
                $configFile = $path . DIRECTORY_SEPARATOR . 'config.json';
                if (file_exists($configFile)) {
                    $blockTypesFinal[$btHandle] = $bt;
                }
            }
        }
        $this->set('blockTypes', $blockTypesFinal);
    }
}