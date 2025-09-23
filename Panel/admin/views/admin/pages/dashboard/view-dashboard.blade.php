@extends('admin.layout.main_layout',["tabTitle" => config('i.service_name')." | Dashboard" ])
@section('page')
<div class="thepage">
	<div class="bread-cum">
		<h4 class="fw-bold py-3 mb-4">
			<span class="text-muted fw-light text-orange">
				B1 / 
				<span class="text-muted"> B2  </span> /
			</span>
		</h4>
	</div>
	<div id="defaultPage" class="pages">
		<div class="tool-box d-flex flex-row justify-content-end align-items-center">
		</div>
		<div class="card rounded-0 pb-3">
			{{-- pageNav --}}
			<h2 class="text-center text-uppercase fs-20 m-0 p-0 pt-2">  Welcome User  </h2>
			<hr class="m-0 mt-2">
			<div class="mt-2 p-2 p-md-4">
			</div>
		</div>
	</div>
</div>
@endsection
