<?php echo $this->Html->css('calender')
?>
 <script language="javascript" type="text/javascript">

 
 function depart_return(type){
     switch(type){
	  case'depart':
	        var return_depart_date=document.getElementById('return_depart_date').value ;
	        var return_depart_time=document.getElementById('return_depart_time').value ;
	   
	       if(return_depart_time!='00:00'){
		    
		     if(return_depart_date==''){
			 document.getElementById('return_depart_date').focus();
			 document.getElementById('return_depart_error').innerHTML='Please enter depart base date';
			 return false;
			
		     }else{
			document.getElementById('return_depart_error').innerHTML='';
		     }
		     
		}
		   
	       if(current_date<return_depart_date){
		  document.getElementById('return_base_error').innerHTML='Depart base date less than current date';
		  document.getElementById('return_base_date').value='';
		  return false;
	       }else{
		  document.getElementById('return_depart_error').innerHTML='';
	       }
	       break;
	  case'retrun':
	        var return_base_day=document.getElementById('return_base_date').value ;
	        var return_base_time=document.getElementById('return_base_time').value ;
	       
	       if(return_base_time!='00:00'){
		   
		    if(return_base_day==''){
			document.getElementById('return_base_date').focus();
			document.getElementById('return_base_error').innerHTML='Please enter return base date';
			return false;
		       
		    }else{
		       document.getElementById('return_base_error').innerHTML='';
		    }
		    
	       }
	
	   
	       if(current_date<return_base_day){
		  document.getElementById('return_base_error').innerHTML='Return base less than current date';
		  document.getElementById('return_base_date').value='';
		  return false;
	       }else{
		  document.getElementById('return_base_error').innerHTML='';
	       }
	    
	       
	       break;
     }
     
 }
 
 
 
 
 

 function isNumberKey(evt)
    {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
	     return false;
             return true;
     }
     
 function callEndStartDiff(){
   
            var strtjob_day=document.getElementById('start_job').value ;
	    if(strtjob_day==''){
	         document.getElementById('start_job_error').innerHTML='Please enter start date';
		 document.getElementById('start_job').focus();
		 return false;
	    }else{
	          document.getElementById('start_job_error').innerHTML='';
	    }
	    
	    
	    
	    
	    
            var endjob_day=document.getElementById('end_job').value ;
	    if(endjob_day!=''){
	       endStartDiff();
	    }else{
	    }
 }
  
  function endStartDiff(){
          
    	    var end_job_time=document.getElementById('end_job_time').value ; 
	    if(end_job_time!='00:00'){
	         var endjob_day=document.getElementById('end_job').value ;
		 if(endjob_day==''){
		     document.getElementById('end_job').focus();
		     document.getElementById('end_job_error').innerHTML='Please enter end date';
		     return false;
		    
		 }else{
		    document.getElementById('end_job_error').innerHTML='';
		 }
	         
	    }
     
	    var strtjob_day=document.getElementById('start_job').value ;
	    var endjob_day=document.getElementById('end_job').value ;
	    var strtjob_time=document.getElementById('start_job_time').value ;
	    var endjob_time=document.getElementById('end_job_time').value ;
	    var current_date='<?php echo date("m-d-Y"); ?>';
	    if(current_date<strtjob_day){
	       document.getElementById('start_job_error').innerHTML='Start date always less than current date';
	       document.getElementById('oprating_time').innerHTML='';
	       document.getElementById('hidden_oprating_value').value='';
	       return false;
	    }else{
	        document.getElementById('start_job_error').innerHTML='';
	    }
	    if(current_date<endjob_day){
	       document.getElementById('end_job_error').innerHTML='End date always less than current date';
	       document.getElementById('oprating_time').innerHTML='';
	       document.getElementById('hidden_oprating_value').value='';
	       return false;
	    }else{
	       document.getElementById('end_job_error').innerHTML='';
	    }
	     
	     
	     
	    
	    var strtjobval=strtjob_day+' '+strtjob_time;
	    var endjobval=endjob_day+' '+endjob_time;
	    
    
	    var rootpath='<?php echo $this->webroot; ?>';
	    document.getElementById('oprating_time').innerHTM='<img src="<?php echo $this->webroot ?>img/ajaxloader" />';
	                  $.ajax({
			  type: "POST",
			  url: rootpath+"Jobs/dateprocess/",
			  data:"strtjobval="+strtjobval+"&endjobval="+endjobval,
			  
			  success: function(res)
			  {
			    if(res=='wrong'){
			      document.getElementById('oprating_time').innerHTML='';
			      document.getElementById('hidden_oprating_value').value='';
			       document.getElementById('end_job_error').innerHTML='End date always greater than Start date';
			     }else{
			      document.getElementById('oprating_time').innerHTML=res;
			      document.getElementById('hidden_oprating_value').value=res;
			     }
	         	  			  	 
                          }
		 
	});
	

	return false;
	    
	
  }
