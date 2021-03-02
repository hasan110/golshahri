<nav class="navbar navbar-expand-lg navbar-light site-nav">
  <div class="container-fluid">
    <router-link class="navbar-brand" :to="{name:'Home'}">
    <img height="40px" src="{{ asset('client/assets/images/4.png') }}" alt="logo">
    </router-link>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <router-link class="nav-link" :to="{name:'Home'}">خانه</router-link>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" :to="{name:'Login'}">ورود</router-link>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" :to="{name:'Register'}">ثبت نام</router-link>
      </li>
      <li class="nav-item nav-left-btn">
        <router-link title="ثبت آگهی" class="btn btn-legendary" :to="{name:'CreateAdvertise'}">ثبت آگهی</router-link>
      </li>
        <!-- <li class="nav-item dropdown">
        <a class="nav-link" href="#" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Dropdown
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <a class="dropdown-item" href="#">Something else here</a>
        </div>
        </li> -->
    </ul>
    </div>
  </div>
</nav>