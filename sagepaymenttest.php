<?php
    require('shared.php');
    
    $nonces = getNonces();

    $req = [
        "merchantId" => $merchant['ID'],
        "merchantKey" => $merchant['KEY'], // don't include the Merchant Key in the JavaScript initialization!
        "requestType" => "payment",
        "orderNumber" => "Invoice" . rand(0, 1000),
        "amount" => $request['amount'],
        "salt" => $nonces['salt'],
        "postbackUrl" => $request['postbackUrl'],
        "preAuth" => $request['preAuth']
    ]; 
    
    $authKey = getAuthKey(json_encode($req), $developer['KEY'], $nonces['salt'], $nonces['iv']);
?>

<!-- PayJS 1.0.1 -->
<script type="text/javascript" src="https://www.sagepayments.net/pay/1.0.1/js/pay.min.js"></script>

<style>
	#paymentResponse.alert {
		background-color: #3f75a1;
		color: white;
		opacity: 0;
	}
	.form-control {
		margin: 0 auto;
		width: 50%;
	}
	.directions {
		font-size: 80%;
		margin: 0;
	}
	#customFormWrapper {
		padding: 15px;
		width: 50%;
		display: block;
		margin: 0 auto;
	}
    #customFormWrapper.static{
        background: repeating-linear-gradient(
          -45deg,
          white,
          white 10px,
          white 10px,
          white 20px
        );
    }
    #customFormWrapper.animated{
        background-image: repeating-linear-gradient(-45deg, white, white 10px, white 10px, white 20px);
            -webkit-animation:progress 2s linear infinite;
            -moz-animation:progress 2s linear infinite;
            -ms-animation:progress 2s linear infinite;
        animation:progress 2s linear infinite;
        background-size: 150% 100%;
    }
	#myCustomForm {
		background-color: white;
		padding: 15px;
		width: 95%;
	}
    #paymentButton.not-disabled{
        background-color: #90afc9;
        border-color: #3f75a1;
    }

    @-webkit-keyframes progress{
      0% {
        background-position: 0 0;
      }
      100% {
        background-position: -75px 0px;
      }
    }
    @-moz-keyframes progress{
      0% {
        background-position: 0 0;
      }
      100% {
        background-position: -75px 0px;
      }
    }    
    @keyframes progress{
      0% {
        background-position: 0 0;
      }
      100% {
        background-position: -70px 0px;
      }
    } 
</style>

<div class="wrapper text-center">
    <div id="customFormWrapper" class="static">
        <form class="form" id="myCustomForm">
            <h1>Pay Your Invoice Here</h1>
			<div class="form-group billing" id="invoice">
                <label class="control-label">Invoice Number(s) and/ or Client Account Number<br /> </label>
				<!-- TH - added "name" and "required" attributes.-->
                <input type="text" class="form-control" id="customer_invoice" name="inv_num" value="" placeholder="" required />
                <span class="help-block"></span>
				<p class="directions">Client Account Number - Please enter as using 5digits.3digits (ie: 12345.123). <br />
				Invoice Numer(s): Please seperate multiple invoice numbers with a comma (ie: 123456, 234567, 345678)</p>
            </div>
			<hr>

            <div class="form-group billing" id="amount">
                <label class="control-label">Amount</label>
                <input type="text" class="form-control" id="amount" value="" placeholder="">
                <span class="help-block"></span>
            </div>
			<div class="form-group billing" id="name-group">
                <label class="control-label">Full Name</label>
                <input type="text" class="form-control" id="billing_name" value="" placeholder="">
                <span class="help-block"></span>
				<p class="directions">As it appears on the credit card</p>
            </div>
            <div class="form-group cc" id="cc-group">
                <label class="control-label">Credit Card Number</label>
                <input type="text" class="form-control" id="cc_number" value="" placeholder="">
                <span class="help-block"></span>
            </div>			
            <div class="form-group cc" id="exp-group">
                <label class="control-label">Credit Card Expiration Date</label>
                <input type="text" class="form-control" id="cc_expiration" value="" placeholder="mm/yy">
                <span class="help-block"></span>
            </div>
            <!--<div class="form-group cc" id="cvv-group">-->
            <!--    <label class="control-label">CVV</label>-->
            <!--    <input type="text" class="form-control" id="cc_cvv" value="" placeholder="">-->
            <!--    <span class="help-block"></span>-->
            <!--</div>-->			
            <div class="form-group billing" id="address-group">
                <label class="control-label">Street Address</label>
                <input type="text" class="form-control" id="billing_street" value="" placeholder="">
                <span class="help-block"></span>
            </div>
            <div class="form-group billing" id="city-group">
                <label class="control-label">City</label>
                <input type="text" class="form-control" id="billing_city" value="" placeholder="">
                <span class="help-block"></span>
            </div>
            <div class="form-group billing" id="state-group">
                <label class="control-label">State</label>
                <input type="text" class="form-control" id="billing_state" value="" placeholder="">
                <span class="help-block"></span>
            </div>
            <div class="form-group billing" id="zip-group">
                <label class="control-label">Zip</label>
                <input type="text" class="form-control" id="billing_zip" value="" placeholder="">
                <span class="help-block"></span>
            </div>			
            <div class="form-group billing" id="email">
                <label class="control-label">Email</label>
                <input type="text" class="form-control" id="customer_email" value="" placeholder="">
                <span class="help-block"></span>
            </div>
			<div class="form-group billing" id="phone">
                <label class="control-label">Phone Number</label>
                <input type="text" class="form-control" id="customer_phone" value="" placeholder="XXX-XXX-XXXX">
                <span class="help-block"></span>
            </div>			
            
            <button class="btn btn-primary" id="paymentButton">Pay Now</button>
			
			<p>For further inqueries, please email <a href="mailto:Receivables@BradyWare.com">Receivables@BradyWare.com</a>.
        </form>
        <br /><br />
    </div>
    <div id="paymentResponse" class="alert alert-success" role="alert"></div>
    <br /><br />
        <h5>Results:</h5>
        <p style="width:100%"><pre><code id="paymentRawResponse">The response will appear here as JSON, and in your browser console as a JavaScript object.</code></pre></p>
