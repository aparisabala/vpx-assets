<header class="px-pdf-header d-none" data-style='{"margin":[15,5,15,0]}'>
    <div class="row">
        <div data-col-size="1" data-style='{"alignment":"left","fontSize":"7"}'>{{ \Carbon\Carbon::now()->format('d/M/Y')}}</div>
        <div data-col-size="10"> 
            <h1 data-style='{"alignment":"center","fontSize":"10"}'>{{ config('i.service_name') }}</h1>
            <h6 data-style='{"alignment":"center","fontSize":"8"}'>
                {{ config('i.mother.union.name') ?? 'not found' }}, {{ config('i.mother.upazila.name') ?? 'not found' }}, {{ config('i.mother.district.name') ?? 'not found' }}
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
