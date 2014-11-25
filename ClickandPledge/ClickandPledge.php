<?php
/**
 * Click & Pledge
 * @class ClickandPledge
 *
 * @author Click & Pledge Team
 * @C&P version 2.3
 * @copyright Click & Pledge, 27 Oct, 2011
 * @package Shopp
 * @since 1.2
 * @subpackage ClickandPledge
 *
 * $Id: ClickandPledge.php 1913 2011-05-18 20:03:58Z jond $
 * @Last Update: November 25, 2014
 * @Tested with: Shopp 1.3.5
 **/

class ClickandPledge extends GatewayFramework implements GatewayModule {

	// Settings
	var $secure = false;
	var $captures = true; // merchant initiated capture supported
	var $recurring = true; // support for recurring payment
	//var $authonly = true;
	var $buttonurl = '';
	var $cards = array("visa","mc","disc","amex","jcb");
	// Internals
	var $baseop = array();
	var $creditcard_names = array();
	
	var $currencies = array("USD", "AUD", "BRL", "CAD", "CZK", "DKK", "EUR", "HKD", "HUF",
	 						"ILS", "JPY", "MYR", "MXN", "NOK", "NZD", "PHP", "PLN", "GBP",
	 						"SGD", "SEK", "CHF", "TWD", "THB");
	var $locales = array("AT" => "de_DE", "AU" => "en_AU", "BE" => "en_US", "CA" => "en_US",
							"CH" => "de_DE", "CN" => "zh_CN", "DE" => "de_DE", "ES" => "es_ES",
							"FR" => "fr_FR", "GB" => "en_GB", "GF" => "fr_FR", "GI" => "en_US",
							"GP" => "fr_FR", "IE" => "en_US", "IT" => "it_IT", "JP" => "ja_JP",
							"MQ" => "fr_FR", "NL" => "nl_NL", "PL" => "pl_PL", "RE" => "fr_FR",
							"US" => "en_US");
	var $status = array('' => 'UNKNOWN','Canceled-Reversal' => 'CHARGED','Transaction processed successfully' => 'CHARGED',
						'Denied' => 'VOID', 'Expired' => 'VOID','Failed' => 'VOID','Pending' => 'PENDING',
						'Refunded' => 'VOID','Reversed' => 'VOID','Processed' => 'PENDING','Voided' => 'VOID');
						
