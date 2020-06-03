<?php if ($bFormType != 4) { ?>
    <?php if ($bFormType != 3) { ?>
        <h3>
            <span class="no-prev"><?php echo translate('FolderDetailsLabel', $bLanguage,false);?></span>
        <a id="card-flip">
            <i class="fa fa-repeat" aria-hidden="true"></i> <?php echo translate('FlipCard',$bLanguage);?>
        </a>
        </h3>
        
        <div class="card-preview" id="card-preview">
            <div class="col flip-container">
                <div class="card-flip">
                    <div class="front">
                        <figure class="card-left-bkg">
                            <img src="<?=$RelativePath. $folPath . $folders[$selected_folder]['folder_in_left']?>" alt="Folder Image">
                        </figure>
                        <figure class="card-img">
                            <img src="<?=$selected_image_path?>" alt="Folder Image">
                        </figure>
                    </div>
                    <div class="back">
                        <img src="<?=$RelativePath. $folPath . $folders[$selected_folder]['folder_cover']?>" alt="Folder Image">
                    </div>
                </div>
                <div class="img-inset">
                    <img src="<?=$RelativePath. $folPath . $folders[$selected_folder]['folder_inset']?>" alt="Folder Image">
                </div>
            </div>
            <div class="col">
                <figure class="card-right-bkg">
                    <img src="<?=$RelativePath. $folPath . $folders[$selected_folder]['folder_in_right']?>" alt="Folder Image">
                </figure>
                <div id="card-text">
                    <div class="card-title">
                        <h3 class="chn-lng" data-key="MissionMassLeague"><?php translatecard('MissionMassLeague', $bLanguage) ?></h3>
                        <p class="chn-lng" data-key="MissionariesDivineWord"><?php translatecard('MissionariesDivineWord', $bLanguage) ?></p>
                    </div>
                    <div class="card-name-for">
                        <span></span>
                    </div>
                    <div class="card-content">
                        <p class="chn-lng" data-key="PrayerMessage"><?php translatecard('PrayerMessage', $bLanguage) ?></p>
                        <p><span class="chn-lng" data-key="WithPrayer"><?php translatecard('WithPrayer', $bLanguage) ?></span> <span class="card-name-by"></span></p>
                        <p class="card-date"></p>
                    </div>
                </div>
            </div>
        </div>
        <p class="small"><span class="no-prev"><?php translate('ActualSize', $bLanguage) ?></span></p>
        <?php } else { ?>
        <h3>
            <span class="no-prev"><?php echo translate('FolderDetailsLabel', $bLanguage, false) ?></span>
        <a id="card-flip">
            <i class="fa fa-repeat" aria-hidden="true"></i> <?php translate('FlipCard',$bLanguage);?>
        </a>
        </h3>
        
        <div class="card-preview ack-form" id="card-preview">       
          <div class="col flip-container">
            <div class="card-flip">
                <div class="front">
                    <figure class="card-left-bkg">
                      <img src="<?=$ack_card_left?>" alt="Folder Image"/>
                    </figure>
                    <div class="text">
                      <p class="chn-lng" data-key="ForGodLove"><?php translatecard('ForGodLove', $bLanguage) ?></p>
                      <p class="chn-lng" data-key="John"><?php translatecard('John', $bLanguage) ?></p>
                    </div>
                    
                </div>
                <div class="back">
                <img class="selected-ack-img" src="<?php echo $selected_ack_image; ?>" alt="Selected Image"/>
                </div>
            </div>
            <div class="img-inset">
              <img class="inset-ack" src="<?=$ack_card_inset?>" alt="Folder Image"/>
            </div>
          </div>
          
          <div class="col">
            <figure class="card-right-bkg">
              <img src="<?=$ack_card_right ?>" alt="Folder Image"/>
            </figure>
            <div id="card-text">
              <div class="card-title">
                <h3 class="chn-lng" data-key="MissionMassLeague"><?php translatecard('MissionMassLeague', $bLanguage) ?></h3>
                <p class="chn-lng" data-key="MissionariesDivineWord"><?php translatecard('MissionariesDivineWord', $bLanguage) ?></p>
              </div>
              <div class="card-name-for">
                        <span></span>
              </div>
                <div class="card-content">
                    <p class="chn-lng" data-key="PrayerMessage"><?php translatecard('PrayerMessage', $bLanguage) ?></p>
                    <p><span class="chn-lng" data-key="WithPrayer"><?php translatecard('WithPrayer', $bLanguage) ?></span> <span class="card-name-by"></span></p>
                    <p class="card-date"></p>
                </div>
            </div>
          </div>        
        </div>
	<?php if($bFormType == 3) { ?>
	<p class="small"><span class="no-prev"><?php translate('AckFormNoteForCardOption', $bLanguage) ?></span></p>
	<?php } else { ?>
        <p class="small"><span class="no-prev"><?php translate('ActualSize', $bLanguage) ?></span></p>
	<?php } ?>
    <?php } ?>
<?php } ?>