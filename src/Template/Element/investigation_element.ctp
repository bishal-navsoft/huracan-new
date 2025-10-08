 <script language="javascript" type="text/javascript">
    
    function add_report_investigation(path)
     {
         
	    var id_holder_list =  document.getElementById('id_holder').value;
	 
	    if(id_holder_list==''){
	       document.getElementById('error_msg').innerHTML='Please select atleast one invesitgation';
	       document.getElementById('personal_data').focus();
	       return false;
	    }else{
	        document.getElementById('error_msg').innerHTML=' ';
	    }
	    if(document.getElementById('immediate_sub_cause')){
	        
		  var immediate_sub_cause =  document.getElementById('immediate_sub_cause').value;
	       
	    }
	     
	     
	      var people_title_val = jQuery.trim(document.getElementById('people_title').value);
	      var people_descrption_val = jQuery.trim(document.getElementById('people_descrption').value);
	      var position_title_val =  jQuery.trim(document.getElementById('position_title').value);
	      var position_descrption_val = jQuery.trim(document.getElementById('position_descrption').value);
	      var part_title_val =  jQuery.trim(document.getElementById('part_title').value);
	      var part_descrption_val = jQuery.trim(document.getElementById('part_descrption').value);
	      var paper_title_val = jQuery.trim(document.getElementById('paper_title').value);
	      var paper_descrption_val =  jQuery.trim(document.getElementById('paper_descrption').value);
	      
	       
	      if(people_title_val!='' &&  people_title_val!='Enter People Title'){
	            if(people_descrption_val=='' || people_descrption_val=='Enter People Description'){
			 $("#people_title").removeClass("texterrorclass");
			 $("#people_title").addClass("textclass");  
		   	 $("#people_descrption").removeClass("textareaclass");
			 $("#people_descrption").addClass("textarea_error_class");
			 $("#people_descrption").focus();
			 return false;
		    }else{
	               
		         $("#people_descrption").removeClass("textarea_error_class");
			 $("#people_descrption").addClass("textareaclass");
	       
	             }
	
	      }
	      
	      if(people_descrption_val!='' && people_descrption_val!='Enter People Description'){
	       
	            if(people_title_val=='' ||  people_title_val=='Enter People Title'){
			 $("#people_descrption").removeClass("textarea_error_class");
			 $("#people_descrption").addClass("textareaclass");  
		   	 $("#people_title").removeClass("textclass");
			 $("#people_title").addClass("texterrorclass");
			 $("#people_title").focus();
			 return false;
		    }else{
			  $("#people_title").removeClass("texterrorclass");
			  $("#people_title").addClass("textclass");
			 
		    }
	
	      }
	      
	      
	      
	       if(position_title_val!='' && position_title_val!='Enter Position Title'){
		     if(position_descrption_val=='' || position_descrption_val=='Enter Position Description'){
		    	 $("#position_title").removeClass("texterrorclass");
			 $("#position_title").addClass("textclass");  
		   	 $("#position_descrption").removeClass("textareaclass");
			 $("#position_descrption").addClass("textarea_error_class");
			 $("#position_descrption").focus();
			 return false;
		    
		     }else{
	         
		         $("#position_descrption").removeClass("textarea_error_class");
			 $("#position_descrption").addClass("textareaclass");
	       
	           }
	
	      }
	      
	      if(position_descrption_val!='' && position_descrption_val!='Enter Position Description'){
	           
		   if(position_title_val=='' || position_title_val=='Enter Position Title'){
	       
			 $("#position_descrption").removeClass("textarea_error_class");
			 $("#position_descrption").addClass("textareaclass");  
		   	 $("#position_title").removeClass("textclass");
			 $("#position_title").addClass("texterrorclass");
			 $("#position_title").focus();
			 return false;
		   }else{
	                			   
		   	 $("#position_title").removeClass("texterrorclass");
			 $("#position_title").addClass("textclass");
	      
	          }
	
	      }
	      
	      
	      
	      if(part_title_val!='' && part_title_val!='Enter Parts Title'){
	            if(part_descrption_val=='' || part_descrption_val=='Enter Parts Description'){
	       
			 $("#part_title").removeClass("texterrorclass");
			 $("#part_title").addClass("textclass");  
		   	 $("#part_descrption").removeClass("textareaclass");
			 $("#part_descrption").addClass("textarea_error_class");
			 $("#part_descrption").focus();
			 return false;
		    }else{
			 $("#part_descrption").removeClass("textarea_error_class");
			 $("#part_descrption").addClass("textareaclass");
			 
		    }
	
	      }
	      
	   
	      if(part_descrption_val!='' && part_descrption_val!='Enter Parts Description'){
	           if(part_title_val=='' || part_title_val=='Enter Parts Title'){
	       
			 $("#part_descrption").removeClass("textarea_error_class");
			 $("#part_descrption").addClass("textareaclass");  
		   	 $("#part_title").removeClass("textclass");
			 $("#part_title").addClass("texterrorclass");
			 $("#part_title").focus();
			 return false;
		   }else{
	                			   
		   	 $("#part_title").removeClass("texterrorclass");
			 $("#part_title").addClass("textclass");
	      
	            }
	
	      }
	      
	      
	      if(paper_title_val!='' && paper_title_val!='Enter Paper Title'){
	           if(paper_descrption_val=='' || paper_descrption_val=='Enter Paper Description'){
			 $("#paper_title").removeClass("texterrorclass");
			 $("#paper_title").addClass("textclass");  
		   	 $("#paper_descrption").removeClass("textareaclass");
			 $("#paper_descrption").addClass("textarea_error_class");
			 $("#paper_descrption").focus();
			 return false;
		   }else{
	         
		         $("#paper_descrption").removeClass("textarea_error_class");
			 $("#paper_descrption").addClass("textareaclass");
	       
	           }
	
	      }
	      
	      
	      if(paper_descrption_val!='' && paper_descrption_val!='Enter Paper Description'){
	       
	          if(paper_title_val=='' || paper_title_val=='Enter Paper Title'){
	       
			 $("#paper_descrption").removeClass("textarea_error_class");
			 $("#paper_descrption").addClass("textareaclass");  
		   	 $("#paper_title").removeClass("textclass");
			 $("#paper_title").addClass("texterrorclass");
			 $("#paper_title").focus();
			 return false;
		  }else{
		   
		         $("#paper_title").removeClass("texterrorclass");
			 $("#paper_title").addClass("textclass");
		   }
	
	      }
	      
	
	     
	     var dataStr = $("#add_report_investigation_form").serialize();
	
             var rootpath='<?php echo $this->webroot ?>';
	     document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
              $.ajax({
			  type: "POST",
			  url: path,
			  data:"data="+dataStr,
			  success: function(res)
			  {
			   
			  if(res=='fail'){
				   document.getElementById('loader').innerHTML='<font color="red">Please try again</font>';  
	                     }else if(res=='add'){
				   document.getElementById('loader').innerHTML='<font color="green">Investigation Data Added Successfully</font>';
				  
			     }else if(res=='Update'){
				   document.getElementById('loader').innerHTML='<font color="green">Investigation Data Update Successfully</font>';
				  
				   
                             }
                             
                             
                 }
		 
	});
	

	return false;
	
 
}
 
 
 function check_character(textareid){
  
        var summary =document.getElementById(textareid).value;
        if(summary.length>5){
	    var summary_holder=summary.substring(0,1000);
	    document.getElementById(textareid).value=summary_holder;
	  	  
      }
}
 
 
 
 
 
 
 
 
 </script>
 
    <div class="sub_contentwrap fixreport_table" >
	  <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttable" >
	       <tr>
                 <td colspan="3" align="center" valign="middle" class="titles">Investigation - Team &nbsp;&nbsp;<?php echo $report_number; ?></td>
      
              <tr>
	  </table>
     </div>
    <div class="looptable_panel">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" >
      <tr>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0"  class="dyntable">
          <tr>
            <td width="31%" align="left" valign="middle">
	       <select id="personal_data" name="personal_data" >
                 <option value="0">Select One</option>	  
		    <?php for($i=0;$i<count($userDetail);$i++){?>
		    <option value="<?php echo $userDetail[$i]['AdminMaster']['position_seniorty']; ?>"><?php echo $userDetail[$i]['AdminMaster']['first_name']." ".$userDetail[$i]['AdminMaster']['last_name']; ?></option>
		    <?php } ?>
              </select>
	    </td>
	    <td width="59%" align="left" valign="middle" id="error_msg" style="color: red"></td>
            <td width="10%" align="right" valign="middle" >
              <?php if($is_add==1){ ?>
	       <input type="button" name="button" value="Add" class="buttonsave" onclick="add_seniority();" />
               <?php } ?>
	       </td>
          </tr>
        </table></td>
      </tr>
    </table>
  </div>   
     
     
   <?PHP
   
  // echo '<pre>';
  // print_r($investigation_team);
   
   ?>
     
     
     
     
     
 <div class="sub_contentwrap fixreport_table" >
  <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttable" id="seniority_holder">
    <tr>
      <td width="30%" align="left" valign="middle" class="label">Name</td>

      <td width="30%" align="left" valign="middle" class="label">Seniority</td>
      <td width="30%" align="left" valign="middle" class="label">User Type</td>
      <td width="30%" align="left" valign="middle" class="label">Position</td>
      <td width="10%" align="left" valign="middle" class="label">Action</td>
    </tr>
    
    <?php
   
    if(count($investnameHolader)>0){
       for($i=0;$i<count($investnameHolader);$i++){?>
       
        <tr id="<?php echo $investnameHolader[$i]['id']; ?>">
           <td width="30%" align="left" valign="middle"  ><?php echo ucfirst($investnameHolader[$i]['first_name'])." ".$investnameHolader[$i]['last_name']; ?></td>
           <td width="30%" align="left" valign="middle" ><?php echo $investnameHolader[$i]['user_seniority'];?></td>
           <td width="30%" align="left" valign="middle" ><?php echo $investnameHolader[$i]['role_name'];?></td>
           <td width="10%" align="left" valign="middle" >
	        <a href="javascript:void(0);" onclick="remove_child(<?php echo $investnameHolader[$i]['id'];?>);">Remove</a>
            </td>
          </tr>
	  
    <?php }}?>
    
   
  </table>

