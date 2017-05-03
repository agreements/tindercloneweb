<!DOCTYPE html>
<html>
    <head>
        <title>DatingFramework Installer v1.5</title>
        <link rel="stylesheet" href="{{{asset('css/bootstrap3.3.6.min.css')}}}">
        <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <script src="{{{asset('js/jquery1.12.0.min.js')}}}"></script>
        <script src="{{{asset('js/bootstrap3.3.6.min.js')}}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.3.0/zxcvbn.js"></script>
        <link rel="stylesheet" href="{{{asset('Install')}}}/css/installer_new.css">
    </head>
    <body>
        <div class="col-md-12 full-col">
            <div class="col-md-8 main-col_steps text-center">
                <div class="col-md-8 step_text_div">
                    <h3 class="text_left opacity_6">Welcome to DatingFramework Installer</h3>
                    <h6 class="text_left opacity_6">This will install DatingFramework Version 1.5</h6>
                </div>
                <div class="col-md-12 error_div">
                    @if(count($errors) > 0)
                    @foreach($errors as $error)
                    <div class="col-md-9 error_para_div">
                        <div class="row">
                            <i class="glyphicon glyphicon-remove-sign pull-left text_white"></i>
                            <p class="error-para">{{{$error}}}</p>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
                @if(count($errors) > 0)
                <form action = "{{{url('/installer')}}}" method = "GET">
                    <button type="submit" class="btn btn-warning next_btn outline_none check-btn">Check Again <i class="fa fa-undo next_icon"></i> </button> 
                </form>
                @else
                <form action = "{{{url('/installer')}}}" method = "POST">
                    {{csrf_field()}}
                    <input type = "hidden" name = "installation" value = "step1">
                    <button type="submit" class="btn btn-danger next_btn outline_none install-btn">Install<i class="fa fa-angle-right next_icon"></i> </button> 
                </form>
                @endif
            </div>
        </div>
    </body>
    <style type="text/css">
    body
     {
        background: url("{{{asset('Install')}}}/images/installer_welcome.jpg");
        background-repeat: no-repeat;
        background-attachment: fixed;
        height: auto;
        background-size:cover;
        background-position: center;
        
    }
   
    </style>
</html>