<!DOCTYPE html>
<html style="height:100vh;overflow:hidden;" lang="IR-fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="CoreUI Bootstrap 4 Admin Template">
    <meta name="author" content="Lukasz Holeczek">
    <meta name="keyword" content="CoreUI Bootstrap 4 Admin Template">
    <!-- <link rel="shortcut icon" href="assets/ico/favicon.png"> -->
    <title>پنل مدیریت</title>
    <link href="{{ asset('server/assets/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('server/assets/css/simple-line-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('server/assets/dest/style.css') }}" rel="stylesheet">
</head>
<body class="navbar-fixed sidebar-nav fixed-nav">
    <div id="arianaapp">
        @include('server.layout.header')
        @include('server.layout.sidebar')
        <main class="main">
            <loader-pannel></loader-pannel>
        </main>
        @include('server.layout.aside')
    </div>
    <script src="{{ asset('server/js/vue.js') }}"></script>
    <script src="{{ asset('server/assets/js/libs/jquery.min.js') }}"></script>
    <script src="{{ asset('server/assets/js/libs/tether.min.js') }}"></script>
    <script src="{{ asset('server/assets/js/libs/bootstrap.min.js') }}"></script>
    <script src="{{ asset('server/assets/js/libs/pace.min.js') }}"></script>
    <script src="{{ asset('server/assets/js/app.js') }}"></script>
    <script>
        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.remove-notification').click(function(event){
                id=$(this).attr("data-id");
                // event.preventDefault();

                $.ajax({
                    type:'post',
                    url:'{{route("notificationDelete")}}',
                    cache: false,
                    async: true,
                    data:{_token: "{{ csrf_token() }}",id:id},
                    success:function(data){
                        $("#"+id).remove();
                    }
                });
            });
        });
    </script>
</body>
</html>
