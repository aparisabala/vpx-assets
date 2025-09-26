<div class="mt-3">
    @if ($errors->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ $errors->first('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if ($errors->has('failed'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $errors->first('failed') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if ($errors->has('cancelled'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ $errors->first('cancelled') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
</div>