@extends('layouts.app')

@section('title', 'My Profile - Soft UI Dashboard')

@section('content')
<div class="row">
  <div class="col-lg-10 col-md-11 mx-auto">
    <div class="card shadow-lg border-0">
      <div class="card-header pb-0 text-left bg-transparent">
        <div class="row">
          <div class="col-md-8">
            <h3 class="font-weight-bolder text-info text-gradient mb-0">My Profile</h3>
            <p class="text-sm text-secondary">Manage your personal information and security settings.</p>
          </div>
          <div class="col-md-4 text-end d-flex align-items-center justify-content-end">
            <div id="image-preview-container" class="avatar avatar-xl position-relative border-radius-lg shadow-sm" style="background: #f8f9fa; border: 2px dashed #dee2e6; overflow: hidden;">
              <img id="image-preview" src="{{ $user->profile_image_url }}" alt="preview" class="w-100 h-100 object-fit-cover">
              <i class="ni ni-camera-compact text-secondary position-absolute top-50 start-50 translate-middle d-none" id="preview-placeholder"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success text-white" role="alert">
                <strong>Success!</strong> {{ session('success') }}
            </div>
        @endif

        <form role="form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          
          <h6 class="text-uppercase text-xs font-weight-bolder text-info mb-3"><i class="ni ni-single-02 me-2"></i>Personal Profile</h6>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-control-label">Full Name</label>
                <div class="input-group mb-3">
                  <span class="input-group-text"><i class="ni ni-single-02"></i></span>
                  <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Michael Jordan" value="{{ old('name', $user->name) }}">
                </div>
                @error('name') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-control-label">Professional Email</label>
                <div class="input-group mb-3">
                  <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                  <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="michael@company.com" value="{{ old('email', $user->email) }}">
                </div>
                @error('email') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-control-label">Security Password <span class="text-xs text-secondary font-weight-normal">(Optional)</span></label>
                <div class="input-group mb-3">
                  <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                  <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Leave empty to keep current">
                </div>
                @error('password') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-control-label">Confirm Password</label>
                <div class="input-group mb-3">
                  <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                  <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                </div>
              </div>
            </div>
            <div class="col-md-4">
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

          <h6 class="text-uppercase text-xs font-weight-bolder text-info mb-3 mt-4"><i class="ni ni-hat-3 me-2"></i>Contact Details</h6>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="form-control-label">Phone Number</label>
                <div class="input-group mb-3">
                  <span class="input-group-text"><i class="ni ni-mobile-button"></i></span>
                  <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="+1 (555) 123-4567" value="{{ old('phone', $user->phone) }}">
                </div>
                @error('phone') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="form-control-label">Address</label>
                <div class="input-group mb-3">
                  <span class="input-group-text"><i class="ni ni-pin-3"></i></span>
                  <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" placeholder="Enter full address" value="{{ old('address', $user->address) }}">
                </div>
                @error('address') <p class="text-danger text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>
          
          <div class="row">
             <div class="col-md-6">
               <div class="form-group">
                 <label class="form-control-label">Job Designation</label>
                 <div class="input-group mb-3">
                   <span class="input-group-text"><i class="ni ni-badge"></i></span>
                   <input type="text" class="form-control" value="{{ $user->designation }}" disabled>
                 </div>
               </div>
             </div>
             <div class="col-md-6">
               <div class="form-group">
                 <label class="form-control-label">Assigned Department</label>
                 <div class="input-group mb-3">
                   <span class="input-group-text"><i class="ni ni-building"></i></span>
                   <input type="text" class="form-control" value="{{ $user->department }}" disabled>
                 </div>
               </div>
             </div>
           </div>

          <div class="d-flex justify-content-end align-items-center mt-4">
            <button type="submit" class="btn bg-gradient-info btn-lg mb-0 shadow-sm border-radius-lg px-5">
              Update Profile
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
    document.getElementById('profile_input').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById('image-preview');
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                document.getElementById('preview-placeholder').classList.add('d-none');
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    @if(!$user->profile_image)
        document.getElementById('image-preview').classList.add('d-none');
        document.getElementById('preview-placeholder').classList.remove('d-none');
    @endif
</script>
@endpush
