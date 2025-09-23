<h4 class="mb-4 text-center fs-30 text-white"> VERIFY RESET CODE </h4>
<form id="frmVerifyAdminCode" class="mb-3" autocomplete="off" method="POST">
    <input type="hidden"  value="{{$user->uuid}}" name="user_uuid"/>
    <div class="form-group text-left mb-3">
        <label class="form-label text-white" for="code"> <b>  Code </b> <em class="required">*</em> <span id="code_error"> </span></label>
        <div class="input-group">
            <input type='text' class="form-control" name="code" id="code" />
        </div>
    </div>
    <div class="mb-3">
        <button class="btn btn-primary d-grid w-100 text-white" type="submit"> VERIFY CODE </button>
    </div>
</form>