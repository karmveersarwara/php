<div class="main-wrapper login-body">
    <div class="login-wrapper">
        <div class="container">
            <div class="loginbox">
                <div class="login-left">
                    <img class="img-fluid" src="assets/img/login.png" alt="Logo">
                </div>
                <div class="login-right">
                    <div class="login-right-wrap">
                        <div id="signIn_page">
                            <h1>Welcome to I Smart Media</h1>
                            <p class="account-subtitle">Need an account? <a href="javascript:void(0)" onclick="signUpPages()">Sign Up</a></p>
                            <h2>Sign in</h2>

                            <form action="javascript:void(0)" onsubmit="signUp(1)">
                                <div class="form-group">
                                    <label>Username <span class="login-danger">*</span></label>
                                    <input id="floatingUsername" class="form-control" type="text">
                                    <span class="profile-views"><i class="fas fa-user-circle"></i></span>
                                </div>
                                <div class="form-group"><!-- current-password -->
                                    <label>Password <span class="login-danger">*</span></label>
                                    <input id="floatingPassword" class="form-control pass-input" type="password" placeholder="">
                                    <span class="profile-views feather-eye-off  toggle-password"></span>
                                </div>
                                <div class="forgotpass">
                                    <div class="remember-me">
                                        <label class="custom_check mr-2 mb-0 d-inline-flex remember-me"> Remember me
                                            <input type="checkbox" name="radio">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <a href="#">Forgot Password?</a>
                                </div>
                                <div class="form-group">
                                    <button id="demo-form" class="btn btn-primary btn-block" type="submit">Login</button>
                                    <!-- <button class="g-recaptcha" data-sitekey="6LccTjUmAAAAAP7YBB7e3mmXFoDDz1_QNP6SQG95" data-callback='onSubmit' data-action='submit'>Submit</button> -->
                                </div>

                                <!-- <button type="submit" class="btn btn-primary py-3 w-100 mb-4"  onclick="signUp(0)" onclick="signUp(0)">Sign Up</button>
                            <p class="text-center mb-0">Already have an Account? <a href="#signIn" onclick="signInPages()">Sign In</a></p> -->

                            </form>

                        </div>

                        <div id="signUp_page">
                            <h1>Sign Up</h1>
                            <p class="account-subtitle">Enter details to create your account</p>
                            <form action="javascript:void(0)">
                                <!-- onsubmit="signUp(0)" -->
                                <div class="form-group">
                                    <label>Username <span class="login-danger">*</span></label>
                                    <input id="Username" class="form-control" type="text">
                                    <span class="profile-views"><i class="fas fa-user-circle"></i></span>
                                </div>
                                <div class="form-group">
                                    <label>Email <span class="login-danger">*</span></label>
                                    <input id="email" class="form-control" type="text">
                                    <span class="profile-views"><i class="fas fa-envelope"></i></span>
                                </div>
                                <div class="form-group">
                                    <label>Mobile <span class="login-danger">*</span></label>
                                    <input id="mobile" class="form-control" type="text">
                                    <span class="profile-views"><i class="fas fa-envelope"></i></span>
                                </div>
                                <div class="form-group">
                                    <label>Password <span class="login-danger">*</span></label>
                                    <input id="password" class="form-control pass-input" type="password" placeholder="new-password">
                                    <span class="profile-views feather-eye-off  toggle-password"></span>
                                </div>
                                <div class="form-group">
                                    <label>Confirm password <span class="login-danger">*</span></label>
                                    <input id="Confirm-Password" class="form-control pass-confirm" type="password" placeholder="conform-password">
                                    <span class="profile-views feather-eye-off  reg-toggle-password  "></span>
                                </div>
                                <div class=" dont-have">Already Registered? <a href="javascript:void(0)" onclick="signInPages()">Login</a></div>
                                <div class="form-group mb-0">
                                </div>
                            </form>
                            <button class="btn btn-primary btn-block" type="button" onclick="signUp(0)">Register</button>
                        </div>
                        <div class="login-or">
                            <span class="or-line"></span>
                            <span class="span-or">or</span>
                        </div>

                        <div class="social-login">
                            <a href="#"><i class="fab fa-google-plus-g"></i></a>
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>

    
    

        const signInPage = document.getElementById("signIn_page");
        const signUpPage = document.getElementById("signUp_page");
        function signUpPages() {
            signUpPage.style.display = 'block';
            signInPage.style.display = 'none';
        }

        function signInPages() {
            signInPage.style.display = 'block';
            signUpPage.style.display = 'none';
        }

        signInPages();
    // $(document).ready(function() {
        // var input = document.getElementsByClassName(".pass-input");
        // input.attr("type", "password");
    // });
</script>
