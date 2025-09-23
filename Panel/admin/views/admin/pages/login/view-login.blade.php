@extends('admin.layout.full_page_layout',["tabTitle" => "Admin Login | ".config('i.service_name') ])
@section('page')
<div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
        <h1 class="fs-20 mt-5 text-center text-white"> {{ (lang() == "en") ? config('i.service_name') : config('i.service_name_bn') }} </h1>
        <div class="card login_base">
            <div class="card-body">
                <div class="app-brand justify-content-center">
                    <a href="{{ url('/admin/login') }}" class="app-brand-link gap-2">
                        <img src="{{ config('i.logo') }}" style="width: 70px;">
                    </a>
                </div>
                <h4 class="mb-4 text-center fs-30  text-white"> Welcome Admin  </h4>
                @include('common.view.fragments.ErrorViewBS5')
                <form id="frmAdminLogin" class="mb-3" autocomplete="off" method="POST">
                    <div class="form-group text-left mb-3">
                        <label class="form-label text-white" for="mobile_number"> <b> Mobile Number </b> <em class="required">*</em> <span id="mobile_number_error"> </span></label>
                        <div class="input-group">
                            <input type='text' class="form-control" name="mobile_number" id="mobile_number" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="">
                            <div class="d-flex justify-content-between">
                                <label class="form-label text-white" for="password"><b> Password </b> <em class="required">*</em> <span id="password_error"> </span> </label>
                                <a href="{{ url('admin/reset') }}">
                                    <small>Forgot Password</small>
                                </a>
                            </div>
                            <div class="input-group">
                                <input type='password' class="form-control" name="password" id="password" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" />
                            <label class="form-check-label text-white" for="remember-me"> <b> Remember me </b> </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-primary d-grid w-100" type="submit"> Log In </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

