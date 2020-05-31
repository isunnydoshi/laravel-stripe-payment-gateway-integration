<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel with Stripe</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    </head>
    <body>
        <div class="content">
            <div class="title m-b-md">
                Laravel + Stripe
            </div>
        </div>
        
        <section class="pricing py-5">
            <div class="container"> 
                    <form action="{{ route('dopay') }}" method="POST" id="subscribe-form" onsubmit="return submitpayment()" >
                    {{ csrf_field() }}
                    
                    <div style="text-align: center; margin-bottom: 20px">
                        <span id="alert-danger" class="alert alert-danger d-none"></span>
                        <span id="alert-success" class="alert alert-success d-none"></span>
                    </div>
                    <div class="row">
                        <div class="col-lg-3"></div> 
                        <div class="col-lg-3">
                            <div class="card mb-5 mb-lg-0">
                                <div class="card-body">
                                    <h5 class="card-title text-muted text-uppercase text-center">Basic Plan</h5>
                                    <h6 class="card-price text-center">$9<span class="period">/year</span></h6>
                                    <hr>
                                    <ul class="fa-ul">
                                        <li><span class="fa-li"><i class="fas fa-check"></i></span>Single User</li>
                                        <li><span class="fa-li"><i class="fas fa-check"></i></span>5GB Storage</li>
                                        <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Free Subdomain</li>
                                        <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Monthly Status Reports</li>
                                        <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Free premium support</li>
                                    </ul>
                                    <input type="submit" id="submit-btn-1" name="submit-btn" value="Pay" class="btn btn-block btn-success text-uppercase" />        
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card mb-5 mb-lg-0">
                                <div class="card-body">
                                    <h5 class="card-title text-muted text-uppercase text-center">Premium Plan</h5>
                                    <h6 class="card-price text-center">$19<span class="period">/year</span></h6>
                                    <hr>
                                    <ul class="fa-ul">
                                        <li><span class="fa-li"><i class="fas fa-check"></i></span>5 Users</li>
                                        <li><span class="fa-li"><i class="fas fa-check"></i></span>15GB Storage</li>
                                        <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Free Subdomain</li>
                                        <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Monthly Status Reports</li>
                                        <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Free premium support</li>
                                    </ul>
                                    <input type="submit" id="submit-btn-2" name="submit-btn" value="Pay" class="btn btn-block btn-success text-uppercase" />        
                                </div>
                            </div>
                        </div>  
                        <div class="col-lg-3"></div>
                    </div>
                    <input type="hidden" name="amount" id="amount" value="" />
                    <input type="hidden" name="plan" id="plan" value="" />
                    <input type="hidden" name="stripeToken" id="stripeToken" value="" /> 
                </form> 
            </div>  
            <br/><br/><br/>
        </section>

    </body>

    <!-- Bootstrap js -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="https://checkout.stripe.com/checkout.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
    
    <script type="text/javascript">
        function showProcessing() {
            $('.subscribe-process').show();
        }
        function hideProcessing() {
            $('.subscribe-process').hide();
        }

        // Handling and displaying error during form submit.
        function subscribeErrorHandler(jqXHR, textStatus, errorThrown) {
            try {
                var resp = JSON.parse(jqXHR.responseText);
                if ('error_param' in resp) {
                    var errorMap = {};
                    var errParam = resp.error_param;
                    var errMsg = resp.error_msg;
                    errorMap[errParam] = errMsg;
                } else {
                    var errMsg = resp.error_msg;
                    $("#alert-danger").addClass('alert alert-danger').removeClass('d-none').text(errMsg);
                }
            } catch (err) {
                $("#alert-danger").show().text("Error while processing your request");
            }
        }

        // Forward to thank you page after receiving success response.
        function subscribeResponseHandler(responseJSON) {
        //window.location.replace(responseJSON.successMsg);
            if (responseJSON.state == 'success') {
                $("#alert-success").addClass('alert alert-success').removeClass('d-none').text(responseJSON.message);
                $("#alert-danger").addClass('d-none');
            }
            if (responseJSON.state == 'error') {
                $("#alert-danger").addClass('alert alert-danger').removeClass('d-none').text(responseJSON.message);
                $("#alert-success").addClass('d-none');
            }
        }

        var handler = StripeCheckout.configure({
        //Replace it with your stripe publishable key
            key: "<?php echo config('constants.STRIPE_KEY'); ?>",
            image: 'https://networkprogramming.files.wordpress.com/2018/10/twitter.png', // add your company logo here
            allowRememberMe: false,
            token: handleStripeToken
        });

        function submitpayment() {
            var form = $("#subscribe-form");
            if (parseInt($("#amount").val()) <= 0) {
                return false;
            }
            handler.open({
                name: 'Laravel Stripe Payment',
                description: $("#plan").val()+' Plan',
                amount: ($("#amount").val() * 100)
            });
            return false;
        }

        function handleStripeToken(token, args) {
            form = $("#subscribe-form");
            $("input[name='stripeToken']").val(token.id);
            var options = {
                beforeSend: showProcessing,
                // post-submit callback when error returns
                error: subscribeErrorHandler,
                // post-submit callback when success returns
                success: subscribeResponseHandler,
                complete: hideProcessing,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                dataType: 'json'
            };

            form.ajaxSubmit(options);
            return false;
        }
        
        $("#submit-btn-1").click(function(){
            $("#amount").val('9');
            $("#plan").val('Basic');
        });
        $("#submit-btn-2").click(function(){
            $("#amount").val('19');
            $("#plan").val('Premium');
        });
    </script>
</html>
