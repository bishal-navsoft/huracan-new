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
          
            var trip_date = jQuery.trim(document.getElementById('trip_date').value);
	    var trip_number ='<?php echo $trip_number;?>';
            var business_unit = jQuery.trim(document.getElementById('business_unit').value);
	    var client = jQuery.trim(document.getElementById('client').value);
	    var field_location = jQuery.trim(document.getElementById('field_location').value);
	    var country = jQuery.trim(document.getElementById('country').value);
	    var summary = jQuery.trim(document.getElementById('summary').value);
	    var details = jQuery.trim(document.getElementById('details').value);
	   
	    
	    if(document.getElementById('weed_hygiene').checked==true){
	       
	       var wH=1;
	    }else{
	       var wH=0;
	    }
	    
	
            var departure_time = jQuery.trim(document.getElementById('departure_time').value);
            var currentdate='<?php echo date('m-d-Y'); ?>';
	   if(trip_date==''){
	       document.getElementById('trip_date').focus();
	       document.getElementById('trip_date_error').innerHTML='Please enter event date';
	       return false;
	     } 
	   
	      
            var well = jQuery.trim(document.getElementById('well').value);
	    
	    var rig = jQuery.trim(document.getElementById('rig').value);


	    
	    var dataStr = $("#add_jn_main_form").serialize();
            var rootpath='<?php echo $this->webroot ?>';
     document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
              $.ajax({
			  type: "POST",
			  url: rootpath+"Jrns/jnmainprocess/",
			  data:"data="+dataStr+"&trip_number="+trip_number+"&business_unit="+business_unit+"&client="+client+"&field_location="+field_location+"&country="+country+"&departure_time="+departure_time+"&wH="+wH,
			  
			  success: function(res)
			  {

	         	    var resval=res.split("~");
			      if(resval[0]=='fail'){
				   document.getElementById('loader').innerHTML='<font color="red">Please try again</font>';  
	                     }else if(resval[0]=='add'){
				   document.getElementById('loader').innerHTML='<font color="green">Journey Report Main Added Successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Jrns/add_jn_report_main/'+resval[1];
				   
                             }else if(resval[0]=='update'){
				   document.getElementById('loader').innerHTML='<font color="green">Journey Report Updated Successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Jrns/add_jn_report_main/'+resval[1];
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
         echo $this->Element('journeytab');
	 ?>
     <script language="javascript" type="text/javascript">
         $(document).ready(function() {
	   $("#main").addClass("selectedtab");
	   $("#personnel").removeClass("selectedtab");
	   $("#checklist").removeClass("selectedtab");
	   $("#attachments").removeClass("selectedtab");
	   $("#link").removeClass("selectedtab");
	   $("#view").removeClass("selectedtab");
	 
      });
     </script>	 
	 
<?php	 
     }

    echo $this->Form->create('add_jn_main_form', array('controller' => 'Jnreports','name'=>"add_jn_main_form", 'id'=>"add_jn_main_form", 'method'=>'post','class'=>'adminform'));
    echo $this->Form->input('id', array('type'=>'hidden', 'id'=>'id', 'value'=>$id));
 ?>

 <h2><?php echo $heading; ?><span class="textcmpul">Field marked with * are compulsory  </span> &nbsp;<a href="http://maps.google.com.au/" target='_blank' style="font-size: 12px;">Get Directions</a>&nbsp;&nbsp;&nbsp;<a href="http://wellinfo.com.au" target='_blank' style="font-size: 12px;">Find a well</a></h2>
 <br/>
<div class="clearflds"></div>
<label><?PHP echo __("Trip Date:");?><span>*</span></label>

<input type="text" id="trip_date" name="trip_date" readonly="readonly"  onclick="displayDatePicker('trip_date',this);" value="<?php echo $trip_date; ?>" /><span class="textcmpul" id="trip_date_error"></span>
<div class="clearflds"></div>

         <label><?PHP echo __("Trip Number:");?><span>*</span></label>
         <label><?PHP echo $trip_number;?></label>
     
     <div class="clearflds"></div>
     <label><?PHP echo __("Departure Time:");?></label>
     <select name="departure_time" id="departure_time">
	  <option value="0">Select One</option>
	  <?php
	    for ($i = 0; $i <= 23; $i++)
	     {
		    for ($j = 0; $j <= 59; $j+=15)
		    {
			       $time = str_pad($i, 2, '0', STR_PAD_LEFT).':'.str_pad($j, 2, '0', STR_PAD_LEFT);
		       ?>
			<option value="<?php echo $time; ?>"<?php if($departure_time==$time){echo "selected";}else{} ?>><?php echo $time; ?></option>
	       <?php }
	      } 
	  ?>
     </select>

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
<label><?PHP echo __("Weed Hygiene:");?></label><input type="checkbox" name="weed_hygiene" id="weed_hygiene" <?php echo $checked_weed_hygiene; ?> value="<?php echo $weed_hygiene; ?>" />
  
<div class="clearflds"></div>
<label><?PHP echo __("Well:");?></label><?PHP echo $this->Form->input('well', array('type'=>'text', 'id'=>'well','value'=>$well,'label' => false,'maxlength'=>30,'div' => false)); ?>
<div class="clearflds"></div>
<label><?PHP echo __("Rig:");?></label><?PHP echo $this->Form->input('rig', array('type'=>'text', 'id'=>'rig','value'=>$rig,'label' => false,'maxlength'=>30,'div' => false)); ?>
<div class="clearflds"></div>
<label><?PHP echo __("Cert Number:");?></label><?PHP echo $this->Form->input('cert_number', array('type'=>'text', 'id'=>'cert_number','value'=>$cert_number,'label' => false,'maxlength'=>30,'div' => false)); ?>
<div class="clearflds"></div>
<label><?PHP echo __("Summary:");?></label> <?PHP echo $this->Form->input('summary', array('type'=>'textarea', 'id'=>'summary','value'=>$summary,'label' => false,'div' => false, "onkeyup" =>"check_character();")); ?>
<div class="clearflds"></div>	
<label>&nbsp;</label><lable style="font-size: 11px;">Only 100 characters allow for summary</lable>
  
<div class="clearflds"></div>			 
<label><?PHP echo __("Details:");?></label>  <?PHP echo $this->Form->input('details', array('type'=>'textarea', 'id'=>'details','value'=>$details,  'label' => false,'div' => false)); ?>
<div class="clearflds"></div>		  
<?php echo $this->Form->end(); ?>
<div class="buttonpanel">
 <span id="loader" style="float:left;font-size: 13px;"></span><input type="button" name="save" id="save" class="buttonsave" onclick="add_report_main();" value="<?php echo $button; ?>" />

</div>
</section>
