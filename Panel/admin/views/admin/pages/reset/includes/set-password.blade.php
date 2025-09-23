<h4 class="mb-4 text-center fs-30 text-white"> SET NEW PASSWORD </h4>
<form id="frmChangePassword" class="mb-3" autocomplete="off" method="POST">
    <input type="hidden"  value="{{$user->uuid}}" name="user_uuid"/>
    <div class="form-group text-left mb-3">
        <label class="form-label text-white" for="password"> <b>  New Password </b> <em class="required">*</em> <span id="password_error"> </span></label>
        <div class="input-group">
            <input type='password' class="form-control" name="password" id="password" />
        </div>
    </div>
    <div class="form-group text-left mb-3">
        <label class="form-label text-white" for="password"> <b>  New Password </b> <em class="required">*</em> <span id="confirm_password_error"> </span></label>
        <div class="input-group">
            <input type='password' class="form-control" name="confirm_password" id="confirm_password" />
        </div>
    </div>
    <div class="mb-3">
        <button class="btn btn-primary d-grid w-100 text-white" type="submit"> CHANGE PASSWORD </button>
    </div>
</form>