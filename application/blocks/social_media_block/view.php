<?php  defined("C5_EXECUTE") or die("Access Denied."); ?>
<?php 
if (isset($AltText) && trim($AltText) != "") {
    $alt = h($AltText);
} else {
    $alt = "";
}


if (trim($SocialMedia) != "") { 

$iClass = "";
    
switch($SocialMedia) {
case "facebook":
    // ENTER MARKUP HERE FOR FIELD "Social Media" : CHOICE "Facebook"
    $iClass = "<i class=\"fa fa-facebook\"><span class=\"sr-only\">" . $Link_text . " icon</span></i>";
    break;
case "youtube":
    // ENTER MARKUP HERE FOR FIELD "Social Media" : CHOICE "Youtube"
    $iClass = "<i class=\"fa fa-youtube\"><span class=\"sr-only\">" . $Link_text . " icon</span></i>";
    break;
case "twitter":
    // ENTER MARKUP HERE FOR FIELD "Social Media" : CHOICE "Twitter"
    $iClass = "<i class=\"fa fa-twitter\"><span class=\"sr-only\">" . $Link_text . " icon</span></i>";
    break;
case "linkedin":
    // ENTER MARKUP HERE FOR FIELD "Social Media" : CHOICE "LinkedIn"
    $iClass = "<i class=\"fa fa-linkedin\"><span class=\"sr-only\">" . $Link_text . " icon</span></i>";
    break;
case "pinterest":
    // ENTER MARKUP HERE FOR FIELD "Social Media" : CHOICE "Pinterest"
    $iClass = "<i class=\"fa fa-pinterest\"><span class=\"sr-only\">" . $Link_text . " icon</span></i>";
    break;
case "googleplus":
    // ENTER MARKUP HERE FOR FIELD "Social Media" : CHOICE "Google Plus+"
    $iClass = "<i class=\"fa fa-google-plus\"><span class=\"sr-only\">" . $Link_text . " icon</span></i>";
    break;
case "tumblr":
    // ENTER MARKUP HERE FOR FIELD "Social Media" : CHOICE "Tumblr"
    $iClass = "<i class=\"fa fa-tumblr\"><span class=\"sr-only\">" . $Link_text . " icon</span></i>";
    break;
case "instagram":
    // ENTER MARKUP HERE FOR FIELD "Social Media" : CHOICE "Instagram"
    $iClass = "<i class=\"fa fa-instagram\"><span class=\"sr-only\">" . $Link_text . " icon</span></i>";
    break;
case "vk":
    // ENTER MARKUP HERE FOR FIELD "Social Media" : CHOICE "VK"
    $iClass = "<i class=\"fa fa-vk\"><span class=\"sr-only\">" . $Link_text . " icon</span></i>";
    break;
case "flickr":
    // ENTER MARKUP HERE FOR FIELD "Social Media" : CHOICE "Flickr"
    $iClass = "<i class=\"fa fa-flickr\"><span class=\"sr-only\">" . $Link_text . " icon</span></i>";
    break;
case "vine":
    // ENTER MARKUP HERE FOR FIELD "Social Media" : CHOICE "Vine"
    $iClass = "<i class=\"fa fa-vine\"><span class=\"sr-only\">" . $Link_text . " icon</span></i>";
    break;
case "meetup":
    // ENTER MARKUP HERE FOR FIELD "Social Media" : CHOICE "Meetup"
    $iClass = "<i class=\"fa fa-meetup\"><span class=\"sr-only\">" . $Link_text . " icon</span></i>";
    break;
                                }   } 
                                
if (trim($NewTab) != "") {
    $newTabValue = "";
    switch ($NewTab) {

        case "yes":
            // ENTER MARKUP HERE FOR FIELD "Open Link in New Tab?" : CHOICE "yes:: Yes"
            $newTabValue = " target=\"_blank\"";
            break;
        case "no":
            // ENTER MARKUP HERE FOR FIELD "Open Link in New Tab?" : CHOICE "no::No"
            $newTabValue = "";
            break;
    }
}                                
?>
<?php  if (isset($Link) && trim($Link) != "") { ?>
   <?php  echo "<a" . $newTabValue . " href=\"" . $Link . "\" title=\"" . $Link_text . "\" alt=\"" . $Link_text . " icon\">" . $iClass . "</a>"; ?><?php  } ?>

