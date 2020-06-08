<?php 
namespace Concrete\Package\BlockDesigner;

use Package;
use BlockType;
use Page;
use SinglePage;
use Loader;

defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends Package
{
    protected $pkgHandle = 'block_designer';
    protected $appVersionRequired = '5.7.0.4';
    protected $pkgVersion = '1.2.0';

    public function getPackageName()
    {
        return t("Block Designer");
    }

    public function getPackageDescription()
    {
        return t("Design your own content blocks within a few clicks!");
    }

    public function install()
    {
        $pkg = parent::install();
        SinglePage::add('/dashboard/blocks/block_order', $pkg);
        SinglePage::add('/dashboard/blocks/block_designer', $pkg);
        SinglePage::add('/dashboard/blocks/block_designer/block_config', $pkg);
    }

    public function uninstall()
    {
        parent::uninstall();
    }

    public function upgrade() {
        $pkg = $this;
        $sp = Page::getByPath('/dashboard/blocks/block_designer/block_config');
        if ($sp->isError() || (!is_object($sp))) {
            SinglePage::add('/dashboard/blocks/block_designer/block_config', $pkg);
        }
        parent::upgrade();
    }
}