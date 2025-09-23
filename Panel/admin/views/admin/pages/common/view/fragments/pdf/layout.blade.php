<div id="{{ $pdf ?? 'pdf'}}">
    @isset($mark)
        @if ($mark=='yes')
        <p class="px-pdf-watermark d-none" data-style='{"opacity": 0.020}'> {{$waterMark ?? config('i.service_domain')}} </p> 
        @endif
    @endisset
    @include('common.view.pdf.header')
    @include('common.view.pdf.footer')
</div>