function add_job_report_main()
{

	  
            var rp_date ='<?php echo date('Y-m-d'); ?>';
	    var report_no ='<?php echo $reportno;?>';
            var strtjob_day=document.getElementById('start_job').value ;
	    var endjob_day=document.getElementById('end_job').value ;
	    var strtjob_time=document.getElementById('start_job_time').value ;
	    var endjob_time=document.getElementById('end_job_time').value ;
	   
            var business_unit = jQuery.trim(document.getElementById('business_unit').value);
	    var client = jQuery.trim(document.getElementById('client').value);
	    var field_location = jQuery.trim(document.getElementById('field_location').value);
	    var country = jQuery.trim(document.getElementById('country').value);
	    var operating_time = jQuery.trim(document.getElementById('hidden_oprating_value').value);
	    var loss_time = jQuery.trim(document.getElementById('loss_time').value);
	    var report_id ='<?php echo $report_id;?>';
	    var return_base_day=document.getElementById('return_base_date').value ;
	    var current_date='<?php echo date("m-d-Y"); ?>';
	    
	      
	    
	    
	    
		    
	    var start_job_time=document.getElementById('start_job_time').value ; 
	    if(start_job_time!='00:00'){
	         var startjob_day=document.getElementById('start_job').value ;
		 if(startjob_day==''){
		     document.getElementById('start_job').focus();
		     document.getElementById('start_job_error').innerHTML='Please enter start date';
		     return false;
		    
		 }else{
		    document.getElementById('start_job_error').innerHTML='';
		 }
	         
	    }
	    
	    
	    var end_job_time=document.getElementById('end_job_time').value ; 
	    if(end_job_time!='00:00'){
	         var endjob_day=document.getElementById('end_job').value ;
		 if(endjob_day==''){
		     document.getElementById('end_job').focus();
		     document.getElementById('end_job_error').innerHTML='Please enter end date';
		     return false;
		    
		 }else{
		    document.getElementById('end_job_error').innerHTML='';
		 }
	         
	    }
	  
	    
	     var strtjobval=strtjob_day+' '+strtjob_time;
	     var endjobval=endjob_day+' '+endjob_time;
	    
	  
	    
	    
	    var return_depart_date=document.getElementById('return_depart_date').value ;
	    var return_depart_time=document.getElementById('return_depart_time').value ;
	   
	   if(return_depart_time!='00:00'){
	        
		 if(return_depart_date==''){
		     document.getElementById('return_depart_date').focus();
		     document.getElementById('return_depart_error').innerHTML='Please enter depart base date';
		     return false;
		    
		 }else{
		    document.getElementById('return_depart_error').innerHTML='';
		 }
	         
	    }
		   
	    if(current_date<return_depart_date){
	       document.getElementById('return_depart_error').innerHTML='Depart base date less than current date';
	       document.getElementById('return_depart_date').focus();
	       return false;
	    }else{
	       document.getElementById('return_depart_error').innerHTML='';
	    }
	    
	    var return_depart_val=return_depart_date+" "+return_depart_time;
	    
	    
	    
	   var return_base_day=document.getElementById('return_base_date').value ;
	   var return_base_time=document.getElementById('return_base_time').value ;
	    
	    if(return_base_time!='00:00'){
	        
		 if(return_base_day==''){
		     document.getElementById('return_base_date').focus();
		     document.getElementById('return_base_error').innerHTML='Please enter return base date';
		     return false;
		    
		 }else{
		    document.getElementById('return_base_error').innerHTML='';
		 }
	         
	    }
	
	   
	    if(current_date<return_base_day){
	       document.getElementById('return_base_error').innerHTML='Return base less than current date';
	       document.getElementById('return_base_date').focus();
	       return false;
	    }else{
	       document.getElementById('return_base_error').innerHTML='';
	    }
	    
	    
	
	    var return_base=return_base_day+" "+return_base_time;
	    
	   
	   
	  
	    
	    
	    
	    
	       
	    var dataStr = $("#add_job_main_form").serialize();
            var rootpath='<?php echo $this->webroot ?>';
     document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
              $.ajax({
			  type: "POST",
			  url: rootpath+"Jobs/jobprocess/",
			  data:"data="+dataStr+"&report_no="+report_no+"&rp_date="+rp_date+"&business_unit="+business_unit+"&client="+client+"&field_location="+field_location+"&country="+country+"&strtjobval="+strtjobval+"&endjobval="+endjobval+"&report_id="+report_id+"&operating_time="+operating_time+"&loss_time="+loss_time+"&return_base="+return_base+"&return_depart_val="+return_depart_val,
			  
			  success: function(res)
			  {
              
	         	    var resval=res.split("~");
			      if(resval[0]=='fail'){
				   document.getElementById('loader').innerHTML='<font color="red">Please try again</font>';  
	                     }else if(resval[0]=='add'){
				   document.getElementById('loader').innerHTML='<font color="green">Job report main added successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Jobs/add_job_report_main/'+resval[1];
				   
                             }else if(resval[0]=='update'){
				   document.getElementById('loader').innerHTML='<font color="green">Job report main updated successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Jobs/add_job_report_main/'+resval[1];
				  }
			   	 
                          }
		 
	});
	

	return false;
 
}

