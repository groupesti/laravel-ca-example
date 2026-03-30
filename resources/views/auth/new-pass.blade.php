@extends("shared.base", ["title" => "New Password"])

@section("styles")
@endsection

@section("content")
    <!-- Start Page Content here -->
    <div class="flex min-h-screen items-center p-12.5">
        <div class="container">
            <div class="flex justify-center px-2.5">
                <div class="2xl:w-4/10 md:w-1/2 sm:w-2/3 w-full">
                    <div class="absolute end-0 top-0">
                        <img alt="auth-card-bg" src="/images/auth-card-bg.svg" />
                    </div>
                    <div class="absolute start-0 bottom-0 rotate-180">
                        <img alt="auth-card-bg" src="/images/auth-card-bg.svg" />
                    </div>
                    <div class="card p-7.5 rounded-2xl">
                        <!-- Auth Brand Logo -->
                        <div class="mb-3 flex flex-col items-center justify-center text-center">
                            <a class="auth-logo" href="{{ url("/") }}">
                                <img alt="logo" class="flex dark:hidden" src="/images/logo-black.png" />
                                <img alt="dark logo" class="hidden dark:flex" src="/images/logo.png" />
                            </a>
                            <p class="text-default-400 mx-auto mt-6 mb-4 w-full lg:w-3/4">We've emailed you a 6-digit verification code. Please enter it below to confirm your email address</p>
                        </div>
                        <form action="index.html">
                            <div class="mb-5">
                                <label class="form-label" for="userEmail">
                                    Email address
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input class="form-input" id="userEmail" placeholder="you@example.com" required="" type="email" />
                                </div>
                            </div>
                            <div class="mb-5">
                                <label class="form-label" for="userEmail">
                                    Enter your 6-digit code
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="two-factor flex gap-2">
                                    <input class="form-input text-center" required="" type="text" />
                                    <input class="form-input text-center" required="" type="text" />
                                    <input class="form-input text-center" required="" type="text" />
                                    <input class="form-input text-center" required="" type="text" />
                                    <input class="form-input text-center" required="" type="text" />
                                    <input class="form-input text-center" required="" type="text" />
                                </div>
                            </div>
                            <div class="mb-5" data-password="bar">
                                <label class="form-label" for="userPassword">
                                    Password
                                    <span class="text-danger">*</span>
                                </label>
                                <input class="form-input" id="userPassword" placeholder="••••••••" type="password" />
                                <div class="password-bar my-3"></div>
                                <p class="text-default-400 text-xs">Use 8+ characters with letters, numbers &amp; symbols.</p>
                            </div>
                            <div class="mb-5">
                                <label class="form-label" for="userNewPassword">
                                    Confirm New Password
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input class="form-input" id="userNewPassword" placeholder="••••••••" required="" type="password" />
                                </div>
                            </div>
                            <div class="mb-6">
                                <div class="flex items-center gap-2">
                                    <input class="form-checkbox form-checkbox-light size-4.5" id="termAndPolicy" type="checkbox" />
                                    <label class="form-check-label" for="termAndPolicy">Agree the Terms &amp; Policy</label>
                                </div>
                            </div>
                            <div>
                                <button class="btn bg-primary w-full py-3 font-semibold text-white hover:bg-primary-hover" type="submit">Update Password</button>
                            </div>
                        </form>
                        <p class="text-default-400 my-9 text-center">
                            Don’t have a code?
                            <a class="text-primary font-semibold underline underline-offset-3" href="#">Resend</a>
                            or
                            <a class="text-primary font-semibold underline underline-offset-3" href="#">Call Us</a>
                        </p>
                        <p class="text-default-400 text-center">
                            Return to
                            <a class="underline text-primary font-semibold" href="{{ url("/auth/sign-in") }}">Sign in</a>
                        </p>
                    </div>
                    <!-- Auth Footer -->
                    <p class="text-default-400 mt-7.5 text-center">
                        ©
                        <script>
                            document.write(new Date().getFullYear())
                        </script>
                        Paces - by
                        <span>Coderthemes</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- End Page content -->

    @include("shared.partials.customizer")

    <!-- Two Factor Validator Js -->

    <!-- Password Suggestion Js -->
@endsection

@section("scripts")
    @vite(["resources/js/pages/auth-two-factor.js"])
    @vite(["resources/js/pages/auth-password.js"])
@endsection
