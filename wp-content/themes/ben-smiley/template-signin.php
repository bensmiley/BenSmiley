<?php 
/*template name: Signin-Signup */
get_header(); ?>
	<div class="container main-content">

		<div class="row">
        <div class="col span_5 login_main">
          <div class="login_header"><h2>Log in</h2></div>
          <br>
		  
		 <form id="login-form" class="login_form" action="" method="post">
		 <div class="row">
		 <div class="col span_12">
					<input type="text" placeholder="Username" name="txtusername" id="txtusername" class="form-control">
          </div>
          </div>
		  <div class="row">
          <div class="col span_12">
					<input type="password" placeholder="Password" name="txtpassword" id="txtpassword" class="form-control"> 
          </div>
          </div>
		  <div class="row">
          <div class="col span_12"><a class="span_3 margin_center" href="#">Forgot Password?</a>
          </div>
          </div>
          <div class="row">
            <div class="col span_12 margin_center">
              <button class="btn btn-primary" type="submit">Login</button>
            </div>
          </div>
		  </form>
		  <hr>
		  <div class="row m-t-10">
          <div class="col span_12">
		   <button class="btn btn-block btn-info span_8 margin_center" type="button">
            <span class="pull-left"><i class="icon-facebook"></i></span>
            <span class="bold">Login with Facebook</span> </button>
            </div></div>
           <div class="row">
          <div class="col span_12 m-b-20">
		   <button class="btn btn-block btn-success span_8 margin_center" type="button">
            <span class="pull-left"><i class="icon-twitter"></i></span>
            <span class="bold">Login with Twitter</span>
		    </button>
		    </div></div>
        </div>
        <div class="col span_5 "> <br>

        </div>
				
		</div><!--/row-->

	</div><!--/container-->

</div><!--/home-wrap-->
	
<?php get_footer(); ?>