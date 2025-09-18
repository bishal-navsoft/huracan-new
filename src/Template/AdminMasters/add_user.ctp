 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<style>
.fa-calendar:before {
    position: relative;
    content: "\f073";
    left: -25px;
    top: 5px;
}
</style>

<?php //echo $this->Html->css('calender') ?>
 <script language="javascript" type="text/javascript">
$( function() {
    	$( "#seniority_date" ).datepicker({
    		dateFormat: 'dd-mm-yy',
	    	onSelect: function(dateText, inst) {
	        	var date = $(this).val();
	        	var remidial_create=date;
	     }
		});
  	});  	

 function isNumberKey(evt)
    {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
	     return false;
             return true;
     }	     
	     
function onkey_check(type){
   switch(type){
	    case'phone_section':
	    var phone= document.getElementById('phone').value;
	      if(phone!='' || phone!='Enter Contact No'){
		if(phone.length>15 || phone.length<10){
		   document.getElementById('contactphone_error').innerHTML = 'Phone number should be between 10 to 15 digits';
		   document.getElementById('phone').focus();
		   return false;
		}else if(phone.length>10 || phone.length<16 || phone!='Enter Contact No'){
		  document.getElementById('contactphone_error').innerHTML = '';
		}else if(phone=='' || phone!='Enter Contact No'){
		  document.getElementById('phone').value = 'Enter Contact No';
		  document.getElementById('phone').focus();
		  return false;
		}
	      }else{
		 document.getElementById('phone').value = 'Enter Contact No';
		 document.getElementById('phone').focus();
		 return false;
	   }
            break;
            case'user_section':
	    var user_name= document.getElementById('user_name').value;
		if(user_name!='' || user_name!='Enter user name'){
		  if(user_name.length<5){
		     document.getElementById('username_error').innerHTML = 'User name should be 5 characters';
		     document.getElementById('user_name').focus();
		     return false;
		  }else if(user_name.length>4){
		    document.getElementById('username_error').innerHTML = '';
		  }
		  }else {
		    document.getElementById('username_error').innerHTML = '';

		  }
		
            break;
            case'user_password':
	    var password= document.getElementById('password').value;
		if(password!='' || password!='Enter password'){
		  if(password.length<5){
		     document.getElementById('password_error').innerHTML = 'Password should be 5 characters';
		     document.getElementById('password').focus();
		     return false;
		}else{
		    
		    document.getElementById('password_error').innerHTML = '';
		}
		  }else {
		    document.getElementById('password_error').innerHTML = '';

		  }
            break;
            case'user_confirm_password':
	         var password= document.getElementById('password').value;
		 var conf_password= document.getElementById('conf_password').value;
		 if(password!=conf_password){
		      document.getElementById('confirm_password_error').innerHTML = 'Password and confirm password not match';
	              document.getElementById('conf_password').focus();
		      return false;
			
		 }else{
		        document.getElementById('confirm_password_error').innerHTML = '';
		 }
            break;
	  case'email_checkup':
	       var emailval  = jQuery.trim(document.getElementById('email').value);
	       if(emailval == ''){
		    document.getElementById('emailaddress_error').innerHTML = 'Enter  valid  email  address';
                    document.getElementById('email').focus();
		    return false;
		    }else  if(emailval != ''){
			 var email_regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			      if(!email_regex.test(emailval)){
                                             document.getElementById('emailaddress_error').innerHTML = 'Enter  valid  email  address';
                                             document.getElementById('email').focus();
				             return  false;			 
                              }else{
				  document.getElementById('emailaddress_error').innerHTML = ''; 
			      }
		    }else{
			   document.getElementById('emailaddress_error').innerHTML = '';
		    }
	            
	  break;    
   }
  
  
}
	     
