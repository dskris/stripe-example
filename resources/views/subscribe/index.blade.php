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
                <div class="panel-heading">Task 3: Subscribe For a plan</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" id="payment-form" role="form" action="{!! URL::route('subscribe') !!}" >
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="ccExpiryMonth" class="col-md-4 control-label">Subscription Plan</label>
                            <div class="col-md-6">
                                <select class="form-control" name="plan" autofocus>
                                 @foreach($plans['data'] as $plan)
                                  <option value="{{$plan['id']}}">{{$plan['id']}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="card_no" class="col-md-4 control-label">Card No</label>
                            <div class="col-md-6">
                                <input id="card_no" type="text" class="form-control" name="card_no" value="{{ old('card_no') }}" autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ccExpiryMonth" class="col-md-4 control-label">Email</label>
                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="email" autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ccExpiryMonth" class="col-md-4 control-label">Expiry Month</label>
                            <div class="col-md-6">
                                <input id="ccExpiryMonth" type="text" class="form-control" name="ccExpiryMonth" value="{{ old('ccExpiryMonth') }}" autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ccExpiryYear" class="col-md-4 control-label">Expiry Year</label>
                            <div class="col-md-6">
                                <input id="ccExpiryYear" type="text" class="form-control" name="ccExpiryYear" value="{{ old('ccExpiryYear') }}" autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ccExpiryYear" class="col-md-4 control-label">Initial Charge</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="amount" autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cvvNumber" class="col-md-4 control-label">CVV No.</label>
                            <div class="col-md-6">
                                <input id="cvvNumber" type="text" class="form-control" name="cvvNumber" value="{{ old('cvvNumber') }}" autofocus>
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
                                    Subscribe
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
                url: $('#baseURL').val()+'/subscribe',
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