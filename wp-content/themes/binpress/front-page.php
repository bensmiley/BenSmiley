<?php
/**
 * binpress template for displaying the Front-Page
 *
 * @package WordPress
 * @subpackage binpress
 * @since binpress 1.0
 */

get_header(); ?>

    <!-- BEGIN CONTAINER -->
    <div class="container">
        <div class="row login-container column-seperation ">
            <div class="col-md-6 ">
                <h2>Sign Up For Our Free Version</h2>

                <p>You can upgrade to a paid plan in the backend when you need to.</p>
                <br>
                <div id="display-msg"></div>
                <!-- display error msg in div -->
                <!-- SIGN UP FORM END -->
                <form id="signup-form" class="login-form">
                    <div class="row">
                        <div class="form-group col-md-10">
                            <label class="form-label">Name</label>

                            <div class="controls">
                                <div class="input-with-icon  right">
                                    <i class=""></i>
                                    <input type="text" name="user_name" id="user_name" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-10">
                            <label class="form-label">Email</label>

                            <div class="controls">
                                <div class="input-with-icon  right">
                                    <i class=""></i>
                                    <input type="text" name="user_email" id="user_email" class="form-control">
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

                            <div class="controls">
                                <div class="input-with-icon  right">
                                    <i class=""></i>
                                    <input type="password" name="user_confirm_password" id="user_confirm_password"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="control-group  col-md-10">
                            <div class="checkbox checkbox check-success">
                                <input type="checkbox" id="agree_tc" value="1" name="agree_tc">
                                <label for="agree_tc">I Agree to all <a data-toggle="modal" data-target="#myModal1">Terms & Conditions</a> </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <button class="btn btn-primary ladda-button"
                                    data-style="expand-left" id="btn-signup">
                                <span class="ladda-label">Submit</span>
                            </button>
                            <span class="ajax-loader-signup" style="display: none"></span>
                            <input type="reset" id="btn-signup-form-reset" style="display: none"/>
                        </div>
                    </div>
                </form>
                <!-- SIGN UP FORM END -->

                <br>
            </div>
            <div class="col-md-5 col-md-offset-1">
                <h2> Already a Customer?</h2>

                <p> If you've got an account with us, please login.</p>
                <br>
                <div id="display-login-msg"></div>
                <!-- display error msg in div -->
                <!-- LOGIN FORM START -->
                <form id="login-form" class="login-form">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="form-label">User Email</label>

                            <div class="controls">
                                <div class="input-with-icon  right">
                                    <i class=""></i>
                                    <input type="text" name="user_email" id="user_email" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
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
                        <div class="control-group  col-md-12">
                            <div class=""><a href="#" data-toggle="modal" data-target="#myModal">
                                    <p>Forgot Password?</a>&nbsp;&nbsp;</p>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <span class="ajax-loader-login pull-right" style="display: none"></span>
                            <button class="btn btn-primary btn-cons pull-left"
                                    type="submit" id="btn-login">Login
                            </button>

                        </div>
                    </div>
                </form>
                <!-- LOGIN FORM END -->

            </div>
            <br>

            <!--FORGOT PASSWORD MODAL START -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog fgt_pass" style="top:25%;">
                    <div class="modal-content">
                        <div class="text-center padding-10">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <i class="icon-credit-card icon-7x"></i>
                            <h4 id="myModalLabel" class="semi-bold">Forgot Password?</h4>

                            <p class="no-margin">No problem! Enter your email address here to receive a link to change
                                password.</p>
                        </div>
                        <div class="tiles white padding-20">
                            <form id="forgot-password-form" class="login-form">
                                <div id="display-forgot-msg"></div>
                                <div class="row form-row">
                                    <div class="col-md-12">
                                        <div class="input-with-icon  right">
                                            <i class=""></i>
                                            <input type="text" class="form-control" placeholder="Email"
                                                   name="userEmail" id="userEmail">
                                        </div>
                                    </div>
                                </div>
                                <div class="m-t-10">
                                    <button type="button" class="btn btn-primary btn-cons"
                                            id="btn-forgot-pass">Submit
                                    </button>

                                    <input type="reset" id="btn-forgot-form-reset" style="display: none"/>


                                </div>
                            </form>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            </div>
            <!--FORGOT PASSWORD MODAL END -->
			
			<!--tERMS AND CONDITIONS-->
            <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog fgt_pass" style="top:25%;">
                    <div class="modal-content">
                        <div class="text-center padding-10">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <i class="icon-credit-card icon-7x"></i>
                            <h4 id="myModalLabel" class="semi-bold">Terms and Condtitions</h4>

                            <p class="no-margin">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                        </div>
                      <div class="tiles white padding-20">
                            <form id="terms-conditions" class="login-form">
                                
                                
                                <div class="m-t-10">
                                    <button type="button" class="btn btn-primary btn-cons"
                                            id="btn-terms-conditions">I Agree
                                    </button>

                                    <button type="button" class="btn btn-primary btn-cons pull-right"
                                            id="terms-conditions">Cancel
                                    </button>


                                </div>
                            </form>
                        </div>
					  </form>
					  
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            </div>
            <!--TERMS AND CONDITIONS END -->
        </div>
		<footer>
		<p class="pull-right">&copy; Bensmiley <?php echo date("Y");?></p>
		</footer>
    </div>
    <!-- END CONTAINER -->

<?php get_footer(); ?>