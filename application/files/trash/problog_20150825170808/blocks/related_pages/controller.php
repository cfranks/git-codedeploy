<?php  
namespace Concrete\Package\Problog\Block\RelatedPages;

use \Concrete\Core\Block\BlockController;
use Loader;
use \Concrete\Package\Problog\Src\Models\ProblogList as ProblogList;
use \Concrete\Core\Block\BlockType\BlockType as BlockType;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use Page;
use View;

class Controller extends BlockController
{

        protected $btTable = 'btRelatedPages';
        protected $btInterfaceWidth = "500";
        protected $btInterfaceHeight = "350";

        public $ccID;

        //var $truncateChars = '90';
        //var $num = '3';

        /**
		 * Used for localization. If we want to localize the name/description we have to include this
		 */
        public function getBlockTypeDescription()
        {
            return t("Return a set number of related pages based on tags");
        }

        public function getBlockTypeName()
        {
            return t("Related Pages");
        }

        public function getJavaScriptStrings()
        {
            return array(
                'feed-name' => t('Please give your RSS Feed a name.')
            );
        }

        public function getPages($query = null)
        {
            global $c;
            $db = Loader::db();
            $bID = $this->bID;
            if ($this->bID) {
                $q = "select num, cParentID, cThis, orderBy, ctID, displayAliases, rss from btRelatedPages where bID = '$bID'";
                $r = $db->query($q);
                if ($r) {
                    $row = $r->fetchRow();
                }
            } else {
                $row['num'] = $this->num;
                $row['cParentID'] = $this->cParentID;
                $row['cThis'] = $this->cThis;
                $row['orderBy'] = $this->orderBy;
                $row['ctID'] = $this->ctID;
                $row['rss'] = $this->rss;
                $row['displayAliases'] = $this->displayAliases;
            }

            $pl = new ProblogList();

            $cArray = array();

            switch ($row['orderBy']) {
                case 'display_asc':
                    $pl->sortByDisplayOrder();
                    break;
                case 'display_desc':
                    $pl->sortByDisplayOrderDescending();
                    break;
                case 'chrono_asc':
                    $pl->sortByPublicDate();
                    break;
                case 'alpha_asc':
                    $pl->sortByName();
                    break;
                case 'alpha_desc':
                    $pl->sortByNameDescending();
                    break;
                default:
                    $pl->sortByPublicDateDescending();
                    break;
            }

            $num = (int) $row['num'];

            if ($num > 0) {
                $pl->setItemsPerPage($num);
            }

            $c = Page::getCurrentPage();
            if (is_object($c)) {
                $this->cID = $c->getCollectionID();
            }
            $cParentID = ($row['cThis']) ? $this->cID : $row['cParentID'];

            $pl->filter(false,"(CHAR_LENGTH(cv.cvName) > 4 OR cv.cvName NOT REGEXP '^[0-9]')");

            $pl->filter('cvName', '', '!=');

            if ($row['ctID']) {
                $ctID = $row['ctID'];
                $pl->filter(false,"p.ptID = $ctID");
            }

            $columns = $db->MetaColumns(CollectionAttributeKey::getIndexedSearchTable());
            if (isset($columns['AK_EXCLUDE_PAGE_LIST'])) {
                $pl->filter(false, '(ak_exclude_page_list = 0 or ak_exclude_page_list is null)');
            }

            if ( intval($row['cParentID']) != 0 && intval($row['cParentID']) != 9999) {
                $path = Page::getByID($cParentID)->getCollectionPath();
                $pl->filterByPath($path);
            } else {
                if (is_object($c)) {
                    $parent = $c->getCollectionParentID();
                    $pl->filterByParentID($parent);
                }
            }

            if ($this->displayFeaturedOnly == 1) {
                $cak = CollectionAttributeKey::getByHandle('is_featured');
                if (is_object($cak)) {
                    $pl->filterByIsFeatured(1);
                }
            }

            if ($this->akID) {
                if ($this->akID == 'same_tags') {
                    if (!is_object($c)) {
                        $c = Page::getByID($this->ccID);
                    }
                    $ak_handle = 'tags';
                    $tags = $c->getAttribute($ak_handle);
                    //$fields_array = explode(',',$tags);
                    if ($tags) {
                        $fields_array = $tags->getOptions();
                    }
                } else {
                    if (!is_object($c)) {
                        $c = Page::getByID($this->ccID);
                    }
                    $ak = CollectionAttributeKey::getByID($this->akID);
                    $ak_handle = $ak->getAttributeKeyHandle();
                    $fields_array = explode(',',$this->fields);
                }
                //var_dump($fields_array);
                if ($fields_array) {
                    foreach ($fields_array as $field) {
                        if ($fs) {
                            $filter .= ' OR ';
                        } else {
                            $filter .= '(';
                        }
                        $filter .= "ak_".$ak_handle." LIKE '%\n$field\n%'";
                        $fs++;
                    }

                    $filter .= ')';
                }
            }

            if (is_object($c)) {
                $pl->filter(false, 'p.cID != '.$c->getCollectionID());
            }

            if ($filter) {
                $pl->Filter(false,$filter);
            }

            //$pl->debug();

            $pl->ignoreAliases();
            $pages = $pl->getResults();
            
            $this->set('pl', $pl);

            return $pages;
        }

