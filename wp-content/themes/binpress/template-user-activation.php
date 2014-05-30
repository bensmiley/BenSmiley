<?php
/**
 * Template Name: User Activation
 */

get_header();

    // check if the url parameters are set propely
    $check_get_params = check_get_parameters($_GET);

    if( $check_get_params['code'] ){

        $user_check = check_user_exists($check_get_params['login']);

        if(!$user_check['code']){

            echo '
                <div class="row">
                <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-offset-1 col-xs-10">
                    <div class="error-container">
                        <div class="error-main">
                            <div class="error-description"> '.$user_check['msg'].'</div>
                           <!-- <div class="error-description-mini"> The page your looking for is not here </div>-->
                            <br>
                        </div>
                    </div>

                </div>
            </div>';
        }
        else{
            $validated_key = validate_activation_key($check_get_params);
            if($validated_key != 0){

                activate_user($check_get_params);
                set_welcome_mail_details($check_get_params);
                echo '
                         <!-- BEGIN CONTAINER -->
                    <div class="container">
                        <div class="row login-container column-seperation">
                            <div class="col-md-5 col-md-offset-1">
                                <h2> Congratulations</h2>
                                <p> Account verified.Please login</p>
                                <br>
                                <form id="login-form" class="login-form">
                                    <div class="row">
                                        <div class="form-group col-md-10">
                                            <label class="form-label">User Email</label>
                                            <div class="controls">
                                                <div class="input-with-icon  right">
                                                    <i class=""></i>
                                                    <input type="text" name="user_name" id="user_name" class="form-control"
                                                     value ="'.$check_get_params['login'].'">
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
                                                    <input type="password" name="user_password" id="user_password" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="control-group  col-md-10">
                                            <div class=""> <a href="#" data-toggle="modal" data-target="#myModal">Forgot Password?</a>&nbsp;&nbsp;
                                            </div>
                                            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog fgt_pass" style="top:25%;">
                                                    <div class="modal-content">
                                                        <div class="text-center padding-10">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            <i class="icon-credit-card icon-7x"></i>
                                                            <h4 id="myModalLabel" class="semi-bold">Forgot Password?</h4>
                                                            <p class="no-margin">No problem! Enter your email address here to receive a link to change password.</p>
                                                        </div>
                                                        <div class="tiles white padding-20">
                                                            <div class="row form-row">
                                                                <div class="col-md-12">
                                                                    <input type="text" class="form-control" placeholder="Email">
                                                                </div>
                                                            </div>
                                                            <div class="m-t-10">
                                                                <button type="button" class="btn btn-primary btn-cons">Submit</button>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <button class="btn btn-primary btn-cons pull-right"
                                                    type="submit" id="btn-login">Login</button>
                                        </div>
                                    </div>
                                </form>
                                <div id="display-login-msg"></div>
                                <br>
                            </div>
                        </div>
                    </div>';



            }
            else{
                echo '
                        <div class="row">
                        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-offset-1 col-xs-10">
                            <div class="error-container">
                                <div class="error-main">
                                    <div class="error-description">Invalid key</div>
                                   <!-- <div class="error-description-mini"> The page your looking for is not here </div>-->
                                    <br>
                                </div>
                            </div>

                        </div>
                    </div>';
            }

        }

    }
    else{
        echo '
                <div class="row">
                <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-offset-1 col-xs-10">
                    <div class="error-container">
                        <div class="error-main">
                            <div class="error-description"> '.$check_get_params['msg'].'</div>
                           <!-- <div class="error-description-mini"> The page your looking for is not here </div>-->
                            <br>
                        </div>
                    </div>

                </div>
            </div>';
    }


 get_footer(); ?>