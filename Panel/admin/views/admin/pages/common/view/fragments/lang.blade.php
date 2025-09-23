<input type="hidden" id="lang" value="{{ json_encode(array_merge(Lang::get('common'),Lang::get('validation'))) }}" />
<input type="hidden" id="digits" value="{{ json_encode(Lang::get('digits')) }}" />
<input type="hidden" id="attributes" value="{{ json_encode(Lang::get('validation.attributes')) }}" />
<input type="hidden" id="locale" value="{{ app()->getLocale() }}" />