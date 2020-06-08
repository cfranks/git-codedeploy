<?php  
defined('C5_EXECUTE') or die("Access Denied.");
?>
<h2><?php   echo t('Latest Comments')?></h2>
<?php      
if(is_array($entries)){
    $blogify = Loader::helper('blogify');
    foreach($entries as $entry){
        $uID = $entry['uID'];
        $cID = $entry['cID'];
        $page = Page::getByID($cID);
        $commentText = $entry['cnvMessageBody'];
        $link = Loader::helper('navigation')->getLinkToCollection($page).'#cnv'.$entry['cnvID'].'Message'.$entry['cnvMessageID'];
        if($uID){
            Loader::model('userinfo');
            $ui = UserInfo::getByID($uID);
            $avatar = $blogify->getPosterAvatar($uID,32);
            echo '<p><div style="float: left; margin-right: 8px;">'.$avatar.'</div><a href="'. URL::to('/members/profile') . '/' . $uID . '/">'.$ui->getUserName().': </a> '.$commentText.'</p>';
        }else{
            echo '<p><div style="float: left; margin-right: 8px;" class="guest_avatar"></div>'.t('Guest').': '.$commentText.'</p>';
        }
        echo '<a href="'.$link.'">'.$link.'</a>';
    }

}else{
    echo $entries;
}
?>