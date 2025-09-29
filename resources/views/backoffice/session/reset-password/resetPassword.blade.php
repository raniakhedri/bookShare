@extends('backoffice.layouts.user_type.guest')

@section('content')
    <style>
        :root {
            --primary-color: #F86D72;
            --primary-dark: #e55a5f;
        }

        .bg-gradient-primary {
            background: linear-gradient(87deg, #F86D72 0, #e55a5f 100%) !important;
        }

        .text-primary-custom {
            color: #F86D72 !important;
        }

        .text-gradient-primary {
            background: linear-gradient(87deg, #F86D72 0, #e55a5f 100%) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            background-clip: text !important;
            color: transparent !important;
            display: inline-block;
        }
    </style>

    <div class="page-header section-height-75">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                    <div class="card card-plain mt-8">
                        <div class="card-header pb-0 text-left bg-transparent">
                            <h4 class="mb-0 text-gradient-primary font-weight-bold">Change Password</h4>
                            <p class="mb-0">Enter your new password below</p>
                        </div>
                        <div class="card-body">
                            <form role="form" action="/admin/reset-password" method="POST">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                <div>
                                    <label for="email">Email</label>
                                    <div class="">
                                        <input id="email" name="email" type="email" class="form-control" placeholder="Email"
                                            aria-label="Email" aria-describedby="email-addon">
                                        @error('email')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div>
                                    <label for="password">New Password</label>
                                    <div class="">
                                        <input id="password" name="password" type="password" class="form-control"
                                            placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                                        @error('password')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div>
                                    <label for="password_confirmation">Confirm Password</label>
                                    <div class="">
                                        <input id="password-confirmation" name="password_confirmation" type="password"
                                            class="form-control" placeholder="Confirm Password"
                                            aria-label="Password-confirmation" aria-describedby="Password-addon">
                                        @error('password')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-primary w-100 mt-4 mb-0">Update
                                        Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                        <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6"
                            style="background-image:url('../assets/img/curved-images/curved6.jpg')"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection