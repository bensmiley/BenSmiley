<?php
/**
 * Template Name: User Activation
 */

get_header();

$form_action = "activate-user";

// check if the url is valid
$validated_url = validate_activation_url( $_GET, $form_action );

if ( !$validated_url[ 'code' ] ) {
    // display the error message
    echo error_message_div( $validated_url[ 'msg' ] );
    die();
}

$user_data_object = $validated_url[ 'user_data_obj' ];

// activate the user and set status to 0
activate_user( $user_data_object->user_email );

// insert details in db for sending welcome email to user and new-user signup mail to admin through cron
$user_data = array( 'user_email' => $user_data_object->user_email,
                    'user_name' => $user_data_object->display_name );
set_user_details_for_mail( $user_data, 'new-user-welcome' );
set_user_details_for_mail( $user_data, 'admin-newuser-notification' );
?>
    <!-- BEGIN CONTAINER -->
    <div class="container">
        <div class="row login-container column-seperation">
            <div class="col-md-12 text-center">
                <h2> Congratulations</h2>

                <p> Account is activated.</p>
                <br>
                <p>Click <a href="<?php echo site_url('home');?>">here</a> to sign in.</p>
            </div>
            <br>
        </div>
    </div>
    <!-- END CONTAINER -->
<?php
get_footer(); ?>