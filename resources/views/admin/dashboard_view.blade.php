@extends('admin.layouts.admin')
                @section('content')
                    @parent


 <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header content-header-custom">
          <h1 class="content-header-head">
            {{trans('admin.menu_dashboard')}}
            
          </h1>
          
        </section>

        <!-- Main content -->
      <section class="content">
        <div class="col-md-12 section-first-col">
          <div class="row">
            <div class="col-md-12 section-first">

              <h4 class="user-statistics">{{trans_choice('admin.user',2)}} {{trans_choice('admin.statistic',2)}}</h4>

               <div class="row">
                <div class="col-md-4">
                  <p class="total-users">{{trans_choice('admin.total',0)}} {{trans_choice('admin.user',1)}}</p>
                  <p class="total-users-count">{{{$totalSignUps}}}</p>
                </div>

                 <div class="col-md-4"> 
                  <p class="this-month">{{trans_choice('admin.this',1)}} {{trans_choice('admin.month',1)}}</p>

                  <p class="this-month-count">{{{$thisMonthSignUps}}}</p>
                </div>
                 <div class="col-md-4">
                  <p class="today">{{trans_choice('admin.today',2)}}</p>
                  <p class="today-count">{{{$daySignUps}}}</p>
                </div>
               </div>
            </div>
           <div class="col-md-12 section-second section-custom-change">
           <h4 class="user-statistics">{{{trans_choice('admin.global_user_stat', 0)}}}</h4>
           <div id="country-users" style="width: 100%; height: 600px;"></div>
           </div> 
            <div class="col-md-12 section-third section-custom-change">
            <h4 class="user-statistics">{{{trans_choice('admin.global_user_stat', 0)}}}</h4>
            <div id="signup-users" style="width: 100%; height: 600px;"></div>
           </div> 
          </div>
        </div>
      </section>
      </div>


    @endsection
    

    @section('scripts')
        @parent


        <script type="text/javascript"
          src="https://www.google.com/jsapi?autoload={
            'modules':[{
              'name':'visualization',
              'version':'1',
              'packages':['corechart']
            }]
          }"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["geochart"]});
      google.setOnLoadCallback(drawRegionsMap);

      function drawRegionsMap() {

        var data = google.visualization.arrayToDataTable([

          ['Country', 'Users'],
          @if(count($countrySignUps) > 0)
            @foreach($countrySignUps as $item)
              ['{{{$item->country}}}', {{{$item->count}}}],
            @endforeach
          @endif

        ]);

        var options = {
          backgroundColor: '#38414A',
          chartArea: {
                    backgroundColor: '#38414A'
                }};

        var chart = new google.visualization.GeoChart(document.getElementById('country-users'));

        chart.draw(data, options);
      }




    </script>
<script type="text/javascript">
      google.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['{{trans_choice('admin.month',1)}}', '{{trans_choice('admin.user',1)}}'],

          @foreach($monthlySignUps as $key => $value)
             [ '{{{$key}}}', {{{$value}}}],
          @endforeach
        ]);

        var options = {
          hAxis : { 
            textStyle : {
              fontSize: 12 // or the number you want
            }

          },
          vAxis : { 
            textStyle : {
              fontSize: 12 // or the number you want
            }

          },
          //width:1000,
          title: '{{trans_choice('admin.signup',1)}}',
          curveType: 'function',
          legend: { position: 'bottom' },
          backgroundColor: '#38414A',
          chartArea: {
                    backgroundColor: '#38414A'
                }
        };

        var chart = new google.visualization.LineChart(document.getElementById('signup-users'));

        chart.draw(data, options);
      }





    </script>


    @endsection