<?php
defined('C5_EXECUTE') or die("Access Denied.");
reset($folders);
$fPath = Config::get('mass_enrollment::custom.ImagesPath');
$folPath = Config::get('mass_enrollment::custom.FolderPath');
$selected_image = isset($data['card']) ? $data['card'] : (isset($images[0]['iID']) ? $images[0]['iID'] : false);
$selected_folder = isset($data['folder']) ? $data['folder'] : (is_array($folders) ? key($folders) : false);
$selected_image_path = '';
$ih = Core::make('helper/concrete/ui');

$image_json = array();
if (count($images)) {
    foreach($images as $image) {
        if ($image['iID']==$selected_image) {
            $selected_image_path = $RelativePath. $fPath . $image['image'];
        }
        $image_json[$image['iID']] = $RelativePath. $fPath . $image['image'];
    }
    $image_json = json_encode($image_json);
}
include_once 'partial/'. $format .'.php';


?>
<script>
    var images = <?=$image_json?>;
    var folders = <?=json_encode($folders)?>;
    var folder_path = '<?=$RelativePath. $folPath?>';

    function selectCard()
    {
        selected_image = $('[name="card"]:checked').val();
        $('.selected-img').attr('src', images[selected_image]);
        $('.card-img img').attr('src', images[selected_image]);
    }

    function changeFolder()
    {
        selected_folder = $('[name="folder"]:checked').val();
        $('.card-left-bkg img').attr('src', folder_path + folders[selected_folder]['folder_in_left']);
        $('.back img').attr('src', folder_path + folders[selected_folder]['folder_cover']);
        $('.img-inset img').attr('src', folder_path + folders[selected_folder]['folder_inset']);
        $('.card-right-bkg img').attr('src', folder_path + folders[selected_folder]['folder_in_right']);
    }

    function updateCard()
    {
        $('.card-date').html($('[name="e_date"]').val());
        $('.card-name-by').html($('[name="e_requested_by"]').val());
    }

    $(document).ready(function() {
        updateCard(); 
        
        $('[name="folder"]').change(function() {
            changeFolder();
        });

        $('[name="e_date"]').change(function() {
            updateCard();
        });

        $('[name="e_requested_by"]').change(function() {
            updateCard();
        });
    });
</script>