<!DOCTYPE html>
<html lang="en"dir="rtl">
 
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="CoreUI Bootstrap 4 Admin Template">
    <meta name="author" content="Lukasz Holeczek">
    <meta name="keyword" content="CoreUI Bootstrap 4 Admin Template">
    <!-- <link rel="shortcut icon" href="assets/ico/favicon.png"> -->
    <title>ورود به پنل مدیریت</title>
    <!-- Icons -->
    <link href="{{ asset('server/assets/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('server/assets/css/simple-line-icons.css') }}" rel="stylesheet">
    <!-- Main styles for this application -->
    <link href="{{ asset('server/assets/dest/style.css') }}" rel="stylesheet">
  </head>
 
  <body class="">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-8 col-xs-12 m-x-auto pull-xs-none vamiddle">
                <div class="card-group ">
                  <div class="card">
                    <form action="{{ route('submitLogin') }}" method="POST">
                      @csrf
                      <div class="card-block">
                          <h1>ورود</h1>
                          <p class="text-muted">وارد حساب کاربری خود شوید</p>
                          <div class="input-group m-b-1">
                              <span class="input-group-addon"><i class="icon-user"></i>
                              </span>
                              <input name="username" value="{{ old('username') }}" type="text" class="form-control en" placeholder="نام کاربری">
                          </div>
                          <div class="input-group m-b-2">
                              <span class="input-group-addon"><i class="icon-lock"></i>
                              </span>
                              <input name="password" type="password" class="form-control en" placeholder="رمز عبور">
                          </div>
                          <div class="row">
                              <div class="col-xs-6">
                                  <button type="submit" class="btn btn-primary p-x-2">
                                    <i class="icon-login"></i>
                                    ورود</button>
                              </div>
                          </div>
                          @if(\Session::has('Error'))
                          <div class="row mt-3">
                            <div class="alert alert-danger alert-dismissible fade in" role="alert">
                                <p>
                                  {{\Session::get('Error')}}
                                </p>
                            </div>
                          </div>
                          @endif
                      </div>
                    </form>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap and necessary plugins -->
    <script src="{{ asset('server/assets/js/libs/jquery.min.js') }}"></script>
    <script src="{{ asset('server/assets/js/libs/tether.min.js') }}"></script>
    <script src="{{ asset('server/assets/js/libs/bootstrap.min.js') }}"></script>
    <script>
        function verticalAlignMiddle()
        {
            var bodyHeight = $(window).height();
            var formHeight = $('.vamiddle').height();
            var marginTop = (bodyHeight / 2) - (formHeight / 2);
            if (marginTop > 0)
            {
                $('.vamiddle').css('margin-top', marginTop);
            }
        }
        $(document).ready(function()
        {
            verticalAlignMiddle();
        });
        $(window).bind('resize', verticalAlignMiddle);
    </script>
  </body>
 
</html>