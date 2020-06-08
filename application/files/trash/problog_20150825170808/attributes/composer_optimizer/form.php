<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
?>
<?php   if (!$settings['alchemy']) { ?>
        <div class="col-xs-12">
            <div class="alert block-message alert-success">
                <a class="close" href="javascript:;">×</a>
                <p><strong><?php        echo t('You have not enabled your Alchemy API!');?></strong></p>
                <p><?php        echo t('<p>You can enable your Alchemy API in your ProBlog settings area.</p>');?></p>
                <div class="alert-actions">

                </div>
            </div>
        </div>
<?php   }else{ ?>
<br/>
<a href="<?php  echo URL::to('/problog/tools/optimizer')?>" class="btn btn-success optimizer-launch" dialog-width="900" dialog-height="700" dialog-modal="true" dialog-title="SEO Optimizer" dialog-on-close=""><i class="fa fa-rocket"></i> <?php  echo t('Run Optimizer Report')?></a>
<script type="text/javascript">
    $(document).ready(function(){
        $('.optimizer-launch').dialog(); 
    });
</script>
<?php   } ?>