<?php   defined('C5_EXECUTE') or die(_("Access Denied."));?>
<style type="text/css">
    div#ccm-dashboard-content header{padding: 1px 80px 14px 55px;}
</style>
<?php   echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('View/Search Blog'),false,false,
    false); ?>
<?php  
if ($remove_cid) {
    ?>
    <div class="ccm-ui" id="ccm-dashboard-result-message">
        <div class="alert alert-danger alert-dismissible">
            <a class="close" href="<?php   echo $this->action('clear_warning');?>">×</a>
            <p><strong><?php   echo t('Holy guacamole! This is a warning!');?></strong></p><br/>
            <p><?php   echo t('Are you sure you want to delete ').$remove_name.'?';?></p>
            <p><?php   echo t('This action may not be undone!');?></p>
            <hr/>
            <div class="alert-actions">
                <a class="btn btn-danger small" href="<?php   echo $this->action('deletethis',$remove_cid,$remove_name
                );?>"><?php   echo t('Yes Remove This');?></a> <a class="btn btn-warning small" href="<?php  
                echo $this->action('clear_warning');?>"><?php   echo t('Cancel');?></a>
            </div>
        </div>
    </div>
<?php  
}
?>
<div class="ccm-dashboard-content-full">
    <form method="get" action="<?php   echo $this->action('view')?>" id="blog_search">
        <?php  
        $sections[0] = '** All';
        asort($sections);
        ?>
        <table class="table" >
            <tr>
                <th><strong><?php   echo $form->label('cParentID', t('Section'))?></strong></th>
                <th><strong><?php   echo t('by Name')?></strong></th>
                <th><strong><?php   echo t('by Category')?></strong></th>
                <th><strong><?php   echo t('by Tag')?></strong></th>
                <th>
                    <input type="checkbox" name="only_unaproved" id="only_unaproved" value="1" <?php       if ($_GET['only_unaproved']==1) {echo 'checked';}?>> <?php      echo t('Only Show Unapproved')?>
                </th>
            </tr>
            <tr>
                <td><?php   echo $form->select('cParentID', $sections, $cParentID)?></td>
                <td><?php   echo $form->text('like', $like)?></td>
                <td>
                    <select name="cat" class="form-control">
                        <option value=''>--</option>
                        <?php  
                        foreach ($cat_values as $cat) {
                            if ($_GET['cat']==$cat['value']) {$selected = 'selected="selected"';} else {$selected=null;}
                            echo '<option '.$selected.'>'.$cat['value'].'</option>';
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <select name="tag" class="form-control">
                        <option value=''>--</option>
                        <?php  
                        foreach ($tag_values as $tag) {
                            if ($_GET['tag']==$tag['value']) {$selected = 'selected="selected"';} else {$selected=null;}
                            echo '<option '.$selected.'>'.$tag['value'].'</option>';
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <?php   echo $form->submit('submit_form', t('Search'))?>
                </td>
            </tr>
        </table>
    </form>
<br/>
    <table border="0" class="ccm-search-results-table" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th>&nbsp;</th>
            <th <?php   if ($controller->checkActiveSortHeader('cv.cvName')>0) { ?>class="ccm-results-list-active-sort-<?php  echo $sortOrder?>"<?php   } ?>>
                <a href="<?php  echo $this->action('view')?>?ccm_order_by=cv.cvName&ccm_order_by_direction=<?php  echo $nextSort?>">
                    <?php   echo t('Name')?>
                </a>
            </th>
            <th <?php   if ($controller->checkActiveSortHeader('cv.cvDatePublic')>0) { ?>class="ccm-results-list-active-sort-<?php  echo $sortOrder?>"<?php   } ?>>
                <a href="<?php  echo $this->action('view')?>?ccm_order_by=cv.cvDatePublic&ccm_order_by_direction=<?php  echo $nextSort?>">
                    <?php   echo t('Date')?>
                </a>
            </th>
            <th><?php   echo t('Page Owner')?></th>
            <th class=""><?php   echo t('blog Category')?></th>
            <th><?php   echo t('In Draft')?></th>
        </tr>
        </thead>
        <tbody>
        <?php  
        $pkt = Loader::helper('concrete/urls');
        $pkg= Package::getByHandle('problog');
        foreach ($blogResults as $cobj) {

            $akct = CollectionAttributeKey::getByHandle('blog_category');
            $blog_category = $cobj->getCollectionAttributeValue($akct);
            ?>
            <tr>
                <td width="75px">
                    <a href="<?php   echo $this->url('/dashboard/problog/add_blog', 'edit',
                        $cobj->getCollectionID())?>" class="fa fa-edit"></a> &nbsp;
                    <a href="<?php   echo $this->url('/dashboard/problog/blog_list', 'delete_check',
                        $cobj->getCollectionID(),$cobj->getCollectionName())?>" class="fa fa-trash"></a>
                </td>
                <td><a href="<?php   echo $navigation->getLinkToCollection($cobj)?>"><?php   echo $cobj->getCollectionName()?></a></td>
                <td>
                    <?php  
                    if ($cobj->getCollectionDatePublic() > date('Y-m-d H:i:s') ) {
                        echo '<font style="color:green;">';
                        echo Core::make('helper/date')->formatCustom(t('M d, Y'),strtotime($cobj->getCollectionDatePublic()));
                        echo '</font>';
                    } else {
                        echo Core::make('helper/date')->formatCustom(t('M d, Y'),strtotime($cobj->getCollectionDatePublic()));
                    }
                    ?>
                </td>
                <td>
                    <?php  
                    $user = UserInfo::getByID($cobj->getCollectionUserID());
                    if ($user) {
                        echo $user->getUserName();
                    }
                    ?>
                </td>
                <td><?php   echo $blog_category;?></td>
                <td>
                    <?php  
                    if (!$cobj->isActive()) {
                        echo '<a href="'.$this->url('/dashboard/problog/blog_list', 'approvethis', $cobj->getCollectionID(),$cobj->getCollectionName()).'">'.t('Approve This').'</a>';
                    }
                    ?>
                </td>
            </tr>
        <?php   } ?>
        </tbody>
    </table>
<br/>
<?php  
//$blogList->displayPaging();
?>
</div>
<div class="ccm-pane-footer">

</div>
<script type="text/javascript">
    /*<![CDATA[*/
    $('#only_unaproved').click(function () {
        $('form#blog_search').submit();
    });
    /*]]>*/
</script>
