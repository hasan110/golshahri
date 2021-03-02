<header class="navbar">
  <div class="container-fluid">
      <button class="navbar-toggler mobile-toggler hidden-lg-up" type="button">&#9776;</button>
      <ul class="nav navbar-nav hidden-md-down">
          <li class="nav-item">
              <a class="nav-link navbar-toggler layout-toggler" href="#">&#9776;</a>
          </li>
      </ul>
      <ul class="nav navbar-nav pull-left">
        <li class="nav-item">
            <a class="nav-link aside-toggle" href="#"><i class="icon-bell"></i><span class="tag tag-pill tag-danger">{{ $count }}</span></a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <img
                @if(!is_null(Auth::user()->image))
                src="{{ asset('uploads/'.Auth::user()->image) }}"
                @else
                src="{{ asset('assets/img/avatars/male-profile.jpg') }}"
                @endif
                class="img-avatar" alt="تصویر">
                <span class="">{{ Auth::user()->name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header text-xs-center">
                    <strong>تنظیمات</strong>
                </div>
                <router-link class="dropdown-item" :to="{name:'AdminProfile'}"><i class="fa fa-user"></i>پروفایل</router-link>
                <a class="dropdown-item" href="#"><i class="fa fa-wrench"></i> تنظیمات</a>
                <!--<a class="dropdown-item" href="#"><i class="fa fa-usd"></i> Payments<span class="tag tag-default">42</span></a>-->
                <div class="divider"></div>
                <a class="dropdown-item" href="{{ route('logout') }}"><i class="fa fa-lock"></i> خروج</a>
            </div>
        </li>
      </ul>
      <ul class="nav navbar-nav pull-left hidden-md-down">
          
          <li class="nav-item">
              <a class="nav-link navbar-toggler aside-toggle" href="#">&#9776;</a>
          </li>

      </ul>
  </div>
</header>