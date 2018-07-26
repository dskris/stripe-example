<!--<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>stripe task 1 : Make payment</title>
    </head>
    <body>
        <form action="/charge" method="POST">
            {{ csrf_field() }}
            <script
                    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                    data-key="pk_test_WXhAzWZCnJMtUAmgK3xrE2Ix"
                    data-amount="1999"
                    data-name="Stripe Demo"
                    data-description="Online course about integrating Stripe"
                    data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                    data-locale="auto"
                    data-currency="usd">
            </script>
        </form>
    </body>
</html>-->

@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Task 2: Create Monthly Plan</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" id="payment-form" role="form" action="{!! URL::route('createMonthlyPlan') !!}" >
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="card_no" class="col-md-4 control-label">ID</label>
                            <div class="col-md-6">
                                <input id="card_no" type="text" class="form-control" name="plan_id" autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ccExpiryMonth" class="col-md-4 control-label">Plan Name</label>
                            <div class="col-md-6">
                                <input id="ccExpiryMonth" type="text" class="form-control" name="plan_name" autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ccExpiryYear" class="col-md-4 control-label">Currency</label>
                            <div class="col-md-6">
                                <select class="form-control" name="plan_currency" autofocus>
                                  <option value="usd">usd</option>
                                  <option value="myr">myr</option>
                                  <option value="idr">idr</option>
                                  <option value="inr">inr</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cvvNumber" class="col-md-4 control-label">Amount</label>
                            <div class="col-md-6">
                                <input id="cvvNumber" type="text" class="form-control" name="plan_amount" autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="amount" class="col-md-4 control-label">Plan Interval</label>
                            <div class="col-md-6">
                                <select class="form-control" name="plan_interval" autofocus>
                                  <option value="day">day</option>
                                  <option value="week">week</option>
                                  <option value="month">month</option>
                                  <option value="year">year</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="error hide" style="color:red;"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="button" class="btn btn-primary" id="submitPaymentButton">
                                    Create Subscription Plan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('customJS')
<script type="text/javascript">
   $(document).ready(function(){
        $("#submitPaymentButton").click(function() {
            //$("#submitPaymentButton").prop("disabled",true);
            $('.error').addClass('hide');
            $.ajax({
                url: $('#baseURL').val()+'/monthly-plan',
                type: 'POST',
                data: $('#payment-form').serialize(),
                success: function (status) {
                    var result = JSON.parse(status);
                    if(result.status){
                        $('.error').html(result.message);
                    }else{
                        $("#submitPaymentButton").prop("disabled",false);
                        $('.error').html(result.message);
                        $('.error').removeClass('hide');
                    }
                },
                error: function(jqXHR, exception){
                    ajax_error_handling(jqXHR, exception);
                }
            });
        });
    });
</script>
@endsection