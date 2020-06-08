<?php
defined('C5_EXECUTE') or die('Access Denied');
?>
<div class="form-group">
            <span>
                <?php echo t('Attach a %s account', t('aadprt')) ?>
            </span>
    <hr>
</div>
<div class="form-group">
    <a href="<?php echo \URL::to('/ccm/system/authentication/oauth2/aadprt/attempt_attach'); ?>"
       class="btn btn-primary btn-aadprt">
        <i class="fa fa-aadprt"></i>
        <?php echo t('Attach a %s account', t('aadprt')) ?>
    </a>
</div>
<style>
    .ccm-ui .btn-aadprt {
        border-width: 0px;
        background: #00aced;
    }

    .btn-aadprt .fa-aadprt {
        margin: 0 6px 0 3px;
    }
</style>