	var $responsecodes = array(2054=>'Total amount is wrong',2055=>'AccountGuid is not valid',2056=>'AccountId is not valid',2057=>'Username is not valid',2058=>'Password is not valid',2059=>'Invalid recurring parameters',2060=>'Account is disabled',2101=>'Cardholder information is null',2102=>'Cardholder information is null',2103=>'Cardholder information is null',2104=>'Invalid billing country',2105=>'Credit Card number is not valid',2106=>'Cvv2 is blank',2107=>'Cvv2 length error',2108=>'Invalid currency code',2109=>'CreditCard object is null',2110=>'Invalid card type ',2111=>'Card type not currently accepted',2112=>'Card type not currently accepted',2210=>'Order item list is empty',2212=>'CurrentTotals is null',2213=>'CurrentTotals is invalid',2214=>'TicketList lenght is not equal to quantity',2215=>'NameBadge lenght is not equal to quantity',2216=>'Invalid textonticketbody',2217=>'Invalid textonticketsidebar',2218=>'Invalid NameBadgeFooter',2304=>'Shipping CountryCode is invalid',2401=>'IP address is null',2402=>'Invalid operation',2501=>'WID is invalid',2502=>'Production transaction is not allowed. Contact support for activation.',2601=>'Invalid character in a Base-64 string',2701=>'ReferenceTransaction Information Cannot be NULL',2702=>'Invalid Refrence Transaction Information',2703=>'Expired credit card',2805=>'eCheck Account number is invalid',2807=>'Invalid payment method',2809=>'Invalid payment method',2811=>'eCheck payment type is currently not accepted',2812=>'Invalid check number',5001=>'Declined (general)',5002=>'Declined (lost or stolen card)',5003=>'Declined (fraud)',5004=>'Declined (Card expired)',5005=>'Declined (Cvv2 is not valid)',5006=>'Declined (Insufficient fund)',5007=>'Declined (Invalid credit card number)');
	var $allowed_Periodicity = array( 'week', '2 Weeks', 'Month', '2 Months', 'Quarter', '6 Months', 'Year' );
	var $country_code = array( 'DE' => '276','AT' => '040','BE' => '056','CA' => '124','CN' => '156','ES' => '724',
						'FI' => '246','FR' => '250','GR' => '300', 'IT' => '380','JP' => '392','LU' => '442',
						'NL' => '528','PL' => '616','PT' => '620','CZ' => '203','GB' => '826','SE' => '752',
						'CH' => '756','DK' => '208','US' => '840','HK' => '344','NO' => '578','AU' => '036',
						'SG' => '702','IE' => '372','NZ' => '554','KR' => '410','IL' => '376','ZA' => '710',
						'NG' => '566','CI' => '384','TG' => '768','BO' => '068','MU' => '480','RO' => '642',
						'SK' => '703','DZ' => '012','AS' => '016','AD' => '020','AO' => '024','AI' => '660',
						'AG' => '028','AR' => '032','AM' => '051','AW' => '533','AZ' => '031','BS' => '044',
						'BH' => '048','BD' => '050','BB' => '052','BY' => '112','BZ' => '084','BJ' => '204',
						'BT' => '060','56' => '064','BW' => '072','BR' => '076','BN' => '096','BF' => '854',
						'MM' => '104','BI' => '108','KH' => '116','CM' => '120','CV' => '132','CF' => '140',
						'TD' => '148','CL' => '152','CO' => '170','KM' => '174','CD' => '180','CG' => '178',
						'CR' => '188','HR' => '191','CU' => '192','CY' => '196','DJ' => '262','DM' => '212',
						'DO' => '214','TL' => '626','EC' => '218','EG' => '818','SV' => '222','GQ' => '226',
						'ER' => '232','EE' => '233','ET' => '231','FK' => '238','FO' => '234','FJ' => '242',
						'GA' => '266','GM' => '270','GE' => '268','GH' => '288','GD' => '308','GL' => '304',
						'GI' => '292','GP' => '312','GU' => '316','GT' => '320','GG' => '831','GN' => '324',
						'GW' => '624','GY' => '328','HT' => '332','HM' => '334','VA' => '336','HN' => '340',
						'IS' => '352','IN' => '356','ID' => '360','IR' => '364','IQ' => '368','IM' => '833',
						'JM' => '388','JE' => '832','JO' => '400','KZ' => '398','KE' => '404','KI' => '296',
						'KP' => '408','KW' => '414','KG' => '417','LA' => '418','LV' => '428','LB' => '422',
						'LS' => '426','LR' => '430','LY' => '434','LI' => '438','LT' => '440','MO' => '446',
						'MK' => '807','MG' => '450','MW' => '454','MY' => '458','MV' => '462','ML' => '466',
						'MT' => '470','MH' => '584','MQ' => '474','MR' => '478','HU' => '348','YT' => '175',
						'MX' => '484','FM' => '583','MD' => '498','MC' => '492','MN' => '496','ME' => '499',
						'MS' => '500','MA' => '504','MZ' => '508','NA' => '516','NR' => '520','NP' => '524',
						'BQ' => '535','NC' => '540','NI' => '558','NE' => '562','NU' => '570','NF' => '574',
						'MP' => '580','OM' => '512','PK' => '586','PW' => '585','PS' => '275','PA' => '591',
						'PG' => '598','PY' => '600','PE' => '604','PH' => '608','PN' => '612','PR' => '630',
						'QA' => '634','RE' => '638','RU' => '643','RW' => '646','BL' => '652','KN' => '659',
						'LC' => '662','MF' => '663','PM' => '666','VC' => '670','WS' => '882','SM' => '674',
						'ST' => '678','SA' => '682','SN' => '686','RS' => '688','SC' => '690','SL' => '694',
						'SI' => '705','SB' => '090','SO' => '706','GS' => '239','LK' => '144','SD' => '729',
						'SR' => '740','SJ' => '744','SZ' => '748','SY' => '760','TW' => '158','TJ' => '762',
						'TZ' => '834','TH' => '764','TK' => '772','TO' => '776','TT' => '780','TN' => '788',
						'TR' => '792','TM' => '795','TC' => '796','TV' => '798','UG' => '800','UA' => '804',
						'AE' => '784','UY' => '858','UZ' => '860','VU' => '548','VE' => '862','VN' => '704',
						'VG' => '092','VI' => '850','WF' => '876','EH' => '732','YE' => '887','ZM' => '894',
						'ZW' => '716','AL' => '008','AF' => '004','AQ' => '010','BA' => '070','BV' => '074',
						'IO' => '086','BG' => '100','KY' => '136','CX' => '162','CC' => '166','CK' => '184',
						'GF' => '254','PF' => '258','TF' => '260','AX' => '248','CW' => '531','SH' => '654',
						'SX' => '534','SS' => '728','UM' => '581'		
          );
	var $shoppmeta = array();
	function __construct () {
		parent::__construct();	
		
		//echo '<pre>';
		//print_r($this->settings);
		//die();
		$this->setup('account_id','guid','cards','testmode','currency');
		
		$this->settings['currency_code'] = $this->currencies[0];
		
		if (in_array($this->baseop['currency']['code'],$this->currencies))
			$this->settings['currency_code'] = $this->baseop['currency']['code'];
		
		if (array_key_exists($this->baseop['country'],$this->locales))
			$this->settings['locale'] = $this->locales[$this->baseop['country']];
		//else $this->settings['locale'] = $this->locales['US'];

		$this->buttonurl = sprintf(force_ssl($this->buttonurl), $this->settings['locale']);

		if (!isset($this->settings['label'])) $this->settings['label'] = "Click & Pledge";
		
		$metaObj = $codeObj = sDB::query("SELECT * FROM ".ShoppDatabaseObject::tablename('meta')." where context = 'shopp' and type='setting'");
		foreach($metaObj as $meta)
		{
			$this->shoppmeta[$meta->name] = $meta->value;
		}
		//echo '<pre>';
		//print_r($this->shoppmeta);
		// Autoset useable payment cards
		$this->settings['cards'] = array();
		
		if( $this->settings['Visa'] == 'on' ) 
		{
			$this->settings['cards'][] = 'Visa';
			$this->creditcard_names[] = 'Visa';
		}
		if( $this->settings['MC'] == 'on' ) 
		{
			$this->settings['cards'][] = 'MC';
			$this->creditcard_names[] = 'MasterCard';
			$this->creditcard_names[] = 'MC';
		}
		if( $this->settings['Disc'] == 'on' ) 
		{
			$this->settings['cards'][] = 'Disc';
			$this->creditcard_names[] = 'Discover Card';
			$this->creditcard_names[] = 'Disc';
		}
		if( $this->settings['Amex'] == 'on' )
		{
			$this->settings['cards'][] = 'Amex';
			$this->creditcard_names[] = 'American Express';
			$this->creditcard_names[] = 'Amex';
		}
		if( $this->settings['JCB'] == 'on' )
		{
			$this->settings['cards'][] = 'JCB';
			$this->creditcard_names[] = 'JCB';
		}
		
		
		if( count( $this->settings['cards'] ) == 0 )
		{
			foreach ($this->cards as $card)	
			{
			$this->settings['cards'][] = $card->symbol;
			}
		}
		
	
		add_action('shopp_clickandpledge_sale',array(&$this,'sale'));
		add_action('shopp_clickandpledge_auth',array(&$this,'auth'));
		add_action('shopp_clickandpledge_capture',array(&$this,'capture'));
	}
	
