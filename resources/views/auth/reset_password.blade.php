@extends('web.layout')

@section('title')
    forgot Password
@endsection

@section('content')




    <!-- Contact -->
    <div id="contact" class="section">

        <!-- container -->
        <div class="container">

            <!-- row -->
            <div class="row">

                <!-- login form -->
                <div class="col-md-6 col-md-offset-3">
                    <div class="contact-form">
                        <h4>{{ __('web.newPassword') }}</h4>

                        @include('web.includes.msg')

                        <form method="POST" action="{{ url('reset-password') }}">
                            @csrf
                            <input class="input" type="email" name="email" placeholder="email">
                            <input class="input" type="password" name="password" placeholder="password">
                            <input class="input" type="password" name="password_confirmation"
                                placeholder="password_confirmation">
                            <input type="hidden" name="token" value="{{ request()->route('token') }}">
                            <button type="submit"
                                class="main-button icon-button pull-right">{{ __('web.submitBtn') }}</button>
                        </form>
                    </div>
                </div>
                <!-- /login form -->

            </div>
            <!-- /row -->

        </div>
        <!-- /container -->

    </div>
    <!-- /Contact -->


@endsection