</div>
<br /><br /><br />
<script type="text/javascript">
    PayJS(['jquery', 'PayJS/Core', 'PayJS/Request', 'PayJS/Response', 'PayJS/Formatting', 'PayJS/Validation'],
    function($, $CORE, $REQUEST, $RESPONSE, $FORMATTING, $VALIDATION) {

        $("#paymentButton").prop('disabled', true);

        var isValidCC = false,
            isValidExp = false;
            //TH - Removed CVV for client sample
            // isValidCVV = false;
        
        // when using REQUEST library, initialize via CORE instead of UI
        $CORE.Initialize({
            clientId: "<?php echo $developer['ID']; ?>",
            postbackUrl: "<?php echo $req['postbackUrl']; ?>",
            merchantId: "<?php echo $req['merchantId']; ?>",
            authKey: "<?php echo $authKey; ?>",
            salt: "<?php echo $req['salt']; ?>",
            requestType: "<?php echo $req['requestType']; ?>",
            orderNumber: "<?php echo $req['orderNumber']; ?>",
            amount: "<?php echo $req['amount']; ?>",
            debug: true,
            environment: "cert"
        });

        $("#paymentButton").click(function() {
            $(this).prop('disabled', true).removeClass("not-disabled");
            $("#myCustomForm :input").prop('disabled', true);
            
            $("#customFormWrapper").addClass("animated").removeClass("static");
            $("#customFormWrapper").fadeTo(2000, 0.1);
            
            // we'll add on the billing data that we collected
            $CORE.setBilling({
                name: $("#billing_name").val(),
                address: $("#billing_street").val(),
                city: $("#billing_city").val(),
                state: $("#billing_state").val(),
                postalCode: $("#billing_zip").val()
            });

            var cc = $("#cc_number").val();
            var exp = $("#cc_expiration").val();
            //TH - set CVV to blank for client sample
            //var cvv = $("#cc_cvv").val();
            var cvv = '';
            
            // run the payment
            $REQUEST.doPayment(cc, exp, cvv, function(resp) {
                // if you want to use the RESPONSE module with REQUEST, run the ajax response through tryParse...
                $RESPONSE.tryParse(resp);
                // ... which will initialize the RESPONSE module's getters
                console.log($RESPONSE.getResponse());
                $("#paymentResponse").text(
                    $RESPONSE.getTransactionSuccess() ? "Your payment has successfully been processed." : "DECLINED"
                )
                $("#paymentRawResponse").text(
                    $RESPONSE.getRawResponse()
                )
                $("#customFormWrapper").hide();
                $("#paymentResponse").fadeTo(1000, 1);
                
            })
        })
        
        $(".billing .form-control").blur(function(){
            toggleClasses($(this).val().length > 0, $(this).parent());
            checkForCompleteAndValidForm();
        })

        $("#cc_number").blur(function() {
            var cc = $("#cc_number").val();
            // we'll format the credit card number with dashes
            cc = $FORMATTING.formatCardNumberInput(cc, '-');
            $("#cc_number").val(cc);
            // and then check it for validity
            isValidCC = $VALIDATION.isValidCreditCard(cc);
            toggleClasses(isValidCC, $("#cc-group"));
            checkForCompleteAndValidForm();
        })


        $("#cc_expiration").blur(function() {
            var exp = $("#cc_expiration").val();
            exp = $FORMATTING.formatExpirationDateInput(exp, '/');
            $("#cc_expiration").val(exp);
            isValidExp = $VALIDATION.isValidExpirationDate(exp);
            toggleClasses(isValidExp, $("#exp-group"));
            checkForCompleteAndValidForm();
        })

        //TH - removed CVV for client sample
        // $("#cc_cvv").blur(function() {
        //     var cvv = $("#cc_cvv").val();
        //     cvv = cvv.replace(/\D/g,'');
        //     $("#cc_cvv").val(cvv);
        //     isValidCVV = $VALIDATION.isValidCvv(cvv, $("#cc_number").val()[0]);
        //     toggleClasses(isValidCVV, $("#cvv-group"));
        //     checkForCompleteAndValidForm();
        // })

        function toggleClasses(isValid, obj) {
            if (isValid) {
                obj.addClass("has-success").removeClass("has-error");
                obj.children(".help-block").text("Valid");
            } else {
                obj.removeClass("has-success").addClass("has-error");
                obj.children(".help-block").text("Invalid");
            }
        }

        function checkForCompleteAndValidForm() {
            var isValidBilling = true;
            $.each($(".billing"), function(){ isValidBilling = isValidBilling && $(this).hasClass("has-success") });
            
            // assuming most people fill out the form from top-to-bottom,
            // checking it from bottom-to-top takes advantage of short-circuiting
            //TH - Removed CVV requirement for client sample
            //if (isValidCVV && isValidExp && isValidCC && isValidBilling) {
            if (isValidExp && isValidCC && isValidBilling) {
                $("#paymentButton").prop('disabled', false).addClass("not-disabled");
            }
        }
    });
</script>	
