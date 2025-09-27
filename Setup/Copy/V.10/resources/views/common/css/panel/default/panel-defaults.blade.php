@php
    $r = \Route::getFacadeRoot()->current()->uri();
@endphp
<style type="text/css">
.note-editor .dropdown-toggle::after {
    all: unset;
}
.note-editor .note-dropdown-menu, .note-editor .note-modal-footer {
    box-sizing: content-box;
}
table.dataTable tr th.select-checkbox.selected::after {
    content: "âœ”";
    margin-top: -11px;
    margin-left: -4px;
    text-align: center;
    text-shadow: rgb(176, 190, 217) 1px 1px, rgb(176, 190, 217) -1px -1px, rgb(176, 190, 217) 1px -1px, rgb(176, 190, 217) -1px 1px;
}
.marque_title {

    width: 20%;
}

.marquee_top {
    width: 80%;
    overflow: hidden;
}
.dataTables_filter,.dataTables_paginate {

    text-align: right;
}

.panel-noti-header {

    background-color: #2980b9;
    text-align: center;
    padding: 0 8px;

}

.form-control {

    font-family: 'arial' !important;
}

.tool-box-dashed {

    border-bottom: 1px dashed gray;
}

thead, tbody, tfoot, tr, td, th {

    border-style: none;
}
</style>
<style type="text/css">
.taxCard * {
    font-size: 14px
}

.form-control {
    font-size: .9rem !important;
}

.dataTable th {
    font-size: .0.75rem !important;
}

.tooltip-inner {

    background-color: #283b91;
}

.tooltip-arrow:before {

    border-top-color: #283b91 !important;
}

.required {

    color: red;
}

.success {

    color: green;
}

.fieldset {

    border: 1px solid #FFD700;
    padding: 10px;
    position: relative !important;
    clear: both !important;
}

.legend {

    font-size: 14px;
    font-weight: bold;
    width: auto;
    padding: 0 12px;
    color: #FFD700;

}

.form-control:focus {
    border: 1px solid #FFD700 !important;
    outline: 0 none;
}

input::file-selector-button {
    font-weight: bold;
    color: dodgerblue;

}

.ta-h {

    height: 130px;
}

.button:focus {

    outline: 0 none;
}

.navbar-toggler:focus {
    box-shadow: none !important;
}

.layout-page {

    transition: all .05s;
}

.layout-menu {

    transition: margin-left 3s;
}

#lg_back,
#lg_open {

    display: none;
}

.input-group:focus-within {
    box-shadow: none !important;
}

.bg-menu-theme {
    background: rgb(9, 107, 121);
    background: linear-gradient(90deg, rgba(2, 0, 36, 1) 10%, rgba(2, 0, 36, 1) 100%);
}

.layout-navbar {
    background: rgb(2, 0, 36);
    background: linear-gradient(90deg, rgba(2, 0, 36, 1) 11%, rgba(2, 0, 36, 1) 100%);
}

.text-orange {
    color: orange !important;
}

.menu-link {

    color: white !important;
}

.dataTable tr th {
    background-color: #243545 !important;
    color: white !important;
}

.pageSideBar {
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

.pageSideBar a {

    text-decoration: none;
    font-size: 14px;
    display: block;
    transition: 0.3s;
}

.pageSideBar a:hover {
    color: #f1f1f1;
}

.pageSideBar .closebtn {
    position: absolute;
    top: 0;
    right: 25px;
    font-size: 36px;
    margin-left: 50px;
}
.menu-vertical .app-brand {
    padding: .3rem;
}

.table th {
    text-transform: none;
    font-size: 14px;
}

.table> :not(caption)>*>* {
    padding: 0.2rem 0.4rem;
    font-size: 12px;
}

.select2-container .select2-selection--multiple {
    padding: .4rem .4rem;
    border: 1px solid #d9dee3;
}

.select2-container--default .select2-results__option--selected {
    background-color: #ffab00;
    color: white;
}

.select2-container .select2-search--inline .select2-search__field {
    margin-top: 0;
    height: 20px;
}

.select2-container--default.select2-container--focus .select2-selection--multiple {
    border: 1px solid #FFD700 !important;
    outline: 0 none;
}

@if (in_array($r, []))
    @media (min-width: 1199.98px) {
        .layout-page {

            padding-left: 0 !important;
        }

        .layout-menu {

            margin-left: -17rem !important;
        }

        .bg-menu-theme .menu-inner>.menu-item.active:before {

            background: transparent !important;
        }

        #lg_back,
        #lg_open {

            display: block !important;
        }
    }
@endif

::-webkit-scrollbar {
    width: 3px;
}
::-webkit-scrollbar-track {
    background: #f1f1f1;
}
::-webkit-scrollbar-thumb {
    background: #888;
}
::-webkit-scrollbar-thumb:hover {
    background: #555;
}
.menu-inner {
    overflow-x: hidden !important;
}
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
}

.summernote {
    font-family: unset !important;
}

.p-link {

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

.thepdf {

    margin-left: -2000px;
}

.p-link-active .p-link-title {

    border-top: 3px solid #19ab9e;
}

.p-link:hover {

    color: #19ab9e !important;
}

.p-link-hr-active {

    color: #19ab9e;
}

.p-link-hr:hover {

    color: #19ab9e !important;
}

.card {

    display: block;
}

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

.tax-setup .nav-tabs .nav-link.active,
.tax-setup .nav-tabs .nav-item.show .nav-link {
    background-color: #71dd37;
    color: white;
}

.text-dark-muted {
    color: #cbcbcb !important;
}
</style>
