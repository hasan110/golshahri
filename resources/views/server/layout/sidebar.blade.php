<div class="sidebar">
  <nav class="sidebar-nav">
      <ul class="nav">
          <li class="nav-item">
              <router-link class="nav-link" :to="{name:'Dashboard'}"><i class="icon-speedometer"></i> داشبورد </router-link>
              {{-- <span class="tag tag-info">جدید</span> --}}
          </li>
          @if (auth()->user()->can('menu_advertise'))
          <li class="nav-item">
              <router-link class="nav-link" :to="{name:'Advertises'}"><i class="icon-docs"></i>لیست آگهی ها</router-link>
          </li>
          @endif
          @if (auth()->user()->can('menu_business'))
          <li class="nav-item">
              <router-link class="nav-link" :to="{name:'Businesses'}"><i class="icon-docs"></i>لیست کسب و کارها</router-link>
          </li>
          @endif
          @if (auth()->user()->can('menu_admin'))
          <li class="nav-item">
              <router-link class="nav-link" :to="{name:'Admins'}"><i class="icon-user"></i>مدیران</router-link>
          </li>
          @endif
          @if (auth()->user()->can('menu_user'))
          <li class="nav-item">
              <router-link class="nav-link" :to="{name:'Users'}"><i class="icon-people"></i>کاربران</router-link>
          </li>
          @endif
          @if (auth()->user()->can('menu_role'))
          <li class="nav-item">
              <router-link class="nav-link" :to="{name:'Roles'}"><i class="icon-diamond"></i>نقش ها</router-link>
          </li>
          @endif
          @if (auth()->user()->can('menu_permission'))
          <li class="nav-item">
              <router-link class="nav-link" :to="{name:'Permissions'}"><i class="icon-like"></i>دسترسی ها</router-link>
          </li>
          @endif
          
          {{-- <li class="nav-title">
            مدیریت سایت
          </li> --}}
          @if (auth()->user()->can('menu_setting'))
          <li class="nav-item">
              <router-link class="nav-link" :to="{name:'Setting'}"><i class="icon-settings"></i>تنظیمات</router-link>
          </li>
          @endif
          {{-- 
          <li class="nav-item">
              <router-link class="nav-link" :to="{name:'SlideShows'}"><i class="icon-frame"></i>اسلایدشوها</router-link>
          </li>
          --}}
      </ul>
  </nav>
</div>