function add_user()
{
             var user_name = jQuery.trim(document.getElementById('user_name').value);
	     var password = jQuery.trim(document.getElementById('password').value);
	     var confirm_password = jQuery.trim(document.getElementById('conf_password').value);
             var fname = jQuery.trim(document.getElementById('fname').value);
	     var lname = jQuery.trim(document.getElementById('lname').value);
             var emailval  = jQuery.trim(document.getElementById('email').value);
	     var phone = jQuery.trim(document.getElementById('phone').value);
	     var roll_type = jQuery.trim(document.getElementById('roll_type').value);
  
        if(user_name=='' || user_name=='Enter user name')
	  {
		    document.getElementById('username_error').innerHTML = 'Enter user name';
		    document.getElementById('user_name').focus();
		    return false;
             
           }else{
	       
	        
			if(user_name.length<5){
				     document.getElementById('username_error').innerHTML = 'User name should be 5 characters';
				     document.getElementById('user_name').focus();
				     return false;
			  }else{
			            document.getElementById('username_error').innerHTML = '';
			  }
		
	     <?php if($id==0){ ?>
		
			if(password == '' || password=='Enter Password')
			    {
				document.getElementById('password_error').innerHTML = 'Enter Password';
				document.getElementById('password').focus();
				return false;
			       
			    }
	     <?php } ?>
	          
                            
                           
		           if(password!=''){
				   if(password.length<5){
						document.getElementById('password_error').innerHTML = 'Password should be 5 characters';
						document.getElementById('password').focus();
						return false;
				   }else{
						document.getElementById('password_error').innerHTML = '';
						var conf_password= document.getElementById('conf_password').value;
						if(password!=conf_password){
							    document.getElementById('confirm_password_error').innerHTML = 'Password and confirm password not match';
							    document.getElementById('conf_password').focus();
							    return false;
						}
				     }
			   }	 
			
                        if(fname == '')
                         {
             
				document.getElementById('fname_error').innerHTML = 'Enter First Name';
                                document.getElementById('fname').focus();
			        return false;
                       
                         }
                        else{
                        
				   if(lname == '')
                                      {
				       document.getElementById('lname_error').innerHTML = 'Enter Last Name';
                                       document.getElementById('lname').focus();
			               return false;
                       
                                       }
                                       else{
				            
					     if(emailval == '' || emailval=='Enter  valid  email  address')
                                                {
						    document.getElementById('emailaddress_error').innerHTML = 'Enter  valid  email  address';
                                                    document.getElementById('email').focus();
			                            return false;
						   
                                                 }
                                                else{
							    
						      if(emailval != '' || emailval !='Enter Valid Email'){
							   
                                                       var email_regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;


							      if(!email_regex.test(emailval)){
                                                                 document.getElementById('emailaddress_error').innerHTML = 'Enter  valid  email  address';
                                                                 document.getElementById('email').focus();
								 return  false;			 
                                                                }
						       }
                       
				                  
						      var phone= document.getElementById('phone').value;
							    if(phone!='' || phone!='Enter Contact No'){
							      if(phone.length>15 || phone.length<10){
								 document.getElementById('contactphone_error').innerHTML = 'Phone number should be between 10 to 15 digits';
								 document.getElementById('phone').focus();
								 return false;
							      }else if(phone.length>10 || phone.length<16 || phone!='Enter Contact No'){
								document.getElementById('contactphone_error').innerHTML = '';
							      }else if(phone=='' || phone!='Enter Contact No'){
								document.getElementById('phone').value = 'Enter Contact No';
								document.getElementById('phone').focus();
								return false;
							      }
							    }else{
							       document.getElementById('phone').value = 'Enter Contact No';
							       document.getElementById('phone').focus();
							       return false;
							 }
							    
                                                        }
                                                 }
                                          }
	                          
	                    }    
                
  
  
    
	    
	    var dataStr = $("#add_user_form").serialize();
	    var rootpath='<?php echo $this->webroot ?>';
	    document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
              $.ajax({
			  type: "POST",
			  url: rootpath+"AdminMasters/userprocess/",
			  data:"data="+dataStr,
			  success: function(res)
			  {
			 	console.log(res); return false;
			   if(res=='useravl'){
				   document.getElementById('loader').innerHTML='<font color="red">User name already exits</font>';
			   }else if(res=='emailavl'){
				   document.getElementById('loader').innerHTML='<font color="red">Email already exits</font>';
			   }else if(res=='update'){
				   document.getElementById('loader').innerHTML='<font color="green">User updated successfully</font>';
				   document.location='<?php echo $this->webroot; ?>AdminMasters/user_list';
			    }else if(res=='fail'){
				   document.getElementById('loader').innerHTML='<font color="red">Please try again</font>';  
	                   }else{
			          document.location='<?php echo $this->webroot; ?>AdminMasters/user_list';
				  ocument.getElementById('loader').innerHTML='<font color="green">User added successfully and login information sent to his email address </font>';
			   }
			 
		
                 	   /* if(res=='fail'){
				   document.getElementById('loader').innerHTML='<font color="red">Please try again</font>';  
	                     }else if(res=='add'){
				   document.getElementById('loader').innerHTML='<font color="green">User added successfully and login information sent to his email address </font>';
				  // document.location='<?php echo $this->webroot; ?>AdminMasters/user_list';
				   
                             }else if(res=='update'){
				   document.getElementById('loader').innerHTML='<font color="green">User updated successfully</font>';
				   
				  // document.location='<?php echo $this->webroot; ?>AdminMasters/user_list';
			    }else if(res=='useravl'){
				   document.getElementById('loader').innerHTML='<font color="red">User name already exits</font>';
			    }else if(res=='emailavl'){
				   document.getElementById('loader').innerHTML='<font color="red">Email already exits</font>';
			    }
			    */
				  
                          }
		 
	});
	

	return false;
 
}
 
 function redirect_page(){
     
          document.location='<?php echo $this->webroot; ?>AdminMasters/user_list';
     
 }


 </script>

<div id="body_container">
<div class="wrapall clearfix">
<!--right panel -->
<section class="adminwrap">
<div class="topadmin_heading clearfix">
<h2>Add New User</h2>
</div>
 <?php
     
     echo $this->Form->create('add_user_form', array('controller' => 'Reports','name'=>"add_user_form", 'id'=>"add_user_form", 'method'=>'post','class'=>'adminform'));
    echo $this->Form->input('id', array('type'=>'hidden', 'id'=>'id', 'value'=>$id));
 ?>

 <h2><?php echo $heading; ?><span class="textcmpul">Field marked with * are compulsory  </span></h2>
 <br/>
     
