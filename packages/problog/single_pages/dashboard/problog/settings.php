<?php   defined('C5_EXECUTE') or die("Access Denied.");
$fm = Loader::helper('form');
$pgp=Loader::helper('form/page_selector');
?>
<style type="text/css">
    div#ccm-dashboard-content header{padding: 1px 80px 14px 55px;}
    table td{padding: 12px!important;}
</style>

<?php  echo  Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t(' Blog Settings'),false,'span10 offset1',false) ?>

<h4><?php   echo t('Options')?></h4>

<form method="post" action="<?php   echo $this->action('save')?>" id="settings">
<table>
    <tr>
        <td colspan="2"><strong><?php   echo t('Show')?></strong></td>
    </tr>
    <tr>
        <td>
            <input name="tweet" type="checkbox" value="1" <?php    if ($tweet==1) {echo ' checked';}?> /> <?php   echo t('Twitter')?>
        </td>
        <td>
            <input name="google" type="checkbox" value="1" <?php    if ($google==1) {echo ' checked';}?> /> <?php   echo t('Google +1')?>
        </td>
        <td>
            <input name="fb_like" type="checkbox" value="1" <?php    if ($fb_like==1) {echo ' checked';}?> /> <?php   echo t('Facebook Like')?>
        </td>
        <td>
            <input name="author" type="checkbox" value="1" <?php    if ($author==1) {echo ' checked';}?> /> <?php   echo t('Author Info')?>
        </td>
        <td>
            <input name="comments" type="checkbox" value="1" <?php    if ($comments==1) {echo ' checked';}?> /> <?php   echo t('Comments')?>
        </td>
    </tr>
</table>
<br/>

<br/>
<h4><?php   echo t('Publishing Settings')?></h4>
<div>
    <table id="settings3" class="table">
        <tr>
            <th class="header">
                <strong><?php   echo t('Enable Canonical URLS')?></strong>
            </th>
        </tr>
        <tr>
            <td>
                <input name="canonical" type="checkbox" value="1" <?php    if ($canonical==1) {echo ' checked';}?> /> <?php   echo t('Yes')?> <br/>
                <i><?php   echo t('Automatically publish pages by year and month. (/blog/2013/06/my-blog-post)')?></i>
            </td>
        </tr>
        <tr>
            <th class="header">
                <strong><?php   echo t('Default Page Template')?></strong>
            </th>
        </tr>
        <tr>
            <td>
                <?php    echo $form->select('pageTemplate', $pageTemplates, $pageTemplate)?>
            </td>
        </tr>
    </table>
</div>

<br/>
<h4><?php   echo t('Content')?></h4>
<div>
    <table id="settings3" class="table">
        <tr>
            <th class="header">
                <strong><?php   echo t('Page Break for Post Preview')?></strong>
            </th>
        </tr>
        <tr>
            <td>
                <?php    echo $fm->text('breakSyntax',$breakSyntax);?>
            </td>
        </tr>
    </table>
</div>

		<br/>
		<h4><?php      echo t('Blog List Settings')?></h4>
		<div style="width: 380px;">
			<table id="settings3" class="ccm-grid" style="width: 380px;">
				<tr>
					<th class="header">
					<strong><?php      echo t('Max Thumbnail Width')?></strong>
					</th>
				</tr>
				<tr>
					<td>
					<?php       
					echo $fm->text('thumb_width',$thumb_width,array('size'=>'2'));
					echo 'px';
					?>
					</td>
				</tr>
				<tr>
					<th class="header">
					<strong><?php      echo t('Max Thumbnail Height')?></strong>
					</th>
				</tr>
				<tr>
					<td>
					<?php       
					echo $fm->text('thumb_height',$thumb_height,array('size'=>'2'));
					echo 'px';
					?>
					</td>
				</tr>
			</table>
		</div>

<br/>
<h4><?php   echo t('Blog Path Settings')?></h4>
<div>
    <table id="settings2" class="table">
        <tr>
            <th class="header">
                <strong><?php   echo t('Tags/Categories Search Results Location')?></strong>
            </th>
        </tr>
        <tr>
            <td>
                <?php  
                echo $pgp->selectPage('search_path',$search_path);
                ?>
            </td>
        </tr>
    </table>
</div>

