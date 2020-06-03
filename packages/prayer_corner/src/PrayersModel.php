<?php
namespace PrayerCorner;

use Loader;

defined('C5_EXECUTE') or die(_("Access Denied."));

class PrayersModel extends BaseModel
{
	protected $_table = 'dat_prayers';
    protected $_primary_key = 'pID';
    protected $fillable = array(
        'first_name',
        'last_name',
        'email',
        'city',
        'country',
        'prayer_request',
        'post_public',
        'email_consent',
        'language',
        'date_created',
        'date_modified',
        'status'
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