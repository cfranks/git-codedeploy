<?php  
namespace Concrete\Package\Problog\Attribute\MultiUserPicker;

use Loader;
use \Concrete\Core\Attribute\DefaultController;
use URL;
use UserInfo;

class Controller extends DefaultController
{

    protected $searchIndexFieldDefinition = array(
        'type' => 'string',
        'options' => array('default' => null, 'notnull' => false)
    );
    
    public function form() {
		if (is_object($this->attributeValue)) {
			$value = $this->getDataValues();
			$ul = explode(', ',$value);
		}

		if(count($ul)==1 && $ul[0]==''){
			$ul = null;
		}
		
		$fieldName2 ='akID['.$this->attributeKey->getAttributeKeyID().']';
		$fieldName = $this->attributeKey->getAttributeKeyID();
		
		$html = '';
		$html .= '<table id="ccmUserSelect' . $fieldName . '" class="ccm-results-list table table-condensed" cellspacing="0" cellpadding="0" border="0">';
		$html .= '<tr>';
		$html .= '<th>' . t('Username') . '</th>';
		$html .= '<th>' . t('Email Address') . '</th>';
		$html .= '<th><a class="ccm-user-select-item dialog-launch" dialog-append-buttons="true" dialog-width="90%" dialog-height="70%" dialog-modal="false" dialog-title="' . t('Choose User') . '" href="'. URL::to('/ccm/system/dialogs/user/search') . '"><img src="' . ASSETS_URL_IMAGES . '/icons/add.png" width="16" height="16" /></a></th>';
		$html .= '</tr><tbody id="ccmUserSelect' . $fieldName . '_body" >';
		for ($i = 0; $i < count($ul); $i++ ) {
			$ui = UserInfo::getByID($ul[$i]);

			if($ul[$i] != '' && $ui->uID){
				$class = $i % 2 == 0 ? 'ccm-row-alt' : '';
				$html .= '<tr id="ccmMultiUserSelect' . $fieldName . '_' . $ui->getUserID() . '" class="ccm-list-record line ' . $class . '">';
				$html .= '<td><input type="hidden" name="' . $fieldName2 . '[]" value="' . $ui->getUserID() . '" />' . $ui->getUserName() . '</td>';
				$html .= '<td>' . $ui->getUserEmail() . '</td>';
				$html .= '<td><a href="javascript:void(0)" class="ccm-user-list-clear"><img src="' . ASSETS_URL_IMAGES . '/icons/close.png" width="16" height="16" class="ccm-user-list-clear-button" /> &nbsp;Remove</a>';
				$html .= '</tr>';		
			}
		}
	
		if($i==0){
			$html .= '<tr class="ccm-user-selected-item-none"><td colspan="3">' . t('No users selected.') . '</td></tr>';
		}
		$html .= '</tbody></table><script type="text/javascript">
		$(function() {
			$("#ccmUserSelect' . $fieldName . ' .ccm-user-select-item").dialog();
			$("a.ccm-user-list-clear").click(function() {
				$(this).parents(\'tr\').remove();
			});

			$("#ccmUserSelect' . $fieldName . ' .ccm-user-select-item").on(\'click\', function() {
				ConcreteEvent.subscribe(\'UserSearchDialogSelectUser\', function(e, data) {
					var uID = data.uID, uName = data.uName, uEmail = data.uEmail;
					e.stopPropagation();
					$("tr.ccm-user-selected-item-none").hide();
					if ($("#ccmUserSelect' . $fieldName . '_" + uID).length < 1) {
						var html = "";
						html += "<tr id=\"ccmUserSelect' . $fieldName . '_" + uID + "\" class=\"ccm-list-record\"><td><input type=\"hidden\" name=\"' . $fieldName2 . '[]\" value=\"" + uID + "\" />" + uName + "</td>";
						html += "<td>" + uEmail + "</td>";
						html += "<td><a href=\"javascript:void(0)\" class=\"ccm-user-list-clear\"><img src=\"' . ASSETS_URL_IMAGES . '/icons/close.png\" width=\"16\" height=\"16\" class=\"ccm-user-list-clear-button\" /></a>";
						html += "</tr>";
						$("#ccmUserSelect' . $fieldName . '_body").append(html);
					}
					$("a.ccm-user-list-clear").click(function() {
						$(this).parents(\'tr\').remove();
					});
				});
				ConcreteEvent.subscribe(\'UserSearchDialogAfterSelectUser\', function(e) {
					jQuery.fn.dialog.closeTop();
				});
			});
		});

		</script>';
		echo $html;
		
	}

    public function saveValue($obj) {
	
		$uIDs = $obj;

		$uIDarray = '';
		if(is_array($uIDs)){
			foreach($uIDs as $uID){
				if($i){ $uIDarray .= ', '; }
				$uIDarray .= $uID ;
				$i++;
			}
		}else{
			$uIDarray = $uIDs;
		}

		$db = Loader::db();
		$db->Replace('atDefault', array('avID' => $this->getAttributeValueID(), 'value' => $uIDarray), 'avID', true);
	}
	
	public function getSearchIndexValue(){
		$ul = array();
		$value = $this->getDataValues();
		return $value;
	}
	
	private function getDataValues(){
		$db = Loader::db();
		$value = $db->GetOne("select value from atDefault where avID = ?", array($this->getAttributeValueID()));
		return $value;
	}
	
	public function getValue() {
		$ul = array();
		$value = $this->getDataValues();
		if($value){
			$ul = explode(', ',$value);
		}
		//if you desire, here is usage example to toss all users into an array
		//$users = array();
		//for ($i = 0; $i < count($ul); $i++ ) {
		//	$users[] = UserInfo::getByID($ul[$i]);	
		//}
		//return $users;
		return $ul;
	}
	
	public function saveForm($data) { 
		$this->saveValue($data);
	}
	
	public function deleteValue() {
		$db = Loader::db();
		$db->Execute('delete from atDefault where avID = ?', array($this->getAttributeValueID()));
	}
	
	public function deleteKey() {
		$db = Loader::db();
		$arr = $this->attributeKey->getAttributeValueIDList();
		foreach($arr as $id) {
			$db->Execute('delete from atDefault where avID = ?', array($id));
		}
	}
	
	public function getDisplayValue() {
		Loader::model('userinfo');
		$values = $this->getValue();
		$html = '';
		if(is_array($values)){
			foreach($values as $id){
				$user = UserInfo::getByID($id);
				if($i){
					$html .= ',';
				}
				if($user){
				$html .= $user->getUserFirstName();
				$i++;
				}
			}
		}
		return $html;
	}

}
