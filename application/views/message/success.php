<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="full-window-body">
                <div class="v-center w-100">
                    <div class="text-center mt-5">
                        <?php
                        if (isset($type)) {
                            switch ($type) {
                                case 'regsuccess':
                                    echo "<h4 class='text-left'>You registration application has been submitted.Please follow the following steps. <br><br>
                                         1. Validate Your email address using the verification link sent to your email.<br><br>
                                         2. Login using your credential and submit the rest of your information for our Teacher verification process by our counselor.<br><br>
                                         3. Wait for getting your profile verified</h4>";
                                    break;
                                case 'user-verify':
                                    echo "<h4>{$msg}</h4>";
                                    break;
                                case 'userInfoUpdate':
                                    echo "<h4>'Your Information have been updated.'</h4>";
                                    break;
                                case 'jobpostsuccess':
                                    echo "<h4>{$msg}</h4>";
                                    break;
                                case 'campaign':
                                    echo "<h4 class='text-left'>You registration application has been submitted.Please follow the following steps. <br><br>
                                         1. Validate Your email address using the verification link sent to your email.<br><br>
                                         2. Login using your credential and submit the counseling requirements.<br><br>
                                         3. Wait for getting contacted by our counselor.</h4>";
                                    break;
                                case 'passwordchange':
                                default:
                                    echo '<h4>Your Password Change is successful. Thanks for being with dikkha.</h4>';
                                    break;
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--<section class="container mt-lg-5 h-50">
    <div class="row h-100 justify-content-center align-items-center">
        <div class="col-md-6 col-sm-6 col-xs-6 mt-5 border border-primary" style="background:url('https://s13639.pcdn.co/wp-content/uploads/2017/12/background-worst-password-blue.png');height:450px;color:white">
            <h4>Your Password Change is successful. Thanks for being with dikkha.</h4>
           
        </div>
        
    </div>
</section>-->


















