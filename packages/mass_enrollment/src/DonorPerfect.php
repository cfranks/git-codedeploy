<?php namespace Concrete\Package\MassEnrollment\Src;

use Config;

class DonorPerfect
{

    protected static $apiUrl = 'https://www.donorperfect.net/prod/xmlrequest.asp';
	protected static $user;
    protected static $password;
    protected static $apiKey;

	public static function setLogin ($username = 'InformaticsAdmin', $pass = 'Lh0N9QLCwQlT', $apiKey = 'MUAX2T8eCWBDJWC4WmK7nM0RmFtYSOwiX1hBWebcLAh1zp7iQzISmkxzximOfTyW48p9wEOGiOuDynjXJK1Sll4%2fKBD8E%2fzqtQfVIqTRCDHaFtWe%2bx4bx0uw2u6r9Q%2fV')
	{
		if(!empty(Config::get('mass_enrollment::integration'))) {
			if(Config::get('mass_enrollment::integration.flagTestMode') == 0) {
				//When log in as API flag is turned on and flag test mode is turned off.
				if(Config::get('mass_enrollment::integration.donorPerfectLogin') == 0) {
					$username = Config::get('mass_enrollment::integration.donorPerfectUsername');
					$pass = Config::get('mass_enrollment::integration.donorPerfectPassword');
				} else {
					$apiKey = Config::get('mass_enrollment::integration.donorPerfectApiKey');
				}
			} else {
				//When log in as API flag is turned on we and flag test mode is turned on.
				if(Config::get('mass_enrollment::integration.donorPerfectLogin') == 0) {
					$username = Config::get('mass_enrollment::integration.testDonorPerfectUsername');
					$pass = Config::get('mass_enrollment::integration.testDonorPerfectPassword');
				} else {
					$apiKey = Config::get('mass_enrollment::integration.testDonorPerfectAPI');
				}
			}
			
		}

		self::$user = $username;
		self::$password = $pass;
		self::$apiKey = $apiKey;
	}
		
