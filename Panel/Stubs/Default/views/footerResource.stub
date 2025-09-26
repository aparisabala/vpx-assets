<input type="hidden" id="language-pack" value="{{ json_encode(array_merge(Lang::get('common'),Lang::get('validation'))) }}" />
<input type="hidden" id="digits" value="{{ json_encode(Lang::get('digits')) }}" />
<input type="hidden" id="attributes" value="{{ json_encode(Lang::get('validation.attributes')) }}" />
<input type="hidden" id="locale" value="{{ app()->getLocale() }}" />
@if(Auth::check()) 
    <input type="hidden" value="{{ Auth::user()->uuid }}" id="auth_uuid">
@endif
<div id="inflate" style="position: fixed;bottom: 50px;z-index: 500000;right: 10px;width: 300px;min-height: 0;background: transparent;">
</div>
<div  id="theGlobalLoader" class="theGlobalLoader">
    <div class="theGlobalLoaderImg">
      <div class="unselectable">
        <img src="{{ url('images/system/loader6.gif') }}" style="height: 100px;width: 100px">
      </div>
    </div>
</div>
<div  id="errorBase" class="errorBase">
	<div id="errorHeader">
		<i class="fa fa-times" style="padding: 16px;cursor: pointer;" aria-hidden="true" id="closeError"></i>
	</div>
	<div id="showErros" class="showErros">
	</div>
</div>
<div class="theDownloadLoader" id="theDownloadLoader">
	<div>
		<i class="fa fa-times" style="padding: 16px;cursor: pointer;" aria-hidden="true" id="closeDownload"></i>
	</div>
    <div class="theDownloadLoaderContent">
      <div class="unselectable">

        <div style="color: white;font-size: 22px;">
        	<img src="{{ url('images/system/loader6.gif') }}" style="height: 40px;width: 40px">
        	Creating Document, Do not close this window...
        </div>
      </div>
    </div>
</div>
{!! $appScripts ?? '' !!}
