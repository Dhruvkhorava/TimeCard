@extends('layouts.app')

@section('title', 'Create Client - Soft UI Dashboard')

@section('content')
<div class="row">
  <div class="col-lg-10 col-md-11 mx-auto">
    <div class="card shadow-lg border-0">
      <div class="card-header pb-0 text-left bg-transparent">
        <div class="row">
          <div class="col-md-8">
            <h3 class="font-weight-bolder text-info text-gradient mb-0">Register New Client</h3>
            <p class="text-sm text-secondary">Establish a new partnership account and contact details.</p>
          </div>
          <div class="col-md-4 text-end d-flex align-items-center justify-content-end">
            <div id="image-preview-container" class="avatar avatar-xl position-relative border-radius-lg shadow-sm preview-container">
              <img id="image-preview" src="{{ asset('img/team-2.jpg') }}" alt="preview" class="w-100 h-100 object-fit-cover">
              <i class="ni ni-shop text-secondary position-absolute top-50 start-50 translate-middle d-none" id="preview-placeholder"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form role="form" action="{{ route('client.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          
          <h6 class="text-uppercase text-xs font-weight-bolder text-info mb-3"><i class="ni ni-circle-08 me-2"></i>Client Identity</h6>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-control-label">Full Name</label>
                <div class="input-group mb-3">
                  <span class="input-group-text"><i class="ni ni-shop"></i></span>
                  <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Acme Corp" value="{{ old('name') }}">
                </div>
                @error('name') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-control-label">Contact Email</label>
                <div class="input-group mb-3">
                  <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                  <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="contact@acme.com" value="{{ old('email') }}">
                </div>
                @error('email') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="form-control-label">Client Logo / Image</label>
                <div class="input-group mb-3">
                  <span class="input-group-text"><i class="ni ni-image"></i></span>
                  <input type="file" name="profile_image" id="profile_input" class="form-control @error('profile_image') is-invalid @enderror">
                </div>
                @error('profile_image') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>          


          <h6 class="text-uppercase text-xs font-weight-bolder text-info mb-3 mt-4"><i class="ni ni-world-2 me-2"></i>Contact Details</h6>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-control-label">Office Phone</label>
                <div class="input-group mb-3">
                  <span class="input-group-text"><i class="ni ni-tablet-button"></i></span>
                  <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="+1 (555) 999-8888" value="{{ old('phone') }}">
                </div>
                @error('phone') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-control-label">Contact Role</label>
                <div class="input-group mb-3">
                  <span class="input-group-text"><i class="ni ni-badge"></i></span>
                  <input type="text" name="designation" class="form-control @error('designation') is-invalid @enderror" placeholder="e.g. CEO / Director" value="{{ old('designation') }}">
                </div>
                @error('designation') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-control-label">Business Sector</label>
                <div class="input-group mb-3">
                  <span class="input-group-text"><i class="ni ni-app"></i></span>
                  <input type="text" name="department" class="form-control @error('department') is-invalid @enderror" placeholder="e.g. Manufacturing" value="{{ old('department') }}">
                </div>
                @error('department') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="form-control-label">Company Address</label>
                <div class="input-group mb-3">
                  <span class="input-group-text"><i class="ni ni-pin-3"></i></span>
                  <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" placeholder="Enter full address" value="{{ old('address') }}">
                </div>
                @error('address') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-control-label">Status</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="ni ni-spaceship"></i></span>
                  <select name="status" class="form-control @error('status') is-invalid @enderror">
                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active Partner</option>
                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive / Archived</option>
                  </select>
                </div>
                @error('status') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="{{ route('client.index') }}" class="btn btn-outline-secondary btn-sm mb-0">
              <i class="ni ni-bold-left me-2"></i>Cancel Registration
            </a>
            <button type="submit" class="btn bg-gradient-info btn-lg mb-0 shadow-sm border-radius-lg px-5">
              Create Client
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
