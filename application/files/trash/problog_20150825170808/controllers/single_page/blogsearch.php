<?php  
namespace Concrete\Package\Problog\Controller\SinglePage;

use Loader;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use \Concrete\Core\Page\Controller\PageController;
use Page;

class Blogsearch extends PageController
{

    public function view($filter=null,$val=null)
    {
        if($filter){
            $this->parseSearchPath($filter,$val);
        }else{

            $c = Page::getCurrentPage();
            
            $metaT = t('Blog Search Page');
            $metaD = t('Search website for terms, categories, tags and content keywords.');
            
            $mtitle = CollectionAttributeKey::getByHandle('meta_title');
            $c->setAttribute($mtitle,$metaT);
            
            $mdesc = CollectionAttributeKey::getByHandle('meta_description');
            $c->setAttribute($mdesc,$metaD);
            
        }
    }
    
    private function parseSearchPath($filter=null,$val=null){

        if($filter && $val){
            $category = $val;
        }else{
            $tag = $filter;
        }

        $c = Page::getCurrentPage();
        if($category){
            $metaT = t('Blog Search Categories').' - '.$category;
            $metaD = t('Blog categories search result for ').$category;
            $this->set('category',$category);
        }else{
            $metaT = t('Blog Search Tags').' - '.$tag;
            $metaD = t('Blog tags search result for ').$tag;
            $this->set('tag',$tag);
        }
        
        $mtitle = CollectionAttributeKey::getByHandle('meta_title');
        $c->setAttribute($mtitle,$metaT);
        
        $mdesc = CollectionAttributeKey::getByHandle('meta_description');
        $c->setAttribute($mdesc,$metaD);

        $blogify = Loader::helper('blogify');
        
        $refered = str_replace(BASE_URL,'',str_replace('/index.php','',$_SERVER['HTTP_REFERER']));
        $rp = Page::getByPath($refered);
        if($rp){
            $parent = $blogify->getCanonicalParent(null,$rp);
            $_REQUEST['search_paths'][] = Page::getByID($parent)->getCollectionPath();
        }
        
        $blog_settings = $blogify->getBlogSettings();
        $path = Loader::helper('navigation')->getLinkToCollection(Page::getByID($blog_settings['search_path']));

        if($category != ''){
            $category = str_replace('_',' ',$category);
            $ak = CollectionAttributeKey::getByHandle('blog_category');
            $akID = $ak->akID;
            $akc = $ak->getController();
            $options = $akc->getOptions();
            if(is_object($options)){
                foreach($options as $option){
                    if($option == $category){
                        $url = $path.'?akID['.$akID.'][atSelectOptionID][]='.$option->ID;
                        if($blog_settings['search_path'] == $c->getCollectionID()){
                            $_REQUEST['akID'][$akID]['atSelectOptionID'][] = $option->ID;
                            $_REQUEST['query'] = $category;
                        }else{
                            $this->redirect($url);
                        }
                    }
                }
            }
        }elseif(substr_count($_SERVER["REQUEST_URI"],'atSelectOptionID') < 1){
            $tag = str_replace('_',' ',$tag);
            $ak = CollectionAttributeKey::getByHandle('tags');
            $akID = $ak->akID;
            $akc = $ak->getController();
            $options = $akc->getOptions();
            if(is_object($options)){
                foreach($options as $option){
                    if($option == $tag){
                        $url = $path.'?akID['.$akID.'][atSelectOptionID][]='.$option->ID;
                        if($blog_settings['search_path'] == $c->getCollectionID()){
                            $_REQUEST['akID'][$akID]['atSelectOptionID'][] = $option->ID;
                            $_REQUEST['query'] = $tag;
                        }else{
                            $this->redirect($url);
                        }
                    }
                }
            }
        }
    }

}
