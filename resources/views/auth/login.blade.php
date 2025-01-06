@extends('layouts.auth-master')

@section('content')
<div class="d-flex flex-column flex-root">
    <!--begin::Authentication - Sign-in -->
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <!--begin::Aside-->
        <div class="d-flex flex-column flex-lg-row-auto w-xl-600px positon-xl-relative">
            <!--begin::Wrapper-->
            <div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-600px scroll-y">
                <!--begin::Content-->
                <div class="d-flex flex-column text-center p-10 pt-lg-20">
                    <a href="#" class="mb-12">
                        <img alt="Logo" src="assets/media/logos/logo-1.svg" class="h-40px" />
                    </a>
                    <h1 class="fw-bolder fs-2qx pb-5 pb-md-10">Welcome to Metronic</h1>
                    <p class="fw-bold fs-2">Discover Amazing Metronic
                    <br />with great build tools</p>
                </div>
                <!--end::Content-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Aside-->
        <!--begin::Body-->
        <div class="d-flex flex-column flex-lg-row-fluid py-10">
            <!--begin::Content-->
            <div class="d-flex flex-center flex-column flex-column-fluid">
                <!--begin::Wrapper-->
                <div class="w-lg-500px p-10 p-lg-15 mx-auto">
                    <!--begin::Form-->
                    <form class="form w-100" method="POST" action="{{ route('login') }}">
                        @csrf
                        <!--begin::Heading-->
                        <div class="text-center mb-10">
                            <h1 class="text-dark mb-3">Sign In to Metronic</h1>
                            <div class="text-gray-400 fw-bold fs-4">New Here?
                            <a href="#" class="link-primary fw-bolder">Create an Account</a></div>
                        </div>
                        <!--end::Heading-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-10">
                            <label class="form-label fs-6 fw-bolder text-dark">Email</label>
                            <input class="form-control form-control-lg form-control-solid" type="email" name="email" autocomplete="off" />
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-10">
                            <label class="form-label fs-6 fw-bolder text-dark">Password</label>
                            <input class="form-control form-control-lg form-control-solid" type="password" name="password" autocomplete="off" />
                        </div>
                        <!--end::Input group-->
                        <!--begin::Actions-->
                        <div class="text-center">
                            <button type="submit" class="btn btn-lg btn-primary w-100 mb-5">
                                <span class="indicator-label">Continue</span>
                            </button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Body-->
    </div>
    <!--end::Authentication - Sign-in-->
</div>
@endsection
