<form id="frmUpdatePassword" autocomplete="off">
    <input type="hidden" value="{{ Auth::user()->uuid }}" name="uuid">
    <div class="row">
        <div class="col-md-4 offset-md-4">
            <div class="form-group text-left mb-3">
                <label class="form-label" for="old_password"> <b> Old Password   </b> <em class="required">*</em> <span
                        id="old_password_error"> </span></label>
                <div class="input-group">
                    <input type="password" class="form-control" name="old_password" id="old_password">
                </div>
            </div>
            <div class="form-group text-left mb-3">
                <label class="form-label" for="password"> <b> New Password </b> <em class="required">*</em> <span
                        id="password_error"> </span></label>
                <div class="input-group">
                    <input type="password" class="form-control" name="password" id="password">
                </div>
            </div>
            <div class="form-group text-left mb-3">
                <label class="form-label" for="confirm_password"> <b>  Confirm New Password </b> <em
                        class="required">*</em> <span id="confirm_password_error"> </span></label>
                <div class="input-group">
                    <input type="password" class="form-control" name="confirm_password" id="confirm_password">
                </div>
            </div>
            <div class="mb-3 mt-3 text-end">
                <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-save"></i> {{ Lang::get('common.btns.glob_update') }}
                </button>
            </div>
        </div>
    </div>
</form>
