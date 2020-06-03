<?php 
    $status = Config::get('prayer_corner::custom.Status'); 
    $languages = Config::get('prayer_corner::languages');
?>
<div class="ccm-pane-body">
    <form method="get">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                Filter
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <?php echo $form->label('Keyword', 'Keyword'); ?>
                            <?php echo $form->text("Keyword", '', array('class' => 'form-control margin-bottom', 'placeholder' => 'Search')); ?>     
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <?php echo $form->label('Status', 'Status'); ?>
                            <?php echo $form->select('Status', ['' => '--Select Status--'] + $status, '', array('class' => 'margin-bottom')) ?>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <?php echo $form->label('Language', 'Language'); ?>
                            <?php echo $form->select('Language', ['' => '--Select Language--'] + $languages, '', array('class' => 'margin-bottom')) ?>
                        </div>
                    </div>
                </div>
                <div class="row">    
                    <div class="col-sm-4">
                        <div class="form-group">
                            <?php echo $form->label('From', 'From Date'); ?>    
                            <?php echo $form->text("From", '', array('class' => 'form-control margin-bottom datepicker', 'placeholder' => 'From date')); ?>     
                            <div class="text-muted">Applied on date created</div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <?php echo $form->label('To', 'To Date'); ?>    
                            <?php echo $form->text("To", '', array('class' => 'form-control margin-bottom datepicker', 'placeholder' => 'To date')); ?>     
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <br>
                        <strong>Prayers count: </strong> <?php echo(count($prayers)); ?>
                    </div>
                </div>
            </div>

            <div class="panel-footer text-center">
                <?php echo $form->submit("filter", "Filter", array('class' => 'btn btn-primary')) ?>
                <a href="<?php echo $this->action(''); ?>" class="btn btn-default">Clear Filter</a>
            </div>
        </div>
    </form>
    <table class="table table-striped">
        <thead>
        <tr>
            <th></th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Status</th>
            <th>Date Created</th>
            <th>Date Modified</th>
        </tr>
        </thead>
        <?php foreach($prayers as $prayer) { ?>
            <tr>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Action
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <!-- status is not 'Not authorize' -->
                            <?php if($prayer['status']!=3) {?> 
                                <?php if($prayer['status']!=1 ) { ?>
                                    <li><a href="<?php echo $this->action('approve', $prayer['pID']); ?>">Approve</a></li>
                                <?php } ?>
                                <?php if($prayer['status']!=2) { ?>
                                    <li><a href="<?php echo $this->action('reject', $prayer['pID']); ?>">Reject</a></li>
                                <?php } ?>
                            <?php } ?>
                            <li><a href="<?php echo $this->action('delete', $prayer['pID']); ?>" onclick="return confirm('Are you sure you want to delete this? This action cannot be undone.');">Delete</a></li>
                        </ul>
                    </div>
                </td>
                <td><a href="<?php echo $this->action('detail', $prayer['pID']); ?>"><?php echo $prayer['first_name']; ?></a></td>
                <td><?php echo $prayer['last_name']; ?></td>
                <td><?php echo isset($status[$prayer['status']]) ? $status[$prayer['status']] : $status[0]; ?></td>
                <td><?php echo strtotime($prayer['date_created']) > 0 ? date("n/j/Y", strtotime($prayer['date_created'])) : ''; ?></td>
                <td><?php echo strtotime($prayer['date_modified']) > 0 ? date("n/j/Y", strtotime($prayer['date_modified'])) : ''; ?></td>
            </tr>
        <?php } ?>
        <?php if(count($prayers)==0) { ?>
          <tr>
            <td colspan="10">No result found</td>
          </tr>  
        <?php } ?>
    </table>
    <?php if (isset($prayers) && count($prayers) > 0 && $model->requiresPaging()) { ?>
        <div class="panel-footer">
            <?php echo $model->displayPagingV2(); ?>
        </div>
    <?php } ?>
</div>