
<script type="text/javascript"><!--mce:0-->
	function trim(s) 
	{
		while (s.substring(0,1) == ' ')
		 {
			 s = s.substring(1,s.length);
		 }
		 while (s.substring(s.length-1,s.length) == ' ')
		  {
			s = s.substring(0,s.length-1);
		  }
		
		return s;
	}

	function front_user_validate()
	{

		var usr=document.getElementById('user_name1').value;
		if((trim(document.getElementById('user_name1').value)=='') || (trim(document.getElementById('user_name1').value)=='Username'))
		{
			document.getElementById('login_error').innerHTML = '<font color="red">Please enter correct username and password</font>';
			document.getElementById('user_name1').focus();
			return false;
		
		}
		else if((trim(document.getElementById('user_pass1').value)=='') || (trim(document.getElementById('user_pass1').value)=='Password'))
		{
			document.getElementById('login_error').innerHTML = '<font color="red">Please enter correct username and password</font>';
			document.getElementById('user_pass1').focus();
			return false;
			
		}
		else
		{
				document.getElementById('login_error').innerHTML = '';
				return true;
		}
		
	}

</script>
<?php
	if($err == 1)
	{
		$txtUsername = $this->request->data['AdminMaster']['admin_user'];
		$txtPassword = $this->request->data['AdminMaster']['admin_pass'];
	}
	/*else if($txtusername && $txtpassword)
	{
		$txtUsername = $txtusername;
		$txtPassword = $txtpassword;
	}
	*/
	else
	{
		$txtUsername = 'Username';
		$txtPassword = 'Password';
	}
	//echo $this->element('sql_dump'); 
?>
<div id="login_container">
<div class="login_inner">
<div class="login_logodiv">
<img src="images/login_huracan_logo.png" title="Huracan" alt="Huracan"/>
</div>
<div class="login_wrap">
<?PHP echo $this->Form->create('AdminMaster', array('method' => 'post','id' => 'form', 'onsubmit' => 'return front_user_validate();')); ?>	    
<h1>Admin Login</h1>
<label>Username</label><?php echo $this->Form->input('admin_user', array('type' => 'text', 'value' => $txtUsername, 'id' => 'user_name1', 'maxlength' => '30','div' => false, 'label' => false, 'onblur' => 'if(this.value == ""){this.value = "Username"}', 'onfocus' => 'if(this.value == "Username"){this.value = ""}')); ?>
<div class="clrbotm"></div>
<label>Password</label><?php echo $this->Form->input('admin_pass', array('type' => 'password', 'value' => $txtPassword, 'id' => 'user_pass1', 'maxlength' => '30','div' => false, 'label' => false, 'onblur' => 'if(this.value == ""){this.value = "Password"}', 'onfocus' => 'if(this.value == "Password"){this.value = ""}')); ?>
<div class="clrbotm"></div>
<span id='login_error' ><?php echo $login_error; ?></span>
<input type="submit" name="button" value="Submit" class="buttonlogin fright" />
<?php echo $this->Form->end(); ?>
<div class="clear"></div>
</div>
</div>
</div>