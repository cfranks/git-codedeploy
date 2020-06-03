<?php  namespace Application\Block\SocialMediaBlock;

defined("C5_EXECUTE") or die("Access Denied.");

use Concrete\Core\Block\BlockController;
use Core;

class Controller extends BlockController
{
    public $helpers = array('form');
    public $btFieldsRequired = array();
    protected $btExportFileColumns = array();
    protected $btTable = 'btSocialMediaBlock';
    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 500;
    protected $btIgnorePageThemeGridFrameworkContainer = false;
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;
    protected $btCacheBlockOutputLifetime = 0;
    protected $pkg = false;
    
    public function getBlockTypeDescription()
    {
        return t("");
    }

    public function getBlockTypeName()
    {
        return t("Social Media Block");
    }

    public function view()
    {
        $SocialMedia_options = array(
            '' => "-- " . t("None") . " --",
            'facebook' => "Facebook",
            'youtube' => "Youtube",
            'twitter' => "Twitter",
            'linkedin' => "LinkedIn",
            'pinterest' => "Pinterest",
            'googleplus' => "Google Plus+",
            'tumblr' => "Tumblr",
            'instagram' => "Instagram",
            'vk' => "VK",
            'flickr' => "Flickr",
            'vine' => "Vine",
            'meetup' => "Meetup"
        );
        $this->set("SocialMedia_options", $SocialMedia_options);
        $NewTab_options = array(
            '' => "-- " . t("None") . " --",
            'yes' => "Yes",
            'no' => "No"
        );
        $this->set("NewTab_options", $NewTab_options);
    }

    public function add()
    {
        $this->addEdit();
    }

    public function edit()
    {
        $this->addEdit();
    }

    protected function addEdit()
    {
        $this->set("SocialMedia_options", array(
                '' => "-- " . t("None") . " --",
                'facebook' => "Facebook",
                'youtube' => "Youtube",
                'twitter' => "Twitter",
                'linkedin' => "LinkedIn",
                'pinterest' => "Pinterest",
                'googleplus' => "Google Plus+",
                'tumblr' => "Tumblr",
                'instagram' => "Instagram",
                'vk' => "VK",
                'flickr' => "Flickr",
                'vine' => "Vine",
                'meetup' => "Meetup"
            )
        );
        $this->set("NewTab_options", array(
                '' => "-- " . t("None") . " --",
                'yes' => "Yes",
                'no' => "No"
            )
        );
        $this->set('btFieldsRequired', $this->btFieldsRequired);
    }

    public function validate($args)
    {
        $e = Core::make("helper/validation/error");
        if ((in_array("SocialMedia", $this->btFieldsRequired) && (!isset($args["SocialMedia"]) || trim($args["SocialMedia"]) == "")) || (isset($args["SocialMedia"]) && trim($args["SocialMedia"]) != "" && !in_array($args["SocialMedia"], array("facebook", "youtube", "twitter", "linkedin", "pinterest", "googleplus", "tumblr", "instagram", "vk", "flickr", "vine", "meetup")))) {
            $e->add(t("The %s field has an invalid value.", t("Social Media")));
        }
        if (((!in_array("Link", $this->btFieldsRequired) && trim($args["Link"]) != "") || (in_array("Link", $this->btFieldsRequired))) && !filter_var($args["Link"], FILTER_VALIDATE_URL)) {
            $e->add(t("The %s field does not have a valid URL.", t("Link")));
        }
        if ((in_array("NewTab", $this->btFieldsRequired) && (!isset($args["NewTab"]) || trim($args["NewTab"]) == "")) || (isset($args["NewTab"]) && trim($args["NewTab"]) != "" && !in_array($args["NewTab"], array("yes", "no")))) {
            $e->add(t("The %s field has an invalid value.", t("Open Link in New Tab?")));
        }
        return $e;
    }

    public function composer()
    {
        $this->edit();
    }
}