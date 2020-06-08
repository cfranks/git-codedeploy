<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
?>
<link rel="stylesheet" type="text/css" href="<?php   echo URL::to('/packages/problog/css/css/font-awesome.css');?>"></link>
<link rel="stylesheet" type="text/css" href="<?php   echo URL::to('/packages/problog/css/css/seo_tools.css');?>"></link>
<script type="text/javascript" src="<?php   echo URL::to('/packages/problog/js/seo_tools.js');?>"></script>
<style type="text/css">
    .good{color: green!important;}
    .borderline{color: #f2a502!important;}
    .poor{color: maroon!important;}
    tr.good td{background-color: #d4ffd0!important;}
    tr.borderline td{background-color: #fff6d5!important;}
    tr.poor td{background-color: #ffd9d9!important;}
    td.checkmark{font-size: 22px;}
</style>
    <div class="col-md-9">
        <div style="display: none;" id="content_dom"></div>
        <a href="http://www.alchemyapi.com/" class="alchemy" target="_blank"></a>
        <h3><?php  echo t('Natural Language Keyword Phrase Strength')?>  <i class="icon icon-question-sign tooltips" title="<?php  echo t('<p>Natural Language Processing (NLP) is an advanced algorithm design to appropriately arranged keywords as users would most likely search for them</p><p>This differs from single keyword density in that users rarely will search for any given term by itself.  Thus providing a more concise recognizable grouping of keyword terms.  Search Engines are now progressing to recognize and parse data in this way</p>')?>"></i></h3>
        <table id="phrase_result" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th><?php  echo t('NLP Keyword Phrase')?></th>
                <th><?php  echo t('Search Relevance')?></th>
                <th><?php  echo t('Times Used')?></th>
                <th><?php  echo t('Density %')?></th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

        <h3><?php  echo t('Single Keyword Strength')?>  <i class="icon icon-question-sign tooltips" title="<?php  echo t('<p>Single Keyword Optimization is still very important in how Search Engines view your content</p><p>The recommended Keyword Density is 1-3%</p>')?>"></i></h3>
        <table id="single_result" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th><?php  echo t('Keyword')?></th>
                <th><?php  echo t('Times Used')?></th>
                <th><?php  echo t('Density %')?></th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>


        <h3><?php  echo t('Link Usage')?> <i id="link_metric"></i>  <i class="icon icon-question-sign tooltips" title="<?php  echo t('<p>Link use is also important in how Search Engines perceive your content.</p><p>The more links you have to related & relevant content, the more relevant Search Engines deem your post.</p><p>Having XMLRPC compatible links can also have a positive impact as your posts will be cross commented between both sites with back linking available for crawlers.</p><p>The recommended Link Density is .5-1.5%</p>')?>"></i></h3>
        <table id="links_result" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th><?php  echo t('Link Address')?></th>
                <th><?php  echo t('Valid URL')?></th>
                <th><?php  echo t('XMLRPC')?></th>
                <th><?php  echo t('Rel Nofollow')?></th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>


        <h3><?php  echo t('Image Usage')?> <i id="image_metric"></i>  <i class="icon icon-question-sign tooltips" title="<?php  echo t('<p>Because most Search Engines now index images for use in their optimized image search tools, having images in your posts can have a positive impact on your page rankings.</p><p>The recommended Image Density is .5-1.5%</p>')?>"></i></h3>
        <table id="img_result" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th><?php  echo t('Link Address')?></th>
                <th><?php  echo t('Valid URL')?></th>
                <th><?php  echo t('Alt Data')?></th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <div class="col-md-3">
        <h3><?php  echo t('SEO Checklist')?>  <i class="icon icon-question-sign tooltips" title="<?php  echo t('<p>This list is designed to help you produce consistent & relevant content that is search optimized and spider friendly.</p><p>The below should be considered the minimum requirement for stronger SEO presence.</p>')?>"></i></h3>
        <table id="metrics" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th><?php  echo t('Metric')?></th>
                <th><?php  echo t('Status')?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?php  echo t('Meta Title')?></td>
                <td class="checkmark"><i class="fa fa-circle-o meta-title"></i></td>
            </tr>
            <tr>
                <td><?php  echo t('Meta Description')?></td>
                <td class="checkmark"><i class="fa fa-circle-o meta-description"></i></td>
            </tr>
            <tr>
                <td><?php  echo t('Meta Keywords')?></td>
                <td class="checkmark"><i class="fa fa-circle-o meta-keywords"></i></td>
            </tr>
            <tr>
                <td><?php  echo t('NLP Keyphrase Density')?></td>
                <td class="checkmark"><i class="fa fa-circle-o keyphrase"></i></td>
            </tr>
            <tr>
                <td><?php  echo t('Keyword Density')?></td>
                <td class="checkmark"><i class="fa fa-circle-o keyword"></i></td>
            </tr>
            <tr>
                <td><?php  echo t('Link Density')?></td>
                <td class="checkmark"><i class="fa fa-circle-o links"></i></td>
            </tr>
            <tr>
                <td><?php  echo t('Image Density')?></td>
                <td class="checkmark"><i class="fa fa-circle-o images"></i></td>
            </tr>
            </tbody>
        </table>

        <h3><?php  echo t('Meta\'s Checklist')?>  <i class="icon icon-question-sign tooltips" title="<?php  echo t('<p>Correlating Meta data is surprizingly often overlooked. </p><p>This Checklist is designed to help you keep your keyword phrases and keyword strategy consistent throughout your meta data</p>')?>"></i></h3>
        <table id="meta_stats" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th><?php  echo t('Metric')?></th>
                <th><?php  echo t('Status')?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?php  echo t('Meta Title contains a top used Keyphrase')?></td>
                <td class="checkmark"><i class="fa fa-circle-o meta-title"></i></td>
            </tr>
            <tr>
                <td><?php  echo t('Meta Description contains top 5 keywords')?></td>
                <td class="checkmark"><i class="fa fa-circle-o meta-description"></i></td>
            </tr>
            <tr>
                <td><?php  echo t('Meta Keywords contain top 5 keyphrases & top 5 keywords')?></td>
                <td class="checkmark"><i class="fa fa-circle-o meta-keywords"></i></td>
            </tr>
            <tr>
                <td><?php  echo t('Tag Attribute contains 3 keywords')?></td>
                <td class="checkmark"><i class="fa fa-circle-o post-tags"></i></td>
            </tr>
            </tbody>
        </table>

        <h3><?php  echo t('HTML Checklist')?>  <i class="icon icon-question-sign tooltips" title="<?php  echo t('<p>This Checklist is designed to optimize your use of HTML tags.</p><p>Search Engines now prioritize semantically structured content over older markup formatting.</p>')?>"></i></h3>
        <table id="html_stats" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th><?php  echo t('Metric')?></th>
                <th><?php  echo t('Status')?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?php  echo t('Page title contains a top used keyphrase')?></td>
                <td class="checkmark"><i class="fa fa-circle-o title"></i></td>
            </tr>
            <tr>
                <td><?php  echo t('No H1 tags within your post')?></td>
                <td class="checkmark"><i class="fa fa-circle-o no-h1s"></i></td>
            </tr>
            <tr>
                <td><?php  echo t('Use of H2 tags')?></td>
                <td class="checkmark"><i class="fa fa-circle-o h2s"></i></td>
            </tr>
            <tr>
                <td><?php  echo t('Use of Blockquote tags')?></td>
                <td class="checkmark"><i class="fa fa-circle-o blockquotes"></i></td>
            </tr>
            </tbody>
        </table>
    </div>
    <script type="text/javascript">
        var phraseWords = null;
        var keyWords = null;
        var linkUse = null;
        var imageUse = null;
        var t5kw = new Array();
        var t5pw = new Array();
        var checkUrl = '<?php  echo Loader::helper('concrete/urls')->getToolsURL('check_url.php','problog')?>';

        $(document).ready(function () {

            $('.close').click(function () {
                $(this).parent().remove();
            });

            $('.seo-tools').click(function () {
                runOptimizer();
            });

            if (typeof runOptimizer === "function") { 
                function runOptimizer(){
                    var APIkey = '<?php  echo $settings['alchemy']?>';
                    var url = 'http://access.alchemyapi.com/';
                    var content = getContent();
                    var text = content.replace(/(<([^>]+)>)/ig,"");
                    $.ajax({
                        url: url+'calls/text/TextGetRankedKeywords',
                        type: 'get',
                        data: {
                            apikey: APIkey,
                            text: text,
                            outputMode: 'json',
                            jsonp: 'getKeywordsFromText',
                            maxRetrieve: 10,
                            keywordExtractMode: 'strict',
                            sentiment: 1
                        },
                        dataType: 'jsonp',
                        complete: function () {
                            scrapeSingleKeywords();

                            getArticleImages();

                            doSeoChecks();

                            doMetaChecks();

                            doHtmlChecks();

                            getArticleLinks();
                        }
                    });

                };
            }

            $('.tooltips').tooltip();

            $('.save').click(function () {
                $('#blog-form').append('<input type="hidden" name="save_post" value="1"/>');
                $('#blog-form').submit();
            });

            runOptimizer();
        });
    </script>