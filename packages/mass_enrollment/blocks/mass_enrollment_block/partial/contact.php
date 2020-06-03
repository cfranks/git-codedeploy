<form class="divine-form" method="POST" action="<?php echo $this->action('submit_contact') ?>">
    <?php include_once 'steps.php'; ?>
    
    <div class="row">
        <div class="col-sm-12">
            <h3><?php translate('NameLabel', $bLanguage) ?></h3>
        </div>
	<?php if(in_array($bFormType, [11,5,6,13])) { ?>
	    <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <?php echo $form->label('c_title', translate('TitleLabel', $bLanguage, false));?>
                            <?php echo $form->text('c_title', $data['c_title'], ['maxlength' => 50, 'placeholder' => translate('TitleLabel', $bLanguage, false)]); ?>
                    	</div>
                    </div>
                    <div class="col-sm-4">
                    	<div class="form-group">
                            <?php echo $form->label('c_first_name', translate('FirstNameLabel', $bLanguage, false),['class'=>'required']);?>
                            <?php echo $form->text('c_first_name', $data['c_first_name'], ['maxlength' => 50, 'placeholder' => translate('FirstNameLabel', $bLanguage, false)]); ?>
                    	</div>
                    </div>
                    <div class="col-sm-5">
                    	<div class="form-group">
                            <?php echo $form->label('c_last_name', translate('LastNameLabel', $bLanguage, false),['class'=>'required']);?>
                            <?php echo $form->text('c_last_name', $data['c_last_name'], ['maxlength' => 75, 'placeholder' => translate('LastNameLabel', $bLanguage, false)]); ?>
                    	</div>
                    </div>
            	</div>
            </div>
        <?php } else { ?>
	    <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <?php echo $form->label('c_title', translate('TitleLabel', $bLanguage, false));?>
                            <?php echo $form->text('c_title', $data['c_title'], ['maxlength' => 50, 'placeholder' => translate('TitleLabel', $bLanguage, false)]); ?>
                    	</div>
                    </div>
                    <div class="col-sm-5">
                    	<div class="form-group">
                            <?php echo $form->label('c_first_name', translate('FirstNameLabel', $bLanguage, false),['class'=>'required']);?>
                            <?php echo $form->text('c_first_name', $data['c_first_name'], ['maxlength' => 50, 'placeholder' => translate('FirstNameLabel', $bLanguage, false)]); ?>
                    	</div>
                    </div>
                    <div class="col-sm-5">
                    	<div class="form-group">
                            <?php echo $form->label('c_last_name', translate('LastNameLabel', $bLanguage, false),['class'=>'required']);?>
                            <?php echo $form->text('c_last_name', $data['c_last_name'], ['maxlength' => 75, 'placeholder' => translate('LastNameLabel', $bLanguage, false)]); ?>
                    	</div>
                    </div>
            	</div>
            </div>
	<?php } ?>
    </div>
    <hr/>
    <div class="row">
        <div class="col-sm-12">
            <h3><?php translate('AddressDetailsLabel', $bLanguage) ?></h3>
        </div>                    
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo $form->label('c_address', translate('AddressLabel', $bLanguage, false),['class'=>'required']);?>
                        <?php echo $form->text('c_address', $data['c_address'], ['maxlength' => 100, 'placeholder' => translate('AddressLabel', $bLanguage, false)]); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo $form->label('c_address2', translate('Address2Label', $bLanguage, false));?>
                        <?php echo $form->text('c_address2', $data['c_address2'], ['maxlength' => 100, 'placeholder' => translate('Address2Label', $bLanguage, false)]); ?>
                    </div>
                </div>
            </div>
            <?php if(in_array($bFormType, [11,5,6,13])) { ?>
	    	<div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo $form->label('c_city', translate('CityLabel', $bLanguage, false),['class'=>'required']);?>
                        <?php echo $form->text('c_city', $data['c_city'], ['maxlength' => 50, 'placeholder' => translate('CityLabel', $bLanguage, false)]); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo $form->label('c_country',translate('CountryLabel', $bLanguage, false),['class'=>'required']);?>
                        <?php echo $form->select('c_country', ['' => translate('CountryLabel', $bLanguage, false)] + $countries,  ($data['c_country'] ? $data['c_country'] : 'US'), ['class' =>'country']); ?>
                        
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo $form->label('c_stateDropdown', translate('StateProvinceLabel', $bLanguage, false),['class'=>'required stateDropdown']);?>
                        <?php echo $form->select('c_stateDropdown',['' => translate('StateProvinceLabel', $bLanguage, false)] + $states,$data['c_state'],['class'=>'stateDropdown']); ?>
                        <?php echo $form->label('c_stateText', translate('Address3Label', $bLanguage, false),['class'=>'stateText']);?>
                        <?php echo $form->text('c_stateText', $data['c_state'], ['maxlength' => 30, 'placeholder' => translate('Address3Label', $bLanguage, false),'class'=>'stateText']); ?>
                    </div>
                </div>
		<div class="col-sm-6">
                    <div class="form-group">
                        <?php echo $form->label('c_zip', translate('ZipPostalLabel', $bLanguage, false),['class'=>'required']);?>
                        <?php echo $form->text('c_zip', $data['c_zip'], ['maxlength' => 20, 'placeholder' => translate('ZipPostalPlaceholder', $bLanguage, false)]); ?>
                    </div>
                </div>
            </div>
	    <?php } else { ?>
	    	<div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label('c_city', translate('CityLabel', $bLanguage, false),['class'=>'required']);?>
                        <?php echo $form->text('c_city', $data['c_city'], ['maxlength' => 50, 'placeholder' => translate('CityLabel', $bLanguage, false)]); ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label('c_country',translate('CountryLabel', $bLanguage, false),['class'=>'required']);?>
                        <?php echo $form->select('c_country', ['' => translate('CountryLabel', $bLanguage, false)] + $countries,  ($data['c_country'] ? $data['c_country'] : 'US'), ['class' =>'country']); ?>
                        
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label('c_stateDropdown', translate('StateProvinceLabel', $bLanguage, false),['class'=>'required stateDropdown']);?>
                        <?php echo $form->select('c_stateDropdown',['' => translate('StateProvinceLabel', $bLanguage, false)] + $states,$data['c_state'],['class'=>'stateDropdown']); ?>
                        <?php echo $form->label('c_stateText', translate('Address3Label', $bLanguage, false),['class'=>'stateText']);?>
                        <?php echo $form->text('c_stateText', $data['c_state'], ['maxlength' => 30, 'placeholder' => translate('Address3Label', $bLanguage, false),'class'=>'stateText']); ?>
                    </div>
                </div>
            </div>
	    <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo $form->label('c_zip', translate('ZipPostalLabel', $bLanguage, false),['class'=>'required']);?>
                        <?php echo $form->text('c_zip', $data['c_zip'], ['maxlength' => 20, 'placeholder' => translate('ZipPostalPlaceholder', $bLanguage, false)]); ?>
                    </div>
                </div>
            </div>
	    <?php } ?>
            
        </div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-sm-12">
            <h3><?php translate('ContactDetailsHeader', $bLanguage) ?></h3>
        </div>
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo $form->label('c_email', translate('EmailLabel', $bLanguage, false),['class'=>'required']);?>
                        <?php echo $form->text('c_email', $data['c_email'], ['maxlength' => 75, 'placeholder' => translate('EmailLabel', $bLanguage, false)]); ?>
                    </div>
                </div>
		<div class="col-sm-6">
                    <div class="form-group">
                        <?php echo $form->label('c_home_phone', translate('HomePhoneLabel', $bLanguage, false));?>
                        <?php echo $form->text('c_home_phone', $data['c_home_phone'], ['maxlength' => 38, 'placeholder' => translate('HomePhoneLabel', $bLanguage, false)]); ?>
                    </div>
                </div>
            </div>
            <div class="row" style="display:none;">
               
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo $form->label('c_cell_phone', translate('CellPhoneLabel', $bLanguage, false));?>
                        <?php echo $form->text('c_cell_phone', $data['c_cell_phone'], ['maxlength' => 38, 'placeholder' => translate('CellPhoneLabel', $bLanguage, false)]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-action"> 
        <div class="pull-left">
            <a href="<?php echo $this->action('enrollment'); ?>" class="btn btn-default">
                <?php translate('BackLabel', $bLanguage) ?>
            </a>            
            <a href="<?php echo $this->action('cancel'); ?>" onclick="return confirm('<?php translate('ConfirmCancel', $bLanguage) ?>')" class="btn btn-danger">
                <?php translate('CancelLabel', $bLanguage) ?>
            </a>
        </div> 
        <button type="submit" class="btn-theme step-2-submit">
            <?php echo translate('ContinueLabel', $bLanguage, false); ?>
        </button>
        <div class="clearfix"></div>
    </div>
</form>