	function sale (OrderEventMessage $Event) {		
		$this->handler('authed',$Event);		
		$this->handler('captured',$Event);
	}
	
	function auth (OrderEventMessage $Event) {
		$this->handler('authed',$Event);
	}
	
	/*
	public function capture ( OrderEventMessage $Event ) {
		$this->handler('captured', $Event);
	}
	*/
	function actions () {		
		add_action('shopp_process_checkout', array(&$this,'checkout'),9);
		add_action('shopp_init_confirmation',array(&$this,'confirmation'));
	}  
	function checkout () {
		$this->Order->Billing->cardtype = "Click & Pledge";
		$this->Order->confirm = true;
	}
	
	function CreditCardCompany($ccNum)
	 {
			/*
				* mastercard: Must have a prefix of 51 to 55, and must be 16 digits in length.
				* Visa: Must have a prefix of 4, and must be either 13 or 16 digits in length.
				* American Express: Must have a prefix of 34 or 37, and must be 15 digits in length.
				* Diners Club: Must have a prefix of 300 to 305, 36, or 38, and must be 14 digits in length.
				* Discover: Must have a prefix of 6011, and must be 16 digits in length.
				* JCB: Must have a prefix of 3, 1800, or 2131, and must be either 15 or 16 digits in length.
			*/
	 
			if (ereg("^5[1-5][0-9]{14}$", $ccNum))
					return "MasterCard";
	 
			elseif (ereg("^4[0-9]{12}([0-9]{3})?$", $ccNum))
					return "Visa";
	 
			elseif (ereg("^3[47][0-9]{13}$", $ccNum))
					return "American Express";
	 
			elseif (ereg("^3(0[0-5]|[68][0-9])[0-9]{11}$", $ccNum))
					return "Diners Club";
	 
			elseif (ereg("^6011[0-9]{12}$", $ccNum))
					return "Discover";
	 
			elseif (ereg("^(3[0-9]{4}|2131|1800)[0-9]{11}$", $ccNum))
					return "JCB";
			else
				return "invalid";
	 }
	 
