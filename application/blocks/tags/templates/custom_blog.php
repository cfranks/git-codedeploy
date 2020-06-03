<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>

<?php if (isset($options) && count($options) > 0) { ?>
    <div class="ccm-block-tags-wrapper news-sidebar">
        <?php if ($title) { ?>
            <h3><?=$title?></h3>
        <?php } ?>
        <div class="ccm-page-list">
        <?php foreach ($options as $option) { 
            $page_list = new PageList();
	    $page_list->ignorePermissions();
            $page_list->filterByAttribute("tags", $option);
           // $page_list->filterByAttribute("blog_entry_topics", $topic->getTreeNodeDisplayName());
            $total = $page_list->getTotalResults();
            ?>
            
            <?php if (isset($target) && $target) { ?>
                <a href="<?=$controller->getTagLink($option) ?>">
                    <?php if (isset($selectedTag) && mb_strtolower($option->getSelectAttributeOptionValue()) == mb_strtolower($selectedTag)) { ?>
                        <?=$option->getSelectAttributeOptionValue() . ($total > 0 ? " (".$total.")" : "")?>
                    <?php } else { ?>
                        <?=$option->getSelectAttributeOptionValue() . ($total > 0 ? " (".$total.")" : "")?>
                    <?php } ?>
                </a>
                <br/>
            <?php } else { ?>
                <?php if (isset($selectedTag) && mb_strtolower($option->getSelectAttributeOptionValue()) == mb_strtolower($selectedTag)) { ?>
                    <span class="ccm-block-tags-tag ccm-block-tags-tag-selected label"><?=$option->getSelectAttributeOptionValue()?></span>
                <?php } else { ?>
                    <span class="ccm-block-tags-tag label"><?=$option->getSelectAttributeOptionValue()?></span>
                <?php } ?>
            <?php } ?>
        <?php } ?>
    </div>
    </div>
<?php } ?>
