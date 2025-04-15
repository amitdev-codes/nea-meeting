@extends('layouts/commonMaster')
@section('layoutContent')
  <div class="container-xxl container-p-y">
    <div class="misc-wrapper centered-content">
      <h1 class="mb-2 mx-2" style="line-height: 6rem; font-size: 6rem;">500</h1>
      <h4 class="mb-2 mx-2">Server Error ⚠️</h4>
      <p class="mb-6 mx-2">We couldn't find the page you are looking for</p>
      
      @auth
        <a href="{{ url('/dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
      @else
        <a href="{{ url('/') }}" class="btn btn-primary">Back to Home</a>
      @endauth
      
      @if(url()->previous() && url()->previous() !== url()->current())
        <a href="{{ url()->previous() }}" class="btn btn-primary mt-2">Back to Previous Page</a>
      @endif
      
      <div class="mt-6">
        <img src="../../assets/img/illustrations/girl-doing-yoga-light.png" alt="girl-doing-yoga-light" width="500" class="img-fluid" data-app-light-img="illustrations/girl-doing-yoga-light.png" data-app-dark-img="illustrations/girl-doing-yoga-dark.png" />
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