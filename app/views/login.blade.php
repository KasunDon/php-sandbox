<style type="text/css">

    .form-signin
    {
        max-width: 330px;
        padding: 15px;
        margin: 0 auto;
    }
    .form-signin .form-control
    {
        position: relative;
        font-size: 16px;
        height: auto;
        padding: 10px;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
    .form-signin .form-control:focus
    {
        z-index: 2;
    }
    .form-signin input[type="text"]
    {
        margin-bottom: -1px;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }
    .form-signin input[type="password"]
    {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
    .account-wall
    {
        padding: 0px 0px 20px 0px;
        background-color: #ffffff;
        box-shadow: 0 2px 10px 0 rgba(0, 0, 0, 0.16);
    }
    .login-title
    {
        color: #555;
        font-size: 22px;
        font-weight: 400;
        display: block;
    }
    .profile-img
    {
        width: 96px;
        height: 96px;
        margin: 0 auto 10px;
        display: block;
        -moz-border-radius: 50%;
        -webkit-border-radius: 50%;
        border-radius: 50%;
    }
    .select-img
    {
        border-radius: 50%;
        display: block;
        height: 75px;
        margin: 0 30px 10px;
        width: 75px;
        -moz-border-radius: 50%;
        -webkit-border-radius: 50%;
        border-radius: 50%;
    }
    .select-name
    {
        display: block;
        margin: 30px 10px 10px;
    }

    .logo-img
    {
        width: 96px;
        height: 96px;
        margin: 0 auto 10px;
        display: block;
        -moz-border-radius: 50%;
        -webkit-border-radius: 50%;
        border-radius: 50%;
    }
</style>
<script src="https://apis.google.com/js/client:platform.js" async defer></script>
<script>
    function signinCallback(authResult) {
        if (authResult['status']['signed_in']) {
            // Update the app to reflect a signed in user
            // Hide the sign-in button now that the user is authorized, for example:
            document.getElementById('signinButton').setAttribute('style', 'display: none');
        } else {
            // Update the app to reflect a signed out user
            // Possible error values:
            //   "user_signed_out" - User is signed-out
            //   "access_denied" - User denied access to your app
            //   "immediate_failed" - Could not automatically log in the user
            console.log('Sign-in state: ' + authResult['error']);
        }
    }
</script>
<div class="row">
    <div class="col-md-12">
        <div class="account-wall">
            <div id="my-tab-content" class="tab-content">
                <div class="tab-pane active" id="login">
                    <form class="form-signin" action="" method="">
                        <div class="row-fluid">
                            <div class="col-md-1">
                                <span id="social-login">

                                    <div class="fb-login-button" data-max-rows="1" data-size="large" data-show-faces="false" data-auto-logout-link="false"></div>
                                    <div id="fb-root"></div>
                                    <script>
                                        (function(d, s, id) {
                                            var js, fjs = d.getElementsByTagName(s)[0];
                                            if (d.getElementById(id))
                                                return;
                                            js = d.createElement(s);
                                            js.id = id;
                                            js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.3";
                                            fjs.parentNode.insertBefore(js, fjs);
                                        }(document, 'script', 'facebook-jssdk'));
                                    </script>
                                </span>
                            </div>
                            <div class="col-md-2">
                                <span
                                    class="g-signin"
                                    data-callback="signinCallback"
                                    data-clientid="CLIENT_ID"
                                    data-cookiepolicy="single_host_origin"
                                    data-requestvisibleactions="http://schema.org/AddAction"
                                    data-scope="https://www.googleapis.com/auth/plus.login">
                                </span>
                            </div>
                        </div>
                        <br>
                        <input type="text" class="form-control" placeholder="Username" required autofocus>
                        <input type="password" class="form-control" placeholder="Password" required>
                        <input type="submit" class="btn btn-lg btn-default btn-block" value="Sign In" />
                    </form>
                    <div id="tabs" data-tabs="tabs">
                        <p class="text-center"><a href="#register" data-toggle="tab">Need an Account ?</a></p>
                    </div>
                </div>
                <div class="tab-pane" id="register">
                    <form class="form-signin" action="" method="">
                        <input type="text" class="form-control" placeholder="First Name ..." required autofocus>
                        <input type="email" class="form-control" placeholder="Emaill Address ..." required>
                        <input type="password" class="form-control" placeholder="Password ..." required>
                        <input type="submit" class="btn btn-lg btn-default btn-block" value="Sign Up" />
                    </form>
                    <div id="tabs" data-tabs="tabs">
                        <p class="text-center"><a href="#login" data-toggle="tab">Have an Account ?</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
