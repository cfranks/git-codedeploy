<form id="payment_form" class="divine-form" method="POST" action="<?php echo $this->action('submit_payment') ?>">
    <?php include_once 'steps.php'; ?>
   
    <div>
        <h3><?php echo translate('OrderSummaryLabel', $bLanguage, false) ?></h3>
        <?php if (in_array($bFormType, [1,2,3])) { ?>
            <?php include_once 'card.php'; ?>
            <?php include_once 'folder.php'; ?>
        <?php } ?>
        <div class="clearfix"></div>
        <div class='row'>
            <div class='col-sm-12'>
                <a class="accordion"><?php echo translate('DonationDetailsTab', $bLanguage, false) ?></a>
                <div class="panel">
                    <?php echo showDonationDetail($bFormType, $bLanguage, $data); ?>
                    <p></p>
                </div>

                <a class="accordion"><?php echo translate('ContactDetailsTab', $bLanguage, false) ?></a>
                <div class="panel">
                    <?php if (isset($data['c_title']) && $data['c_title'] != '') { ?> 
                        <div class="row">
                            <div class="col-sm-4">
                                <strong><?php translate('TitleLabel', $bLanguage); ?></strong>
                            </div>
                            <div class="col-sm-8">
                                <?php echo $data['c_title']; ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (isset($data['c_first_name']) && $data['c_first_name'] != '') { ?> 
                        <div class="row">
                            <div class="col-sm-4">
                                <strong><?php translate('FirstNameLabel', $bLanguage); ?></strong>
                            </div>
                            <div class="col-sm-8">
                                <?php echo $data['c_first_name']; ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (isset($data['c_last_name']) && $data['c_last_name'] != '') { ?> 
                        <div class="row">
                            <div class="col-sm-4">
                                <strong><?php translate('LastNameLabel', $bLanguage); ?></strong>
                            </div>
                            <div class="col-sm-8">
                                <?php echo $data['c_last_name']; ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (isset($data['c_address']) && $data['c_address'] != '') { ?> 
                        <div class="row">
                            <div class="col-sm-4">
                                <strong><?php translate('AddressLabel', $bLanguage); ?></strong>
                            </div>
                            <div class="col-sm-8">
                                <?php echo $data['c_address']; ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (isset($data['c_address2']) && $data['c_address2'] != '') { ?> 
                        <div class="row">
                            <div class="col-sm-4">
                                <strong><?php translate('Address2Label', $bLanguage); ?></strong>
                            </div>
                            <div class="col-sm-8">
                                <?php echo $data['c_address2']; ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (isset($data['c_city']) && $data['c_city'] != '') { ?> 
                        <div class="row">
                            <div class="col-sm-4">
                                <strong><?php translate('CityLabel', $bLanguage); ?></strong>
                            </div>
                            <div class="col-sm-8">
                                <?php echo $data['c_city']; ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (isset($data['c_state']) && $data['c_state'] != '') { ?> 
                        <div class="row">
                            <div class="col-sm-4">
                                <strong><?php translate('StateProvinceLabel', $bLanguage); ?></strong>
                            </div>
                            <div class="col-sm-8">
                                <?php echo isset($states[$data['c_state']]) ? t($states[$data['c_state']]) : $data['c_state']; ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (isset($data['c_country']) && $data['c_country'] != '') { ?> 
                        <div class="row">
                            <div class="col-sm-4">
                                <strong><?php translate('CountryLabel', $bLanguage); ?></strong>
                            </div>
                            <div class="col-sm-8">
                                <?php echo (isset($countries[$data['c_country']]) ? t($countries[$data['c_country']]) : $data['c_country']); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (isset($data['c_zip']) && $data['c_zip'] != '') { ?> 
                        <div class="row">
                            <div class="col-sm-4">
                                <strong><?php translate('ZipPostalLabel', $bLanguage); ?></strong>
                            </div>
                            <div class="col-sm-8">
                                <?php echo $data['c_zip']; ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (isset($data['c_email']) && $data['c_email'] != '') { ?> 
                        <div class="row">
                            <div class="col-sm-4">
                                <strong><?php translate('EmailLabel', $bLanguage); ?></strong>
                            </div>
                            <div class="col-sm-8">
                                <?php echo $data['c_email']; ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (isset($data['c_home_phone']) && $data['c_home_phone'] != '') { ?> 
                        <div class="row">
                            <div class="col-sm-4">
                                <strong><?php translate('HomePhoneLabel', $bLanguage); ?></strong>
                            </div>
                            <div class="col-sm-8">
                                <?php echo $data['c_home_phone']; ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (isset($data['c_cell_phone']) && $data['c_cell_phone'] != '') { ?> 
                        <div class="row">
                            <div class="col-sm-4">
                                <strong><?php translate('CellPhoneLabel', $bLanguage); ?></strong>
                            </div>
                            <div class="col-sm-8">
                                <?php echo $data['c_cell_phone']; ?>
                            </div>
                        </div>
                    <?php } ?>
                    <p></p>
                </div>
            </div>
            <div class="col-sm-12">
                <h4><?php translate('PaymentDetailsTab', $bLanguage); ?></h4>
                <table class="table table-bordered">
                    <tr>
                        <th><?php translate('DonationAmountLabel', $bLanguage) ?></th>
                        <td><?php $main = $controller->getCurrentPrice($data);
                            echo '$' .number_format($main, 2); ?> </td>
                    </tr>
                    <?php if ($ship = $controller->calculateShippingPrice($data)) {?>
                    <tr>
                        <th><?php translate('InternationalShippingLabel', $bLanguage) ?></th>
                        <td><?php echo '$' .number_format($ship, 2); ?> </td>
                    </tr>
                    <?php } ?>
                    <tr class="success">
                        <th><?php translate('TotalPayment', $bLanguage) ?></th>
                        <td><?php echo '$' .number_format($main + $ship, 2); ?> </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <?php echo $form->checkbox("chkSameAsContact", 'checked', false); ?>
                <?php echo $form->label('chkSameAsContact', translate('SameAsContactDetail', $bLanguage, false)); ?>
                <br>
            </div>
        </div>
    </div>
    <div class="row billing-detail">
        <div class="col-sm-12">
            <h3><?php translate('BillingDetailsLabel', $bLanguage) ?></h3> 
        </div> 
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo $form->label('p_address', translate('AddressLabel', $bLanguage, false), ['class' => 'required']); ?>
                        <?php echo $form->text('p_address', $data['p_address'], ['maxlength' => 100, 'placeholder' => translate('AddressLabel', $bLanguage, false)]); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php echo $form->label('p_address2', translate('Address2Label', $bLanguage, false)); ?> 
                        <?php echo $form->text('p_address2', $data['p_address2'], ['maxlength' => 100, 'placeholder' => translate('Address2Label', $bLanguage, false)]); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label('p_city', translate('CityLabel', $bLanguage, false), ['class' => 'required']); ?>
                        <?php echo $form->text('p_city', $data['p_city'], ['maxlength' => 50, 'placeholder' => translate('CityLabel', $bLanguage, false)]); ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label('p_country', translate('CountryLabel', $bLanguage, false), ['class' => 'required']); ?>
                        <?php echo $form->select('p_country', ['' => translate('CountryLabel', $bLanguage, false)] + $countries, ($data['country'] ? $data['country'] : 'US') , ['class' => 'country']); ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label('p_stateDropdown', translate('StateProvinceLabel', $bLanguage, false), ['class' => 'required stateDropdown']); ?>
                        <?php echo $form->select('p_stateDropdown', ['' => translate('StateProvinceLabel', $bLanguage, false)] + $states, ['class' => 'stateDropdown'], $data['p_state']); ?>
                        <?php echo $form->label('p_stateText', translate('Address3Label', $bLanguage, false), ['class' => 'stateText']); ?>
                        <?php echo $form->text('p_stateText', $data['p_state'], ['maxlength' => 45, 'placeholder' => translate('Address3Label', $bLanguage, false), 'class' => 'stateText']); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <?php echo $form->label('p_zip', translate('ZipPostalPlaceholder', $bLanguage, false), ['class' => 'required']); ?>
                        <?php echo $form->text('p_zip', $data['p_zip'], ['maxlength' => 20, 'placeholder' => translate('ZipPostalPlaceholder', $bLanguage, false)]); ?>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="form-group">
                        <?php echo $form->label('p_email', translate('EmailLabel', $bLanguage, false)); ?>
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
    <div class="form-action"> 
    <div class="pull-left">
            <a href="<?php echo $this->action('contact'); ?>" class="btn btn-default">
                <?php translate('BackLabel', $bLanguage) ?>
            </a>            
            <a href="<?php echo $this->action('cancel'); ?>" onclick="return confirm('<?php translate('ConfirmCancel', $bLanguage) ?>')" class="btn btn-danger">
                <?php translate('CancelLabel', $bLanguage) ?>
            </a>
        </div> 
        <button id="payment-submitButton" type="submit" class="btn-theme step-3-submit customPayButton">
            <?php echo translate('SubmitLabel', $bLanguage, false); ?>
        </button>
        <div class="clearfix"></div>
    </div>
    <p class="multiple-click-message"><?php echo translate('DoubleClickMessage', $bLanguage, false); ?></p>
</form>
<div id="loading" style="display: none;">
    <div class="loader-text">
    	<div class="loader">
	</div>
	Submitting Payment...<br/>
	Please wait.
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#chkSameAsContact').change(function () {
            if ($(this).is(":checked")) {
                $(this).val('checked');
                $(".billing-detail").hide();
            } else {
                $(this).val('unchecked');
                $(".billing-detail").show();
            }
        });
    });
    if ($("#chkSameAsContact").is(":checked")) {
        $(".billing-detail").hide();
    } else {
        $(".billing-detail").show();
    }
    var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    }
  });
}
</script>
<script src="https://secure.safesavegateway.com/token/Collect.js"
            data-tokenization-key="<?php echo !empty(Config::get('mass_enrollment::integration.paymentTokenizationKey')) ? Config::get('mass_enrollment::integration.paymentTokenizationKey') : 'v6836S-ZUA6Z3-Cy787A-Y8MZSP'; ?>"></script>
	    <script>
            document.addEventListener('DOMContentLoaded', function () {
                CollectJS.configure({
                    'paymentSelector' : '.customPayButton',
                    'theme': 'bootstrap',
                    'primaryColor': '#4bca66',
                    'secondaryColor': '#ffe200',
                    'buttonText': '<?php echo translate('paymentButtonText', $bLanguage, false); ?>',
                    'instructionText': '<?php echo translate('paymentInstructionText', $bLanguage, false); ?>',
                    'paymentType': 'cc',
                    'callback' : function(response) {
                        displayLoader();
			 var input = document.createElement("input");
                        input.type = "hidden";
                        input.name = "payment_token";
                        input.value = response.token;
			var form = document.getElementById("payment_form");
                        form.appendChild(input);
                        form.submit();
                    }
                });
            });
        </script>
<style>
/* Style the buttons that are used to open and close the accordion panel */
.accordion {
  background-color: #eee;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  text-align: left;
  border: none;
  outline: none;
  transition: 0.4s;
  display: block;
}
.multiple-click-message {
    color: red;
    font-size: 17px;
    text-align: center;
    font-weight: 600;
}

/* Add a background color to the button if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */
.active, .accordion:hover {
  background-color: #ccc;
}

/* Style the accordion panel. Note: hidden by default */

.panel {
  padding: 0 18px;
  background-color: white;
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.2s ease-out;
  clear: both;
  margin-bottom: 5px;
}
.accordion:after {
  content: '\02795'; /* Unicode character for "plus" sign (+) */
  font-size: 13px;
  color: #777;
  float: right;
  margin-left: 5px;
}

.accordion.active:after {
  content: "\2796"; /* Unicode character for "minus" sign (-) */
}
</style>