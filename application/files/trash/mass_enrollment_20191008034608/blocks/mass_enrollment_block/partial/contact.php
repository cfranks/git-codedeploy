<?php
    defined('C5_EXECUTE') or die(_("Access Denied."));
    $ih = Core::make('helper/concrete/ui');
    // $service = Core::make('helper/concrete/file_manager');
    // $toggle = Config::get('result_administrative::custom.Toggle');
?>
<ul class="nav nav-tabs nav-justified">
        <li >
            <a data-toggle="tab" href="#home">
                <?php echo translate('DonationDetailsTab', $bLanguage, false) ?> 
            </a>
        </li>
        <li class="active">
            <a href="<?php echo $this->action('contact'); ?>">
                <?php echo translate('ContactDetailsTab', $bLanguage, false) ?> 
            </a>
        </li>
        <li>
            <a href="#">
                <?php echo translate('PaymentDetailTab', $bLanguage, false) ?>
            </a>
        </li>
</ul>
<div class="panel panel-border">
    <form method="post" action='<?php echo $this->action('submit_contact'); ?>'>
        <div class="panel-body ">
     
            <?php if (isset($error_messages)) { ?>
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
                                <?php $message_shown[] = $error_message->getMessage(); ?>
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
            <div class="row">
                <div class="col-sm-3">
                    <h4><?php translate('ContactNameLabel', $bLanguage) ?></h4>
                </div>
                <div class="col-sm-9">
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
            <hr/>
            <div class="row">
                <div class="col-sm-3">
                <h4><?php translate('AddressDetailsLabel', $bLanguage) ?></h4>
                </div>                    
                <div class="col-sm-9">
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
                                <?php echo $form->select('c_country', ['' => translate('CountryLabel', $bLanguage, false)] + Config::get('mass_enrollment::custom.Country') ,  $data['c_country'],['class' =>'country']); ?>
                                
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?php echo $form->label('c_stateDropdown', translate('StateProvinceLabel', $bLanguage, false),['class'=>'required stateDropdown']);?>
                                <?php echo $form->select('c_stateDropdown',['' => translate('StateProvinceLabel', $bLanguage, false)] + Config::get('mass_enrollment::custom.State'),['class'=>'stateDropdown'],$data['c_state']); ?>
                                <?php echo $form->label('c_stateText', translate('StateProvinceLabel', $bLanguage, false),['class'=>'required stateText']);?>
                                <?php echo $form->text('c_stateText', $data['c_state'], ['maxlength' => 30, 'placeholder' => translate('StateProvinceLabel', $bLanguage, false),'class'=>'stateText']); ?>

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
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-sm-3">
                <h4><?php translate('ContactDetailsLabel', $bLanguage) ?></h4>
                </div>
                <div class="col-sm-9">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?php echo $form->label('c_email', translate('EmailLabel', $bLanguage, false),['class'=>'required']);?>
                                <?php echo $form->text('c_email', $data['c_email'], ['maxlength' => 75, 'placeholder' => translate('EmailLabel', $bLanguage, false)]); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?php echo $form->label('c_home_phone', translate('HomePhoneLabel', $bLanguage, false));?>
                                <?php echo $form->text('c_home_phone', $data['c_home_phone'], ['maxlength' => 40, 'placeholder' => translate('HomePhoneLabel', $bLanguage, false)]); ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?php echo $form->label('c_cell_phone', translate('CellPhoneLabel', $bLanguage, false));?>
                                <?php echo $form->text('c_cell_phone', $data['c_cell_phone'], ['maxlength' => 40, 'placeholder' => translate('CellPhoneLabel', $bLanguage, false)]); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <div class="panel-footer">
            <div class="pull-left">
                <?php echo($ih->button(translate('BackLabel', $bLanguage, false),$this->action('enrollment'), 'btn-default')); ?>
                <?php echo($ih->button(translate('CancelLabel', $bLanguage, false), $this->action('cancel'),  'btn-default')); ?>
            </div>
            <div class="pull-right">
                <?php echo($ih->submit(translate('ContinueLabel', $bLanguage, false), 'save', 'right', 'btn-success')); ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </form>
<script type="text/javascript">
  
</script>
<!-- </form> -->