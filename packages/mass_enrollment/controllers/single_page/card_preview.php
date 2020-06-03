<?php
namespace Concrete\Package\MassEnrollment\Controller\SinglePage;

use Config;
use Database;
use Package;
use \Concrete\Core\Page\Controller\PageController;
use Concrete\Package\MassEnrollment\Src\EnrollmentModel;

defined('C5_EXECUTE') or die("Access Denied.");

/**
 * Class for the provider portal
 */
class CardPreview extends PageController
{
    protected $helpers = ['form'];
    /**
     * Function to set the variables for view.
     * 
     * @param void
     * @author AN 05/16/2016
     */
    public function view($id = '')
    {
        if (empty($id)) {
            $this->redirect('/');
        } else {
            $this->setupForm();
            $id = head(explode("-", $id));
            $model = new EnrollmentModel();
            $data = $model->find($id);
            $block = $this->getBlock($data['bID']);
	    $this->set('bLanguage',$block['bLanguage']);
            if (!in_array($block['bFormType'], [1,2,3])) {
                $this->redirect('/');
            }
            $this->set('data', $data);
            $this->set('format', 'payment');
        }
    }
    
    public function getBlock($id)
    {
        $db = Database::connection();
        if ($id) {
            return $db->GetRow("SELECT * FROM btMassEnrollment where bID=$id");
        } else {
            return 0;
        }
    }

    /**
     * Function to set up variables
     * 
     * @return type
     * @author JS 09/24/2019
     */
    public function setupForm() 
    {
        $package = Package::getByHandle('mass_enrollment');
        $languages = Config::get('mass_enrollment::cardlanguages');
        $this->set('languages', $languages);
        $this->set('images', $this->getImages());
        $this->set('folders', $this->getfolder());
        $this->set('RelativePath', $package->getRelativePath());
        $occasions = Config::get('mass_enrollment::custom.occasion');
        $this->set('occasions', $occasions);
    }

    /**
     * Function to get the images.
     * 
     * @return files
     * @author SR 09/26/2019
     */
    public function getImages() 
    {
        $db = Database::connection();
        return $db->GetAll("SELECT * FROM ctr_card_images");
    }

    /**
     * Function to get the images.
     * 
     * @return files
     * @author SR 09/26/2019
     */
    public function getfolder() 
    {
        $folder_final = array();
        $db = Database::connection();
        $folder = $db->GetAll("SELECT * FROM ctr_folder");
        foreach ($folder as $fol) {
            $folder_final[$fol['fID']] = $fol;
        }
        return $folder_final;
    }
}