<br/>
<h4><?php   echo t('API Settings')?></h4>
<div>
    <table id="settings3" class="table">
        <tr>
            <th class="header">
                <strong><?php   echo t('Alchemy API Key')?></strong>
            </th>
        </tr>
        <tr>
            <td>
                <?php    echo $fm->text('alchemy', $alchemy)?>
                <br/><br/>
                <i><?php   echo t('Your Alchemy API key enables you to optimize your blog content.')?></i>
            </td>
        </tr>
        <tr>
            <th class="header">
                <strong><?php   echo t('Sharethis Publisher Key')?></strong>
            </th>
        </tr>
        <tr>
            <td>
                <?php    echo $fm->text('sharethis', $sharethis)?>
                <br/><br/>
                <i><?php   echo t('grab your publisher key <a href="http://sharethis.com/account/"
                target="_blank">http://sharethis.com/account/</a> in your account area.  track your social impact for free.')?></i>
            </td>
        </tr>
        <tr>
            <th class="header">
                <strong><?php   echo t('Disqus Short Name (case sensitive)')?></strong>
            </th>
        </tr>
        <tr>
            <td>
                <?php  
                echo $fm->text('disqus',$disqus,array('size'=>'2'));
                ?>
                <br/><br/>
                <i><?php   echo t('Entering this value will set your comments, comment counts,
                and latest comments block to the Disqus comments system.  Simply remove this value if you desire to use guestbook and/or advanced guestbook.')?></i>
            </td>
        </tr>
        <tr>
            <th class="header">
                <strong><?php   echo t('embed.ly API Key')?></strong>
            </th>
        </tr>
        <tr>
            <td>
                <?php   echo $fm->text('embedly', $embedly)?>
                <br/><br/>
                <i><?php   echo t('grab your API key <a href="http://embed.ly/" target="_blank">http://embed.ly</a> in
                your account area for free & embed pretty much anything!')?></i>
            </td>
        </tr>
        <tr>
            <th class="header">
                <strong><?php   echo t('Enable Twitter Option')?></strong>
            </th>
        </tr>
        <tr>
            <td>
                <?php  
                $pkg = Package::getByHandle('problog');
                $PB_AUTH_TOKEN = $pkg->getConfig()->get('api.twitter_auth_token', false);
                $PB_AUTH_SECRET = $pkg->getConfig()->get('api.twitter_auth_secret', false);
                $PB_APP_KEY = $pkg->getConfig()->get('api.twitter_app_key', false);
                $PB_APP_SECRET = $pkg->getConfig()->get('api.twitter_app_secret', false);
                $uh = Loader::helper('concrete/urls');
                $tool = urlencode(URL::to('problog/tools/twitter_save'));
                if (!$PB_APP_KEY) {
                    $pkg->getConfig()->save('api.twitter_app_key', 'MfUJJrhZDXHUsvbVSf2Ag');
                    $pkg->getConfig()->save('api.twitter_app_secret', 'uhvYCtKCNSdHGYvwNwu80rkw5Ju53f5jhaLPMAgK0');
                    $PB_APP_KEY = $pkg->getConfig()->get('api.twitter_app_key', false);
                    $PB_APP_SECRET = $pkg->getConfig()->get('api.twitter_app_secret', false);
                }
                if (!$PB_AUTH_TOKEN) {
                    $connection = new TwitterOAuth($PB_APP_KEY, $PB_APP_SECRET);
                    $temporary_credentials = $connection->getRequestToken($tool);
                    $session->set('problog.twitter.oauth_token',$temporary_credentials['oauth_token']);
                    $session->set('problog.twitter.oauth_token_secret',$temporary_credentials['oauth_token_secret']);
                    $redirect_url = $connection->getAuthorizeURL($temporary_credentials,false);
                    echo '<a href="' . $redirect_url . '" class="btn btn-info"><i class="fa fa-twitter"></i> '.t('Authorize with Twitter').'</a>';
                } else {
                    $connection = new TwitterOAuth($PB_APP_KEY, $PB_APP_SECRET, $PB_AUTH_TOKEN, $PB_AUTH_SECRET);
                    $content = $connection->get('account/verify_credentials');
                    $username = $content->screen_name;
                    $profilepic = $content->profile_image_url;
                    echo '</br/>';
                    echo t('You are connected to twitter as').': <br/><br/><img src="'.$profilepic.'" width="22px"/> <a href="https://twitter.com/#!/'.$username.'">'.$username.'</a>';
                    echo '<a href="'.$this->action('clear_twitter').'" class="btn btn-danger ccm-button-v2-right">'.t
                        ('Clear').'</a>';
                }

                ?>
            </td>
        </tr>
    </table>
</div>
<div class="ccm-pane-footer">
    <button type="submit" class="btn btn-primary" style="float: right;"><?php   echo t('Save Settings') ?></button>
</div>
</form>
