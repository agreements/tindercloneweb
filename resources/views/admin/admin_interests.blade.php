@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{trans_choice('admin.interest',0)}} {{trans_choice('admin.manage',1)}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col user-section-first-col">

         <div class="row">
            <div class="col-md-12 section-first">
              <h4 class="user-statistics">{{trans_choice('admin.interest',0)}} {{trans_choice('admin.statistic',1)}}</h4>
               <div class="row">
                <div class="col-md-4">
                  <p class="total-users">{{trans_choice('admin.overall',0)}}</p>
                  <p class="total-users-count">{{{$total}}}</p>
                </div>
                 <div class="col-md-4">
                  <p class="this-month">{{trans_choice('admin.interest',1)}} {{trans_choice('admin.use',2)}}</p>
                  <p class="total-users-count">{{{$totalthismonth}}}</p>
                </div>
                 <div class="col-md-4">
                  <p class="today"> {{trans_choice('admin.today',1)}} {{trans_choice('admin.user',2)}} {{trans_choice('admin.add',2)}} {{trans_choice('admin.interest',0  )}}</p>
                  <p class="total-users-count">{{{$totaltoday}}}</p>
                </div>
               </div>
            </div>
       
           
          </div>





          <div class="row">

            
              
               <div class="col-md-10 add-creditpackage-col add-interest-div">
                  <p class="add-credit-package-text">{{trans_choice('admin.add',0)}} {{trans_choice('admin.new',1)}} {{trans_choice('admin.interest',0)}}</p>
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.interest', 0)}}</label>
                     <input type="text" id = "interest_text" placeholder="{{trans_choice('admin.enter', 0)}} {{trans_choice('admin.interest', 0)}} {{trans_choice('admin.text', 0)}}" name = "name" class="form-control  input-border-custom">
                  </div>
                  <button type="button" id = "add_interest_btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.add',0)}}</button>
               </div>
            </form>

          </div>



          <div class = "row">


            <div class="col-md-12 user-dropdown-col">
               <div class="table-responsive">
                  <div class="col-md-12 col-table-inside">
                     <p class="current-credit-head admin-interest-checkbox">{{trans_choice('admin.interest',1)}} {{trans_choice('admin.list',0)}}</p>
                     
                        
                  </div>
                  <table class="table" id="interest-table">
                     <thead>
                        <tr>
                           <th>{{trans_choice('admin.interest',0)}}</th>
                           <th>{{trans_choice('admin.no_of', 0)}}</th>
                           <th>{{trans_choice('admin.added_on', 1)}}</th>
                           <th>{{trans_choice('admin.action', 1)}}</th>
                        </tr>
                     </thead>
                     <tbody>

                     @if(count($interests) > 0)
                     @foreach ($interests as $interest)
                        <tr id = "interest_row_{{{$interest->id}}}">
                           <td>{{{ $interest->interest}}}</td>
                           <td>{{{ $interest->userinterests->count()}}}</td>
                           <td>{{{ $interest->created_at}}}</td>
                           <td>
                              <div class="dropup dropdown-custom-left">
                                 <button class="btn btn-primary dropdown-toggle user-dropdowntoggle-button" type="button" data-toggle="dropdown"><i class="material-icons material-morevert-custom">more_vert</i></button>
                                 <ul class="dropdown-menu interest_list_dropup">
                                    <li class="delete-interest" data-interest-id ="{{{$interest->id}}}" data-interest-text = "{{{$interest->interest}}}"><a href="javascript:;">{{trans_choice('admin.delete', 0)}}</a></li>
                                 
                                 </ul>
                              </div>
                           </td>
                        </tr>
                     @endforeach
                     @else
                        <tr >
                           <td id = "no_row" colspan = "5" style = "text-align : center; color : red">{{trans_choice('admin.no_record', 0)}}</td>
                        </tr>
                     @endif
                     </tbody>
                  </table>
                  {!! $interests->render() !!}
               </div>
            </div>


          </div>
         


      </div>
   </section>






</div>
@endsection
@section('scripts')


<script>


$('#add_interest_btn').click(function(e){

  e.preventDefault();

  var interest = $('#interest_text').val();
  
  if(interest == '') {

    toastr.error('{{ trans('admin.require_attr', ['attr' => trans_choice('admin.interest',0)]) }}.');

  } else if(interest.length < 4 || interest.length > 100) {
    
    toastr.error('{{trans('admin.compare_len', ['attr'=>trans_choice('admin.interest', 0), 'min' => 4, 'max' => 100])}}');    

  } else {

    var data = {};
    data['interest'] = interest;
    data['_token'] = "{{{ csrf_token() }}}";

    $.post("{{{ url('admin/interests/addInterest') }}}", data, function(response){

      if(response.status == 'success') {

        toastr.success(interest + ' {{trans_choice('admin.add', 2)}} {{trans_choice('admin.success', 3)}}.');
        $('#interest_text').val('');
        
        $('#interest-table > tbody').prepend('<tr id = "interest_row_'+response.id+'">'+
                          '<td>'+interest+'</td>'+
                          '<td>0</td><td>'+ response.timestamp +'</td>'+
                          '<td><div class="dropup dropdown-custom-left">'+
                          '<button class="btn btn-primary dropdown-toggle user-dropdowntoggle-button" type="button" data-toggle="dropdown"><i class="material-icons material-morevert-custom">more_vert</i></button>'+
                          '<ul class="dropdown-menu interest_list_dropup">'+
                          '<li class="delete-interest" data-interest-id ="'+response.id+'" data-interest-text = "'+interest+'"><a href="javascript:;">DELETE</a></li>'+
                          '</ul></div></td></td></tr>'
        );
        $('#no_row').remove();
      } else if(response.status == 'existed') {

        toastr.warning('interest {{trans('admin.exist')}}.');

      } else if (response.status == 'error') {

        toastr.error('{{trans_choice('admin.failed',1)}} ' + interest);
      }

    });


  }

    

});




//delete interst 
$(document).on('click', '.delete-interest', function(){
  //$('#interest_row_'+$(this).data('interest-id')).remove();
  var id = $(this).data('interest-id');
  var text = $(this).data('interest-text');
  $.get("{{{url('/admin/interests/delete')}}}/"+id, function(response){

    if(response.status == 'success') {
      toastr.success(text + ' {{trans_choice('admin.delete',2)}} {{trans_choice('admin.success',3)}}.');
      $('#interest_row_'+id).remove();

    } else {

      toastr.error('{{trans_choice('admin.failed',2)}} ' + text-align);
    }

  });

});






</script>


<style type="text/css">
  
  .add-interest-div {
   
    width : 100%;
  }

  .interest_list_dropup{
     margin-left: -151px;
     background-color: #353E47;
   }

   .section-first-col{
    min-height: 0px;
  }

</style>

@endsection