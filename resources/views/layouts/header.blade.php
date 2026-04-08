<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
    navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
            </ol>
            <h6 class="font-weight-bolder mb-0">Dashboard</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                {{-- <div class="input-group">
                    <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="Type here...">
                </div> --}}
            </div>
            <ul class="navbar-nav  justify-content-end">
                <li class="nav-item dropdown px-2 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0 d-flex align-items-center"
                        id="dropdownProfileButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <div
                            class="avatar avatar-sm bg-gradient-primary rounded-circle me-sm-1 d-flex align-items-center justify-content-center shadow-sm">
                            <img src="{{ Auth::user()->profile_image_url }}" class="w-100 border-radius-sm"
                                alt="profile_image">
                        </div>
                        <span class="d-sm-inline d-none font-weight-bold ms-2">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end px-2 py-3 ms-n4" aria-labelledby="dropdownProfileButton">
                        <li class="px-3 py-2 mb-2 bg-gray-50 border-radius-md border-0">
                            <h6 class="text-xs font-weight-bolder text-uppercase text-secondary mb-1">Account</h6>
                            <p class="text-sm font-weight-bold mb-0 text-dark">{{ Auth::user()->name }}</p>
                            <p class="text-xxs text-secondary mb-0">
                                {{ strtolower(Auth::user()->getRoleNames()->first() ?? 'User') }}</p>
                        </li>
                        <li>
                            <a class="dropdown-item border-radius-md" href="{{ route('profile.index') }}">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <i class="fa fa-user me-3 text-sm opacity-6"></i>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            My Profile
                                        </h6>
                                    </div>
                                </div>
                            </a>
                        </li>
                        {{-- <li>
                            <a class="dropdown-item border-radius-md" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <i class="fa fa-cog me-3 text-sm opacity-6"></i>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            Settings
                                        </h6>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item border-radius-md" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <i class="fa fa-shield-alt me-3 text-sm opacity-6"></i>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            Security
                                        </h6>
                                    </div>
                                </div>
                            </a>
                        </li> --}}
                        <hr class="horizontal dark my-3">
                        <li>
                            <a class="dropdown-item border-radius-md text-danger" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <i class="fa fa-sign-out-alt me-3 text-sm opacity-10"></i>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-bold mb-1">
                                            Logout
                                        </h6>
                                    </div>
                                </div>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
                {{-- <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </a>
            </li> --}}
                {{-- <li class="nav-item px-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0">
                <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
              </a>
            </li> --}}
            </ul>
        </div>
    </div>
</nav>