	public static function api($action='', $params = '',$f=0)
	{
		$apiResponse = null;
		self::setLogin();
		
		
		if(Config::get('mass_enrollment::integration.donorPerfectLogin') == 0) {
			$apiQuery = '?action=' .$action. ($params ? '&params='.$params : '').'&login='. self::$user . '&pass=' . self::$password;
		} else {
			$apiQuery = '?apikey='.self::$apiKey.'&action=' .$action. ($params ? '&params='.$params : '');
		}	
		// $apiQuery = urlencode($apiQuery);
		if (strlen(self::$apiUrl . $apiQuery) > 2048)
		{
			throw new \Exception('DP API Call Exceeds Maximum Length');
		}

		$apiConnection = curl_init(self::$apiUrl . $apiQuery);
		curl_setopt($apiConnection, CURLOPT_HTTPHEADER, ['Content-Type: text/xml']);
		curl_setopt($apiConnection, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($apiConnection, CURLOPT_TIMEOUT, 20);
		curl_setopt($apiConnection, CURLOPT_SSL_VERIFYPEER, FALSE);
		$apiResponse = '';
		$apiResponseLog = $apiResponse = curl_exec($apiConnection);
		curl_close ($apiConnection);
		$apiResponse = preg_replace('|(?Umsi)(value=\'DATE:.*\\R*\')|', 'value=\'\'', $apiResponse);
		$apiResponse = json_decode(json_encode(simplexml_load_string($apiResponse)), true);
		if (is_array($apiResponse)) {
			$apiResponse = self::parseApiResponse($apiResponse);
		} elseif ( ! is_array($apiResponse))  {
			//throw new \Exception('Error connecting to DonorPerfect.');
		}
		
		return $apiResponse;
	}


	public static function donorSearch($params=[],$f=0)
	{
		$params= '@donor_id='.(empty($params['donor_id']) ? 'null' : 'N%27'.self::getSpecialCharactersValue($params, 'donor_id').'%27').
			 ',%20@last_name='.(empty($params['last_name']) ? 'null' : 'N%27'.self::getSpecialCharactersValue($params, 'last_name').'%27').
			 ',%20@first_name='.(empty($params['first_name']) ? 'null' : 'N%27'.self::getSpecialCharactersValue($params, 'first_name').'%27').
			 ',%20@opt_line='.(empty($params['opt_line']) ? 'null' : 'N%27'.self::getSpecialCharactersValue($params, 'opt_line').'%27').
			 ',%20@address='.(empty($params['address']) ? 'null' : 'N%27'.self::getSpecialCharactersValue($params, 'address').'%27').
			 ',%20@city='.(empty($params['city']) ? 'null' : 'N%27'.self::getSpecialCharactersValue($params, 'city').'%27').
			 ',%20@state='.(empty($params['state']) || $params['country'] != 'US'  ? 'null' : 'N%27'.self::getSpecialCharactersValue($params, 'state').'%27').
			 ',%20@zip='.(empty($params['zip']) ? 'null' : 'N%27'.self::getSpecialCharactersValue($params, 'zip').'%27').
			 ',%20@country='.(empty($params['country']) ? 'null' : 'N%27'.self::getSpecialCharactersValue($params, 'country').'%27').
			 ',%20@filter_id=null,%20'.
			 '@user_id=null';
		return self::api('dp_donorsearch',$params);
    	}
    
	public static function searchDonor($email)
	{
		return self::api("SELECT%20dp.donor_id%20FROM%20dp%20WHERE%20dp.email%20=%20%27$email%27");
	}
	
	public static function getDonorDetails($donor_id)
	{
		return self::api("SELECT%20*%20FROM%20dp%20WHERE%20dp.donor_id%20=%20%27$donor_id%27");
	}
	
	public static function getExistingDonorAddress3($donor_id)
	{
		return self::api("SELECT%20dp.address3%20FROM%20dp%20WHERE%20dp.donor_id%20=%20%27$donor_id%27");	
	}
	
	public static function checkLocation($donor_id)
	{
		$donorUdfObj = self::api("SELECT%20*%20FROM%20dpudf%20WHERE%20dpudf.donor_id%20=%20%27$donor_id%27");	
		return $donorUdfObj->location;
	}
	
	public static function checkNameGroup($donor_id)
	{
		$donorUdfObj = self::api("SELECT%20name_group%20FROM%20dpudf%20WHERE%20dpudf.donor_id%20=%20%27$donor_id%27");	
		return $donorUdfObj->name_group;
	}
    
    	public static function getDonorContact($donor_id)
	{
		return self::api("SELECT%20dp.MOBILE_PHONE,%20dp.BUSINESS_PHONE,%20du.altphone4%20FROM%20dp%20LEFT%20JOIN%20dpudf%20du%20ON%20du.donor_id=dp.donor_id%20WHERE%20dp.donor_id%20=%20%27$donor_id%27");
	}
		
	public static function saveDonor($params=[],$f=1)
	{
		$params= '@donor_id=N%27'.self::getSpecialCharactersValue($params,'donor_id',0).'%27,%20'.
			 '@first_name='.(empty($params['first_name']) ? 'null' : 'N%27'.self::getSpecialCharactersValue($params, 'first_name').'%27').
			 ',%20@last_name='.(empty($params['last_name']) ? 'null' : 'N%27'.self::getSpecialCharactersValue($params, 'last_name').'%27').
			 ',%20@middle_name='.(empty($params['middle_name']) ? 'null' : 'N%27'.self::getSpecialCharactersValue($params, 'middle_name').'%27').
			 ',%20@suffix='.(empty($params['suffix']) ? 'null' : 'N%27'.self::getSpecialCharactersValue($params, 'suffix').'%27').
			 ',%20@title='.(empty($params['title']) ? 'null' : 'N%27'.self::getSpecialCharactersValue($params, 'title').'%27').
			 ',%20@salutation='.(empty($params['salutation']) ? 'null' : 'N%27'.self::getSpecialCharactersValue($params, 'salutation').'%27').
			 ',%20@prof_title='.(empty($params['prof_title']) ? 'null' : 'N%27'.self::getSpecialCharactersValue($params, 'prof_title').'%27').
			 ',%20@opt_line=N%27'.self::getSpecialCharactersValue($params, 'opt_line').'%27,%20'.
			 '@address=N%27'.self::getSpecialCharactersValue($params, 'address').'%27,%20'.
			 '@address2=N%27'.self::getSpecialCharactersValue($params, 'address2').'%27,%20'.
			 '@address3=N%27'.self::getSpecialCharactersValue($params, 'address3').'%27,%20'.
			 '@city=N%27'.self::getSpecialCharactersValue($params, 'city').'%27,%20'.
			 '@state=N%27'.self::getSpecialCharactersValue($params, 'state').'%27,%20'.
			 '@zip=N%27'.self::getSpecialCharactersValue($params, 'zip').'%27,%20'.
			 '@country=N%27'.self::getSpecialCharactersValue($params, 'country').'%27,%20'.
			 '@address_type=N%27'.self::getSpecialCharactersValue($params, 'address_type').'%27,%20'.
			 '@home_phone=N%27'.self::getSpecialCharactersValue($params, 'home_phone').'%27,%20'.
			 '@business_phone=N%27'.self::getSpecialCharactersValue($params, 'business_phone').'%27,%20'.
			 '@fax_phone=N%27'.self::getSpecialCharactersValue($params, 'fax_phone').'%27,%20'.
			 '@mobile_phone=N%27'.self::getSpecialCharactersValue($params, 'mobile_phone').'%27,%20'.
			 '@email=N%27'.self::getSpecialCharactersValue($params, 'email').'%27,%20'.
			 '@org_rec=N%27'.self::getSpecialCharactersValue($params, 'org_rec').'%27,%20'.
			 '@donor_type=N%27'.self::getSpecialCharactersValue($params, 'donor_type').'%27,%20'.
			 '@nomail=N%27'.self::getSpecialCharactersValue($params, 'nomail').'%27,%20'.
			 '@nomail_reason=N%27'.self::getSpecialCharactersValue($params, 'nomail_reason').'%27,%20'.
			 '@narrative=N%27'.self::getSpecialCharactersValue($params, 'narrative').'%27,%20'.
			 '@user_id='.'%27API%27';	
		return self::api('dp_savedonor', $params);
    }
    
	public static function gifts($params=[])
	{
		$params = self::convertParams([
			'donor_id' => self::getInteger($params, 'donor_id'),
		]);
		return self::api('dp_gifts&params=' . $params);
	}
    
    
	public static function saveGift($params=[])
	{
		$params = '@gift_id=' . self::getInteger($params, 'gift_id', 0) . 
		',%20@donor_id=' . self::getInteger($params, 'donor_id') . 
		',%20@record_type=N%27'.self::getValue($params, 'record_type', 'G').
		'%27,%20@gift_date=%27'.self::getValue($params, 'gift_date') .
		'%27,%20@amount='.self::getValue($params, 'amount').
		',%20@gl_code='.($params['gl_code'] ? 'N%27'.self::getValue($params, 'gl_code').'%27' : 'null').
		',%20@solicit_code=NULL,%20@sub_solicit_code=NULL,%20@campaign=NULL,%20@gift_type=N%27'.self::getValue($params, 'gift_type','ON').'%27,%20@split_gift=%27N%27,%20@pledge_payment=%27N%27,'.
		'%20@reference='.self::getValue($params, 'reference').
		',%20@memory_honor=NULL,%20@gfname=NULL,%20@glname=NULL,%20@fmv=0,%20@batch_no=0,%20@gift_narrative=NULL,%20@ty_letter_no=NULL,%20@glink='.($params['glink'] ? '%27'.self::getValue($params, 'glink').'%27' : 'null').',%20@plink=NULL,%20@nocalc=%27N%27,%20@old_amount=NULL,%20@receipt=%27N%27,%20@user_id=%27API%27,%20@gift_aid_date=NULL,%20@gift_aid_amt=NULL,%20@gift_aid_eligible_g=NULL,%20@currency=%27USD%27';
		return self::api('dp_savegift', $params);
	}
	
	public static function savePledge($params=[])
	{
		$params = self::convertParams([
			'gift_id' => self::getInteger($params, 'gift_id', 0),
			'donor_id' => self::getInteger($params, 'donor_id'),
			'gift_date' => self::getValue($params, 'gift_date'),
			'start_date' => self::getValue($params, 'start_date'),
			'total' => self::getValue($params, 'total'),
			'bill' => self::getValue($params, 'bill'),
			'frequency' => self::getValue($params, 'frequency', 'M'),
			'reminder' => self::getValue($params, 'reminder', 'N'),
			'gl_code' => self::getValue($params, 'gl_code'),
			'solicit_code' => self::getValue($params, 'solicit_code'),
			'initial_payment' => self::getValue($params, 'initial_payment'),
			'sub_solicit_code' => self::getValue($params, 'sub_solicit_code'),
			'writeoff_amount' => self::getValue($params, 'writeoff_amount'),
			'writeoff_date' => self::getValue($params, 'writeoff_date'),
			'user_id' => self::getValue($params, 'user_id'),
			'campaign' => self::getValue($params, 'campaign'),
			'membership_type' => self::getValue($params, 'membership_type'),
			'membership_level' => self::getValue($params, 'membership_level'),
			'membership_enr_date' => self::getValue($params, 'membership_enr_date'),
			'membership_exp_date' => self::getValue($params, 'membership_exp_date'),
			'membership_link_id' => self::getValue($params, 'membership_link_id'),
			'address_id' => self::getInteger($params, 'address_id'),
			'gift_narrative' => self::getValue($params, 'gift_narrative'),
			'ty_letter_no' => self::getValue($params, 'ty_letter_no'),
			'vault_id' => self::getValue($params, 'vault_id'),
			'receipt_delivery_g' => self::getValue($params, 'receipt_delivery_g'),
			'contact_id' => self::getInteger($params, 'contact_id'),
		]);
		return self::api('dp_savepledge&params=' . $params);
	}
	
	public static function saveOtherInfo($params=[])
	{
		$params = self::convertParams([
			'other_id' => self::getInteger($params, 'other_id', 0),
			'donor_id' => self::getInteger($params, 'donor_id'),
			'other_date' => self::getValue($params, 'other_date'),
			'comments' => self::getValue($params, 'comments'),
			'user_id' => self::getValue($params, 'user_id'),
		]);
		return self::api('dp_saveotherinfo&params=' . $params);
	}
	
	public static function saveUdfXml($params=[],$f=1)
	{	
		$params = '@matching_id='.self::getInteger($params, 'matching_id').
			  ',%20@field_name=N%27'.self::getValue($params, 'field_name').
			  '%27,%20@data_type=N%27'.self::getValue($params, 'data_type').
			  '%27,%20@char_value='.(empty($params['char_value']) ? 'null' : 'N%27'.self::getValue($params, 'char_value').'%27').
			  ',%20@date_value='.(empty($params['date_value']) ? 'null' : 'N%27'.self::getValue($params, 'date_value').'%27').
			  ',%20@number_value='.(empty($params['number_value']) ? 'null' : 'N%27'.self::getValue($params, 'number_value').'%27').
			  ',%20@user_id=%27API%27';
		if($f == 2) {
			return self::api('dp_save_udf_xml', $params,1);	
		}
		return self::api('dp_save_udf_xml', $params);
	}
    	

	public static function saveFlagXml($params=[])
	{
		$params = self::convertParams([
			'donor_id' => self::getInteger($params, 'donor_id'),
			'flag' => self::getValue($params, 'flag'),
			'user_id' => self::getValue($params, 'user_id'),
		]);
		return self::api('dp_saveflag_xml&params=' . $params);
	}
    
    
	public static function deleteFlagsXml($params=[])
	{
		$params = self::convertParams([
			'donor_id' => self::getInteger($params, 'donor_id'),
			'user_id' => self::getValue($params, 'user_id'),
		]);
		return self::api('dp_delflags_xml&params=' . $params);
	}
	
	public static function saveContact($params=[])
	{
		$params = self::convertParams([
			'contact_id' => self::getInteger($params, 'contact_id', 0),
			'donor_id' => self::getInteger($params, 'donor_id'),
			'activity_code' => self::getValue($params, 'activity_code'),
			'mailing_code' => self::getValue($params, 'mailing_code'),
			'by_whom' => self::getValue($params, 'by_whom'),
			'contact_date' => self::getValue($params, 'contact_date'),
			'due_date' => self::getValue($params, 'due_date'),
			'due_time' => self::getValue($params, 'due_time'),
			'completed_date' => self::getValue($params, 'completed_date'),
			'comment' => self::getValue($params, 'comment'),
			'document_path' => self::getValue($params, 'document_path'),
			'user_id' => self::getValue($params, 'user_id'),
		]);
		return self::api('dp_savecontact&params=' . $params);
	}
	
	public static function paymentMethodInsert($params=[])
	{
		$params = self::convertParams([
			'customer_vault_id' => self::getInteger($params, 'customer_vault_id', 0),
			'donor_id' => self::getInteger($params, 'donor_id'),
			'is_default' => self::getInteger($params, 'is_default', 0),
			'account_type' => self::getValue($params, 'account_type'),
			'dp_payment_method_type_id' => self::getValue($params, 'dp_payment_method_type_id'),
			'card_number_last_four' => self::getValue($params, 'card_number_last_four'),
			'card_expiration_date' => self::getValue($params, 'card_expiration_date'),
			'bank_account_number_last_four' => self::getValue($params, 'bank_account_number_last_four'),
			'name_on_account' => self::getValue($params, 'name_on_account'),
			'created_date' => self::getValue($params, 'created_date'),
			'modified_date' => self::getValue($params, 'modified_date'),
			'import_id' => self::getValue($params, 'import_id'),
			'created_by' => self::getValue($params, 'created_by'),
			'modified_by' => self::getValue($params, 'modified_by'),
			'selected_currency' => self::getValue($params, 'selected_currency'),
		]);
		return self::api('dp_paymentmethod_insert&params=' . $params);
    }
    
	public static function listFunds()
	{
		return self::api("
			SELECT
				funds.code,
				funds.description,
				funds.goal,
				funds.comments,
				funds.start_date,
				funds.end_date,
				funds.solicit_code2,
				funds.campaign,
				funds.created_date,
				category.code AS category_code,
				category.description AS category_description,
				category.goal AS category_goal,
				category.comments AS category_comments,
				category.start_date AS category_start_date,
				category.end_date AS category_end_date,
				category.created_date AS category_created_date,
				campaign.code AS campaign_code,
				campaign.description AS campaign_description,
				campaign.goal AS campaign_goal,
				campaign.comments AS campaign_comments,
				campaign.start_date AS campaign_start_date,
				campaign.end_date AS campaign_end_date,
				campaign.created_date AS campaign_created_date
			FROM dpcodes AS funds
			LEFT JOIN dpcodes AS category ON category.field_name = 'SOLICIT_CODE' AND category.code = funds.solicit_code2
			LEFT JOIN dpcodes AS campaign ON campaign.field_name = 'CAMPAIGN' AND campaign.code = funds.campaign
			WHERE
				funds.field_name = 'SUB_SOLICIT_CODE' AND
				funds.inactive != 'Y' AND
				(category.code IS NULL OR category.inactive != 'Y') AND
				(campaign.code IS NULL OR campaign.inactive != 'Y')
			ORDER BY funds.code ASC
		");
    }
    
	public static function getFund($code, $returnTotalGifts=false, $returnTotalGoalZero=true)
	{
		$code = (is_array($code)) ? $code : [$code];
		$funds = self::listFunds();
		$fund = array_filter($funds, function($fund) use ($code)
		{
			return (in_array($fund->code, $code));
		});
		if (count($code) === 1)
		{
			$fund = ( ! empty($fund)) ? array_shift($fund) : null;
			if ( ! empty($fund) && $returnTotalGifts && ($returnTotalGoalZero === true || $fund->goal > 0))
			{
				$totalGifts = self::api("SELECT SUM(amount) AS total FROM dpgift WHERE sub_solicit_code = '{$fund->code}'");
				if ( ! empty($totalGifts))
				{
					$fund->total = $totalGifts->total;
					$fund->remaining = number_format($fund->goal - $fund->total, 2, '.', '');
					if ($fund->remaining < 0) $fund->remaining = 0;
					if ($fund->goal > 0)
					{
						$fund->remainingPercentage = floor((1 - ($fund->total / $fund->goal)) * 100);
						if ($fund->remainingPercentage < 0) $fund->remainingPercentage = 0;
						elseif ($fund->remainingPercentage > 100) $fund->remainingPercentage = 100;
					}
					else
					{
						$fund->remainingPercentage = 0;
					}
				}
			}
		}
		return $fund;
    }
    
	public static function listGLs()
	{
		return self::api("
			SELECT
				gl.code,
				gl.description,
				gl.created_date,
				income_account.code AS income_account_code,
				income_account.description AS income_account_description,
				income_account.created_date AS income_account_created_date,
				cash_account.code AS cash_account_code,
				cash_account.description AS cash_account_description,
				cash_account.created_date AS cash_account_created_date
			FROM dpcodes AS gl
			LEFT JOIN dpcodes AS income_account ON income_account.field_name = 'ACCT_NUM' AND income_account.code = gl.acct_num
			LEFT JOIN dpcodes AS cash_account ON cash_account.field_name = 'CASHACT' AND cash_account.code = gl.cashact
			WHERE
				gl.field_name = 'GL_CODE' AND
				gl.inactive != 'Y' AND
				(income_account.code IS NULL OR income_account.inactive != 'Y') AND
				(cash_account.code IS NULL OR cash_account.inactive != 'Y')
			ORDER BY gl.code ASC
		");
    }
    
	public static function listClasses()
	{
		return self::api("
			SELECT
				class.code,
				class.description,
				class.created_date
			FROM dpcodes AS class
			WHERE
				class.field_name = 'CLASS' AND
				class.inactive != 'Y'
			ORDER BY class.code ASC
		");
    }
    
	public static function getClass($code)
	{
		$code = (is_array($code)) ? $code : [$code];
		$classes = self::listClasses();
		$class = array_filter($classes, function($class) use ($code)
		{
			return (in_array($class->code, $code));
		});
		return $class;
    }
    
	public static function getDonorData($id, $table='profile', $fields=null, $where=null, $orderBy=null)
	{
		$tableLookup = [
			'profile' => 'dp',
			'meta' => 'dpudf',
			'gifts' => 'dpgift',
			'pledges' => 'dpgift',
			'gifts_and_pledges' => 'dpgift',
			'flags' => 'dpflags',
			'links' => 'dplink',
			'addresses' => 'dpaddress',
			'contact_history' => 'dpcontact',
			'others' => 'dpotherinfo',
			'bio_options' => 'dpusermultivalues',
		];
		$giftsAndPledges = ($table === 'gifts_and_pledges');
		$wherePledge = ($table === 'pledges') ? 'AND record_type = \'P\'' : (($table === 'gifts') ? 'AND (record_type = \'G\' OR record_type = \'M\')' : '');
		$table = $tableLookup[$table];
		$join = '';
		$fields = ( ! empty($fields)) ? $fields : $table . '.*';
		$keyField = ($table === 'bio_options') ? 'matching_id' : 'donor_id';
		if ($giftsAndPledges)
		{
			$join = ' LEFT JOIN dpgiftudf ON dpgiftudf.gift_id = dpgift.gift_id';
			$fields .= ', dpgiftudf.class, dpgiftudf.eft_payment_type, dpgiftudf.eft_bank_name, dpgiftudf.eft_account, dpgiftudf.eft_routing, dpgiftudf.eft_expiration_year, dpgiftudf.eft_expiration_month, dpgiftudf.eft_recurring_id, dpgiftudf.anongift, dpgiftudf.gift_status';
		}
		$id = ( ! is_array($id)) ? $table . '.' . $keyField . ' = ' . $id : $table . '.' . $keyField . ' IN [' . implode(',', $id) . ']';
		if ( ! empty($where)) $where = 'AND '. $where;
		if ( ! empty($orderBy)) $orderBy = 'ORDER BY '. $orderBy;
		$response = self::api("
			SELECT
				{$fields}
			FROM {$table}
			{$join}
			WHERE
				{$id}
				{$where}
				{$wherePledge}
			{$orderBy}
		");
		return ($table === 'dp' || is_array($response)) ? $response : [$response];
    }
    
	public static function getGiftCustom($id, $fields=null, $where=null)
	{
		$table = 'dpgiftudf';
		$fields = ( ! empty($fields)) ? $fields : $table . '.*';
		$id = ( ! is_array($id)) ? $table . '.gift_id = ' . $id : $table . '.gift_id IN [' . implode(',', $id) . ']';
		if ( ! empty($where)) $where = 'AND '. $where;
		return self::api("
			SELECT
				{$fields}
			FROM {$table}
			WHERE
				{$id}
				{$where}
		");
    }
    
	public static function getContactHistoryCustom($id, $fields=null, $where=null)
	{
		$table = 'dpcontactudf';
		$fields = ( ! empty($fields)) ? $fields : $table . '.*';
		$id = ( ! is_array($id)) ? $table . '.contact_id = ' . $id : $table . '.contact_id IN [' . implode(',', $id) . ']';
		if ( ! empty($where)) $where = 'AND '. $where;
		return self::api("
			SELECT
				{$fields}
			FROM {$table}
			WHERE
				{$id}
				{$where}
		");
    }
    
	public static function getOtherCustom($id, $fields=null, $where=null)
	{
		$table = 'dpotherinfoudf';
		$fields = ( ! empty($fields)) ? $fields : $table . '.*';
		$id = ( ! is_array($id)) ? $table . '.other_id = ' . $id : $table . '.other_id IN [' . implode(',', $id) . ']';
		if ( ! empty($where)) $where = 'AND '. $where;
		return self::api("
			SELECT
				{$fields}
			FROM {$table}
			WHERE
				{$id}
				{$where}
		");
    }
    
	public static function getCodes($fieldName, $fields=null, $where=null)
	{
		$table = 'dpcodes';
		$fields = ( ! empty($fields)) ? $fields : $table . '.*';
		if ( ! empty($where)) $where = 'AND '. $where;
		return self::api("
			SELECT
				{$fields}
			FROM {$table}
			WHERE
				{$table}.field_name = '{$fieldName}'
				{$where}
		");
    }
    
	public static function listPledges($from, $to, $funds=null)
	{
		$funds = ( ! empty($funds) && ! is_array($funds)) ? $funds = [$funds] : null;
		$records = [];
		if (empty($records))
		{
			$pageSize = 500;
			$pageCount = 0;
			$pageStart = 1;
			$pageEnd = $pageSize;
			$dateRange = '(DATEPART(d, g.start_date) IN (';
			for ($i = $from; $i <= $to; $i++)
			{
				$dateRange .= $i . ',';
			}
			$dateRange = trim($dateRange, ',') . '))';
			$funds = ( ! empty($funds)) ? 'g.sub_solicit_code = \'' . implode('\' OR g.sub_solicit_code = \'', $funds) . '\'' : null;
			$fundFilter = ( ! empty($funds)) ? 'AND (' . $funds . ')' : '';
			while ($pageSize !== null)
			{
				$response = self::api("
					SELECT
						*
					FROM (
						SELECT
							ROW_NUMBER() OVER(ORDER BY d.first_name, d.middle_name, d.last_name ASC) AS row_number,
							d.donor_id,
							d.first_name,
							d.last_name,
							d.email,
							d.address,
							d.address2,
							d.city,
							d.state,
							d.zip,
							d.country,
							g.gift_id,
							g.gift_type,
							g.gift_date,
							g.start_date,
							g.bill AS amount,
							g.gl_code,
							g.solicit_code,
							g.sub_solicit_code,
							f.description AS sub_solicit_code_description,
							g.campaign,
							g.receipt,
							g.gift_narrative,
							dpgiftudf.class,
							c.description AS class_description,
							eft_payment_type,
							eft_bank_name,
							eft_account,
							eft_routing,
							eft_expiration_year,
							eft_expiration_month,
							eft_recurring_id,
							dpgiftudf.anongift
						FROM dpgift AS g
						LEFT JOIN dp AS d ON d.donor_id = g.donor_id
						LEFT JOIN dpgiftudf ON dpgiftudf.gift_id = g.gift_id
						LEFT JOIN dpcodes AS f ON f.code = g.sub_solicit_code AND f.field_name = 'SUB_SOLICITOR_CODE'
						LEFT JOIN dpcodes AS c ON c.code = dpgiftudf.class AND c.field_name = 'CLASS'
						WHERE
							g.record_type = 'P'
							AND g.frequency = 'M' /*M, ?, Q, S*/
							/*AND (g.writeoff_date IS NULL OR g.writeoff_date = '')*/
							AND dpgiftudf.gift_status = 'ACT'
							AND {$dateRange}
							{$fundFilter}
					) AS tmp
				WHERE tmp.row_number BETWEEN {$pageStart} AND {$pageEnd}
				");
				if (is_object($response) && ! empty($response->donor_id)) $response = [$response];
				if (is_array($response) && count($response) > 0)
				{
					$responseSize = count($response);
					for ($i=0;$i < $responseSize;$i++)
					{
						$records[] = $response[$i];
					}
				}
				else
				{
					$pageSize = null;
				}
				$pageCount++;
				$pageStart += $pageSize;
				$pageEnd += $pageSize;
			}
		}
		return $records;
    }
    
	public static function listGifts($from, $to, $onlyEFT=false, $funds=null)
	{
		$funds = ( ! empty($funds) && ! is_array($funds)) ? $funds = [$funds] : null;
		$records = [];
		if (empty($records))
		{
			$pageSize = 500;
			$pageCount = 0;
			$pageStart = 1;
			$pageEnd = $pageSize;
			$dateRange = ($from !== $to) ? '(g.gift_date >= \'' . $from . '\' AND g.gift_date <= \'' . $to . '\')' : '(g.gift_date = \'' . $from . '\')';
			$eftFilter = ($onlyEFT) ? 'AND (g.gift_type = \'EF\' OR u.eft_payment_type = \'EF\' OR g.gift_type = \'RGDD\') AND (u.eft_account IS NOT NULL AND u.eft_account != \'\') AND (u.eft_routing IS NOT NULL AND u.eft_routing != \'\')' : '';
			$funds = ( ! empty($funds)) ? 'g.sub_solicit_code = \'' . implode('\' OR g.sub_solicit_code = \'', $funds) . '\'' : null;
			$fundFilter = ( ! empty($funds)) ? 'AND (' . $funds . ')' : '';
			while ($pageSize !== null)
			{
				$response = self::api("
					SELECT
						*
					FROM (
						SELECT
							ROW_NUMBER() OVER(ORDER BY d.first_name, d.middle_name, d.last_name ASC) AS row_number,
							d.donor_id,
							d.first_name,
							d.last_name,
							d.home_phone,
							d.business_phone,
							d.mobile_phone,
							d.email,
							d.address,
							d.address2,
							d.city,
							d.state,
							d.zip,
							d.country,
							g.plink,
							g.gift_id,
							g.gift_type,
							g.gift_date,
							g.created_date,
							g.start_date,
							g.amount,
							g.gl_code,
							g.solicit_code,
							g.sub_solicit_code,
							f.description AS sub_solicit_code_description,
							g.campaign,
							g.receipt,
							g.gift_narrative,
							u.class,
							c.description AS class_description,
							u.eft_payment_type,
							u.eft_bank_name,
							u.eft_account,
							u.eft_routing,
							u.anongift,
							u.batch
						FROM dpgift AS g
						LEFT JOIN dp AS d ON d.donor_id = g.donor_id
						LEFT JOIN dpgiftudf AS u ON u.gift_id = g.gift_id
						LEFT JOIN dpcodes AS f ON f.code = g.sub_solicit_code AND f.field_name = 'SUB_SOLICITOR_CODE'
						LEFT JOIN dpcodes AS c ON c.code = u.class AND c.field_name = 'CLASS'
						WHERE g.record_type = 'G' AND {$dateRange} {$eftFilter} {$fundFilter}
					) AS tmp
				WHERE tmp.row_number BETWEEN {$pageStart} AND {$pageEnd}
				");
			
				if (is_object($response) && ! empty($response->donor_id)) $response = [$response];
				if (is_array($response) && count($response) > 0)
				{
					$responseSize = count($response);
					for ($i=0;$i < $responseSize;$i++)
					{
						$records[] = $response[$i];
					}
				}
				else
				{
					$pageSize = null;
				}
				$pageCount++;
				$pageStart += $pageSize;
				$pageEnd += $pageSize;
			}
		}
		return $records;
	}

    protected static function convertParams($params)
	{
		return implode(',', array_values($params));
    }
    
	protected static function getInteger($params, $name, $default = 'null')
	{
		$value = (array_key_exists($name, $params)) ? $params[$name] : null;
		if (is_string($value)) $value = trim($value);
		try {
			$value = ( ! is_null($value)) ? (int) $value : $default;
		}
		catch (\Exception $e)
		{
			return $e;
		}
		return $value;
    }
    
	protected static function getValue($params, $name, $default = 'null')
	{
		$value = (array_key_exists($name, $params)) ? $params[$name] : null;
		if (is_string($value)) $value = trim($value);
		return ( ! empty($value)) ? str_replace(["'", '"', '%', ' ','&','.','!','#','$','*','(',')','-','/','+',',',':',';','<','=','>','?'], ["''", '', '%25', '%20','%26','%2E','%21','%23','%24','%2A','%28','%29','%2D','%2F','%2B','%2C','%3A','%3B','%3C','%3D','%3E','%3F'], $value) : $default;
    }
    
    	protected static function getSpecialCharactersValue($params, $name, $default = '')
	{
		$value = (array_key_exists($name, $params)) ? $params[$name] : null;
		if (is_string($value)) $value = trim($value);
		return ( ! empty($value)) ? str_replace(["'", '"', '%', ' ','&','.','!','#','$','*','(',')','-','/','+',',',':',';','<','=','>','?'], ["''", '', '%25', '%20','%26','%2E','%21','%23','%24','%2A','%28','%29','%2D','%2F','%2B','%2C','%3A','%3B','%3C','%3D','%3E','%3F'], $value) : $default; 	
	}
    
    	protected static function getValueWithoutStringReplace($params, $name, $default = 'null')
	{
		$value = (array_key_exists($name, $params)) ? $params[$name] : null;
		if (is_string($value)) $value = trim($value);
		return ( ! empty($value)) ? str_replace(['"', '%', ' ','-','/','.',';','&'], ['', '', '','','','','',''], $value) : $default;
    }
    
	protected static function parseApiResponse($response)
	{
		// Error
		if (array_key_exists('error', $response))
		{
			throw new \Exception($response['error']);
		}
		if (empty($response['record'])) return [];
		$records = $response['record'];
		$response = [];
		$isRow = false;
		foreach ($records as $i => $record)
		{
			// Happens with custom multi-record returns
			if (array_key_exists('field', $record))
			{
				$record = $record['field'];
				$isRow = true;
			}
			elseif ($isRow === true)
			{
				ddme('Error', $i, $record, $response);
			}
			// Happens with custom single-row record returns
			if (array_key_exists('@attributes', $record))
			{
				$record = [$record];
			}
			foreach ($record as $ii => $field)
			{
				$field = $field['@attributes'];
				if ($isRow && is_array($response) && ! array_key_exists($i, $response))
				{
					$response[$i] = (object) [];
				}
				elseif ( ! $isRow && is_array($response))
				{
					$response = (object) [];
				}
				// Record returned
				if ( ! empty($field['id']))
				{
					$field['id'] = strtolower($field['id']);
					$field['value'] = str_ireplace(['`'], ['\''], $field['value']);
					if ($isRow && is_array($response))
					{
						$response[$i]->{$field['id']} = $field['value'];
					}
					else
					{
						$response->{$field['id']} = $field['value'];
					}
				}
				// Item ID returned when saving or updating
				else
				{
					return (int) $field['value'];
				}
			}
		}
		return $response;
    }
    
	public static function escapeValue($value)
	{
		return ( ! empty($value)) ? str_replace(["'", '"', '%'], ["''", '', '%25'], $value) : $value;
    }
    
	public static function lookupGiftType($code)
	{
		$giftTypeLookup = [
			'EF' => 'eCheck',
			'VS' => 'VISA',
			'MC' => 'MasterCard',
			'AX' => 'American Express',
			'DI' => 'Discover',
		];
		foreach ($giftTypeLookup as $giftTypeCode => $giftTypeDescription)
		{
			if ($code == $giftTypeCode) return $giftTypeDescription;
			if ($code == $giftTypeDescription) return $giftTypeCode;
		}
		return null;
	}
	
	public static function saveDonorAddress($params = [])
	{
		$params= '@address_id=N%27'.self::getInteger($params, 'address_id', 0).'%27,%20'.
			 '@donor_id=N%27'.self::getInteger($params, 'donor_id', 0).'%27,%20'.
			 '@opt_line=N%27'.self::getSpecialCharactersValue($params, 'opt_line').'%27,%20'.
			 '@address=N%27'.self::getSpecialCharactersValue($params, 'address').'%27,%20'.
			 '@address2=N%27'.self::getSpecialCharactersValue($params, 'address2').'%27,%20'.
			 '@city=N%27'.self::getSpecialCharactersValue($params, 'city').'%27,%20'.
			 '@state=N%27'.self::getSpecialCharactersValue($params, 'state').'%27,%20'.
			 '@zip=N%27'.self::getSpecialCharactersValue($params, 'zip').'%27,%20'.
			 '@country=N%27'.self::getSpecialCharactersValue($params, 'country').'%27,%20'.
			 '@address_type=N%27'.self::getSpecialCharactersValue($params, 'address_type').'%27,%20'.
			 '@getmail=N%27'.self::getSpecialCharactersValue($params, 'getmail').'%27,%20'.
			 '@user_id=N%27'.self::getSpecialCharactersValue($params, 'user_id').'%27,%20'.
			 '@title='.(empty($params['title']) ? 'null' : 'N%27'.self::getSpecialCharactersValue($params, 'title').'%27').
			 ',%20@first_name=N%27'.self::getSpecialCharactersValue($params, 'first_name').'%27,%20'.
			 '@middle_name=N%27'.self::getSpecialCharactersValue($params, 'middle_name').'%27,%20'.
			 '@last_name=N%27'.self::getSpecialCharactersValue($params, 'last_name').'%27,%20'.
			 '@suffix=N%27'.self::getSpecialCharactersValue($params, 'suffix').'%27,%20'.
			 '@prof_title=N%27'.self::getSpecialCharactersValue($params, 'prof_title').'%27,%20'.
			 '@salutation=N%27'.self::getSpecialCharactersValue($params, 'salutation').'%27,%20'.
			 '@seasonal_from_date=N%27'.self::getSpecialCharactersValue($params, 'seasonal_from_date').'%27,%20'.
			 '@seasonal_to_date=N%27'.self::getSpecialCharactersValue($params, 'seasonal_to_date').'%27,%20'.
			 '@email=N%27'.self::getSpecialCharactersValue($params, 'email').'%27,%20'.
			 '@home_phone=N%27'.self::getSpecialCharactersValue($params, 'home_phone').'%27,%20'.
			 '@business_phone=N%27'.self::getSpecialCharactersValue($params, 'business_phone').'%27,%20'.
			 '@fax_phone=N%27'.self::getSpecialCharactersValue($params, 'fax_phone').'%27,%20'.
			 '@mobile_phone=N%27'.self::getSpecialCharactersValue($params, 'mobile_phone').'%27,%20'.
			 '@address3=N%27'.self::getSpecialCharactersValue($params, 'address3').'%27,%20'.
			 '@address4=N%27'.self::getSpecialCharactersValue($params, 'address4').'%27,%20'.
			 '@org_rec=N%27'.self::getSpecialCharactersValue($params, 'org_rec').'%27';
			return self::api('dp_saveaddress', $params);
	}
}