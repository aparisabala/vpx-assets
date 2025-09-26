<style>
    .breadcrumb-item + .breadcrumb-item::before{
        float: unset
    }
    .breadCum .breadcrumb-item {
        color:  white;
    }
      .sidebar {
        height: 100%;
        width: 0;
        position: fixed;
        z-index: 2000;
        top: 0;
        left: 0;
        background-color: white;
        overflow-x: hidden;
        transition: 0.5s;
        padding-top: 60px;
    }

    .sidebar a {

        text-decoration: none;
        font-size: 14px;
        display: block;
        transition: 0.3s;
    }

    .sidebar a:hover {
        color: #f1f1f1;
    }

    .sidebar .closebtn {
        position: absolute;
        top: 0;
        right: 25px;
        font-size: 36px;
        margin-left: 50px;
    }
    .table-bordered, .table-bordered thead tr th, .table-bordered tbody tr td {
        border: 1px solid #dee2e6;
    }
</style>
<style type="text/css">
    a {

        text-decoration: none;
    }

    .cursor-pointer {

        cursor: pointer;
    }

    .form-label {

        font-weight: bold;
    }

    .body * {

        font-family: kalpurush, Arial !important;
    }

    .p-link {

        color: #989898;
        transition: all .5s;
        display: inline-block;
        margin-right: 10px;
    }

    .sub-link {

        color: #989898;
        transition: all .5s;
        display: inline-block;
        margin-right: 10px;
    }

    .p-link-hr {

        color: #989898;
        display: block;
    }

    .p-link .p-link-title {

        padding: 6px 12px;
        font-weight: bold;
    }

    .p-link-active {

        color: #19ab9e;
        border-top: 3px solid #19ab9e;

    }

    .sub-link-active {

        color: #19ab9e;

    }

    .badge {
        text-transform: capitalize;
    }

    .thepdf {

        margin-left: -2000px;
    }

    .p-link-active .p-link-title {

        border-top: 3px solid #19ab9e;
    }

    .sub-link-active .sub-link-title {

        border-top: 3px solid #19ab9e;
    }

    .infoTable {
        border-collapse: initial !important;
       
    }
    .infoTable * {
        font-size: 12px !important;
    }

    .p-link:hover {

        color: #19ab9e !important;
    }

    .sub-link:hover {

        color: #19ab9e !important;
    }

    .p-link-hr-active {

        color: #19ab9e;
    }

    .sub-link-hr-active {

        color: #19ab9e;
    }

    .table-bordered, .table-bordered thead tr th, .table-bordered tbody tr td {
        border: 1px solid #dee2e6;
    }
    .p-link-hr:hover {

        color: #19ab9e !important;
    }

    .sub-link-hr:hover {

        color: #19ab9e !important;
    }

    .card {

        display: block;
    }
