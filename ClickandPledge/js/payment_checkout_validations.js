jQuery(document).ready(function() {//alert('load-chkout');
	
	/*		START ONLOAD		*/
	jQuery("#clickandpledge-payment_options_label").prop("required", "required");
	jQuery("#clickandpledge-payment_options_recurring").attr("disabled", true); 	
	/*		END ONLOAD		*/
	
	if(jQuery('input:radio.payment_typeClass:checked').val() != "Credit Card" && jQuery('input:radio.payment_typeClass:checked').val() != "eCheck")
		{
			jQuery(".Custom_Payment").show();
			jQuery(".payment").hide();
			jQuery("#cnp_eCheck_div").hide();
			jQuery("#billing-card, #billing-cardexpires-mm, #billing-cardexpires-yy, #billing-cardtype").removeClass("paycard  required");
			jQuery("#billing-card, #billing-cardexpires-mm, #billing-cardexpires-yy, #billing-cardtype").attr("disabled", true);
			
			jQuery("#billing-cvv").removeClass("min3 required paycard");
			jQuery("#billing-cvv").attr("disabled", true);
		}
	else if(jQuery('input:radio.payment_typeClass:checked').val() == "Credit Card")
		{
			
			jQuery(".payment").show();jQuery(".Custom_Payment").hide();
			jQuery("#billing-card, #billing-cardexpires-mm, #billing-cardexpires-yy, #billing-cardtype").addClass("paycard  required");
			jQuery("#billing-card, #billing-cardexpires-mm, #billing-cardexpires-yy, #billing-cardtype").attr("disabled", false);
			jQuery("#cnp_eCheck_div").hide();
			jQuery("#billing-cvv").addClass("min3 required paycard");
			jQuery("#billing-cvv").attr("disabled", false);
		}
	var payment_options_recurringtypeValue = jQuery("input[name=payment_options_recurringtype]:checked").val();
	
	if(payment_options_recurringtypeValue == 'recurring')
	{
		jQuery("#recurringtypes-block").css("display", "block");
	}
	else if(payment_options_recurringtypeValue == 'onetimeonly')
	{
		jQuery("#recurringtypes-block").css("display", "none");
	}
	/*	START checkout form validations for Recurring	*/	
	
	var cnp = jQuery('select[name="paymethod"]').val();//jQuery('select option:contains("Click & Pledge")').length;
	var CnP_pay = jQuery('#CnP_pay').val();
	//alert(cnp+' -- '+CnP_pay);
	//order-data-payment_options_onetimeonly
	if(cnp == "click-pledge" || CnP_pay == 'YES')
	{//alert('cnp-1');	
		jQuery("#recStruct").css("display", "block");
	}
	else
	{//alert('cnp-2');
		/*jQuery("#order-data-recurringfield_label").css("display", "none");
		jQuery("#order-data-recurringfield_label").prop("disabled", "disabled");
		jQuery("#order-data-field_description").css("display", "none");
		jQuery("#order-data-field_description").prop("disabled", "disabled");
		jQuery("#order-data-payment_options_onetimeonly").css("display", "none");	
		jQuery("#order-data-payment_options_recurring").css("display", "none");
		jQuery("#order-data-recurringperiodicity").css("display", "none");
		jQuery("#order-data-installments_no").css("display", "none");
		jQuery("#order-data-indefiniterecurring").css("display", "none");
		jQuery("#order-data-indefiniterecurring").css("display", "none");*/
		jQuery("#recStruct").css("display", "none");
	}
	//alert(jQuery('select[name="order-data-payment_options_recurringtype"]'));
	
	jQuery("#checkout-button").click(function(e) {
		
		if(jQuery('input[name=payment_options_recurringtype]:checked').val() === "recurring"){
		 var maxinsta = jQuery("#maxinstallments_no").val();
		 if(jQuery('input[name=payment_type]:checked').val() != "Credit Card" && jQuery('input[name=payment_type]:checked').val() != "eCheck"){  
		
		 if(jQuery('input[name=payment_options_recurringtype]:checked').val() == 'recurring' ||        jQuery('#payment_options_recurringtype').val() == 'recurring') {
				  alert('Sorry but recurring payments are not supported with this payment method');
					 return false;
				}
		}
			 if(jQuery('input[name=payment_type]:checked').val() == "Credit Card" || jQuery('input[name=payment_type]:checked').val() == "eCheck"){  
			if(jQuery("#installments_no").val() == "")
				{
					alert("Please enter valid number of payments");
					jQuery("#installments_no").focus();
					return false;
				}
			else if (/^\d*$/.test(jQuery("#installments_no").val()) == false)
				{
					 alert("Please enter valid number of payments");
						 $("#installments_no").focus(); 
						 return false;
				}
			else if(jQuery("#installments_no").val() <= 1)
				{
					alert("Please enter number of payments should be greater than or equal to 2");
					jQuery("#installments_no").focus();
					return false;
				}
			else if(parseInt(maxinsta) !="" && parseInt(jQuery("#installments_no").val()) > parseInt(maxinsta))
				{
					if(parseInt(maxinsta) != 2){
						alert("Please enter number of payments between 2- "+ maxinsta+" only");}
					else{alert("Please enter number of payments 2 only");}
					jQuery("#installments_no").focus();
					return false;
				}
			else if(jQuery("#RecurringType").val() =="Installment" && parseInt(jQuery("#installments_no").val()) > 998)
				{
				  alert("Please enter number of payments value between 2 to 998 for installment");
				  jQuery("#installments_no").focus();
				   return false;
				}
			 }
		}
		
        var CardNumber_reg = /^([0-9]){15,17}$/;
        var Cvv2_reg = /^([0-9]){3,4}$/;
		
		if(jQuery('input[name=payment_type]:checked').val() == "Credit Card"){
			
		if(jQuery("#billing-card").val()=="")
			{
			  alert("You did not provide a credit card number");
			  jQuery("#billing-card").focus();
			  return false;
			}
		if(CardNumber_reg.test(jQuery("#billing-card").val()) == false){
		   alert("Invalid Credit Card Number");
		   jQuery("#billing-card").focus();
		   return false;
			}
		if(jQuery("#billing-cvv").val()=="")
			{
			  alert("You did not enter a valid security ID for the card you provided");
			  jQuery("#billing-cvv").focus();
			  return false;
			}
		if(Cvv2_reg.test(jQuery("#billing-cvv").val()) == false){
			   alert("Please enter a number at least 3 or 4 digits in card security ID");
			   jQuery("#billing-cvv").focus();
			   return false;
			}
		if(jQuery("#billing-cardexpires-mm").val()=="")
			{
			  alert("You did not enter the month the credit card expires");
			  jQuery("#billing-cardexpires-mm").focus();
			  return false;
			}
		if(jQuery("#billing-cardexpires-yy").val()=="")
			{
			  alert("You did not enter the year the credit card expires");
			  jQuery("#billing-cardexpires-yy").focus();
			  return false;
			}
		}
		else if(jQuery('input[name=payment_type]:checked').val() == "eCheck"){
		var AccountNumber_reg = /^([a-zA-Z0-9]){1,17}$/;
        var RoutingNumber_reg = /^([a-zA-Z0-9]){1,9}$/;
        var CheckNumber_reg = /^([a-zA-Z0-9]){1,10}$/;
        var NameOnAccount_reg = /^([a-zA-Z0-9]){0,100}$/;
			jQuery("#billing-card, #billing-cardexpires-mm, #billing-cardexpires-yy, #billing-cardtype").removeClass("paycard  required");
			jQuery("#billing-card, #billing-cardexpires-mm, #billing-cardexpires-yy, #billing-cardtype").attr("disabled", true);
			
			jQuery("#billing-cvv").removeClass("min3 required paycard");
			jQuery("#billing-cvv").attr("disabled", true);
		if(jQuery("#clickandpledge_echeck_NameOnAccount").val()=="")
			{
			  alert("Please enter Name On Account");
			  jQuery("#clickandpledge_echeck_NameOnAccount").focus();
			  return false;
			}
			if(NameOnAccount_reg.test(jQuery("#clickandpledge_echeck_NameOnAccount").val()) == false){
			   alert("Invalid Name On Account");
			   jQuery("#clickandpledge_echeck_NameOnAccount").focus();
			   return false;
			}
			if(jQuery("#clickandpledge_echeck_CheckNumber").val()=="")
			{
			  alert("Please enter Check Number");
			  jQuery("#clickandpledge_echeck_CheckNumber").focus();
			  return false;
			}
			if(CheckNumber_reg.test(jQuery("#clickandpledge_echeck_CheckNumber").val()) == false){
			   alert("Invalid Check Number");
			   jQuery("#clickandpledge_echeck_CheckNumber").focus();
			   return false;
			}
			if(jQuery("#clickandpledge_echeck_RoutingNumber").val()=="")
			{
			  alert("Please enter Routing Number");
			  jQuery("#clickandpledge_echeck_RoutingNumber").focus();
			  return false;
			}
			if(RoutingNumber_reg.test(jQuery("#clickandpledge_echeck_RoutingNumber").val()) == false){
			   alert("Invalid Routing Number");
			   jQuery("#clickandpledge_echeck_RoutingNumber").focus();
			   return false;
			}
			if(jQuery("#clickandpledge_echeck_AccountNumber").val()=="")
			{
			  alert("Please enter Account Number");
			  jQuery("#clickandpledge_echeck_AccountNumber").focus();
			  return false;
			}
			if(AccountNumber_reg.test(jQuery("#clickandpledge_echeck_AccountNumber").val()) == false){
			   alert("Invalid Account Number");
			   jQuery("#clickandpledge_echeck_AccountNumber").focus();
			   return false;
			}
			if(jQuery("#clickandpledge_echeck_retypeAccountNumber").val()=="")
			{
			  alert("Please enter Account Number Again");
			  jQuery("#clickandpledge_echeck_retypeAccountNumber").focus();
			  return false;
			}
			if(jQuery("#clickandpledge_echeck_AccountNumber").val()!=jQuery("#clickandpledge_echeck_retypeAccountNumber").val())
			{
			  alert("Please enter same Account Number Again");
			  jQuery("#billing-cardexpires-yy").focus();
			  return false;
			}
		}
		else if(jQuery('input[name=payment_type]:checked').val() != "Credit Card" && jQuery('input[name=payment_type]:checked').val() != "eCheck" && jQuery('input[name=payment_options_recurringtype]:checked').val() != "recurring"){  
		
		 if(jQuery("#PaymentNumber").val()=="")
			{
			  alert("You did not enter the payment number");
			  jQuery("#PaymentNumber").focus();
			  return false;
			}			
		}
		else{	
		jQuery("#checkout").submit();}
	});
	jQuery(".paymentoptionsrecurringtype").click(function() {
		var rcType = jQuery(this).val();
		//alert(rcType);
		if(rcType == "onetimeonly")
		{
			jQuery("#recurringtypes-block").css("display", "none");
		}
		if(rcType == "recurring")
		{
			jQuery("#recurringtypes-block").css("display", "block");
		}
	
	});
	
	
	jQuery("#RecurringType").change(function() {
		var RTVal = jQuery(this).val();//alert(RTVal);
		if(jQuery("#frntnoofpayments").val() == "Indefinite + Open Field Option" || jQuery("#frntnoofpayments").val() == "Indefinite Only" ){
		if(RTVal == "Installment")
		{	jQuery("#installments_noSpan").text(998);	
			jQuery("#installments_no").val(998);	
		}
		if(RTVal == "Subscription")
		{		
			jQuery("#installments_noSpan").text(999);	
			jQuery("#installments_no").val(999);		
		}
		}
	});
	//jQuery("#cnp_eCheck_div").hide();
	//jQuery(".Custom_Payment").hide();//onLOAD
	jQuery('.payment_typeClass').change(function(){
		var PTVal = jQuery(this).val();//alert(PTVal);

		if(PTVal != "Credit Card" && PTVal != "eCheck")
		{
			jQuery(".Custom_Payment").show();
			jQuery(".payment").hide();
			jQuery("#cnp_eCheck_div").hide();
			jQuery("#billing-card, #billing-cardexpires-mm, #billing-cardexpires-yy, #billing-cardtype").removeClass("paycard  required");
			jQuery("#billing-card, #billing-cardexpires-mm, #billing-cardexpires-yy, #billing-cardtype").attr("disabled", true);
			
			jQuery("#billing-cvv").removeClass("min3 required paycard");
			jQuery("#billing-cvv").attr("disabled", true);
		}
		if(PTVal == "eCheck")
		{
			jQuery(".Custom_Payment").hide();
			jQuery(".payment").hide();
			jQuery("#cnp_eCheck_div").show();
			jQuery("#billing-card, #billing-cardexpires-mm, #billing-cardexpires-yy, #billing-cardtype").removeClass("paycard  required");
			jQuery("#billing-card, #billing-cardexpires-mm, #billing-cardexpires-yy, #billing-cardtype").attr("disabled", true);
			
			jQuery("#billing-cvv").removeClass("min3 required paycard");
			jQuery("#billing-cvv").attr("disabled", true);
		}
		if(PTVal == "Credit Card")
		{
			jQuery(".payment").show();jQuery(".Custom_Payment").hide();
			jQuery("#billing-card, #billing-cardexpires-mm, #billing-cardexpires-yy, #billing-cardtype").addClass("paycard  required");
			jQuery("#billing-card, #billing-cardexpires-mm, #billing-cardexpires-yy, #billing-cardtype").attr("disabled", false);
			jQuery("#cnp_eCheck_div").hide();
			jQuery("#billing-cvv").addClass("min3 required paycard");
			jQuery("#billing-cvv").attr("disabled", false);
		}
	});
	
	jQuery('select[name="paymethod"]').change(function(){									 
		var PGVal = jQuery(this).val();
	
		if(PGVal === "click-pledge")
		{//alert('click-pledge');		
			jQuery("#recStruct").css("display", "block");	
			jQuery("#order-data-recurringfield_label").css("display", "block");	
			jQuery("#order-data-recurringfield_label").prop("disabled", "disabled");
			jQuery("#order-data-field_description").css("display", "block");	
			jQuery("#order-data-field_description").prop("disabled", "disabled");
			jQuery("#order-data-payment_options_onetimeonly").css("display", "block");	
			jQuery("#order-data-payment_options_recurring").css("display", "block");	
			jQuery("#clickandpledge-SubscriptionLimit").css("display","none");
			jQuery("label[for='clickandpledge-subscriptionlimit']").css("display","none");
			
			jQuery("#clickandpledge-InstallmentLimit").css("display","none");
			jQuery("label[for='clickandpledge-InstallmentLimit']").css("display","none");
			
			jQuery("#clickandpledge-recurring").click(function() {
				//alert(jQuery("#clickandpledge-recurring").checked);
				/*jQuery("#clickandpledge-recurringtype").css("display","none");
				jQuery("label[for='clickandpledge-recurringtype']").css("display","none");*/
			});
			jQuery("#clickandpledge-Subscription").change(function() {
				var rtVal = jQuery(this).val();//alert(rtVal);
				if(rtVal == "Subscription")
				{
					
				}
				
			});	
			
		//alert(jQuery.isNumeric(jQuery("#clickandpledge-subscriptionlimit").val()));	
			jQuery("#clickandpledge-subscriptionlimit").change(function() {
				var rtVal = jQuery(this).val();//alert(rtVal);return false;
				if(rtVal > 999)
				{
					alert("Maximum Subscription Limit is 999");
					jQuery("#clickandpledge-subscriptionlimit").focus();
					return false;
				}
				if(!isNumeric(rtVal))
				{
					alert("Subscription Limit accepts only Numerics");
					jQuery("#clickandpledge-subscriptionlimit").focus();
					return false;
				}		
			});	
			
			jQuery("#clickandpledge-installmentlimit").change(function() {
				var rtVal = jQuery(this).val();//alert(rtVal);return false;
				if(rtVal > 999)
				{
					alert("Maximum Installment Limit is 999");
					jQuery("clickandpledge-installmentlimit").focus();
					return false;
				}
				if(!isNumeric(rtVal))
				{
					alert("Installment Limit accepts only Numerics");
					jQuery("#clickandpledge-subscriptionlimit").focus();
					return false;
				}		
			});
			
			/*jQuery("#order-data-recurring").click(function() {
				var recurringchecked = jQuery(this).is(":checked");
				if(recurringchecked == true)
				{
					jQuery("#order-data-recurringtype").css("display", "block");
					jQuery("#order-data-recurringperiodicity").css("display", "block");
					jQuery("#order-data-installments_no").css("display", "block");
					jQuery("#order-data-indefiniterecurring").css("display", "block");
				}
				else
				{
					jQuery("#order-data-recurringtype").css("display", "none");
					jQuery("#order-data-recurringperiodicity").css("display", "none");
					jQuery("#order-data-installments_no").css("display", "none");
					jQuery("#order-data-indefiniterecurring").css("display", "none");
				}
			});*/
			
			jQuery("#order-data-indefiniterecurring").click(function() {
				var indefiniterecurringchecked = jQuery(this).is(":checked");
				if(indefiniterecurringchecked == true)
				{
					jQuery("#order-data-installments_no").css("display", "none");
				}
				else
				{
					jQuery("#order-data-installments_no").css("display", "block");
				}
			});
		}
		else
		{//alert('else');		
			jQuery("#recStruct").css("display", "none");
			/*jQuery("#order-data-recurringfield_label").css("display", "none");	
			jQuery("#order-data-field_description").css("display", "none");
			//jQuery("#order-data-payment_options_recurringtype").css("display", "none");	
			//jQuery("#order-data-payment_options_recurringtype").css("display", "none");		
			jQuery("#order-data-recurringtype").css("display", "none");
			jQuery("#order-data-recurringperiodicity").css("display", "none");
			jQuery("#order-data-installments_no").css("display", "none");
			jQuery("#order-data-indefiniterecurring").css("display", "none");
			jQuery("#order-data-indefiniterecurring").css("display", "none");*/
		}
	});
	/*	END checkout form validations for Recurring	*/
	
	/*		onSubmit		*/
	/*jQuery("#checkout").submit(function() {
		alert('checkout');return false;
	});*/
});