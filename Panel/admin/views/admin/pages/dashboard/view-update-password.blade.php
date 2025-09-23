@extends('admin.layout.main_layout',["tabTitle" => config('i.service_name')." | Update Profile " ])
@section('page')
<div id="pageSideBar" class="pageSideBar">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav('pageSideBar')">×</a>
    @include('admin.pages.dashboard.navs.navs')
</div>
<div class="thepage">
	<div class="bread-cum">
		<h4 class="fw-bold py-3 mb-4 ">
			<a href="{{ url('admin/dashboard') }}"> Admin </a> /
			<span class="text-muted"> Dashboard</span> /
			<span class="text-muted"> Change Password </span>
		</h4>
	</div>
	<div id="defaultPage" class="pages">
		<div class="card rounded-0 pb-3">
			@if ($data['item'] != null)
				<div class="d-none d-md-block">
					@include('admin.pages.dashboard.navs.navs')
				</div>
				<div class="d-block d-md-none">
					<div class="d-flex flex-row justify-content-end align-items-center p-2">
						<span style="font-size:30px;cursor:pointer" onclick="openNav('pageSideBar')">&#9776;</span>
					</div>
				</div>
				<div class="">
					<h2 class="text-center text-uppercase fs-20 m-0 p-0 pt-2">
						Update User ({{  $data['item']->name }}) -  Change Password </h2>
					<hr class="m-0 mt-4 mb-3">
					<div class="p-3">
						@include('admin.pages.dashboard.includes.user_password')
					</div>
				</div>
			@else
				@include('common.view.NotFoundFragment')
			@endif
		</div>
	</div>
</div>
@endsection
