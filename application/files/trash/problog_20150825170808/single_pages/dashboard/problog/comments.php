<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
?>
<style>
    div#ccm-dashboard-content header{padding: 1px 80px 14px 55px;}
    tr.message-Removed td{background-color: #ffd1d0!important;}
</style>

<?php  echo  Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t(' Blog Comments'),false,'span10 offset1',false) ?>

<div class="ccm-dashboard-content-full">
    <form method="get" action="<?php        echo $this->action('view')?>" id="blog_search">
        <?php  
        $sections[0] = '** All';
        asort($sections);
        ?>
        <table class="table" >
            <tr>
                <th><strong><?php   echo t('Filter');?></strong></th>
            </tr>
            <tr>
                <td>
                    <div class="col-md-6">
                        <select name="comment_todo" class="form-control">
                            <option value=""><?php      echo t('All')?></option>
                            <option value="approves" <?php   if($comment_todo=='approves'){echo 'selected';}?>><?php      echo t('Approved')?></option>
                            <option value="unapproves" <?php   if($comment_todo=='unapproves'){echo 'selected';}?>><?php      echo t('Unapproved')?></option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="submit" name="do" value="<?php      echo t('submit')?>" class="btn"/>
                    </div>
                </td>
            </tr>
        </table>
    </form>
    <br/>
    <form>
    <table border="0" class="ccm-search-results-table" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th class=""><?php   echo t('Status')?></th>
                <th class=""><?php   echo t('User Info')?></th>
                <th class=""><?php   echo t('Date')?></th>
                <th class=""><?php   echo t('Comment')?></th>
            </tr>
        </thead>
        <tbody>
        <?php  
        //count the number of current posts returned
            $num = 15;  
            $pcount = count($comments);
            
        //now calc the last page    
            $lastpage = ceil($pcount/$num);
            
        
        //set the current page min max keys -1 as array key's start @ 0
            $sKey = $num * ($pageno-1) ;
            $eKey = ($num * ($pageno-1)) + ($num-1) ;
            
            foreach($comments as $key => $comment){
                $ui = UserInfo::getByID($comment['uID']);
                $page = Page::getByID($comment['cID']);
                $status = $comment['cnvIsMessageApproved'] ? 'Approved' : 'Removed';
                $delete = '<a href="'.$this->action('delete',$comment['cnvMessageID']).'" title="'.t('Permanently Remove').'"><i class="fa fa-trash"> </i></a>';
                //$spam = '<a href="'.$this->action('flag',$comment['cnvMessageID']).'" title="'.t('Flag As Spam').'"><i class="fa fa-flag"> </i></a>';
                $reply = '<a href="'.Loader::helper('navigation')->getLinkToCollection($page).'#cnv'.$comment['cnvID'].'Message'.$comment['cnvMessageID'].'" title="'.t('Reply To Message').'"><i class="fa fa-comment"> </i></a>';
                if($status=='Approved'){
                    $icon = '<a href="'.$this->action('remove',$comment['cnvMessageID']).'" title="'.t('Unapprove').'"><i class="fa fa-times-circle-o"> </i></a>';
                }else{
                    $icon = '<a href="'.$this->action('approve',$comment['cnvMessageID']).'" title="'.t('Approve').'"><i class="fa fa-check"> </i></a>';
                }
                echo '<tr class="message-'.$status.'">';
                echo '<td>'.$delete.' '.$spam.' '.$icon.' '.$reply.'</td>';
                echo '<td>'.$status.'</td>';
                echo '<td>'. (is_object($ui) ? $ui->getUserName() .'<br/>'. $ui->getUserEmail() : 'Guest') .'</td>';
                echo '<td>'.date('D M jS g:i a',strtotime($comment['cnvMessageDateCreated'])).'</td>';
                echo '<td>';
                echo '<a href="'.Loader::helper('navigation')->getLinkToCollection($page).'">'.$page->getCollectionPath().'</a><br/>';
                echo $comment['cnvMessageBody'];
                echo '</td>';
                echo '</tr>';
            }
        ?>
        </tbody>
    </table>
    </form>
    <br/>
    <?php        
        if ($pcount > $num) {
            echo '<div id="pagination">';
        
            if ($pageno == 1) {
                    echo t(" FIRST PREV ");
            } else {
                    echo '<a href="?pageno=1">'.t('FIRST ').'</a>';
                    $prevpage = $pageno-1;
                    echo '<a href="?pageno='.$prevpage.'">'.t(' PREV').'</a>';
            } // if
        
            echo ' ( Page '.$pageno.' of '.$lastpage.' ) ';
        
            if ($pageno == $lastpage) {
                    echo t(" NEXT LAST ");
            } else {
                    $nextpage = $pageno+1;
                    echo '<a href="?pageno='.$nextpage.'">'.t('NEXT ').'</a>';
                    echo '<a href="?pageno='.$lastpage.'">'.t(' LAST').'</a>';
            } // if     
            echo '</div>';
        }   
    ?>
    <br/>
    

</div>
<div class="ccm-pane-footer">

</div>
