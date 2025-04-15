@extends('layouts/commonMaster')
@section('layoutContent')
  <div class="container-xxl container-p-y">
    <div class="misc-wrapper centered-content">
      <h1 class="mb-2 mx-2" style="line-height: 6rem; font-size: 6rem;">401</h1>
      <h4 class="mb-2 mx-2">Not Authorized ⚠️</h4>
      <p class="mb-6 mx-2">You don’t have permission to access this page. Go Home!</p>
      
      @auth
        <a href="{{ url('/dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
      @else
        <a href="{{ url('/') }}" class="btn btn-primary">Back to Home</a>
      @endauth
      
      @if(url()->previous() && url()->previous() !== url()->current())
        <a href="{{ url()->previous() }}" class="btn btn-primary mt-2">Back to Previous Page</a>
      @endif
      
      <div class="mt-6">
        <img src="../../assets/img/illustrations/girl-with-laptop-light.png" alt="page-misc-not-authorized-light" width="500" class="img-fluid" data-app-light-img="illustrations/girl-with-laptop-light.png" data-app-dark-img="illustrations/girl-with-laptop-dark.png" />
      </div>
    </div>
  </div>

  <style>
    .centered-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        text-align: center;
    }
  </style>
@endsection