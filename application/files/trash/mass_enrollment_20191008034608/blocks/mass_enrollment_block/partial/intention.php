<?php
defined('C5_EXECUTE') or die(_("Access Denied."));
?>
<?php $ih = Core::make('helper/concrete/ui'); ?>
<style>
    .required:after {
        content: "*";
        color: red;
    }
    .panel-border {
        border: solid 1px #ddd;
        border-top: 0px;
    }
</style>
<div class="panel">
	<form class="divine-form" method="POST" action="<?php echo $this->action('submit_enrollment') ?>">
		<div class="panel-body">
			<?php if (isset($error_messages)) { ?>
		    <br>
		    <div style="margin-top:20px"  class="alert alert-danger">
		        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		        <p>Please correct the following errors before proceeding</p>
		        <ul>
		            <?php
		            $messagi_shown = array();
		            foreach ($error_messages as $error_message) {
		                if (!in_array($error_message->getMessage(), $messagi_shown)) {
		                    ?>
		                    <li>
		                    <?php $messagi_shown[] = $error_message->getMessage(); ?>
		                    <?php translate($error_message->getMessage(), $bLanguage); ?>
		                    </li> 
		                    <?php
		                }
		            }
		            ?>
		        </ul>
		    </div>
		    <br>
		    <?php } ?>	
		    <h3><?php echo translate('MassDetailsLabel', $bLanguage, false)?></h3>
		    <div class="row">
		    	<div class="col-sm-6">
		    		<div class="form-group">
	                    <?php echo $form->label('i_occasion', translate('OcassionLabel', $bLanguage, false));?>
                    	<?php  echo $form->select("i_occasion",['' => '-- Select Occasion --'] + Config::get('mass_enrollment::custom.occasion'), $data['i_occasion']); ?>
                	</div>            
		    	</div>
		    	<div class="col-sm-6">
		    		<?php echo $form->label('i_number_masses', translate('NumberOfMassesLabel', $bLanguage, false),['class'=>'required']);?>
		    		<i class="fa fa-question-circle" aria-hidden="true" 
        				title='<?php echo translate('NumberOfMassesInfo', $bLanguage, false) ?>'></i>
                    <?php echo $form->number("i_number_masses", $data['i_number_masses'], array('class' => 'form-control', 
                    	'min' => 0,'placeholder' => translate('NumberOfMassesLabel', $bLanguage, false))); ?>
		    	</div>
		    </div>
		    <h3><?php echo translate('RequestDetailsLabel', $bLanguage, false) ?></h3>
		    <div class="form-group">
                <?php echo $form->label('i_your_intentions', translate('YourIntentionsLabel', $bLanguage, false));?>
                <?php echo $form->textarea('i_your_intentions', $data['i_your_intentions'],
                    ['rows' => 2,'placeholder' => translate('YourIntentionsPlaceholder', 
                        $bLanguage, false),'maxlength' => 120]); ?>
            </div>
            <div class="form-group">
                <?php echo $form->label('i_requesting_masses', translate('RequestingMassesLabel', $bLanguage, false));?>	 
                <?php echo $form->textarea('i_requesting_masses', $data['i_requesting_masses'],
                    ['rows' => 2,'placeholder' => translate('RequestingMassesPlaceholder', 
                        $bLanguage, false),'maxlength' => 120]); ?>
                <div class="pull-right">
                    <span id="countdRequestingMasses">0</span> <?php echo translate('OutOFLabel', $bLanguage, false) ?> 120 <?php echo translate('CharacterLabel', $bLanguage, false) ?>
                </div>
            </div>
            <div class="form-group">
			    <?php echo $form->label('i_special_instructions', translate('SpecialInstructionsTitle', $bLanguage, false));?>	
			    <?php echo $form->textarea('i_special_instructions', $data['i_special_instructions'],
			    ['rows' => 4,'placeholder' => translate('SpecialInstructionsLabel',$bLanguage, false)]); ?>
            </div>
            <div class="row">
		        <div class="col-sm-2">
		            <div class="form-group">
		                <?php echo $form->radio("i_living_deceased",'living',['checked' =>'checked']); ?>
		                <?php echo $form->label('i_living_deceased', translate('LivingLabel', $bLanguage, false));?>	
		            </div>
		        </div>
		        <div class="col-sm-2">
		            <div class="form-group">
		                <?php echo $form->radio("i_living_deceased",'deceased'); ?>    
		                <?php echo $form->label('i_living_deceased', translate('DeceasedLabel', $bLanguage, false));?>
		            </div>
		        </div>
		    </div>
		    <div class="form-group">
		    	<?php echo $form->label('i_donation_amount', translate('DonationAmountLabel',$bLanguage, false),['class'=>'required']);?> 
                <?php echo $form->number('i_donation_amount', $data['i_donation_amount'], 
                    ['placeholder' => translate('DonationAmountPlaceholder',$bLanguage, false),'maxlength'=>50]); ?>
		    </div>
		    <h3><?php translate('DeliveryDetailsLabel', $bLanguage) ?></h3>
		    <div class="form-group">
		        <?php echo $form->checkbox("dChkSendNotification",'checked',['checked' => true]); ?>
		        <?php echo translate('SendNotificationLabel',$bLanguage, false) ?><br>
		    </div>
		    <div id="delivery_details">
		        <div class="form-group">
		            <?php echo $form->label('i_notification_language', translate('NotificationLanguangeLabel',$bLanguage, false),['class'=>'required']);?>
		            <?php echo $form->select('i_notification_language', ['' => '-- Select Langauge --'] + Config::get('mass_enrollment::languages') ,  $data['i_notification_language']); ?>
		        </div>
		        <div class="row">
		            <div class="col-sm-2">
		                <div class="form-group">
		                    <?php echo $form->label('i_title', translate('TitleLabel',$bLanguage, false));?>
		                    <?php echo $form->text('i_title', $data['i_title'], 
		                            ['placeholder' => translate('TitleLabel',$bLanguage, false),
		                            'maxlength'=>50]); ?>
		                </div>
		            </div>
		            <div class="col-sm-5">
		                <div class="form-group">
		                    <?php echo $form->label('i_first_name', translate('FirstNameLabel',$bLanguage, false)
		                        ,['class'=>'required']);?>
		                    <?php echo $form->text('i_first_name', $data['i_first_name'], 
		                            ['placeholder' => translate('FirstNameLabel',$bLanguage, false),
		                            'maxlength'=>50]); ?>
		                </div>
		            </div>
		            <div class="col-sm-5">
		                <div class="form-group">
		                    <?php echo $form->label('i_last_name', translate('LastNameLabel',$bLanguage, false)
		                        ,['class'=>'required']);?>
		                    <?php echo $form->text('i_last_name', $data['i_last_name'], 
		                            ['placeholder' => translate('LastNameLabel',$bLanguage, false),
		                            'maxlength'=>75]); ?>
		                </div>
		            </div>
		        </div>
		        <div class="row">
		            <div class="col-sm-6">
		                <div class="form-group">
		                    <?php echo $form->label('i_address', translate('AddressLabel',$bLanguage, false),['class'=>'required']);?>
		                    <?php echo $form->text('i_address', $data['i_address'], ['maxlength' => 100, 'placeholder' => translate('AddressLabel',$bLanguage, false)]); ?>
		                </div>
		            </div>
		            <div class="col-sm-6">
		                <div class="form-group">
		                    <?php echo $form->label('i_address2', translate('Address2Label',$bLanguage, false));?> 
		                    <?php echo $form->text('i_address2', $data['i_address2'], ['maxlength' => 100, 'placeholder' => translate('Address2Label',$bLanguage, false)]); ?>
		                </div>
		            </div>
		        </div>
		        <div class="row">
		            <div class="col-sm-4">
		                <div class="form-group">
		                    <?php echo $form->label('i_city', translate('CityLabel',$bLanguage, false),['class'=>'required']);?>
		                    <?php echo $form->text('i_city', $data['i_city'], ['maxlength' => 50, 'placeholder' => 
		                    translate('CityLabel',$bLanguage, false)]); ?>
		                </div>
		            </div>
		            <div class="col-sm-4">
		                <div class="form-group">
		                    <?php echo $form->label('i_country', translate('CountryLabel',$bLanguage, false),['class'=>'required']);?>
		                    <?php echo $form->select('i_country', ['' => '-- Select Country --'] + Config::get('mass_enrollment::custom.Country') ,  $data['i_country']); ?>
		                </div>
		            </div>
		            <div class="col-sm-4">
		                <div class="form-group">
		                    <?php echo $form->label('i_stateDropdown', translate('StateProvinceLabel', $bLanguage, false),['class'=>'required stateDropdown']);?>
		                    <?php echo $form->select('i_stateDropdown',['' => translate('StateProvinceLabel', $bLanguage, false)] + Config::get('mass_enrollment::custom.State'),['class'=>'stateDropdown'],$data['i_state']); ?>
		                    <?php echo $form->label('i_stateText', translate('StateProvinceLabel', $bLanguage, false),['class'=>'required stateText']);?>
		                    <?php echo $form->text('i_stateText', $data['i_state'], ['maxlength' => 30, 'placeholder' => translate('StateProvinceLabel', $bLanguage, false),'class'=>'stateText']); ?>
		                </div>
		            </div>
		        </div>
		        <div class="row">
		            <div class="col-sm-4">
		                <div class="form-group">
		                    <?php echo $form->label('i_zip', translate('ZipPostalLabel',$bLanguage, false),
		                    ['class'=>'required']);?>
		                    <?php echo $form->text('i_zip', $data['i_zip'], ['maxlength' => 20, 'placeholder' => 
		                    translate('ZipPostalPlaceholder',$bLanguage, false)]); ?>
		                </div>
		            </div>
		            <div class="col-sm-4">
		                <div class="form-group">
		                    <?php echo $form->label('i_email', translate('EmailLabel',$bLanguage, false));?>
		                    <?php echo $form->text('i_email', $data['i_email'], ['maxlength' => 75, 'placeholder' => 
		                    translate('EmailLabel',$bLanguage, false)]); ?>
		                </div>
		            </div>
		        </div>
		    </div>
		    <div class="form-group">
		        <?php echo $form->label('i_support_donation', translate('SupportWithDonationLabel',$bLanguage, false),['class'=>'required']);?>
		        <?php echo $form->select('i_support_donation', ['' => '-- Select Donation --'] + Config::get('mass_enrollment::custom.SupportWithDonation'),
		            $data['donation_type']); ?>
		    </div>
		</div>
		<div class="panel-footer">
			<?php echo($ih->submit(translate('ContinueLabel',$bLanguage, false), 'btncontinue', 'right')); ?> 
			<div class="clearfix"></div>
		</div>
	</form>
</div>
<script type="text/javascript">
	$('#i_requesting_masses').on('input',function(e){
        if (this.value.length > 120) {
            this.value = this.value.substring(0, 120);
        }
        $('#countdRequestingMasses').text(this.value.length);
    });
    $("#dChkSendNotification").click(function(){
        if($(this).is(":checked")){
            $(this).val('checked');
            $("#delivery_details").show();
        } else{
            $(this).val('unchecked');
            $("#delivery_details").hide();
        }
    })
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