</style>
<style type="text/css">
    .bg-flat-color-1 {
        background: #00c292;
    }

    .bg-flat-color-2 {
        background: #ab8ce4;
    }

    .bg-flat-color-3 {
        background: #03a9f3;
    }

    .bg-flat-color-4 {
        background: #fb9678;
    }

    .bg-flat-color-5 {
        background: #66bb6a;
    }

    .bg-flat-color-6 {
        background: #5c6bc0;
    }

    .border-flat-color-1 {
        border: 1px solid #00c292;
    }
    .border-flat-color-2 {
        border: 1px solid #ab8ce4;
    }
    .border-flat-color-3 {
        border: 1px solid #03a9f3;
    }
    .border-flat-color-4 {
        border: 1px solid #fb9678;
    }
    .border-flat-color-5 {
        border: 1px solid #66bb6a;
    }
    .border-flat-color-6 {
        border: 1px solid #5c6bc0;
    }

    .border-orange {
        border: 1px solid rgb(228, 179, 88);
    }
    .border-orange:hover {
        border: 1px solid #cb780b;
        box-shadow: 0px 0px 15px -1px #cb780b;
    }

    .tax-setup .nav-tabs .nav-link.active,
    .tax-setup .nav-tabs .nav-item.show .nav-link {
        background-color: #71dd37;
        color: white;
    }

    .text-dark-muted {
        color: #cbcbcb !important;
    }

    .authentication-inner .input-group:focus-within .form-control,
    .authentication-inner .input-group:focus-within .input-group-text {
        box-shadow: 0 0 0 50px white inset;
    }

    .authentication-inner .was-validated .input-group .form-control:valid:focus,
    .authentication-inner .input-group .form-control.is-valid:focus {
        box-shadow: 0 0 0 50px white inset;
    }

    .authentication-inner input,
    .authentication-inner input:focus,
    .authentication-inner input:focus-within {
        background-color: transparent !important;
        -webkit-box-shadow: 0 0 0 50px white inset;
    }

    .log_base {

        width: 150px;
    }

    .log_icon {
        width: 40px;
    }

    .log_text {
        font-size: 16px;
    }

    @media (max-width: 575.98px) {
        .log_base {
            width: 100px;
        }

        .log_icon {
            width: 30px;
        }

        .log_text {
            font-size: 12px;
        }
    }
    .menu-inner li div {
        font-weight: 600;
    }
    .menu-inner li div,
    .menu-inner li i {
        font-size: 13px;
    }

    .title_bg {
        background: transparent url({{ url('app_assets/images/system/bgs/ttitle_bg.svg') }}) left top / cover;
    }
</style>

<style>
    .authentication-inner .input-group:focus-within .form-control,
    .authentication-inner .input-group:focus-within .input-group-text {
        box-shadow: 0 0 0 50px white inset;
    }

    .authentication-inner .was-validated .input-group .form-control:valid:focus,
    .authentication-inner .input-group .form-control.is-valid:focus {
        box-shadow: 0 0 0 50px white inset;
    }

    .authentication-inner input,
    .authentication-inner input:focus,
    .authentication-inner input:focus-within {
        background-color: transparent !important;
        -webkit-box-shadow: 0 0 0 50px white inset;
    }

    .log_base {

        width: 150px;
    }

    .log_icon {
        width: 40px;
    }

    .log_text {
        font-size: 16px;
    }

    @media (max-width: 575.98px) {
        .log_base {
            width: 100px;
        }

        .log_icon {
            width: 30px;
        }

        .log_text {
            font-size: 12px;
        }
    }

    .menu-inner li div,
    .menu-inner li i {
        font-size: 13px;
    }

    .title_bg {
        background: transparent url({{ url('statics/images/system/bgs/ttitle_bg.svg') }}) left top / cover;
    }
    .idcards #pdf * {
        font-size: 10px;
    }

    .bg-success {
        --bs-bg-opacity: 1;
        background-color: #1b4f00 !important;
    }

    .text-custom-success {
        --bs-bg-opacity: 1;
        color:#08672f !important;
    }

    .bg-cutom-color-purple {
        --bs-bg-opacity: 1;
        background-color: #1d094f !important;
    }

    .bg-cutom-color-red {
        --bs-bg-opacity: 1;
        background-color:#e10415 !important;
    }

    .text-custom-danger {
        --bs-bg-opacity: 1;
        color: #e10415 !important;
    }

    .income-bg {
        background-color: #08672f;
    }

    .expense-bg {
        background-color: #e10415;
    }
    .gross-bg {

        background-color: #28235b;
    }

    .gray-bg {

        background-color: #b1b1b1;
    }

    .bg-menu-theme .menu-link, .text-blue {
       
        color: #0105ff;
    }
    
    .gradeTable th, .gradeTable td {

        font-size: 7px !important;
    }
    .dashed-hr {
        border-bottom: 1px dashed gray;
    }

    .cardIcon {
        position: absolute;
        right: 5px;
        top: 5px;
        z-index: 1;
        opacity: .3;
    }
    .select2-results__options {

        text-align: left;
    }

    .legalDate {
        cursor: pointer;
    }
    .legalDate.active {
        background-color: orange !important;
    }

    .modal {
        text-align: left;
    }
    
</style>