function check_character(){
  
        var summary = document.getElementById('summary').value;
        if(summary.length>5){
	    var summary_holder=summary.substring(0,100);
	    document.getElementById('summary').value=summary_holder;
	  	  
      }
}

  
  function show_client_reviewer(){
      var clientreviewed_value = jQuery.trim(document.getElementById('clientreviewed').value);
      if(clientreviewed_value==3){
	  document.getElementById('client_revier_section').style.display="block";
	  
      }else if(clientreviewed_value!=3){
	  document.getElementById('client_revier_section').style.display="none";
	  
      }
  }
     

 </script>

<aside>
<?php echo $this->Element('left_menu'); ?>
</aside>
 
 <section>
 <?php

    
    if($id!=0){

      echo $this->Element('jobtab');
      ?>
      
       <script language="javascript" type="text/javascript">
          $(document).ready(function() {
		$("#main").addClass("selectedtab");
	        $("#job_data").removeClass("selectedtab");
	        $("#gyro_job_data").removeClass("selectedtab");
	        $("#gauge_data").removeClass("selectedtab");
	        $("#action_item").removeClass("selectedtab");
	        $("#attachments").removeClass("selectedtab");
	        $("#link").removeClass("selectedtab");
	        $("#csr").removeClass("selectedtab");
	        $("#view").removeClass("selectedtab");
                $("#print").removeClass("selectedtab");
	      
	   });
       </script>
      
      <?php

     }

    echo $this->Form->create('add_job_main_form', array('controller' => 'Jobs','name'=>"add_job_main_form", 'id'=>"add_job_main_form", 'method'=>'post','class'=>'adminform'));
    echo $this->Form->input('id', array('type'=>'hidden', 'id'=>'id', 'value'=>$id));
 ?>

 <h2><?php echo $heading; ?><span class="textcmpul">Field marked with * are compulsory  </span></h2>
 <br/>
<label><?PHP echo __("Report Date:");?></label><span  style="font-size: 13px;color:#666666"><?PHP echo $report_date;?></span>
<div class="clearflds"></div>
<label><?PHP echo __("Report No:");?></label><span  style="font-size: 13px;color:#666666"><?PHP echo $reportno;?></span>
<div class="clearflds"></div>
<label><?PHP echo __("Created By:");?></label><span  style="font-size: 13px;color:#666666"><?PHP echo $created_by;?></span>
<div class="clearflds"></div>
<label><?PHP echo __("Client:");?></label>
          <span id="client_section">
			   <span id="client_section">
			    <select id="client" name="client">	
                             <?php for($i=0;$i<count($clientDetail);$i++){?>
			     <option value="<?php echo $clientDetail[$i]['Client']['id']; ?>" <?php if($client==$clientDetail[$i]['Client']['id']){echo "selected";}else{} ?>><?php echo $clientDetail[$i]['Client']['name']; ?></option>
			     <?php } ?>
			      </select>
		             
		   </span>	
		</span>
<div class="clearflds"></div>
<label><?PHP echo __("Business Unit:");?></label>
         <span id="Business_unit_section">
			    <select id="business_unit" name="business_unit">	
                             <?php for($i=0;$i<count($businessDetail);$i++){?>
			     <option value="<?php echo $businessDetail[$i]['BusinessType']['id']; ?>" <?php if($business_unit==$businessDetail[$i]['BusinessType']['id']){echo "selected";}else{} ?>><?php echo $businessDetail[$i]['BusinessType']['type']; ?></option>
			     <?php } ?>
			     </select>
		             
		</span>
