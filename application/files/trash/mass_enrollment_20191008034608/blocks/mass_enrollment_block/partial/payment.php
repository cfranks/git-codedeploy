<?php
defined('C5_EXECUTE') or die(_("Access Denied."));
?>
<?php $ih = Core::make('helper/concrete/ui'); ?>
<ul class="nav nav-tabs nav-justified">
    <li>
        <a>
            <?php echo translate('DonationDetailsTab', $bLanguage, false) ?> 
        </a>
    </li>
    <li >
        <a>
            <?php echo translate('ContactDetailsTab', $bLanguage, false) ?> 
        </a>
    </li>
    <li class="active">
        <a>
            <?php echo translate('PaymentDetailTab', $bLanguage, false) ?>
        </a>
    </li>
</ul>
<div class="panel panel-border">
    <form class="form" method="POST" action="<?php echo $this->action('submit_payment') ?>">
        <div class="panel-body">
            <?php if (isset($error_messages)) { ?>
                <div style="margin-top:20px"  class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <p><?php translate('CorrectFollowing', $bLanguage) ?></p>
                    <ul>
                        <?php
                        $message_shown = array();
                        foreach ($error_messages as $error_message) {
                            if (!in_array($error_message->getMessage(), $message_shown)) {
                                ?>
                                <li><?php echo $message_shown[] = $error_message->getMessage(); ?></li> 
                                <?php
                            }
                        }
                        ?>
                    </ul>
                </div>
            <?php } ?>
            <div class="row">
                <div class="col-sm-3">
                    <h4><?php translate('PaymentInformationLabel', $bLanguage) ?></h4> 
                </div>
                <div class="col-sm-9">
                    <div class="form-group">
                        <?php echo $form->label('p_card_holder_name', translate('CardHolderNameLabel', $bLanguage, false),['class'=>'required']);?> 
                        <?php echo $form->text('p_card_holder_name', $data['p_card_holder_name'], ['placeholder' => translate('CardHolderNameLabel', $bLanguage, false)]); ?>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?php echo $form->label('p_card_type', translate('CardTypeLabel', $bLanguage, false),['class'=>'required']);?>
                                <?php  echo $form->select("p_card_type", ['' => translate('CardTypeLabel', $bLanguage, false)] + Config::get('mass_enrollment::custom.CardType'),$data['p_card_type']); ?>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <?php echo $form->label('p_card_number', translate('CardNumberLabel', $bLanguage, false),['class'=>'required']);?>
                                <?php echo $form->text('p_card_number', $data['p_card_number'], ['placeholder' => translate('CardNumberLabel', $bLanguage, false),'maxlength' => 16]); ?>      
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?php echo $form->label('p_exp_month', translate('ExpMonthLabel', $bLanguage, false),['class'=>'required']);?> 
                                <?php  echo $form->select("p_exp_month",['' => translate('ExpMonthPlaceholder', $bLanguage, false)] + Config::get('mass_enrollment::custom.ExpireMonth'), $data['p_exp_month']); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?php echo $form->label('p_exp_year', translate('ExpYearLabel', $bLanguage, false),['class'=>'required']);?>
                                <?php  echo $form->select("p_exp_year", ['' => translate('ExpYearPlaceholder', $bLanguage, false)] + Config::get('mass_enrollment::custom.ExpireYear'), $data['p_exp_year']); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?php echo $form->label('p_cvv', translate('CVVLabel', $bLanguage, false),['class'=>'required']);?>
                                <?php echo $form->text('p_cvv', $data['p_cvv'], 
                                ['maxlength'=> 4,'placeholder' => translate('CVVPlaceholder', $bLanguage, false)]); ?>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <?php echo $form->checkbox("chkSameAsContact", 'checked', false); ?>
                        <?php echo $form->label('chkSameAsContact', translate('SameAsContactDetail', $bLanguage, false));?>
                        <br>
                    </div>
                </div>
            </div>
            <div class="row billing-detail">
                <div class="col-sm-3">
                    <h4><?php translate('BillingDetailsLabel', $bLanguage) ?></h4> 
                </div> 
                <div class="col-sm-9">
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?php echo $form->label('p_address', translate('AddressLabel', $bLanguage, false),['class'=>'required']);?>
                                <?php echo $form->text('p_address', $data['p_address'], ['maxlength' => 100, 'placeholder' => translate('AddressLabel', $bLanguage, false)]); ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?php echo $form->label('p_address2', translate('Address2Label', $bLanguage, false));?> 
                                <?php echo $form->text('p_address2', $data['p_address2'], ['maxlength' => 100, 'placeholder' => translate('Address2Label', $bLanguage, false)]); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?php echo $form->label('p_city', translate('CityLabel', $bLanguage, false),['class'=>'required']);?>
                                <?php echo $form->text('p_city', $data['p_city'], ['maxlength' => 50, 'placeholder' => translate('CityLabel', $bLanguage, false)]); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?php echo $form->label('p_country', translate('CountryLabel', $bLanguage, false),['class'=>'required']);?>
                                <?php echo $form->select('p_country', ['' => translate('CountryLabel', $bLanguage, false)] + Config::get('mass_enrollment::custom.Country') ,  $data['country'],['class' =>'country']); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?php echo $form->label('p_stateDropdown', translate('StateProvinceLabel', $bLanguage, false),['class'=>'required stateDropdown']);?>
                                <?php echo $form->select('p_stateDropdown',['' => 'State/Province'] + Config::get('mass_enrollment::custom.State'),['class'=>'stateDropdown'],$data['p_state']); ?>
                                <?php echo $form->label('p_stateText', translate('StateProvinceLabel', $bLanguage, false),['class'=>'required stateText']);?>
                                <?php echo $form->text('p_stateText', $data['p_state'], ['maxlength' => 45, 'placeholder' => 'State/Province','class'=>'stateText']); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?php echo $form->label('p_zip', translate('ZipPostalPlaceholder', $bLanguage, false),['class'=>'required']);?>
                                <?php echo $form->text('p_zip', $data['p_zip'], ['maxlength' => 20, 'placeholder' => translate('ZipPostalPlaceholder', $bLanguage, false)]); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?php echo $form->label('p_email', translate('EmailLabel', $bLanguage, false));?>
                                <?php echo $form->text('p_email', $data['p_email'], ['maxlength' => 75, 'placeholder' => translate('EmailLabel', $bLanguage, false)]); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <div class="pull-left">
                <?php echo($ih->button(translate('BackLabel', $bLanguage, false), $this->action('contact'), '' , 'btn-default')); ?>
                <?php echo($ih->button(translate('CancelLabel', $bLanguage, false), $this->url('#'), '')); ?>
            </div>
            <div class="pull-right">
                <?php echo($ih->submit(translate('SubmitLabel', $bLanguage, false), 'btnSubmit', 'right', 'btn-success')); ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#chkSameAsContact').change(function() {
            if($(this).is(":checked")){
                $(this).val('checked');
                $(".billing-detail").hide();
            } else{
                $(this).val('unchecked');
                $(".billing-detail").show();
            }
        });
    });
    if ($("#chkSameAsContact").is(":checked")){
            $(".billing-detail").hide();
        } else{
            $(".billing-detail").show();
    }
</script>