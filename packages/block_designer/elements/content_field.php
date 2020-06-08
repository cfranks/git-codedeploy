<?php  defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

{{#xif " this.base_fields == 'true' " }}
    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <label for="fieldLabels[{{id}}][label]" class="control-label">
                    <?php  echo t('Label'); ?>
                    <small><?php  echo t('As seen in the form'); ?></small>
                </label>

                <input id="fieldLabels[{{id}}][label]"
                       autocomplete="off"
                       value="{{label}}"
                       type="text" name="fields[{{id}}][label]"
                       class="form-control ccm-input-text"
                       data-validation="required">
            </div>
            <div class="col-md-6">
                <label for="fieldLabels[{{id}}][slug]" class="control-label">
                    <?php  echo t('Slug'); ?>
                    <small><?php  echo t('This name will be used in the view file (a-zA-Z characters only)'); ?></small>
                </label>

                <input id="fieldLabels[{{id}}][slug]"
                       autocomplete="off"
                       value="{{slug}}"
                       type="text" name="fields[{{id}}][slug]"
                       class="form-control ccm-input-text" data-validation="custom"
                       data-validation-regexp="^([a-zA-Z]+)$">
            </div>
        </div>
    </div>

    <div class="form-group">
        <input type="checkbox" name="fields[{{id}}][required]" id="fieldsRequired[{{id}}]" value="1" {{#xif " this.required == '1' " }}checked="checked"{{/xif}}>
        <label for="fieldsRequired[{{id}}]"><?php  echo t('Required?'); ?></label>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="fieldPrefixes[{{id}}]" class="control-label">
                        <?php  echo t('Wrapper HTML open'); ?>
                        <small><?php  echo t('i.e.'); ?> &lt;div class="abc"&gt;</small>
                    </label>

                    <textarea rows="3" name="fields[{{id}}][prefix]" id="fieldPrefixes[{{id}}]" class="form-control">{{prefix}}</textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="fieldSuffixes[{{id}}]" class="control-label">
                        <?php  echo t('Wrapper HTML close'); ?>
                        <small><?php  echo t('i.e.'); ?> &lt;/div&gt;</small>
                    </label>

                    <textarea
                        rows="3"
                        name="fields[{{id}}][suffix]"
                        id="fieldSuffixes[{{id}}]"
                        class="form-control">{{suffix}}</textarea>
                </div>
            </div>
        </div>
    </div>
{{/xif}}

<?php 
foreach ($field_types as $ftSlug => $ft) {
    $ftClass = $ft['class'];
    if (method_exists($ftClass, 'getExtraOptions')) {
        echo $ftClass->getExtraOptions();
    }
    if (method_exists($ftClass, 'getFieldOptions')) {
        ?>
        {{#xif " this.type == '<?php  echo $ftSlug; ?>' " }}
        <?php  echo $ftClass->getFieldOptions(); ?>
        {{/xif}}<?php 
    }
} ?>