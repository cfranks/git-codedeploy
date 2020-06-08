<?php   defined('C5_EXECUTE') or die(_("Access Denied."));

use Page as Page;
use Block as Block;
use \Concrete\Package\Problog\Block\ProblogList\Controller as ProblogListBlockController;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;

if($_GET['bID'] && $_GET['problogRss']){

    //grab our problog_list block
    $b = Block::getByID($_GET['bID']);

    //grab the list page
    $c = $b->getBlockCollectionObject();

        header('Content-type: text/xml;');
        echo "<" . "?" . "xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo "<"."?"."xml-stylesheet type=\"text/css\" media=\"screen\" href=\"http://feeds.feedburner.com/~d/styles/itemcontent.css\"?>\n";
            
            //grab our problog_list block
            $b = Block::getByID($_GET['bID']);

            //grab the list page
            $c = $b->getBlockCollectionObject();
            //last modified
            $lastmodified = $c->getCollectionDateLastModified();

            //get this install's blog settings
            $blogify = Loader::helper('blogify');
            //extract settings to vars
            extract($blogify->getBlogSettings());

            //if we have a valid RSS block
            //loop through and process.
            if ($b) {

                $controller = $b->getController();

                $cArray = $controller->getPages();
                $nh = Loader::helper('navigation');

                $feed .= '<rss version="2.0">';
                $feed .= '  <channel>';
                $feed .= '  <title><![CDATA['.$controller->rssTitle.']]></title>';
                $feed .= '  <link>'.BASE_URL.DIR_REL.htmlspecialchars($rssUrl).'</link>';
                $feed .= '  <description><![CDATA['.$controller->rssDescription.']]></description> ';
                $feed .= '  <lastBuildDate>'.date('D, j M Y',strtotime($lastmodified)).'</lastBuildDate>';
                for ($i = 0; $i < count($cArray); $i++ ) {
                    $cobj = $cArray[$i];
                    $title = $cobj->getCollectionName();
                    $feed .= '  <item>';
                    $feed .= '    <title><![CDATA['.$title.']]></title>';
                    $feed .= '    <link>';
                    $feed .=        BASE_URL.$nh->getLinkToCollection($cobj);
                    $feed .= '    </link>';

                    //are we using the content block?
                    if ($controller->use_content > 0) {
                        //grab page area 'Main'
                        $block = $cobj->getBlocks('Main');
                        //loop through all blocks
                        foreach ($block as $bi) {
                            //find the content block
                            if ($bi->getBlockTypeHandle()=='content' || $bi->getBlockTypeHandle()=='sb_blog_post') {
                                //assign content
                                $content = $bi->getInstance()->getContent();
                            }
                        }
                    } else {
                        //use collection description
                        $content = $cobj->getCollectionDescription();
                    }
                    //should we page break?
                    if ($controller->PageBreak && $breakSyntax) {
                        //explode to array at page break
                        $tempContent = explode($breakSyntax,$content);
                        //assign new content var
                        $content = $tempContent[0];
                    } else {
                        //strip page break tag
                        $content = str_replace($breakSyntax,'',$content);
                    }

                    //clean up any truncated or broken tags
                    $tidy = $blogify->closetags($content);
                    // replaces html non-breaking space with an actual space
        /*
                    $tidy = preg_replace("/\s| /"," ", $tidy);
                    // removes other encoded chars
                    $tidy = preg_replace("/&#?[a-z0-9]{2,8};/i","", $tidy);
        */

                    $feed .= '    <description><![CDATA['.$tidy.']]></description>';

                    $tags = preg_split('/\n/', $cobj->getAttribute('tags'));
                    if ($tags) {
                        foreach ($tags as $tag) {
                          $feed .= "    <category>";
                          $feed .= $tag;
                          $feed .= "    </category>";
                        }
                    }

                    //$feed .= '      <pubDate>'.date( 'D, d M Y H:i:s T',strtotime($cobj->getCollectionDatePublic())).'</pubDate>
                    $feed .= '    <pubDate>'.date( DATE_RFC822,strtotime($cobj->getCollectionDatePublic())).'</pubDate>';
                    $feed .= '  </item>';
                }
                $feed .= '  </channel>';
                $feed .= '</rss>';

                echo $feed;
            }
    }
exit;