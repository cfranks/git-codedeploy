<?php       defined('C5_EXECUTE') or die("Access Denied.");

use \URL as URL;
use \Concrete\Core\Block\BlockType\BlockType as BlockType;
use \Concrete\Core\Attribute\Type as AttributeType;
use \Concrete\Core\Page\Type\Type as CollectionType;

$c = Page::getCurrentPage();
$fm = Loader::helper('form');

$bt = BlockType::getByHandle('related_pages');
$akIDs = explode(',', $akID);

$at = AttributeType::getByHandle('select');
$atID = $at->getAttributeTypeID();
$list = $controller->getList(array('AttributeKeys.atID'=>$atID));

$AJAXselect = URL::to('/problog/tools/nab_attribute');
//var_dump($AJAXselect);
?>
<div id="ccm-relatedpagesPane-add" class="ccm-relatedpagesPane">
	<div class="ccm-block-field-group">
	  <h4><?php       echo t('Number and Type of Pages')?></h4>
	  <?php       echo t('Display')?>
	  <input type="text" name="num" value="<?php       echo $num?>" style="width: 30px">
	  <?php       echo t('pages of type')?>
	  <?php  
            $ctArray = CollectionType::getList();

            if (is_array($ctArray)) { ?>
	  <select name="ctID" id="selectCTID">
		<option value="0">** <?php       echo t('All')?> **</option>
		<?php       foreach ($ctArray as $ct) { ?>
		<option value="<?php       echo $ct->getPageTypeID()?>" <?php       if ($ctID == $ct->getPageTypeID()) { ?> selected <?php       } ?>>
		<?php       echo $ct->getPageTypeName()?>
		</option>
		<?php       } ?>
	  </select>
	  <?php       } ?>
	</div>
	<div class="ccm-block-field-group">
	  <h4><?php       echo t('Location in Website')?></h4>
	  <?php       echo t('Display pages that are located')?>:<br/>
	  <br/>
	  <div>
			<input type="radio" name="cParentID" id="cEverywhereField" value="9999" <?php       if ($cParentID == 9999 || !$cParentID) { ?> checked<?php       } ?> />
			<?php       echo t('everywhere')?>

			<input type="radio" name="cParentID" id="cThisParentField" value="<?php       echo $c->getCollectionParentID()?>" <?php       if ($cParentID == $c->getCollectionParentID()) { ?> checked<?php       } ?> />
			<?php       echo t('with same parent')?>

			&nbsp;&nbsp;
			<input type="radio" name="cParentID" id="cThisPageField" value="<?php       echo $c->getCollectionID()?>" <?php       if ($cParentID == $c->getCollectionID()) { ?> checked<?php       } ?>>
			<?php       echo t('beneath this page')?>

			&nbsp;&nbsp;
			<input type="radio" name="cParentID" id="cOtherField" value="OTHER" <?php       if ($isOtherPage) { ?> checked<?php       } ?>>
			<?php       echo t('beneath another page')?> </div>

			<div class="ccm-page-list-page-other" <?php       if (!$isOtherPage) { ?> style="display: none" <?php       } ?>>

			<?php       $form = Loader::helper('form/page_selector');
            if ($isOtherPage) {
                print $form->selectPage('cParentIDValue', $cParentID);
            } else {
                print $form->selectPage('cParentIDValue');
            }
            ?>

			</div>
	</div>
	 <h4><?php       echo t('Filter Page')?></h4>

	<fieldset class="filter" <?php       if ($type==2) {echo 'style="display: none;"';}?>>

	 <?php  
      $cadf = CollectionAttributeKey::getByHandle('is_featured');
      ?>
	  	<div>
	  	<?php       echo $fm->label('displayFeaturedOnly','Featured pages')?>
	  <input <?php        if (!is_object($cadf)) { ?> disabled <?php        } ?> type="checkbox" name="displayFeaturedOnly" value="1" <?php        if ($displayFeaturedOnly == 1) { ?> checked <?php        } ?> style="vertical-align: middle" />
	  <?php       echo t('Featured pages only.')?>
		<?php        if (!is_object($cadf)) { ?>
			 <?php       echo t('(<strong>Note</strong>: requires "is_featured" page attribute.)');?></span>
		<?php        } ?>
		<br/><br/>
		</div>
		<div>
			<?php       echo $fm->label('akID','Attribute')?>
			<select name="akID" class="attribute">
				<option value="same_tags"><?php      echo t('Tags From Current Page')?></option>
				<?php  
                if (is_array($list)) {
                    foreach ($list as $item) {
                        echo '<option value="'.$item->akID.'" ';
                        if (in_array($item->akID,$akIDs)) {echo 'selected';}
                        echo '>'.$item->akName.'</option>';
                    }
                }
                ?>
			</select>
		</div>
	</fieldset>
	<div id="type_results">
		<center class="ajax_block_loader" style="display: none;"><img src="<?php       echo Loader::helper('concrete/urls')->getBlockTypeAssetsURL($bt, 'ajax-loader.gif')?>" alt="loading"/></center>
	</div>
	<?php      echo $fm->hidden('ccID',$c->getCollectionID())?>
