
<?php if (isset($message_custom)) {?>
    <div style="margin-top:20px"  class="alert alert-info">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo translate('MessageSuccess', $bLanguage, false) ?>
    </div>
<?php } else {?>
    <form class="divine-form" method="POST" action="<?php echo $this->action('submit_enrollment') ?>">
        <ol class="form-steps">
            <li class="active">
                <?php echo translate('DonationDetailsTab', $bLanguage, false) ?>
            </li>
            <li>
                <?php echo translate('ContactDetailsTab', $bLanguage, false) ?>
            </li>
            <li> 
                <?php echo translate('PaymentDetailTab', $bLanguage, false) ?>
            </li>
        </ol>
        <?php if (isset($error_messages)) {?>
            <br>
            <div style="margin-top:20px"  class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <p><?php translate('CorrectFollowing', $bLanguage) ?></p>
                <ul>
                    <?php
                        $message_shown = array();
                        foreach ($error_messages as $error_message) {
                            if (!in_array($error_message->getMessage(), $message_shown)) {
                    ?>
                                <li>
                                    <?php $message_shown[] = $error_message->getMessage();?>
                                    <?php translate($error_message->getMessage(), $bLanguage);?>
                                </li>
                    <?php
                            }
                        }
                ?>
                </ul>
            </div>
            <br>
        <?php }?>
        <?php if ($bFormType != 4) {?>
            <h3>
                <?php echo translate('FolderDetailsLabel', $bLanguage, false) ?>
                <a href="#.html" id="card-flip">
                    <i class="fa fa-repeat" aria-hidden="true"></i> <?php translate('FlipCard', $bLanguage) ?>
                </a>
            </h3>
            <div class="card-preview" id="card-preview">
                <div class="col flip-container">
                    <div class="card-flip">
                        <div class="front">
                            <figure class="card-left-bkg">
                                <img src="<?=$RelativePath. $folPath . $folders[$selected_folder]['folder_in_left']?>" alt="Folder Image">
                            </figure>
                            <figure class="card-img">
                                <img src="<?=$selected_image_path?>" alt="Folder Image">
                            </figure>
                        </div>
                        <div class="back">
                            <img src="<?=$RelativePath. $folPath . $folders[$selected_folder]['folder_cover']?>" alt="Folder Image">
                        </div>
                    </div>
                    <div class="img-inset">
                        <img src="<?=$RelativePath. $folPath . $folders[$selected_folder]['folder_inset']?>" alt="Folder Image">
                    </div>
                </div>
                <div class="col">
                    <figure class="card-right-bkg">
                        <img src="<?=$RelativePath. $folPath . $folders[$selected_folder]['folder_in_right']?>" alt="Folder Image">
                    </figure>
                    <div id="card-text">
                        <div class="card-title">
                            <h3><?php translate('MissionMassLeague', $bLanguage) ?></h3>
                            <p><?php translate('MissionariesDivineWord', $bLanguage) ?></p>
                        </div>
                        <div class="card-name-for">
                            <span>TOdo</span>
                        </div>
                        <div class="card-content">
                            <p><?php translate('PrayerMessage', $bLanguage) ?></p>
                            <p><?php translate('WithPrayer', $bLanguage) ?> <span class="card-name-by">Luke Skywalker</span></p>
                            <p class="card-date">TOdo</p>
                        </div>
                    </div>
                </div>
            </div>
            <p class="small"><?php translate('ActualSize', $bLanguage) ?></p>
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
                                <img src="<?php echo $RelativePath. $fPath . $image['image_thumb']; ?>">
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
                    <label><?php translate('CardImage', $bLanguage) ?></label>
                    <img class="selected-img" src="<?php echo $selected_image_path; ?>" alt="Selected Image">
                    <a id="card-modal-btn"><?php translate('SelectYourImage', $bLanguage) ?></a>
                </div>
                <div class="col-sm-4">
                    <div class="form-group no-before">
                        <label><?php translate('FolderColor', $bLanguage) ?></label>
                        <?php foreach($folders as $id => $folder) {  ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="folder" value="<?php echo $id;?>" <?php echo $id==$selected_folder ? 'checked' : ''; ?>>
                                    <img src="<?=$RelativePath. $folPath . $folder['folder_color']?>" alt="Folder Image">
                                    <?php echo translate($folder['translation_key'], $bLanguage, false) ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <?php echo $form->label('e_language', translate('LanguageLabel', $bLanguage, false)); ?>
                        <?php echo $form->select('e_language', ['' => '-- Select Langauge --'] + Config::get('mass_enrollment::languages'), ($data['e_language'] ? $data['e_language'] : $bLanguage)); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->label('e_occasion', translate('OcassionLabel', $bLanguage, false)); ?>
                        <?php echo $form->select("e_occasion", ['' => '-- Select Ocassion --'] + Config::get('mass_enrollment::custom.occasion'), $data['e_occasion']); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->label('e_date', translate('EnrollmentDateLabel', $bLanguage, false)); ?>
                        <?php echo $form->text("e_date", '', array('class' => 'form-control margin-bottom datepicker', 'placeholder' => translate('EnrollmentDateLabel', $bLanguage, false))); ?>
                    </div>
                </div>
            </div>
        <?php } else {?>
            <h3><?php echo translate('EnrollmentDetailsTitle', $bLanguage, false) ?></h3>
            <div class="form-group">
                <?php echo $form->label('e_occasion', translate('OcassionLabel', $bLanguage, false)); ?>
                <?php echo $form->select("e_occasion", ['' => '-- Select Ocassion --'] + Config::get('mass_enrollment::custom.occasion'), $data['e_occasion']); ?>
            </div>
            <div class="form-group">
                <?php echo $form->label('e_date', translate('EnrollmentDateLabel', $bLanguage, false)); ?>
                <?php echo $form->text("e_date", '', array('class' => 'form-control margin-bottom datepicker', 'placeholder' => translate('EnrollmentDateLabel', $bLanguage, false))); ?>
            </div>
        <?php }?>
        <h3><?php echo translate('EnrollmentTypeTitle', $bLanguage, false) ?></h3>
        <div class="row" id="enroll_type">
            <div class="col-sm-5">
                <div class="form-group">
                    <div class="radio">
                        <label>
                            <?php echo $form->radio("e_enrollment_type", 'individual', ['checked' => 'checked']); ?>
                            <?php echo translate('IndividualPriceLabel', $bLanguage, false) ?>
                            (
                                <?php
                                    if ($bFormType == 1) {
                                        echo '$30.00';
                                    } elseif ($bFormType == 3) {
                                        echo '$20.00';
                                    } elseif ($bFormType == 2) {
                                        echo '$10.00';
                                    } elseif ($bFormType == 4) {
                                        echo '$10.00';
                                    } else {
                                        echo '';
                                    }

                                ?>
                            )
                        </label>
                    </div>
                </div>
                <div class="form-group" id="enroll_individual">
                    <p class="help-block">
                    <?php echo translate('IndividualTextTitle', $bLanguage, false) ?>
                    </p>
                    <?php echo $form->textarea('e_individual_name', $data['e_individual_name'],
            ['rows' => 2, 'placeholder' => translate('IndividualEnrollNamePlaceholder',
                $bLanguage, false), 'maxlength' => 120]); ?>
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
                            <?php echo $form->radio("e_enrollment_type", 'family'); ?>
                            <?php echo translate('FamilyPriceLabel', $bLanguage, false) ?>
                            (
                                <?php
                                    if ($bFormType == 1) {
                                            echo '$40.00';
                                        } elseif ($bFormType == 3) {
                                            echo '$25.00';
                                        } elseif ($bFormType == 2) {
                                            echo '$15.00';
                                        } elseif ($bFormType == 4) {
                                            echo '$15.00';
                                        } else {
                                            echo '';
                                        }
                                ?>
                            )
                        </label>
                    </div>
                    <div class="form-group" id="enroll_family" style="display: none;">
                        <p class="help-block">
                        <?php echo translate('FamilyEnrollNameTitle', $bLanguage, false) ?>
                        </p>
                        <?php echo $form->textarea('e_family_name', $data['e_family_name'],
            ['rows' => 2, 'placeholder' => translate('FamiliyEnrollNamePlaceholder',
                $bLanguage, false), 'maxlength' => 120]); ?>
                        <div class="pull-right">
                            <span id="countdFamilyName">0</span> <?php echo translate('OutOFLabel', $bLanguage, false) ?> 120 <?php echo translate('CharacterLabel', $bLanguage, false) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h3><?php echo translate('RequestedByLabel', $bLanguage, false) ?> <i class="fa fa-question-circle" aria-hidden="true"
            title='<?php echo translate('RequestedByInfo', $bLanguage, false) ?>'></i></h3>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <p class="help-block"><?php echo translate('RequestedByTitle', $bLanguage, false) ?></p>
                    <?php echo $form->textarea('e_requested_by', $data['e_requested_by'],
            ['rows' => 4, 'placeholder' => translate('RequestedByLabel', $bLanguage, false)]); ?>
                    <div class="pull-right">
                        <span id="countTxtRequestedBy">0</span> <?php echo translate('OutOFLabel', $bLanguage, false) ?> 120 <?php echo translate('CharacterLabel', $bLanguage, false) ?>
                    </div>
                </div>
            </div>
        </div>
        <h3><?php echo translate('SpecialInstructionsLabel', $bLanguage, false) ?></h3>
        <p class="help-block"><?php echo translate('SpecialInstructionsTitle', $bLanguage, false) ?></p>
        <?php echo $form->textarea('e_special_instructions', $data['e_special_instructions'],
            ['rows' => 4, 'placeholder' => translate('SpecialInstructionsLabel', $bLanguage, false)]); ?>
        <br>
        <!-- Display Living or deceased when form type is Perpetual Enrollment – Acknowledgement card (PE – Ackn) -->
        <?php if ($bFormType == 3) {?>
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group">
                        <?php echo $form->radio("e_living_deceased", 'living', ['checked' => 'checked']); ?>
                        <?php echo translate('LivingLabel', $bLanguage, false) ?>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <?php echo $form->radio("e_living_deceased", 'deceased'); ?>
                        <?php echo translate('DeceasedLabel', $bLanguage, false) ?>
                    </div>
                </div>
            </div>
        <?php }?>
        <h3><?php translate('AddressDetailsLabel', $bLanguage)?></h3>
        <div class="form-group">
            <?php echo $form->checkbox("dChkSendNotification", 'checked', ['checked' => true],$data['dChkSendNotification']); ?>
            <?php echo translate('SendNotificationLabel', $bLanguage, false) ?><br>
        </div>
        <div id="delivery_details">
            <div class="form-group">
                <?php echo $form->label('e_notification_language', translate('NotificationLanguangeLabel', $bLanguage, false), ['class' => 'required']); ?>
                <?php echo $form->select('e_notification_language', ['' => '-- Select Langauge --'] + Config::get('mass_enrollment::languages'), $data['e_notification_language']); ?>
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
                        <?php echo $form->label('e_first_name', translate('FirstNameLabel', $bLanguage, false)
            , ['class' => 'required']); ?>
                        <?php echo $form->text('e_first_name', $data['e_first_name'],
            ['placeholder' => translate('FirstNameLabel', $bLanguage, false),
                'maxlength' => 50]); ?>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <?php echo $form->label('e_last_name', translate('LastNameLabel', $bLanguage, false)
            , ['class' => 'required']); ?>
                        <?php echo $form->text('e_last_name', $data['e_last_name'],
            ['placeholder' => translate('LastNameLabel', $bLanguage, false),
                'maxlength' => 75]); ?>
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
                        <?php echo $form->text('e_city', $data['e_city'], ['maxlength' => 50, 'placeholder' =>
            translate('CityLabel', $bLanguage, false)]); ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label('e_country', translate('CountryLabel', $bLanguage, false), ['class' => 'required']); ?>
                        <?php echo $form->select('e_country', ['' => '-- Select Country --'] + Config::get('mass_enrollment::custom.Country'), $data['e_country'],['class' =>'country']); ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label('e_stateDropdown', translate('StateProvinceLabel', $bLanguage, false), ['class' => 'required stateDropdown']); ?>
                        <?php echo $form->select('e_stateDropdown', ['' => translate('StateProvinceLabel', $bLanguage, false)] + Config::get('mass_enrollment::custom.State'), ['class' => 'stateDropdown'], $data['e_state']); ?>
                        <?php echo $form->label('e_stateText', translate('StateProvinceLabel', $bLanguage, false), ['class' => 'required stateText']); ?>
                        <?php echo $form->text('e_stateText', $data['e_state'], ['maxlength' => 30, 'placeholder' => translate('StateProvinceLabel', $bLanguage, false), 'class' => 'stateText']); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label('e_zip', translate('ZipPostalLabel', $bLanguage, false),
            ['class' => 'required']); ?>
                        <?php echo $form->text('e_zip', $data['e_zip'], ['maxlength' => 20, 'placeholder' =>
            translate('ZipPostalPlaceholder', $bLanguage, false)]); ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label('e_email', translate('EmailLabel', $bLanguage, false)); ?>
                        <?php echo $form->text('e_email', $data['e_email'], ['maxlength' => 75, 'placeholder' =>
            translate('EmailLabel', $bLanguage, false)]); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->label('e_support_donation', translate('SupportWithDonationLabel', $bLanguage, false), ['class' => 'required']); ?>
            <?php echo $form->select('e_support_donation', ['' => '-- Select Donation --'] + Config::get('mass_enrollment::custom.SupportWithDonation'),$data['e_support_donation']); ?>
        </div>    
        <hr/>        
        <div class="form-action">  
            <button type="submit" class="btn-theme">
                <?php echo translate('ContinueLabel', $bLanguage, false); ?>
            </button>
            <div class="clearfix"></div>
        </div>
    </form>
<?php }?>
<script type="text/javascript">
    $("#dChkSendNotification").click(function(){
        if($(this).is(":checked")){
            $(this).val('checked');
            $("#delivery_details").show();
        } else{
            $(this).val('unchecked');
            $("#delivery_details").hide();
        }
    })
    //checks if the send notification checkbox is checked on page load.
    if($("#dChkSendNotification").is(":checked")){
        $("#delivery_details").show();
    } else {
        $("#delivery_details").hide();
    }
    $('#e_requested_by').on('input',function(e){
        if (this.value.length > 120) {
            this.value = this.value.substring(0, 120);
        }
        $('#countTxtRequestedBy').text(this.value.length);
    });

    $('#e_enrollment_type1').change(function(){
        $("#enroll_individual").show()
        $("#enroll_family").hide()
    })

    $('#e_enrollment_type2').change(function(){
        $("#enroll_individual").hide()
        $("#enroll_family").show()
    })

    $('#e_individual_name').on('input',function(e){
        if (this.value.length > 120) {
            this.value = this.value.substring(0, 120);
        }
        $('#countdIndividualName').text(this.value.length);
    });

    $('#e_family_name').on('input',function(e){
        if (this.value.length > 120) {
            this.value = this.value.substring(0, 120);
        }
        $('#countdFamilyName').text(this.value.length);
    });
</script>