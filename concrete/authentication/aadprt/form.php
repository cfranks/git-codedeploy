<?php
use Concrete\Core\Validation\CSRF\Token;

defined('C5_EXECUTE') or die('Access Denied');
if (isset($error)) {
    ?>
    <div class="alert alert-danger"><?php echo $error ?></div>
    <?php
}
if (isset($message)) {
    ?>
    <div class="alert alert-success"><?php echo $message ?></div>
<?php
}


if (isset($show_email) && $show_email) {
    ?>
    <form action="<?php echo \URL::to('/login/callback/aadprt/handle_register') ?>">
        <span><?php echo t('Register an account for "%s"', "@{$username}") ?></span>
        <hr />
        <div class="input-group">
            <input type="email" name="uEmail" placeholder="email" class="form-control" />
            <span class="input-group-btn">
                <button class="btn btn-primary"><?php echo t('Register') ?></button>
            </span>
        </div>
        <?php echo id(new Token)->output('aadprt_register'); ?>
    </form>
    <?php
} else {

    $user = new User;

    if ($user->isLoggedIn()) {
        ?>
        <div class="form-group">
            <span>
                <?php echo t('Attach a %s account', t('aadprt')) ?>
            </span>
            <hr>
        </div>
        <div class="form-group">
            <a href="<?php echo \URL::to('/ccm/system/authentication/oauth2/aadprt/attempt_attach'); ?>"
               class="btn btn-primary btn-aadprt btn-block">
                <i class="fa fa-aadprt"></i>
                <?php echo t('Attach a %s account', t('aadprt')) ?>
            </a>
        </div>
    <?php
    } else {
        ?>
        <div class="form-group">
            <span>
                <?php echo t('Sign in with %s', t('aadprt')) ?>
            </span>
            <hr>
        </div>
        <div class="form-group">
            <a href="<?php echo \URL::to('/ccm/system/authentication/oauth2/aadprt/attempt_auth'); ?>"
               class="btn btn-primary btn-aadprt btn-block">
                <i class="fa fa-aadprt"></i>
                <?php echo t('Log in with %s', 'aadprt') ?>
            </a>
        </div>
    <?php
    }
    ?>
    <style>
        .ccm-ui .btn-aadprt {
            border-width: 0px;
            background: #00aced;
        }

        .btn-aadprt .fa-aadprt {
            margin: 0 6px 0 3px;
        }
    </style>
<?php
}
?>
