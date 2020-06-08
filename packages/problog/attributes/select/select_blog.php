<?php  
namespace Concrete\Package\Problog\Attribute\Select;

use Concrete\Attribute\Select\Controller as Concrete5Select;
use Concrete\Attribute\Select\Option as Option;
use Concrete\Attribute\Select\OptionList as OptionList;

use Loader;

class SelectBlog extends Concrete5Select
{
    public $attributeKey;

    public function getOptionUsageArray($parentPage = false, $limit = 9999)
    {
        $db = Loader::db();
        $q = "select atSelectOptions.value, atSelectOptionID, count(atSelectOptionID) as total from Pages inner join CollectionVersions on (Pages.cID = CollectionVersions.cID and CollectionVersions.cvIsApproved = 1) inner join CollectionAttributeValues on (CollectionVersions.cID = CollectionAttributeValues.cID and CollectionVersions.cvID = CollectionAttributeValues.cvID) inner join atSelectOptionsSelected on (atSelectOptionsSelected.avID = CollectionAttributeValues.avID) inner join atSelectOptions on atSelectOptionsSelected.atSelectOptionID = atSelectOptions.ID";
        
        if (is_object($parentPage)) {
            $q .= " inner join PagePaths on Pages.cID=PagePaths.cID";
        }
        
        $q .= " where Pages.cIsActive = 1 and CollectionAttributeValues.akID = ? ";
        
        $v = array($this->attributeKey->getAttributeKeyID());
        if (is_object($parentPage)) {
            $path = $parentPage->getCollectionPath();
            $q .= "and PagePaths.cPath LIKE '$path%'";
        }
        $q .= " group by atSelectOptionID order by total desc limit " . $limit;
        $r = $db->Execute($q, $v);
        $list = new OptionList();
        $i = 0;
        while ($row = $r->FetchRow()) {
            $opt = new Option($row['atSelectOptionID'], $row['value'], $i, $row['total']);
            $list->add($opt);
            $i++;
        }

        return $list;
    }

    public function setAttributeKey($ak)
    {
        $this->attributeKey = $ak;
    }

}
