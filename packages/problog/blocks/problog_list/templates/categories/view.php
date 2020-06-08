<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));

use \Concrete\Package\Problog\Attribute\Select\SelectBlog;
use \Concrete\Core\Attribute\Type as AttributeType;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;

if ($title!='') {
    echo '<h3>'.t($title).'</h3>';
}
?>
    <div class="ccm-page-list">

    <?php  
    $tagCounts = array();

    $ak = CollectionAttributeKey::getByHandle('blog_category');
    $akc = new SelectBlog(AttributeType::getByHandle('select'));
    $akc->setAttributeKey($ak);
    $ttags = $akc->getOptionUsageArray($pp);

    $tags = array();

    foreach ($ttags as $t) {

        $tagCounts[] = $t->getSelectAttributeOptionUsageCount();
        $tags[] = $t;
    }

    shuffle($tags);

    for ($i = 0; $i < $ttags->count(); $i++) {
        $akct = $tags[$i];
        //$qs = $akc->field('atSelectOptionID') . '[]=' . $akct->getSelectAttributeOptionID();
        echo '<a href="'.$search.'categories/'.rawurlencode(str_replace(' ','_',$akct->getSelectAttributeOptionValue())).'/">'.$akct->getSelectAttributeOptionValue().' ('.$akct->getSelectAttributeOptionUsageCount().')</a><br/>';
    }
    ?></div><br/><?php  
