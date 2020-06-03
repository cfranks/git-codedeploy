
<?php if (isset($message_custom)) {?>
    <div style="margin-top:20px"  class="alert alert-info">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo translate('MessageSuccess', $bLanguage, false) ?>
    </div>
<?php } else {?>
    <form class="divine-form" method="POST" action="<?php echo $this->action('submit_enrollment') ?>">
        <?php include_once 'steps.php'; ?>
        <?php include_once 'card.php'; ?>
        <h3 class="required">
		<?php if (in_array($bFormType, [1])) { ?><p style="font-size:medium; font-weight: 400; color: red"><?php echo translate('InternationalCharge',$bLanguage, false) ?></p><?php } ?>
		<?php echo translate('EnrollmentTypeTitle', $bLanguage, false) ?>
	</h3>
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
                    <?php echo $form->textarea('e_individual_name', $data['e_individual_name'], ['oninput' => 'updateCard();', 'rows' => 2, 'placeholder' => translate('IndividualEnrollNamePlaceholder', $bLanguage, false), 'maxlength' => 120]); ?>
                    <div class="pull-right">
                        <span id="countdIndividualName">0</span> <?php echo translate('OutOFLabel', $bLanguage, false) ?> 120 <?php echo translate('CharacterLabel', $bLanguage, false) ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                <p style="text-align: center;"><?php echo translate('or', $bLanguage, false) ?></p>
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
                        <?php echo $form->textarea('e_family_name', $data['e_family_name'], ['oninput' => 'updateCard()', 'rows' => 2, 'placeholder' => translate('FamiliyEnrollNamePlaceholder', $bLanguage, false), 'maxlength' => 120]); ?>
                        <div class="pull-right">
                            <span id="countdFamilyName">0</span> <?php echo translate('OutOFLabel', $bLanguage, false) ?> 120 <?php echo translate('CharacterLabel', $bLanguage, false) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <!-- Display Living or deceased when form type is Perpetual Enrollment – Padded card (PE Padded) -->
        <?php if ($bFormType == 1 || $bFormType == 2 || $bFormType == 4) {?>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo $form->radio("i_living_deceased", 'living', (isset($data['i_living_deceased']) && $data['i_living_deceased']=='living' ? true : (!isset($data['i_living_deceased']) ? true : false))); ?>
                        <?php echo $form->label('i_living_deceased', translate('LivingLabel', $bLanguage, false));?>	
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo $form->radio("i_living_deceased", 'deceased', (isset($data['i_living_deceased']) && $data['i_living_deceased']=='deceased' ? true : false)); ?>    
                        <?php echo $form->label('i_living_deceased', translate('DeceasedLabel', $bLanguage, false));?>
                    </div>
                </div>
            </div>
        <?php }?>
        <h3 class="required"><?php echo translate('RequestedByLabel', $bLanguage, false) ?> <i class="fa fa-question-circle" data-toggle="tooltip" title="Type your message and sign your name e.g., with love Bob and Mary" aria-hidden="true" title='<?php echo translate('RequestedByInfo', $bLanguage, false) ?>'></i></h3>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <p class="help-block"><?php echo translate('RequestedByTitle', $bLanguage, false) ?></p>
                    <?php echo $form->textarea('e_requested_by', $data['e_requested_by'], ['oninput' => 'updateCard()','rows' => 4, 'placeholder' => translate('RequestedByLabel', $bLanguage, false)]); ?>
                    <div class="pull-right">
                        <span id="countTxtRequestedBy">0</span> <?php echo translate('OutOFLabel', $bLanguage, false) ?> 120 <?php echo translate('CharacterLabel', $bLanguage, false) ?>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once 'folder.php'; ?>
        <h3><?php echo translate('SpecialInstructionsLabel', $bLanguage, false) ?></h3>
        <p class="help-block"><?php echo translate('SpecialInstructionsTitle', $bLanguage, false) ?></p>
        <?php echo $form->textarea('e_special_instructions', $data['e_special_instructions'], ['rows' => 4, 'placeholder' => translate('SpecialInstructionsLabel', $bLanguage, false)]); ?>
	<h3><?php echo translate('SupportWithDonationLabel', $bLanguage, false).' <i class="fa fa-question-circle" data-toggle="tooltip" title="'.translate('SupportWithDonationInfo', $bLanguage, false) .'" aria-hidden="true"></i> '; ?></h3>
	<?php echo $form->select('e_support_donation', ['' => translate('SupportWithDonationPlaceholder', $bLanguage, false)] + $support_donation, $data['e_support_donation']); ?>
        <h3><?php translate('DeliveryDetailsLabel', $bLanguage)?></h3>
        <div class="form-group">
            <?php echo $form->checkbox("dChkSendNotification", 'checked', ($data['dChkSendNotification']=='checked')); ?>
            <?php echo translate('SendNotificationLabel', $bLanguage, false) ?><br>
        </div>
        <div id="delivery_details">
            <div class="form-group">
                <?php echo $form->label('e_notification_language', translate('NotificationLanguangeLabel', $bLanguage, false), ['class' => 'required']); ?>
                <?php echo $form->select('e_notification_language', ['' => translate('NotificationLanguangeLabel', $bLanguage, false)] + $notificationLanguage, $data['e_notification_language']); ?>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group">
                        <?php echo $form->label('e_title', translate('TitleLabel', $bLanguage, false)); ?>
                        <?php echo $form->text('e_title', $data['e_title'],['placeholder' => translate('TitleLabel', $bLanguage, false),'maxlength' => 50]); ?>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <?php echo $form->label('e_first_name', translate('FirstNameLabel', $bLanguage, false), ['class' => 'required']); ?>
                        <?php echo $form->text('e_first_name', $data['e_first_name'], ['placeholder' => translate('FirstNameLabel', $bLanguage, false), 'maxlength' => 50]); ?>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <?php echo $form->label('e_last_name', translate('LastNameLabel', $bLanguage, false) , ['class' => 'required']); ?>
                        <?php echo $form->text('e_last_name', $data['e_last_name'], ['placeholder' => translate('LastNameLabel', $bLanguage, false),'maxlength' => 75]); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo $form->label('e_address', translate('AddressLabel', $bLanguage, false), ['class' => 'required']); ?>
                        <?php echo $form->text('e_address', $data['e_address'], ['maxlength' => 100, 'placeholder' => translate('AddressLabel', $bLanguage, false)]); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo $form->label('e_address2', translate('Address2Label', $bLanguage, false)); ?>
                        <?php echo $form->text('e_address2', $data['e_address2'], ['maxlength' => 100, 'placeholder' => translate('Address2Label', $bLanguage, false)]); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label('e_city', translate('CityLabel', $bLanguage, false), ['class' => 'required']); ?>
                        <?php echo $form->text('e_city', $data['e_city'], ['maxlength' => 50, 'placeholder' => translate('CityLabel', $bLanguage, false)]); ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label('e_country', translate('CountryLabel', $bLanguage, false), ['class' => 'required']); ?>
                        <?php echo $form->select('e_country', ['' => translate('CountryLabel', $bLanguage, false)] + $countries, ($data['e_country'] ? $data['e_country'] : 'US'),['class' =>'country']); ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label('e_stateDropdown', translate('StateProvinceLabel', $bLanguage, false), ['class' => 'required stateDropdown']); ?>
                        <?php echo $form->select('e_stateDropdown', ['' => translate('StateProvinceLabel', $bLanguage, false)] + $states, $data['e_state'], ['class' => 'stateDropdown']); ?>
                        <?php echo $form->label('e_stateText', translate('Address3Label', $bLanguage, false), ['class' => 'stateText']); ?>
                        <?php echo $form->text('e_stateText', $data['e_state'], ['maxlength' => 30, 'placeholder' => translate('Address3Label', $bLanguage, false), 'class' => 'stateText']); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label('e_zip', translate('ZipPostalLabel', $bLanguage, false), ['class' => 'required']); ?>
                        <?php echo $form->text('e_zip', $data['e_zip'], ['maxlength' => 20, 'placeholder' => translate('ZipPostalPlaceholder', $bLanguage, false)]); ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label('e_email', translate('EmailLabel', $bLanguage, false), $bFormType == 2 ? ['class' => 'required'] : ''); ?>
                        <?php echo $form->text('e_email', $data['e_email'], ['maxlength' => 75, 'placeholder' => translate('EmailLabel', $bLanguage, false)]); ?>
                    </div>
                </div>
            </div>
        </div>    
        <hr class="mar-tp-btm"/>        
        <div class="form-action">  
	    <?php if (in_array($bFormType, [1,2,3])) { ?>
	    <a href="#card-preview" class="btn-text"><?php echo translate('ViewPreview', $bLanguage, false) ?></a>
	    <?php } ?>
            <button type="submit" class="btn-theme step-1-submit">
                <?php echo translate('ContinueLabel', $bLanguage, false); ?>
            </button>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </form>
<?php }?>