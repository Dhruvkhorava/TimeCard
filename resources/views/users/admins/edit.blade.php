@extends('layouts.app')

@section('title', 'Edit Admin - Soft UI Dashboard')

@section('content')
<div class="row">
  <div class="col-lg-10 col-md-11 mx-auto">
    <div class="card shadow-lg border-0">
      <div class="card-header pb-0 text-left bg-transparent">
        <div class="row">
          <div class="col-md-8">
            <h3 class="font-weight-bolder text-info text-gradient mb-0">Edit Admin: {{ $admin->name }}</h3>
            <p class="text-sm text-secondary">Update administrator profile and system permissions.</p>
          </div>
          <div class="col-md-4 text-end d-flex align-items-center justify-content-end">
            <div id="image-preview-container" class="avatar avatar-xl position-relative border-radius-lg shadow-sm" style="background: #f8f9fa; border: 2px dashed #dee2e6; overflow: hidden;">
              <img id="image-preview" src="{{ $admin->profile_image_url }}" alt="preview" class="w-100 h-100 object-fit-cover">
              <i class="ni ni-camera-compact text-secondary position-absolute top-50 start-50 translate-middle d-none" id="preview-placeholder"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form role="form" action="{{ route('admin.update', $admin->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          
          <h6 class="text-uppercase text-xs font-weight-bolder text-info mb-3"><i class="ni ni-single-02 me-2"></i>Account Identity</h6>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-control-label">Full Name</label>
                <div class="input-group mb-3">
                  <span class="input-group-text"><i class="ni ni-single-02"></i></span>
                  <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. John Doe" value="{{ old('name', $admin->name) }}">
                </div>
                @error('name') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-control-label">Email Address</label>
                <div class="input-group mb-3">
                  <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                  <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="john@company.com" value="{{ old('email', $admin->email) }}">
                </div>
                @error('email') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-control-label">Password <span class="text-xs text-secondary font-weight-normal">(Leave blank to keep current)</span></label>
                <div class="input-group mb-3">
                  <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                  <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••">
                </div>
                @error('password') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-control-label">Update Profile Image</label>
                <div class="input-group mb-3">
                  <span class="input-group-text"><i class="ni ni-image"></i></span>
                  <input type="file" name="profile_image" id="profile_input" class="form-control @error('profile_image') is-invalid @enderror">
                </div>
                @error('profile_image') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <h6 class="text-uppercase text-xs font-weight-bolder text-info mb-3 mt-4"><i class="ni ni-briefcase-24 me-2"></i>Professional Details</h6>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-control-label">Phone Number</label>
                <div class="input-group mb-3">
                  <span class="input-group-text"><i class="ni ni-mobile-button"></i></span>
                  <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="+1 (555) 000-0000" value="{{ old('phone', $admin->phone) }}">
                </div>
                @error('phone') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-control-label">Designation</label>
                <div class="input-group mb-3">
                  <span class="input-group-text"><i class="ni ni-badge"></i></span>
                  <input type="text" name="designation" class="form-control @error('designation') is-invalid @enderror" placeholder="e.g. Lead Administrator" value="{{ old('designation', $admin->designation) }}">
                </div>
                @error('designation') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-control-label">Department</label>
                <div class="input-group mb-3">
                  <span class="input-group-text"><i class="ni ni-building"></i></span>
                  <input type="text" name="department" class="form-control @error('department') is-invalid @enderror" placeholder="e.g. IT Operations" value="{{ old('department', $admin->department) }}">
                </div>
                @error('department') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="form-control-label">Address</label>
                <div class="input-group mb-3">
                  <span class="input-group-text"><i class="ni ni-pin-3"></i></span>
                  <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" placeholder="Enter full address" value="{{ old('address', $admin->address) }}">
                </div>
                @error('address') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-control-label">Account Status</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="ni ni-check-bold"></i></span>
                  <select name="status" class="form-control @error('status') is-invalid @enderror">
                    <option value="1" {{ old('status', $admin->status) == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $admin->status) == '0' ? 'selected' : '' }}>Suspended</option>
                  </select>
                </div>
                @error('status') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary btn-sm mb-0">
              <i class="ni ni-bold-left me-2"></i>Discard Changes
            </a>
            <button type="submit" class="btn bg-gradient-info btn-lg mb-0 shadow-sm border-radius-lg px-5">
              Update Administrator
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
