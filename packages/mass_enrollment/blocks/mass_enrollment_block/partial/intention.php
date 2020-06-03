<?php if (isset($message_custom)) {?>
    <div style="margin-top:20px"  class="alert alert-info">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo translate('MessageSuccess', $bLanguage, false) ?>
    </div>
<?php } else {?>
	<form class="divine-form" method="POST" action="<?php echo $this->action('submit_enrollment') ?>">
		<?php include_once 'steps.php'; ?>
		<?php if (in_array($bFormType, [5,13])) { ?> 
		<h3><?php echo translate('MassDetailsLabel', $bLanguage, false)?></h3>
		<div class="row">

	    		<div class="col-sm-6">
				<div class="form-group">
					<?php echo $form->label('e_occasion', translate('OcassionLabel', $bLanguage, false), ['class' => 'required']); ?>
					<?php echo $form->select("e_occasion", $occasions, $data['e_occasion']); ?>
				</div>            
			</div>
			<div class="col-sm-6">
				<?php echo $form->label('i_number_masses', translate('NumberOfMassesLabel', $bLanguage, false).' <i class="fa fa-question-circle" data-toggle="tooltip" title="'.translate('NumberOfMassesInfo', $bLanguage, false). '" aria-hidden="true"></i> ', ['class'=>'required']);?><br/>
				<!--<i class="fa fa-question-circle" aria-hidden="true" title='<?php echo translate('NumberOfMassesInfo', $bLanguage, false) ?>'></i>-->
				<?php echo $form->number("i_number_masses", $data['i_number_masses'], array('class' => 'form-control', 'min' => 1,'onkeypress' => 'return event.charCode != 45','placeholder' => translate('NumberOfMassesPlaceholder', $bLanguage, false))); ?>
				<span class="secondary-label"> (<?php echo(translate('NumberOfMassesLabelSecondary', $bLanguage, false)); ?>)</span> 
			</div>
		</div>
		<?php } ?> 
		<h3><?php echo translate('RequestDetailsLabel', $bLanguage, false) ?></h3>
		<div class="form-group">
			<?php echo $form->label('e_intention', translate('YourIntentionsLabel', $bLanguage, false), ['class' => 'required']);?>
			<?php if (in_array($bFormType, [6])) {?> 
			<div class="pull-right"> 
				<?php echo translate('GregorianDonation', $bLanguage, false); ?> 
			</div> 
			<?php } ?>
			<?php echo $form->textarea('e_intention', $data['e_intention'], ['rows' => 2,'placeholder' => in_array($bFormType, [6]) ? translate('DeceasedPerson', $bLanguage, false) : translate('YourIntentionsPlaceholder', $bLanguage, false),'maxlength' => 120]); ?>
			<div class="pull-right">
				<span id="countdIntention">0</span> <?php echo translate('OutOFLabel', $bLanguage, false) ?> 120 <?php echo translate('CharacterLabel', $bLanguage, false) ?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label('e_requested_by', translate('RequestingMassesLabel', $bLanguage, false), ['class' => 'required']);?>	 
			<?php echo $form->textarea('e_requested_by', $data['e_requested_by'], ['rows' => 2,'placeholder' => translate('RequestingMassesPlaceholder', $bLanguage, false),'maxlength' => 120]); ?>
			<div class="pull-right">
				<span id="countTxtRequestedBy">0</span> <?php echo translate('OutOFLabel', $bLanguage, false) ?> 120 <?php echo translate('CharacterLabel', $bLanguage, false) ?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label('e_special_instructions', translate('SpecialInstructionsTitle', $bLanguage, false));?>	
			<?php echo $form->textarea('e_special_instructions', $data['e_special_instructions'], ['rows' => 4,'placeholder' => translate('SpecialInstructionsLabel',$bLanguage, false)]); ?>
		</div>
		<?php if (in_array($bFormType, [5,13])) { ?> 
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
		<?php } ?> 
		<?php if (!in_array($bFormType, [5,6,13])) { ?> 
		<div class="form-group">
			<?php echo $form->label('i_donation_amount', translate('DonationAmountLabel',$bLanguage, false),['class'=>'required']);?> 
			<?php echo $form->number('i_donation_amount', $data['i_donation_amount'], ['placeholder' => translate('DonationAmountPlaceholder',$bLanguage, false),'min' => 1,'onkeypress' => 'return event.charCode != 45','maxlength'=>50]); ?>
		</div>
		<?php } ?>
        <?php if (in_array($bFormType, [8,12])) { ?> 
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <br>
                    <?php echo $form->checkbox("dCheckIfYouWantTheCard",'checked', ($data['dCheckIfYouWantTheCard']=='checked')); ?>
                    <?php echo '<strong style="font-size: medium">'.translate('CheckIfYouWantTheCardOnBehalf',$bLanguage, false).'</strong>' ?><br>
                </div>        
            </div>
            <div class="col-sm-6 day-novena-languages">
                <div class="form-group">
                    <?php echo $form->label('e_language', translate('LanguageLabel', $bLanguage, false), ['class' => 'required']); ?>
                    <?php echo $form->select('e_language', $novenaCardLanguages, ($data['e_language'] ? $data['e_language'] : $bLanguage), ['onchange' => 'changeCardLangugage()']); ?>
                </div>
            </div>
        </div>
        <?php } ?>
	<h3><?php echo translate('SupportWithDonationLabel', $bLanguage, false).' <i class="fa fa-question-circle" data-toggle="tooltip" title="'.translate('SupportWithDonationInfo', $bLanguage, false) .'" aria-hidden="true"></i> '; ?></h3>
		<?php echo $form->select('e_support_donation', ['' => translate('SupportWithDonationPlaceholder', $bLanguage, false)] + $support_donation, $data['e_support_donation']); ?>
		<h3><?php translate('DeliveryDetailsLabel', $bLanguage) ?></h3>
		<div class="form-group">
			<?php echo $form->checkbox("dChkSendNotification",'checked', ($data['dChkSendNotification']=='checked')); ?>
			<?php echo translate('SendNotificationLabel',$bLanguage, false) ?><br>
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
                        <?php echo $form->label('e_email', translate('EmailLabel', $bLanguage, false)); ?>
                        <?php echo $form->text('e_email', $data['e_email'], ['maxlength' => 75, 'placeholder' => translate('EmailLabel', $bLanguage, false)]); ?>
                    </div>
                </div>
            </div>
		</div>
		<div class="form-action">  
            <button type="submit" class="btn-theme step-1-submit">
                <?php echo translate('ContinueLabel', $bLanguage, false); ?>
            </button>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
	</form>
<?php }?>
<script type="text/javascript">
    checkIfYouWantTheCard();
    $("#dChkSendNotification").click(function(){
        if($(this).is(":checked")){
            $(this).val('checked');
            $("#delivery_details").show();
        } else{
            $(this).val('unchecked');
            $("#delivery_details").hide();
        }
    })
    $("#dCheckIfYouWantTheCard").click(function(){
        checkIfYouWantTheCard();
    })
    function checkIfYouWantTheCard() {
        //checks if the send notification checkbox is checked on page load.
        if($("#dCheckIfYouWantTheCard").is(":checked")){
            $(".day-novena-languages").show();
        } else {
            $(".day-novena-languages").hide();
        }
    }
    $("#i_country").change(function(){
        if($(this).val() == "0"){
            $(".i_stateDropdown").show();
            $(".i_stateText").hide();
        } else if ($(this).val() == ""){
            $(".i_stateDropdown").hide();
            $(".i_stateText").hide();
        } else {
            $(".i_stateDropdown").hide();
            $(".i_stateText").show();
      	}
    });
</script>