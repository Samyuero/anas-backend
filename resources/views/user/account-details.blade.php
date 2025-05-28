@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4" style="padding-top: 50px;"></div>
    <section class="my-account container">
      <h2 class="page-title">Account Details</h2>
      <div class="row">
        <div class="col-lg-3">
          @include('user.account-nav')
        </div>
        <div class="col-lg-9">
          <div class="page-content my-account__edit">
            <div class="my-account__edit-form">
              <form name="account_edit_form" action="{{ route('user.account.update') }}" method="POST" class="needs-validation" novalidate="">
                @csrf
                @method('PUT')
                 @if(Session::has('success'))
                  <div class="alert alert-success alert-dismissable fade show" role="alert">
                      {{Session::get('success')}}
                  </div>
                @endif
                <div class="row">
                  <input type="hidden" name="id" value="{{ Auth::user()->id }}">
                  <div class="col-md-6">
                    <div class="form-floating my-3">
                      <input type="text" class="form-control" placeholder="Full Name" name="name" value="{{ old('name', $user->name) }}">
                      <label for="name">Name</label>
                      @error('name')
                        <div class="text-danger">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-floating my-3">
                      <input type="text" class="form-control" placeholder="Mobile Number" name="mobile" value="{{ old('name', $user->mobile) }}">
                      <label for="mobile">Mobile Number</label>
                      @error('mobile')
                        <div class="text-danger">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-floating my-3">
                      <input type="email" class="form-control" placeholder="Email Address" name="email" value="{{ old('name', $user->email) }}">
                      <label for="account_email">Email Address</label>
                      @error('email')
                        <div class="text-danger">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  
                  <div class="col-md-4">
                    <div class="form-floating my-3">
                      <input type="text" class="form-control" name="sitio" placeholder="Sitio" value="{{ old('sitio')}}">
                      <label for="sitio">Sitio</label>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-floating my-3">
                      <input type="text" class="form-control" name="barangay" placeholder="Barangay" value="{{ old('barangay')}}">
                      <label for="barangay">Barangay</label>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-floating my-3">
                      <input type="text" class="form-control" name="city" placeholder="City or Municipality" value="{{ old('city')}}">
                      <label for="city">City or Municipality</label>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-floating my-3">
                      <input type="text" class="form-control" name="landmark" placeholder="Landmark" value="{{ old('landmark')}}">
                      <label for="landmark">Landmark</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-floating my-3">
                     <a href="{{ route('cart.edit.address')}}"><button type="button" class="btn btn-primary">Edit Address</button></a>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-floating my-3">
                      <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Old password" value="{{ old('password')}}">
                      <label for="old_password">Password</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-floating my-3">
                      <a href="{{ route('user.change.password') }}"> <button type="button" class="btn btn-primary">Change Password</button> </a>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="my-3">
                      <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection