<style type="text/css">
	.no-prev{
	     display: none !important;
	}
</style>
<?php
defined('C5_EXECUTE') or die("Access Denied.");
if (isset($folders)) {
    reset($folders);
    $fPath = Config::get('mass_enrollment::custom.ImagesPath');
    $folPath = Config::get('mass_enrollment::custom.FolderPath');
    $selected_image = isset($data['card']) ? $data['card'] : (isset($images[0]['iID']) ? $images[0]['iID'] : false);
    $selected_folder = isset($data['folder']) ? $data['folder'] : (is_array($folders) ? key($folders) : false);
    $selected_image_path = '';
    $image_json = array();
    $img_living = $RelativePath. $fPath . '518_2.png';
    $img_deceased = $RelativePath. $fPath . '518_2.png';
    if (count($images)) {
        foreach($images as $image) {
            if ($image['iID']==$selected_image) {
                $selected_image_path = $RelativePath. $fPath . $image['image'];
            }
            $image_json[$image['iID']] = $RelativePath. $fPath . $image['image'];
        }
        $image_json = json_encode($image_json);
    }
}
$ih = Core::make('helper/concrete/ui');
// include_once 'partial/'. $format .'.php';
?>
<div class="col-lg-8 col-lg-push-2">
<form class="divine-form" method="POST" style="background: 0" action="<?php echo $this->action('submit_enrollment') ?>">
    <?php include_once DIR_PACKAGES . '/mass_enrollment/blocks/mass_enrollment_block/partial/card.php'; ?>
    <?php include_once DIR_PACKAGES . '/mass_enrollment/blocks/mass_enrollment_block/partial/folder.php'; ?>
</form>
</div>
<div class="clearfix"></div>
<script>
    var images = <?=$image_json?>;
    var folders = <?=json_encode($folders)?>;
    var folder_path = '<?=$RelativePath. $folPath?>';
    var card_language = <?=json_encode(Config::get('mass_enrollment::card'))?>;
    var date_default = '<?=date("n/j/Y")?>';
    var date_formats = <?=json_encode(Config::get('mass_enrollment::carddateformat'))?>;
    
    $(document).ready(function() {
        chnFamIndi();
        updateCard(); 
        changeCardLangugage();
        chkDeliverlyDetails();
        typeCounter('#e_requested_by', '#countTxtRequestedBy');
        typeCounter('#e_individual_name', '#countdIndividualName');
        typeCounter('#e_family_name', '#countdFamilyName');
        typeCounter('#e_intention', '#countdIntention');
    });

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
        if ($('[name="e_date"]').length && $('[name="e_language"]').length) {
            var date = $('[name="e_date"]').val();
            if (!date) {
                date = date_default;
            }
            date = date.split("/");
            var month = date[0];
            var day = date[1];
            var year = date[2];
            var langcard = $('[name="e_language"]').val();
            var format = date_formats[langcard];
            format = format.replace(/M/g, month)
            format = format.replace(/D/g, day)
            format = format.replace(/Y/g, year)
            $('.card-date').html(format);
            $('.card-name-by').html($('[name="e_requested_by"]').val());
            var name = '';
            if ($('[name="e_enrollment_type"]:checked').val()=='individual') {
                name = $('[name="e_individual_name"]').val();
            } else {
                name = $('[name="e_family_name"]').val();
            }
            $('.card-name-for span').html(name);
    }
    }

    function changeCardLangugage()
    {
        var langcard = $('[name="e_language"]').val();
        var newlang = card_language[langcard];
        var data = '';
        $('.chn-lng').each(function() {
            data = newlang[$(this).data('key')];
            if(data!='') {
                $(this).html(data);
            } else {
                $(this).html(card_language['en'][$(this).data('key')]);
            }
        });
        updateCard(); 
    }

    $("#dChkSendNotification").click(function(){
        chkDeliverlyDetails();
    });

    function chkDeliverlyDetails() {
        //checks if the send notification checkbox is checked on page load.
        if($("#dChkSendNotification").is(":checked")){
            $("#delivery_details").show();
        } else {
            $("#delivery_details").hide();
        }
    }
    
    function typeCounter(source, target)
    {
        $(source).on('input',function(e){
            if (this.value.length > 120) {
                this.value = this.value.substring(0, 120);
            }
            $(target).text(this.value.length);
        });
    }
    
    function chnFamIndi()
    {
        var selected = $('[name="e_enrollment_type"]:checked').val();
        if (selected=='individual') {
            $("#enroll_individual").show()
            $("#enroll_family").hide()
        } else if (selected=='family') {
            $("#enroll_individual").hide()
            $("#enroll_family").show()
        } else {
            $("#enroll_individual").hide()
            $("#enroll_family").hide()
        }
    } 
</script>
<style>
h3 {
    margin-top: 0;
    padding-top: 0;
}
</style>