 <script language="javascript" type="text/javascript">

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
	    var phone= document.getElementById('phone_no').value;
	      if(phone!='' || phone!='Enter Contact No'){
		if(phone.length>15 || phone.length<10){
		   document.getElementById('contactphone_error').innerHTML = 'Phone number should be between 10 to 15 digits';
		   document.getElementById('phone_no').focus();
		   return false;
		}else if(phone.length>10 || phone.length<16){
		  document.getElementById('contactphone_error').innerHTML = '';
		}else if(phone==''){
		  document.getElementById('contactphone_error').innerHTML = 'Enter Contact No';
		  document.getElementById('phone_no').focus();
		  return false;
		}
	      }else{
		 document.getElementById('contactphone_error').innerHTML = 'Enter Contact No';
		 document.getElementById('phone_no').focus();
		 return false;
	   }
            break;
   }
  
  
}
	     
function add_report_personal()
{
             
  
            var personal_data = jQuery.trim(document.getElementById('personal_data').value);
	    if(personal_data==0){
	        document.getElementById('personal_data_error').innerHTML='Please select name';
		document.getElementById('personal_data').focus();
	        return false;
	    }else{
	       document.getElementById('personal_data_error').innerHTML=''; 
	    }
	
	    var since_sleep = jQuery.trim(document.getElementById('since_sleep').value);
	    var phone_no = jQuery.trim(document.getElementById('phone_no').value);
	    var vehicle_name = jQuery.trim(document.getElementById('vehicle_name').value);
	    var report_id ='<?php echo $report_id;?>';
	    var last_sleep = jQuery.trim(document.getElementById('last_sleep').value);
            var dataStr = $("#add_jn_personal_form").serialize();
            var rootpath='<?php echo $this->webroot ?>';
	    document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
              $.ajax({
			  type: "POST",
			  url: rootpath+"Jrns/jnpersonnelprocess/",
			  data:"data="+dataStr+"&personal_data="+personal_data+"&report_id="+report_id+"&last_sleep="+last_sleep+"&since_sleep="+since_sleep+"&phone_no="+phone_no+"&vehicle_name="+vehicle_name,
			  success: function(res)
			  {
			
					   	
		            if(res=='add'){
				   document.getElementById('loader').innerHTML='<font color="green">Personnel Data Added Successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Jrns/report_jn_perssonel_list/<?php echo base64_encode($report_id); ?>';
			     }else if(res=='update'){
				   document.getElementById('loader').innerHTML='<font color="green">Personnel Data Update Successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Jrns/report_jn_perssonel_list/<?php echo base64_encode($report_id); ?>';
				   
                             }
			     else if(res=='avl'){
				   document.getElementById('loader').innerHTML='<font color="red">Personnel Data Already Exist</font>';
				  
				   
                             }
                    
                            
                         }
		 
	});
	

	return false;
 
}

 
 </script>

 <aside>
<?php echo $this->Element('left_menu'); ?>
</aside>
 
 <section>
 <?php

         echo $this->Element('journeytab');
   
  ?>
     <script language="javascript" type="text/javascript">
     
      
                $(document).ready(function() {
                      $("#main").removeClass("selectedtab");
                      $("#personnel").addClass("selectedtab");
                      $("#checklist").removeClass("selectedtab");
                      $("#attachments").removeClass("selectedtab");
                      $("#link").removeClass("selectedtab");
                      $("#authorisation").removeClass("selectedtab");
                      $("#view").removeClass("selectedtab");
                });
     
    
     
     
    function retrive_data(){
           var personal_info = jQuery.trim(document.getElementById('personal_data').value);
	   if(personal_info!=0){
	   var usre_info=personal_info.split("~");
	   document.getElementById('postion_seniorty').style.display='block';
	   document.getElementById('postion_seniorty').innerHTML='<label><?PHP echo __("Position:");?></label><label>'+usre_info[0]+'</label><div class="clearflds"></div><label><?PHP echo __("Seniority:");?></label><label>'+usre_info[1]+'</label><div class="clearflds"></div>';
	   $('#phone_no').val(usre_info[3]);

	   
	   }else if(personal_info==0){
	   document.getElementById('postion_seniorty').innerHTML='';
	   document.getElementById('postion_seniorty').style.display='none';
	 
	       
	   }
    }
 </script>   
 <?php
    echo $this->Form->create('add_jn_personal_form', array('controller' => 'Jnreports','name'=>"add_jn_personal_form", 'id'=>"add_jn_personal_form", 'method'=>'post','class'=>'adminform'));
    echo $this->Form->input('id', array('type'=>'hidden', 'id'=>'id', 'value'=>$id));
    echo $this->Form->input('pid', array('type'=>'hidden', 'pid'=>'id', 'value'=>$pid));
 ?>

 <h2><?php echo $heading; ?><span class="textcmpul">Field marked with * are compulsory  </span></h2>
 <br/>
