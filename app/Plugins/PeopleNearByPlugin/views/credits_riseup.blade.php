@if($peoplenearby_only_superpowers != 'true')

	<div class="row spotlight_box">
	    <div class="card card--big">
	        <div class="card__image" style=
	            "background-image: url({{{$riseup_screenshot}}})"></div>
	        <h2 class="card__title"></h2>
	        <span class=
	            "card__subtitle">By Mattia Astorino</span>
	        <p class="card__text">{{{trans_choice('app.riseup_title',0)}}}</p>
	        <div class="card__action-bar">
	            <form role="form" method = "POST" action = "{{{ url('/riseup/pay') }}}" enctype="multipart/form-data">
	                {!! csrf_field() !!}
	                <button type="button" href="#0"  class="btn btn-default custom_modal-popup card__button cd-popup-trigger2"><span class="upgrade-now">{{{trans_choice('admin.activate',0)}}}</span></button>
	            </form>
	        </div>
	    </div>
	    <div class="col-md-4">
	        <h4>{{{trans_choice('app.riseup_title',0)}}}</h4>
	        <p>{{{trans_choice('app.riseup_title',1)}}}
	        </p>
	    </div>
	</div>

@endif