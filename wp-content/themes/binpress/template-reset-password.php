<?php
/**
 * Template Name: Reset Password
 */
/**
 * http://localhost/bensmiley/reset-password/?action=reset-password&key=c476f62d9d1d00450b77797b827ed2d74eb9166b&login=tina%40mailinator.com
 */
get_header('binpress');

$form_action = "reset-password";

// check if the url is valid
$validated_url = validate_reset_password_url( $_GET, $form_action );

if ( !$validated_url[ 'code' ] ) {
    // display the error message
    echo error_message_div( $validated_url[ 'msg' ] );
    die();
}

$user_data_object = $validated_url[ 'user_data_obj' ];

// on successful validation: clear the activation key
reset_activation_key( $user_data_object->user_email ); ?>

    <div class="row login-container">
        <div class="col-md-3 ">
        </div>
        <div class="col-md-6 col-md-offset-1">
            <h2> Reset Password</h2>
            <br>

            <form id="reset-password-form" class="login-form" method="post"  >
                <div class="row">
                    <div class="form-group col-md-10">
                        <label class="form-label">Email</label>

                        <div class="controls">
                            <div class="input-with-icon  right">
                                <i class=""></i>
                                <input type="text" name="user_email" id="user_email" class="form-control"
                                       value="<?php echo $_GET[ 'login' ]; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-10">
                        <label class="form-label">Password</label>
                        <span class="help"></span>

                        <div class="controls">
                            <div class="input-with-icon  right">
                                <i class=""></i>
                                <input type="password" name="user_pass" id="user_pass" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-10">
                        <label class="form-label">Confirm Password</label>
                        <span class="help"></span>

                        <div class="controls">
                            <div class="input-with-icon  right">
                                <i class=""></i>
                                <input type="password" name="confirm_password"
                                       id="confirm_password" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10">
                        <button class="btn btn-primary btn-cons pull-right m-t-10" id="btn-reset-password"
                                type="submit">Save
                        </button>
                    </div>
                </div>
                <input type="reset" id="btn-reset-form" style="display: none"/>

                <div id="display-reset-msg"></div>
            </form>
        </div>
        <div class="col-md-3 ">
        </div>
    </div>

<?php get_footer('binpress');