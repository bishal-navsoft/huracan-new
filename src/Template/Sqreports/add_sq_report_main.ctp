<?php echo $this->Html->css('calender') ?>
 <script language="javascript" type="text/javascript">


 function isNumberKey(evt)
    {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
	     return false;
             return true;
     }	     
	     
  
  function check_diff(dateString){
            
            var current_date='<?php echo date("m-d-Y"); ?>';
	    var rootpath='<?php echo $this->webroot; ?>';
	    if(current_date<dateString){
	       document.getElementById('event_date_error').innerHTML='Event date always less than current date';
	       return false;
	    }else{
	    	   
	               $.ajax({
			  type: "POST",
			  url: rootpath+"Reports/date_calculate/",
			  data:"data=a&lowre_date="+dateString+"&current_date="+current_date,
			  success: function(res)
			  {
			      document.getElementById('since_event').innerHTML=res; 
			     document.getElementById('since_event_hidden').value=res; 
                          }
			  
		 
	           });
		  return false;     
	  
	    }
	     
  }
function add_report_main()
{
          
            var event_date = jQuery.trim(document.getElementById('event_date').value);
	    var since_event = jQuery.trim(document.getElementById('since_event_hidden').value);
	    var report_no ='<?php echo $reportno;?>';
	    var reporter = jQuery.trim(document.getElementById('reporter').value);
            //var closer_date = jQuery.trim(document.getElementById('closer_date').value);
	    var incident_type = jQuery.trim(document.getElementById('incident_type').value);
            var business_unit = jQuery.trim(document.getElementById('business_unit').value);
	    var client = jQuery.trim(document.getElementById('client').value);
	    var field_location = jQuery.trim(document.getElementById('field_location').value);
	    var country = jQuery.trim(document.getElementById('country').value);
	    var reporter = jQuery.trim(document.getElementById('reporter').value);
	    var potential = jQuery.trim(document.getElementById('potential').value);
            var residual = jQuery.trim(document.getElementById('residual').value);
	    var summary = jQuery.trim(document.getElementById('summary').value);
	    var details = jQuery.trim(document.getElementById('details').value);
	    var severity = jQuery.trim(document.getElementById('severity_type').value);
	    var operating_time = jQuery.trim(document.getElementById('operating_time').value);
            var incident_location = jQuery.trim(document.getElementById('incident_location').value);
	    var currentdate=<?php echo date('m-d-Y'); ?>
	    
	    if(event_date==''){
	       document.getElementById('event_date').focus();
	       document.getElementById('event_date_error').innerHTML='Please enter event date';
	       return false;
	     }else if(currentdate<event_date){
	       document.getElementById('event_date').focus();
	       document.getElementById('event_date_error').innerHTML='Event date always less than current date';
	       return false;
	     } 
	    
	      
            var well = jQuery.trim(document.getElementById('well').value);
	    var rig = jQuery.trim(document.getElementById('rig').value);
	    var report_id ='<?php echo $report_id;?>';
	    var clientncr = jQuery.trim(document.getElementById('clientncr').value);
            var clientreviewed= jQuery.trim(document.getElementById('clientreviewed').value);
	    if(clientreviewed==3){
	        var clientreviewer = jQuery.trim(document.getElementById('clientreviewer').value);
		if(clientreviewer==''){
		    document.getElementById('client_reviewer_error').innerHTML='Please Enter Client Reviewer';
		    return false;
		}else{
		    document.getElementById('client_reviewer_error').innerHTML='';
		}
	       
	    }

	    var wellsiterep = jQuery.trim(document.getElementById('wellsiterep').value);

	    
	    var dataStr = $("#add_sq_main_form").serialize();
            var rootpath='<?php echo $this->webroot ?>';
     document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
              $.ajax({
			  type: "POST",
			  url: rootpath+"Sqreports/sqreportprocess/",
			  data:"data="+dataStr+"&report_no="+report_no+"&incident_type="+incident_type+"&business_unit="+business_unit+"&client="+client+"&field_location="+field_location+"&country="+country+"&reporter="+reporter+"&since_event="+since_event+"&reporter="+reporter+"&potential="+potential+"&report_id="+report_id+"&clientreviewed="+clientreviewed+"&residual="+residual+"&severity="+severity+"operating_time="+operating_time+"&incident_location="+incident_location,
			  
			  success: function(res)
			  {
			     
    
	         	    var resval=res.split("~");
			      if(resval[0]=='fail'){
				   document.getElementById('loader').innerHTML='<font color="red">Please try again</font>';  
	                     }else if(resval[0]=='add'){
				   document.getElementById('loader').innerHTML='<font color="green">SQ Main Added Successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Sqreports/add_sq_report_main/'+resval[1];
				   
                             }else if(resval[0]=='update'){
				   document.getElementById('loader').innerHTML='<font color="green">SQ Main Updated Successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Sqreports/add_sq_report_main/'+resval[1];
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
         echo $this->Element('sqtab');
     }

    echo $this->Form->create('add_sq_main_form', array('controller' => 'Sqreports','name'=>"add_sq_main_form", 'id'=>"add_sq_main_form", 'method'=>'post','class'=>'adminform'));
    echo $this->Form->input('id', array('type'=>'hidden', 'id'=>'id', 'value'=>$id));
 ?>

 <h2><?php echo $heading; ?><span class="textcmpul">Field marked with * are compulsory  </span></h2>
 <br/>
     
<label><?PHP echo __("Event Date:");?><span>*</span></label>

<input type="text" id="event_date" name="event_date" readonly="readonly"  onclick="displayDatePicker('event_date',this);" value="<?php echo $event_date; ?>" /><span class="textcmpul" id="event_date_error"></span>
<div class="clearflds"></div>

         <label><?PHP echo __("Report No:");?><span>*</span></label>
         <label><?PHP echo $reportno;?></label>
         <div class="clearflds"></div>  
         <label><?PHP echo __("Days Since Event:");?><span>*</span></label>
         <label id="since_event"><?PHP echo $since_event_hidden;?></label>
	 <input type="hidden" id="since_event_hidden" name="since_event_hidden" value="<?PHP echo $since_event_hidden;?>" />
         <div class="clearflds"></div>


<label><?PHP echo __("Incident Type:");?></label>
<span id="incident_section">
			 
	<select id="incident_type" name="incident_type">	
                             <?php for($i=0;$i<count($incidentDetail);$i++){?>
			     <option value="<?php echo $incidentDetail[$i]['Incident']['id']; ?>" <?php if($incident_type==$incidentDetail[$i]['Incident']['id']){echo "selected";}else{} ?>><?php echo $incidentDetail[$i]['Incident']['type']; ?></option>
			     <?php } ?>
	</select>
			    
			
        </span>


<div class="clearflds"></div>
<label><?PHP echo __("Created By:");?><span>*</span></label><span  style="font-size: 13px;color:#666666"><?PHP echo $created_by;?></span>
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
<label><?PHP echo __("Reporter:");?></label>
                         <span id="report_section">
			    <select id="reporter" name="reporter">	
                             <?php for($i=0;$i<count($userDetail);$i++){?>
			     <option value="<?php echo $userDetail[$i]['AdminMaster']['id']; ?>" <?php if($reporter==$userDetail[$i]['AdminMaster']['id']){echo "selected";}else{} ?>><?php echo $userDetail[$i]['AdminMaster']['first_name']." ".$userDetail[$i]['AdminMaster']['last_name']; ?></option>
			     <?php } ?>
			     </select>

		       </span>	
	  
	  
	  
	  
<div class="clearflds"></div>
<label><?PHP echo __("Well:");?></label><?PHP echo $this->Form->input('well', array('type'=>'text', 'id'=>'well','value'=>$well,'label' => false,'maxlength'=>30,'div' => false)); ?>
<div class="clearflds"></div>
<label><?PHP echo __("Rig:");?></label><?PHP echo $this->Form->input('rig', array('type'=>'text', 'id'=>'rig','value'=>$rig,'label' => false,'maxlength'=>30,'div' => false)); ?>
<div class="clearflds"></div>
<label><?PHP echo __("Operating Time:");?></label>
<select name="operating_time" id="operating_time">
     <option value="0">Select One</option>
     <?php
       for ($i = 0; $i <= 23; $i++)
	{
	       for ($j = 0; $j <= 59; $j+=15)
	       {
			  $time = str_pad($i, 2, '0', STR_PAD_LEFT).':'.str_pad($j, 2, '0', STR_PAD_LEFT);
		  ?>
		   <option value="<?php echo $time; ?>"<?php if($operating_time==$time){echo "selected";}else{} ?>><?php echo $time; ?></option>
	  <?php }
	 } 
     ?>
</select>
<div class="clearflds"></div>
<span><label><?PHP echo __("Lost Time:");?></label><label><?php echo $loss_time; ?></label></span>
<div class="clearflds"></div>
<label><?PHP echo __("Severity:");?></label>
<select id="severity_type" name="severity_type">	
<?php for($i=0;$i<count($incidentSeverityDetail);$i++){?>
<option value="<?php echo $incidentSeverityDetail[$i]['IncidentSeverity']['id']; ?>" <?php if($severity==$incidentSeverityDetail[$i]['IncidentSeverity']['id']){echo "selected";}else{} ?>><?php echo $incidentSeverityDetail[$i]['IncidentSeverity']['type']; ?>
</option>
<?php } ?>
</select>
<div class="clearflds"></div>

<label><?PHP echo __("Incident Location:");?></label>
                         <span id="report_section">
			    <select id="incident_location" name="incident_location">
                             <option value="0">Select One</option>	
                             <?php for($i=0;$i<count($incidentLocation);$i++){?>
			     <option value="<?php echo $incidentLocation[$i]['IncidentLocation']['id']; ?>" <?php if($incidentLocation_id==$incidentLocation[$i]['IncidentLocation']['id']){echo "selected";}else{} ?>><?php echo $incidentLocation[$i]['IncidentLocation']['type']; ?></option>
			     <?php } ?>
			     </select>

		       </span>	
<div class="clearflds"></div>
<label><?PHP echo __("Wellsite Rep:");?></label><?PHP echo $this->Form->input('wellsiterep', array('type'=>'text', 'id'=>'wellsiterep','value'=>$wellsiterep,'label' => false,'maxlength'=>30,'div' => false)); ?>
<div class="clearflds"></div>
<label><?PHP echo __("Client NCR:");?></label><?PHP echo $this->Form->input('clientncr', array('type'=>'text', 'id'=>'clientncr','value'=>$clientncr,'label' => false,'maxlength'=>30,'div' => false)); ?>
<div class="clearflds"></div>			 
<label><?PHP echo __("Field Ticket:");?></label> <?PHP echo $this->Form->input('field_ticket', array('type'=>'text', 'id'=>'field_ticket','value'=>$field_ticket,'label' => false,'div' => false)); ?>
<div class="clearflds"></div>	
<label><?PHP echo __("Client Reviewed:");?><span>*</span></label>
<span id="clientreviewed_section">
			   <span id="client_section">
			       <select id="clientreviewed" name="clientreviewed" onchange="show_client_reviewer();">	
                                <option value="1" <?php if($clientreviewed==1){echo "selected";}else{} ?>>N/A</option>
				<option value="2" <?php if($clientreviewed==2){echo "selected";}else{} ?>>No</option>
				<option value="3" <?php if($clientreviewed==3){echo "selected";}else{} ?>>Yes</option>
			       </select>
		            
		   </span>	
		</span>
<div class="clearflds"></div>
<span id="client_revier_section" <?php echo $clientreviewed_style; ?>>
<label><?PHP echo __("Client Reviewer:"); ?><span>*</span></label><?PHP echo $this->Form->input('clientreviewer', array('type'=>'text', 'id'=>'clientreviewer','value'=>$clientreviewer,'label' => false,'maxlength'=>30,'div' => false)); ?><span class="textcmpul" id="client_reviewer_error"></span>
</span>
<div class="clearflds"></div>


<h2>Classification</h2>
	 
<div class="clearflds"></div>
<label><?PHP echo __("Potential:");?></label>
                         <span id="potential_section">
			    <select id="potential" >	
                 
				   <?php for($i=0;$i<count($potentialDetail);$i++){?>
				   <option value="<?php echo $potentialDetail[$i]['Potential']['id']; ?>" <?php if($potential==$potentialDetail[$i]['Potential']['id']){echo "selected";}else{} ?>><?php echo $potentialDetail[$i]['Potential']['type']; ?></option>
				   <?php } ?>
			    </select>
		
		            
		       </span>
<div class="clearflds"></div>			 
<label><?PHP echo __("Residual:");?></label>
                         <span id="residual_section">
			    <select id="residual">	
                             <?php for($i=0;$i<count($residualDetail);$i++){?>
			     <option value="<?php echo $residualDetail[$i]['Residual']['id']; ?>" <?php if($residual==$residualDetail[$i]['Residual']['id']){echo "selected";}else{} ?>><?php echo $residualDetail[$i]['Residual']['type']; ?></option>
			     <?php } ?>
			    </select>
		       </span>
<div class="clearflds"></div>

<div class="clearflds"></div>			 
<label><?PHP echo __("Summary:");?></label> <?PHP echo $this->Form->input('summary', array('type'=>'textarea', 'id'=>'summary','value'=>$summary,'label' => false,'div' => false, "onkeyup" =>"check_character();")); ?>

<div class="clearflds"></div>	
<label>&nbsp;</label><lable style="font-size: 11px;">Only 100 characters allow for summary</lable>
  
<div class="clearflds"></div>			 
<label><?PHP echo __("Details:");?></label>  <?PHP echo $this->Form->input('details', array('type'=>'textarea', 'id'=>'details','value'=>$details,  'label' => false,'div' => false)); ?>
<div class="clearflds"></div>		  
<?php echo $this->Form->end(); ?>
<div class="buttonpanel">
<?php

if($button=='Update' && $closer_date!='00-00-0000'){
     
}elseif($button=='Update' &&  $closer_date=='00-00-0000' ){?>
  <span id="loader" style="float:left;font-size: 13px;"></span><input type="button" name="save" id="save" class="buttonsave" onclick="add_report_main();" value="<?php echo $button; ?>" />
     
<?php }elseif($button=='Submit'){ ?>

 <span id="loader" style="float:left;font-size: 13px;"></span><input type="button" name="save" id="save" class="buttonsave" onclick="add_report_main();" value="<?php echo $button; ?>" />

<?php } ?>

</div>
</section>
