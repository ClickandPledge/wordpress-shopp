jQuery(document).ready(function() {
	
	function getURLParameter(url, name) {
		return (RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1];
	}
	/*		ONLOAD START		*/
	getdefaultpaymentmedhods();
	
	jQuery('#clickandpledge-cnpaccount_id').change(function() {
	
		var  cnpshpaccountid= jQuery('#clickandpledge-cnpaccount_id').val().trim();
		var  cnpwJcamp= jQuery('#clickandpledge-connectcampaignalias').val().trim();
		var resarr = cnpshpaccountid.split("[");
		var  cnpwJaccountid = resarr[0].trim();

		 	 jQuery.ajax({
				  type: "POST", 
				  url : ajaxurl ,
				  data: {
						'action':'getCnPUserconectAccountList',
					  	'cnpacid':cnpwJaccountid,
					  	'cnpcamp':cnpwJcamp,
						},
				  cache: false,
				  beforeSend: function() {
					
					jQuery("#clickandpledge-connectcampaignalias").html("<option>Loading............</option>");
					},
					complete: function() {
					
					},	
				  success: function(htmlText) {
				
				  if(htmlText !== "")
				  {
					
					var res = htmlText.split("||");
					    jQuery("#clickandpledge-cnpecheck").hide();
						jQuery("label[for='clickandpledge-cnpecheck']").css('visibility', 'hidden');

						jQuery("#clickandpledge-cnpjcb").hide();
						jQuery("label[for='clickandpledge-cnpjcb']").css('visibility', 'hidden');

						jQuery("#clickandpledge-cnpamex").hide();
						jQuery("label[for='clickandpledge-cnpamex']").css('visibility', 'hidden');

						jQuery("#clickandpledge-cnpdisc").hide();
						jQuery("label[for='clickandpledge-cnpdisc']").css('visibility', 'hidden');

						jQuery("#clickandpledge-cnpmc").hide();
						jQuery("label[for='clickandpledge-cnpmc']").css('visibility', 'hidden');

						jQuery("#cclickandpledge-cnpvisa").hide();
						jQuery("label[for='clickandpledge-cnpvisa']").css('visibility', 'hidden');

						jQuery("#clickandpledge-cnpcreditcard").hide();
						jQuery("label[for='clickandpledge-cnpcreditcard']").css('visibility', 'hidden');
						jQuery(".lblcnpacptcrds").hide();		
					  
						jQuery("#clickandpledge-connectcampaignalias").html(res[0]);  
						jQuery(".lblcnppaymntmthd").html(res[1]); 
					  
					  if(res[2] === ""){
						  jQuery('#clickandpledge-custompayment').prop('checked', false);
						    jQuery("#clickandpledge-custompayment").hide();
						  	jQuery('.CustomPaymentTitleclass').hide();
							jQuery("label[for='clickandpledge-custompayment']").css('visibility', 'hidden');
							jQuery("label[for='clickandpledge-custom_payment_title']").css('visibility', 'hidden');
						
						  getdefaultpaymentmedhods();
						 	
					  }
					  else
						  {
							jQuery("#clickandpledge-custompayment").attr('checked','checked');
							jQuery("#clickandpledge-custompayment").show();
						  	jQuery('.CustomPaymentTitleclass').show();
							jQuery("label[for='clickandpledge-custompayment']").css('visibility', 'visible');
							jQuery("label[for='clickandpledge-custom_payment_title']").css('visibility', 'visible');
						   // jQuery('#wpjobboard_clickandpledge_reference').parents('tr').show();
							  
							  getdefaultpaymentmedhods();
						  }
					 }
				  else
				  {
				  jQuery(".cnperror").show();
				  }
					
				  },
				  error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				  }
				});
	 return false;
 });
	jQuery('#rfrshtokens').on('click', function() 
		 {  			 	
			var rcnpshoppaccountid = jQuery('#clickandpledge-cnpaccount_id').val().trim();
	
		 	 jQuery.ajax({
				  type: "POST", 
				  url: ajaxurl ,
				  data: {
						'action':'getshoppCnPAccountList',
					  	'rcnpshoppaccountid':rcnpshoppaccountid,
						},
				    cache: false,
				    beforeSend: function() {
				
					jQuery("#clickandpledge-cnpaccount_id").html("<option>Loading............</option>");
					},
					complete: function() {
						jQuery('.cnp_loader').hide();
					
					},	
				  success: function(htmlText) {
					
				  if(htmlText !== "")
				  {
					
					jQuery("#clickandpledge-cnpaccount_id").html(htmlText);  
				    jQuery("#clickandpledge-cnpaccount_id").change();
				  
				  }
				  else
				  {
				  jQuery(".cnperror").show();
				  }
					
				  },
				  error: function(xhr, ajaxOptions, thrownError) {
				  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				  }
				});
	 return false;
 });
	jQuery('.cnpchangeacnt').on('click', function() 
		 {  			 	
			
		 	 jQuery.ajax({
				  type: "POST", 
				  url: ajaxurl ,
				  data: {
						'action':'getShoppCnPDeleteAccountList',
					  	
						},
				    cache: false,
				    beforeSend: function() {
					jQuery('.cnp_loader').show();
					
					},
					complete: function() {
						jQuery('.cnp_loader').hide();
					
					},	
				  success: function(htmlText) {
				
				  location.reload()
					
				  },
				  error: function(xhr, ajaxOptions, thrownError) {
				  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				  }
				});
	 return false;
 });
	cnphidefields();
	if(jQuery("#clickandpledge-cnpcreditcard").prop("checked") == true){
	
		jQuery("#clickandpledge-cnpcreditcard").attr("disabled", true);
	}
		if(jQuery("#clickandpledge-cnpvisa").prop("checked") == true){
	
		jQuery("#clickandpledge-cnpvisa").attr("disabled", true);
	}
		if(jQuery("#clickandpledge-cnpmc").prop("checked") == true){ 
	
		jQuery("#clickandpledge-cnpmc").attr("disabled", true);
	}
		if(jQuery("#clickandpledge-cnpdisc").prop("checked") == true){ 
	
		jQuery("#clickandpledge-cnpdisc").attr("disabled", true);
	}
		if(jQuery("#clickandpledge-cnpamex").prop("checked") == true){ 
	
		jQuery("#clickandpledge-cnpamex").attr("disabled", true);
	}
	if(jQuery("#clickandpledge-cnpjcb").prop("checked") == true){ 
		jQuery("#clickandpledge-cnpjcb").attr("disabled", true);
	}
	if(jQuery("#clickandpledge-cnpecheck").prop("checked") == true){ 
	
		jQuery("#clickandpledge-cnpecheck").attr("disabled", true);
	}
	function cnphidefields(){
	
	jQuery("#clickandpledge-label").hide();
	jQuery("label[for='clickandpledge-label']").css('visibility', 'hidden');
									
	jQuery("#clickandpledge-cnpshp_code	").hide();
	jQuery("label[for='clickandpledge-cnpshp_code']").css('visibility', 'hidden');
	}
	function cnphidefrmsettingfields(){
	
	jQuery("#clickandpledge-payment_options_recurring").hide();
	jQuery("label[for='clickandpledge-payment_options_recurring']").css('visibility', 'hidden');
									
	jQuery("#clickandpledge-payment_options_onetimeonly").hide();
	jQuery("label[for='clickandpledge-payment_options_onetimeonly']").css('visibility', 'hidden');
		
	//jQuery("#clickandpledge-payment_options_recurring").hide();
	jQuery("label[for='clickandpledge-termsconditionid']").css('visibility', 'hidden');
	jQuery(".TermsConditionCNT").parent('div').hide();
	
	jQuery("label[for='clickandpledge-organizationinformationid']").css('visibility', 'hidden');
	jQuery(".OrganizationInformationCNT").parent('div').hide();
	
	jQuery("#clickandpledge-notificationemail").hide();
	jQuery("label[for='clickandpledge-notificationemail']").css('visibility', 'hidden');
		
	jQuery("#clickandpledge-cnpecheck").hide();
	jQuery("label[for='clickandpledge-cnpecheck']").css('visibility', 'hidden');
	
	jQuery("#clickandpledge-cnpjcb").hide();
	jQuery("label[for='clickandpledge-cnpjcb']").css('visibility', 'hidden');
	
	jQuery("#clickandpledge-cnpamex").hide();
	jQuery("label[for='clickandpledge-cnpamex']").css('visibility', 'hidden');
	
	jQuery("#clickandpledge-cnpdisc").hide();
	jQuery("label[for='clickandpledge-cnpdisc']").css('visibility', 'hidden');
		
	jQuery("#clickandpledge-cnpmc").hide();
	jQuery("label[for='clickandpledge-cnpmc']").css('visibility', 'hidden');
		
	jQuery("#cclickandpledge-cnpvisa").hide();
	jQuery("label[for='clickandpledge-cnpvisa']").css('visibility', 'hidden');
		
	jQuery("#clickandpledge-cnpcreditcard").hide();
	jQuery("label[for='clickandpledge-cnpcreditcard']").css('visibility', 'hidden');
		
	jQuery("#clickandpledge-connectcampaignalias").hide();
	jQuery("label[for='clickandpledge-connectcampaignalias']").css('visibility', 'hidden');
	
	jQuery("#clickandpledge-cnpaccount_id").hide();
	jQuery("label[for='clickandpledge-cnpaccount_id']").css('visibility', 'hidden');
	
	jQuery("#clickandpledge-testmode").hide();
	jQuery("label[for='clickandpledge-testmode']").css('visibility', 'hidden');
	
	jQuery(".lblcnppaymntmthd").parent('div').hide();	
	jQuery(".lblcnpacptcrds").parent('div').hide();	
	jQuery(".lblcnprcrstngs").parent('div').hide();	
	jQuery(".lblcnppymntoptions").parent('div').hide();	
	jQuery(".cnpchangeusr").parent('div').hide();	
		
		
	}
	function cnprecurringtrue()
	{
		jQuery("#RecurringTitle").css("display", "block");
		jQuery(".RecurringTitleClass").parent('div').show();
		jQuery(".lblrcrtyp").parent('div').show();
		jQuery(".lblprdcty").parent('div').show();
		jQuery(".lblnbrpaymnts").parent('div').show();
		jQuery("#clickandpledge-recurringfieldlabel").css("display", "block");
		jQuery('label[for="clickandpledge-recurringfieldlabel"]').css('visibility', 'visible');
		jQuery("#clickandpledge-recurringfieldlabel").parent('div').show();
		
		jQuery(".RecurringfieldDescriptionClass").css("display", "block");
		jQuery('label[for="clickandpledge-recurringfielddescription"]').css('visibility', 'visible');
		jQuery(".RecurringfieldDescriptionClass").parent('div').show();
		
		jQuery("#clickandpledge-payment_options_label").css("display", "block");
		jQuery('label[for="clickandpledge-payment_options_label"]').css('visibility', 'visible');
		jQuery("#clickandpledge-payment_options_label").parent('div').show();
		
		jQuery("#clickandpledge-defaultpaymentoptions").css("display", "block");
		jQuery('label[for="clickandpledge-defaultpaymentoptions"]').css('visibility', 'visible');
		jQuery("#clickandpledge-defaultpaymentoptions").parent('div').show();
		
		jQuery("#clickandpledge-recurring_types_label").css("display", "block");
		jQuery('label[for="clickandpledge-recurring_types_label"]').css('visibility', 'visible');
		jQuery("#clickandpledge-recurring_types_label").parent('div').show();
		
		jQuery("#clickandpledge-recurringtype_installment").show();//css("display", "block");
		jQuery('label[for="clickandpledge-recurringtype_installment"]').show();//css('visibility', 'visible');
		jQuery("#clickandpledge-recurringtype_installment").parent('label').parent('div').show();
		
		jQuery("#clickandpledge-recurringtype_subscription").show();//css("display", "block");
		jQuery('label[for="clickandpledge-recurringtype_subscription"]').show();//css('visibility', 'visible');
		jQuery("#clickandpledge-recurringtype_subscription").parent('label').parent('div').show();
		
		var defRec = jQuery(".Chkrecurring").filter(':checked').length;
	
		if(defRec == 2)
		{
			jQuery("#clickandpledge-defaultrecurringoptions").css("display", "block");
			jQuery('label[for="clickandpledge-defaultrecurringoptions"]').css('visibility', 'visible');
			jQuery("#clickandpledge-defaultrecurringoptions").parent('div').show();
		}
		else
		{
			jQuery("#clickandpledge-defaultrecurringoptions").css("display", "none");
			jQuery('label[for="clickandpledge-defaultrecurringoptions"]').css('visibility', 'hidden');
			jQuery("#clickandpledge-defaultrecurringoptions").parent('div').hide();
		}
		jQuery("#clickandpledge-periodicity").css("display", "block");
		jQuery('label[for="clickandpledge-periodicity"]').css('visibility', 'visible');
		jQuery("#clickandpledge-periodicity").parent('div').show();
		
		jQuery("#clickandpledge-week").show();
		jQuery('label[for="clickandpledge-week"]').show();
		jQuery("#clickandpledge-week").parent('label').parent('div').show();
		
		jQuery("#clickandpledge-2-weeks").show();
		jQuery('label[for="clickandpledge-2-weeks"]').show();
		jQuery("#clickandpledge-2-weeks").parent('label').parent('div').show();
		
		jQuery("#clickandpledge-month").show();
		jQuery('label[for="clickandpledge-month"]').show();
		jQuery("#clickandpledge-month").parent('label').parent('div').show();
		
		jQuery("#clickandpledge-2-months").show();
		jQuery('label[for="clickandpledge-2-months"]').show();
		jQuery("#clickandpledge-2-months").parent('label').parent('div').show();
		
		jQuery("#clickandpledge-quarter").show();
		jQuery('label[for="clickandpledge-quarter"]').show();
		jQuery("#clickandpledge-quarter").parent('label').parent('div').show();
		
		jQuery("#clickandpledge-6-months").show();
		jQuery('label[for="clickandpledge-6-months"]').show();
		jQuery("#clickandpledge-6-months").parent('label').parent('div').show();
		
		jQuery("#clickandpledge-year").show();
		jQuery('label[for="clickandpledge-year"]').show();
		jQuery("#clickandpledge-year").parent('label').parent('div').show();
		
		jQuery("#clickandpledge-noofpayments").css("display", "block");
		jQuery('label[for="clickandpledge-noofpayments"]').css('visibility', 'visible');
		jQuery("#clickandpledge-noofpayments").parent('div').show();
		
		jQuery("#clickandpledge-noofpaymentsvalue").css("display", "block");
		jQuery('label[for="clickandpledge-noofpaymentsvalue"]').css('visibility', 'visible');
		jQuery("#clickandpledge-noofpaymentsvalue").parent('div').show();
		
		var noofpayhidden = jQuery("#clickandpledge-noofpaymentsvalue").val();
		var valSelected = "";
		if((jQuery("#clickandpledge-recurringtype_installment").is(':checked') == true) && (jQuery("#clickandpledge-recurringtype_subscription").is(':checked') == true))
		{			
			jQuery('#clickandpledge-noofpaymentsvalue').html('');
			
			if(noofpayhidden == "Please Select")	valSelected = "selected";
			else									valSelected = "";
			jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Please Select" '+valSelected+'>Please Select</option>');
			
			if(noofpayhidden == "Indefinite Only")	valSelected = "selected";
			else									valSelected = "";
			jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Indefinite Only" '+valSelected+'>Indefinite Only</option>');
			
			if(noofpayhidden == "Open Field Only")	valSelected = "selected";
			else									valSelected = "";
			jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Open Field Only" '+valSelected+'>Open Field Only</option>');
			
			if(noofpayhidden == "Indefinite + Open Field Option")	valSelected = "selected";
			else									valSelected = "";
			jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Indefinite + Open Field Option" '+valSelected+'>Indefinite + Open Field Option</option>');
			
			if(noofpayhidden == "Fixed Number - No Change Allowed")	valSelected = "selected";
			else													valSelected = "";
			jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Fixed Number - No Change Allowed" '+valSelected+'>Fixed Number - No Change Allowed</option>');
		}
		if((jQuery("#clickandpledge-recurringtype_installment").is(':checked') == true) && (jQuery("#clickandpledge-recurringtype_subscription").is(':checked') == false))
		{
			jQuery("#clickandpledge-defaultrecurringoptions").css("display", "none");
			jQuery('label[for="clickandpledge-defaultrecurringoptions"]').css('visibility', 'hidden');
			jQuery("#clickandpledge-defaultrecurringoptions").parent('div').hide();
			
			jQuery('#clickandpledge-noofpaymentsvalue').html('');
			
			if(noofpayhidden == "Please Select")	valSelected = "selected";
			else									valSelected = "";
			jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Please Select" '+valSelected+'>Please Select</option>');
			
			if(noofpayhidden == "Open Field Only")	valSelected = "selected";
			else									valSelected = "";
			jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Open Field Only" '+valSelected+'>Open Field Only</option>');
			
			if(noofpayhidden == "Fixed Number - No Change Allowed")	valSelected = "selected";
			else													valSelected = "";
			jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Fixed Number - No Change Allowed" '+valSelected+'>Fixed Number - No Change Allowed</option>');
		}
		if((jQuery("#clickandpledge-recurringtype_installment").is(':checked') == false) && (jQuery("#clickandpledge-recurringtype_subscription").is(':checked') == true))
		{
			jQuery("#clickandpledge-defaultrecurringoptions").css("display", "none");
			jQuery('label[for="clickandpledge-defaultrecurringoptions"]').css('visibility', 'hidden');
			jQuery("#clickandpledge-defaultrecurringoptions").parent('div').hide();
		
			jQuery('#clickandpledge-noofpaymentsvalue').html('');
			
			if(noofpayhidden == "Please Select")	valSelected = "selected";
			else									valSelected = "";
			jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Please Select" '+valSelected+'>Please Select</option>');
			
			if(noofpayhidden == "Indefinite Only")	valSelected = "selected";
			else									valSelected = "";
			jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Indefinite Only" '+valSelected+'>Indefinite Only</option>');
			
			if(noofpayhidden == "Open Field Only")	valSelected = "selected";
			else									valSelected = "";
			jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Open Field Only" '+valSelected+'>Open Field Only</option>');
			
			if(noofpayhidden == "Indefinite + Open Field Option")	valSelected = "selected";
			else													valSelected = "";
			jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Indefinite + Open Field Option" '+valSelected+'>Indefinite + Open Field Option</option>');
			
			if(noofpayhidden == "Fixed Number - No Change Allowed")	valSelected = "selected";
			else													valSelected = "";
			jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Fixed Number - No Change Allowed" '+valSelected+'>Fixed Number - No Change Allowed</option>');
		}
		
		var noofpay = jQuery("#clickandpledge-noofpaymentsvalue").val();
		var defRec = jQuery(".Chkrecurring").filter(':checked').length;
/*alert(noofpay+' - '+defRec+' - '+jQuery("#clickandpledge-recurringtype_subscription").is(':checked')+' - '+jQuery("#clickandpledge-recurringtype_installment").is(':checked'));*/
		if(noofpay == "Indefinite Only")
		{
			jQuery("#clickandpledge-defnoofpaymentslbl").css("display", "none");
			jQuery('label[for="clickandpledge-defnoofpaymentslbl"]').css('visibility', 'hidden');
			jQuery("#clickandpledge-defnoofpaymentslbl").parent('div').show();
			
			jQuery("#clickandpledge-defnoofpayments").css("display", "none");
			jQuery('label[for="clickandpledge-defnoofpayments"]').css('visibility', 'hidden');
			jQuery("#clickandpledge-defnoofpayments").parent('div').show();
			
			jQuery("#clickandpledge-maxnoofinstallmentslbl").css("display", "none");
			jQuery('label[for="clickandpledge-maxnoofinstallmentslbl"]').css('visibility', 'hidden');
			jQuery("#clickandpledge-maxnoofinstallmentslbl").parent('div').show();
			
			jQuery("#clickandpledge-maxnoofinstallments").css("display", "none");
			jQuery('label[for="clickandpledge-maxnoofinstallments"]').css('visibility', 'hidden');
			jQuery("#clickandpledge-maxnoofinstallments").parent('div').show();
		}
		if(noofpay == "Open Field Only")
		{
			jQuery("#clickandpledge-defnoofpaymentslbl").css("display", "block");
			jQuery('label[for="clickandpledge-defnoofpaymentslbl"]').css('visibility', 'visible');
			jQuery("#clickandpledge-defnoofpaymentslbl").parent('div').show();
			
			jQuery("#clickandpledge-defnoofpayments").css("display", "block");
			jQuery('label[for="clickandpledge-defnoofpayments"]').css('visibility', 'visible');
			jQuery("#clickandpledge-defnoofpayments").parent('div').show();
			
			jQuery("#clickandpledge-maxnoofinstallmentslbl").css("display", "block");
			jQuery('label[for="clickandpledge-maxnoofinstallmentslbl"]').css('visibility', 'visible');
			jQuery("#clickandpledge-maxnoofinstallmentslbl").parent('div').show();
			
			jQuery("#clickandpledge-maxnoofinstallments").css("display", "block");
			jQuery('label[for="clickandpledge-maxnoofinstallments"]').css('visibility', 'visible');
			jQuery("#clickandpledge-maxnoofinstallments").parent('div').show();
		}
		if(noofpay == "Indefinite + Open Field Option")
		{
			var defnoofpayments = jQuery("#clickandpledge-defnoofpayments").val();
			jQuery("#clickandpledge-defnoofpaymentslbl").css("display", "block");
			jQuery('label[for="clickandpledge-defnoofpaymentslbl"]').css('visibility', 'visible');
			jQuery("#clickandpledge-defnoofpaymentslbl").parent('div').show();
			
			if(defnoofpayments == "")
			{
				jQuery("#clickandpledge-defnoofpayments").val("999");
				//jQuery("#clickandpledge-defnoofpayments").prop('readonly',true);
				jQuery("#clickandpledge-defnoofpayments").parent('div').show();
			}
			
			var maxnoofinstallments = jQuery("#clickandpledge-maxnoofinstallments").val();
			jQuery("#clickandpledge-maxnoofinstallmentslbl").css("display", "block");
			jQuery('label[for="clickandpledge-maxnoofinstallmentslbl"]').css('visibility', 'visible');
			jQuery("#clickandpledge-maxnoofinstallmentslbl").parent('div').show();
			if(maxnoofinstallments == "")
			{
				jQuery("#clickandpledge-maxnoofinstallments").val("999");
				
				jQuery("#clickandpledge-maxnoofinstallments").parent('div').show();
			}jQuery("#clickandpledge-maxnoofinstallments").attr("readonly",true);
		}
		if(noofpay == "Fixed Number - No Change Allowed")
		{
			jQuery("#clickandpledge-defnoofpaymentslbl").css("display", "block");
			jQuery('label[for="clickandpledge-defnoofpaymentslbl"]').css('visibility', 'visible');
			jQuery("#clickandpledge-defnoofpaymentslbl").parent('div').show();
			
			jQuery("#clickandpledge-defnoofpayments").css("display", "block");
			jQuery('label[for="clickandpledge-defnoofpayments"]').css('visibility', 'visible');
			jQuery("#clickandpledge-defnoofpayments").parent('div').show();
			
			jQuery("#clickandpledge-maxnoofinstallmentslbl").css("display", "none");
			jQuery('label[for="clickandpledge-maxnoofinstallmentslbl"]').css('visibility', 'hidden');
			jQuery("#clickandpledge-maxnoofinstallmentslbl").parent('div').show();
			
			jQuery("#clickandpledge-maxnoofinstallments").css("display", "none");
			jQuery('label[for="clickandpledge-maxnoofinstallments"]').css('visibility', 'hidden');
			jQuery("#clickandpledge-maxnoofinstallments").parent('div').show();
		}
	}
	function cnprecurringfalse()
	{
			jQuery("#RecurringTitle").css("display", "none");
		    jQuery(".RecurringTitleClass").parent('div').hide();
		    jQuery(".lblrcrtyp").parent('div').hide();
			jQuery(".lblprdcty").parent('div').hide();
		    jQuery(".lblnbrpaymnts").parent('div').hide();
		

		
		jQuery("#clickandpledge-defaultpaymentoptions").css("display", "none");
		jQuery('label[for="clickandpledge-defaultpaymentoptions"]').css('visibility', 'hidden');
		jQuery("#clickandpledge-defaultpaymentoptions").parent('div').hide();
		
		jQuery("#clickandpledge-recurring_types_label").css("display", "none");
		jQuery('label[for="clickandpledge-recurring_types_label"]').css('visibility', 'hidden');
		jQuery("#clickandpledge-recurring_types_label").parent('div').hide();
		
		jQuery("#clickandpledge-recurringtype_installment").hide();//css("display", "none");
		jQuery('label[for="clickandpledge-recurringtype_installment"]').hide();//css('visibility', 'hidden');
		jQuery(".Chkrecurring").parent('label').parent('div').hide();
		
		jQuery("#clickandpledge-recurringtype_subscription").hide();//css("display", "none");
		jQuery('label[for="clickandpledge-recurringtype_subscription"]').hide();//css('visibility', 'hidden');
		jQuery("#clickandpledge-recurringtype_subscription").parent('label').parent('div').hide();
		
		jQuery("#clickandpledge-defaultrecurringoptions").css("display", "none");
		jQuery('label[for="clickandpledge-defaultrecurringoptions"]').css('visibility', 'hidden');
		jQuery("#clickandpledge-defaultrecurringoptions").parent('div').hide();
		
		jQuery("#clickandpledge-periodicity").css("display", "none");
		jQuery('label[for="clickandpledge-periodicity"]').css('visibility', 'hidden');
		jQuery("#clickandpledge-periodicity").parent('div').hide();
		
		jQuery("#clickandpledge-week").hide();
		jQuery('label[for="clickandpledge-week"]').hide();
		jQuery("#clickandpledge-week").parent('label').parent('div').hide();
		
		jQuery("#clickandpledge-2-weeks").hide();
		jQuery('label[for="clickandpledge-2-weeks"]').hide();
		jQuery("#clickandpledge-2-weeks").parent('label').parent('div').hide();
		
		jQuery("#clickandpledge-month").hide();
		jQuery('label[for="clickandpledge-month"]').hide();
		jQuery("#clickandpledge-month").parent('label').parent('div').hide();
		
		jQuery("#clickandpledge-2-months").hide();
		jQuery('label[for="clickandpledge-2-months"]').hide();
		jQuery("#clickandpledge-2-months").parent('label').parent('div').hide();
		
		jQuery("#clickandpledge-quarter").hide();
		jQuery('label[for="clickandpledge-quarter"]').hide();
		jQuery("#clickandpledge-quarter").parent('label').parent('div').hide();
		
		jQuery("#clickandpledge-6-months").hide();
		jQuery('label[for="clickandpledge-6-months"]').hide();
		jQuery("#clickandpledge-6-months").parent('label').parent('div').hide();
		
		jQuery("#clickandpledge-year").hide();
		jQuery('label[for="clickandpledge-year"]').hide();
		jQuery("#clickandpledge-year").parent('label').parent('div').hide();
		
		jQuery("#clickandpledge-noofpayments").css("display", "none");
		jQuery('label[for="clickandpledge-noofpayments"]').css('visibility', 'hidden');
		jQuery("#clickandpledge-noofpayments").parent('div').hide();
		
		jQuery("#clickandpledge-noofpaymentsvalue").css("display", "none");
		jQuery('label[for="clickandpledge-noofpaymentsvalue"]').css('visibility', 'hidden');
		jQuery("#clickandpledge-noofpaymentsvalue").parent('div').hide();
		
		jQuery("#clickandpledge-defnoofpaymentslbl").css("display", "none");
		jQuery('label[for="clickandpledge-defnoofpaymentslbl"]').css('visibility', 'hidden');
		jQuery("#clickandpledge-defnoofpaymentslbl").parent('div').hide();
		
		jQuery("#clickandpledge-defnoofpayments").css("display", "none");
		jQuery('label[for="clickandpledge-defnoofpayments"]').css('visibility', 'hidden');
		jQuery("#clickandpledge-defnoofpayments").parent('div').hide();
		
		jQuery("#clickandpledge-maxnoofinstallmentslbl").css("display", "none");
		jQuery('label[for="clickandpledge-maxnoofinstallmentslbl"]').css('visibility', 'hidden');
		jQuery("#clickandpledge-maxnoofinstallmentslbl").parent('div').hide();
		
		jQuery("#clickandpledge-maxnoofinstallments").css("display", "none");
		jQuery('label[for="clickandpledge-maxnoofinstallments"]').css('visibility', 'hidden');
		jQuery("#clickandpledge-maxnoofinstallments").parent('div').hide();
	}
	var recurringSelected = jQuery("#clickandpledge-payment_options_recurring").is(':checked');
	
	var countCheckedCheckboxes = jQuery(".payment_options_Class").filter(':checked').length;
	if (recurringSelected == false) {
		 
		cnprecurringfalse();
	 }
	 if (recurringSelected == true) {
		 
		cnprecurringtrue();
	 }
	 if (countCheckedCheckboxes <= 1) {
		jQuery("#clickandpledge-defaultpaymentoptions").css("display", "none");
		jQuery('label[for="clickandpledge-defaultpaymentoptions"]').css('visibility', 'hidden');
		jQuery("#clickandpledge-defaultpaymentoptions").parent('div').hide();
			
	 } if (countCheckedCheckboxes == 2) {
		jQuery("#clickandpledge-defaultpaymentoptions").css("display", "block");
		jQuery('label[for="clickandpledge-defaultpaymentoptions"]').css('visibility', 'visible');
		jQuery("#clickandpledge-defaultpaymentoptions").parent('div').show();
	 }
		
	/*if(jQuery("#clickandpledge-creditcard").is(":checked") == true)
	{
		jQuery(".AcceptedCreditCards").show();
		jQuery(".AcceptedCreditCards").parent('div').show();
		
		//jQuery('#clickandpledge-visa').attr('checked', 'true');
		jQuery('label[for="clickandpledge-visa"]').css('visibility', 'visible');
		jQuery("#clickandpledge-visa").parent('label').parent('div').show();
		
		//jQuery('#clickandpledge-mc').attr('checked', 'true');
		jQuery('label[for="clickandpledge-mc"]').css('visibility', 'visible');
		jQuery("#clickandpledge-mc").parent('label').parent('div').show();
		
		//jQuery('#clickandpledge-disc').attr('checked', 'true');
		jQuery('label[for="clickandpledge-disc"]').css('visibility', 'visible');
		jQuery("#clickandpledge-disc").parent('label').parent('div').show();
		
		//jQuery('#clickandpledge-amex').attr('checked', 'true');
		jQuery('label[for="clickandpledge-amex"]').css('visibility', 'visible');
		jQuery("#clickandpledge-amex").parent('label').parent('div').show();
		
		
		//jQuery('#clickandpledge-jcb').attr('checked', 'true');
		jQuery('label[for="clickandpledge-jcb"]').css('visibility', 'visible');
		jQuery("#clickandpledge-jcb").parent('label').parent('div').show();
		
		jQuery('#CreditCardLimit').show();
		jQuery("#CreditCardLimit").parent('div').show();
	}
	if(jQuery("#clickandpledge-creditcard").is(":checked") == false)
	{
		jQuery(".AcceptedCreditCards").hide();
		jQuery(".AcceptedCreditCards").parent('div').hide();
		
		jQuery('label[for="clickandpledge-visa"]').css('visibility', 'hidden');
		jQuery("#clickandpledge-visa").parent('label').parent('div').hide();
		
		jQuery('label[for="clickandpledge-mc"]').css('visibility', 'hidden');
		jQuery("#clickandpledge-mc").parent('label').parent('div').hide();
		
		jQuery('label[for="clickandpledge-disc"]').css('visibility', 'hidden');
		jQuery("#clickandpledge-disc").parent('label').parent('div').hide();
		
		jQuery('label[for="clickandpledge-amex"]').css('visibility', 'hidden');
		jQuery("#clickandpledge-amex").parent('label').parent('div').hide();
		
		jQuery('label[for="clickandpledge-jcb"]').css('visibility', 'hidden');
		jQuery("#clickandpledge-jcb").parent('label').parent('div').hide();
		
		jQuery('#CreditCardLimit').hide();
		jQuery("#CreditCardLimit").parent('div').hide();
	}*/
		
	/*		ONLOAD fill Default Payment Methods START		*/
	var creditcard = jQuery(".CCclass").filter(':checked').length;
	var eCheck = jQuery(".eCheckclass").filter(':checked').length;
	var CustomPayment = jQuery(".CustomPaymentclass").filter(':checked').length;
	var defval_selected = "";
	

		
	/*		ONLOAD END		*/
	/* MY Code()*/
	jQuery(document).on('click', '#clickandpledge-cnpshp_btncode', function(e) {
//	if(jQuery("input[name=gateway]").val() == "ClickandPledge"){
		//jQuery('#clickandpledge-cnpshp_btncode').on('click', function(e) 
		// {  
			  e.stopPropagation();
		 	 if(jQuery('#clickandpledge-cnpshp_btncode').val() == "Get the code")
			 {
			 var cnpemailid = jQuery('#clickandpledge-cnpshp_emailid').val();
			
			 if(jQuery('#clickandpledge-cnpshp_emailid').val() != "" && validateEmail(cnpemailid))
			 {
				
			
				 jQuery.ajax({
				  type: "POST", 
				  url: ajaxurl,
				  dataType: 'json',
				  data: {
						'action':'cnp_wpshpgetconnectcode',
						'cnpemailid' : cnpemailid
					  },
					
					cache: false,
					beforeSend: function(xhr) {
					jQuery('.cnploaderimage').show();
					jQuery(".cnperror").hide();
					},
					complete: function() {
					jQuery('.cnploaderimage').hide();
						
					},	
				  success: function(htmlText){  
				  var obj = jQuery.parseJSON(htmlText);
					  console.log(htmlText);
				  if(htmlText == "Code has been sent successfully")
				  {
					  jQuery("#clickandpledge-cnpshp_code").show();
					  jQuery("label[for='clickandpledge-cnpshp_code']").css('visibility', 'visible');
					  jQuery("#clickandpledge-cnpshp_btncode").html("Login");
					  jQuery('#clickandpledge-cnpshp_btncode').val("Login") 
					  jQuery(".text-danger").html("");
					  jQuery(".text-success").html("");
					  jQuery(".text-success").html("Please enter the code sent to your email");
				  }
				  else if(htmlText == null) 
				  {
				   jQuery(".text-danger").html("Sorry but we cannot find the email in our system. Please try again.");
					  
				  }
				  /*else if(obj.Message !="") 
				  {
				   jQuery(".text-danger").html("Sorry but we cannot find the email in our system. Please try again.");
					  
				  }*/
					
				  },
				  error: function(xhr, ajaxOptions, thrownError) {
					
					 alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				  }
				});
			  }
			  else{
			  alert("Please enter valid connect user name");
			  jQuery('#clickandpledge-cnpshp_emailid').focus();
			  return false;
			  }
			 }
			 if(jQuery('#clickandpledge-cnpshp_btncode').val() == "Login")
			 {
			 	 var cnpemailid = jQuery('#clickandpledge-cnpshp_emailid').val().trim();
				  var cnpcode = jQuery('#clickandpledge-cnpshp_code').val().trim();
				 if(cnpemailid != "" && cnpcode != "")
				 {
				 jQuery.ajax({
				  type: "POST", 
				  url: ajaxurl ,
				  data: {
						'action':'cnp_WPSHOPPgetAccounts',
						'wpjbcnpemailid' : cnpemailid,
					  	'wpjbcnpcode' : cnpcode
					  },
				  cache: false,
				  beforeSend: function() {
					jQuery("#clickandpledge-cnpshp_btncode").html('Loading....');
					jQuery("#clickandpledge-cnpshp_btncode").prop('disabled', 'disabled');
					},
					complete: function() {
					
					},	
				  success: function(htmlText) {
				
				  if(htmlText.trim() != "error")
				  {
					    jQuery(".text-danger").html("");
					  jQuery(".text-success").html("");
				      jQuery('#clickandpledge-cnpshp_emailid').val("");
					  jQuery('#clickandpledge-cnpshp_code').val("");
  				  	 // jQuery(".cnpcode").hide();
					  jQuery("#clickandpledge-cnpshp_btncode").prop('disabled', '');
				      jQuery("#clickandpledge-cnpshp_btncode").html('Get the code');
					  jQuery("#clickandpledge-cnpshp_code").hide();
					  jQuery("label[for='clickandpledge-cnpshp_code']").css('visibility', 'hidden');
					  jQuery("#clickandpledge-cnpshp_emailid").hide();
					  jQuery("label[for='clickandpledge-cnpshp_emailid']").css('visibility', 'hidden');
					  jQuery("#clickandpledge-cnpshp_emailid").hide();
					  jQuery("#clickandpledge-cnpshp_btncode").hide();
					  jQuery(".cnpdtl").parent().closest('div').hide();
					  // jQuery("#cnpfrmwcregister").hide();
					  location.reload();
					 // jQuery("#cnpfrmwcsettings").show();
					 // jQuery('.form-table').show();
					 // jQuery('.ReceiptSettingsSection').show();
					 // jQuery('.RecurringSection').show();
        			 // jQuery('.button-primary').show();
					 
				  }
				  else
				  {
					  jQuery(".text-danger").html("");
					  jQuery(".text-success").html("");
					  jQuery(".cnpdtl").hide();
					  jQuery(".text-danger").html("Invalid Code");
					  jQuery("#clickandpledge-cnpshp_btncode").html('Login');
					  jQuery("#clickandpledge-cnpshp_btncode").prop('disabled', false);
				  }
					
				  },
				  error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				  }
				});
			  }
			 }
			 else if(jQuery('#clickandpledge-cnpshp_emailid').val() == "")
			 {
			  alert("Please enter connect user name");
			  return false;
			 }
		 
		
		 });