<?php  
if ($fields) {
    $field_array = explode(',',$fields);
    ?>
	<fieldset class="existing_fields">
		<?php  
        if ($akID && $akID != 'same_tags') {
            $html = '';
            $db = loader::db();
            $r = $db->execute("SELECT * FROM atSelectOptions WHERE akID = $akID");
            while ($row = $r->fetchrow()) {
                $id = $row['ID'];
                $options[$id] = $row['value'];
            }
            foreach ($options as $key=>$option) {
                $html .= '<input type="checkbox" name="fields[]" value="'.$option.'"';
                if (in_array($option,$field_array)) {
                    $html .= 'checked=checked';
                }
                $html .= '>'.$option.'<br/>';
            }
            echo $html;
        }
        ?>
	</fieldset>
	<?php  
}
?>
<script type="text/javascript">
/*<![CDATA[*/
	$('.attribute').change(function () {
		$('.existing_fields').remove();
		$('#type_results').html('');
		$('.ajax_block_loader').show();
		var akID = $(this).val();
        if($(this).val() != 'same_tags'){
    		var ccID = <?php      echo $c->getCollectionID()?>;
    		var url = '<?php       echo $AJAXselect?>?akID='+akID+'&ccID='+ccID;
    		//alert(url);
    		$.get(url,function (data) {
    			//alert(data);
    			$('.ajax_block_loader').hide();
    			$('#type_results').html(data);
    		});
        }else{
            $('.ajax_block_loader').hide();
        }
	});
/*]]>*/
</script>
	<div class="ccm-block-field-group">
	  <h4><?php       echo t('Sort Pages')?></h4>
	  <?php       echo t('Pages should appear')?>
	  <select name="orderBy">
	  	<option value="display_ran" <?php       if ($orderBy == 'display_ran') { ?> selected <?php       } ?>><?php       echo t('in a random order')?></option>
		<option value="display_asc" <?php       if ($orderBy == 'display_asc') { ?> selected <?php       } ?>><?php       echo t('in their sitemap order')?></option>
		<option value="chrono_desc" <?php       if ($orderBy == 'chrono_desc') { ?> selected <?php       } ?>><?php       echo t('with the most recent first')?></option>
		<option value="chrono_asc" <?php       if ($orderBy == 'chrono_asc') { ?> selected <?php       } ?>><?php       echo t('with the earliest first')?></option>
		<option value="alpha_asc" <?php       if ($orderBy == 'alpha_asc') { ?> selected <?php       } ?>><?php       echo t('in alphabetical order')?></option>
		<option value="alpha_desc" <?php       if ($orderBy == 'alpha_desc') { ?> selected <?php       } ?>><?php       echo t('in reverse alphabetical order')?></option>
	  </select>
	</div>

	<style type="text/css">
	#ccm-relatedpages-truncateTxt.faintText{ color:#999; }
	<?php       if(truncateChars==0 && !$truncateSummaries) $truncateChars=128; ?>
	</style>
	<div class="ccm-block-field-group">
	   <h4><?php       echo t('Truncate Summaries')?></h4>
	   <input id="ccm-relatedpages-truncateSummariesOn" name="truncateSummaries" type="checkbox" value="1" <?php       echo ($truncateSummaries ? "checked=\"checked\"" : "")?> />
	   <span id="ccm-relatedpages-truncateTxt" <?php       echo ($truncateSummaries ? "" : "class=\"faintText\"")?>>
	   		<?php       echo t('Truncate descriptions after')?>
			<input id="ccm-relatedpages-truncateChars" <?php       echo ($truncateSummaries ? "" : "disabled=\"disabled\"")?> type="text" name="truncateChars" size="3" value="<?php       echo intval($truncateChars)?>" />
			<?php       echo t('characters')?>
	   </span>
	</div>

</div>
<script type="text/javascript">
    $(function(){ 
        Concrete.event.fire('problog_block_edit');
    });
</script>