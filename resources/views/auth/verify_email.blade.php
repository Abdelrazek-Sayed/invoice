@extends('web.layout')

@section('title')
    verify email
@endsection


@section('content')


<div class="alert alert-success">

    A verification email sent successfully, please check your inbox


    </div>

    <div class="container">
       <div class="row">
           <div class="col-md-6 col-md-offset-3">
               <div class="contact-form">

                  <form action="{{url('/email/verification-notification')}}" method="POST">
                     @csrf
                  <button type="submit" class="main-button icon-button pull-right" >Resend Email</button>

                </form>
               </div>
           </div>
       </div>
    </div>

@endsection
