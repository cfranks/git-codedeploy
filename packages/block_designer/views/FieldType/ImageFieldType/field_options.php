<?php  defined('C5_EXECUTE') or die(_("Access Denied.")); ?>

<div class="content-field-options">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="fields[{{id}}][class]" class="control-label">
                    <?php  echo t('Class(es)'); ?>
                    <small><?php  echo t('Class(es) to be added to your image, i.e. "%s"', 'img-responsive img-rounded'); ?></small>
                </label>
                <input type="text"
                       name="fields[{{id}}][class]"
                       id="fields[{{id}}][class]"
                       value="{{class}}"
                       data-validation-optional="true"
                       data-validation="custom"
                       data-validation-length="min3"
                       data-validation-regexp="^([a-zA-Z]+)([a-zA-Z- ]+)$"
                       class="form-control"/>
            </div>

            <div class="form-group">
                <label for="fields[{{id}}][link]" class="control-label">
                    <?php  echo t('Link'); ?>
                </label>

                <select name="fields[{{id}}][link]" class="form-control make_link" id="fields[{{id}}][link]">
                    {{#select link}}
                    <option value="">-- <?php  echo t('None'); ?> --</option>
                    <option value="1"><?php  echo t('Page'); ?></option>
                    <option value="2"><?php  echo t('URL'); ?></option>
                    {{/select}}
                </select>
            </div>

            <div class="form-group">
                <div class="link-values" style="display: none;">
                    <div class="form-group">
                        <label for="fields[{{id}}][link_class]" class="control-label">
                            <?php  echo t('Class(es)'); ?>
                            <small><?php  echo t('Class(es) to be added to your link, i.e. "%s"', 'product-anchor'); ?></small>
                        </label>
                        <input type="text"
                               name="fields[{{id}}][link_class]"
                               id="fields[{{id}}][link_class]"
                               value="{{link_class}}"
                               data-validation-optional="true"
                               data-validation="custom"
                               data-validation-length="min3"
                               data-validation-regexp="^([a-zA-Z]+)([a-zA-Z-0-9 ]+)$"
                               class="form-control"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="fields[{{id}}][thumbnail]">
                    <input class="make_thumbnail" type="checkbox" name="fields[{{id}}][thumbnail]" value="1" id="fields[{{id}}][thumbnail]" {{#xif " this.thumbnail == '1' " }}checked="checked"{{/xif}}>
                    <?php  echo t('Make a thumbnail of the image'); ?>
                </label>
            </div>

            <div class="thumbnail-values" style="display: none;">
                <div class="alert alert-info">
                    <i class="fa fa-info"></i> <?php  echo t('Width and height are required upon making the image a thumbnail. Both values are being entered in whole pixels (no decimals allowed).'); ?>
                </div>

                <div class="form-group">
                    <label for="fields[{{id}}][crop]">
                        <input type="checkbox" name="fields[{{id}}][crop]" value="1" id="fields[{{id}}][crop]" {{#xif " this.crop == '1' " }}checked="checked"{{/xif}}>
                        <?php  echo t('Crop the image to the given width and height'); ?>
                    </label>
                </div>

                <div class="form-group">
                    <label for="fields[{{id}}][width]" class="control-label">
                        <?php  echo t('Width'); ?>
                    </label>
                    <input type="text"
                           name="fields[{{id}}][width]"
                           id="fields[{{id}}][width]"
                           value="{{width}}"
                           data-validation-optional="true"
                           data-validation="number"
                           data-validation-allowing="range[1;99999999999]"
                           class="form-control width"/>
                </div>

                <div class="form-group">
                    <label for="fields[{{id}}][height]" class="control-label">
                        <?php  echo t('Height'); ?>
                    </label>
                    <input type="text"
                           name="fields[{{id}}][height]"
                           id="fields[{{id}}][height]"
                           value="{{height}}"
                           data-validation-optional="true"
                           data-validation="number"
                           data-validation-allowing="range[1;99999999999]"
                           class="form-control height"/>
                </div>
            </div>
        </div>
    </div>
</div>