<?php if ($bFormType != 4) {?>
    <?php if ($bFormType != 3) { ?> 
        <div class="<?php echo $format=='payment' ? 'hidden' : ''; ?>">
            <h3><?php translate('CardOptions', $bLanguage) ?></h3>
            <div class="card-modal">
                <div class="modal-info">
                    <h3><?php translate('SelectYourImage', $bLanguage) ?></h3>
                    <a class="card-close" onclick="selectCard()"><?php translate('AcceptAndClose', $bLanguage) ?></a>
                </div>
                <div class="card-select">
                    <?php foreach($images as $image) {  ?>
                        <div class="radio <?php echo $image['iID']==$selected_image ? 'active' : ''; ?>">
                            <label>
                                <input type="radio" name="card" value="<?php echo $image['iID']; ?>" <?php echo $image['iID']==$selected_image ? 'checked' : ''; ?>>
                                <img src="<?php echo $RelativePath. $fPath . $image['image_thumb']; ?>" alt="<?php echo $image['image_thumb']; ?>">
                                <?php echo translate($image['translation_key'], $bLanguage, false) ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
                <div class="modal-info">
                    <a class="card-close" onclick="selectCard()"><?php translate('AcceptAndClose', $bLanguage) ?></a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <label class="required"><?php translate('CardImage', $bLanguage) ?></label>
                    <img class="selected-img" src="<?php echo $selected_image_path; ?>" alt="Selected Image">
                    <a id="card-modal-btn"><?php translate('SelectYourImage', $bLanguage) ?></a>
                </div>
                <div class="col-sm-4">
                    <div class="form-group no-before">
                        <label class="required"><?php translate('FolderColor', $bLanguage) ?></label>
                        <?php foreach($folders as $id => $folder) {  ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="folder" value="<?php echo $id;?>" <?php echo $id==$selected_folder ? 'checked' : ''; ?> onclick="changeFolder()">
                                    <img src="<?=$RelativePath. $folPath . $folder['folder_color']?>" alt="Folder Image">
                                    <?php echo translate($folder['translation_key'], $bLanguage, false) ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <?php echo $form->label('e_language', translate('LanguageLabel', $bLanguage, false), ['class' => 'required']); ?>
                        <?php echo $form->select('e_language', $languages, (isset($session) ? $session['e_language'] : (isset($data['e_language']) && !empty($data['e_language']) ? $data['e_language'] : ($bLanguage === 'sp' ? 'es' : $bLanguage)) ), ["onchange" => "changeCardLangugage()"]); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->label('e_occasion', translate('OcassionLabel', $bLanguage, false), ['class' => 'required']); ?>
                        <?php echo $form->select("e_occasion", $occasions, ($data['e_occasion'] ? $data['e_occasion'] : 5)); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->label('e_date', translate('EnrollmentDateLabel', $bLanguage, false)); ?>
                        <?php echo $form->text("e_date", (strtotime($data['e_date']) > 0 ? date("n/j/Y", strtotime($data['e_date'])) : ''), array('onchange' => 'updateCard()', 'class' => 'form-control margin-bottom datepickercustom', 'placeholder' => translate('EnrollmentDateLabel', $bLanguage, false))); ?>
                    </div>
                    <div class="form-action">
                        <?php if (in_array($bFormType, [1,2,3])) { ?>
                          <a href="#card-preview" class="btn-text"><?php echo translate('ViewPreview', $bLanguage, false) ?></a>
                        <?php } ?>
                    </div>
                    <?php if($bFormType == 1 && false) {?>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-gorup">
                                    <?php echo $form->radio("i_living_deceased", 'deceased'); ?>
                                    <?php echo translate('DeceasedLabel', $bLanguage, false) ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <?php echo $form->radio("i_living_deceased", 'living',['checked' => 'checked']); ?>
                                    <?php echo translate('LivingLabel', $bLanguage, false) ?>
                                </div>
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>
            
            <?php if ($format=='payment') {?>
                <div class="row" id="enroll_type">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <div class="radio">
                                <label>
                                    <?php echo $form->radio("e_enrollment_type", 'individual', ($data['e_enrollment_type']=='individual'), ['onchange' => 'updateCard();chnFamIndi();', 'checked' => 'checked']); ?>
                                    <?php echo translate('IndividualPriceLabel', $bLanguage, false) ?>
                                    <?php
                                    $price = 0;
                                    $price = Config::get('mass_enrollment::custom.Prices.I'.$bFormType);
                                    echo '($' . number_format($price,2) . ')';
                                    ?>
                                </label>
                            </div>
                        </div>
                        <div class="form-group" id="enroll_individual">
                            <p class="help-block">
                                <?php echo translate('IndividualTextTitle', $bLanguage, false) ?>
                            </p>
                            <?php echo $form->textarea('e_individual_name', $data['e_individual_name'], ['onchange' => 'updateCard();', 'rows' => 2, 'placeholder' => translate('IndividualEnrollNamePlaceholder', $bLanguage, false), 'maxlength' => 120]); ?>
                            <div class="pull-right">
                                <span id="countdIndividualName">0</span> <?php echo translate('OutOFLabel', $bLanguage, false) ?> 120 <?php echo translate('CharacterLabel', $bLanguage, false) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <p style="text-align: center;">- OR -</p>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <div class="radio">
                                <label>
                                    <?php echo $form->radio("e_enrollment_type", 'family', ($data['e_enrollment_type']=='family'), ['onchange' => 'updateCard();chnFamIndi();']); ?>
                                    <?php echo translate('FamilyPriceLabel', $bLanguage, false) ?>
                                    <?php
                                    $price = 0;
                                    $price = Config::get('mass_enrollment::custom.Prices.F'.$bFormType);
                                    echo '($'.number_format($price,2).')';
                                    ?>
                                </label>
                            </div>
                            <div class="form-group" id="enroll_family" style="display: none;">
                                <p class="help-block">
                                    <?php echo translate('FamilyEnrollNameTitle', $bLanguage, false) ?>
                                </p>
                                <?php echo $form->textarea('e_family_name', $data['e_family_name'], ['onchange' => 'updateCard()', 'rows' => 2, 'placeholder' => translate('FamiliyEnrollNamePlaceholder', $bLanguage, false), 'maxlength' => 120]); ?>
                                <div class="pull-right">
                                    <span id="countdFamilyName">0</span> <?php echo translate('OutOFLabel', $bLanguage, false) ?> 120 <?php echo translate('CharacterLabel', $bLanguage, false) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h3 class="required"><?php echo translate('RequestedByLabel', $bLanguage, false) ?> <i class="fa fa-question-circle" data-toggle="tooltip" title="Type your message and sign your name e.g., with love Bob and Mary" aria-hidden="true" title='<?php echo translate('RequestedByInfo', $bLanguage, false) ?>'></i></h3>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <p class="help-block"><?php echo translate('RequestedByTitle', $bLanguage, false) ?></p>
                            <?php echo $form->textarea('e_requested_by', $data['e_requested_by'], ['onchange' => 'updateCard()','rows' => 4, 'placeholder' => translate('RequestedByLabel', $bLanguage, false)]); ?>
                            <div class="pull-right">
                                <span id="countTxtRequestedBy">0</span> <?php echo translate('OutOFLabel', $bLanguage, false) ?> 120 <?php echo translate('CharacterLabel', $bLanguage, false) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } else { ?>
        <div class="<?php echo $format=='payment' ? 'hidden' : ''; ?>">
            <h3><?php translate('CardOptions', $bLanguage) ?></h3>
            <div class="row">
                <div class="col-sm-7">
                    <div class="row">
                        <div class="col-sm-6">
                            <img class="selected-img" src="<?php echo $img_deceased; ?>" alt="Selected Image"><br/>
                            <?php echo $form->radio("i_living_deceased", 'deceased', (isset($data['i_living_deceased']) && $data['i_living_deceased'] == 'deceased') ? true : false); ?>
                            <?php echo translate('DeceasedLabel', $bLanguage, false) ?>
                        </div>
                        <div class="col-sm-6">
                            <img class="selected-img" src="<?php echo $img_living; ?>" alt="Selected Image"><br/>
                            <?php echo $form->radio("i_living_deceased", 'living', !isset($data['i_living_deceased']) || (isset($data['i_living_deceased']) && $data['i_living_deceased'] == 'living')? true : false); ?>
                            <?php echo translate('LivingLabel', $bLanguage, false) ?>
                        </div>
                    </div>
		    <p class="small" style="text-align: start; padding: 15px 0px;"><?php 
                translate('AckFormNoteForCardOption', $bLanguage) ?></p>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <?php echo $form->label('e_language', translate('LanguageLabel', $bLanguage, false), ['class' => 'required',"onchange" => "changeCardLangugage()"]); ?>
                        <?php echo $form->select('e_language', $languages, (isset($session) ? $session['e_language'] : (isset($data['e_language']) && !empty($data['e_language']) ? $data['e_language'] : ($bLanguage === 'sp' ? 'es' : $bLanguage)) ), ['onchange' => 'changeCardLangugage()']); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->label('e_occasion', translate('OcassionLabel', $bLanguage, false), ['class' => 'required']); ?>
                        <?php echo $form->select("e_occasion", $occasions, ($data['e_occasion'] ? $data['e_occasion'] : 5)); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->label('e_date', translate('EnrollmentDateLabel', $bLanguage, false)); ?>
                        <?php echo $form->text("e_date", (strtotime($data['e_date']) > 0 ? date("n/j/Y", strtotime($data['e_date'])) : ''), array('onchange' => 'updateCard()', 'class' => 'form-control margin-bottom datepickercustom', 'placeholder' => translate('EnrollmentDateLabel', $bLanguage, false))); ?>
                    </div>
                    <div class="form-action">
                        <?php if (in_array($bFormType, [1,2,3])) { ?>
                          <a href="#card-preview" class="btn-text"><?php echo translate('ViewPreview', $bLanguage, false) ?></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php if ($format=='payment') {?>
                <div class="row" id="enroll_type">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <div class="radio">
                                <label>
                                    <?php echo $form->radio("e_enrollment_type", 'individual', ($data['e_enrollment_type']=='individual'), ['onchange' => 'updateCard();chnFamIndi();', 'checked' => 'checked']); ?>
                                    <?php echo translate('IndividualPriceLabel', $bLanguage, false) ?>
                                    <?php
                                    $price = 0;
                                    $price = Config::get('mass_enrollment::custom.Prices.I'.$bFormType);
                                    echo '($' . number_format($price,2) . ')';
                                    ?>
                                </label>
                            </div>
                        </div>
                        <div class="form-group" id="enroll_individual">
                            <p class="help-block">
                                <?php echo translate('IndividualTextTitle', $bLanguage, false) ?>
                            </p>
                            <?php echo $form->textarea('e_individual_name', $data['e_individual_name'], ['onchange' => 'updateCard();', 'rows' => 2, 'placeholder' => translate('IndividualEnrollNamePlaceholder', $bLanguage, false), 'maxlength' => 120]); ?>
                            <div class="pull-right">
                                <span id="countdIndividualName">0</span> <?php echo translate('OutOFLabel', $bLanguage, false) ?> 120 <?php echo translate('CharacterLabel', $bLanguage, false) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <p style="text-align: center;">- OR -</p>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <div class="radio">
                                <label>
                                    <?php echo $form->radio("e_enrollment_type", 'family', ($data['e_enrollment_type']=='family'), ['onchange' => 'updateCard();chnFamIndi();']); ?>
                                    <?php echo translate('FamilyPriceLabel', $bLanguage, false) ?>
                                    <?php
                                    $price = 0;
                                    $price = Config::get('mass_enrollment::custom.Prices.F'.$bFormType);
                                    echo '($'.number_format($price,2).')';
                                    ?>
                                </label>
                            </div>
                            <div class="form-group" id="enroll_family" style="display: none;">
                                <p class="help-block">
                                    <?php echo translate('FamilyEnrollNameTitle', $bLanguage, false) ?>
                                </p>
                                <?php echo $form->textarea('e_family_name', $data['e_family_name'], ['onchange' => 'updateCard()', 'rows' => 2, 'placeholder' => translate('FamiliyEnrollNamePlaceholder', $bLanguage, false), 'maxlength' => 120]); ?>
                                <div class="pull-right">
                                    <span id="countdFamilyName">0</span> <?php echo translate('OutOFLabel', $bLanguage, false) ?> 120 <?php echo translate('CharacterLabel', $bLanguage, false) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h3 class="required"><?php echo translate('RequestedByLabel', $bLanguage, false) ?> <i class="fa fa-question-circle" data-toggle="tooltip" title="Type your message and sign your name e.g., with love Bob and Mary" aria-hidden="true" title='<?php echo translate('RequestedByInfo', $bLanguage, false) ?>'></i></h3>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <p class="help-block"><?php echo translate('RequestedByTitle', $bLanguage, false) ?></p>
                            <?php echo $form->textarea('e_requested_by', $data['e_requested_by'], ['onchange' => 'updateCard()','rows' => 4, 'placeholder' => translate('RequestedByLabel', $bLanguage, false)]); ?>
                            <div class="pull-right">
                                <span id="countTxtRequestedBy">0</span> <?php echo translate('OutOFLabel', $bLanguage, false) ?> 120 <?php echo translate('CharacterLabel', $bLanguage, false) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
<?php } else if($format!='payment') { ?>
    <h3><?php echo translate('EnrollmentDetailsTitle', $bLanguage, false) ?></h3>
    <div class="form-group">
        <?php echo $form->label('e_occasion', translate('OcassionLabel', $bLanguage, false), ['class' => 'required']); ?>
        <?php echo $form->select("e_occasion", $occasions, $data['e_occasion']); ?>
    </div>
    <div class="form-group">
        <?php echo $form->label('e_date', translate('EnrollmentDateLabel', $bLanguage, false)); ?>
        <?php echo $form->text("e_date", (strtotime($data['e_date']) > 0 ? date("n/j/Y", strtotime($data['e_date'])) : ''), array('class' => 'form-control margin-bottom datepickercustom', 'placeholder' => translate('EnrollmentDateLabel', $bLanguage, false))); ?>
    </div>
<?php }?>