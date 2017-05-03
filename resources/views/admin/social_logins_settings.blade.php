@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header content-header-custom">
        <h1 class="content-header-head">{{trans_choice('admin.social_logins',1)}} {{trans_choice('admin.setting',2)}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col">
            <div class="row">
                <form action = "" method = "POST" id = "social_login_form">
                    {!! csrf_field() !!}
                    <div class="col-md-10 add-creditpackage-col admin-create-div">
                        <p class="add-credit-package-text">{{trans_choice('admin.set',1)}} {{trans_choice('admin.priority',1)}}</p>
                        <table class="table" id="interest-table">
                            <thead>
                                <tr>
                                    <th>{{trans_choice('admin.plugin_name',0)}}</th>
                                    <th>{{trans_choice('admin.priority_order', 0)}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($social_logins as $login)
                                <tr id = "">
                                    <td> <label class="package-label">{{{$login->name}}} :</label> </td>
                                    <td><input type="number" placeholder="{{trans_choice('admin.priority',0)}}" id = "{{{ $login->plugin_id }}}" name = "{{{ $login->plugin_id }}}" value = "{{{$login->priority}}}" class="input-border-custom"></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="form-group">
                            <label class="package-label"> {{trans_choice('admin.number_of_social_logins',0)}}</label>
                            <input type="number" id = "no_social_logins" placeholder="{{trans_choice('admin.number_of_social_logins',1)}}" value = "@if(isset($no_social_logins)){{{$no_social_logins}}}@endif" name = "no_social_logins" class="form-control  input-border-custom">
                        </div>
                        <div class="form-group">
                            <label class="package-label">{{trans('admin.only_social_logins_title')}}</label>
                            <label class="switch">
                            <input class="switch-input" type="checkbox" name="only_social_logins" @if($only_social_logins == 'true') checked @endif />
                            <span class="switch-label"></span> 
                            <span class="switch-handle"></span>
                            </label>
                        </div>
                        <button type="submit" id = "set-seo-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save',2)}}</button>
                    </div>
                </form>
            </div>
        </div>
</div>
</section>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    
    $(document).ready(function(){


        $("#social_login_form").on("submit", function(e){

            e.preventDefault();

            var formData = $(this).serializeArray();

            $.post("{{{url('/admin/settings/socialLoginSettings')}}}", formData, function(res){

                if(res.status == 'success') {

                    toastr.success("{{trans_choice('admin.set_status_message',0)}}");

                }

            });

        });



    });


</script>
<style type="text/css">
    .admin-create-div{
    width : 100%;
    }
    .block-switch{
    margin-left: 108%;
    margin-top: -21px;
    }
    .row {
    background-color: #38414A;
    }
    .section-first-col{
    min-height: 0px;
    }
</style>
@endsection