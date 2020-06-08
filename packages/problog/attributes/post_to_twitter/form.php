<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
?>
<br/>
<?php  
$pkg = Package::getByHandle('problog');
$doTweet = URL::to("/problog/tools/dotweet");
$PB_AUTH_TOKEN = $pkg->getConfig()->get('api.twitter_auth_token', false);
if ($PB_AUTH_TOKEN) {
$tweet_token = Loader::helper('validation/token')->generate('tweet_token');
?>
<div class="alert alert-success" id="tweet-success" role="alert" style="display: none;">
    <i class="fa fa-twitter"></i> &nbsp;<?php  echo t('Your Tweet Is Live! Woot!!!')?>
</div>
<div id="tweet-form">
    <a class="btn btn-info" id="tweet-now"><i class="fa fa-twitter"></i> <?php  echo t('Post To Twitter')?></a>
    <input type="text" id="tweet-hashtags" class="form-control" placeholder="<?php  echo t('#hashtag1 #hashtag2 @soandso')?>" maxlength="100" style="max-width: 310px;display:inline-block;"/>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#tweet-now").click(function(e){
            e.preventDefault();
            if($('#blogID').val() < 1){
                alert('You must save your Blog Post at least once before tweeting!');
                return false;
            }
            if($('#tweet-hashtags').val().length < 1){
                alert('<?php   echo t('You forgot to add some hashtags!'); ?>');
                return false;
            }
            $.ajax({
                url: '<?php  echo $doTweet?>',
                data: {
                    hashtags: $('#tweet-hashtags').val(),
                    pID: $('#blogID').val(),
                    tweet_token: '<?php   echo $tweet_token; ?>'
                },
                success: function(response){
                    if(response == 'success'){
                        $('#tweet-form').slideUp();
                        $('#tweet-success').slideDown();
                    }
                }
            });
            return false;
        });
    });
</script>
<?php   } else { ?>
    <div class="alert alert-info" role="alert">
        <i class="fa fa-twitter"></i> &nbsp;<?php  echo t('You have not authorized twitter in your ProBlog settings yet!')?>
    </div>
<?php   } ?>