<label><?PHP echo __("Name:");?><span>*</span></label>

     <span id="personal_data_section">
     <select id="personal_data" name="personal_data" onchange="retrive_data();">
       <option value="0">Select One</option>	  
      <?php for($i=0;$i<count($userDetail);$i++){?>
      <option value="<?php echo $userDetail[$i]['AdminMaster']['position_seniorty']; ?>" <?php if($userDetail[$i]['AdminMaster']['id']==$person){ echo "selected";} ?>><?php echo $userDetail[$i]['AdminMaster']['first_name']." ".$userDetail[$i]['AdminMaster']['last_name']; ?></option>
      <?php } ?>
      </select>
     </span><span id="personal_data_error" class="textcmpul"></span>	
<div class="clearflds"></div>
<span id="postion_seniorty" <?php echo $styledisplay; ?> >
         <label><?PHP echo __("Position:");?></label>
         <label><?php echo $roll_name; ?></label>
         <div class="clearflds"></div>  
         <label><?PHP echo __("Seniority:");?></label>
         <label><?php echo $snr; ?></label>
         <div class="clearflds"></div>
</span>
<div class="clearflds"></div>
<label><?PHP echo __("Phone No:");?></label><?PHP echo $this->Form->input('phone_no', array('type'=>'text', 'id'=>'phone_no','name'=>'phone_no', 'value'=>$phone_no,'size'=>30,'maxlength'=>'40', 'label' => false,'div' => false,'onkeypress'=>'javascript:return isNumberKey(event);',"onkeyup"=>"onkey_check('phone_section');")); ?><span id="contactphone_error" class="textcmpul"></span>
<div class="clearflds"></div>
<label><?PHP echo __("Vehicle:");?></label>
         <span id="vehicle_section">
			    <select id="vehicle_name" name="vehicle_name">	
                             <?php for($i=0;$i<count($vehicleDATA);$i++){?>
			     <option value="<?php echo $vehicleDATA[$i]['Vehicle']['id']; ?>" <?php if($vid==$vehicleDATA[$i]['Vehicle']['id']){echo "selected";}else{} ?>><?php echo $vehicleDATA[$i]['Vehicle']['type']; ?></option>
			     <?php } ?>
			     </select>
		             
		</span>
<div class="clearflds"></div>
<label><?PHP echo __("Hrs Last Sleep:");?></label>
	  <select name="last_sleep" id="last_sleep">
	  <?php
	  
	  for($i = 0; $i <= 23; $i++){
	       if($i<10){
		    $hr="0$i";
		    
	       }else{
		  $hr="$i";  
	       }
	       ?>
		<option value="<?php echo $hr; ?>" <?php if($time_last_sleep==$hr){echo "selected";}else{} ?>><?php echo $hr; ?></option>
	      <?php  
	      
	  }
	  ?>
	  
	  </select>

<div class="clearflds"></div>
<label><?PHP echo __("Hrs Since Sleep:");?></label>
	  <select name="since_sleep" id="since_sleep">
	  <?php
	  
	  for($i = 0; $i <= 23; $i++){
	       if($i<10){
		    $hr="0$i";
		    
	       }else{
		  $hr="$i";  
	       }
	       ?>
		<option value="<?php echo $hr; ?>" <?php if($time_since_sleep==$hr){echo "selected";}else{} ?>><?php echo $hr; ?></option>
	      <?php  
	      
	  }
	  ?>
	  </select>
<div class="clearflds"></div>		  
<?php echo $this->Form->end(); ?>
<div class="buttonpanel">
 <span id="loader" style="float:left;font-size: 13px;"></span><input type="button" name="save" id="save" class="buttonsave" onclick="add_report_personal();" value="<?php echo $button; ?>" />
</div>
</section>
