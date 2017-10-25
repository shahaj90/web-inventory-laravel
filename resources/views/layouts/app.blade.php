<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta charset="utf-8" />
        <title>Inventory - @yield('title')</title>
        <meta name="description" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <!-- bootstrap & fontawesome -->
        <link rel="stylesheet" href="{{asset('public/assets/css/bootstrap.min.css')}}" />
        <link rel="stylesheet" href="{{asset('public/assets/font-awesome/4.5.0/css/font-awesome.min.css')}}" />
        <!-- page specific plugin styles -->

        <!-- text fonts -->
        <link rel="stylesheet" href="{{asset('public/assets/css/fonts.googleapis.com.css')}}" />
        <!-- ace styles -->
        <link rel="stylesheet" href="{{asset('public/assets/css/ace.min.css')}}" class="ace-main-stylesheet" id="main-ace-style" />
        <link rel="stylesheet" href="{{asset('public/assets/css/ace-skins.min.css')}}" />
        <link rel="stylesheet" href="{{asset('public/assets/css/ace-rtl.min.css')}}" />
        <link rel="stylesheet" href="{{asset('public/assets/css/sweetalert2.min.css')}}" />
        <link rel="stylesheet" href="{{asset('public/assets/css/bootstrap-datepicker3.min.css')}}" />
        <!-- ace settings handler -->
        <script src="{{asset('public/assets/js/ace-extra.min.js')}}"></script>
    </head>

    <body class="no-skin">
        <div id="navbar" class="navbar navbar-default ace-save-state">
            <div class="navbar-container ace-save-state" id="navbar-container">
                <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
                    <span class="sr-only">Toggle sidebar</span>

                    <span class="icon-bar"></span>

                    <span class="icon-bar"></span>

                    <span class="icon-bar"></span>
                </button>

                <div class="navbar-header pull-left">
                    <a href="{{URL::to('/dashboard')}}" class="navbar-brand">
                        <small>
                            <!--<i class="fa fa-leaf"></i>-->
                            Dashboard
                        </small>
                    </a>
                </div>

                <div class="navbar-buttons navbar-header pull-right" role="navigation">
                    <ul class="nav ace-nav">
                        <li class="light-blue dropdown-modal">
                            <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                                <!--<img class="nav-user-photo" src="public/assets/images/avatars/user.jpg" alt="Jason's Photo" />-->
                                <span class="user-info">
                                    <small>Welcome,</small>
                                    {{Auth::user()->name}}
                                </span>

                                <i class="ace-icon fa fa-caret-down"></i>
                            </a>

                            <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                                <li>
                                    <a href="#">
                                        <i class="ace-icon fa fa-cog"></i>
                                        Settings
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <i class="ace-icon fa fa-user"></i>
                                        Profile
                                    </a>
                                </li>

                                <li class="divider"></li>

                                <li>
                                    <a href="{{URL::to('/logout')}}">
                                        <i class="ace-icon fa fa-power-off"></i>
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /.navbar-container -->
        </div>

        <div class="main-container ace-save-state" id="main-container">
            <script type="text/javascript">
