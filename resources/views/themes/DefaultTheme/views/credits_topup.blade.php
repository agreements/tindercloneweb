<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
	@if(isset($message)) {{{$message}}}
	@endif
  <form action="{{{ url('addCredits') }}}" method = "POST">
              <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
              <label>package:</label>
              <select name = "package">
                @foreach($packs as $pack)
                  <option value="{{{$pack->packageName}}}">{{{$pack->packageName}}} : {{{$pack->amount}}}  INR = {{{$pack->credits}}}</option>
                @endforeach
              </select>
              <label>Payment Gateway:</label>
              <select name = "gateway">
                  <option value="paypal">paypal</option>
                  <option value="stripe">stripe</option>
              </select>
              <input type="submit" value="Add Credits">
            </form>

            <br>
            <form action="{{{ url('activatesuperpowerpack') }}}" method = "POST">
              <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
              <label>SuperPower packages</label>
              <select name = "package">
                @foreach($superPowerPacks as $pack)
                  <option value="{{{$pack->id}}}">{{{$pack->package_name}}} : {{{$pack->amount}}}  INR = {{{$pack->credits}}}</option>
                @endforeach
              </select>
              <input type="submit" value="Add SuperPowers">
                {{{ session('activateSuperPowerPackStatus') }}}
       </form>

</body>
</html>
