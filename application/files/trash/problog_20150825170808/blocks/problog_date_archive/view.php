<?php    
defined('C5_EXECUTE') or die("Access Denied.");  
?>
<div class="grid_4 alpha omega" id="sidebar-archives">
    <h2><?php   echo $title ?></h2>
    <?php        
    $dh = Loader::helper('date');

    if($firstPost){
        $startDt = new \DateTime();
    
        $firstPost = new \DateTime($firstPost->format('m/d/Y'));
        
        $first = date_diff($startDt,$firstPost);
    
        $first = ($first->y * 12) + ($first->m + 1);
    
        $numMonths = intval($numMonths);
    
        if($first > $numMonths) {
            $first = $numMonths;
        }   
        
        $startDt->modify('-'.$first.' months');
        $workingDt = $startDt;
        $year = $workingDt->format('Y');
        ?>
        <h4><?php   echo $year;?></h4>
        <ul class="archived_list" <?php    if($year < date('Y')){echo 'style="display: none;"';}?>>
            <?php         
            for($i=0;$i <= $numMonths;$i++) {
                $thisdate = $workingDt->format('Y').'-'.$workingDt->format('m');
                if($controller->check_date($thisdate)){ 
                    if($workingDt->format('Y') > $year) {
                        $year = $workingDt->format('Y');
                        ?></ul><h4><?php        echo $year?></h4><ul class="archived_list" <?php        if($year < date('Y')){echo 'style="display: none;"';}?>><?php        
                    }
            
                    ?>
                    <li>
                            <a href="<?php   echo $navigation->getLinkToCollection($target)."?year=".$workingDt->format('Y'). "&month=".$workingDt->format('m') ?>" <?php   echo ($workingDt->format('m-Y') == $_REQUEST['month']?'class="selected"':'')?>><?php   echo $dh->date('M', $workingDt->getTimestamp() ); ?></a>
                    </li>
                    <?php        
                }
                $workingDt->modify('+1 month');
            } 
            ?>
        </ul>
    <?php   } ?>
</div>