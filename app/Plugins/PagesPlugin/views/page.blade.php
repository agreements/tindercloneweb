<?php use App\Components\Theme; ?>
@extends(Theme::layout('master-nocols'))
				@section('content')
					@parent
						@if($body)
							{!! $body !!}
						@endif
				@endsection
@section('scripts')

@endsection