<div class="clearflds"></div>

<label><?PHP echo __("Field Location:");?></label>
          <span id="client_section">
			   <span id="field_location_section">
		             <select id="field_location" name="field_location">	
                             <?php for($i=0;$i<count($fieldlocationDetail);$i++){?>
			     <option value="<?php echo $fieldlocationDetail[$i]['Fieldlocation']['id']; ?>" <?php if($field_location==$fieldlocationDetail[$i]['Fieldlocation']['id']){echo "selected";}else{} ?>><?php echo $fieldlocationDetail[$i]['Fieldlocation']['type']; ?></option>
			     <?php } ?>
			     </select>
		             
		   </span>	
		</span>
<div class="clearflds"></div>
<label><?PHP echo __("Country:");?></label>

			   <span id="country_section">
                             <select id="country" name="country">	
                             <?php for($i=0;$i<count($country);$i++){?>
			     <option value="<?php echo $country[$i]['Country']['id']; ?>" <?php if($country[$i]['Country']['id']==$cnt){echo "selected";}else{} ?>><?php echo $country[$i]['Country']['name']; ?></option>
			     <?php } ?>
			     </select>
		            
		   </span>	

	  
<div class="clearflds"></div>
<label><?PHP echo __("Depart Base:");?></label>
<input type="text" id="return_depart_date" name="return_depart_date" readonly="readonly" style="width:131px;"  onclick="displayDatePicker('return_depart_date',this);" value="<?php echo $return_depart; ?>" />
&nbsp;<select name="return_depart_time" id="return_depart_time" style="width:140px;height:28px;" onchange="depart_return('depart');" >
     <?php
       for ($i = 0; $i <= 23; $i++)
	{
	       for ($j = 0; $j <= 59; $j+=15)
	       {
			  $time = str_pad($i, 2, '0', STR_PAD_LEFT).':'.str_pad($j, 2, '0', STR_PAD_LEFT);
		  ?>
		   <option value="<?php echo $time; ?>"<?php if($depart_dt==$time){echo "selected";}else{} ?>><?php echo $time; ?></option>
	  <?php }
	 } 
     ?>
</select>
<span class="textcmpul" id="return_depart_error"></span>	  
<div class="clearflds"></div>

<label><?PHP echo __("Start Job:");?></label>
<input type="text" id="start_job" name="start_job" readonly="readonly" style="width:131px;"  onclick="displayDatePicker('start_job',this);" value="<?php echo $start_job; ?>" />
&nbsp;<select name="start_job_time" id="start_job_time" style="width:140px;height:28px;" onchange="callEndStartDiff();">
     <?php
       for ($i = 0; $i <= 23; $i++)
	{
	       for ($j = 0; $j <= 59; $j+=15)
	       {
			  $time = str_pad($i, 2, '0', STR_PAD_LEFT).':'.str_pad($j, 2, '0', STR_PAD_LEFT);
		  ?>
		   <option value="<?php echo $time; ?>"<?php if($sjt==$time){echo "selected";}else{} ?>><?php echo $time; ?></option>
	  <?php }
	 } 
     ?>
</select>

<span class="textcmpul" id="start_job_error"></span>
<div class="clearflds"></div>	  
<label><?PHP echo __("End Job:");?></label>
<input type="text" id="end_job" name="end_job" readonly="readonly" style="width:131px;"  onclick="displayDatePicker('end_job',this);" value="<?php echo $end_job; ?>" />
&nbsp;<select name="end_job_time" id="end_job_time" style="width:140px;height:28px;" onchange="endStartDiff();">
     <?php
       for ($i = 0; $i <= 23; $i++)
	{
	       for ($j = 0; $j <= 59; $j+=15)
	       {
			  $time = str_pad($i, 2, '0', STR_PAD_LEFT).':'.str_pad($j, 2, '0', STR_PAD_LEFT);
		  ?>
		   <option value="<?php echo $time; ?>"<?php if($ejt==$time){echo "selected";}else{} ?>><?php echo $time; ?></option>
	  <?php }
	 } 
     ?>