	 function safeString( $str,  $length=1, $start=0 )
	{
		return substr( htmlspecialchars( $str ), $start, $length );
	}
	 
function handler ($type,$Event) 
{
		if(!isset($Event->txnid)) $Event->txnid = time();
		
		$Order = $this->Order;
		
	    $regions = Lookup::country_zones();
	    $states = $regions[$Order->Billing->country];
	    $billing_states=$states[$Order->Billing->state];
		$Periodicity = '';
		
		$shipstates = $regions[$Order->Shipping->country];
	    $shipping_states = $shipstates[$Order->Shipping->state];
		if($this->settings['account_id'] == '' || $this->settings['guid'] == '')
		{
			new ShoppError(__("Invalid settings for Click & Pledge Payment. Please contact administrator",'Shopp'),'c&p_express_transacton_error',SHOPP_TRXN_ERR);
			shopp_redirect(shoppurl(false,'checkout'));
		}
		
		if (!in_array($this->baseop['currency']['code'], array('USD', 'EUR', 'CAD', 'GBP'))) 
		{
			new ShoppError(__("Click & Pledge do no allow <b>".$this->baseop['currency']['code']."</b>. We are allowing USD, EUR, CAD, GBP",'Shopp'),'c&p_express_transacton_error',SHOPP_TRXN_ERR);
			shopp_redirect(shoppurl(false,'checkout'));
		}
			
		if(!in_array($Order->Billing->cardtype, $this->creditcard_names))
		{
			new ShoppError(__("We are not accepting <b>".$Order->Billing->cardtype."</b> type cards",'Shopp'),'c&p_express_transacton_error',SHOPP_TRXN_ERR);
			shopp_redirect(shoppurl(false,'checkout'));
		}
		
		$cardnumber = $_POST['billing'];		
		if( preg_match( '/^(X)/', $cardnumber['card'] )  )
		{
			new ShoppError(__("Invalid Credit Card Number.",'Shopp'),'c&p_express_transacton_error',SHOPP_TRXN_ERR);
			shopp_redirect(shoppurl(false,'checkout'));
		}
		
		$cnumber = ($Order->Billing->card) ? $Order->Billing->card : $cardnumber['card'];
		if( $this->CreditCardCompany($cnumber) == 'invalid'  )
		{
			new ShoppError(__("Invalid Credit Card Number.",'Shopp'),'c&p_express_transacton_error',SHOPP_TRXN_ERR);
			shopp_redirect(shoppurl(false,'checkout'));
		}
		
		if( $Order->Billing->name == ''  )
		{
			new ShoppError(__("Please enter Billing Name.",'Shopp'),'c&p_express_transacton_error',SHOPP_TRXN_ERR);
			shopp_redirect(shoppurl(false,'checkout'));
		}
		
		if( $Order->Billing->cvv == '' || !preg_match( '/^\d{1,4}$/', $Order->Billing->cvv )  )
		{
			new ShoppError(__("You did not enter a valid security ID for the card you provided. The security ID is a 3 or 4 digit number found on the back of the credit card.",'Shopp'),'c&p_express_transacton_error',SHOPP_TRXN_ERR);
			shopp_redirect(shoppurl(false,'checkout'));
		}
		
		$dom = new DOMDocument('1.0', 'UTF-8');
         $root = $dom->createElement('CnPAPI', '');
         $root->setAttribute("xmlns","urn:APISchema.xsd");
         $root = $dom->appendChild($root);
         $version=$dom->createElement("Version",'0.1');
         $version=$root->appendChild($version);
		 $engine = $dom->createElement('Engine', '');
         $engine = $root->appendChild($engine);
		 
		 $application = $dom->createElement('Application','');
		 $application = $engine->appendChild($application);
    
		 $applicationid=$dom->createElement('ID','CnP:PaaS:Shopp');
		 $applicationid=$application->appendChild($applicationid);
			
		 $applicationname=$dom->createElement('Name','Salesforce:CnP_PaaS_SC_Shopp');
		 $applicationid=$application->appendChild($applicationname);
			
		 $applicationversion=$dom->createElement('Version','2.3');
		 $applicationversion=$application->appendChild($applicationversion);
    
         $request = $dom->createElement('Request', '');
         $request = $engine->appendChild($request);
    
		 $operation=$dom->createElement('Operation','');
		 $operation=$request->appendChild($operation);
		
		 $operationtype=$dom->createElement('OperationType','Transaction');
		 $operationtype=$operation->appendChild($operationtype);
	     
         $ipaddress=$dom->createElement('IPAddress',$_SERVER['REMOTE_ADDR']);
		 $ipaddress=$operation->appendChild($ipaddress);
		 
		$httpreferrer=$dom->createElement('UrlReferrer',$_SERVER['HTTP_REFERER']);
		$httpreferrer=$operation->appendChild($httpreferrer);
		
		 $authentication=$dom->createElement('Authentication','');
		 $authentication=$request->appendChild($authentication);	
		 
		 $accounttype=$dom->createElement('AccountGuid',$this->settings['guid'] ); 
         $accounttype=$authentication->appendChild($accounttype);
    
         $accountid=$dom->createElement('AccountID',$this->settings['account_id']);
         $accountid=$authentication->appendChild($accountid);
		 
		 if($this->settings['testmode']=="on")
		 {
		     $mode="Test";
		 }else{
		     $mode ="Production";
		 }
		 $order=$dom->createElement('Order','');
         $order=$request->appendChild($order);
    
         $ordermode=$dom->createElement('OrderMode',$mode);
         $ordermode=$order->appendChild($ordermode);
		 		 
		 $cardholder=$dom->createElement('CardHolder','');
         $cardholder=$order->appendChild($cardholder);
    
		 if( $Order->Customer->firstname != '' && $Order->Customer->lastname != '' )
		 {
			 $billinginfo=$dom->createElement('BillingInformation','');
			 $billinginfo=$cardholder->appendChild($billinginfo);
			 
			 $billfirst_name=$dom->createElement('BillingFirstName',$this->safeString($Order->Customer->firstname, 50));
			 $billfirst_name=$billinginfo->appendChild($billfirst_name);
		
			 $billlast_name=$dom->createElement('BillingLastName',$this->safeString($Order->Customer->lastname, 50));
			 $billlast_name=$billinginfo->appendChild($billlast_name);

			 if( $Order->Customer->email != '' )
			 {
				$bill_email=$dom->createElement('BillingEmail',$this->safeString($Order->Customer->email, 255));
				$bill_email=$billinginfo->appendChild($bill_email);
			 }
			
			 if( $Order->Customer->phone != '' )
			 {
				$bill_phone=$dom->createElement('BillingPhone', $Order->Customer->phone ? $this->safeString($Order->Customer->phone, 50) : "000000" );
				$bill_phone=$billinginfo->appendChild($bill_phone);
			 }
		}//BillingInformation Node end
		
		$billingaddress=$dom->createElement('BillingAddress','');
		$billingaddress=$cardholder->appendChild($billingaddress);

		if( $Order->Billing->address != '' )
		{
			$billingaddress1=$dom->createElement('BillingAddress1',$this->safeString($Order->Billing->address, 100));
			$billingaddress1=$billingaddress->appendChild($billingaddress1);
		}
		
		if( $Order->Billing->xaddress != '' )
		{
			$billingaddress2=$dom->createElement('BillingAddress2',$this->safeString($Order->Billing->xaddress, 100));
			$billingaddress2=$billingaddress->appendChild($billingaddress2);
		}
				
		if( $Order->Billing->city != '' )
		{
			$billing_city=$dom->createElement('BillingCity',$this->safeString($Order->Billing->city, 50));
			$billing_city=$billingaddress->appendChild($billing_city);
		}
		
		if( $Order->Billing->state != '' )
		{
			$billing_state=$dom->createElement('BillingStateProvince',$this->safeString((!empty($billing_states) && $billing_states != 'Other') ? $billing_states : $Order->Billing->state, 50));
			$billing_state=$billingaddress->appendChild($billing_state);
		}
		
		if( $Order->Billing->postcode != '' )
		{
			$billing_zip=$dom->createElement('BillingPostalCode',$this->safeString($Order->Billing->postcode, 20));
			$billing_zip=$billingaddress->appendChild($billing_zip);
		}
		
		if( $Order->Billing->country != '' )
		{
			$billing_country=$dom->createElement('BillingCountryCode',$this->country_code[$Order->Billing->country]);
			$billing_country=$billingaddress->appendChild($billing_country);
		}
		
		
		if( ($Order->Shipping->name) || ($Order->Shipping->address != '' &&  $Order->Shipping->city != '' && $Order->Shipping->country != '') )
		{
			$shippinginfo=$dom->createElement('ShippingInformation','');
			$shippinginfo=$cardholder->appendChild($shippinginfo);
			
			if($Order->Shipping->name != '')
			{
				$ShippingContactInformation=$dom->createElement('ShippingContactInformation','');
				$ShippingContactInformation=$shippinginfo->appendChild($ShippingContactInformation);
				$parts = explode(' ', $Order->Shipping->name);
				if(count($parts) == 2) {
					if(isset($parts[0])) {
					$ShippingFirstName=$dom->createElement('ShippingFirstName',$this->safeString($parts[0], 50));
					$ShippingFirstName=$ShippingContactInformation->appendChild($ShippingFirstName);
					}
					if(isset($parts[1])) {
					$ShippingLastName=$dom->createElement('ShippingLastName',$this->safeString($parts[1], 50));
					$ShippingFirstName=$ShippingContactInformation->appendChild($ShippingLastName);
					}
				} else {
					if(isset($parts[0])) {
					$ShippingFirstName=$dom->createElement('ShippingFirstName',$this->safeString($parts[0], 50));
					$ShippingFirstName=$ShippingContactInformation->appendChild($ShippingFirstName);
					}
					if(isset($parts[1])) {
					$ShippingMI=$dom->createElement('ShippingMI',$this->safeString($parts[1], 1));
					$ShippingFirstName=$ShippingContactInformation->appendChild($ShippingMI);
					}
					if(isset($parts[2])) {
					$ShippingLastName=$dom->createElement('ShippingLastName',$this->safeString($parts[2], 50));
					$ShippingFirstName=$ShippingContactInformation->appendChild($ShippingLastName);
					}
				}
			}
			
			$shippingaddress=$dom->createElement('ShippingAddress','');
			$shippingaddress=$shippinginfo->appendChild($shippingaddress);
			
			if( $Order->Shipping->address != '' )
			{
				$ship_address1=$dom->createElement('ShippingAddress1',$this->safeString($Order->Shipping->address, 100));
				$ship_address1=$shippingaddress->appendChild($ship_address1);
			}

			if( $Order->Shipping->xaddress != '' )
			{
				$ship_address2=$dom->createElement('ShippingAddress2',$this->safeString($Order->Shipping->xaddress, 100));
				$ship_address2=$shippingaddress->appendChild($ship_address2);
			}

			if( $Order->Shipping->city != '' )
			{
				$ship_city=$dom->createElement('ShippingCity',$this->safeString($Order->Shipping->city,  40));
				$ship_city=$shippingaddress->appendChild($ship_city);
			}

			if( $Order->Shipping->state != '' )
			{
				$ship_state=$dom->createElement('ShippingStateProvince', (!empty($shipping_states) && $shipping_states!="Other") ? $shipping_states : $this->safeString($Order->Shipping->state,40));
				$ship_state=$shippingaddress->appendChild($ship_state);
			}
			
			if( $Order->Shipping->postcode != '' )
			{
				$ship_zip=$dom->createElement('ShippingPostalCode',$this->safeString($Order->Shipping->postcode,  20));
				$ship_zip=$shippingaddress->appendChild($ship_zip);
			}
			
			if( $Order->Shipping->country != '' )
			{
				$ship_country=$dom->createElement('ShippingCountryCode',$this->country_code[$Order->Shipping->country]);
				$ship_country=$shippingaddress->appendChild($ship_country);
			}
		}//End of Shipping Address node
		
		if( $Order->Customer->company != '' )
		{
			$customfieldlist = $dom->createElement('CustomFieldList','');
			$customfieldlist = $cardholder->appendChild($customfieldlist);
		
			$customfield = $dom->createElement('CustomField','');
			$customfield = $customfieldlist->appendChild($customfield);
				
			$fieldname = $dom->createElement('FieldName','Company Name');
			$fieldname = $customfield->appendChild($fieldname);
				
			$fieldvalue = $dom->createElement('FieldValue',$this->safeString($Order->Customer->company, 500));
			$fieldvalue = $customfield->appendChild($fieldvalue);
		}
		
		$paymentmethod=$dom->createElement('PaymentMethod','');
		$paymentmethod=$cardholder->appendChild($paymentmethod);
		
		$payment_type=$dom->createElement('PaymentType','CreditCard');
		$payment_type=$paymentmethod->appendChild($payment_type);
		
		$creditcard=$dom->createElement('CreditCard','');
		$creditcard=$paymentmethod->appendChild($creditcard);

		if($Order->Billing->name != '') 
		{
			$credit_name=$dom->createElement('NameOnCard',$this->safeString( $Order->Billing->name, 50));
			$credit_name=$creditcard->appendChild($credit_name);
		} 
		else 
		{
			$name = $this->safeString($Order->Customer->firstname,  50);
			if($Order->Customer->lastname)
			$name .= ' '.$this->safeString($Order->Customer->lastname,  50);
			
			$credit_name=$dom->createElement('NameOnCard',$name);
			$credit_name=$creditcard->appendChild($credit_name);
		}
		
		$credit_number=$dom->createElement('CardNumber',$cnumber);
		$credit_number=$creditcard->appendChild($credit_number);
		
		$credit_cvv=$dom->createElement('Cvv2',$Order->Billing->cvv);
		$credit_cvv=$creditcard->appendChild($credit_cvv);
		 
		$card_exp_month = date("m/y",$Order->Billing->cardexpires);

		$credit_expdate=$dom->createElement('ExpirationDate',$card_exp_month);
		$credit_expdate=$creditcard->appendChild($credit_expdate);
		
		$orderitemlist=$dom->createElement('OrderItemList','');
        $orderitemlist=$order->appendChild($orderitemlist);		
		
		$Items = shopp_cart_items();
		$sku_id="";
		$items = 0;
		$is_recurring = false;
		$recurring_method = $Periodicity = '';
		$cycles = $calctax = $TotalTax = $TotalDiscount = $interval = $UnitDiscount = 0;
		foreach($Items as $i => $Item) {
				//echo '<pre>';
				//print_r($Item);
				//die();
				if($Item->type == 'Subscription' && isset($Item->option->recurring['period']) && isset($Item->option->recurring['interval']))
				{
					if( isset( $Item->option->recurring ) )
					{
						switch( $Item->option->recurring['period'] )
						{
							case 'd':
								$period = 'Day';
								break;
							case 'w':
								$period = 'Week';
								break;
							case 'm':
								$period = 'Month';
								break;
							case 'y':
								$period = 'Year';
								break;
						}
					}
					$interval = $Item->option->recurring['interval'];
					
					$Periodicity = ( $interval > 1 ) ? $interval . ' ' . $period . 's' : $period;
					$Periodicity = ( $Periodicity == '3 Months' ) ? 'Quarter' : $Periodicity;
					if( $Item->option->recurring['trial'] == 'on' )
					{
						new ShoppError(__("Click & Pledge do not support trail period for subscriptions. Please contact administrator.",'Shopp'),'c&p_express_transacton_error',SHOPP_TRXN_ERR);
						shopp_redirect(shoppurl(false,'checkout'));
					}
					
					if( !in_array( $Periodicity, $this->allowed_Periodicity ) )
					{
						new ShoppError(__("Selected Periodicity <b>".$Periodicity."</b> is not valid for Click & Pledge. We are allowing 'week', '2 Weeks', 'Month', '2 Months', 'Quarter', '6 Months', 'Year' only. Please contact administrator.",'Shopp'),'c&p_express_transacton_error',SHOPP_TRXN_ERR);
						shopp_redirect(shoppurl(false,'checkout'));
					}
					
					if( $Item->option->recurring['cycles'] > 999 )
					{
						new ShoppError(__("Billing cycles should be between 2 and 999. Please contact administrator.",'Shopp'),'c&p_express_transacton_error',SHOPP_TRXN_ERR);
						shopp_redirect(shoppurl(false,'checkout'));
					}
					$is_recurring = true;
					$recurring_method = $Item->type;
					$cycles = $Item->option->recurring['cycles'];
				}
				$orderitem=$dom->createElement('OrderItem','');
				$orderitem=$orderitemlist->appendChild($orderitem);
				
				$f_unit_price =  number_format($Item->unitprice,2,'.','');
				$f_unit_tax = $f_unit_price*$Item->taxrate;				
				
				$itemid=$dom->createElement('ItemID',$this->safeString($Item->product,  25));
				$itemid=$orderitem->appendChild($itemid);
				
				$item_name = $Item->name;
				if(isset($Item->option->label))
				$item_name .= ' ('.$Item->option->label.')';
				$itemid=$dom->createElement('ItemName',$this->safeString($item_name, 50));
				$itemid=$orderitem->appendChild($itemid);
				
				$quntity=$dom->createElement('Quantity',$Item->quantity);
				$quntity=$orderitem->appendChild($quntity);
				
				$unitprice=$dom->createElement('UnitPrice',number_format($Item->unitprice,2,'.','')*100);
				$unitprice=$orderitem->appendChild($unitprice);	
								
				if( isset( $Item->unittax ) && $Item->unittax != 0 && $this->shoppmeta['tax_inclusive'] == 'off' )
				{
					$calctax = $calctax + number_format(($Item->unittax * $Item->quantity),2,'.','');
					$unit_tax=$dom->createElement('UnitTax',number_format($Item->unittax,2,'.','')*100);
					$unit_tax=$orderitem->appendChild($unit_tax);
				}
				
				if( isset( $Item->discount ) && $Item->discount != 0 )
				{
					$UnitDiscount = $UnitDiscount + number_format(($Item->discount * $Item->quantity),2,'.','');
					$unit_disc=$dom->createElement('UnitDiscount',number_format($Item->discount,2,'.','')*100);
					$unit_disc=$orderitem->appendChild($unit_disc);
				}
				
				
				$sku_id=$Item->sku;				
								
				if( $sku_id != '' )
				{
					$sku_code=$dom->createElement('SKU',$this->safeString($sku_id,  100));
					$sku_code=$orderitem->appendChild($sku_code);
				}	
		}
						
		if(shopp('cart','has-ship-costs'))
		{
		//echo shopp('cart.get-shipping', 'number=on');
		//die('gddgdg gdgd');
		$shipping=$dom->createElement('Shipping','');
		$shipping=$order->appendChild($shipping);
		
		while( shopp( 'shipping', 'options' ) ) 
		{		
			if ( shopp('shipping','option-selected') )
			{
				$shipping_method=$dom->createElement('ShippingMethod',$this->safeString( shopp('shipping', 'get-option-name'),50));
				$shipping_method=$shipping->appendChild($shipping_method);
				$shipping_value = $dom->createElement('ShippingValue',number_format(shopp('cart.get-shipping','number=on'), 2, '.', '')*100);
				$shipping_value=$shipping->appendChild($shipping_value);
			}
		}		
		}
		//die('Out');
		$receipt=$dom->createElement('Receipt','');
		$receipt=$order->appendChild($receipt);
		
		$recipt_lang=$dom->createElement('Language','ENG');
		$recipt_lang=$receipt->appendChild($recipt_lang);
		
		if( $this->settings['OrganizationInformation'] != '')
		{
			$recipt_org=$dom->createElement('OrganizationInformation',$this->safeString($this->settings['OrganizationInformation'], 1500));
			$recipt_org=$receipt->appendChild($recipt_org);
		}

		if( $this->settings['ThankYouMessage'] != '')
		{
			$recipt_thanks=$dom->createElement('ThankYouMessage',$this->safeString($this->settings['ThankYouMessage'], 500));
			$recipt_thanks=$receipt->appendChild($recipt_thanks);
		}
		
		if( $this->settings['TermsCondition'] != '')
		{
			$recipt_terms=$dom->createElement('TermsCondition',$this->safeString($this->settings['TermsCondition'], 1500));
			$recipt_terms=$receipt->appendChild($recipt_terms);
		}
		
		$recipt_deduct=$dom->createElement('Deductible','1');
		$recipt_deduct=$receipt->appendChild($recipt_deduct);
		
		if( $this->settings['NotificationEmail'] == 'on'  )
		{
			$recipt_email=$dom->createElement('EmailNotificationList','');
			$recipt_email=$receipt->appendChild($recipt_email);
		
			$email_note=$dom->createElement('NotificationEmail',$Order->Customer->email);
			$email_note=$recipt_email->appendChild($email_note);
		}
		$transation=$dom->createElement('Transaction','');
		$transation=$order->appendChild($transation);

		$trans_type=$dom->createElement('TransactionType','Payment');
		$trans_type=$transation->appendChild($trans_type);
		
		$trans_desc=$dom->createElement('DynamicDescriptor','DynamicDescriptor');
		$trans_desc=$transation->appendChild($trans_desc);
		
		if(  $is_recurring )
		{
			$trans_recurr=$dom->createElement('Recurring','');
			$trans_recurr=$transation->appendChild($trans_recurr);
			if( $cycles == 0 )
			{
				$total_installment=$dom->createElement('Installment',999);
				$total_installment=$trans_recurr->appendChild($total_installment);
			}
			else
			{
				$total_installment=$dom->createElement('Installment',$cycles);
				$total_installment=$trans_recurr->appendChild($total_installment);
			}
			
			$total_periodicity=$dom->createElement('Periodicity',$Periodicity);
			$total_periodicity=$trans_recurr->appendChild($total_periodicity);
		
			$RecurringMethod=$dom->createElement('RecurringMethod',$recurring_method);
			$RecurringMethod=$trans_recurr->appendChild($RecurringMethod);
		
		}
		
		$trans_totals=$dom->createElement('CurrentTotals','');
		$trans_totals=$transation->appendChild($trans_totals);
		
		if( shopp('cart.get-discount','number=on') )
		{
			$TotalDiscount = shopp('cart.get-discount','number=on');
			
			$total_discount=$dom->createElement('TotalDiscount',number_format(shopp('cart.get-discount','number=on'), 2, '.', '')*100);
			$total_discount=$trans_totals->appendChild($total_discount);
			
        }
		
		if( shopp('cart.get-tax', 'number=on') && $this->shoppmeta['tax_inclusive'] == 'off' )
		{
			$TotalTax = shopp('cart.get-tax', 'number=on');
			$total_tax=$dom->createElement('TotalTax',number_format($TotalTax, 2, '.', '')*100);
			$total_tax=$trans_totals->appendChild($total_tax);
		}
		
		if( shopp('cart','has-ship-costs') && shopp('cart.get-shipping','number=on') )
		{
			$TotalShipping = shopp('cart.get-shipping','number=on');
			$total_ship=$dom->createElement('TotalShipping',number_format($TotalShipping, 2, '.', '')*100);
			$total_ship=$trans_totals->appendChild($total_ship);
		}
		
		$Total = shopp('cart.get-total', 'number=on');
		
		$total_amount=$dom->createElement('Total',number_format($Total, 2, '.', '')*100);
		$total_amount=$trans_totals->appendChild($total_amount);
		//echo '<pre>';
		//print_r($Order->Discounts);
		
		
		
		$couponcode="";
		if(shopp('cart','has-promos')) 
		{
			if(count($Order->Discounts) > 0)
			{
				$couponcodes = array();
				foreach($Order->Discounts as $key=>$val) {
					array_push($couponcodes,$key);
				}
				
				for($c = 0; $c < count($couponcodes); $c++)
				{
					$table = ShoppDatabaseObject::tablename('promo');
					$codeObj = sDB::query("SELECT * FROM $table where id = '".$couponcodes[$c]."' and status='enabled'");
					$code = unserialize( $codeObj->rules );
					
					foreach($code as $i => $val) 
					{
						if(is_numeric($i))
						{
							if($val['property'] == 'Promo code')
							{
							$couponcode.= $val['value'];
							$couponcode.= ";";
							}
						}
					}
				}
			}
			/*
			while(shopp('cart','promos'))
			{		
				$name = shopp('cart','get-promo-name');
				$table = ShoppDatabaseObject::tablename('promo');
				$codeObj = sDB::query("SELECT * FROM $table where name = '".$name."' and status='enabled'");				
				$code = unserialize( $codeObj->rules );
				foreach($code as $i => $val) {
				$couponcode.= $val['value'];
				$couponcode.= ";";
				}
			}
			*/
		}
		
		if( $couponcode != '' )
		{
			$trans_coupon=$dom->createElement('CouponCode',substr($couponcode,0,-1));
			$trans_coupon=$transation->appendChild($trans_coupon);
		}
		
		
		if( shopp('cart.get-discount','number=on') )
		{
			$TransactionDiscount = shopp('cart.get-discount','number=on') - $UnitDiscount;
			$trans_coupon_discount=$dom->createElement('TransactionDiscount',number_format($TransactionDiscount, 2, '.', '')*100);
			$trans_coupon_discount=$transation->appendChild($trans_coupon_discount);
		}
		
		
		if( shopp('cart.get-tax', 'number=on') && $this->shoppmeta['tax_inclusive'] == 'off' )
		{
			$transaction_tax = shopp('cart.get-tax', 'number=on') - $calctax;
			if($transaction_tax) {
			$trans_tax=$dom->createElement('TransactionTax',number_format($transaction_tax, 2, '.', '')*100);
			$trans_tax=$transation->appendChild($trans_tax);
			}			
		}
		
        $strParam = $dom->saveXML();
		//echo $strParam;
		//die();
		$response=array();
		$connect = array('soap_version' => SOAP_1_1, 'trace' => 1, 'exceptions' => 0);
		$client = new SoapClient('https://paas.cloud.clickandpledge.com/paymentservice.svc?wsdl', $connect);
		$params = array('instruction'=>$strParam);
		$response = $client->Operation($params); 
		
		if($response->faultstring == 'Could not connect to host')
		{
			new ShoppError($response->faultstring.'. Please try after some time','c&p_express_transacton_error',SHOPP_TRXN_ERR,array());
			shopp_redirect(shoppurl(false,'checkout'));
		}
		$authorizedcode = $response->OperationResult->AuthorizationCode;
		$response_value=$response->OperationResult->ResultData;
		$VaultGUID = $response->OperationResult->VaultGUID;	
		$response_code=$response->OperationResult->ResultCode;
				
		$transation_number=$response->OperationResult->TransactionNumber;
		$xml_error=explode(":",$response->OperationResult->AdditionalInfo);		
		
		if(isset($xml_error['2']))
		{
		   $payment_error=$xml_error['2'];
		}else{
		   $payment_error="";
		}
		
		$this->settings['error'] = FALSE;
		if ($response_code != 0) {
		$this->settings['error'] = TRUE;
		}
		if ( $this->settings['error'] && $type == 'authed' ) {
			if( in_array( $response_code, array( 2051,2052,2053 ) ) )
			{
				$AdditionalInfo = $response->OperationResult->AdditionalInfo;
			}
			else
			{
				//print_r($this->responsecodes);
		//die();
				if( isset( $this->responsecodes[$response_code] ) )
				{
					$AdditionalInfo = $this->responsecodes[$response_code];
				}
				else
				{
					$AdditionalInfo = 'Unknown error';
				}
			}
			$message = join("; ",$response_value);
			if (empty($message)) 
			{
				$message = __( $AdditionalInfo ,'Shopp');
			}
			new ShoppError($AdditionalInfo,'c&p_express_transacton_error',SHOPP_TRXN_ERR,array('codes'=>join('; ',$response_code)));
			shopp_redirect(shoppurl(false,'checkout'));
		}

		$capture = ( count( $Order->Cart->shipped ) > 0 ) ? true : false;		
		$Billing = $this->Order->Billing;		
		shopp_add_order_event($Event->order,$type,array(
				'txnid' => $VaultGUID,
				'txnorigin' => $Event->txnid,
				'fees' => 0,
				'paymethod' => $this->module,
				'payid' => $Billing->card,
				'paytype' => $Billing->cardtype,
				'amount' => $Event->amount,
				'gateway' => $this->module,
				'capture' => $capture,										// Capture flag
			));
		
	}	
	function settings () {		
		$currency = array("USD"=>"US","EUR"=>"EUR");
		$this->ui->text(0,array(
			'name' => 'account_id',
			'value' => $this->settings['account_id'],
			'size' => 30,
			'label' => __('Enter your Account Id .','Shopp')
		));
		
		$this->ui->text(0,array(
			'name' => 'guid',
			'value' => $this->settings['guid'],
			'size' => 30,
			'label' => __('Enter your GUID','Shopp')
		));
		$this->ui->textarea(1,array(
			'name' => 'OrganizationInformation',
			'value' => $this->settings['OrganizationInformation'],
			'size' => 30,
			'label' => __('Organization Information<br>(Maximum: 1500 characters)','Shopp'),
		));
		$this->ui->textarea(1,array(
			'name' => 'ThankYouMessage',
			'value' => $this->settings['ThankYouMessage'],
			'size' => 30,
			'label' => __('Thank You Message<br>(Maximum: 500 characters)','Shopp')
		));
		$this->ui->textarea(1,array(
			'name' => 'TermsCondition',
			'value' => $this->settings['TermsCondition'],
			'size' => 30,
			'label' => __('Terms Conditions<br>(Maximum: 1500 characters)','Shopp')
		));
		
		//Second Row
		/*
		$this->ui->menu(1,array(
			'name' => 'currency',
			'selected' => $this->settings['currency'],
			'label'=>__('Select Currency','Shopp')
		),$currency);
		*/
		$this->ui->checkbox(0,array(
			'name' => 'Visa',
			'checked' => $this->settings['Visa'],
			'label'=>__('Visa','Shopp')
		));
		$this->ui->checkbox(0,array(
			'name' => 'MC',
			'checked' => $this->settings['MC'],
			'label'=>__('MasterCard','Shopp')
		));
		$this->ui->checkbox(0,array(
			'name' => 'Disc',
			'checked' => $this->settings['Disc'],
			'label'=>__('Discover Card','Shopp')
		));
		$this->ui->checkbox(0,array(
			'name' => 'Amex',
			'checked' => $this->settings['Amex'],
			'label'=>__('American Express','Shopp')
		));	
		$this->ui->checkbox(0,array(
			'name' => 'JCB',
			'checked' => $this->settings['JCB'],
			'label'=>__('JCB','Shopp')
		));		
		
		$this->ui->p(0,array(
			'content' => '<span style="width: 300px;">Limit acceptable card types from above list</span>'
		));
		$this->ui->checkbox(0,array(
			'name' => 'NotificationEmail',
			'checked' => ($this->settings['NotificationEmail'] == "on"),
			'label' => sprintf(__('Send e-mail to customer a receipt based on your account settings.','Shopp'))
		));
		$this->ui->checkbox(0,array(
			'name' => 'testmode',
			'checked' => ($this->settings['testmode'] == "on"),
			'label' => sprintf(__('Test Mode','Shopp'))
		));
		//$script = "var tc ='ClickandPledge';jQuery(document).bind(tc+'Settings',function(){var $=jqnc(),p='#'+tc+'-',v=$(p+'account_id'),t=$(p+'guid');v.change(function(){v.prop('checked')?t.parent().fadeIn('fast'):t.parent().hide();}).change();});";
		//$this->ui->behaviors( $script );		
	}	 
} // END class ClickandPledge

?>