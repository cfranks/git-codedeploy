<?php  defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<div class="content-field-options">
    <div class="form-group">
        <label for="fields[{{id}}][nl2br]">
            <input type="checkbox" name="fields[{{id}}][nl2br]" value="1" id="fields[{{id}}][nl2br]" {{#xif " this.nl2br == '1' " }}checked="checked"{{/xif}}>
            <?php  echo t('New line to blank rule'); ?>
            <br/><small>
                <?php  echo t('Returns entered string with "%s" or "%s" inserted before all newlines', '&lt;br /&gt;', '&lt;br&gt;'); ?>
            </small>
        </label>
    </div>
</div>