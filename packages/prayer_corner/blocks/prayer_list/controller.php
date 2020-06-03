<?php

/** Include Block Namespaces * */

namespace Concrete\Package\PrayerCorner\Block\PrayerList;

/** Use Blocks and Package * */
use Concrete\Core\Block\BlockController;
use Config;
use Package;
use Loader;
use Core;
use PrayerCorner\PrayersModel;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends BlockController
{
    /**
     * Protected variables
     * 
     * @btTable defines the name of the block table
     * @btInterfaceWidth Defines the width of add/edit block interface 
     * @btInterfaceHeight Defines the width of add/edit block interface 
     */
    protected $btTable = 'btPrayerCorner';
    protected $btInterfaceWidth = "375";
    protected $btInterfaceHeight = "450";

    /**
     * Function to return Block Description
     * 
     * @return string
     */
    public function getBlockTypeDescription()
    {
        return t("Prayer Corner List Block");
    }

    /**
     * Function to return Block Type Name
     * 
     * @return string
     */
    public function getBlockTypeName()
    {
        return t("Prayer Corner List");
    }

    /**
     * Function to set up variables
     * 
     * @return type
     * @author SR 09/25/2019
     */
    public function setupForm()
    {
        $country = Core::make('helper/lists/countries');
        $countries = $country->getCountries();
        $this->set('countries', $countries);
        $model = new PrayersModel();
        $model->filter('status', 1);
        $model->filter('post_public', 1);
        $model->sortBy('date_created', 'desc');
        $data = $model->get($bTotal);
        $this->set('data', $data);
    }

    /**
     * Function to process the view and display the view
     * 
     * @return View Resposne
     * @author JS 09/24/2019
     */
    public function view()
    {
        //Setup form
        $this->setupForm();
        $this->set('format','listing');
    }

    public function add()
    {   
        $this->setupForm();
    }

    public function edit()
    {
        $this->setupForm();
    }

    public function save($args)
    {
        parent::save($args);
    }

}
