
@extends('admin.layouts.admin')
                @section('content')
                    @parent


 <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header content-header-custom">
          <h1 class="content-header-head">
            {{trans_choice('admin.financial_management',1)}}
            
          </h1>
          
        </section>

        <!-- Main content -->
      <section class="content">
        <div class="col-md-12 section-first-col">
          <div class="row">
            <div class="col-md-12 section-first">
              <h4 class="user-statistics"></h4>
               <div class="row">
                <div class="col-md-4">
                  <p class="total-users">{{trans_choice('admin.total',1)}} {{trans_choice('admin.revenue',1)}}</p>
                  <p class="total-users-count">{{{$totalRevenue}}}</p>
                </div>
                 <div class="col-md-4">
                  <p class="this-month">{{trans_choice('admin.this',1)}} {{trans_choice('admin.month',1)}} {{trans_choice('admin.revenue',1)}}</p>
                  <p class="total-users-count">{{{$thisMonthRevenue}}}</p>
                </div>
                 <div class="col-md-4">
                  <p class="today">{{trans_choice('admin.today',2)}} {{trans_choice('admin.revenue',1)}}</p>
                  <p class="total-users-count">{{{$dayRevenue}}}</p>
                </div>
               </div>
            </div>
          
            </div>

             <div class="row">


               <div class="col-md-4 three-col-chart">
                  <p class="add-credit-package-text">{{trans_choice('admin.share_gender',1)}}</p>
                  <div id="revenueShareGender"></div>
               </div>


               <div class="col-md-4 three-col-chart">
                  <p class="add-credit-package-text">{{trans_choice('admin.share_country',1)}}</p>
                  <div id="revenueShareCountry"></div>
               </div>


               <div class="col-md-4  three-col-chart">
                  <p class="add-credit-package-text">{{trans_choice('admin.share_payment',1)}}</p>
                  <div id="revenueSharePayment"></div>
               </div>
       


            </div>


            <div class="row">
            <div class="col-md-12 section-first">
            <p class="add-credit-package-text">{{trans_choice('admin.share_year',1)}}</p>
            <div id="curve_chart" ></div>
            </div>
            </div>




        </div>
      </section>
      </div>


    @endsection
    

    @section('scripts')
        @parent

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {

        var dataGender = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          @foreach($revenueShareGender as $key => $value)
            ['{{trans('custom_profile.'.$key)}}', {{{$value}}}],
          @endforeach
          
         
        ]);

        var dataCountry = google.visualization.arrayToDataTable([

          ['Task', 'Hours per Day'],
          @foreach($revenueShareCountry as $country => $count)
            [ '{{{$country}}}', {{{$count}}} ],
          @endforeach
         
        ]);

        var dataPayment = google.visualization.arrayToDataTable([
            
          ['Task', 'Hours per Day'],
          @foreach($revenueSharePayment as $gateway => $count)
            [ '{{{$gateway}}}', {{{$count}}} ],
          @endforeach
         
        ]);

        var optionsGender = {
          title: '{{trans_choice('admin.share_gender',1)}}'
        };

        var optionsCountry = {
          title: '{{trans_choice('admin.share_country',1)}}'
        };

        var optionsPayment = {
          title: '{{trans_choice('admin.share_payment',1)}}'
        };

        var chartGender = new google.visualization.PieChart(document.getElementById('revenueShareGender'));
        var chartCountry = new google.visualization.PieChart(document.getElementById('revenueShareCountry'));
        var chartPayment = new google.visualization.PieChart(document.getElementById('revenueSharePayment'));

        chartGender.draw(dataGender, optionsGender);
        chartCountry.draw(dataCountry, optionsCountry);
        chartPayment.draw(dataPayment, optionsPayment);
      }




      //monthly revenue
    

    </script>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['{{trans_choice('admin.year',1)}}', '{{trans_choice('admin.revenue',1)}}', ],

          @foreach($revenueYear as $revenue)
          ['{{{$revenue->year}}}', {{{$revenue->amount}}}],
          @endforeach
        ]);

        var options = {
          title: '{{trans_choice('admin.share_year',1)}}',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>

    <style type="text/css">

   
    .three-col-chart {
        background-color: #353E47;
    }

    </style>


    @endsection