//}
	/* MY Code oAuth()*/

					
	jQuery(document).on("click",".edit",function() {
		var url = jQuery(this).attr('href');
		 var typ = getURLParameter(url, 'id');
		 //if (typ == 'ClickandPledge') {
			 window.location.href = url;
		/* } else {
			 jQuery('.clickandpledge-editing').css("display", "none");
			 //jQuery('#payment-setting-clickandpledge').css("display", "block");
		 }*/
		return false;		 
	});
	
if(jQuery("input[name=gateway]").val() == "ClickandPledge"){
   jQuery(".OrganizationInformationCNT").keyup(function() {
			var maxLimit = 1500;
			var tval = jQuery(this).val();
			var len = jQuery(this).val().length;
			var ch = maxLimit - len;
	        limitText(jQuery('.OrganizationInformationCNT'),jQuery('.OrganizationInformationCNT'),1500);
			if (ch <= 0) {
			
			//	jQuery(".OrganizationInformationCNT").val((tval).substring(0, len - 1));
				
				jQuery("label[for='clickandpledge-organizationinformationid']").text('Organization Information<br>(Maximum: 1500 characters) you have reached the limit');
			}
			else if (ch <= maxLimit) {
				jQuery("label[for='clickandpledge-organizationinformationid']").text('Organization Information<br>(Maximum: 1500 characters) '+ ch + ' characters left');
			}
		});
		 jQuery(".OrganizationInformationCNT").keydown(function() {
			var maxLimit = 1500;
			var tval = jQuery(this).val();
			var len = jQuery(this).val().length;
			var ch = maxLimit - len;
	        limitText(jQuery('.OrganizationInformationCNT'),jQuery('.OrganizationInformationCNT'),1500);
			if (ch <= 0) {
			
			//	jQuery(".OrganizationInformationCNT").val((tval).substring(0, len - 1));
				
				jQuery("label[for='clickandpledge-organizationinformationid']").text('Organization Information<br>(Maximum: 1500 characters) you have reached the limit');
			}
			else if (ch <= maxLimit) {
				jQuery("label[for='clickandpledge-organizationinformationid']").text('Organization Information<br>(Maximum: 1500 characters) '+ ch + ' characters left');
			}
		});
		jQuery(".TermsConditionCNT").keyup(function() {
			var maxLimit = 1500;
			var tval = jQuery(".TermsConditionCNT").val();
			var len = jQuery(".TermsConditionCNT").val().length;
			var ch = maxLimit - len;
			  limitText(jQuery('.TermsConditionCNT'),jQuery('.TermsConditionCNT'),1500);
			if (ch <= 0) {
			
				//jQuery(".TermsConditionCNT").val((tval).substring(0, len - 1));
				jQuery("label[for='clickandpledge-termsconditionid']").text('Terms Conditions<br>(Maximum: 1500 characters)  you have reached the limit');
			}
			else if (ch <= maxLimit) {
				jQuery("label[for='clickandpledge-termsconditionid']").text('Terms Conditions<br>(Maximum: 1500 characters) '+ ch + ' characters left');
			}
		});
		
		jQuery(".TermsConditionCNT").keydown(function() {
			var maxLimit = 1500;
			var tval = jQuery(".TermsConditionCNT").val();
			var len = jQuery(".TermsConditionCNT").val().length;
			var ch = maxLimit - len;
			  limitText(jQuery('.TermsConditionCNT'),jQuery('.TermsConditionCNT'),1500);
			if (ch <= 0) {
			
				//jQuery(".TermsConditionCNT").val((tval).substring(0, len - 1));
				jQuery("label[for='clickandpledge-termsconditionid']").text('Terms Conditions<br>(Maximum: 1500 characters)  you have reached the limit');
			}
			else if (ch <= maxLimit) {
				jQuery("label[for='clickandpledge-termsconditionid']").text('Terms Conditions<br>(Maximum: 1500 characters) '+ ch + ' characters left');
			}
		});
		/*		onclick eCheck fill Default Payment Methods END		*/
		
		/*		onclick CustomPayment fill Default Payment Methods START		*/
		jQuery("#clickandpledge-custompayment").click(function(){															
			var creditcard = jQuery(".CCclass").filter(':checked').length;
			var eCheck = jQuery(".eCheckclass").filter(':checked').length;
			var CustomPayment = jQuery(".CustomPaymentclass").filter(':checked').length;
			var defval_selected = "";
		
			if(CustomPayment == 1)
			{
				jQuery(".CustomPaymentTitleclass").show();
				jQuery('label[for="clickandpledge-custom_payment_title"]').css('visibility', 'visible');
				jQuery(".CustomPaymentTitleclass").parent('div').show();
			}
			else
			{
				jQuery(".CustomPaymentTitleclass").hide();
				jQuery('label[for="clickandpledge-custom_payment_title"]').css('visibility', 'hidden');
				jQuery(".CustomPaymentTitleclass").parent('div').hide();
			}
			jQuery('#clickandpledge-defaultpaymentmethod').html('');
		/*	jQuery('<option>').val('Please Select').text('Please Select').appendTo('#clickandpledge-defaultpaymentmethod');*/
			var defaultpaymentmethodHidden = jQuery("#clickandpledge-defaultpaymentmethod_hidden").val();
			var defval_selected = "";

			if(creditcard == 1 && eCheck == 0 && CustomPayment == 0)
			{//alert(1);
				if(defaultpaymentmethodHidden == 'Credit Card')	defval_selected = "  selected";
				else											defval_selected = "";
						
				jQuery('<option >').val('Credit Card').text('Credit Card').appendTo('#clickandpledge-defaultpaymentmethod');
			}
			if(creditcard == 1 && eCheck == 1 && CustomPayment == 0)
			{//alert(2);
				if(defaultpaymentmethodHidden == 'Credit Card')	defval_selected = "  selected";
				else											defval_selected = "";						
				jQuery('<option >').val('Credit Card').text('Credit Card').appendTo('#clickandpledge-defaultpaymentmethod');
				
				if(defaultpaymentmethodHidden == 'eCheck')	defval_selected = "  selected";
				else										defval_selected = "";
				jQuery('<option >').val('eCheck').text('eCheck').appendTo('#clickandpledge-defaultpaymentmethod');
			}
			if(creditcard == 1 && eCheck == 0 && CustomPayment == 1)
			{//alert(3);		
				if(defaultpaymentmethodHidden == 'Credit Card')	defval_selected = "  selected";
				else											defval_selected = "";
						
				jQuery('<option >').val('Credit Card').text('Credit Card').appendTo('#clickandpledge-defaultpaymentmethod');
				var CustomPaymentTitles = jQuery(".CustomPaymentTitleclass").val();//alert(CustomPaymentTitles);
				var splt = "";
				if(CustomPaymentTitles != "")
				{
					splt = CustomPaymentTitles.split(';');
					for(var i=0;i < splt.length; i++)
					{		
						var spltVal = splt[i].trim();
						if( spltVal !="" ){
							if(defaultpaymentmethodHidden == spltVal)	defval_selected = "  selected";
							else										defval_selected = "";
							
							jQuery('<option >').val(splt[i]).text(splt[i]).appendTo('#clickandpledge-defaultpaymentmethod');
						}
					}
				}
			}
			if(creditcard == 1 && eCheck == 1 && CustomPayment == 1)
			{//alert(4);
				if(defaultpaymentmethodHidden == 'Credit Card')	defval_selected = "  selected";
				else											defval_selected = "";
						
				jQuery('<option >').val('Credit Card').text('Credit Card').appendTo('#clickandpledge-defaultpaymentmethod');
				
				if(defaultpaymentmethodHidden == 'eCheck')	defval_selected = "  selected";
				else										defval_selected = "";
				jQuery('<option >').val('eCheck').text('eCheck').appendTo('#clickandpledge-defaultpaymentmethod');
				var CustomPaymentTitles = jQuery(".CustomPaymentTitleclass").val();//alert(CustomPaymentTitles);
				var splt = "";
				if(CustomPaymentTitles != "")
				{
					splt = CustomPaymentTitles.split(';');
					for(var i=0;i < splt.length; i++)
					{
						var spltVal = splt[i].trim();
						if( spltVal !="" ){
							if(defaultpaymentmethodHidden == spltVal)	defval_selected = "  selected";
							else										defval_selected = "";
							
							jQuery('<option >').val(splt[i]).text(splt[i]).appendTo('#clickandpledge-defaultpaymentmethod');
						}
					}
				}
			}
			if(creditcard == 0 && eCheck == 1 && CustomPayment == 1)
			{//alert(5);
				if(defaultpaymentmethodHidden == 'eCheck')	defval_selected = "  selected";
				else										defval_selected = "";
				jQuery('<option >').val('eCheck').text('eCheck').appendTo('#clickandpledge-defaultpaymentmethod');
				var CustomPaymentTitles = jQuery(".CustomPaymentTitleclass").val();//alert(CustomPaymentTitles);
				var splt = "";
				if(CustomPaymentTitles != "")
				{
					splt = CustomPaymentTitles.split(';');
					for(var i=0;i < splt.length; i++)
					{
						var spltVal = splt[i].trim();
						if( spltVal !="" ){
							if(defaultpaymentmethodHidden == spltVal)	defval_selected = "  selected";
							else										defval_selected = "";
							
							jQuery('<option >').val(splt[i]).text(splt[i]).appendTo('#clickandpledge-defaultpaymentmethod');
						}
					}
				}
			}
			if(creditcard == 0 && eCheck == 1 && CustomPayment == 0)
			{//alert(6);
				if(defaultpaymentmethodHidden == 'eCheck')	defval_selected = "  selected";
				else										defval_selected = "";
				jQuery('<option >').val('eCheck').text('eCheck').appendTo('#clickandpledge-defaultpaymentmethod');
			}
			if(creditcard == 0 && eCheck == 0 && CustomPayment == 1)
			{//alert(7);
				var CustomPaymentTitles = jQuery(".CustomPaymentTitleclass").val();//alert(CustomPaymentTitles);
				var splt = "";
				if(CustomPaymentTitles != "")
				{
					splt = CustomPaymentTitles.split(';');
					for(var i=0;i < splt.length; i++)
					{
						var spltVal = splt[i].trim();
						if( spltVal !="" ){
							if(defaultpaymentmethodHidden == spltVal)	defval_selected = "  selected";
							else										defval_selected = "";
							
							jQuery('<option >').val(splt[i]).text(splt[i]).appendTo('#clickandpledge-defaultpaymentmethod');
						}
					}
				}
			}
			/*if(creditcard == 0 && eCheck == 0 && CustomPayment == 0)
			{//alert(8);
				jQuery('<option>').val('Please Select').text('Please Select').appendTo('#clickandpledge-defaultpaymentmethod');
			}*/
		});
		/*		onclick CustomPayment fill Default Payment Methods END		*/
		
		/*		onchange CustomPaymentTextarea fill Default Payment Methods START		*/
		jQuery(".CustomPaymentTitleclass").on('change', function(){		
		
	jQuery('#clickandpledge-hdndfltpaymnt').val(jQuery("#clickandpledge-defaultpaymentmethod").val());					
		getdefaultpaymentmedhods();
	
		});
		/*		onchange CustomPaymentTextarea fill Default Payment Methods END		*/
		jQuery("#clickandpledge-defaultpaymentmethod").change(function(){

			var asgnVal = jQuery(this).val();
			jQuery("#clickandpledge-hdndfltpaymnt").val(asgnVal);

			/*var asgnVal2 = jQuery(this).val();
			jQuery("#clickandpledge-defaultpaymentmethod_hidden2").val(asgnVal2);*/
		
		});
		/*		onChange Default Payment Methods START		*/
		jQuery(".defaultpaymentmethodClass").change(function(){

			var asgnVal = jQuery(this).val();
			jQuery("#clickandpledge-defaultpaymentmethod_hidden").val(asgnVal);

			/*var asgnVal2 = jQuery(this).val();
			jQuery("#clickandpledge-defaultpaymentmethod_hidden2").val(asgnVal2);*/
		
		});
		/*		onclick CustomPayment fill Default Payment Methods END		*/
				
		jQuery(".payment_options_Class").change(function(){
														 
			var recurringSelected = jQuery("#clickandpledge-payment_options_recurring").is(':checked');
			
			var countCheckedCheckboxes = jQuery(".payment_options_Class").filter(':checked').length;
			 if (recurringSelected == false) {
				 cnprecurringfalse();
	
				
			 }
			 if (recurringSelected == true) {
				 cnprecurringtrue();
			 }
			 if (countCheckedCheckboxes <= 1) {
				jQuery("#clickandpledge-defaultpaymentoptions").css("display", "none");
				jQuery('label[for="clickandpledge-defaultpaymentoptions"]').css('visibility', 'hidden');
				jQuery("#clickandpledge-defaultpaymentoptions").parent('div').hide();

			 } if (countCheckedCheckboxes == 2) {
				jQuery("#clickandpledge-defaultpaymentoptions").css("display", "block");
				jQuery('label[for="clickandpledge-defaultpaymentoptions"]').css('visibility', 'visible');
				jQuery("#clickandpledge-defaultpaymentoptions").parent('div').show();
			 }
		   
		});
		
		jQuery("#clickandpledge-recurringtype_installment, #clickandpledge-recurringtype_subscription").click(function(){
		//alert('installment');			
			var payValPreselected = jQuery('#clickandpledge-noofpaymentsvalue').val();
			var setSelected = "";
			if((jQuery("#clickandpledge-recurringtype_installment").is(':checked') == true) && (jQuery("#clickandpledge-recurringtype_subscription").is(':checked') == true))
			{//alert(11);
				jQuery("#clickandpledge-defaultrecurringoptions").css("display", "block");
				jQuery('label[for="clickandpledge-defaultrecurringoptions"]').css('visibility', 'visible');
				jQuery("#clickandpledge-defaultrecurringoptions").parent('div').show();
				
				jQuery('#clickandpledge-noofpaymentsvalue').html('');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Please Select">Please Select</option>');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Indefinite Only">Indefinite Only</option>');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Open Field Only">Open Field Only</option>');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Indefinite + Open Field Option">Indefinite + Open Field Option</option>');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Fixed Number - No Change Allowed">Fixed Number - No Change Allowed</option>');
			}
			if((jQuery("#clickandpledge-recurringtype_installment").is(':checked') == false) && (jQuery("#clickandpledge-recurringtype_subscription").is(':checked') == false))
			{//alert(12);		
				jQuery('#clickandpledge-noofpaymentsvalue').html('');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Please Select">Please Select</option>');
				
				jQuery("#clickandpledge-defaultrecurringoptions").css("display", "none");
				jQuery('label[for="clickandpledge-defaultrecurringoptions"]').css('visibility', 'hidden');
				jQuery("#clickandpledge-defaultrecurringoptions").parent('div').hide();
			}
			if((jQuery("#clickandpledge-recurringtype_installment").is(':checked') == true) && (jQuery("#clickandpledge-recurringtype_subscription").is(':checked') == false))
			{//alert(13);	
				jQuery("#clickandpledge-defaultrecurringoptions").css("display", "none");
				jQuery('label[for="clickandpledge-defaultrecurringoptions"]').css('visibility', 'hidden');
				jQuery("#clickandpledge-defaultrecurringoptions").parent('div').hide();
				
				jQuery('#clickandpledge-noofpaymentsvalue').html('');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Please Select">Please Select</option>');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Open Field Only">Open Field Only</option>');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Fixed Number - No Change Allowed">Fixed Number - No Change Allowed</option>');
			}
			if((jQuery("#clickandpledge-recurringtype_installment").is(':checked') == false) && (jQuery("#clickandpledge-recurringtype_subscription").is(':checked') == true))
			{//alert(14);
				jQuery("#clickandpledge-defaultrecurringoptions").css("display", "none");
				jQuery('label[for="clickandpledge-defaultrecurringoptions"]').css('visibility', 'hidden');
				jQuery("#clickandpledge-defaultrecurringoptions").parent('div').hide();
			
				jQuery('#clickandpledge-noofpaymentsvalue').html('');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Please Select">Please Select</option>');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Indefinite Only">Indefinite Only</option>');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Open Field Only">Open Field Only</option>');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Indefinite + Open Field Option">Indefinite + Open Field Option</option>');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Fixed Number - No Change Allowed">Fixed Number - No Change Allowed</option>');
			}
			
			jQuery("#clickandpledge-defnoofpaymentslbl").css("display", "none");
			jQuery('label[for="clickandpledge-defnoofpaymentslbl"]').css('visibility', 'hidden');
			jQuery("#clickandpledge-defnoofpaymentslbl").parent('div').hide();
			
			jQuery("#clickandpledge-defnoofpayments").css("display", "none");
			jQuery('label[for="clickandpledge-defnoofpayments"]').css('visibility', 'hidden');
			jQuery("#clickandpledge-defnoofpayments").parent('div').hide();
			
			jQuery("#clickandpledge-maxnoofinstallmentslbl").css("display", "none");
			jQuery('label[for="clickandpledge-maxnoofinstallmentslbl"]').css('visibility', 'hidden');
			jQuery("#clickandpledge-maxnoofinstallmentslbl").parent('div').hide();
			
			jQuery("#clickandpledge-maxnoofinstallments").css("display", "none");
			jQuery('label[for="clickandpledge-maxnoofinstallments"]').css('visibility', 'hidden');
			jQuery("#clickandpledge-maxnoofinstallments").parent('div').hide();
		});		
		
		jQuery("#clickandpledge-defaultrecurringoptions").change(function() {
			var defSelected = jQuery(this).val();
			
		
			if((jQuery("#clickandpledge-recurringtype_installment").is(':checked') == true) && (jQuery("#clickandpledge-recurringtype_subscription").is(':checked') == true))
			{			
				
				jQuery('#clickandpledge-noofpaymentsvalue').html('');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Please Select">Please Select</option>');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Indefinite Only">Indefinite Only</option>');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Open Field Only">Open Field Only</option>');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Indefinite + Open Field Option">Indefinite + Open Field Option</option>');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Fixed Number - No Change Allowed">Fixed Number - No Change Allowed</option>');
			}
			if((jQuery("#clickandpledge-recurringtype_installment").is(':checked') == true) && (jQuery("#clickandpledge-recurringtype_subscription").is(':checked') == false))
			{
				jQuery("#clickandpledge-defaultrecurringoptions").css("display", "none");
				jQuery('label[for="clickandpledge-defaultrecurringoptions"]').css('visibility', 'hidden');
				jQuery("#clickandpledge-defaultrecurringoptions").parent('div').hide();
				
				jQuery('#clickandpledge-noofpaymentsvalue').html('');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Please Select">Please Select</option>');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Open Field Only">Open Field Only</option>');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Fixed Number - No Change Allowed">Fixed Number - No Change Allowed</option>');
			}
			if((jQuery("#clickandpledge-recurringtype_installment").is(':checked') == false) && (jQuery("#clickandpledge-recurringtype_subscription").is(':checked') == true))
			{
				jQuery("#clickandpledge-defaultrecurringoptions").css("display", "none");
				jQuery('label[for="clickandpledge-defaultrecurringoptions"]').css('visibility', 'hidden');
				jQuery("#clickandpledge-defaultrecurringoptions").parent('div').hide();
			
				jQuery('#clickandpledge-noofpaymentsvalue').html('');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Please Select">Please Select</option>');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Indefinite Only">Indefinite Only</option>');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Open Field Only">Open Field Only</option>');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Indefinite + Open Field Option">Indefinite + Open Field Option</option>');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Fixed Number - No Change Allowed">Fixed Number - No Change Allowed</option>');
			}
			jQuery("#clickandpledge-defnoofpaymentslbl").css("display", "none");
			jQuery('label[for="clickandpledge-defnoofpaymentslbl"]').css('visibility', 'hidden');
			jQuery("#clickandpledge-defnoofpaymentslbl").parent('div').hide();
			
			jQuery("#clickandpledge-defnoofpayments").css("display", "none");
			jQuery('label[for="clickandpledge-defnoofpayments"]').css('visibility', 'hidden');
			jQuery("#clickandpledge-defnoofpayments").parent('div').hide();
			
			jQuery("#clickandpledge-maxnoofinstallmentslbl").css("display", "none");
			jQuery('label[for="clickandpledge-maxnoofinstallmentslbl"]').css('visibility', 'hidden');
			jQuery("#clickandpledge-maxnoofinstallmentslbl").parent('div').hide();
			
			jQuery("#clickandpledge-maxnoofinstallments").css("display", "none");
			jQuery('label[for="clickandpledge-maxnoofinstallments"]').css('visibility', 'hidden');
			jQuery("#clickandpledge-maxnoofinstallments").parent('div').hide();
		});
		
		jQuery("#clickandpledge-noofpaymentsvalue").change(function(){														
																			
			var defRec = jQuery(".Chkrecurring").filter(':checked').length;
			//alert('change- '+defRec+' -- '+jQuery("#clickandpledge-defaultrecurringoptions").val());	
			
			var noofpay = jQuery(this).val();
			
			if(defRec == 2 && jQuery("#clickandpledge-defaultrecurringoptions").val() == "Installment")
			{
				if(noofpay == "Please Select")
				{
					jQuery("#clickandpledge-defnoofpaymentslbl").css("display", "none");
					jQuery('label[for="clickandpledge-defnoofpaymentslbl"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-defnoofpaymentslbl").parent('div').hide();
					
					jQuery("#clickandpledge-defnoofpayments").css("display", "none");
					jQuery('label[for="clickandpledge-defnoofpayments"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-defnoofpayments").parent('div').hide();
					
					jQuery("#clickandpledge-maxnoofinstallmentslbl").css("display", "none");
					jQuery('label[for="clickandpledge-maxnoofinstallmentslbl"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-maxnoofinstallmentslbl").parent('div').hide();
					
					jQuery("#clickandpledge-maxnoofinstallments").css("display", "none");
					jQuery('label[for="clickandpledge-maxnoofinstallments"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-maxnoofinstallments").parent('div').hide();
				}
				if(noofpay == "Indefinite Only")
				{
					jQuery("#clickandpledge-defnoofpaymentslbl").css("display", "none");
					jQuery('label[for="clickandpledge-defnoofpaymentslbl"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-defnoofpaymentslbl").parent('div').hide();
					
					jQuery("#clickandpledge-defnoofpayments").css("display", "none");
					jQuery('label[for="clickandpledge-defnoofpayments"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-defnoofpayments").parent('div').hide();
					
					jQuery("#clickandpledge-maxnoofinstallmentslbl").css("display", "none");
					jQuery('label[for="clickandpledge-maxnoofinstallmentslbl"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-maxnoofinstallmentslbl").parent('div').hide();
					
					jQuery("#clickandpledge-maxnoofinstallments").css("display", "none");
					jQuery('label[for="clickandpledge-maxnoofinstallments"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-maxnoofinstallments").parent('div').hide();
				}
				if(noofpay == "Indefinite + Open Field Option")
				{
					jQuery("#clickandpledge-defnoofpaymentslbl").css("display", "block");
					jQuery('label[for="clickandpledge-defnoofpaymentslbl"]').css('visibility', 'visible');
					jQuery("#clickandpledge-defnoofpaymentslbl").parent('div').show();
					
					jQuery("#clickandpledge-defnoofpayments").css("display", "block");
					jQuery("#clickandpledge-defnoofpayments").val("998");
					jQuery('label[for="clickandpledge-defnoofpayments"]').css('visibility', 'visible');
					jQuery("#clickandpledge-defnoofpayments").parent('div').show();
					
					jQuery("#clickandpledge-maxnoofinstallmentslbl").css("display", "block");
					jQuery('label[for="clickandpledge-maxnoofinstallmentslbl"]').css('visibility', 'visible');
					jQuery("#clickandpledge-maxnoofinstallmentslbl").parent('div').show();
					
					jQuery("#clickandpledge-maxnoofinstallments").css("display", "block");
					jQuery('label[for="clickandpledge-maxnoofinstallments"]').css('visibility', 'visible');
					jQuery("#clickandpledge-maxnoofinstallments").val("998");
					jQuery("#clickandpledge-maxnoofinstallments").attr('readonly',true);
					jQuery("#clickandpledge-maxnoofinstallments").parent('div').show();
				}
				if(noofpay == "Open Field Only")
				{
					jQuery("#clickandpledge-defnoofpaymentslbl").css("display", "block");
					jQuery('label[for="clickandpledge-defnoofpaymentslbl"]').css('visibility', 'visible');
					jQuery("#clickandpledge-defnoofpaymentslbl").parent('div').show();
					
					jQuery("#clickandpledge-defnoofpayments").css("display", "block");
					jQuery('label[for="clickandpledge-defnoofpayments"]').css('visibility', 'visible');
					jQuery("#clickandpledge-defnoofpayments").val("");
					jQuery("#clickandpledge-defnoofpayments").parent('div').show();
					
					jQuery("#clickandpledge-maxnoofinstallmentslbl").css("display", "block");
					jQuery('label[for="clickandpledge-maxnoofinstallmentslbl"]').css('visibility', 'visible');
					jQuery("#clickandpledge-maxnoofinstallmentslbl").parent('div').show();
					
					jQuery("#clickandpledge-maxnoofinstallments").css("display", "block");
					jQuery('label[for="clickandpledge-maxnoofinstallments"]').css('visibility', 'visible');
					jQuery("#clickandpledge-maxnoofinstallments").attr('readonly',false);
					jQuery("#clickandpledge-maxnoofinstallments").val("");
					jQuery("#clickandpledge-maxnoofinstallments").parent('div').show();
				}
				if(noofpay == "Fixed Number - No Change Allowed")
				{
					jQuery("#clickandpledge-defnoofpaymentslbl").css("display", "block");
					jQuery('label[for="clickandpledge-defnoofpaymentslbl"]').css('visibility', 'visible');
					jQuery("#clickandpledge-defnoofpaymentslbl").parent('div').show();
					
					jQuery("#clickandpledge-defnoofpayments").val("");					
					jQuery("#clickandpledge-defnoofpayments").css("display", "block");
					jQuery('label[for="clickandpledge-defnoofpayments"]').css('visibility', 'visible');
					jQuery("#clickandpledge-defnoofpayments").parent('div').show();
					
					jQuery("#clickandpledge-maxnoofinstallmentslbl").css("display", "none");
					jQuery('label[for="clickandpledge-maxnoofinstallmentslbl"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-maxnoofinstallmentslbl").parent('div').hide();
					
					jQuery("#clickandpledge-maxnoofinstallments").css("display", "none");
					jQuery('label[for="clickandpledge-maxnoofinstallments"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-maxnoofinstallments").parent('div').hide();
				}
			}
			if(defRec == 2 && jQuery("#clickandpledge-defaultrecurringoptions").val() == "Subscription")
			{
				if(noofpay == "Please Select")
				{
					jQuery("#clickandpledge-defnoofpaymentslbl").css("display", "none");
					jQuery('label[for="clickandpledge-defnoofpaymentslbl"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-defnoofpaymentslbl").parent('div').hide();
					
					jQuery("#clickandpledge-defnoofpayments").css("display", "none");
					jQuery('label[for="clickandpledge-defnoofpayments"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-defnoofpayments").parent('div').hide();
					
					jQuery("#clickandpledge-maxnoofinstallmentslbl").css("display", "none");
					jQuery('label[for="clickandpledge-maxnoofinstallmentslbl"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-maxnoofinstallmentslbl").parent('div').hide();
					
					jQuery("#clickandpledge-maxnoofinstallments").css("display", "none");
					jQuery('label[for="clickandpledge-maxnoofinstallments"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-maxnoofinstallments").parent('div').hide();
				}
				if(noofpay == "Indefinite Only")
				{
					jQuery("#clickandpledge-defnoofpaymentslbl").css("display", "none");
					jQuery('label[for="clickandpledge-defnoofpaymentslbl"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-defnoofpaymentslbl").parent('div').hide();
					
					jQuery("#clickandpledge-defnoofpayments").css("display", "none");
					jQuery('label[for="clickandpledge-defnoofpayments"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-defnoofpayments").parent('div').hide();
					
					jQuery("#clickandpledge-maxnoofinstallmentslbl").css("display", "none");
					jQuery('label[for="clickandpledge-maxnoofinstallmentslbl"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-maxnoofinstallmentslbl").parent('div').hide();
					
					jQuery("#clickandpledge-maxnoofinstallments").css("display", "none");
					jQuery('label[for="clickandpledge-maxnoofinstallments"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-maxnoofinstallments").parent('div').hide();
				}
				if(noofpay == "Open Field Only")
				{
					jQuery("#clickandpledge-defnoofpaymentslbl").css("display", "block");
					jQuery('label[for="clickandpledge-defnoofpaymentslbl"]').css('visibility', 'visible');
					jQuery("#clickandpledge-defnoofpaymentslbl").parent('div').show();
					
					jQuery("#clickandpledge-defnoofpayments").css("display", "block");
					jQuery('label[for="clickandpledge-defnoofpayments"]').css('visibility', 'visible');
					jQuery("#clickandpledge-defnoofpayments").val("");
					jQuery("#clickandpledge-defnoofpayments").parent('div').show();
					
					jQuery("#clickandpledge-maxnoofinstallmentslbl").css("display", "block");
					jQuery('label[for="clickandpledge-maxnoofinstallmentslbl"]').css('visibility', 'visible');
					jQuery("#clickandpledge-maxnoofinstallmentslbl").parent('div').show();
					
					jQuery("#clickandpledge-maxnoofinstallments").css("display", "block");
					jQuery('label[for="clickandpledge-maxnoofinstallments"]').css('visibility', 'visible');
					jQuery("#clickandpledge-maxnoofinstallments").attr('readonly',false);
					jQuery("#clickandpledge-maxnoofinstallments").val("");
					jQuery("#clickandpledge-maxnoofinstallments").parent('div').show();
				}
				if(noofpay == "Indefinite + Open Field Option")
				{
					jQuery("#clickandpledge-defnoofpaymentslbl").css("display", "block");
					jQuery('label[for="clickandpledge-defnoofpaymentslbl"]').css('visibility', 'visible');
					jQuery("#clickandpledge-defnoofpaymentslbl").parent('div').show();
					
					jQuery("#clickandpledge-defnoofpayments").css("display", "block");
					jQuery("#clickandpledge-defnoofpayments").val("999");
					jQuery('label[for="clickandpledge-defnoofpayments"]').css('visibility', 'visible');
					jQuery("#clickandpledge-defnoofpayments").parent('div').show();
					
					jQuery("#clickandpledge-maxnoofinstallmentslbl").css("display", "block");
					jQuery('label[for="clickandpledge-maxnoofinstallmentslbl"]').css('visibility', 'visible');
					jQuery("#clickandpledge-maxnoofinstallmentslbl").parent('div').show();
					
					jQuery("#clickandpledge-maxnoofinstallments").css("display", "block");
					jQuery('label[for="clickandpledge-maxnoofinstallments"]').css('visibility', 'visible');
					jQuery("#clickandpledge-maxnoofinstallments").val("999");
					jQuery("#clickandpledge-maxnoofinstallments").attr('readonly',true);
					jQuery("#clickandpledge-maxnoofinstallments").parent('div').show();
				}
				if(noofpay == "Fixed Number - No Change Allowed")
				{
					jQuery("#clickandpledge-defnoofpaymentslbl").css("display", "block");
					jQuery('label[for="clickandpledge-defnoofpaymentslbl"]').css('visibility', 'visible');
					jQuery("#clickandpledge-defnoofpaymentslbl").parent('div').show();
					
					jQuery("#clickandpledge-defnoofpayments").css("display", "block");
					jQuery('label[for="clickandpledge-defnoofpayments"]').css('visibility', 'visible');
					jQuery("#clickandpledge-defnoofpayments").val("");
					jQuery("#clickandpledge-defnoofpayments").parent('div').show();
					
					jQuery("#clickandpledge-maxnoofinstallmentslbl").css("display", "none");
					jQuery('label[for="clickandpledge-maxnoofinstallmentslbl"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-maxnoofinstallmentslbl").parent('div').hide();
					
					jQuery("#clickandpledge-maxnoofinstallments").css("display", "none");
					jQuery('label[for="clickandpledge-maxnoofinstallments"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-maxnoofinstallments").parent('div').hide();
				}	
			}
			if(defRec == 1)
			{
				if(noofpay == "Please Select")
				{
					jQuery("#clickandpledge-defnoofpaymentslbl").css("display", "none");
					jQuery('label[for="clickandpledge-defnoofpaymentslbl"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-defnoofpaymentslbl").parent('div').hide();
					
					jQuery("#clickandpledge-defnoofpayments").css("display", "none");
					jQuery('label[for="clickandpledge-defnoofpayments"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-defnoofpayments").parent('div').hide();
					
					jQuery("#clickandpledge-maxnoofinstallmentslbl").css("display", "none");
					jQuery('label[for="clickandpledge-maxnoofinstallmentslbl"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-maxnoofinstallmentslbl").parent('div').hide();
					
					jQuery("#clickandpledge-maxnoofinstallments").css("display", "none");
					jQuery('label[for="clickandpledge-maxnoofinstallments"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-maxnoofinstallments").parent('div').hide();
				}
				if(noofpay == "Indefinite Only")
				{
					jQuery("#clickandpledge-defnoofpaymentslbl").css("display", "none");
					jQuery('label[for="clickandpledge-defnoofpaymentslbl"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-defnoofpaymentslbl").parent('div').hide();
					
					jQuery("#clickandpledge-defnoofpayments").css("display", "none");
					jQuery('label[for="clickandpledge-defnoofpayments"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-defnoofpayments").parent('div').hide();
					
					jQuery("#clickandpledge-maxnoofinstallmentslbl").css("display", "none");
					jQuery('label[for="clickandpledge-maxnoofinstallmentslbl"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-maxnoofinstallmentslbl").parent('div').hide();
					
					jQuery("#clickandpledge-maxnoofinstallments").css("display", "none");
					jQuery('label[for="clickandpledge-maxnoofinstallments"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-maxnoofinstallments").parent('div').hide();
				}
				if(noofpay == "Open Field Only")
				{
					jQuery("#clickandpledge-defnoofpaymentslbl").css("display", "block");
					jQuery('label[for="clickandpledge-defnoofpaymentslbl"]').css('visibility', 'visible');
					jQuery("#clickandpledge-defnoofpaymentslbl").parent('div').show();
					
					jQuery("#clickandpledge-defnoofpayments").css("display", "block");
					jQuery('label[for="clickandpledge-defnoofpayments"]').css('visibility', 'visible');
					jQuery("#clickandpledge-defnoofpayments").val("");
					jQuery("#clickandpledge-defnoofpayments").parent('div').show();
					
					jQuery("#clickandpledge-maxnoofinstallmentslbl").css("display", "block");
					jQuery('label[for="clickandpledge-maxnoofinstallmentslbl"]').css('visibility', 'visible');
					jQuery("#clickandpledge-maxnoofinstallmentslbl").parent('div').show();
					
					jQuery("#clickandpledge-maxnoofinstallments").css("display", "block");
					jQuery('label[for="clickandpledge-maxnoofinstallments"]').css('visibility', 'visible');
					jQuery("#clickandpledge-maxnoofinstallments").attr('readonly',false);
					jQuery("#clickandpledge-maxnoofinstallments").val("");
					jQuery("#clickandpledge-maxnoofinstallments").parent('div').show();
				}
				if(noofpay == "Indefinite + Open Field Option")
				{
					jQuery("#clickandpledge-defnoofpaymentslbl").css("display", "block");
					jQuery('label[for="clickandpledge-defnoofpaymentslbl"]').css('visibility', 'visible');
					jQuery("#clickandpledge-defnoofpaymentslbl").parent('div').show();
					
					jQuery("#clickandpledge-defnoofpayments").css("display", "block");
					jQuery("#clickandpledge-defnoofpayments").val("999");
					jQuery('label[for="clickandpledge-defnoofpayments"]').css('visibility', 'visible');
					jQuery("#clickandpledge-defnoofpayments").parent('div').show();
					
					jQuery("#clickandpledge-maxnoofinstallmentslbl").css("display", "block");
					jQuery('label[for="clickandpledge-maxnoofinstallmentslbl"]').css('visibility', 'visible');
					jQuery("#clickandpledge-maxnoofinstallmentslbl").parent('div').show();
					
					jQuery("#clickandpledge-maxnoofinstallments").css("display", "block");
					jQuery('label[for="clickandpledge-maxnoofinstallments"]').css('visibility', 'visible');
					jQuery("#clickandpledge-maxnoofinstallments").val("999");
					jQuery("#clickandpledge-maxnoofinstallments").attr('readonly',true);
					jQuery("#clickandpledge-maxnoofinstallments").parent('div').show();
				}
				if(noofpay == "Fixed Number - No Change Allowed")
				{
					jQuery("#clickandpledge-defnoofpaymentslbl").css("display", "block");
					jQuery('label[for="clickandpledge-defnoofpaymentslbl"]').css('visibility', 'visible');
					jQuery("#clickandpledge-defnoofpaymentslbl").parent('div').show();
					
					jQuery("#clickandpledge-defnoofpayments").css("display", "block");
					jQuery('label[for="clickandpledge-defnoofpayments"]').css('visibility', 'visible');
					jQuery("#clickandpledge-defnoofpayments").val("");
					jQuery("#clickandpledge-defnoofpayments").parent('div').show();
					
					jQuery("#clickandpledge-maxnoofinstallmentslbl").css("display", "none");
					jQuery('label[for="clickandpledge-maxnoofinstallmentslbl"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-maxnoofinstallmentslbl").parent('div').hide();
					
					jQuery("#clickandpledge-maxnoofinstallments").css("display", "none");
					jQuery('label[for="clickandpledge-maxnoofinstallments"]').css('visibility', 'hidden');
					jQuery("#clickandpledge-maxnoofinstallments").parent('div').hide();
				}	
			
			}
		});
		
		
		/*		START FORM submit		*/
		jQuery(".button-primary").click(function() {
												 
			if(jQuery("#clickandpledge-defaultpaymentmethod").val() == "Please Select" )
			{
				alert("Please Select Default Payment Method");
				jQuery("#clickandpledge-defaultpaymentmethod").focus();
				return false;									 
			}
			/*		Payment Method Mandatory START		*/
			var creditcard = jQuery(".CCclass").filter(':checked').length;
			var eCheck = jQuery(".eCheckclass").filter(':checked').length;
			var CustomPayment = jQuery(".CustomPaymentclass").filter(':checked').length;
		
			if(CustomPayment == 1)
			{
				CustomPaymentVal = jQuery(".CustomPaymentTitleclass").val();
				if(jQuery(".CustomPaymentTitleclass").val() == "" || (CustomPaymentVal.trim() == "")) 
				{
					alert('Please enter atleast one Custom Payment Title');
					jQuery(".CustomPaymentTitleclass").val(CustomPaymentVal.trim());
					jQuery(".CustomPaymentTitleclass").focus();
					return false;
				}
			}
			
			if (creditcard == 0 && eCheck == 0 && CustomPayment == 0) {
				alert('Please select atleast one Payment Method');
				jQuery("#clickandpledge-creditcard").focus();
				return false;
			}
			else
			{
				getdefaultpaymentmedhods();
			
			}
			/*		Payment Method Mandatory END		*/
			
			if (jQuery(".payment_options_Class").filter(':checked').length == 0) {
				alert('Please select Payment Options');
				return false;
			}
			
var recurringSelectedn = jQuery("#clickandpledge-payment_options_recurring").is(':checked');
			if(recurringSelectedn == true){
			if(recurringSelectedn == true && (jQuery("#clickandpledge-recurringtype_subscription").is(':checked') == false) && (jQuery("#clickandpledge-recurringtype_installment").is(':checked') == false))
			{
				alert('Please select at least one recurring type');
				jQuery("#clickandpledge-recurringtype_installment").focus();
				return false;
			}
			
			if (jQuery(".periodicity").filter(':checked').length == 0) {
				alert('Please select at least one Periodicity');
				jQuery("#clickandpledge-week").focus();
				return false;
			}
			
			var noofpay = jQuery("#clickandpledge-noofpaymentsvalue").val();
			var defRec = jQuery(".Chkrecurring").filter(':checked').length;

				 if (jQuery("#clickandpledge-defnoofpayments").val() !="" && /^\d*$/.test(jQuery("#clickandpledge-defnoofpayments").val()) == false)	
					 {
						alert("Enter valid Default Number of Payments");
					jQuery("#clickandpledge-defnoofpayments").focus();
					return false; 
					 }
				 if (jQuery("#clickandpledge-maxnoofinstallments").val() !="" && /^\d*$/.test(jQuery("#clickandpledge-maxnoofinstallments").val()) == false)	
					 {
						alert("Enter valid maximum number of installments allowed");
					jQuery("#clickandpledge-maxnoofinstallments").focus();
					return false; 
					 }
			if(noofpay == "Please Select")
			{
				alert("Please Select Number of Payments");
				jQuery("#clickandpledge-noofpaymentsvalue").focus();
				return false;
			}
			/*if(defRec == 2 && noofpay == "Indefinite Only")
			{
				if(jQuery("#clickandpledge-defaultrecurringoptions").val() == "Installment")
				{
					alert("Indefinite Recurring is not supported for Installment");
					jQuery("#clickandpledge-noofpaymentsvalue").focus();
					return false;
				}
			}
			if(defRec == 1 && noofpay == "Indefinite Only" && (jQuery("#clickandpledge-recurringtype_subscription").is(':checked') == false) && (jQuery("#clickandpledge-recurringtype_installment").is(':checked') == true))
			{
				alert("Indefinite Recurring is not supported for Installment");
				jQuery("#clickandpledge-noofpaymentsvalue").focus();
				return false;
			}*/
			
			if(noofpay == "Open Field Only")
			{
				var defnoofpayments = (jQuery("#clickandpledge-defnoofpayments").val()) ;
				
				var maxnoofinstallments = (jQuery("#clickandpledge-maxnoofinstallments").val());
		
				if((defnoofpayments !== "") )
				{					
					if(isInteger(jQuery("#clickandpledge-defnoofpayments").val()) == false)
					{
						alert("Only Numerics allowed for Default Number of Payments");
						jQuery("#clickandpledge-defnoofpayments").focus();
						return false;
					}
					
					if(defnoofpayments <= 1)
					{
						alert("Default Number of Payments should be greater than 1");
						jQuery("#clickandpledge-defnoofpayments").focus();
						return false;
					}
					
					else if(jQuery("#clickandpledge-recurringtype_installment").is(':checked') == true && jQuery("#clickandpledge-recurringtype_subscription").is(':checked') == false && defnoofpayments > 998)
					{
						alert("Please enter value between 2 to 998");
						jQuery("#clickandpledge-defnoofpayments").focus();
						return false;
					}

					else if(jQuery("#clickandpledge-recurringtype_installment").is(':checked') == true && jQuery("#clickandpledge-recurringtype_subscription").is(':checked') == true && jQuery("#clickandpledge-defaultrecurringoptions").val() == "Subscription" && defnoofpayments > 999)
					{
						alert("Please enter value between 2 to 999");
						jQuery("#clickandpledge-defnoofpayments").focus();
						return false;
					}

					else if(jQuery("#clickandpledge-recurringtype_installment").is(':checked') == true && jQuery("#clickandpledge-recurringtype_subscription").is(':checked') == true && jQuery("#clickandpledge-defaultrecurringoptions").val() == "Installment" && defnoofpayments > 998)
					{
						alert("Please enter value between 2 to 998");
						jQuery("#clickandpledge-defnoofpayments").focus();
						return false;
					}
				}
				
			
				if(maxnoofinstallments != "")
				{
					var defnoofpayments =parseInt(jQuery("#clickandpledge-defnoofpayments").val()) ;
				
				var maxnoofinstallments = parseInt(jQuery("#clickandpledge-maxnoofinstallments").val());
					if(jQuery("#clickandpledge-maxnoofinstallments").val() !="" && isInteger(jQuery("#clickandpledge-maxnoofinstallments").val()) == false)
					{
						alert("Only Numerics allowed for Maximum Number of installments");
						jQuery("#clickandpledge-maxnoofinstallments").focus();
						return false;
					}
					if(maxnoofinstallments <= 1)
					{
						alert("Maximum Number of installments should be greater than 1");
						jQuery("#clickandpledge-maxnoofinstallments").focus();
						return false;
					}
					if(maxnoofinstallments < defnoofpayments)
					{
						alert("Maximum number of installments allowed to be greater than or equal to default number of payments");
						jQuery("#clickandpledge-maxnoofinstallments").focus();
						return false;
					}
					/*if(maxnoofinstallments > 999)
					{
						alert("Maximum Number of installments should not be greater than 999");
						jQuery("#clickandpledge-maxnoofinstallments").focus();
						return false;
					}*/
					
					else if(jQuery("#clickandpledge-recurringtype_installment").is(':checked') == true && jQuery("#clickandpledge-recurringtype_subscription").is(':checked') == false && maxnoofinstallments > 998)
					{
						alert("Please enter value between 2 to 998");
						jQuery("#clickandpledge-maxnoofinstallments").focus();
						return false;
					}

					else if(jQuery("#clickandpledge-recurringtype_installment").is(':checked') == true && jQuery("#clickandpledge-recurringtype_subscription").is(':checked') == true && jQuery("#clickandpledge-defaultrecurringoptions").val() == "Installment" && maxnoofinstallments > 998)
					{
						alert("Please enter value between 2 to 998");
						jQuery("#clickandpledge-maxnoofinstallments").focus();
						return false;
					}

					else if(jQuery("#clickandpledge-recurringtype_installment").is(':checked') == true && jQuery("#clickandpledge-recurringtype_subscription").is(':checked') == true && jQuery("#clickandpledge-defaultrecurringoptions").val() == "Subscription" && maxnoofinstallments > 999)
					{
						alert("Please enter value between 2 to 999");
						jQuery("#clickandpledge-maxnoofinstallments").focus();
						return false;
					}
				}
			}
			}			
			
			if(noofpay == "Indefinite + Open Field Option")
			{
				var defnoofpayments = parseInt(jQuery("#clickandpledge-defnoofpayments").val()) || 0;
				var maxnoofinstallments = parseInt(jQuery("#clickandpledge-maxnoofinstallments").val()) || 0;
				
				/*if(defRec == 2 && noofpay == "Indefinite + Open Field Option")
				{
					if(jQuery("#clickandpledge-defaultrecurringoptions").val() == "Installment")
					{
						alert("'Indefinite + Open Field Option' Recurring is not supported for Installment");
						jQuery("#clickandpledge-noofpaymentsvalue").focus();
						return false;
					}
				}
				if(defRec == 1 && noofpay == "Indefinite + Open Field Option" && (jQuery("#clickandpledge-recurringtype_subscription").is(':checked') == false) && (jQuery("#clickandpledge-recurringtype_installment").is(':checked') == true))
				{
					alert("'Indefinite + Open Field Option' Recurring is not supported for Installment");
					jQuery("#clickandpledge-noofpaymentsvalue").focus();
					return false;
				}*/
			
				if( defnoofpayments == "" )
				{
					alert("Please fill Default Number of payments");
					jQuery("#clickandpledge-defnoofpayments").focus();
					return false;
				}
				if(defnoofpayments != "")
				{
					if(defnoofpayments <= 1)
					{
						alert("Default Number of payments should be greater than 1");
						jQuery("#clickandpledge-defnoofpayments").focus();
						return false;
					}
					if(defnoofpayments > 999)
					{
						alert("Please enter value between 2 to 999");
						jQuery("#clickandpledge-defnoofpayments").focus();
						return false;
					}
					if(isInteger(jQuery("#clickandpledge-defnoofpayments").val()) == false)
					{
						alert("Only Numerics allowed for Default Number of Payments");
						jQuery("#clickandpledge-defnoofpayments").focus();
						return false;
					}
				}
				
				if(maxnoofinstallments != "")
				{
					if(maxnoofinstallments <= 1)
					{
						alert("Maximum Number of installments should be greater than 1");
						jQuery("#clickandpledge-defnoofpayments").focus();
						return false;
					}
					if(maxnoofinstallments > 999)
					{
						alert("Please enter value between 2 to 998");
						jQuery("#clickandpledge-maxnoofinstallments").focus();
						return false;
					}
					if(jQuery("#clickandpledge-recurringtype_installment").is(':checked') == true && jQuery("#clickandpledge-recurringtype_subscription").is(':checked') == false && defnoofpayments > 998)
					{
						alert("Please enter value between 2 to 998");
						jQuery("#clickandpledge-defnoofpayments").focus();
						return false;
					}

					if(jQuery("#clickandpledge-recurringtype_installment").is(':checked') == true && jQuery("#clickandpledge-recurringtype_subscription").is(':checked') == true && jQuery("#clickandpledge-defaultrecurringoptions").val() == "Installment" && defnoofpayments > 998)
					{
						alert("Please enter value between 2 to 998");
						jQuery("#clickandpledge-defnoofpayments").focus();
						return false;
					}
					if(maxnoofinstallments < defnoofpayments)
					{
						alert("Maximum number of installments allowed to be greater than or equal to default number of payments");
						jQuery("#clickandpledge-defnoofpayments").focus();
						return false;
					}
					/*if(defnoofpayments > maxnoofinstallments)
					{
						alert("Maximum Number of installments should not be greater than Default Number of Payments");
						jQuery("#clickandpledge-defnoofpayments").focus();
						return false;
					}*/
					
					if(isInteger(jQuery("#clickandpledge-maxnoofinstallments").val()) == false)
					{
						alert("Only Numerics allowed for Maximum Number of installments");
						jQuery("#clickandpledge-defnoofpayments").focus();
						return false;
					}
				}
			}
			if(noofpay =="Fixed Number - No Change Allowed")
			{
				var defnoofpayments = parseInt(jQuery("#clickandpledge-defnoofpayments").val()) || 0;
				
				var maxnoofinstallments = parseInt(jQuery("#clickandpledge-maxnoofinstallments").val()) || 0;
//alert(defnoofpayments+" - "+jQuery("#clickandpledge-recurringtype_installment").is(':checked') +" == "+jQuery("#clickandpledge-recurringtype_subscription").is(':checked')+" - "+jQuery("#clickandpledge-defaultrecurringoptions").val());return false;
				
				if( defnoofpayments == "" ||  defnoofpayments == 0)
				{
					alert("Please fill Default Number of payments");
					jQuery("#clickandpledge-defnoofpayments").focus();
					return false;
				}
				
				if(defnoofpayments != "")
				{
					if(defnoofpayments <= 1)
					{
						alert("Default Number of payments should be greater than 1");
						jQuery("#clickandpledge-defnoofpayments").focus();
						return false;
					}
					
					if(jQuery("#clickandpledge-recurringtype_installment").is(':checked') == true && jQuery("#clickandpledge-recurringtype_subscription").is(':checked') == false && defnoofpayments > 998)
					{
						alert("Please enter value between 2 to 998");
						jQuery("#clickandpledge-defnoofpayments").focus();
						return false;
					}

					if(jQuery("#clickandpledge-recurringtype_installment").is(':checked') == true && jQuery("#clickandpledge-recurringtype_subscription").is(':checked') == true && jQuery("#clickandpledge-defaultrecurringoptions").val() == "Installment" && defnoofpayments > 998)
					{
						alert("Please enter value between 2 to 998");
						jQuery("#clickandpledge-defnoofpayments").focus();
						return false;
					}

					if(jQuery("#clickandpledge-recurringtype_installment").is(':checked') == true && jQuery("#clickandpledge-recurringtype_subscription").is(':checked') == true && jQuery("#clickandpledge-defaultrecurringoptions").val() == "Subscription" && defnoofpayments > 999)
					{
						alert("Please enter value between 2 to 999");
						jQuery("#clickandpledge-defnoofpayments").focus();
						return false;
					}
					if(isInteger(jQuery("#clickandpledge-defnoofpayments").val()) == false)
					{
						alert("Only Numerics allowed for Default Number of Payments");
						jQuery("#clickandpledge-defnoofpayments").focus();
						return false;
					}
				}
			}
			
			/*var Selecteddefaultrecurringoptions = jQuery("#clickandpledge-defaultrecurringoptions").val();//alert(Selecteddefaultrecurringoptions);
			
			if(Selecteddefaultrecurringoptions == "Installment" && noofpay == "Indefinite Only")
			{
				//alert("Indefinite Recurring is not possible for Installment");
//				jQuery("#clickandpledge-defnoofpayments").focus();
//				return false;
				
				jQuery('#clickandpledge-noofpaymentsvalue').html('');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Open Field Only">Open Field Only</option>');
				jQuery("#clickandpledge-noofpaymentsvalue").append('<option value="Fixed Number - No Change Allowed">Fixed Number - No Change Allowed</option>');
			
				jQuery("#clickandpledge-defnoofpaymentslbl").css("display", "block");
				jQuery('label[for="clickandpledge-defnoofpaymentslbl"]').css('visibility', 'visible');
				
				jQuery("#clickandpledge-defnoofpayments").css("display", "block");
				jQuery('label[for="clickandpledge-defnoofpayments"]').css('visibility', 'visible');
				
				jQuery("#clickandpledge-maxnoofinstallmentslbl").css("display", "block");
				jQuery('label[for="clickandpledge-maxnoofinstallmentslbl"]').css('visibility', 'visible');
				
				jQuery("#clickandpledge-maxnoofinstallments").css("display", "block");
				jQuery('label[for="clickandpledge-maxnoofinstallments"]').css('visibility', 'visible');return false;
			}*/
		});
		/*		END FORM submit		*/	
	}
	function getdefaultpaymentmedhods()
	{
		
			var creditcard = jQuery(".CCclass").filter(':checked').length;
			var eCheck = jQuery(".eCheckclass").filter(':checked').length;
			var CustomPayment = jQuery(".CustomPaymentclass").filter(':checked').length;
			var defaultval = jQuery('#clickandpledge-hdndfltpaymnt').val(); 
			var paymethods = [];
			var paymethods_titles = [];
			var str = '';													   
			if(creditcard == 1) {
			paymethods.push('Credit Card');
			paymethods_titles.push('Credit Card');
		}
		if(eCheck == 1 && eCheck != undefined) {
			paymethods.push('eCheck');
			paymethods_titles.push('eCheck');
		}	
			if(CustomPayment == 1)
			{
				jQuery(".CustomPaymentTitleclass").show();
				jQuery(".CustomPaymentTitleclass").parent('div').show();
		
				jQuery('label[for="clickandpledge-custom_payment_title"]').css('visibility', 'visible');
				jQuery(".CustomPaymentTitleclass").parent('div').show();
				 var titles1 = jQuery(".CustomPaymentTitleclass").val(); 
					  var titlesarr1 = titles1.split(";");
						for(var j1=0;j1 < titlesarr1.length; j1++)
						 { 
							 if(titlesarr1[j1] !=""){
								 paymethods.push(titlesarr1[j1]);
								 paymethods_titles.push(titlesarr1[j1]);
							 }
						 }
			}
			else
			{
				jQuery(".CustomPaymentTitleclass").hide();
				jQuery(".CustomPaymentTitleclass").parent('div').hide();
		
				jQuery('label[for="clickandpledge-custom_payment_title"]').css('visibility', 'hidden');
				jQuery(".CustomPaymentTitleclass").parent('div').hide();
			}
			
					if(paymethods.length > 0) {
						for(var i1 = 0; i1 < paymethods.length; i1++) {
						
							if(paymethods[i1] == defaultval) {
							str += '<option value="'+paymethods[i1]+'" selected>'+paymethods_titles[i1]+'</option>';
							} else {
							str += '<option value="'+paymethods[i1]+'">'+paymethods_titles[i1]+'</option>';
							}
						}
					} else {
					str = '<option selected="selected" value="">Please select</option>';
					}
					jQuery('#clickandpledge-defaultpaymentmethod').html(str);
	}
	 // THE SCRIPT THAT CHECKS IF THE KEY PRESSED IS A NUMERIC OR DECIMAL VALUE.
    function isNumber(evt, element) {

        var charCode = (evt.which) ? evt.which : event.keyCode;//alert(charCode);

        if ((charCode != 45) &&      // “-” CHECK MINUS.
            (charCode != 46) &&      // “.” CHECK DOT.
            (charCode != 32) &&      // “.” CHECK SPACE.			
            (charCode != 48))      // “0” CHECK SPACE.			
            
            return false;

        return true;
    } 
	function isInteger(n) { 
		return /^[0-9]+$/.test(n); 
	} 
	function limitText(limitField, limitCount, limitNum) {
			
					if (limitField.val().length > limitNum) {
						limitField.val( limitField.val().substring(0, limitNum) );
					} else {
					//	limitCount.html (limitNum - limitField.val().length);
					}
				}
	function validateEmail($email) {
		  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		  return emailReg.test( $email );
		}
	 jQuery('table#t-result-data').trigger('update');	
});
jQuery(document).ready(function() {
 jQuery(document).on('update', '#payments-settings-table', function() {
    console.log('tr id');
  }); 
//jQuery("#payments-settings-table").change(cnphidefieldss);
function cnphidefieldss(){
	jQuery("#clickandpledge-label").hide();
	jQuery("label[for='clickandpledge-label']").css('visibility', 'hidden');
									
	jQuery("#clickandpledge-cnpshp_code	").hide();
	jQuery("label[for='clickandpledge-cnpshp_code']").css('visibility', 'hidden');
	}});