<?php  defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<div class="content-field-options">
    <div class="form-group">
        <label for="fields[{{id}}][hide_title]">
            <input type="checkbox" name="fields[{{id}}][hide_title]" value="1" id="fields[{{id}}][hide_title]" {{#xif " this.hide_title == '1' " }}checked="checked"{{/xif}}>
            <?php  echo t('Hide title field'); ?>
            <br/>
            <small>
                <?php  echo t('Do not show an extra field, where an alternative (page) title can be entered'); ?>
            </small>
        </label>
    </div>
</div>