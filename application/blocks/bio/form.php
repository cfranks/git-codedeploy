<?php  defined("C5_EXECUTE") or die("Access Denied."); ?>

<?php  $al = Core::make("helper/concrete/asset_library"); ?>
<div class="form-group">
    <?php 
    if ($img > 0) {
        $img_o = File::getByID($img);
        if ($img_o->isError()) {
            unset($img_o);
        }
    } ?>
    <?php  echo $form->label('img', t("Image")); ?>
    <?php  echo isset($btFieldsRequired) && in_array('img', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php  echo $al->file($view->field('ccm-b-file-img'), "img", t("Choose File"), $img_o); ?>
</div>

<?php 
$fp = FilePermissions::getGlobal();
$tp = new TaskPermission();
?>

<div class="form-group">
    <?php  echo $form->label('content', t("Content")); ?>
    <?php  echo isset($btFieldsRequired) && in_array('content', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>

    <div id="wysiwyg-ft-content"><?php  echo $content; ?></div>

    <script type="text/javascript">
        var CCM_EDITOR_SECURITY_TOKEN = "<?php  echo Loader::helper('validation/token')->generate('editor')?>";
        $(function () {
            $("#wysiwyg-ft-content").redactor({
                minHeight: "300",
                "concrete5": {
                    filemanager: <?php  echo $fp->canAccessFileManager()?>,
                    sitemap: <?php  echo $tp->canAccessSitemap()?>,
                    lightbox: true
                },
                "plugins": [
                    "fontcolor", "concrete5"
                ]
            });
            $("#wysiwyg-ft-content").prev().css({opacity: "1"});
        });
    </script>
</div>