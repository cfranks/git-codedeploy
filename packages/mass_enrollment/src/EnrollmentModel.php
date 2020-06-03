<?php
namespace Concrete\Package\MassEnrollment\Src;

use Loader;

defined('C5_EXECUTE') or die(_("Access Denied."));

class EnrollmentModel extends BaseModel
{
  protected $_table = 'dat_enrollments';
    protected $_primary_key = 'cID';
    protected $fillable = array(
        'e_language',
        'e_occasion',
        'e_date',
        'e_enrollment_type',
        'e_individual_name',
        'e_family_name',
        'e_requested_by',
        'e_special_instructions',
        'dChkSendNotification',
        'e_notification_language',
        'e_title',
        'e_first_name',
        'e_last_name',
        'e_address',
        'e_address2',
        'e_city',
        'e_country',
        'e_state',
        'e_zip',
        'e_email',
        'e_support_donation',
        'c_title',
        'c_first_name',
        'c_last_name',
        'c_address',
        'c_address2',
        'c_city',
        'c_country',
        'c_state',
        'c_zip',
        'c_email',
        'c_home_phone',
        'c_cell_phone',
        'card',
        'folder',
        'authcode',
        'transactionid',
        'donor_id',
        'soft_donor_id',
        'i_number_masses',
        'i_living_deceased',
        'i_donation_amount',
        'p_address',
        'p_address2',
        'p_city',
        'p_country',
        'p_state',
        'p_zip',
        'p_email',
        'bID',
        'DateCreated',
        'DateModified',   
    );
    protected $date_fields = [
        'e_date'
    ];

    /**
     * 
     * Contructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setBaseQuery();
    }

    /**
     * 
     * Set base query
     */
    public function setBaseQuery()
    {
        $q = 'Select *  from ' . $this->_table;
        $this->setQuery($q);
    }

    public function generatePaymentReceipt($id)
    {
        $enrollment = $this->find($id);
        $html = "<div id='id-receipt' class='id-receipt' style='text-align:center'>";
        if (!$flag_mail) {
            $html .= "<div class='media-print-screen'>"
                . "<img src='/application/themes/divineword/img/logo.png' style='max-width: 100%'>"
                . "<hr/></div>";
        }
        $html .= "<h3>Thank You</h3>"
            . "Your appointment has been scheduled.";

        if (!empty($appointment['OrderConfirmationCode']) && isset($appointment['OrderConfirmationCode'])) {
            $html .= "<br><br>"
                . "<strong>Your Confirmation Code</strong><br>"
                . "<strong>" . strtoupper($appointment['OrderConfirmationCode']) . "</strong>";
        }
        if (!empty($appointment['TransactionID']) && isset($appointment['TransactionID'])) {
            $html .= "<br><br>"
                . "<strong>Your Transaction ID</strong><br>"
                . "<strong>" . $appointment['TransactionID'] . "</strong>";
        }
        if (!empty($appointment['ServicesID']) && isset($appointment['ServicesID'])) {
            $html .= "<br><br>"
                . "<strong>Service</strong><br>"
                . $services['sName'];
        }
        $total = $services['sPrice'];
        if (!empty($services['sPrice']) && isset($services['sPrice'])) {
            $html .= "<br><br>"
                . "<strong>Price</strong><br>"
                . "Surgery: $" . number_format($services['sPrice'], 2);
        }
        if (!empty($appointment['PromotionCode']) && isset($appointment['PromotionCode'])) {
            $html .= "<br><br>"
                . "<strong>Promotional Code</strong><br>"
                . $appointment['PromotionCode'] . " - $" . number_format($appointment['DiscountAmount'], 2);
            $total = $total - $appointment['DiscountAmount'];
        }
        if (!empty($appointment['PaymentMethod']) && isset($appointment['PaymentMethod'])) {
            $html .= "<br><br>"
                . "<strong>Payment Method</strong><br>"
                . $payment_method[$appointment['PaymentMethod']];
        }
        if (!empty($appointment['OrderTotal']) && isset($appointment['OrderTotal'])) {
            $html .= "<br><br>"
                . "<strong>Amount Paid</strong><br>"
                . "Non-refundable Deposit - $"
                . number_format($appointment['OrderTotal'], 2);
            $total = $total - $appointment['OrderTotal'];
        }

        $html .= "<br><br>"
            . "<strong>Amount Remaining</strong><br>"
            . "$" . number_format($total, 2);

        if (!empty($appointment['AppointmentDate']) && isset($appointment['AppointmentDate'])) {
            $html .= "<br><br>"
                . "<strong>Your Check-in Time</strong><br>"
                . date_format(date_create($appointment['AppointmentDate']), Config::get('scheduling_module::variables.DisplayDateFormat')) . " at " . $appointment['AppointmentTime'] . "<br>";
        }
        $html .= $appointment['Location'];
        $notes = \Config::get('scheduling_module::variables.appointment_notes');
        if (!empty($notes) && isset($notes)) {
            $html .= "<br><br>"
                . "<strong>Appointment Notes</strong><br>"
                . $notes. "<br/>"
		. 'Please fill out our <a target="_blank" href="' . BASE_URL . '/low-cost-vet-clinic/low-cost-spay-and-neuter">Spay and Neuter intake forms</a> and bring with you to your appointment<br/>'
		. "<em>Additional fees may apply</em>";
        }
        $html .= "<br><br></div>";

        return $html;
    }
}