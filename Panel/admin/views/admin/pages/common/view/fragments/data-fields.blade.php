<input type="hidden" value="{{ config('a.lang') }}" id="l">
<input type="hidden" value="{{ config('i.service_name') }}" id="service_name">
<input type="hidden" value="{{ config('i.service_domain') }}" id="service_domain">
<input type="hidden" value="{{ config('i.uuid') }}" id="service_uuid">
@if(Auth::check())
    <input type="hidden" value="{{ Auth::user()->uuid }}" id="auth_uuid">
@endif
