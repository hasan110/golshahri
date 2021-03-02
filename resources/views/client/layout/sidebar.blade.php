<div class="sidebar">
  <nav class="sidebar-nav">
      <ul class="nav">
          <li class="nav-item">
              <router-link class="nav-link" :to="{name:'Dashboard'}"><i class="icon-speedometer"></i> داشبورد </router-link>
              {{-- <span class="tag tag-info">جدید</span> --}}
          </li>
          <li class="nav-title">
            کاربری
          </li>
          <li class="nav-item">
              <router-link class="nav-link" :to="{name:'Admins'}"><i class="icon-user"></i>مدیران</router-link>
          </li>
          <li class="nav-item">
              <router-link class="nav-link" :to="{name:'Users'}"><i class="icon-people"></i>کاربران</router-link>
          </li>
          <li class="nav-item">
              <router-link class="nav-link" :to="{name:'Roles'}"><i class="icon-diamond"></i>نقش ها</router-link>
          </li>
          <li class="nav-item">
              <router-link class="nav-link" :to="{name:'Permissions'}"><i class="icon-like"></i>دسترسی ها</router-link>
          </li>
          {{-- 
          <li class="nav-title">
            مدیریت اپ
          </li>
          <li class="nav-item">
              <router-link class="nav-link" :to="{name:'SlideShows'}"><i class="icon-frame"></i>اسلایدشوها</router-link>
          </li>
          --}}
          <li class="nav-title">
            مدیریت آگهی
          </li>
          <li class="nav-item">
              <router-link class="nav-link" :to="{name:'Advertises'}"><i class="icon-docs"></i>لیست آگهی ها</router-link>
          </li>
      </ul>
  </nav>
</div>