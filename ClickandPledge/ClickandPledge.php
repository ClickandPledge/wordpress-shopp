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
	var $cards = array("Visa","MC","Disc","Amex","JCB");
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
	var $allowed_Periodicity = array( 'Week', '2 Weeks', 'Month', '2 Months', 'Quarter', '6 Months', 'Year' );
	var $country_code = array( 'DE' => '276','AT' => '040','BE' => '056','CA' => '124','CN' => '156','ES' => '724','FI' => '246','FR' => '250','GR' => '300', 'IT' => '380','JP' => '392','LU' => '442','NL' => '528','PL' => '616','PT' => '620','CZ' => '203','GB' => '826','SE' => '752','CH' => '756','DK' => '208','US' => '840','HK' => '344','NO' => '578','AU' => '036','SG' => '702','IE' => '372','NZ' => '554','KR' => '410','IL' => '376','ZA' => '710','NG' => '566','CI' => '384','TG' => '768','BO' => '068','MU' => '480','RO' => '642','SK' => '703','DZ' => '012','AS' => '016','AD' => '020','AO' => '024','AI' => '660','AG' => '028','AR' => '032','AM' => '051','AW' => '533','AZ' => '031','BS' => '044','BH' => '048','BD' => '050','BB' => '052','BY' => '112','BZ' => '084','BJ' => '204','BT' => '060','56' => '064','BW' => '072','BR' => '076','BN' => '096','BF' => '854','MM' => '104','BI' => '108','KH' => '116','CM' => '120','CV' => '132','CF' => '140','TD' => '148','CL' => '152','CO' => '170','KM' => '174','CD' => '180','CG' => '178','CR' => '188','HR' => '191','CU' => '192','CY' => '196','DJ' => '262','DM' => '212','DO' => '214','TL' => '626','EC' => '218','EG' => '818','SV' => '222','GQ' => '226','ER' => '232','EE' => '233','ET' => '231','FK' => '238','FO' => '234','FJ' => '242','GA' => '266','GM' => '270','GE' => '268','GH' => '288','GD' => '308','GL' => '304','GI' => '292','GP' => '312','GU' => '316','GT' => '320','GG' => '831','GN' => '324','GW' => '624','GY' => '328','HT' => '332','HM' => '334','VA' => '336','HN' => '340','IS' => '352','IN' => '356','ID' => '360','IR' => '364','IQ' => '368','IM' => '833','JM' => '388','JE' => '832','JO' => '400','KZ' => '398','KE' => '404','KI' => '296','KP' => '408','KW' => '414','KG' => '417','LA' => '418','LV' => '428','LB' => '422','LS' => '426','LR' => '430','LY' => '434','LI' => '438','LT' => '440','MO' => '446','MK' => '807','MG' => '450','MW' => '454','MY' => '458','MV' => '462','ML' => '466','MT' => '470','MH' => '584','MQ' => '474','MR' => '478','HU' => '348','YT' => '175','MX' => '484','FM' => '583','MD' => '498','MC' => '492','MN' => '496','ME' => '499','MS' => '500','MA' => '504','MZ' => '508','NA' => '516','NR' => '520','NP' => '524','BQ' => '535','NC' => '540','NI' => '558','NE' => '562','NU' => '570','NF' => '574','MP' => '580','OM' => '512','PK' => '586','PW' => '585','PS' => '275','PA' => '591','PG' => '598','PY' => '600','PE' => '604','PH' => '608','PN' => '612','PR' => '630','QA' => '634','RE' => '638','RU' => '643','RW' => '646','BL' => '652','KN' => '659','LC' => '662','MF' => '663','PM' => '666','VC' => '670','WS' => '882','SM' => '674','ST' => '678','SA' => '682','SN' => '686','RS' => '688','SC' => '690','SL' => '694','SI' => '705','SB' => '090','SO' => '706','GS' => '239','LK' => '144','SD' => '729','SR' => '740','SJ' => '744','SZ' => '748','SY' => '760','TW' => '158','TJ' => '762','TZ' => '834','TH' => '764','TK' => '772','TO' => '776','TT' => '780','TN' => '788','TR' => '792','TM' => '795','TC' => '796','TV' => '798','UG' => '800','UA' => '804','AE' => '784','UY' => '858','UZ' => '860','VU' => '548','VE' => '862','VN' => '704','VG' => '092','VI' => '850','WF' => '876','EH' => '732','YE' => '887','ZM' => '894','ZW' => '716','AL' => '008','AF' => '004','AQ' => '010','BA' => '070','BV' => '074','IO' => '086','BG' => '100','KY' => '136','CX' => '162','CC' => '166','CK' => '184','GF' => '254','PF' => '258','TF' => '260','AX' => '248','CW' => '531','SH' => '654','SX' => '534','SS' => '728','UM' => '581');
	 
	var $shoppmeta = array();
	function __construct () {
		parent::__construct();	
	
		if(is_admin() && ($_GET['page'] == 'shopp-system' || $_GET['page'] == 'shopp-system-payments')) {
			
			wp_register_script( 'clickandpledge-admin-script', plugins_url( '/js/payment_admin_validations.js', __FILE__ ) );
			wp_enqueue_script( 'clickandpledge-admin-script' );			
			wp_localize_script('clickandpledge-admin-script', 'mycnpAjax', array('ajax_url' =>admin_url('admin-ajax.php')));
       	if(is_admin() && ($_GET['id'] == 'ClickandPledge')) {	
			$active_gateways = shopp_setting('active_gateways');
			if ( ! $active_gateways ) $cnpgateways = array();
			else $cnpgateways = explode(',', $active_gateways);
			$cnpgtwyname = "ClickandPledge";
				if (!in_array($cnpgtwyname, $cnpgateways) )  {  
				$position = array_push( $cnpgateways,$cnpgtwyname);
				
				shopp_set_setting('active_gateways', join(',', $cnpgateways));
				
			}
		}
		}
	 
		    global $wpdb;
			$shpsettingstable_name = self::get_cnp_wpshpsettingsinfo();
			$shptokentable_name    = self::get_cnp_wpshptokeninfo();
			$shpaccountstable_name = self::get_cnp_wpshpaccountsinfo();
			$shpcharset_collate    = $wpdb->get_charset_collate();
			
		 $settingssql = "CREATE TABLE $shpsettingstable_name (
			  `cnpsettingsinfo_id` int(11) NOT NULL AUTO_INCREMENT,
			  `cnpsettingsinfo_clientid` varchar(255) NOT NULL,
			  `cnpsettingsinfo_clentsecret` varchar(255) NOT NULL,
			  `cnpsettingsinfo_granttype` varchar(255) NOT NULL,
			  `cnpsettingsinfo_scope` varchar(255) NOT NULL,
			   PRIMARY KEY (`cnpsettingsinfo_id`)
			) $shpcharset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $settingssql );
			
			$tokensql = "CREATE TABLE $shptokentable_name (
 			`cnptokeninfo_id` int(11) NOT NULL AUTO_INCREMENT,
			`cnptokeninfo_username` varchar(255) NOT NULL,
			`cnptokeninfo_code` varchar(255) NOT NULL,
			`cnptokeninfo_accesstoken` text NOT NULL,
			`cnptokeninfo_refreshtoken` text NOT NULL,
			`cnptokeninfo_date_added` datetime NOT NULL,
			`cnptokeninfo_date_modified` datetime NOT NULL,
			 PRIMARY KEY (`cnptokeninfo_id`)
			) $shpcharset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $tokensql );
			
			$accountssql = "CREATE TABLE $shpaccountstable_name (
 			  `cnpaccountsinfo_id` int(11) NOT NULL AUTO_INCREMENT,
			  `cnpaccountsinfo_orgid` varchar(100) NOT NULL,
  			  `cnpaccountsinfo_orgname` varchar(250) NOT NULL,
  	          `cnpaccountsinfo_accountguid` varchar(250) NOT NULL,
			  `cnpaccountsinfo_userfirstname` varchar(250) NOT NULL,
			  `cnpaccountsinfo_userlastname` varchar(250) NOT NULL,
			  `cnpaccountsinfo_userid` varchar(250) NOT NULL,
			  `cnpaccountsinfo_crtdon` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `cnpaccountsinfo_crtdby` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`cnpaccountsinfo_id`)
			) $shpcharset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $accountssql );
			$cnpsql= "SELECT count(*) FROM ". $shpsettingstable_name;
			$rowcount = $wpdb->get_var( $cnpsql );
			if($rowcount == 0)
			{
					$cnpfldname = 'connectwordpressplugin';
					$cnpfldtext = 'zh6zoyYXzsyK9fjVQGd8m+ap4o1qP2rs5w/CO2fZngqYjidqZ0Fhbhi1zc/SJ5zl';
					$cnpfldpwd = 'password';
					$cnpfldaccsid = 'openid profile offline_access';


					$wpdb->insert( 
						$shpsettingstable_name, 
						array( 
							'cnpsettingsinfo_clientid' => $cnpfldname, 
							'cnpsettingsinfo_clentsecret' => $cnpfldtext, 
							'cnpsettingsinfo_granttype' => $cnpfldpwd,
							'cnpsettingsinfo_scope' => $cnpfldaccsid,
						) 
					);
			}
		//print_r($this->baseop);
		$this->setup('cnpaccount_id','guid','cards','testmode','currency');
		
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
		
	   add_action( 'wp_ajax_cnp_wpshpgetconnectcode', array($this, 'cnp_wpshpgetconnectcode'));
	   add_action( 'wp_ajax_nopriv_cnp_wpshpgetconnectcode',  array($this,'cnp_wpshpgetconnectcode'));
	   add_action( 'wp_ajax_cnp_WPSHOPPgetAccounts', array($this, 'cnp_wpshpgetcnpaccounts'));
	   add_action( 'wp_ajax_nopriv_cnp_WPSHOPPgetAccounts',  array($this,'cnp_wpshpgetcnpaccounts'));
	   add_action( 'wp_ajax_getShoppCnPDeleteAccountList', array($this,'cnp_getShoppCnPDeleteAccountList'));
	   add_action( 'wp_ajax_nopriv_getShoppCnPDeleteAccountList', array($this,'cnp_getShoppCnPDeleteAccountList'));
	   add_action( 'wp_ajax_getshoppCnPAccountList', array($this,'cnp_getshoppCnPAccountList'));
	   add_action( 'wp_ajax_nopriv_getshoppCnPAccountList', array($this,'cnp_getshoppCnPAccountList'));
	  
	   add_action( 'wp_ajax_getCnPUserconectAccountList', array($this,'cnp_getCnPUserConnectAccountList'));
		
	   add_action( 'wp_ajax_nopriv_getCnPUserconectAccountList', array($this,'cnp_getCnPUserConnectAccountList'));
		
	   add_action('shopp_clickandpledge_sale',array(&$this,'sale'));
	   add_action('shopp_clickandpledge_auth',array(&$this,'auth'));
	   add_action('shopp_clickandpledge_capture',array(&$this,'capture'));
		$Items = shopp_cart_items();
		//print_r($Items);
		add_action('shopp_checkout_gateway_inputs',array(&$this,'Recurring_function') ,7);
	    add_action('shopp_process_checkout', array(&$this,'checkout'),8);
	    add_action('shopp_init_confirmation',array(&$this,'confirmation'),9);
	}
	function my_gateway_inputs_filter( $content ) {
    // add additional input for my gateway
    $content .= '<div id="my_gateway_input"><input type="text" /></div>';
    return $content;
}
	public function cnp_getCnPUserConnectAccountList() {
		 $cnpwjbaccountid = $_REQUEST['cnpacid']; 
		 $cnpwjbcamp      = $_REQUEST['cnpcamp'];
		 $cnprtrntxt      = $this->getshpCnPConnectCampaigns($cnpwjbaccountid,$cnpwjbcamp);
		 $confcntcam = $this->settings['ConnectCampaignAlias'];
	
		foreach($cnprtrntxt as $cnpcampngs)
		{
			if($confcntcam == $cnprtrntxt[$cnpcampngs->alias]){$selectacnt ="selected='selected'";}
					 	 $camrtrnval .= "<option value='".$cnpcampngs->alias."' ".$selectacnt.">".$cnpcampngs->name." (".$cnpcampngs->alias.")</option>"; }
				
	    
	     $cnprtrnpaymentstxt = $this->getShpCnPactivePaymentList($cnpwjbaccountid);
		
		    $responsearramex              =  $cnprtrnpaymentstxt->GetAccountDetailResult->Amex;
		    $responsearrJcb               =  $cnprtrnpaymentstxt->GetAccountDetailResult->Jcb;
		    $responsearrMaster            =  $cnprtrnpaymentstxt->GetAccountDetailResult->Master;
	        $responsearrVisa              =  $cnprtrnpaymentstxt->GetAccountDetailResult->Visa;
		    $responsearrDiscover          =  $cnprtrnpaymentstxt->GetAccountDetailResult->Discover;
			$responsearrecheck            =  $cnprtrnpaymentstxt->GetAccountDetailResult->Ach;
			$responsearrCustomPaymentType =  $cnprtrnpaymentstxt->GetAccountDetailResult->CustomPaymentType;
			$cmpacntacptdcards .= '			
			<ul style="margin:0px;">
            	<li><label for="clickandpledge-cnpcreditcard""><input type="checkbox" id="clickandpledge-cnpcreditcard"" class="checkbox_active" value="CreditCard" name="clickandpledge-cnpcreditcard""  onclick="block_creditcard(this.checked);" ';
			if(($responsearramex == true || $responsearrJcb == true || $responsearrMaster== true || $responsearrVisa ==true || $responsearrDiscover == true) )
			{$cmpacntacptdcards .= 'checked="checked"';}
		     $cmpacntacptdcards .= 'checked="checked" disabled="disabled"> Credit Card</label>
			 <script>jQuery("#wpjobboard_clickandpledge_Paymentmethods_Visa").val("");
				jQuery("#wpjobboard_clickandpledge_Paymentmethods_Amex").val("");
				jQuery("#wpjobboard_clickandpledge_Paymentmethods_Discover").val("");
				jQuery("#wpjobboard_clickandpledge_Paymentmethods_master").val("");
				jQuery("#wpjobboard_clickandpledge_Paymentmethods_Jcb").val("");
				jQuery("#wpjobboard_clickandpledge_Paymentmethods_eCheck").val("");</script>
			 			<div class="tracceptedcards" style="padding: 12px 26px;">
			 				<ul class="accounts">														
									<li class="account">Accepted Credit Cards</li>';
								if($responsearrVisa == true){
								    $cmpacntacptdcards .= '
							      	<li class="account"><label for="clickandpledge-cnpvisa">
									<input type="Checkbox" name="clickandpledge-cnpvisa" id="clickandpledge-cnpvisa"';
									if(isset($responsearrVisa)){ $cmpacntacptdcards .='checked="checked "'; }
									 $cmpacntacptdcards .= 'value="on" checked="checked" disabled="disabled">Visa</label></li>';
									$cmpacntacptdcards .= '<script>jQuery("#clickandpledge-hdncnpvisa").val("yes");</script>';
								  }
								
								if($responsearramex == true){
									$cmpacntacptdcards .= '
									<li><label for="clickandpledge-cnpamex">
									<input type="Checkbox" name="clickandpledge-cnpamex" id="clickandpledge-cnpamex"';
									if(isset($responsearramex)){ $cmpacntacptdcards .='checked="checked"'; }
									$cmpacntacptdcards .= 'value="on" checked="checked" disabled="disabled">American Express</label></li>';
									$cmpacntacptdcards .= '<script>jQuery("#clickandpledge-hdncnpamex").val("yes");</script>';
								}
								
								if($responsearrDiscover == true){
								 $cmpacntacptdcards .= '
									<li><label for="clickandpledge-cnpdisc">
									<input type="Checkbox" name="clickandpledge-cnpdisc" id="clickandpledge-cnpdisc"'; 
									if(isset($responsearrDiscover)){ $cmpacntacptdcards .='checked="checked"'; }
										$cmpacntacptdcards .= ' value="on" checked="checked" disabled="disabled">Discover</label></li>';
									$cmpacntacptdcards .= '<script>jQuery("#clickandpledge-hdncnpdisc").val("yes");</script>';
								}
								
								if($responsearrMaster == true){
								  $cmpacntacptdcards .= '
									<li><label for="clickandpledge-cnpmc">
									<input type="Checkbox" name="clickandpledge-cnpmc" id="clickandpledge-cnpmc"';
									if(isset($responsearrMaster)){ $cmpacntacptdcards .='checked="checked"'; }
									$cmpacntacptdcards .= ' value="on"  checked="checked" disabled="disabled">MasterCard</label></li>';
									$cmpacntacptdcards .= '<script>jQuery("#clickandpledge-hdncnpmc").val("yes");</script>';
								}
								
								if($responsearrJcb == true){
								  $cmpacntacptdcards .= '
									<li><label for="clickandpledge-cnpjcb">
									
									<input type="Checkbox" name="clickandpledge-cnpjcb" id="clickandpledge-cnpjcb"';
									if(isset($responsearrJcb)){ $cmpacntacptdcards .='checked="checked"'; }
									$cmpacntacptdcards .= ' value="on" checked="checked" disabled="disabled">JCB</label></li>';
					$cmpacntacptdcards .= '<script>jQuery("#clickandpledge-hdncnpjcb").val("yes");</script>';
								}
								
			$cmpacntacptdcards .= '</ul></div></li>';
			
			if($responsearrecheck == true){
			$cmpacntacptdcards .='<li><label for="clickandpledge-cnpecheck"><input type="checkbox" value="on" id=clickandpledge-cnpecheck" class="checkbox_active" name="clickandpledge-cnpecheck" onclick="block_echek(this.checked);"';
				if(isset($responsearrecheck)){ $cmpacntacptdcards .='checked="checked"'; }
				 $cmpacntacptdcards .= ' checked="checked" disabled="disabled">eCheck</label></li>';
		$cmpacntacptdcards.='<script>jQuery("#clickandpledge-hdncnpecheck").val("yes");</script>';
			}else
			{
				$cmpacntacptdcards .= '<input type="hidden" value="" name="clickandpledge-hdncnpecheck" id="clickandpledge-hdncnpecheck">
				<script>jQuery("#clickandpledge-hdncnpecheck").val("");</script>';
				
			}
			
					$cmpacntacptdcards .= '</ul><input type="hidden" value="'.$responsearrCustomPaymentType.'" name="cnpcp" id="cnpcp">||'.$responsearrCustomPaymentType;
		
	  $rtntxt = $camrtrnval."||".$cmpacntacptdcards;
	  echo $rtntxt;
	  die();
	}
	function cnp_getShoppCnPDeleteAccountList()
	{
		echo $rtncnpdata = self::delete_wpshpcnpaccountslist();
		echo $cnptransactios = self::delete_cnpwpshptransactions();
	
	}
	
	public function cnp_wpshpgetconnectcode(){
  	    $cnpemailaddress = $_REQUEST['cnpemailid'];
		$response =	wp_remote_get('https://api.cloud.clickandpledge.com/users/requestcode', array('headers' => array('content-type' => 'application/x-www-form-urlencoded', 'email' => $cnpemailaddress)) );
	   try {
			 $responsebody = wp_remote_retrieve_body($response);
			 echo $responsebody;

			} catch ( Exception $ex ) {
				$json = null;
			}
	
 
      wp_die(); // ajax call must die to avoid trailing 0 in your response
   } 
	public static function get_cnpwpshptransactions($cnpemailid,$cnpcode)
	{
		global $wpdb;
		
		$table_name = self::get_cnp_wpshpsettingsinfo();
        $sql = "SELECT * FROM ". $table_name;
        $results = $wpdb->get_results($sql, ARRAY_A);

        $count = sizeof($results);
        for($i=0; $i<$count; $i++){
			 $password="password";
			 $cnpsecret = openssl_decrypt($results[$i]['cnpsettingsinfo_clentsecret'],"AES-128-ECB",$password);
			 $rtncnpdata = "client_id=".$results[$i]['cnpsettingsinfo_clientid']."&client_secret=". $cnpsecret."&grant_type=".$results[$i]['cnpsettingsinfo_granttype']."&scope=".$results[$i]['cnpsettingsinfo_scope']."&username=".$cnpemailid."&password=".$cnpcode;
        }
        return $rtncnpdata;
	}
	
	public static function delete_cnpwpshptransactions()
	{
		global $wpdb;
		
        $table_name = self::get_cnp_wpshptokeninfo();
        $wpdb->query("DELETE FROM ". $table_name);
	}
	public static function  insrt_cnpwpshptokeninfo($cnpemailid, $cnpcode, $cnptoken, $cnprtoken)
	{
		  global $wpdb;
         $table_name = self::get_cnp_wpshptokeninfo();
         $wpdb->insert($table_name, array('cnptokeninfo_username' => $cnpemailid, 
					'cnptokeninfo_code' => $cnpcode, 
					'cnptokeninfo_accesstoken' => $cnptoken,
					'cnptokeninfo_refreshtoken' => $cnprtoken));
		
            $id = $wpdb->get_var("SELECT LAST_INSERT_ID()");
			
        return $id;
	}
	public static function delete_wpshpcnpaccountslist()
	{
		global $wpdb;
        $table_name = self::get_cnp_wpshpaccountsinfo();
	
        $wpdb->query("DELETE FROM ". $table_name);
	}
	public static function insert_cnpwpshpaccountsinfo($cnporgid,$cnporgname,$cnpaccountid,$cnpufname,$cnplname,$cnpuid){
        global $wpdb;
        $table_name = self::get_cnp_wpshpaccountsinfo();
      
            $wpdb->insert($table_name, array('cnpaccountsinfo_orgid' => $cnporgid, 
					'cnpaccountsinfo_orgname' => $cnporgname, 
					'cnpaccountsinfo_accountguid' => $cnpaccountid,
					'cnpaccountsinfo_userfirstname' => $cnpufname,
					'cnpaccountsinfo_userlastname'=> $cnplname,
					'cnpaccountsinfo_userid'=> $cnpuid));
            $id = $wpdb->get_var("SELECT LAST_INSERT_ID()");
			
        return $id;
    }
	public function cnp_wpshpgetcnpaccounts(){
		
		$cnpemailid = $_REQUEST['wpjbcnpemailid'];
		$cnpcode    = $_REQUEST['wpjbcnpcode'];
		$cnptransactios = self::get_cnpwpshptransactions($cnpemailid,$cnpcode);
	
		$responseap = wp_remote_post("https://aaas.cloud.clickandpledge.com/idserver/connect/token", array('headers' => array('content-type' => 'application/x-www-form-urlencoded'),'body' =>$cnptransactios) );
		try {
 
        // Note that we decode the body's response since it's the actual JSON feed
        	$cnptokendata = json_decode(  wp_remote_retrieve_body($responseap) );
			
			if(!isset($cnptokendata->error)){
			$cnptoken = $cnptokendata->access_token;
			$cnprtoken = $cnptokendata->refresh_token;
			$cnptransactios = self::delete_cnpwpshptransactions();
			$rtncnpdata =	self::insrt_cnpwpshptokeninfo($cnpemailid,$cnpcode,$cnptoken,$cnprtoken);	
			
			if($rtncnpdata != "")
			{
				$response1 =	wp_remote_get('https://api.cloud.clickandpledge.com/users/accountlist', array('headers' => array('accept' => 'application/json','content-type' => 'application/json', 'authorization' => 'Bearer'." ".$cnptoken)) );
	  
		 try {
			 	$cnpAccountsdata = json_decode(  wp_remote_retrieve_body($response1) );
			    $cnptransactios = self::delete_wpshpcnpaccountslist();
					
					foreach($cnpAccountsdata as $cnpkey =>$cnpvalue)
					{
						 $cnporgid = $cnpvalue->OrganizationId;
						 $cnporgname = addslashes($cnpvalue->OrganizationName);
						 $cnpaccountid = $cnpvalue->AccountGUID;
						 $cnpufname = addslashes($cnpvalue->UserFirstName);
						 $cnplname = addslashes($cnpvalue->UserLastName);
						 $cnpuid = $cnpvalue->UserId;
						 $cnptransactios = self::insert_cnpwpshpaccountsinfo($cnporgid,$cnporgname,$cnpaccountid,$cnpufname,$cnplname,$cnpuid);	
					
					}
					
				   echo "success";
 
			} catch ( Exception $ex ) {
				$json = null;
			} 
		}
			}
			else{
				echo "error";
			}
 
    } catch ( Exception $ex ) {
        $json = null;
    } 
		
		
		die();
	}	
	public static function get_cnp_wpshpsettingsinfo(){
        global $wpdb;
        return $wpdb->prefix . "cnp_wp_shpcnpsettingsinfo";
    }

	 public static function get_cnp_wpshptokeninfo(){
        global $wpdb;
        return $wpdb->prefix . "cnp_wp_shpcnptokeninfo";
    }

	 public static function get_cnp_wpshpaccountsinfo(){
        global $wpdb;
        return $wpdb->prefix . "cnp_wp_shpcnpaccountsinfo";
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
	  // add_action('shopp_checkout_gateway_inputs',  array(&$this,'Recurring_function'),7);
		add_action('shopp_process_checkout', array(&$this,'checkout'),9);
		add_action('shopp_init_confirmation',array(&$this,'confirmation'));
		
	}  
	function checkout () {
				
		$this->Order->Billing->cardtype = "Click & Pledge";
		$this->Order->confirm = true;
}
function Recurring_function () { //echo "<pre>";print_r($this->settings);echo '<br /><br />';
	
		wp_register_script( 'clickandpledge-admin-script', plugins_url( '/js/payment_checkout_validations.js', __FILE__ ) );
		wp_enqueue_script( 'clickandpledge-admin-script' );

		$recurringDiv = "";
		$recurringtypearray =array();$recurringperiodicityarray= array();
		if($this->settings['recurringtype_installment'] == "on"){ array_push($recurringtypearray,"Installment");}
		if($this->settings['recurringtype_subscription'] == "on"){ array_push($recurringtypearray,"Subscription");}
		if($this->settings['Week'] == "on"){ array_push($recurringperiodicityarray,"Week");}
		if($this->settings['2 Weeks'] == "on"){ array_push($recurringperiodicityarray,"2 Weeks");}
		if($this->settings['Month'] == "on"){ array_push($recurringperiodicityarray,"Month");}
		if($this->settings['2 Months'] == "on"){ array_push($recurringperiodicityarray,"2 Months");}
		if($this->settings['Quarter'] == "on"){ array_push($recurringperiodicityarray,"Quarter");}
		if($this->settings['6 Months'] == "on"){ array_push($recurringperiodicityarray,"6 Months");}
		if($this->settings['Year'] == "on"){ array_push($recurringperiodicityarray,"Year");}			
								
		if(isset($this->settings['hdnCreditCard']) && $this->settings['hdnCreditCard'] =="yes"){
			    $paymentMethods['Credit Card'] = 'Credit Card';
        }
		if(isset($this->settings['hdncnpeCheck']) && $this->settings['hdncnpeCheck'] =="yes"){
			    $paymentMethods['eCheck'] = 'eCheck';
        }
		
		if(isset($this->settings['CustomPayment']) && $this->settings['CustomPayment'] =="on"){ 
				 $customtypes = explode(";",$this->settings['Custom_Payment_title']);
				 
				if(count($customtypes) > 0) {
				     foreach($customtypes as $custompays)
					{
						if(trim($custompays) != '') {
							$paymentMethods[$custompays] = trim($custompays);
							$customtypes[] = trim($custompays);
						} 
					 }
					
				 } 
				 }		

		if($this->settings['payment_options_OneTimeOnly'] == 'on' || $this->settings['payment_options_recurring'] == 'on')
		{			
			
			$recurringDiv .=  "<li>";//start recStruct li
			$recurringDiv .=  "<input type='hidden' name='CnP_pay' id='CnP_pay' value='YES'>";
			$recurringDiv .=  "<div id='recStruct'>";
			$recurringDiv .=  "<div class='inline' id='cnp_RecurringfieldLabel' style='margin-top: 10px;padding: 0 0px 10px 0px;'><b>Click & Pledge</b></div>";// "Recurring Label";
			$defaultpayment = $this->settings['defaultpaymentmethod'];
			if($defaultpayment != 'Credit Card'){
			$recurringDiv .= ' <script type="text/javascript">
					     jQuery(document).ready(function() {
						 jQuery(".payment").hide();
						 });
				         </script>';
			};	
			$crtprdtype = "N";
			$crdItems = shopp_cart_items();
			foreach($crdItems as $i => $crdItem) {
				if($crdItem->type == 'Subscription')
				{
				$crtprdtype = "S";
			}}
			if($crtprdtype != "S"){
				if(($this->settings['payment_options_recurring'] == 'on') && ($this->settings['payment_options_OneTimeOnly'] == 'on'))
		        {
			$recurringDiv .=  "<div class='left' id='recurringpaymentoptions-div' style='padding: 0 0px 10px 0px;'><label for='payment_options_label'>Payment options:</label></div>";
			$recurringDiv .=  "<div class='right'>";
			
			$def_selected = '';
	if($this->settings['payment_options_OneTimeOnly'] == 'on' && $this->settings['defaultpaymentoptions'] == 'One Time Only')
		$def_selected = 'checked=checked';							
				$recurringDiv .=  "<input type='radio' name='payment_options_recurringtype' id='payment_options_recurringtype' value='onetimeonly' class='paymentoptionsrecurringtype' ".$def_selected." style='width: 10%;float: left;'>";			
				$recurringDiv .=  "<span id='payment_options_onetimeonly_label' style='font-size: 0.8125em;font-weight: normal;padding-top: 0.6em;margin-top: -7px;'>One Time Only</span>";
	
	$def_selected1 = '';
	if($this->settings['payment_options_recurring'] == 'on' && $this->settings['defaultpaymentoptions'] == 'Recurring')	
		$def_selected1 = 'checked=checked';	
				$recurringDiv .=  "<input type='radio' name='payment_options_recurringtype' id='payment_options_recurringtype' value='recurring' class='paymentoptionsrecurringtype' ".$def_selected1." style='width: 10%;float: left;'>";
				$recurringDiv .=  "<span id='payment_options_recurring_label' style='font-size: 0.8125em;font-weight: normal;padding-top: 0.6em;margin-top: -7px;'>Recurring</span>";
			
			$recurringDiv .=  "</div>";
				}
					if($this->settings['payment_options_recurring'] == 'on'){
					if($this->settings['payment_options_recurring'] == 'on' && $this->settings['payment_options_OneTimeOnly'] != 'on' ){	
						$recurringDiv .=  "<input type='hidden' name='payment_options_recurringtype' id='payment_options_recurringtype' value='recurring' >";
					}
			$recurringDiv .= "<div id='recurringtypes-block'>";
			$recurringDiv .= "<div class='left' id='recurringtypes-div' style='padding: 0 0px 10px 0px;'>";
			$recurringDiv .= "<label for='recurring_types_label'>Recurring types:</label>";
			$recurringDiv .= "</div>";
			$recurringDiv .= "<div class='right'>";
					if(count($recurringtypearray)>1)
					{
						
						$recurringDiv .= "<select name='RecurringType' id='RecurringType'>";
						foreach($recurringtypearray as $key=>$value)
						{$selected = "";
							if($this->settings['defaultrecurringoptions']  == $value)	$selected = "selected";
							$recurringDiv .= "<option value='".$value."' ".$selected.">".$value."</option>";
						}
						$recurringDiv .= "</select>";
					}
					else
					{
						$recurringtypemenu = "";
						if($this->settings['recurringtype_subscription'] == "on")	$recurringtypemenu = "Subscription";
						if($this->settings['recurringtype_installment'] == "on")	$recurringtypemenu = "Installment";
						
						$recurringDiv .= "<label for='RecurringType'>".$recurringtypemenu."</label>";
						$recurringDiv .= "<input type='hidden' name='RecurringType' id='RecurringType' value='".$recurringtypemenu."'>";
						
					}
				$recurringDiv .=  "</div>";
							
				$recurringDiv .= "<div class='left' id='RecurringPeriodicity-div' style='padding: 0 0px 10px 0px;'>";
					$recurringDiv .= "<label for='recurringperiodicity_label'>Periodicity:</label>";
				$recurringDiv .= "</div>";
				$recurringDiv .= "<div class='right'>";
					if(count($recurringperiodicityarray)>1)
					{
						$recurringDiv .= "<select name='RecurringPeriodicity' id='RecurringPeriodicity' class='required'>";
						foreach($recurringperiodicityarray as $key=>$value)
						{
							$recurringDiv .= "<option value='".$value."'>".$value."</option>";
						}
						$recurringDiv .= "</select>";
					}
					else if(count($recurringperiodicityarray) == 1)
					{
						foreach($recurringperiodicityarray as $key=>$value)
						{
							$RecurringPeriodicitymenu = $value;
						
							$recurringDiv .= "<label for='RecurringPeriodicity' id='RecurringPeriodicity'>".$RecurringPeriodicitymenu."</label>";
							$recurringDiv .= "<input type='hidden' name='RecurringPeriodicity' id='RecurringPeriodicity' value='".$RecurringPeriodicitymenu."'>";
						}
					}
					/*else
					{
						$RecurringPeriodicitymenu = $this->settings['recurringperiodicity'];
						
						$recurringDiv .= "<label for=='RecurringPeriodicity' id='RecurringPeriodicity'>".$RecurringPeriodicitymenu."</label>";
						$recurringDiv .= "<input type='hidden' name='RecurringPeriodicity' id='RecurringPeriodicity' value='".$RecurringPeriodicitymenu."'>";
						
					}*/
				$recurringDiv .=  "</div>";
				
				$recurringDiv .= "<div class='left' id='noofpayments-div' style='padding: 0 0px 10px 0px;'>";
					$recurringDiv .= "<label for='noofpayments_label'>Number of payments:</label>";
				$recurringDiv .= "</div><div class='right'>";
				if(($this->settings['noofpaymentsvalue'] != "Indefinite Only") && ($this->settings['noofpaymentsvalue'] != "Fixed Number - No Change Allowed"))
				{
				$recurringDiv .= "<input id='maxinstallments_no' name='maxinstallments_no' type='hidden'  value='".$this->settings['maxnoofinstallments']."'>";
						}
					$noofpaymentsvalue = "";
					if($this->settings['noofpaymentsvalue'] == "Indefinite Only")
					{
						if($this->settings['defaultrecurringoptions'] == "Subscription")	$noofpaymentsvalue = "999";
						if($this->settings['defaultrecurringoptions'] == "Installment")		$noofpaymentsvalue = "998";
						
						$recurringDiv .= "<span id='installments_noSpan'> Indefinite Recurring Only </span>";
						
						$recurringDiv .= "<input id='installments_no' name='installments_no' type='hidden' value='".$noofpaymentsvalue."'>";
					}
					if($this->settings['noofpaymentsvalue'] == "Open Field Only")
					{
						$defAsgn = "";
						if($this->settings['defnoofpayments']!="")	$defAsgn = $this->settings['defnoofpayments'];
						$recurringDiv .=  "<input id='installments_no' name='installments_no' type='text' maxlength='3' value=".$defAsgn.">";	
					}
					if($this->settings['noofpaymentsvalue'] == "Indefinite + Open Field Option")
					{
						$defAsgn = "";
						if($this->settings['defnoofpayments']!="")	$defAsgn = $this->settings['defnoofpayments'];
						$recurringDiv .=  "<input id='installments_no' name='installments_no' type='text' maxlength='3' value=".$defAsgn.">";	
					}
					if($this->settings['noofpaymentsvalue'] == "Fixed Number - No Change Allowed")
					{
						$recurringDiv .= "<span id='spninstallments_no'>".$this->settings['defnoofpayments']."</span>";
						$recurringDiv .=  "<input id='installments_no' name='installments_no' type='hidden' value=".$this->settings['defnoofpayments'].">";	
					}
				$recurringDiv .=  "<input type='hidden' name='frntnoofpayments' id='frntnoofpayments' value='".$this->settings['noofpaymentsvalue']."'></div>";
									
			
			$recurringDiv .= "</div>";
			}
			}
			//	field_description
			$recurringDiv .= "<div class='inline' id='field_description' ><label style='font-weight:bold'>Payment Methods</label></div><div class='inline' id='field_description' style='padding:20px 0px 15px;border-bottom:1px solid #ddd;margin-bottom:10px;'>";
			
	//print_r($paymentMethods);
			if($crtprdtype == "S"){
				foreach($paymentMethods as $pkey => $pval) {
					
					if($pkey !='Credit Card' && $pkey !='eCheck')
					{
						unset($paymentMethods[$pkey]);
					}
				}
			$defaultpayment ="Credit Card";
			}
			
			foreach($paymentMethods as $pkey => $pval) {
				//if($crtprdtype != "S" && ($pkey =='Credit Card' || $pkey =='eCheck')){	
					if($pkey == $defaultpayment) {
					
					$recurringDiv .= '<input type="radio" id="payment_type" name="payment_type"  class="payment_typeClass" value="'.$pkey.'" checked="checked" style="width: 10%;float: left;">';
					$recurringDiv .=  "<span id='payment_options_onetimeonly_label' style='font-size: 0.8125em;font-weight: normal;padding-top: 0.6em;margin-top: -7px;'>".$pval."</span>";
					 } 
					else{
						
					 $recurringDiv .= '<input type="radio" id="payment_type" name="payment_type"  class="payment_typeClass" value="'.$pkey.'" style="width: 10%;float: left;">';
					 $recurringDiv .=  "<span id='payment_options_onetimeonly_label' style='font-size: 0.8125em;font-weight: normal;padding-top: 0.6em;margin-top: -7px;'>".$pval."</span>";
					 } 
			
					$recurringDiv .= ' <script type="text/javascript">
					     var simple = "#payment_type'.$pkey.'";
						
						 jQuery(simple).click(function(){
						 jQuery("#cnp_CreditCard_div").hide();					
					     jQuery("#cnp_eCheck_div").hide();
					     jQuery("#cnp_Custompay_div").show();
					  
						 });
				         </script>';
				      
                }
			$recurringDiv .= '<div class="clear"></div></div>';
			//credit card
			
			$ccdivdisplay = ($defaultpayment == 'Credit Card') ? 'block' : 'none';	
			/*$recurringDiv .= '<div style="display:'.$ccdivdisplay.'" id="cnp_CreditCard_div">fvdf</div>';*/
				//echeck
		$eCheckdivdisplay = ($defaultpayment == 'eCheck') ? 'block' : 'none';	
		$recurringDiv .= '<div style="display:'.$eCheckdivdisplay.'" id="cnp_eCheck_div">';
		/*$recurringDiv .= "<p><img src='".WP_PLUGIN_URL . "/" . plugin_basename( dirname(__FILE__)) . "/images/eCheck.png' title='eCheck' alt='eCheck'/></p>";*/
		if($this->settings['testmode'] == "Test")
		{
			$recurringDiv .= '<div class="" style="margin:0 0 10px;color:red;"><br>eCheck does not support test transactions</div>';
		}
			
			$recurringDiv .= '<div id="recurringtypes-block"><div class="left" id="recurringtypes-div" style="padding: 0 0px 10px 0px;"><label for="recurring_types_label">Account Type:</label></div><div class="right"><select name="clickandpledge_echeck_AccountType" id="clickandpledge_echeck_AccountType" title="Account Type" >
					<option value="SavingsAccount">SavingsAccount</option>
					<option value="CheckingAccount">CheckingAccount</option>
			  	</select></div></div><div class="clear"></div>';
			$recurringDiv .= '<div id="recurringtypes-block"><div class="left" id="recurringtypes-div" style="padding: 0 0px 10px 0px;"><label for="recurring_types_label">Name on Account:</label></div><div class="right">	<input type="text" data-clickandpledge="number" name="clickandpledge_echeck_NameOnAccount" id="clickandpledge_echeck_NameOnAccount" class="required AccountNumber"  placeholder="Name on Account"/></div></div><div class="clear"></div>';
			$recurringDiv .= '<div id="recurringtypes-block"><div class="left" id="recurringtypes-div" style="padding: 0 0px 10px 0px;"><label for="recurring_types_label">Type of ID:</label></div><div class="right">	<select name="clickandpledge_echeck_IdType" id="clickandpledge_echeck_IdType" title="Type of ID" ><option value="Driver">Driver</option>
			<option value="Military">Military</option><option value="State">State</option>
			</select></div></div><div class="clear"></div>';
			$recurringDiv .= '<div id="recurringtypes-block"><div class="left" id="recurringtypes-div" style="padding: 0 0px 10px 0px;"><label for="recurring_types_label">Check Type:</label></div><div class="right"> <select name="clickandpledge_echeck_CheckType" id="clickandpledge_echeck_CheckType" title="Check Type">
						<option value="Company">Company</option>
						<option value="Personal">Personal</option>
				  </select></div></div><div class="clear"></div>';
			$recurringDiv .= '<div id="recurringtypes-block"><div class="left" id="recurringtypes-div" style="padding: 0 0px 10px 0px;"><label for="recurring_types_label">Check Number:</label></div><div class="right"> <input type="text" data-clickandpledge="number" name="clickandpledge_echeck_CheckNumber" id="clickandpledge_echeck_CheckNumber" class="required CheckNumber"  placeholder="Check Number"/></div></div><div class="clear"></div>';
			$recurringDiv .= '<div id="recurringtypes-block"><div class="left" id="recurringtypes-div" style="padding: 0 0px 10px 0px;"><label for="recurring_types_label">Routing Number:</label></div><div class="right"> <input type="text" data-clickandpledge="number" name="clickandpledge_echeck_RoutingNumber" id="clickandpledge_echeck_RoutingNumber" class="required RoutingNumber"  placeholder="Routing Number"/></div></div><div class="clear"></div>';
			$recurringDiv .= '<div id="recurringtypes-block"><div class="left" id="recurringtypes-div" style="padding: 0 0px 10px 0px;"><label for="recurring_types_label">Account Number:</label></div><div class="right"> <input type="text" data-clickandpledge="number" name="clickandpledge_echeck_AccountNumber" id="clickandpledge_echeck_AccountNumber" class="required AccountNumber"  placeholder="Account Number"/></div>	</div><div class="clear"></div>';
			$recurringDiv .= '<div id="recurringtypes-block"><div class="left" id="recurringtypes-div" style="padding: 0 0px 10px 0px;"><label for="recurring_types_label">Retype Account Number:</label></div><div class="right"> <input type="text" data-clickandpledge="number" name="clickandpledge_echeck_retypeAccountNumber" id="clickandpledge_echeck_retypeAccountNumber" class="required AccountNumber" placeholder="Retype Account Number"/>	</div>	</div><div class="clear"></div>';
				
		
		$recurringDiv .= '</div></div>'; //eCheck Div End
			//echeck
			/*$recurringDiv .=  "<input type='radio' name='payment_type' id='payment_type' value='CreditCard' class='payment_typeClass' checked style='width: 10%;float: left;'>";			
			$recurringDiv .=  "<span id='payment_options_onetimeonly_label' style='font-size: 0.8125em;font-weight: normal;padding-top: 0.6em;margin-top: -7px;'>Credit Card</span>";
			if($this->settings['hdncnpeCheck'] == 'yes'){
			$recurringDiv .=  "<input type='radio' name='payment_type' id='payment_type' value='eCheck' class='payment_typeClass' checked style='width: 10%;float: left;'>";			
			$recurringDiv .=  "<span id='payment_options_onetimeonly_label' style='font-size: 0.8125em;font-weight: normal;padding-top: 0.6em;margin-top: -7px;'>eCheck</span>";
			}
								
			$recurringDiv .=  "<input type='radio' name='payment_type' id='payment_type' value='CustomPayment' class='payment_typeClass' style='width: 10%;float: left;'>";			
			$recurringDiv .=  "<span id='payment_options_onetimeonly_label' style='font-size: 0.8125em;font-weight: normal;padding-top: 0.6em;margin-top: -7px;'>Custom Payment</span>";*/
			
			if(($defaultpayment != 'Credit Card') && ($defaultpayment != 'eCheck')  ) $Custompaydivdisplay = 'block'; else $Custompaydivdisplay = 'none'; 
			if($this->settings['CustomPayment'] == 'on')
			{
				$recurringDiv .=  "<div class='inline Custom_Payment' style='display:".$Custompaydivdisplay."'>";
				$recurringDiv .=  "<div class='inline' id='cnp_RecurringfieldLabel' style='margin-top: 10px;padding: 0 0px 10px 0px;'><b>".$this->settings['Custom_Payment_label']."</b></div>";// "Recurring Label";

				$recurringDiv .= "<span id='PaymentNumber_label'>Payment Number:</span>";
				$recurringDiv .=  "<input id='PaymentNumber' name='PaymentNumber' type='text'>";	
				
				$recurringDiv .=  "</div>";
			}
				
			
			$recurringDiv .=  "</div>";
			$recurringDiv .=  "</li>";//end recStruct li
			$content .=$recurringDiv;
			return $content;
		}
	}
	/*	END */
	
	
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
	 function fetchsuscriptionperiodicity($cycle_period, $cycle_number)
	 {
			$Periodicity = '';
			switch($cycle_period)
			{
				case 'Day':
					if(in_array($cycle_number, array(7,14,30))) {
						if($cycle_number == 7) {
						$Periodicity = 'Week';
						}
						elseif($cycle_number == 14) {
						$Periodicity = '2 Weeks';
						}
						elseif($cycle_number == 30) {
						$Periodicity = 'Month';
						}
						
					}
					else
					{
						$Periodicity = $cycle_number . " Days";
					}
				break;
				case 'Week':
					$days = $cycle_number; //This will convert week into days
					if(in_array($days, array(1,2,4,8,12,24))) {
						if($cycle_number == 1) {
						$Periodicity = 'Week';
						}
						elseif($cycle_number == 2) {
						$Periodicity = '2 Weeks';
						}
						elseif($cycle_number == 4) {
						$Periodicity = 'Month';
						}
						elseif($cycle_number == 8) {
						$Periodicity = '2 Months';
						}
						elseif($cycle_number == 12) {
						$Periodicity = 'Quarter';
						}
						elseif($cycle_number == 24) {
						$Periodicity = '6 Months';
						}
						
					}
					else
					{
						$Periodicity = $cycle_number . " Week(s)";
					}
				break;
				case 'Month':
					if(in_array($cycle_number, array(1,2,3,6,12))) {
						if($cycle_number == 1) {
						$Periodicity = 'Month';
						}elseif($cycle_number == 2) {
						$Periodicity = '2 Months';
						}elseif($cycle_number == 3) {
						$Periodicity = 'Quarter';
						}elseif($cycle_number == 6) {
						$Periodicity = '6 Months';
						} else {
						$Periodicity = 'Year';
						}
					}
					else
					{
						$Periodicity = $cycle_number . " Months";
					}
				break;
				case 'Year':
					if(in_array($cycle_number, array(1))) {
						$Periodicity = 'Year';
					}
					else
					{
						$Periodicity = $cycle_number . " Years";
					}
				break;
				
			}
			return $Periodicity;
		}
	public function shpnumber_formatprc($number, $decimals = 2,$decsep = '', $ths_sep = '') {
		$parts = explode('.', $number);
		if(count($parts) > 1) {	return $parts[0].'.'.substr($parts[1],0,$decimals);	} else {return $number;	}
	}
