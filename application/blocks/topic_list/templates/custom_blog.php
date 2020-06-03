<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>

<div class="ccm-block-topic-list-wrapper news-sidebar">

    <h3><?php echo h($title); ?></h3>

    <?php
    if ($mode == 'S' && is_object($tree)) {
        $node = $tree->getRootTreeNodeObject();
        $node->populateChildren();
        if (is_object($node)) {
            if (!isset($selectedTopicID)) {
                $selectedTopicID = null;
            }
            $walk = function ($node) use (&$walk, &$view, $selectedTopicID) {
                ?>
                <div class="ccm-page-list"><?php
                foreach ($node->getChildNodes() as $topic) {
                    $page_list = new PageList();
                    $page_list->filterByAttribute("blog_entry_topics", $topic);
		    $page_list->ignorePermissions();
		    //$page_list->filterByPublicDate(date('Y-m-d H:i:s'), '<');
                    //$page_list->filterByAttribute("blog_entry_topics", $topic->getTreeNodeDisplayName());
                    $total = $page_list->getTotalResults();
                    if ($topic instanceof \Concrete\Core\Tree\Node\Type\Category) {
                        ?><?php echo $topic->getTreeNodeDisplayName(); ?>
                        <?php
                    } else {
                        ?><a href="<?php echo $view->controller->getTopicLink($topic); ?>" <?php
                        if (isset($selectedTopicID) && $selectedTopicID == $topic->getTreeNodeID()) {
                            ?> class="ccm-block-topic-list-topic-selected"<?php
                        }
                        ?>><?php echo $topic->getTreeNodeDisplayName() . ($total > 0 ? " (".$total.")" : ""); ?></a><br/><?php
                    }
                    if (count($topic->getChildNodes())) {
                        $walk($topic);
                    } ?>
                    <?php
                }
                ?>
                </div><?php
            };
            $walk($node);
        }
    }

    if ($mode == 'P') {
        if (isset($topics) && count($topics)) {
            ?><ul class="ccm-block-topic-list-page-topics"><?php
            foreach ($topics as $topic) {
                ?><li><a href="<?php echo $view->controller->getTopicLink($topic); ?>"><?php echo $topic->getTreeNodeDisplayName(); ?></a></li><?php
            }
            ?></ul><?php
        } else {
            echo t('No topics.');
        }
    }
    ?>

</div>
<style>
.news-sidebar h3 {
    text-transform: uppercase;
    font-size: 14px;
    padding: 5px;
    background: #eee;
    color: #333;
}

.news-sidebar .ccm-page-list, .news-sidebar h4 {
    padding: 0 0 0 15px;
    font-size: 15px;
}
</style>