@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4" style="padding-top: 50px;"></div>
    <section class="my-account container">
      <h2 class="page-title">Change Password</h2>
      <div class="row">
        <div class="col-lg-3">
          @include('user.account-nav')
        </div>
        <div class="col-lg-9">
          <div class="page-content my-account__edit">
            <div class="my-account__edit-form">
              <form name="account_edit_form" action="{{ route('user.password.update') }}" method="POST" class="needs-validation" novalidate="">
                @csrf
                 @if(Session::has('success'))
                  <div class="alert alert-success alert-dismissable fade show" role="alert">
                      {{Session::get('success')}}
                  </div>
                @endif
                <div class="row">
                  <input type="hidden" name="id" value="{{ $user->id }}">                  
                  <div class="col-md-12">
                    <div class="my-3">
                      <h5 class="text-uppercase mb-0">Change Password</h5>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-floating my-3">
                      <input type="password" class="form-control" id="old_password" name="current_password" placeholder="Current Password" required="">
                      <label for="current_password">Current Password</label>
                    </div>
                    @error('current_password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="col-md-12">
                    <div class="form-floating my-3">
                      <input type="password" class="form-control" id="new_password" name="new_password"
                        placeholder="New password" required="">
                      <label for="account_new_password">New Password</label>
                    </div>
                    @error('new_password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="col-md-12">
                    <div class="form-floating my-3">
                      <input type="password" class="form-control" cfpwd="" data-cf-pwd="#new_password"
                        id="new_password_confirmation" name="new_password_confirmation"
                        placeholder="Confirm new password" required="">
                      <label for="new_password_confirmation">Confirm new password</label>
                      <div class="invalid-feedback">Passwords did not match!</div>
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