<label><?PHP echo __("User Name.");?><span>*</span></label><?php echo $this->Form->input('admin_user', array('type' => 'text', 'value' => $user_name, 'id' => 'user_name','name' => 'user_name','maxlength' => '30','div' => false, 'label' => false, "onkeyup"=>"onkey_check('user_section');")); ?><span id="username_error" class="textcmpul"></span>
<div class="clearflds"></div>
<label><?PHP echo __("Password");?><span>*</span></label><?PHP echo $this->Form->input('password', array('type'=>'password', 'id'=>'password','name'=>'password','size'=>30,'maxlength'=>'40', 'label' => false,'div' => false, "onkeyup"=>"onkey_check('user_password');")); ?><span id="password_error" class="textcmpul"></span>
<div class="clearflds"></div>
<label><?PHP echo __("Confirm Password");?><span>*</span></label><?PHP echo $this->Form->input('conf_password', array('type'=>'password', 'id'=>'conf_password','name'=>'conf_password','size'=>30,'maxlength'=>'40', 'label' => false,'div' => false, "onkeyup"=>"onkey_check('user_confirm_password');")); ?><span id="confirm_password_error" class="textcmpul"></span>
<div class="clearflds"></div>
<label><?PHP echo __("First Name");?><span>*</span></label><?PHP echo $this->Form->input('fname', array('type'=>'text', 'id'=>'fname','name'=>'fname', 'value'=>$fname,'size'=>30,'maxlength'=>'40', 'label' => false,'div' => false, "onkeyup"=>"onkey_check('firstname');")); ?><span id="fname_error" class="textcmpul"></span>
<div class="clearflds"></div>
<label><?PHP echo __("Last Name");?><span>*</span></label><?PHP echo $this->Form->input('lname', array('type'=>'text', 'id'=>'lname','name'=>'lname', 'value'=>$lname,'size'=>30,'maxlength'=>'40', 'label' => false,'div' => false, "onkeyup"=>"onkey_check('lastname');")); ?><span id="lname_error" class="textcmpul"></span>
<div class="clearflds"></div>
<label><?PHP echo __("Email Address");?><span>*</span></label><?PHP echo $this->Form->input('email', array('type'=>'text', 'id'=>'email','name'=>'email', 'value'=>$email,'size'=>30,'maxlength'=>'40', 'label' => false,'div' => false,"onkeyup"=>"onkey_check('email_checkup');")); ?><span id="emailaddress_error" class="textcmpul"></span>
<div class="clearflds"></div>
<label><?PHP echo __("Contact No");?><span>*</span></label><?PHP echo $this->Form->input('phone', array('type'=>'text', 'id'=>'phone','name'=>'phone', 'value'=>$phone,'size'=>30,'maxlength'=>'40', 'label' => false,'div' => false,'onkeypress'=>'javascript:return isNumberKey(event);',"onkeyup"=>"onkey_check('phone_section');")); ?><span id="contactphone_error" class="textcmpul"></span>
<div class="clearflds"></div>
<label><?PHP echo __("User Type");?><span>*</span></label>

<span id="roll_section">
  <select id="roll_type" name="roll_type">	
          <?php for($i=0;$i<count($roleList);$i++){
	       if($roleList[$i]['RoleMaster']['id']!=1){
	       
	       ?>
			     <option value="<?php echo $roleList[$i]['RoleMaster']['id']; ?>" <?php if($rollid==$roleList[$i]['RoleMaster']['id']){echo "selected";}else{} ?>><?php echo $roleList[$i]['RoleMaster']['role_name']; ?></option>
	   <?php } }?>
   </select>
</span>
<div class="clearflds"></div>


<label><?PHP echo __("Position");?><span>*</span></label><?PHP echo $this->Form->input('position', array('type'=>'text', 'id'=>'position','name'=>'position', 'value'=>$position,'size'=>30,'maxlength'=>'40', 'label' => false,'div' => false)); ?>
<div class="clearflds"></div>

<!--<label><?PHP //echo __("Seniority Date");?><span>*</span></label><input type="text" id="seniority_date" name="seniority_date" readonly="readonly"  onclick="displayDatePicker('seniority_date',this);" value="<?php //echo $seniority_date_val; ?>" /><span class="textcmpul" id="seniority_date_error"></span>-->

<label><?PHP echo __("Seniority Date");?><span>*</span></label><input type="text"  id="seniority_date" name="seniority_date" readonly="readonly" name="seniority_date" style="width:90px;"  value="<?php echo $seniority_date_val; ?>" ><i id="dateicon"  class="fa fa-calendar hasdatepicker"></i>
<div class="clearflds"></div>

<div class="buttonpanel">
<span id="loader" style="float:left;font-size: 13px;"></span><input type="button" name="save" id="save" class="buttonsave" onclick="add_user();" value="<?php echo $button; ?>" />
<input type="button" name="button" onclick="redirect_page();" value="Cancel" class="buttoncancel" />
<?php echo $this->Form->end(); ?>
</section>
</div>
</div>
