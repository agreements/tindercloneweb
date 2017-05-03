<?php use App\Components\Theme; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{{ csrf_token() }}}">
        <title>Dashboard</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="{{{asset('admin_assets')}}}/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- jvectormap -->
        <link rel="stylesheet" href="{{{asset('admin_assets')}}}/plugins_assets/jvectormap/jquery-jvectormap-1.2.2.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{{asset('admin_assets')}}}/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
            folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="{{{asset('admin_assets')}}}/css/skins/_all-skins.min.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="{{{asset('admin_assets')}}}/css/admin-custom.css">
        <link href="{{{asset('admin_assets')}}}/css/toastr.css" rel="stylesheet"/>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar sidebar-custom">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <!-- /.search form -->
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <li class="header admin-header-custom">{{{trans_choice('admin.control_panel',1)}}}</li>
                        <li class="active">
                            <a href="{{{ url('/admin/dashboard') }}}">
                            <i class="fa fa-dashboard"></i> <span>{{trans('admin.menu_dashboard')}}</span></i>
                            </a>
                        </li>
                        <li class="treeview">
                            <a href="{{{ url('/admin/updater') }}}">
                                <i class="fa fa-refresh"></i>
                                <span>{{trans('admin.menu_updates')}}</span></i>
                                <!-- <span class="label label-primary pull-right">4</span> -->
                            </a>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <!--Dummy Link-->
                                <i class="fa fa-users"></i>
                                <span>{{trans('admin.menu_user')}}</span><i class="fa fa-caret-down pull-right"></i>
                                <!-- <span class="label label-primary pull-right">4</span> -->
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{{ url('/admin/users/usermanagement') }}}"><i class="fa fa-circle-o"></i> {{trans('admin.menu_user_users')}}</a></li>
                                <li><a href="{{{ url('/admin/users/deactivate_usermanagement') }}}"><i class="fa fa-circle-o"></i> {{trans('admin.menu_user_deactivate_users')}}</a></li>
                                <li><a href="{{{url('admin/users/adminmanagement')}}}"><i class="fa fa-circle-o"></i> {{trans('admin.menu_user_admins')}}</a></li>
                            </ul>
                        </li>
                        <!-- <li>
                            <a href="#">
                              <i class="fa fa-users"></i> <span>Users</span> <small class="label pull-right bg-green">new</small>
                            </a>
                            </li> -->
                        <li class="treeview">
                            <a href="#">
                                <!--Dummy Link-->
                                <i class="fa fa-sticky-note-o"></i>
                                <span>{{trans('admin.menu_content')}}</span>
                                <i class="fa fa-caret-down pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                {{{Theme::render('admin_content_links')}}}
                                <li><a href="{{{ url('/admin/interests') }}}"><i class="fa fa-circle-o"></i> {{trans('admin.menu_content_interests')}}</a></li>
                                <li>
                                    <a href="{{{ url('/admin/profilefields') }}}">
                                        <i class="fa fa-refresh"></i>
                                        <span>{{trans_choice('admin.profilefields',0)}}</span></i>
                                        <!-- <span class="label label-primary pull-right">4</span> -->
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                            <i class="fa fa-usd"></i> <span>{{trans('admin.menu_economics')}}</span>
                            <i class="fa fa-caret-down pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{{ url('/admin/creditManage') }}}"><i class="fa fa-circle-o"></i> {{trans('admin.menu_economics_credit')}}</a></li>
                                <li><a href="{{{ url('/admin/financeSettings') }}}"><i class="fa fa-circle-o"></i> {{trans('admin.menu_economics_financial')}}</a></li>
                                <li><a href="{{{ url('admin/billing/superpower-histories') }}}"><i class="fa fa-circle-o"></i> {{{trans("admin.menu_superpower")}}}</a></li>
                                <li><a href="{{{ url('admin/billing/credit-histories') }}}"><i class="fa fa-circle-o"></i> {{{trans("admin.menu_credit")}}}</a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <!--Dummy Link-->
                                <i class="glyphicon glyphicon-cog"></i> <span>{{trans('admin.menu_settings')}}</span>
                                <i class="fa fa-caret-down pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{{ url('/admin/generalSettings') }}}"><i class="fa fa-circle-o"></i> {{trans('admin.menu_settings_general')}}</a></li>
                                <li><a href="{{{ url('/admin/settings/maxmind') }}}"><i class="fa fa-circle-o"></i> {{trans('admin.menu_settings_maxmind')}}</a></li>
                                <li><a href="{{{ url('/admin/settings/email') }}}"><i class="fa fa-circle-o"></i> {{trans('admin.menu_settings_email')}}</a></li>
                                <li><a href="{{{ url('/admin/settings/limitsettings') }}}"><i class="fa fa-circle-o"></i> {{trans_choice("admin.limit",1)}} {{trans("admin.setting")}}</a></li>
                                <li><a href="{{{ url('admin/settings/profile') }}}"><i class="fa fa-circle-o"></i>{{trans('admin.menu_settings_profile')}}</a></li>
                                <li><a href="{{{ url('/admin/settings/socialLoginSettings') }}}"><i class="fa fa-circle-o"></i> {{trans("admin.social_logins")}} {{trans("admin.setting")}}</a></li>
                                {{{Theme::render('admin_plugin_menu')}}}
                            </ul>
                        </li>
                        <!-- <li class="treeview">
                            <a href="#">
                              <i class="fa fa-paint-brush"></i> <span>Themes</span>
                              <i class="fa fa-caret-down pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                              <li><a href="#"><i class="fa fa-circle-o"></i> Simple tables</a></li>
                              <li><a href="#"><i class="fa fa-circle-o"></i> Data tables</a></li>
                            </ul>
                            </li> -->
                        <li>
                            <a href="{{{url('/admin/themes')}}}">
                                <i class="fa fa-paint-brush"></i> <span> {{trans('admin.menu_themes')}}</span>
                                <!-- <small class="label pull-right bg-red">3</small> -->
                            </a>
                        </li>
                        <li>
                            <a href="{{{url('/admin/plugins')}}}" >
                                <i class="fa fa-plug"></i> <span> {{trans('admin.menu_plugins')}}</span>
                                <!-- <small class="label pull-right bg-red">3</small> -->
                            </a>
                        </li>
                        <li class="treeview">
                            <a href="#" >
                            <i class="fa fa-flag"></i>
                            <span> {{trans('admin.menu_abuse')}}</span>
                            <i class="fa fa-caret-down pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{{ url('/admin/misc/userabuse') }}}"><i class="fa fa-circle-o"></i> {{trans('admin.menu_abuse_user')}}</a></li>
                                <li><a href="{{{ url('/admin/misc/photoabuse') }}}"><i class="fa fa-circle-o"></i> {{trans('admin.menu_abuse_photo')}}</a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="{{{ url('/admin/logout') }}}">
                            <i class="fa fa-dashboard"></i> <span> {{trans('admin.menu_logout')}}</span></i>
                            </a>
                        </li>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>
            @section('content')
            @show
        </div>
        <!-- jQuery 2.1.4 -->
        <script src="{{{asset('admin_assets')}}}/plugins_assets/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="{{{asset('admin_assets')}}}/js/bootstrap.min.js"></script>
        <!-- FastClick -->
        <script src="{{{asset('admin_assets')}}}/plugins_assets/fastclick/fastclick.min.js"></script>
        <!-- AdminLTE App -->
        <script src="{{{asset('admin_assets')}}}/js/app.min.js"></script>
        <!-- Sparkline -->
        <script src="{{{asset('admin_assets')}}}/plugins_assets/sparkline/jquery.sparkline.min.js"></script>
        <!-- jvectormap -->
        <script src="{{{asset('admin_assets')}}}/plugins_assets/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="{{{asset('admin_assets')}}}/plugins_assets/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
        <!-- SlimScroll 1.3.0 -->
        <script src="{{{asset('admin_assets')}}}/plugins_assets/slimScroll/jquery.slimscroll.min.js"></script>
        <!-- ChartJS 1.0.1 -->
        <script src="{{{asset('admin_assets')}}}/plugins_assets/chartjs/Chart.min.js"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="{{{asset('admin_assets')}}}/js/pages/dashboard2.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="{{{asset('admin_assets')}}}/js/demo.js"></script>
        <script src="{{{asset('admin_assets')}}}/js/toastr.js"></script>
        @section('scripts')
        @show
        <script type="text/javascript">
            $(document).ready(function()
            {
              toastr.options = {
                "closeButton": true
              };
             $("#sidebar-menu").click(function()
             {
                
             });
            });
        </script>
        <script type="text/javascript">
            $(document).ready(
               function(){
                   $('#fileInput').change(
                       function(){
                           if ($(this).val()) {
                               $('#set-logo-btn').css('display','inline'); 
                           } 
                       }
                       );
            
               });
        </script>
        <script type="text/javascript">
            $(document).ready(
               function(){
                   $('#fileInputOuter').change(
                       function(){
                           if ($(this).val()) {
                               $('#set-outerlogo-btn').css('display','inline'); 
                           } 
                       }
                       );
            
               });
        </script>
        <script type="text/javascript">
            $(document).ready(
               function(){
                   $('#fileInput1').change(
                       function(){
                           if ($(this).val()) {
                               $('#set-logo-btn1').css('display','inline'); 
                           } 
                       }
                       );
                   
               });
        </script>
        <script type="text/javascript">
            $(document).ready(
               function(){
                   $('#fileInput2').change(
                       function(){
                           if ($(this).val()) {
                               $('#set-logo-btn2').css('display','inline'); 
                           } 
                       }
                       );
                   
               });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
               checkSize();
            
               $(window).resize(checkSize);
            });
            
            function checkSize(){
               if ($(".admin-header-custom").css("display") == "none" ){
                
               
                
               $("li").hover(function(){
               $(".main-sidebar").css({"width":"230px"});  
               x=$("li").find('span');
               y=$("li").find('.fa-caret-down');
               x.show();
               y.show();
               
               }, function(){
               x.hide();
               y.hide();
               $(".main-sidebar").css({"width":"55px"});
               });
               }
            else
            {
               x=$("li").find('span');
               y=$("li").find('.fa-caret-down');
               x.show();
               y.show();
               $(".main-sidebar").css({"width":"230px"});
            }
            }
            
        </script>
        <script >
            $(document).ready(function()
            {
             x=$("#user-table_filter").find("input");
              x.css("background-color","rgba(0,0,0,0.13)");
             var y=$("#user-table_filter").find("input");
             y.attr("placeholder","Enter string to search");
             y.css("padding-left","10px");
             });
        </script>
        <script>
            $(document).ready(function()
            {
               $("li").each(function(){
               if(window.location.href.indexOf($(this).find('a').attr('href'))>-1)
               {
               $(this).addClass('active').siblings().removeClass('active');
               }
            });
            });
        </script>
        <script>
            $.ajaxSetup({
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
            })
        </script>
    </body>
</html>