        public function add()
        {
            Loader::model("collection_types");
            $c = Page::getCurrentPage();
            $uh = Loader::helper('concrete/urls');
            //	echo $rssUrl;
            $this->set('c', $c);
            $this->set('uh', $uh);
            $this->set('bt', BlockType::getByHandle('related_pages'));
            $this->set('displayAliases', true);
        }

        public function edit()
        {
            $b = $this->getBlockObject();
            $bCID = $b->getBlockCollectionID();
            $bID=$b->getBlockID();
            $this->set('bID', $bID);
            $c = Page::getCurrentPage();
            if ($c->getCollectionID() != $this->cParentID && $c->getCollectionParentID() != $this->cParentID && ($this->cParentID != 0) && ($this->cParentID != 9999)) {
                $isOtherPage = true;
                $this->set('isOtherPage', true);
            }
            $uh = Loader::helper('concrete/urls');
            $this->set('uh', $uh);
            $this->set('bt', BlockType::getByHandle('related_pages'));
        }

        public function view()
        {
            $cArray = $this->getPages();
            $nh = Loader::helper('navigation');
            $this->set('nh', $nh);
            if ($this->orderBy == 'display_ran' && $cArray) {
                shuffle($cArray);
            }
            $this->set('cArray', $cArray);
        }

        public function save($args)
        {
            if (is_array($args['fields'])) {
                foreach ($args['fields'] as $id) {
                    if ($fs) {
                        $field_string .= ',';
                    }
                    $field_string .= $id;
                    $fs++;
                }
            }
            $args['fields'] = $field_string;

            $bID = $this->bID;
            $c = $this->getCollectionObject();
            if (is_object($c)) {
                $this->cID = $c->getCollectionID();
            }

            $args['num'] = ($args['num'] > 0) ? $args['num'] : 1;
            $args['cThis'] = ($args['cParentID'] == $this->cID) ? '1' : '0';
            $args['cParentID'] = ($args['cParentID'] == 'OTHER') ? $args['cParentIDValue'] : $args['cParentID'];
            $args['truncateSummaries'] = ($args['truncateSummaries']) ? '1' : '0';
            $args['displayFeaturedOnly'] = ($args['displayFeaturedOnly']) ? '1' : '0';
            $args['displayAliases'] = ($args['displayAliases']) ? '1' : '0';
            $args['truncateChars'] = intval($args['truncateChars']);
            $args['paginate'] = intval($args['paginate']);

            parent::save($args);

        }

        public function getRssUrl($b, $tool = 'rss')
        {
            $uh = Loader::helper('concrete/urls');
            if(!$b) return '';
            $btID = $b->getBlockTypeID();
            $bt = BlockType::getByID($btID);
            $c = $b->getBlockCollectionObject();
            $a = $b->getBlockAreaObject();
            $rssUrl = $uh->getBlockTypeToolsURL($bt)."/" . $tool . "?bID=".$b->getBlockID()."&amp;cID=".$c->getCollectionID()."&amp;arHandle=" . $a->getAreaHandle();

            return $rssUrl;
        }

        public static function getList($filters = array())
        {
            $db = Loader::db();
            $q = 'select akID from AttributeKeys inner join AttributeKeyCategories on AttributeKeys.akCategoryID = AttributeKeyCategories.akCategoryID where akCategoryHandle = ?';
            foreach ($filters as $key => $value) {
                $q .= ' and ' . $key . ' = ' . $value . ' ';
            }
            $r = $db->Execute($q, array('Collection'));
            $list = array();
            while ($row = $r->FetchRow()) {

                Loader::model('attribute/categories/collection');
                $c1a = CollectionAttributeKey::getByID($row['akID']);
                if (is_object($c1a)) {
                    $list[] = $c1a;
                }
            }
            $r->Close();

            return $list;
        }

    }
