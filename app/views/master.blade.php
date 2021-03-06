<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Ep </title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
        {{ HTML::style('//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css') }}
        {{ HTML::style('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css') }}
        <!-- Ionicons -->
        {{ HTML::style('//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') }}
        <!-- Date Picker -->
        {{ HTML::style('css/datepicker/datepicker3.css') }}
        <!-- Theme style -->
        {{ HTML::style('css/style.css') }}
        {{ HTML::style('css/semantic/feed.css') }}
        {{ HTML::style('css/semantic/comment.css') }}
        {{ HTML::style('css/semantic/button.css') }}
        {{ HTML::style('css/semantic/message.css') }}
        {{ HTML::style('css/custom.css') }}
        {{ HTML::style("//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css") }}
        {{ HTML::style("css/style.css") }}
        <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
        {{ HTML::style("css/jquery.fileupload.css") }}

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        {{ HTML::style('https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js') }}
        {{ HTML::style('https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js') }}
        <![endif]-->
        @yield('topScript')
    </head>

    <body class="skin-black">

        <!-- header logo: style can be found in header.less -->
        <header class="header">

            <a href="/feed" class="logo">
                <img src={{asset('img/small_logo.png')}}>
            </a>

            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <div class="navbar-right">
                    @include('components.navbar')
                </div>
            </nav>

        </header>

        <div class="wrapper row-offcanvas row-offcanvas-left">

            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                @include('components.sidebar')
                <!-- /.sidebar -->
            </aside>


            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">

                <!-- Main content -->
                <section class="content">
                    <!-- Main row -->
                    <div class="row">
                        <!-- Left col -->
                        <section class="col-lg-8">

                            @yield('feed')

                        </section><!-- /.Left col -->
                        <!-- Left col -->
                        <section class="col-lg-4">

                            @yield('right')

                        </section><!-- /.Left col -->

                        <!-- right col -->
                        <section class="col-lg-4">

                        </section><!-- right col -->

                    </div>
                </section><!-- /.content -->

            </aside><!-- /.right-side -->

        </div><!-- ./wrapper -->


        {{ HTML::script('http://code.jquery.com/jquery-2.1.0.min.js') }}
        {{ HTML::script('js/jquery.textarea_autosize.min.js') }}
        {{ HTML::script('js/jquery.jscroll.js') }}
        <script>
            $('textarea.js-auto-size').textareaAutoSize();
        </script>
        {{ HTML::script('//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js') }}
        {{ HTML::script('//code.jquery.com/ui/1.11.1/jquery-ui.min.js') }}

        <!-- datepicker -->
        {{ HTML::script('js/plugins/datepicker/bootstrap-datepicker.js') }}

        <!-- App js -->
        {{ HTML::script('js/app.js') }}

        @yield('bottom-script')
    </body>
</html>