</select>
<span class="textcmpul" id="end_job_error"></span>
<div class="clearflds"></div>
<label><?PHP echo __("Operating Time:");?></label>
<label id="oprating_time"><?PHP echo $oprating_time;?> </label><input type="hidden" id="hidden_oprating_value"  value="<?PHP echo $hidden_oprating_value;?>" />
<div class="clearflds"></div>
<label><?PHP echo __("Return Base:");?></label>
<input type="text" id="return_base_date" name="return_base_date" readonly="readonly" style="width:131px;"  onclick="displayDatePicker('return_base_date',this);" value="<?php echo $return_base; ?>" />
&nbsp;<select name="return_base_time" id="return_base_time" style="width:140px;height:28px;" onchange="depart_return('retrun');"  >
     <?php
       for ($i = 0; $i <= 23; $i++)
	{
	       for ($j = 0; $j <= 59; $j+=15)
	       {
			  $time = str_pad($i, 2, '0', STR_PAD_LEFT).':'.str_pad($j, 2, '0', STR_PAD_LEFT);
		  ?>
		   <option value="<?php echo $time; ?>"<?php if($rbt==$time){echo "selected";}else{} ?>><?php echo $time; ?></option>
	  <?php }
	 } 
     ?>
</select>
<span class="textcmpul" id="return_base_error"></span>	  
<div class="clearflds"></div>
<label><?PHP echo __("Well:");?></label><?PHP echo $this->Form->input('well', array('type'=>'text', 'id'=>'well','value'=>$well,'label' => false,'maxlength'=>30,'div' => false)); ?>
<div class="clearflds"></div>
<label><?PHP echo __("Rig:");?></label><?PHP echo $this->Form->input('rig', array('type'=>'text', 'id'=>'rig','value'=>$rig,'label' => false,'maxlength'=>30,'div' => false)); ?>
<div class="clearflds"></div>
<label><?PHP echo __("Loss Time:");?></label>
<select name="loss_time" id="loss_time">
     <option value="0">Select One</option>
     <?php
       for ($i = 0; $i <= 23; $i++)
	{
	       for ($j = 0; $j <= 59; $j+=15)
	       {
			  $time = str_pad($i, 2, '0', STR_PAD_LEFT).':'.str_pad($j, 2, '0', STR_PAD_LEFT);
		  ?>
		   <option value="<?php echo $time; ?>"<?php if($loss_time==$time){echo "selected";}else{} ?>><?php echo $time; ?></option>
	  <?php }
	 } 
     ?>
</select>
<div class="clearflds"></div>
<label><?PHP echo __("Wellsite Rep:");?></label><?PHP echo $this->Form->input('wellsiterep', array('type'=>'text', 'id'=>'wellsiterep','value'=>$wellsiterep,'label' => false,'maxlength'=>30,'div' => false)); ?>
<div class="clearflds"></div>
<label><?PHP echo __("Revenue:");?></label>
<?PHP echo $this->Form->input('revenue', array('type'=>'text', 'id'=>'revenue', 'value'=>$revenue,'size'=>30,'maxlength'=>'40', 'label' => false,'div' => false,'onkeypress'=>'javascript:return isNumberKey(event);')); ?>
<div class="clearflds"></div>			 
<label><?PHP echo __("Field Ticket:");?></label> <?PHP echo $this->Form->input('field_ticket', array('type'=>'text', 'id'=>'field_ticket','value'=>$field_ticket,'label' => false,'div' => false)); ?>
<div class="clearflds"></div>	
<div class="clearflds"></div>	
<h2>Job Comments</h2>
<div class="clearflds"></div>			 
<label><?PHP echo __("Comments:");?></label> <?PHP echo $this->Form->input('summary', array('type'=>'textarea', 'id'=>'summary','value'=>$comments,'label' => false,'div' => false, "onkeyup" =>"check_character();")); ?>
<div class="clearflds"></div>	
<label>&nbsp;</label><span style="font-size: 11px;">Only 100 characters allow for summary</span>
  
<div class="clearflds"></div>			 
		  
<?php echo $this->Form->end(); ?>
<div class="buttonpanel">
<?php

if($button=='Update' && $closer_date!='00-00-0000'){
     
}elseif($button=='Update' &&  $closer_date=='00-00-0000' ){?>
  <span id="loader" style="float:left;font-size: 13px;"></span><input type="button" name="save" id="save" class="buttonsave" onclick="add_job_report_main();" value="<?php echo $button; ?>" />
     
<?php }elseif($button=='Submit'){ ?>

 <span id="loader" style="float:left;font-size: 13px;"></span><input type="button" name="save" id="save" class="buttonsave" onclick="add_job_report_main();" value="<?php echo $button; ?>" />

<?php } ?>

</div>
</section>
