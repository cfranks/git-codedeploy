<?php  
namespace Concrete\Package\Problog\Src\Page\Type\PublishTarget\Type;

use \Concrete\Core\Page\Type\PublishTarget\Type\Type as Type;
use \Concrete\Core\Page\Type\Type as PageType;
use \Concrete\Package\Problog\Src\Page\Type\PublishTarget\Configuration\BlogSectionConfiguration;

class BlogSectionType extends Type
{

    public function configurePageTypePublishTarget(PageType $pt, $post)
    {
        $configuration = new BlogSectionConfiguration($this);

        return $configuration;
    }

    public function configurePageTypePublishTargetFromImport($txml)
    {
        $configuration = new ParentPageConfiguration($this);
        $path = (string) $txml['path'];
        if (!$path) {
            $c = Page::getByID(HOME_CID);
        } else {
            $c = Page::getByPath($path);
        }
        $configuration->setParentPageID($c->getCollectionID());

        return $configuration;
    }
}