function getPaymentXML( $Order, $case = '' ) 
		{
//print_r($Order);
	   $adminBar = new ShoppRegistration($form);
	   $reflector = new ReflectionObject($adminBar);
	   $nodes = $reflector->getProperty('form');
	   $nodes->setAccessible(true);
	   $newval = $nodes->getValue($adminBar);
	//print_r($newval);exit;
	 if ( empty($newval['payment_type']) ) {
				shopp_add_error( Shopp::__('The order amount changed and requires that you select a payment method.') );
			Shopp::redirect(Shopp::url(false,'checkout'));
		}
	 if($newval['payment_type'] !="Credit Card" && $newval['payment_type'] !="eCheck")  { 
			    if($newval['payment_options_recurringtype'] == 'recurring') {
				  shopp_add_error( Shopp::__('Sorry but recurring payments are not supported with this payment method'));
					Shopp::redirect(Shopp::url(false,'checkout'));
				}
			}
		 do_action('shopp_checkout_processed');
	     $cnpVersion = "3.000.000/WP:v".get_bloginfo('version')."/WPSHOPP:v".ShoppVersion::release();
	     $dom  = new DOMDocument('1.0', 'UTF-8');
         $root = $dom->createElement('CnPAPI', '');
         $root->setAttribute("xmlns","urn:APISchema.xsd");
         $root = $dom->appendChild($root);
         $version = $dom->createElement("Version",'0.1');
         $version = $root->appendChild($version);
		 $engine  = $dom->createElement('Engine', '');
         $engine  = $root->appendChild($engine);
		 
		 $application = $dom->createElement('Application','');
		 $application = $engine->appendChild($application);
    
		 $applicationid=$dom->createElement('ID','CnP:PaaS:Shopp');
		 $applicationid=$application->appendChild($applicationid);
			
		 $applicationname=$dom->createElement('Name','Salesforce:CnP_PaaS_SC_Shopp');
		 $applicationid=$application->appendChild($applicationname);
			
		 $applicationversion=$dom->createElement('Version',$cnpVersion);
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
		 $xmlcnpactiveuserarr = explode("[",$this->settings['cnpaccount_id']);
		 $xmlcnpfuser = trim($xmlcnpactiveuserarr[0]);
		 $accounttype=$dom->createElement('AccountGuid',$this->getshpCnPAccountGUID($xmlcnpfuser) ); 
         $accounttype=$authentication->appendChild($accounttype);
    
         $accountid=$dom->createElement('AccountID',$xmlcnpfuser);
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
	
		 $ConnectCampaignAlias=$dom->createElement('ConnectCampaignAlias',$this->settings['ConnectCampaignAlias'] );
    	$ConnectCampaignAlias=$order->appendChild($ConnectCampaignAlias);		
	
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
		if($newval['payment_type'] == 'Credit Card') {
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
		$cnumber = ($Order->Billing->card) ? $Order->Billing->card : $cardnumber['card'];
		$credit_number=$dom->createElement('CardNumber',$cnumber);
		$credit_number=$creditcard->appendChild($credit_number);
		
		$credit_cvv=$dom->createElement('Cvv2',$Order->Billing->cvv);
		$credit_cvv=$creditcard->appendChild($credit_cvv);
		 
		$card_exp_month = date("m/y",$Order->Billing->cardexpires);

		$credit_expdate=$dom->createElement('ExpirationDate',$card_exp_month);
		$credit_expdate=$creditcard->appendChild($credit_expdate);
		}
	elseif($newval['payment_type'] == 'eCheck') {
		$payment_type=$dom->createElement('PaymentType','Check');
			$payment_type=$paymentmethod->appendChild($payment_type);
			
			$echeck=$dom->createElement('Check','');
			$echeck=$paymentmethod->appendChild($echeck);
		
			if(!empty($newval['clickandpledge_echeck_AccountNumber'])) {
			$ecAccount=$dom->createElement('AccountNumber',$this->safeString( $newval['clickandpledge_echeck_AccountNumber'], 17));
			$ecAccount=$echeck->appendChild($ecAccount);
			}
			if(!empty($newval['clickandpledge_echeck_AccountType'])) {
			$ecAccount_type=$dom->createElement('AccountType',$newval['clickandpledge_echeck_AccountType']);
			$ecAccount_type=$echeck->appendChild($ecAccount_type);
			}
			if(!empty($newval['clickandpledge_echeck_RoutingNumber'])) {
			$ecRouting=$dom->createElement('RoutingNumber',$this->safeString( $newval['clickandpledge_echeck_RoutingNumber'], 9));
			$ecRouting=$echeck->appendChild($ecRouting);
			}
			if(!empty($newval['clickandpledge_echeck_CheckNumber'])) {
			$ecCheck=$dom->createElement('CheckNumber',$this->safeString( $newval['clickandpledge_echeck_CheckNumber'], 10));
			$ecCheck=$echeck->appendChild($ecCheck);
			}
			if(!empty($newval['clickandpledge_echeck_CheckType'])) {
			$ecChecktype=$dom->createElement('CheckType',$newval['clickandpledge_echeck_CheckType']);
			$ecChecktype=$echeck->appendChild($ecChecktype);
			}
			if(!empty($newval['clickandpledge_echeck_NameOnAccount'])) {
			$ecName=$dom->createElement('NameOnAccount',$this->safeString( $newval['clickandpledge_echeck_NameOnAccount'], 100));
			$ecName=$echeck->appendChild($ecName);
			}
			if(!empty($newval['clickandpledge_echeck_IdType'])) {
			$ecIdtype=$dom->createElement('IdType',$newval['clickandpledge_echeck_IdType']);
			$ecIdtype=$echeck->appendChild($ecIdtype);
			}			
			if(!empty($newval['clickandpledge_echeck_IdNumber'])) {
			$IdNumber=$dom->createElement('IdNumber',$this->safeString( $newval['clickandpledge_echeck_IdNumber'], 30));
			$IdNumber=$creditcard->appendChild($IdNumber);
			}
			if(!empty($newval['clickandpledge_echeck_IdStateCode'])) {
			$IdStateCode=$dom->createElement('IdStateCode', $newval['clickandpledge_echeck_IdStateCode']);
			$IdStateCode=$creditcard->appendChild($IdStateCode);
			}
	}
	else if($newval['payment_type'] !="") {			
			$payment_type=$dom->createElement('PaymentType','CustomPaymentType');
			$payment_type=$paymentmethod->appendChild($payment_type);			
			$CustomPayment=$dom->createElement('CustomPaymentType','');
			$CustomPayment=$paymentmethod->appendChild($CustomPayment);
			$CustomPaymentName=$dom->createElement('CustomPaymentName',$this->safeString($newval['payment_type'],50));
			$CustomPaymentName=$CustomPayment->appendChild($CustomPaymentName);
			if( isset($newval['PaymentNumber']) &&  $newval['PaymentNumber'] != '' ) {
			$CustomPaymentNum=$dom->createElement('CustomPaymentNumber',$this->safeString($newval['PaymentNumber'],50));
			$CustomPaymentNum=$CustomPayment->appendChild($CustomPaymentNum);
			}
		}
		$orderitemlist=$dom->createElement('OrderItemList','');
        $orderitemlist=$order->appendChild($orderitemlist);		
		
		$Items = shopp_cart_items();
		$sku_id="";
		$items = 0;
		$is_recurring = false;
		$recurring_method = $Periodicity = '';$trialint = false;$trialintprice = 0;
		$cycles = $calctax = $TotalTax = $TotalDiscount = $interval = $UnitDiscount = $ShippingTaxCalculate = 0;$UnitPriceCalculate=0;$p = 101500;
		foreach($Items as $i => $Item) {
				//echo '<pre>';
				//print_r($Item);
				//die();
		
			if($case == "trail"){
			if($Item->type == 'Subscription' && $Item->option->recurring['trial']=='on' )
				{
				 $trialint = true;
				 $trialintprice = $Item->option->recurring['trialprice'];
					if( isset( $Item->option->recurring ) )
					{
						switch( $Item->option->recurring['trialperiod'] )
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
					$interval = $Item->option->recurring['trialint'];
					
				//	$Periodicity = ( $interval > 1 ) ? $interval . ' ' . $period . 's' : $period;
				//	$Periodicity = ( $Periodicity == '3 Months' ) ? 'Quarter' : $Periodicity;
				    $Periodicity = $period;
					//$Periodicity = $this->fetchsuscriptionperiodicity($period, $interval);
					
					if( !in_array( $Periodicity, $this->allowed_Periodicity ) )
					{
						new ShoppError(__("Selected Periodicity <b>".$Periodicity."</b> is not valid for Click & Pledge. We are allowing 'week', '2 Weeks', 'Month', '2 Months', 'Quarter', '6 Months', 'Year' only. Please contact administrator.",'Shopp'),'c&p_express_transacton_error',SHOPP_TRXN_ERR);
						
						Shopp::redirect(Shopp::url(false,'checkout'));
					}
					
					if( $Item->option->recurring['trialint'] > 999 )
					{
						new ShoppError(__("Billing cycles should be between 2 and 999. Please contact administrator.",'Shopp'),'c&p_express_transacton_error',SHOPP_TRXN_ERR);
						Shopp::redirect(Shopp::url(false,'checkout'));
					}
					if($Item->option->recurring['trialint'] >1){
						$is_recurring = true;
					}
				else
				{
					$is_recurring = false;
				}//$is_recurring = true;
					$recurring_method = $Item->type;
					$cycles = $Item->option->recurring['trialint'];
				}	
			}
			elseif($case == "Subscription"){
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
					 $Periodicity = $this->fetchsuscriptionperiodicity($period, $interval);
					
					if( !in_array( $Periodicity, $this->allowed_Periodicity ) )
					{
						new ShoppError(__("Selected Periodicity <b>".$Periodicity."</b> is not valid for Click & Pledge. We are allowing 'week', '2 Weeks', 'Month', '2 Months', 'Quarter', '6 Months', 'Year' only. Please contact administrator.",'Shopp'),'c&p_express_transacton_error',SHOPP_TRXN_ERR);
						
						Shopp::redirect(Shopp::url(false,'checkout'));
					}
					
					if( $Item->option->recurring['cycles'] > 999 )
					{
						new ShoppError(__("Billing cycles should be between 2 and 999. Please contact administrator.",'Shopp'),'c&p_express_transacton_error',SHOPP_TRXN_ERR);
						Shopp::redirect(Shopp::url(false,'checkout'));
					}
					$is_recurring = true;
					$recurring_method = $Item->type;
					$cycles = $Item->option->recurring['cycles'];
				}
			}
				$orderitem=$dom->createElement('OrderItem','');
				$orderitem=$orderitemlist->appendChild($orderitem);
			
				if($case == "trail"){ $cnpitemprice = $trialintprice;}
			    elseif($case == "Subscription"){$cnpitemprice = $Item->subprice;}
			    else{$cnpitemprice = $Item->unitprice;}
		
				$f_unit_price =  number_format($cnpitemprice,2,'.','');
				$f_unit_tax = $f_unit_price*$Item->taxrate;				
				
				$itemid=$dom->createElement('ItemID',++$p);
				$itemid=$orderitem->appendChild($itemid);
				
				$item_name = $Item->name;
				if(isset($Item->option->label))
				$item_name .= ' ('.$Item->option->label.')';
				$itemid=$dom->createElement('ItemName',$this->safeString($item_name, 50));
				$itemid=$orderitem->appendChild($itemid);
				
				$quntity=$dom->createElement('Quantity',$Item->quantity);
				$quntity=$orderitem->appendChild($quntity);
			
				/*$unitprice=$dom->createElement('UnitPrice',number_format($cnpitemprice,2,'.','')*100);
				$unitprice=$orderitem->appendChild($unitprice);	
				$UnitPriceCalculate += ($cnpitemprice*$Item->quantity);
			    print_r($Item);print_r($_POST);*/
			
				if( isset($newval['payment_options_recurringtype']) &&  $newval['payment_options_recurringtype'] == 'recurring' ) { 
				if($newval['RecurringType'] == 'Installment') {
					$UnitPrice = ($this->shpnumber_formatprc(($cnpitemprice/$newval['installments_no']),2,'.','')*100);
					$UnitPriceCalculate += $this->shpnumber_formatprc($cnpitemprice/$newval['installments_no'],2,'.','')*$Item->quantity;
					$unitprice=$dom->createElement('UnitPrice',$UnitPrice);
					$unitprice=$orderitem->appendChild($unitprice);
				} else {	 			
				$unitprice=$dom->createElement('UnitPrice',number_format($cnpitemprice,2,'.','')*100);
				$unitprice=$orderitem->appendChild($unitprice);
				$UnitPriceCalculate += ($cnpitemprice*$Item->quantity);
				}
			} else {	
			
			$unitprice = $dom->createElement('UnitPrice',number_format($cnpitemprice,2,'.','')*100);
			$unitprice = $orderitem->appendChild($unitprice);
			$UnitPriceCalculate += ($cnpitemprice*$Item->quantity);
			}
				
				if( isset( $Item->unittax ) && $Item->unittax != 0 && $this->shoppmeta['tax_inclusive'] == 'off' )
				{
					if( isset($newval['payment_options_recurringtype']) &&          $newval['payment_options_recurringtype'] == 'recurring' ) {
				
					if($newval['RecurringType'] == 'Installment') {
					
							$UnitTax = $this->shpnumber_formatprc(($Item->unittax/$newval['installments_no']),2,'.','')*100;
							$unit_tax=$dom->createElement('UnitTax', $UnitTax);
							$unit_tax=$orderitem->appendChild($unit_tax);	
							$UnitTaxCalculate += $this->shpnumber_formatprc(($Item->unittax/$newval['installments_no']),2,'.','')*$Item->quantity;
					
						
					} else {
					
						$unit_tax=$dom->createElement('UnitTax',number_format($Item->unittax,2,'.','')*100);
						$unit_tax=$orderitem->appendChild($unit_tax);
						$UnitTaxCalculate += (number_format($Item->unittax,2,'.','')*$Item->quantity);
					
				  }
				}
				else {
				
					$unit_tax=$dom->createElement('UnitTax',number_format($Item->unittax,2,'.','')*100);
					$unit_tax=$orderitem->appendChild($unit_tax);
					$UnitTaxCalculate += (number_format($Item->unittax,2,'.','')*$Item->quantity);

					}
				}
				echo $UnitTaxCalculate;
				if( isset( $Item->discount ) && $Item->discount != 0 )
				{
					/*$UnitDiscount = $UnitDiscount + number_format(($Item->discount * $Item->quantity),2,'.','');
					$unit_disc=$dom->createElement('UnitDiscount',number_format($Item->discount,2,'.','')*100);
					$unit_disc=$orderitem->appendChild($unit_disc);*/
					
					
					if( isset($newval['payment_options_recurringtype']) &&  $newval['payment_options_recurringtype'] == 'recurring' ) {
				
					if($newval['RecurringType'] == 'Installment') {
					
							$UnitDiscount = $this->shpnumber_formatprc(($Item->discount/$newval['installments_no']),2,'.','')*100;
							$unit_disc=$dom->createElement('UnitDiscount', $UnitDiscount);
							$unit_disc=$orderitem->appendChild($unit_disc);							
							$UnitdiscCalculate += ($this->shpnumber_formatprc(($Item->discount/$newval['installments_no']),2,'.','')*$Item->quantity);
					
						
					} else {
					    $UnitDiscount = number_format(($Item->discount),2,'.','')*100;
						$unit_disc=$dom->createElement('UnitDiscount',number_format($Item->discount,2,'.','')*100);
						$unit_disc=$orderitem->appendChild($unit_disc);
						$UnitdiscCalculate += (number_format($Item->discount,2,'.','')*$Item->quantity);
					
				  }
				}
				else {
					  $UnitDiscount = number_format(($Item->discount),2,'.','')*100;
							$unit_disc=$dom->createElement('UnitDiscount',number_format($Item->discount,2,'.','')*100);
				      $unit_disc=$orderitem->appendChild($unit_disc);
				      $UnitdiscCalculate += (number_format($Item->discount,2,'.','')*$Item->quantity);
				  }
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
				
				/*$shipping_value = $dom->createElement('ShippingValue',number_format(shopp('cart.get-shipping','number=on'), 2, '.', '')*100);
				$shipping_value=$shipping->appendChild($shipping_value);*/
			
							
			if( isset($newval['payment_options_recurringtype']) &&  $newval['payment_options_recurringtype'] == 'recurring' ) {
			  if($newval['RecurringType'] == 'Installment') {
				
			 $ShippingValue = $this->shpnumber_formatprc((shopp('cart.get-shipping','number=on')/$newval['installments_no']), 2, '.', '')*100;
				$shipping_value = $dom->createElement('ShippingValue', $ShippingValue);
				$shipping_value=$shipping->appendChild($shipping_value);
				$ShippingValueCalculate += $this->shpnumber_formatprc((shopp('cart.get-shipping','number=on')/$newval['installments_no']), 2, '.', '');
				} else {
				$shipping_value = $dom->createElement('ShippingValue',number_format(shopp('cart.get-shipping','number=on'), 2, '.', '')*100);
				$shipping_value=$shipping->appendChild($shipping_value);
				$ShippingValueCalculate += number_format(shopp('cart.get-shipping','number=on'), 2, '.', '');
				}
			} else {
			$shipping_value = $dom->createElement('ShippingValue',number_format(shopp('cart.get-shipping','number=on'), 2, '.', '')*100);
			$shipping_value=$shipping->appendChild($shipping_value);
			$ShippingValueCalculate += number_format(shopp('cart.get-shipping','number=on'), 2, '.', '');
			}			
			$cnptotaltax   =  shopp('cart.get-tax', 'number=on');
			$cnpshippingtax= ($cnptotaltax- $UnitTaxCalculate);
				
			if($cnpshippingtax > 0)
			{
				 $order_shipping_tax = $cnpshippingtax;
				if( isset($newval['payment_options_recurringtype']) &&  $newval['payment_options_recurringtype'] == 'recurring' ) {
			    if($newval['RecurringType'] == 'Installment') {
					
				 	$cnpinstotaltax =shopp('cart.get-tax', 'number=on');
					 $UnitTaxCalculate= $UnitTaxCalculate * $newval['installments_no'];
			        $order_shipping_tax = ($cnpinstotaltax- $UnitTaxCalculate);
					
					$ShippingTax = $this->shpnumber_formatprc( (($order_shipping_tax/$newval['installments_no'])), 2, '.', '' )*100;	
					$shipping_tax=$dom->createElement('ShippingTax',$ShippingTax);
					$shipping_tax=$shipping->appendChild($shipping_tax);
					$ShippingTaxCalculate += $this->shpnumber_formatprc( (($order_shipping_tax/$newval['installments_no'])), 2, '.', '' );
					} else {
					$ShippingTax = number_format( ($order_shipping_tax), 2, '.', '' )*100;	
					$shipping_tax=$dom->createElement('ShippingTax',$ShippingTax);
					$shipping_tax=$shipping->appendChild($shipping_tax);
					$ShippingTaxCalculate += number_format( ($order_shipping_tax), 2, '.', '' );
					}
				} else {
				$ShippingTax = number_format( $order_shipping_tax, 2, '.', '' )*100;	
				$shipping_tax=$dom->createElement('ShippingTax',$ShippingTax);
				$shipping_tax=$shipping->appendChild($shipping_tax);
				$ShippingTaxCalculate += number_format( $order_shipping_tax, 2, '.', '' );
				}
			}	
		}
	 }		
}
		
		$receipt=$dom->createElement('Receipt','');
		$receipt=$order->appendChild($receipt);
		
	if($this->settings['NotificationEmail'] == 'on') 
		{
			$email_sendreceipt =$dom->createElement('SendReceipt',"true");
			$email_sendreceipt=$receipt->appendChild($email_sendreceipt);
		}
		else
		{
			$email_sendreceipt=$dom->createElement('SendReceipt',"false");
			$email_sendreceipt=$receipt->appendChild($email_sendreceipt);		
		}

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
		if($Item->type == 'Subscription'){
		if($is_recurring)
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
		}
	
	if( isset($newval['payment_options_recurringtype']) &&  $newval['payment_options_recurringtype'] == 'recurring' )
		{
			$trans_recurr=$dom->createElement('Recurring','');
			$trans_recurr=$transation->appendChild($trans_recurr);
		
				if($newval['installments_no'] != '') {
					$total_installment=$dom->createElement('Installment',$newval['installments_no']);
					$total_installment=$trans_recurr->appendChild($total_installment);
				} else {
					$total_installment=$dom->createElement('Installment',1);
					$total_installment=$trans_recurr->appendChild($total_installment);
				}
						
			$total_periodicity=$dom->createElement('Periodicity',$newval['RecurringPeriodicity']);
			$total_periodicity=$trans_recurr->appendChild($total_periodicity);
			
			if( isset($newval['RecurringType']) ) {
				$RecurringMethod=$dom->createElement('RecurringMethod',$newval['RecurringType']);
				$RecurringMethod=$trans_recurr->appendChild($RecurringMethod);
			} else {
				$RecurringMethod=$dom->createElement('RecurringMethod','Subscription');
				$RecurringMethod=$trans_recurr->appendChild($RecurringMethod);
			}	
		}	
		$trans_totals=$dom->createElement('CurrentTotals','');
		$trans_totals=$transation->appendChild($trans_totals);
		
		if( shopp('cart.get-discount','number=on') )
		{
			$TotalDiscount = shopp('cart.get-discount','number=on');
			
			/*$total_discount=$dom->createElement('TotalDiscount',number_format(shopp('cart.get-discount','number=on'), 2, '.', '')*100);
			$total_discount=$trans_totals->appendChild($total_discount);*/
			
			if( isset($newval['payment_options_recurringtype']) &&  $newval['payment_options_recurringtype'] == 'recurring' ) {
				if($newval['RecurringType'] == 'Installment') {
					$TotalDiscount = (shopp('cart.get-discount','number=on') )/$newval['installments_no'];
					$TotalDiscountt = $this->shpnumber_formatprc($TotalDiscount, 2, '.', '')*100;
				} else {
					$TotalDiscount = shopp('cart.get-discount','number=on');
					$TotalDiscountt = number_format(shopp('cart.get-discount','number=on')  , 2, '.', '')*100;		
				}
			} else {
				$TotalDiscount = shopp('cart.get-discount','number=on');
			$TotalDiscountt = number_format(shopp('cart.get-discount','number=on')  , 2, '.', '')*100;
			}
		
			if($TotalDiscount) {		
			$total_discount=$dom->createElement('TotalDiscount', $TotalDiscountt);
			$total_discount=$trans_totals->appendChild($total_discount);
			$TotalDiscountCalculate = $TotalDiscountt;
			}
			
        }	
		
		if( shopp('cart.get-tax', 'number=on') && $this->shoppmeta['tax_inclusive'] == 'off' )
		{
			/*$TotalTax = shopp('cart.get-tax', 'number=on');
			$total_tax=$dom->createElement('TotalTax',number_format($TotalTax, 2, '.', '')*100);
			$total_tax=$trans_totals->appendChild($total_tax);
			*/
			
			if( isset($newval['payment_options_recurringtype']) &&  $newval['payment_options_recurringtype'] == 'recurring' ) {
				if($newval['RecurringType'] == 'Installment') {
					$TotalTax = (shopp('cart.get-tax','number=on') )/$newval['installments_no'];
					$total_tax = $this->shpnumber_formatprc($TotalTax, 2, '.', '')*100;
				} else {
					$TotalTax = shopp('cart.get-tax', 'number=on');
					$total_tax = number_format(shopp('cart.get-tax','number=on')  , 2, '.', '')*100;		
				}
			} else {
				$TotalTax = shopp('cart.get-tax', 'number=on');
			$total_tax = number_format(shopp('cart.get-tax','number=on')  , 2, '.', '')*100;
			}
		
			if($total_tax) {		
			$total_taxn=$dom->createElement('TotalTax', $total_tax);
			$total_taxn=$trans_totals->appendChild($total_taxn);
			//$TotalDiscountCalculate = $TotalDiscount;
			}
		}
	
		if( shopp('cart','has-ship-costs') && shopp('cart.get-shipping','number=on') )
		{
			
			if( isset($newval['payment_options_recurringtype']) &&  $newval['payment_options_recurringtype'] == 'recurring' ) {
				if($newval['RecurringType'] == 'Installment') {
					$TotalShipping = $this->shpnumber_formatprc(shopp('cart.get-shipping','number=on') /$newval['installments_no'], 2, '.', '');
					$total_ship = $this->shpnumber_formatprc($TotalShipping, 2, '.', '')*100;
				} else {
					$TotalShipping = shopp('cart.get-shipping','number=on');
					$total_ship = number_format(shopp('cart.get-shipping','number=on')  , 2, '.', '')*100;		
				}
			} else {
				$TotalShipping = shopp('cart.get-shipping','number=on');
			$total_ship = number_format(shopp('cart.get-shipping','number=on')  , 2, '.', '')*100;
			}
		
			if($total_ship) {		
			$total_ship=$dom->createElement('TotalShipping', $total_ship);
			$total_ship=$trans_totals->appendChild($total_ship);
			//$TotalDiscountCalculate = $TotalDiscount;
			}
		}
		
		//$Total = shopp('cart.get-total', 'number=on');
		//$Total = ($UnitPriceCalculate+$TotalTax + total_ship)-$TotalDiscount;
	/*echo "<br>****".(number_format($UnitPriceCalculate , 2, '.', '')*100)."***".($total_tax)."***".(number_format($TotalShipping , 2, '.', '')*100)."***".(number_format($TotalDiscount , 2, '.', '')*100);
	echo "<br>****";
	echo  */ 
	
	$Total = (number_format($UnitPriceCalculate , 2, '.', '')*100 + ($total_tax) + number_format($TotalShipping , 2, '.', '')*100)- number_format($TotalDiscount , 2, '.', '')*100;
		$total_amount=$dom->createElement('Total',$Total);
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
		
		if(  $Item->discount == 0 )
		{
		if( shopp('cart.get-discount','number=on') )
		{
			 $TransactionDiscount = shopp('cart.get-discount','number=on') ;
					
			
				if( isset($_POST['payment_options_recurringtype']) &&  $_POST['payment_options_recurringtype'] == 'recurring' ) {
				if($_POST['RecurringType'] == 'Installment') {
				$trans_coupon_discount=$dom->createElement('TransactionDiscount', $this->shpnumber_formatprc($TransactionDiscount/$_POST['installments_no'], 2, '.', '')*100);
				$trans_coupon_discount=$transation->appendChild($trans_coupon_discount);
				} else {
				$trans_coupon_discount=$dom->createElement('TransactionDiscount',number_format($TransactionDiscount, 2, '.', '')*100);
				$trans_coupon_discount=$transation->appendChild($trans_coupon_discount);
				}
			} else {
			$trans_coupon_discount=$dom->createElement('TransactionDiscount',number_format($TransactionDiscount, 2, '.', '')*100);
			$trans_coupon_discount=$transation->appendChild($trans_coupon_discount);
			}
		 }
		}
		
		/*if( shopp('cart.get-tax', 'number=on') && $this->shoppmeta['tax_inclusive'] == 'off' )
		{
			$transaction_tax = shopp('cart.get-tax', 'number=on') - $calctax;
			
				
				
				if( isset($_POST['payment_options_recurringtype']) &&  $_POST['payment_options_recurringtype'] == 'recurring' ) {
				if($_POST['RecurringType'] == 'Installment') {
					
				$trans_tax=$dom->createElement('TransactionTax', number_format($transaction_tax, 2, '.', '')*100);
				$trans_tax=$transation->appendChild($trans_tax);
				} else {
				$trans_tax=$dom->createElement('TransactionTax',number_format($transaction_tax, 2, '.', '')*100);
				$trans_tax=$transation->appendChild($trans_tax);
				}
			} else {
			$trans_tax=$dom->createElement('TransactionTax',number_format($transaction_tax, 2, '.', '')*100);
			$trans_tax=$transation->appendChild($trans_tax);
			}
						
		}*/
		if($case =='Subscription'){
				$chargeDate=$dom->createElement('ChargeDate',date('y/m/d', strtotime($Order->ProfileStartDate)));
				$chargeDate=$transation->appendChild($chargeDate);

			}
        $strParam = $dom->saveXML();//print_r($strParam);die();
	return $strParam;
	
		
	
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
		if($this->settings['cnpaccount_id'] == '' )
		{
			new ShoppError(__("Invalid settings for Click & Pledge Payment. Please contact administrator",'Shopp'),'c&p_express_transacton_error',SHOPP_TRXN_ERR);
			Shopp::redirect(Shopp::url(false,'checkout'));
		}
	
		
		if (!in_array($this->baseop['currency']['code'], array('USD', 'EUR', 'CAD', 'GBP'))) 
		{
			new ShoppError(__("Click & Pledge do no allow <b>".$this->baseop['currency']['code']."</b>. We are allowing USD, EUR, CAD, GBP",'Shopp'),'c&p_express_transacton_error',SHOPP_TRXN_ERR);
			Shopp::redirect(Shopp::url(false,'checkout'));
		}
	
		$Items = shopp_cart_items();
		$sku_id="";
		$items = 0;
		$is_recurring = false;
		$recurring_method = $Periodicity = '';
		
		foreach($Items as $i => $Item) {
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
					if( $Item->option->recurring['trial'] == 'on' )
					 {	
					  if($Item->option->recurring['trialperiod'] == "d"){$cnptrailperiod="Day";}
					  if($Item->option->recurring['trialperiod'] == "w"){$cnptrailperiod="Week";}
					  if($Item->option->recurring['trialperiod'] == "m"){$cnptrailperiod="Month";}
					  if($Item->option->recurring['trialperiod'] == "y"){$cnptrailperiod="Year";}
					  $trilprd = $Item->option->recurring['trialint']-1;
					
					$this->Order->ProfileendDate   = date("Y-m-d", strtotime("+ " . $trilprd. " " . $cnptrailperiod));
					$this->Order->ProfileStartDate = date("Y-m-d", strtotime("+ " . $Item->option->recurring['interval'] . " " . $period, strtotime($this->Order->ProfileendDate))). "T0:0:0";
	
	 
					$xml = $this->getPaymentXML($this->Order, "trail");	
					$response = array();
		$connect = array('soap_version' => SOAP_1_1, 'trace' => 1, 'exceptions' => 0);
		$client  = new SoapClient('https://paas.cloud.clickandpledge.com/paymentservice.svc?wsdl', $connect);
		$params   = array('instruction'=>$xml);
		$response = $client->Operation($params); 
		
		if($response->faultstring == 'Could not connect to host')
		{
			new ShoppError($response->faultstring.'. Please try after some time','c&p_express_transacton_error',SHOPP_TRXN_ERR,array());
			Shopp::redirect(Shopp::url(false,'checkout'));
		}
		$authorizedcode    = $response->OperationResult->AuthorizationCode;
		$response_value    = $response->OperationResult->ResultData;
		$VaultGUID         = $response->OperationResult->VaultGUID;	
		$response_code     = $response->OperationResult->ResultCode;
		$transation_number = $response->OperationResult->TransactionNumber;
		$xml_error         = explode(":",$response->OperationResult->AdditionalInfo);		
		
		if(isset($xml_error['2']))
		{
		   $payment_error = $xml_error['2'];
			
		}else{
			
		   $payment_error = "";
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
			
			Shopp::redirect(Shopp::url(false,'checkout'));
		}

		/*$capture = ( count( $Order->Cart->shipped ) > 0 ) ? true : false;		
		$Billing = $this->Order->Billing;		
		shopp_add_order_event($Event->order,$type,array(
				'txnid' => $transation_number,
				'txnorigin' => $Event->txnid,
				'fees' => 0,
				'paymethod' => $this->module,
				'payid' => $Billing->card,
				'paytype' => $Billing->cardtype,
				'amount' => $Event->amount,
				'gateway' => $this->module,
				'capture' => $capture,										// Capture flag
			));*/
				     }
					if( $Item->option->recurring['interval'] >= 1 )
					 {	//echo "in";
						$xml = $this->getPaymentXML($this->Order, "Subscription");
					//  print_r($xml);exit;
						$response=array();
		$connect = array('soap_version' => SOAP_1_1, 'trace' => 1, 'exceptions' => 0);
		$client = new SoapClient('https://paas.cloud.clickandpledge.com/paymentservice.svc?wsdl', $connect);
		$params = array('instruction'=>$xml);
		$response = $client->Operation($params); 
		
		if($response->faultstring == 'Could not connect to host')
		{
			new ShoppError($response->faultstring.'. Please try after some time','c&p_express_transacton_error',SHOPP_TRXN_ERR,array());
			Shopp::redirect(Shopp::url(false,'checkout'));
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
			Shopp::redirect(Shopp::url(false,'checkout'));
		}

		$capture = ( count( $Order->Cart->shipped ) > 0 ) ? true : false;		
		$Billing = $this->Order->Billing;		
		shopp_add_order_event($Event->order,$type,array(
				'txnid' => $transation_number,
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
				}
			else{
				
		$xml = $this->getPaymentXML($this->Order, "");	
		$response=array();
		$connect = array('soap_version' => SOAP_1_1, 'trace' => 1, 'exceptions' => 0);
		$client = new SoapClient('https://paas.cloud.clickandpledge.com/paymentservice.svc?wsdl', $connect);
		$params = array('instruction'=>$xml);
		$response = $client->Operation($params); 
		
		if($response->faultstring == 'Could not connect to host')
		{
			new ShoppError($response->faultstring.'. Please try after some time','c&p_express_transacton_error',SHOPP_TRXN_ERR,array());
			Shopp::redirect(Shopp::url(false,'checkout'));
		}
		$authorizedcode = $response->OperationResult->AuthorizationCode;
		$response_value = $response->OperationResult->ResultData;
		$VaultGUID      = $response->OperationResult->VaultGUID;	
		$response_code  = $response->OperationResult->ResultCode;
		$transation_number = $response->OperationResult->TransactionNumber;
		$xml_error = explode(":",$response->OperationResult->AdditionalInfo);		
		
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
			Shopp::redirect(Shopp::url(false,'checkout'));
		}

		$capture = ( count( $Order->Cart->shipped ) > 0 ) ? true : false;		
		$Billing = $this->Order->Billing;		
		shopp_add_order_event($Event->order,$type,array(
				'txnid' => $transation_number,
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
		}
	
	/*	if(!in_array($Order->Billing->cardtype, $this->creditcard_names))
		{
			new ShoppError(__("We are not accepting <b>".$Order->Billing->cardtype."</b> type cards",'Shopp'),'c&p_express_transacton_error',SHOPP_TRXN_ERR);
			shopp_redirect(shoppurl(false,'checkout'));
		}
		
		$cardnumber = $_POST['billing'];		
		if( preg_match( '/^(X)/', $cardnumber['card'] ))
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
		}*/
	
		
		
		
	}
	public function get_CnPshpaccountslist()
	{
			global $wpdb;
			$data['cnpaccounts'] = array();
			$query = "SELECT * FROM " . $wpdb->prefix . "cnp_wp_shpcnpaccountsinfo";
			$results = $wpdb->get_results($query, ARRAY_A);
			$count = sizeof($results);
			for($i=0; $i<$count; $i++){
				$data['cnpaccounts'][] = array(
				'AccountId'      => $results[$i]['cnpaccountsinfo_orgid'],
				'GUID'           => $results[$i]['cnpaccountsinfo_accountguid'],
				'Organization'   => $results[$i]['cnpaccountsinfo_orgname']    
			);

			}
		
		return $data['cnpaccounts'];
	}
	public function getshpCnPAccountGUID($accid)
		{
			global $wpdb;
			$cnpAccountGUId ="";
			$query = "SELECT * FROM " . $wpdb->prefix . "cnp_wp_shpcnpaccountsinfo where cnpaccountsinfo_orgid ='".$accid."'";
		    $result = $wpdb->get_results($query, ARRAY_A);
			$count = sizeof($result);
				for($i=0; $i<$count; $i++){
				$cnpAccountGUId      = $result[$i]['cnpaccountsinfo_accountguid'];
				}
			 
			return $cnpAccountGUId;
		
		}
	public function getshpCnPConnectCampaigns($cnpaccid)
	{

		$cnpacountid = $cnpaccid;
	    $cnpaccountGUID = $this->getshpCnPAccountGUID($cnpacountid);
		$cnpUID = "14059359-D8E8-41C3-B628-E7E030537905";
		$cnpKey = "5DC1B75A-7EFA-4C01-BDCD-E02C536313A3";
		$connect  = array('soap_version' => SOAP_1_1, 'trace' => 1, 'exceptions' => 0);
	    $client   = new SoapClient('https://resources.connect.clickandpledge.com/wordpress/Auth2.wsdl', $connect);
		$responsearr =array();
		if( isset($cnpacountid) && $cnpacountid !="" && isset($cnpaccountGUID) &&  $cnpaccountGUID !="")
		{ 
			$xmlr  = new SimpleXMLElement("<GetActiveCampaignList2></GetActiveCampaignList2>");
			$cnpsel ="";
			$xmlr->addChild('accountId', $cnpacountid);
			$xmlr->addChild('AccountGUID', $cnpaccountGUID);
			$xmlr->addChild('username', $cnpUID);
			$xmlr->addChild('password', $cnpKey);
			$response = $client->GetActiveCampaignList2($xmlr); 
			$responsearr =  $response->GetActiveCampaignList2Result->connectCampaign;
		}
		
		return $responsearr;
			
		}
	 public static function getshoppCnPrefreshtoken() {
		 
		global $wpdb;
		
        $table_name =  self::get_cnp_wpshptokeninfo();
		$settingtable_name = self::get_cnp_wpshpsettingsinfo();
        $sql = "SELECT cnptokeninfo_refreshtoken  FROM ". $table_name;
        $cnprefreshtkn = $wpdb->get_var( $sql );
		
		$cnpsettingsquery = "SELECT *  FROM ".$settingtable_name;
		$results = $wpdb->get_results($cnpsettingsquery, ARRAY_A);

        $count = sizeof($results);
        for($i=0; $i<$count; $i++){
			 $password="password";
			 $cnpsecret = openssl_decrypt($results[$i]['cnpsettingsinfo_clentsecret'],"AES-128-ECB",$password);
			
			 $rtncnpdata = "client_id=".$results[$i]['cnpsettingsinfo_clientid']."&client_secret=". $cnpsecret."&grant_type=refresh_token&scope=".$results[$i]['cnpsettingsinfo_scope']."&refresh_token=".$cnprefreshtkn;
        }
		
			return $rtncnpdata;
			exit;
		
	 }
	function cnp_getshoppCnPAccountList()
	{
		
		$rtnrefreshtokencnpdata = self::getshoppCnPrefreshtoken();
		$rcnpshoppaccountid = $_REQUEST['rcnpshoppaccountid'];
		$response = wp_remote_post( "https://aaas.cloud.clickandpledge.com/IdServer/connect/token", array('headers' => array('content-type' => 'application/x-www-form-urlencoded', 'email' => $cnpemailaddress),'body' =>$rtnrefreshtokencnpdata) );
		try {
 
        $cnptokendata = json_decode( wp_remote_retrieve_body($response));
		 $cnptoken = $cnptokendata->access_token;
			 $cnprtokentyp = $cnptokendata->token_type;
			if($cnptoken != "")
			{
			 
			$response1 =	wp_remote_get('https://api.cloud.clickandpledge.com/users/accountlist', array('headers' => array('accept' => 'application/json','content-type' => 'application/json', 'authorization' => $cnprtokentyp." ".$cnptoken)) );
	  
		 try {
			
				$cnpAccountsdata = json_decode( wp_remote_retrieve_body($response1));
				$camrtrnval = "";
					$rtncnpdata = self::delete_wpshpcnpaccountslist();
					$confaccno 	 =  $rcnpshoppaccountid;	
				
					foreach($cnpAccountsdata as $cnpkey =>$cnpvalue)
					{
					 $selectacnt ="";
					 $cnporgid = $cnpvalue->OrganizationId;
					 $cnporgname = addslashes($cnpvalue->OrganizationName);
					 $cnpaccountid = $cnpvalue->AccountGUID;
					 $cnpufname = addslashes($cnpvalue->UserFirstName);
					 $cnplname = addslashes($cnpvalue->UserLastName);
				     $cnpuid = $cnpvalue->UserId;
					 $rtncnpdata = self::insert_cnpwpshpaccountsinfo($cnporgid,$cnporgname,$cnpaccountid,$cnpufname,$cnplname,$cnpuid);
					 if($confaccno == $cnporgid){$selectacnt ="selected='selected'";}
						$newcnpacnt = $cnporgid." [".$cnpvalue->OrganizationName."]";
					 	 $camrtrnval .= "<option value='".$newcnpacnt."' ".$selectacnt.">".$cnporgid." [".$cnpvalue->OrganizationName."]</option>"; }
					echo $camrtrnval;
					wp_die();

			} catch ( Exception $ex ) {
				$json = null;
			}
			  
				}
 
		} catch ( Exception $ex ) {
			$json = null;
		} 
		
	}
		public  function getCnPSHPLoginUser() {
		$cnpPMPAccountId ="";
		global $wpdb;
		
        $table_name = self::get_cnp_wpshpaccountsinfo();
		$cnpPMPAccountId = $wpdb->get_var("SELECT cnpaccountsinfo_userid FROM " . $table_name." Limit 0,1"); 
	 	return $cnpPMPAccountId;
	}
	public function getShpCnPactivePaymentList($cnpaccid)
	{

		global $wpdb;
		$cmpacntacptdcards = "";
		$cnpacountid = $cnpaccid;
		 $cnpaccountGUID = $this->getshpCnPAccountGUID($cnpacountid);
		$cnpUID = "14059359-D8E8-41C3-B628-E7E030537905";
		$cnpKey = "5DC1B75A-7EFA-4C01-BDCD-E02C536313A3";
		$connect1  = array('soap_version' => SOAP_1_1, 'trace' => 1, 'exceptions' => 0);
	    $client1   = new SoapClient('https://resources.connect.clickandpledge.com/wordpress/Auth2.wsdl', $connect1);
		if( isset($cnpacountid) && $cnpacountid !="" && isset($cnpaccountGUID) &&  $cnpaccountGUID !="")
		{ 
			$xmlr1  = new SimpleXMLElement("<GetAccountDetail></GetAccountDetail>");
			$xmlr1->addChild('accountId',$cnpacountid);
			$xmlr1->addChild('accountGUID',$cnpaccountGUID);
			$xmlr1->addChild('username',$cnpUID);
			$xmlr1->addChild('password',$cnpKey);
			$response1 =  $client1->GetAccountDetail($xmlr1);
	        return $response1;	
		wp_exit();
	 }
	}
	function settings () {	
			 $defaultpaymentoptions = array("One Time Only"=>"One Time Only","Recurring"=>"Recurring");
			 $defaultrecurringoptions = array("Subscription"=>"Subscription","Installment"=>"Installment");
			 $noofpaymentsvalue = array("Indefinite Only"=>"Indefinite Only","Open Field Only"=>"Open Field Only","Indefinite + Open Field Option"=>"Indefinite + Open Field Option","Fixed Number - No Change Allowed"=>"Fixed Number - No Change Allowed");
		
			 global $wpdb;
			 $accountstable_name =$wpdb->prefix . 'cnp_wp_shpcnpaccountsinfo';
			 $cnpsqlst= "SELECT count(*) FROM ". $accountstable_name;
			 $rowcount = $wpdb->get_var( $cnpsqlst );
		
			 $cnpshptransactios=$this->get_CnPshpaccountslist();
			 $cnpactiveCampaigns = array();
	
		$cnpaccountslist = array();$cnpfuser = trim($cnpshptransactios[0]['AccountId']);
		foreach($cnpshptransactios as $cnpacnts){
	
			
			 $newcnpacc= $cnpacnts['AccountId']." [".stripslashes($cnpacnts['Organization'])."]";
			 $cnpactiveuserarr1 = explode("[",$newcnpacc);
			 $cnpfuser1 = trim($cnpactiveuserarr1[0]);
			 $cnpactiveuserarr2 = explode("[",$this->settings['cnpaccount_id']);
			 $cnpfuser2 = trim($cnpactiveuserarr2[0]);
			if(strcmp($cnpfuser1,$cnpfuser2)==0){
				 $found = true;
				 $cnpactiveuserarr = explode("[",$newcnpacc);
				 $cnpfuser = trim($cnpactiveuserarr[0]);
			
			}
		    $selval = $cnpacnts['AccountId']." [".stripslashes($cnpacnts['Organization'])."]";
			$selval = preg_replace('/\s+/', ' ', $selval);
			array_push($cnpaccountslist,$selval);
		
		
		} 
		//print_r($cnpaccountslist);
	
		$cnpconnectcampaign = $this->getshpCnPConnectCampaigns($cnpfuser);
		//print_r($cnpconnectcampaign);
		foreach($cnpconnectcampaign as $cnpcampngs){
			$cnpactiveCampaigns[$cnpcampngs->alias] = $cnpcampngs->name." (".$cnpcampngs->alias.")";
		}
		//print_r($cnpactiveCampaigns);
		$cnpactivepaymnts=$this->getShpCnPactivePaymentList($cnpfuser);

		    $responsearramex              =  $cnpactivepaymnts->GetAccountDetailResult->Amex;
			$responsearrJcb               =  $cnpactivepaymnts->GetAccountDetailResult->Jcb;
			$responsearrMaster            =  $cnpactivepaymnts->GetAccountDetailResult->Master;
			$responsearrVisa              =  $cnpactivepaymnts->GetAccountDetailResult->Visa;
			$responsearrDiscover          =  $cnpactivepaymnts->GetAccountDetailResult->Discover;
			$responsearrecheck            =  $cnpactivepaymnts->GetAccountDetailResult->Ach;
			$responsearrCPaymentType      =  $cnpactivepaymnts->GetAccountDetailResult->CustomPaymentType;
	
		$transactionMode = array("Test"=>"Test","Production"=>"Production");
	    $defaultpaymentmethod =array();
	
		$script = "var s='clickandpledge'; jQuery('#payment-option-menu').on('change', function() {
		if(jQuery('#payment-option-menu').val()=='ClickandPledge') {var url = window.location.href;    
		if (url.indexOf('?') > -1){url += '&id=ClickandPledge'}else{ url += '?id=ClickandPledge'}
		window.location.href = url;};});jQuery(document).bind(s+'Settings',function(){
			jQuery('#clickandpledge-cnpshp_code').hide();
			jQuery('label[for=clickandpledge-cnpshp_code]').css('visibility', 'hidden');
			;
	     });";
	
		$this->ui->behaviors($script);
		if($responsearrCPaymentType == false )
		{
			$script = "jQuery(document).ready(function($) { 
				
			   jQuery('#clickandpledge-custompayment').prop('checked', false);
			   jQuery('.CustomPaymentTitleclass').hide();
			   jQuery('#clickandpledge-custompayment').hide();
			   jQuery('label[for=clickandpledge-custompayment]').css('visibility', 'hidden');
			   jQuery('label[for=clickandpledge-custom_payment_title]').css('visibility', 'hidden');
						
					str = '<option value=CreditCard>Credit Card</option>';
					str += '<option value=eCheck>eCheck</option>';
						
					jQuery('#clickandpledge-defaultpaymentmethod').html(str);
					});";
		
	
		$this->ui->behaviors($script);
		}
		else{
			  if($this->settings['CustomPayment'] == "on") {
			$script = "jQuery(document).ready(function($) { 
				
			   jQuery('#clickandpledge-custompayment').prop('checked', true);
			   jQuery('.CustomPaymentTitleclass').show();
			
				jQuery('.CustomPaymentTitleclass').parent('div').show();
			   jQuery('label[for=clickandpledge-custompayment]').css('visibility', 'visible');
			   jQuery('label[for=clickandpledge-custom_payment_title]').css('visibility', 'visible');
						
					str = '<option value=CreditCard>Credit Card</option>';
					str += '<option value=eCheck>eCheck</option>';
						
					//jQuery('#clickandpledge-defaultpaymentmethod').html(str);
					});";
		}
			else{
					$script = "jQuery(document).ready(function($) { 
				
			   jQuery('#clickandpledge-custompayment').prop('checked', false);
			   jQuery('.CustomPaymentTitleclass').hide();
			
				
					});";
				
			}
	
		$this->ui->behaviors($script);
		}
		if($rowcount == 0){
		$this->ui->p(0,array(
			'content' => '<span class="cnpdtl">1. Enter the email address associated with your Click &amp; Pledge account, and click on <strong>Get the Code</strong>.</span>'
		));
		  $this->ui->p(0,array(
			'content' => '<span class="cnpdtl">2. Please check your email inbox for the Login Verification Code email.</span>'
		));
		  $this->ui->p(0,array(
			'content' => '<span  class="cnpdtl">3. Enter the provided code and click <strong>Login</strong>.</span>'
		));
		$this->ui->text(0,array(
			'name' => 'cnpshp_emailid',
			'value' => $this->settings['cnpshp_emailid'],
			'size' => 30,
			'class' => "testcls",
			'label' => __('Enter your Connect User Name','Shopp')
		));
		
		$this->ui->text(0,array(
			'name' => 'cnpshp_code',
			'value' => $this->settings['cnpshp_code'],
			'size' => 30,
			'label' => __('Enter Code','Shopp')
		));
		$this->ui->button(0,array(
			'name' => 'cnpshp_btncode',
			'value' => "Get the code",
			'size' => 30,
			'content' => __('Get the code ','Shopp')
		));
		 $this->ui->p(0,array(
			'content' => '<span style="color:#841a09" class="text-danger"></span><span style="color:#008000" class="text-success"></span>'
		));
		}
		else{

			$currentcnppmpusr = $this->getCnPSHPLoginUser();
			if($currentcnppmpusr !=""){
			$this->ui->p(0,array(
			'content' => '<span class="cnpchangeusr"><strong>[You are logged in as: '.$currentcnppmpusr.']</strong></span>'
			));
			}

			$this->ui->p(0,array(
			'content' => '<span class="cnpchangeusr"><strong><a id="cnpchangeacnt" class="cnpchangeacnt" href="#">Change User</a> </strong></span><hr/>'
			));
		
			
			$this->ui->hidden(0,array(
				'name' => 'hdncnpaccount_id',
				'value' => $this->settings['cnpaccount_id'],
				'label' => sprintf(__('','Shopp'))
			));	


		$this->ui->p(0,array(
		'content' => '<label for="clickandpledge-cnpaccount_id" class="lblnbrpaymnts"><b>C&P Account ID</b>  &nbsp; &nbsp; &nbsp;<a href="#" id="rfrshtokens">Refresh Accounts</a></label>'
		));
		$this->ui->menu(0,array(
			'name' => 'cnpaccount_id',
			'selected' => $this->settings['cnpaccount_id']			
			//'label'=>__('','Shopp')
		),$cnpaccountslist);


		$this->ui->p(0,array(
		'content' => '<label for="clickandpledge-testmode" style="padding-top: 20px;display: block;"><b>Select Mode</b></label>'
		));
			$this->ui->menu(0,array(
			'name' => 'testmode',
			'selected' => $this->settings['testmode'],
			//'label'=>__('Select Mode','Shopp')
		),$transactionMode);

		$this->ui->p(0,array(
		'content' => '<label for="clickandpledge-connectcampaignalias" style="padding-top: 20px;display: block;"><b>Connect Campaign URL Alias</b></label>'
		));
		$this->ui->menu(0,array(
			'name' => 'ConnectCampaignAlias',
			'selected' => $this->settings['ConnectCampaignAlias'],
			//'label'=>__('Connect Campaign URL Alias','Shopp')
		),$cnpactiveCampaigns);	
		

		$this->ui->p(0,array(
			'content' => '<div class="lblcnppaymntmthd" style="padding-top: 20px;display: block;"><b>Payment Methods</b></div>'
		));
		$this->ui->hidden(0,array(
				'name' => 'hdndfltpaymnt',
				'value' => $this->settings['defaultpaymentmethod'],
				'label' => sprintf(__('','Shopp'))
			));	
		if(($responsearramex == true || $responsearrJcb == true || $responsearrMaster== true || $responsearrVisa ==true || $responsearrDiscover == true) )
		{
			$this->ui->checkbox(0,array(
				'name' => 'cnpCreditCard',
				'class' => 'CCclass',
				'checked' => true,
				'disabled' => true,
				'label' => sprintf(__('Credit Card','Shopp'))
			));			
			$this->ui->hidden(0,array(
				'name' => 'hdnCreditCard',
				'class' => 'CChdnclass',
				'value' => "yes",
				'label' => sprintf(__('','Shopp'))
			));	

			$this->ui->p(0,array(
				'content' => '<div class="lblcnpacptcrds">Accepted Credit Cards</div>'
			));
				//$defaultpaymentmethod =array("Credit Card"=>"Credit Card");
	    }	    

		if($responsearrVisa ==true)
		{	
			$this->ui->checkbox(0,array(
				'name' => 'cnpVisa',
				'class' => 'CreditCardclass',
				'checked' => true,
				'label'=>__('Visa','Shopp')
			));
			$this->ui->hidden(0,array(
			'name' => 'hdncnpVisa',
			'value' => "yes",
			'label' => sprintf(__('','Shopp'))
		));	
		}
		if($responsearrMaster== true)
		{	
			$this->ui->checkbox(0,array(
				'name' => 'cnpMC',
				'class' => 'CreditCardclass',
				'checked' => true,
				'label'=>__('MasterCard','Shopp')
			));
				$this->ui->hidden(0,array(
				'name' => 'hdncnpMC',
				'value' => "yes",
				'label' => sprintf(__('','Shopp'))
			));	
		}
		if($responsearrDiscover == true)
		{
		$this->ui->checkbox(0,array(
			'name' => 'cnpDisc',
			'class' => 'CreditCardclass',
			'checked' => true,
			'label'=>__('Discover Card','Shopp')
		));
			$this->ui->hidden(0,array(
			'name' => 'hdncnpDisc',
			'value' => "yes",
			'label' => sprintf(__('','Shopp'))
		));	
			}
		if($responsearramex == true  )
		{
			$this->ui->checkbox(0,array(
				'name' => 'cnpAmex',
				'class' => 'CreditCardclass',
				'checked' => true,
				'label'=>__('American Express','Shopp')
			));	
			$this->ui->hidden(0,array(
			'name' => 'hdncnpAmex',
			'value' => "yes",
			'label' => sprintf(__('','Shopp'))
		));	
		}
		if( $responsearrJcb == true  )
		{
		$this->ui->checkbox(0,array(
			'name' => 'cnpJCB',
			'class' => 'CreditCardclass',
			'checked' => true,
			'label'=>__('JCB','Shopp')
		));	
			$this->ui->hidden(0,array(
			'name' => 'hdncnpJCB',
			'value' => "yes",
			'label' => sprintf(__('','Shopp'))
		));	
		}
		if( $responsearrecheck == true  )
		{
		
		$this->ui->checkbox(0,array(
			'name' => 'cnpeCheck',
			'class' => 'eCheckclass',
			'checked' => true,
			'label' => sprintf(__('eCheck','Shopp'))
		));	
			$this->ui->hidden(0,array(
			'name' => 'hdncnpeCheck',
			'value' => "yes",
			'label' => sprintf(__('','Shopp'))
		));	
			//$defaultpaymentmethod =array("eCheck"=>"eCheck");
		}
		/*if( $responsearrCPaymentType == true  )
		{*/
		$this->ui->checkbox(0,array(
			'name' => 'CustomPayment',
			'class' => 'CustomPaymentclass',
			'checked' => ($this->settings['CustomPayment'] == "on"),
			'label' => sprintf(__('Custom Payment','Shopp'))
		));	
		
		$this->ui->textarea(0,array(
			'name' => 'Custom_Payment_title',
			'class' => 'CustomPaymentTitleclass',
			'id' => 'Custom_Payment_title',
			'value' => $this->settings['Custom_Payment_title'],
			'size' => '70',
			'cols' => '70',
			'label' => __('Title(s): Separate with semicolon (;)','Shopp')
		));	
			//}


		$this->ui->p(0,array(
				'content' => '<label for="clickandpledge-defaultpaymentmethod" style="padding-top: 20px;display: block;"><b>Select Default Payment Method</b></div>'
		));

		$this->ui->menu(0,array(
			'name' => 'defaultpaymentmethod',
			'selected' => $this->settings['defaultpaymentmethod'],
			//'label'=>__('Select Default Payment Method','Shopp')
		),$defaultpaymentmethod);
			
		$this->ui->checkbox(0,array(
			'name' => 'NotificationEmail',
			'checked' => ($this->settings['NotificationEmail'] == "on"),
			'label' => sprintf(__('Send Receipt to Patron','Shopp'))
		));	
		

		$this->ui->p(0,array(
				'content' => '<label style="padding-top: 20px;display: block;"><b>Organization Information</b></div>'
		));
		
		$this->ui->textarea(0,array(
			'name' => 'OrganizationInformation',
			'class' => 'OrganizationInformationCNT',
			'id' => 'organizationinformationid',
			'value' => $this->settings['OrganizationInformation'],
			'size' => '70',
			'cols' => '70',
			'rows' => '10',
			'label' => __('(Maximum: 1500 characters)','Shopp'),
		));
		

		$this->ui->p(0,array(
				'content' => '<label style="padding-top: 20px;display: block;"><b>Terms Conditions</b></div>'
		));

		$this->ui->textarea(0,array(
			'name' => 'TermsCondition',
			'class' => 'TermsConditionCNT',
			'id' => 'termsconditionid',
			'value' => $this->settings['TermsCondition'],
			'size' => '70',
			'cols' => '70',
			'rows' => '10',
			'label' => __('(Maximum: 1500 characters)','Shopp')
		));
		
		
		$this->ui->p(0,array(
			'content' => '<h3 class="lblcnprcrstngs"><b>Recurring Settings</b></h3>'
		));
		
		
		$this->ui->p(0,array(
			'content' => '<span class="lblcnppymntoptions"><b>Payment options</b></span>'
		));
		$this->ui->checkbox(0,array(
			'name' => 'payment_options_OneTimeOnly',
			'class' => 'payment_options_Class',
			'checked' => ($this->settings['payment_options_OneTimeOnly'] == "on"),
			'label' => sprintf(__('One Time Only','Shopp'))
		));
		
		$this->ui->checkbox(0,array(
			'name' => 'payment_options_recurring',
			'class' => 'payment_options_Class',
			'checked' => ($this->settings['payment_options_recurring'] == "on"),
			'label' => sprintf(__('Recurring','Shopp'))
		));	
	
		$this->ui->p(0,array(
				'content' => '<label for="clickandpledge-defaultpaymentoptions" style="display: block;">Select Default Payment Options</div>'
		));
		
		$this->ui->menu(0,array(
			'name' => 'defaultpaymentoptions',
			'selected' => $this->settings['defaultpaymentoptions'],
			//'label'=>__('Select Default Payment Options','Shopp')
		),$defaultpaymentoptions);		
		
		$this->ui->p(0,array(
			'content' => '<label class="lblrcrtyp" style="padding-top: 20px;display: block;"><b>Recurring types</b></label>'
		));
		$this->ui->checkbox(0,array(
			'name' => 'recurringtype_installment',
			'class' => 'Chkrecurring',
			'checked' => ($this->settings['recurringtype_installment'] == "on"),
			'label' => sprintf(__('Installment (e.g. pay $1000 in 10 installments of $100 each)','Shopp'))
		));
		
		$this->ui->checkbox(0,array(
			'name' => 'recurringtype_subscription',
			'class' => 'Chkrecurring',
			'checked' => ($this->settings['recurringtype_subscription'] == "on"),
			'label' => sprintf(__('Subscription (e.g. pay $100 every month for 12 months)','Shopp'))
		));
		
		$this->ui->p(0,array(
				'content' => '<label for="clickandpledge-defaultpaymentoptions" style="display: block;">Select Default Recurring types</div>'
		));		
		
		$this->ui->menu(0,array(
			'name' => 'defaultrecurringoptions',
			'selected' => $this->settings['defaultrecurringoptions'],
			//'label'=>__('Select Default Recurring types','Shopp')
		),$defaultrecurringoptions);
			
		$this->ui->p(0,array(
			'content' => '<label class="lblprdcty" style="padding-top: 20px;display: block;"><b>Periodicity</b></label>'
		));
		
		$this->ui->checkbox(0,array(
			'name' => 'Week',
			'class' => 'periodicity',
			'checked' =>$this->settings['Week'],
			'label' => __('Week','Shopp')
		));	
		
		$this->ui->checkbox(0,array(
			'name' => '2 Weeks',
			'class' => 'periodicity',
			'checked' => $this->settings['2 Weeks'],
			'label' => __('2 Weeks','Shopp')
		));
		
		$this->ui->checkbox(0,array(
			'name' => 'Month',
			'class' => 'periodicity',
			'checked' => $this->settings['Month'],
			'label' => __('Month','Shopp')
		));
		
		$this->ui->checkbox(0,array(
			'name' => '2 Months',
			'class' => 'periodicity',
			'checked' => $this->settings['2 Months'],
			'label' => __('2 Months','Shopp')
		));
		
		$this->ui->checkbox(0,array(
			'name' => 'Quarter',
			'class' => 'periodicity',
			'checked' => $this->settings['Quarter'],
			'label' => __('Quarter','Shopp')
		));
		
		$this->ui->checkbox(0,array(
			'name' => '6 Months',
			'class' => 'periodicity',
			'checked' => $this->settings['6 Months'],
			'label' => __('6 Months','Shopp')
		));
		
		$this->ui->checkbox(0,array(
			'name' => 'Year',
			'class' => 'periodicity',
			'checked' => $this->settings['Year'],
			'label' => __('Year','Shopp')
		));	
		
		$this->ui->p(0,array(
			'content' => '<label class="lblnbrpaymnts" style="padding-top: 20px;display: block;"><b>Number of payments</b></label>'
		));
		$this->ui->menu(0,array(
			'name' => 'noofpaymentsvalue',
			'selected' => $this->settings['noofpaymentsvalue'],
			'placeholder' => 'Number of Payments',
			'label'=>__('Select Number of Payments','Shopp')
		),$noofpaymentsvalue);
	
		
		$defnoofpayments = $this->settings['defnoofpayments'];
		$this->ui->text(0,array(
			'name' => 'defnoofpayments',
			'value' => $defnoofpayments,
			'size' => 30,
			'maxlength'=>3,
			'placeholder' => 'Default Number of Payments',
			'label'=>__('Enter Default Number of Payments','Shopp')
		));
		
	$maxnoofinstallments = ($this->settings['maxnoofinstallments']) ? $this->settings['maxnoofinstallments'] : '';
		$this->ui->text(0,array(
			'name' => 'maxnoofinstallments',
			'value' => $maxnoofinstallments,
			'size' => 30,
			'maxlength'=>3,
			'placeholder' => 'Maximum Number of Installments',
			'label'=>__('Enter Maximum Number of Installments allowed','Shopp')
		));
		}
	}	 
} // END class ClickandPledge document.getElementsByTagName('head')[0].appendChild(script);

?>