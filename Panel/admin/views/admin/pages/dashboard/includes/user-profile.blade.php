<form id="frmUpdateSystemUser" autocomplete="off">
    <input type="hidden" name="created_by" value="{{ Auth::user()->uuid }}" />
    <input type="hidden" name="uuid" value="{{ $data['item']->uuid }}" />
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group text-left mb-3">
                                <label class="form-label"> <b> User Name  </b> <em class="required">*</em> <span id="name_error"> </span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="name" id="name" value="{{$data['item']->name}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group text-left mb-3">
                                <label class="form-label"> <b> Mobile Number </b> <em class="required">*</em> <span id="mobile_number_error"> </span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="mobile_number" id="mobile_number"  value="{{$data['item']->mobile_number}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group text-left mb-3">
                                <div class="d-flex flex-row justify-content-between">
                                    <label class="form-label" > <b> User Image </b> <em class="required">*</em> <span id="user_image_error"></span></label>
                                    <a href="{{ url('appsettings/image-editor') }}" target="_blank"><i class="fa fa-edit"></i></a>
                                </div>
                                <div class="input-group">
                                    <input type="file" class="form-control" name="user_image" id="user_image"/>
                                </div>
                                @if($data['item']->user_image != null)
                                    @php
                                        $path = 'uploads/app/'.config('i.service_domain').'/user/'.$data['item']->user_image;
                                    @endphp
                                    @if(file_exists($path))
                                    <p class=""> Uploaded Image <a href="{{ url($path) }}" download> Image </a></p>
                                    @endif
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group text-left mb-3">
                                <label class="form-label"> <b>  Email  </b> <em class="required">*</em> <span id="email_error"> </span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="email" id="email" value="{{$data['item']->email}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 mt-3 text-end">
                        <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-plus"></i>  Update  </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>