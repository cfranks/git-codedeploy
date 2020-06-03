<?php
defined('C5_EXECUTE') or die("Access Denied.");
$ih = Core::make('helper/concrete/ui');
$del_url = '/dashboard/prayer_corner/manage/delete/' . $data['pID'];
$reject_url = '/dashboard/prayer_corner/manage/reject/' . $data['pID'];
$approve_url = '/dashboard/prayer_corner/manage/approve/' . $data['pID'];
$languages = Config::get('prayer_corner::languages');
$status = Config::get('prayer_corner::custom.Status');
?>
<div class="ccm-pane-body" >
    <div class="ccm-dashboard-header-buttons">
        <?php echo($ih->button(t('Back To Listing'), $this->url('/dashboard/prayer_corner/manage'), 'left', 'btn-primary')); ?>
        <!-- status is not 'Not authorize' -->
        <?php if($data['status']!=3) {?> 
            <?php if($data['status']!=1) { ?>
                <?php echo($ih->button(t('Mark as Approved'), $this->url($approve_url), 'left', 'btn-success', ['style'=>'margin-left: 10px'])); ?>
            <?php } ?>
            <?php if($data['status']!=2) { ?>
                <?php echo($ih->button(t('Mark as Rejected'), $this->url($reject_url), 'left', 'btn-warning', ['style'=>'margin-left: 10px'])); ?>
            <?php } ?>
        <?php } ?>
        <?php echo($ih->button(t('Delete'), $this->url($del_url), 'left', 'btn-danger', ['style'=>'margin-left: 10px','onclick' => 'return confirm(\'Are you sure you want to delete this? This action can not be undone.\')'])); ?>
    </div>

    <div class="clearfix"></div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                Form Submission Details
            </div>
        </div>

        <div class="panel-body">
            <div class="form-group row">
                <div class="col-sm-4">
                    <span class="">
                        <?php echo $form->label('first_name', 'First Name:'); ?>
                    </span>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php echo isset($data['first_name']) && !empty($data['first_name']) ? $data['first_name'] : 'NA'; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <span class="">
                        <?php echo $form->label('last_name', 'First Name:'); ?>
                    </span>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php echo isset($data['last_name']) && !empty($data['last_name']) ? $data['last_name'] : 'NA'; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <span class="">
                        <?php echo $form->label('email', 'Email Address:'); ?>
                    </span>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php echo isset($data['email']) && !empty($data['email']) ? $data['email'] : 'NA'; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <span class="">
                        <?php echo $form->label('city', 'City:'); ?>
                    </span>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php echo isset($data['city']) && !empty($data['city']) ? $data['city'] : 'NA'; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <span class="">
                        <?php echo $form->label('country', 'Country:'); ?>
                    </span>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php echo isset($countries[$data['country']]) ? $countries[$data['country']] : ($data['country'] ? $data['country'] : ''); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <span class="">
                        <?php echo $form->label('prayer_request', 'Prayer request:'); ?>
                    </span>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php echo isset($data['prayer_request']) && !empty($data['prayer_request']) ? $data['prayer_request'] : 'NA'; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <span class="">
                        <?php echo $form->label('post_public', 'Post to the public Prayer Wall:'); ?>
                    </span>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php echo isset($data['post_public']) && $data['post_public']==1 ? 'Yes' : 'No'; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <span class="">
                        <?php echo $form->label('email_consent', 'Consent for Contact Via Email:'); ?>
                    </span>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php echo isset($data['email_consent']) && $data['email_consent']==1 ? 'Yes' : 'No'; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <span class="">
                        <?php echo $form->label('status', 'Status:'); ?>
                    </span>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php echo isset($status[$data['status']]) ? $status[$data['status']] : $status[0]; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <span class="">
                        <?php echo $form->label('language', 'Language:'); ?>
                    </span>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php echo isset($languages[$data['language']]) ? $languages[$data['language']] : 'NA'; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <span class="">
                        <?php echo $form->label('date_created', 'Date Created:'); ?>
                    </span>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php echo isset($data['date_created']) && strtotime($data['date_created']) > 0 ? date("m/d/Y", strtotime($data['date_created'])) : 'NA'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>