try {
    ace.settings.loadState('main-container')
} catch (e) {
}
            </script>

            <div id="sidebar" class="sidebar responsive ace-save-state">
                <script type="text/javascript">
                    try {
                        ace.settings.loadState('sidebar')
                    } catch (e) {
                    }
                </script>

                <ul class="nav nav-list">
                    <li class="">
                        <a href="{{URL::to('/dashboard')}}">
                            <i class="menu-icon fa fa-tachometer"></i>
                            <span class="menu-text"> Dashboard </span>
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="">
                        <a href="{{URL::to('/category')}}">
                            <i class="menu-icon fa fa-certificate"></i>
                            <span class="menu-text"> Category </span>
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="">
                        <a href="{{URL::to('/product')}}">
                            <i class="menu-icon glyphicon glyphicon-th-list"></i>
                            <span class="menu-text"> Product </span>
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="">
                        <a href="{{URL::to('/invoice')}}">
                            <i class="menu-icon fa fa-shopping-cart"></i>
                            <span class="menu-text"> Invoice </span>
                        </a>
                        <b class="arrow"></b>
                    </li>
                    @if(Auth::User()->is_admin())
                    <li class="">
                        <a href="{{URL::to('/user')}}">
                            <i class="menu-icon fa fa-users"></i>
                            <span class="menu-text"> User </span>
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li class="">
                        <a href="{{URL::to('/setting')}}">
                            <i class="menu-icon fa fa-asterisk"></i>
                            <span class="menu-text"> Setting </span>
                        </a>
                        <b class="arrow"></b>
                    </li>
                    @endif

                    <!--                    <li class="">
                                            <a href="#" class="dropdown-toggle">
                                                <i class="menu-icon fa fa-list"></i>
                                                <span class="menu-text"> Tables </span>
                    
                                                <b class="arrow fa fa-angle-down"></b>
                                            </a>
                    
                                            <b class="arrow"></b>
                    
                                            <ul class="submenu">
                                                <li class="">
                                                    <a href="tables.html">
                                                        <i class="menu-icon fa fa-caret-right"></i>
                                                        Simple &amp; Dynamic
                                                    </a>
                    
                                                    <b class="arrow"></b>
                                                </li>
                    
                                                <li class="">
                                                    <a href="jqgrid.html">
                                                        <i class="menu-icon fa fa-caret-right"></i>
                                                        jqGrid plugin
                                                    </a>
                    
                                                    <b class="arrow"></b>
                                                </li>
                                            </ul>
                                        </li>-->

                </ul>
                <!-- /.nav-list -->

                <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
                    <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
                </div>
            </div>

            <div class="main-content">
                <div class="main-content-inner">
                    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                        @yield('breadcrumb')
                        <!-- /.breadcrumb -->
                    </div>

                    <div class="page-content">
                        <div class="ace-settings-container" id="ace-settings-container">
                            <div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
                                <i class="ace-icon fa fa-cog bigger-130"></i>
                            </div>

                            <div class="ace-settings-box clearfix" id="ace-settings-box">
                                <div class="pull-left width-50">
                                    <div class="ace-settings-item">
                                        <div class="pull-left">
                                            <select id="skin-colorpicker" class="hide">
                                                <option data-skin="no-skin" value="#438EB9">#438EB9</option>
                                                <option data-skin="skin-1" value="#222A2D">#222A2D</option>
                                                <option data-skin="skin-2" value="#C6487E">#C6487E</option>
                                                <option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
                                            </select>
                                        </div>
                                        <span>&nbsp; Choose Skin</span>
                                    </div>

                                    <div class="ace-settings-item">
                                        <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-navbar" autocomplete="off" />
                                        <label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
                                    </div>

                                    <div class="ace-settings-item">
                                        <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-sidebar" autocomplete="off" />
                                        <label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
                                    </div>

                                    <div class="ace-settings-item">
                                        <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-breadcrumbs" autocomplete="off" />
                                        <label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
                                    </div>

                                    <div class="ace-settings-item">
                                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" autocomplete="off" />
                                        <label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
                                    </div>

                                    <div class="ace-settings-item">
                                        <input type="checkbox" class="ace ace-checkbox-2 ace-save-state" id="ace-settings-add-container" autocomplete="off" />
                                        <label class="lbl" for="ace-settings-add-container">
                                            Inside
                                            <b>.container</b>
                                        </label>
                                    </div>
                                </div><!-- /.pull-left -->

                                <div class="pull-left width-50">
                                    <div class="ace-settings-item">
                                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-hover" autocomplete="off" />
                                        <label class="lbl" for="ace-settings-hover"> Submenu on Hover</label>
                                    </div>

                                    <div class="ace-settings-item">
                                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-compact" autocomplete="off" />
                                        <label class="lbl" for="ace-settings-compact"> Compact Sidebar</label>
                                    </div>

                                    <div class="ace-settings-item">
                                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-highlight" autocomplete="off" />
                                        <label class="lbl" for="ace-settings-highlight"> Alt. Active Item</label>
                                    </div>
                                </div><!-- /.pull-left -->
                            </div><!-- /.ace-settings-box -->
                        </div><!-- /.ace-settings-container -->
                        <div class="page-header">
                            @yield('pageHeader')
                        </div><!-- /.page-header -->

                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT BEGINS -->
                                @yield('content')
                                <!--<h1>Dashboard</h1>-->

                                <!-- PAGE CONTENT ENDS -->
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->

            <div class="footer">
                <div class="footer-inner">
                    <div class="footer-content">
                        <span class="bigger-120">
                            <span class="blue bolder">IT</span>
                            Park &copy; 2017
                        </span>

                        &nbsp; &nbsp;
                        <span class="action-buttons">
                            <!--                            <a href="#">
                                                            <i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
                                                        </a>
                            
                                                        <a href="#">
                                                            <i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
                                                        </a>
                            
                                                        <a href="#">
                                                            <i class="ace-icon fa fa-rss-square orange bigger-150"></i>
                                                        </a>-->
                        </span>
                    </div>
                </div>
            </div>

            <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
            </a>
        </div><!-- /.main-container -->

        <!-- basic scripts -->
<!--        <script>
            $(document).ready(function () {
                var url = window.location;
                $('ul.nav a[href="' + url + '"]').parentsUntil('.sidebar').addClass('active');
//                $('ul.nav a').filter(function () {
//                    return this.href == url;
//                }).parentsUntil('.nav').addClass('active');
            });
        </script>-->
        <!--[if !IE]> -->
        <script src="{{asset('public/assets/js/jquery-2.1.4.min.js')}}"></script>
        <script type="text/javascript">
                    var baseUrl = "{{URL::to('/') }}/";
                    if ('ontouchstart' in document.documentElement)
                        document.write("<script src='public/assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    //Check user
                    var user;
                    $.ajax({
                        url: baseUrl + "user/checkUsers",
                        type: 'GET',
                        dataType: 'json',
                        async: false,
                    }).done(function (response) {
                        user = response;
                    });
        </script>
        <script src="{{asset('public/assets/js/bootstrap.min.js')}}"></script>
        <!-- page specific plugin scripts -->
        <script src="{{asset('public/assets/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('public/assets/js/jquery.dataTables.bootstrap.min.js')}}"></script>
        <script src="{{asset('public/assets/js/dataTables.buttons.min.js')}}"></script>
        <script src="{{asset('public/assets/js/buttons.flash.min.js')}}"></script>
        <script src="{{asset('public/assets/js/buttons.html5.min.js')}}"></script>
        <script src="{{asset('public/assets/js/buttons.print.min.js')}}"></script>
        <script src="{{asset('public/assets/js/buttons.colVis.min.js')}}"></script>
        <script src="{{asset('public/assets/js/dataTables.select.min.js')}}"></script>
        <script src="{{asset('public/assets/js/validator.min.js')}}"></script>
        <script src="{{asset('public/assets/js/sweetalert2.min.js')}}"></script>
        <script src="{{asset('public/assets/js/bootstrap-datepicker.min.js')}}"></script>
        @yield('pageScript')
        <!-- ace scripts -->
        <script src="{{asset('public/assets/js/ace-elements.min.js')}}"></script>
        <script src="{{asset('public/assets/js/ace.min.js')}}"></script>
    </body>
</html>
