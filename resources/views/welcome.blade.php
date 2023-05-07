<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SignUp</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5" style="max-width: 550px">
        <div class="alert alert-danger" id="error" style="display: none;"></div>
        
        <div class="alert alert-success" id="successAuth" style="display: none;"></div>
        <form id="signup">
        @csrf
            <!-- <input type="text" id="username" class="form-control" name="username" placeholder="">
            <h3>User Email</h3>
            <input type="text" id="email" class="form-control" name="email" placeholder="">
            <h3>User Password</h3>
            <input type="password" id="password" class="form-control" name="password" placeholder="">
            -->

            <label>Phone Number:</label>
            <input type="text" id="number" class="form-control" required="required" placeholder="+91 ********">
            <div id="recaptcha-container"></div>
            <button type="button" class="btn btn-primary mt-3" onclick="sendOTP();">Send OTP</button>
        </form>

        <div class="mb-5 mt-5">
            <div class="alert alert-success" id="successOtpAuth" style="display: none;"></div>
            <form id="verification-form" style="display: none;">
            <h3>Add verification code</h3>
                <input type="text" id="verification" class="form-control" placeholder="Verification code">
                <button type="button" class="btn btn-danger mt-3" onclick="verify()">Verify code</button>
            </form>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
    <!-- <script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script> -->
    <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>

    <script>
        var firebaseConfig = {
            apiKey: "AIzaSyCcuE0a3hzXC1C1ob5o93Nlw6vRY5zsZ6E",
            authDomain: "laravel-sigup.firebaseapp.com",
            databaseURL: "https://PROJECT_ID.firebaseio.com",
            projectId: "laravel-sigup",
            storageBucket: "laravel-sigup.appspot.com",
            messagingSenderId: "933160836440",
            appId: "1:933160836440:web:9905060733090b1a19992d",
            measurementId: "G-J8SD8ZQR19"
        };

    
        firebase.initializeApp(firebaseConfig);
    </script>
    <script type="text/javascript">
        window.onload = function () {
            render();
        };
        function render() {
            window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
            recaptchaVerifier.render();
        }
        function sendOTP() {
            var number = $("#number").val();
            phonenumber(number);
            // var username = $("#username").val();
            // var username = $("#email").val();
            // var username = $("#password").val();
            firebase.auth().signInWithPhoneNumber(number, window.recaptchaVerifier)
            .then(function (confirmationResult) {
                window.confirmationResult = confirmationResult;
                coderesult = confirmationResult;
                console.log(coderesult.verificationId);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '{{ route("save-token") }}',
                    type: 'POST',
                    data: {
                        token:coderesult.verificationId,
                        verificationId: coderesult.verificationId,
                        phone:number
                    },
                    dataType: 'html',
                    success: function (response) {  
                        $("#error").hide();
                        $("#successAuth").text("Message sent");
                        $("#successAuth").show();
                        $("#signup").hide();
                        $("#verification-form").show();
                    }
                                       
                });
               
                
            }).catch(function (error) {
                $("#error").text(error.message);
                $("#error").show();
            });
        }
        function verify() {
            var code = $("#verification").val();
            coderesult.confirm(code).then(function (result) {
                var user = result.user;
                console.log(user.phoneNumber);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '{{ route("update-token") }}',
                    type: 'POST',
                    data: {
                        phone:user.phoneNumber
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        alert(response);
                        alert('Token saved successfully.');
                        $("#error").hide();
                        $("#successAuth").hide("Message sent");
                        $("#successAuth").show();
                        $("#signup").hide();
                        $("#verification-form").show();
                                           }
                                          

                });
                $("#successOtpAuth").text("Auth is successful");
                $("#successOtpAuth").show();
            }).catch(function (error) {
                $("#error").text(error.message);
                $("#error").show();
            });
        }


        function phonenumber(phone)
            {   
                var phoneno = /^(?:(?:\+|0{0,2})91(\s*|[\-])?|[0]?)?([6789]\d{2}([ -]?)\d{3}([ -]?)\d{4})$/;
                if(phone.trim()=="")
                {
                    $("#error").text("Mobile number");
                    $("#error").show();
                }
                else if(phoneno.test(phone))
                        {
                          return true;
                        }
                    else
                        {
                            $("#error").text("Invalid number");
                            $("#error").show();
                        }
            }
    </script>
</body>
</html>