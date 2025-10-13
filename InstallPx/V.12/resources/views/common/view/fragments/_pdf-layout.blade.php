<div id="{{ $pdf ?? 'pdf'}}">
    @isset($mark)
        @if ($mark=='yes')
        <p class="px-pdf-watermark d-none" data-style='{"opacity": 0.020}'> {{$waterMark ?? config('i.service_domain')}} </p>
        @endif
    @endisset
    <header class="px-pdf-header d-none" data-style='{"margin":[15,5,15,0]}'>
        <div class="row">
            <div data-col-size="1" data-style='{"alignment":"left","fontSize":"7"}'>{{ \Carbon\Carbon::now()->format('d/M/Y')}}</div>
            <div data-col-size="10">
                <h1 data-style='{"alignment":"center","fontSize":"10"}'>{{ config('i.service_name') }}</h1>
                <h6 data-style='{"alignment":"center","fontSize":"8"}'>
                    {{ config('i.address') ?? 'not found' }}
                </h6>
            </div>
            <div data-col-size="1" class="px-pdf-page-count" data-style='{"alignment":"right","fontSize":"7"}'>*</div>
        </div>
        <div class="row">
            <div data-col-size="12" data-style='{"alignment":"left","fontSize":"8","margin":[0,3,0,0],"bold":true}'>
                {{ $docTitle ?? "No Title Found" }}
            </div>
        </div>
    </header>
    <footer class="px-pdf-footer d-none">
        <div class="row">
            <div data-col-size="5" data-style='{"alignment":"left","fontSize":"8","margin":[15,0,0,0]}'> Mantained By: {{config('i.company_name')}} </div>
            <div data-col-size="2"  data-style='{"alignment":"center","fontSize":"8","margin":[0,0,15,0]}' class="px-pdf-page-count"> * </div>
            <div data-col-size="5" data-style='{"alignment":"right","fontSize":"8","margin":[0,0,15,0]}'> Mantained By: {{config('i.company_name')}}</div>
        </div>
    </footer>
</div>
