@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4" style="padding-top: 50px;"></div>
    <section class="my-account container">
      <h2 class="page-title">Edit Address</h2>
      <div class="row">
        <div class="col-lg-3">
          @include('user.account-nav')
        </div>
        <div class="col-lg-9">
          <div class="page-content my-account__edit">
            <div class="my-account__edit-form">
              <form name="" action="{{ route('cart.update.address') }}" method="POST"  novalidate="">
                @csrf
                @method('PUT')
                 @if(Session::has('success'))
                  <div class="alert alert-success alert-dismissable fade show" role="alert">
                      {{Session::get('success')}}
                  </div>
                @endif
                <div class="row">
                  <input type="text" name="id" value="{{ $address->id }}">
                   <input type="text" name="user_id" value="{{ $address->user->id }}">
                  <div class="col-md-12">
                    <div class="my-3">
                      <h5 class="text-uppercase mb-0">Edit Address</h5>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-floating my-3">
                      <input type="text" class="form-control" name="sitio" placeholder="Sitio" value="{{ old('sitio', $address->sitio) }}">
                      <label for="sitio">Sitio</label>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-floating my-3">
                      <input type="text" class="form-control" name="barangay" placeholder="Barangay" value="{{ old('barangay', $address->barangay) }}">
                      <label for="barangay">Barangay</label>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-floating my-3">
                      <input type="text" class="form-control" name="city" placeholder="City or Municipality" value="{{ old('city', $address->city) }}">
                      <label for="city">City or Municipality</label>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-floating my-3">
                      <input type="text" class="form-control" name="landmark" placeholder="Landmark" value="{{ old('landmark',  $address->landmark) }}">
                      <label for="landmark">Landmark</label>
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