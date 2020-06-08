<?php  
namespace Concrete\Package\Problog\Block\ProblogDateArchive;

use \Concrete\Core\Block\BlockController;
use Loader;
use \Concrete\Core\Page\Type\Type as CollectionType;
use Page;
use View;

class Controller extends BlockController
{

    protected $btTable = 'btProBlogDateArchive';
    protected $btInterfaceWidth = "500";
    protected $btInterfaceHeight = "350";

    protected $btExportPageColumns = array('targetCID');

    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;

    public $helpers =  array('navigation');

    public $page_type = 'pb_post';

    /**
	 * Used for localization. If we want to localize the name/description we have to include this
	*/
    public function getBlockTypeDescription()
    {
        return t("Displays month archive for pages");
    }

    public function getBlockTypeName()
    {
        return t("ProBlog Date Archive");
    }

    public function view()
    {
        $ct = CollectionType::getByHandle('pb_post');
        $ctID = $ct->getPageTypeID();

        if ($this->targetCID > 0) {
            $target = Page::getByID($this->targetCID);
            $this->set('target',$target);
        } else {
            $target = Page::getByPath('/blogsearch');
            $this->set('target',$target);
        }
        if (!$this->page_type) {$this->page_type = 'pb_post';}
        $page_type = trim($this->page_type);
        $query = "SELECT MIN(cv.cvDatePublic) as firstPost
			FROM CollectionVersions cv
            INNER JOIN Pages pp ON cv.cID = pp.cID
			INNER JOIN PageTypes pt ON pp.ptID = pt.ptID
			WHERE pt.ptHandle = ? and cv.cvIsApproved = 1
			AND cv.cvDatePublic < CURDATE()
            ORDER BY firstPost ASC";

        $db = Loader::db();
        $firstPost = $db->getOne($query,array($page_type));

        if (strlen($firstPost)) {

            $firstPost = new \DateTime($firstPost);
            $this->set('firstPost',$firstPost);
        }

        $this->set('numMonths',$this->numMonths);
        $this->set('navigation',loader::helper('navigation'));
    }

    public function check_date($date)
    {
        $page_type = trim($this->page_type);
        $query = "SELECT cv.cvDatePublic
			FROM CollectionVersions cv
            INNER JOIN Pages pp ON cv.cID = pp.cID
			INNER JOIN PageTypes pt ON pp.ptID = pt.ptID
			WHERE pt.ptHandle = '$page_type' and cv.cvIsApproved = 1 and DATE_FORMAT(cv.cvDatePublic,'%Y-%m') = ?";

        $db = Loader::db();
        $posts = $db->getOne($query,array($date));
        if ($posts) {
            return 1;
        } else {
            return 0;
        }
    }

    public function save($args)
    {
        parent::save($args);
    }
}

if (!function_exists('date_diff')) {
    class DateInterval
    {
        public $y;
        public $m;
        public $d;
        public $h;
        public $i;
        public $s;
        public $invert;

        public function format($format)
        {
            $format = str_replace('%R%y', ($this->invert ? '-' : '+') . $this->y, $format);
            $format = str_replace('%R%m', ($this->invert ? '-' : '+') . $this->m, $format);
            $format = str_replace('%R%d', ($this->invert ? '-' : '+') . $this->d, $format);
            $format = str_replace('%R%h', ($this->invert ? '-' : '+') . $this->h, $format);
            $format = str_replace('%R%i', ($this->invert ? '-' : '+') . $this->i, $format);
            $format = str_replace('%R%s', ($this->invert ? '-' : '+') . $this->s, $format);

            $format = str_replace('%y', $this->y, $format);
            $format = str_replace('%m', $this->m, $format);
            $format = str_replace('%d', $this->d, $format);
            $format = str_replace('%h', $this->h, $format);
            $format = str_replace('%i', $this->i, $format);
            $format = str_replace('%s', $this->s, $format);

            return $format;
        }
    }

    function date_diff(DateTime $date1, DateTime $date2)
    {
        $diff = new DateInterval();
        if ($date1 > $date2) {
            $tmp = $date1;
            $date1 = $date2;
            $date2 = $tmp;
            $diff->invert = true;
        }

        $diff->y = ((int) $date2->format('Y')) - ((int) $date1->format('Y'));
        $diff->m = ((int) $date2->format('n')) - ((int) $date1->format('n'));
        if ($diff->m < 0) {
            $diff->y -= 1;
            $diff->m = $diff->m + 12;
        }
        $diff->d = ((int) $date2->format('j')) - ((int) $date1->format('j'));
        if ($diff->d < 0) {
            $diff->m -= 1;
            $diff->d = $diff->d + ((int) $date1->format('t'));
        }
        $diff->h = ((int) $date2->format('G')) - ((int) $date1->format('G'));
        if ($diff->h < 0) {
            $diff->d -= 1;
            $diff->h = $diff->h + 24;
        }
        $diff->i = ((int) $date2->format('i')) - ((int) $date1->format('i'));
        if ($diff->i < 0) {
            $diff->h -= 1;
            $diff->i = $diff->i + 60;
        }
        $diff->s = ((int) $date2->format('s')) - ((int) $date1->format('s'));
        if ($diff->s < 0) {
            $diff->i -= 1;
            $diff->s = $diff->s + 60;
        }

        return $diff;
    }
}