<div class="sub_contentwrap">
  <table width="100%" border="0" cellspacing="1" cellpadding="0" class="investigationtable">
      <div class="sub_contentwrap">
       <table width="100%" border="0" cellspacing="1" cellpadding="0" class="investigationtable">
	   <tr>
	   <td colspan="4" align="center" valign="middle" class="titles">Investigation - Data Gathering</td>
	   </tr>
	   <tr>
	       <td align="left" width="22%" valign="middle" rowspan="2" class="label">People:</td><td><input type="text" class="textclass" name="people_title" id="people_title" maxlength=100  onblur="if(this.value == ''){this.value = 'Enter People Title';}" onclick="if(this.value == 'Enter People Title'){this.value = '';}" value="<?php echo $epeoplet; ?>"/>&nbsp;<span valign="middle"> Only 100 characters allow for title</span></td>
	   </tr>
	   <tr><td  width="78%"><textarea id="people_descrption" name="people_descrption" class="textareaclass"  onblur="if(this.value == ''){this.value = 'Enter People Description';}" onclick="if(this.value == 'Enter People Description'){this.value = '';}" onkeyup="check_character('people_descrption');" ><?php echo $epeopled; ?></textarea>&nbsp; <span valign="middle"></span></td></tr>
	   <tr>
	       <td align="left" valign="middle" rowspan="2" class="label">Position:</td><td><input type="text" name="position_title" id="position_title" class="textclass" maxlength=100   onblur="if(this.value == ''){this.value = 'Enter Position Title';}" onclick="if(this.value == 'Enter Position Title'){this.value = '';}" value="<?php echo $eposplet; ?>"/>&nbsp;<span valign="middle"> Only 100 characters allow for title</span></td>
	   </tr>
	   <tr><td><textarea id="position_descrption" class="textareaclass" name="position_descrption" onblur="if(this.value == ''){this.value = 'Enter Position Description';}" onclick="if(this.value == 'Enter Position Description'){this.value = '';}" onkeyup="check_character('position_descrption');" ><?php echo $epospled; ?></textarea>&nbsp;</td></tr>
	    <tr>
	       <td align="left" width="22%" valign="middle" rowspan="2" maxlength=100 class="label">Parts:</td><td><input type="text" name="part_title" id="part_title" class="textclass" onblur="if(this.value == ''){this.value = 'Enter Parts Title';}" onclick="if(this.value == 'Enter Parts Title'){this.value = '';}" value="<?php echo $epartsplet; ?>"/>&nbsp;<span valign="middle"> Only 100 characters allow for title</span></td>
	   </tr>
	   <tr><td  width="78%"><textarea id="part_descrption" name="part_descrption" class="textareaclass" onblur="if(this.value == ''){this.value = 'Enter Parts Description';}" onclick="if(this.value == 'Enter Parts Description'){this.value = '';}" onkeyup="check_character('part_descrption');"><?php echo $epartspled; ?></textarea>&nbsp;<span valign="middle"></span></td></tr>
	   <tr>
	       <td align="left" valign="middle" rowspan="2" maxlength=100 class="label">Paper:</td><td><input type="text" name="paper_title" class="textclass" id="paper_title" value="<?php echo $epapert; ?>" onblur="if(this.value == ''){this.value = 'Enter Paper Title';}" onclick="if(this.value == 'Enter Paper Title'){this.value = '';}"/>&nbsp;<span valign="middle"> Only 100 characters allow for title</span></td>
	   </tr>
	    <tr><td><textarea id="paper_descrption" name="paper_descrption" class="textareaclass" onblur="if(this.value == ''){this.value = 'Enter Paper Description';}" onclick="if(this.value == 'Enter Paper Description'){this.value = '';}" onkeyup="check_character('paper_descrption');" ><?php echo $epaperd; ?></textarea>&nbsp;<span valign="middle"></span></td></tr>
       </table>
      </div>
  </table>
  </div>