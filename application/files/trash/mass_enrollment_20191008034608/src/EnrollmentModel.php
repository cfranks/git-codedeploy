<?php
namespace Concrete\Package\MassEnrollment\Src;

use Loader;

defined('C5_EXECUTE') or die(_("Access Denied."));

class EnrollmentModel extends BaseModel
{
  protected $_table = 'dat_enrollments';
    protected $_primary_key = 'eID';
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
        'p_card_holder_name',
        'p_card_type',
        'p_card_number',
        'p_exp_month',
        'p_exp_year',
        'p_cvv',
        'p_address',
        'p_address2',
        'p_city',
        'p_country',
        'p_state',
        'p_zip',
        'p_email',
        'DateCreated',
        'DateModified',

   
    );

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
}