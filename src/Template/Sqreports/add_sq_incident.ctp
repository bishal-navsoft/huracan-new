 <script language="javascript" type="text/javascript">

 function isNumberKey(evt)
    {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
	     return false;
             return true;
     }	     
 



function assign_id(){
  var path=<?php echo $this->webroot; ?>;
  var damage_category = jQuery.trim(document.getElementById('damage_category').value);
  if(damage_category!=0){
  document.getElementById('damage_sub_category_section').innerHTML='<img src="<?php echo $this->webroot; ?>img/ajaxloader.gif" style="margin-left:120px;" /><div class="clearflds"></div>';	
	    $.ajax({
			    type: "POST",
			    url: path+"Sqreports/displaycontentfordamage/",
			    data:"type="+damage_category,
			    success: function(res)
			    {

				$("#damage_sub_category_section").html(res);
			
					 				
			     }
			
				
	        });

	return false;
  }else{
                             document.getElementById('damage_sub_category_section').innerHTML='';
			
  }
}

	     
function add_sq_incident()
{
             
  
            var incident_time = jQuery.trim(document.getElementById('incident_time').value);
	    var incident_severity = jQuery.trim(document.getElementById('incident_severity').value);
	    var date_incident = jQuery.trim(document.getElementById('date_incident').value);
	    var incident_summary = jQuery.trim(document.getElementById('incident_summary').value);
	    var loss_time = jQuery.trim(document.getElementById('loss_time').value);
	    
	    
	    
	    if(document.getElementById('incident_category')){
	       var incident_category = jQuery.trim(document.getElementById('incident_category').value);
	           
	    }else{
	       var incident_category=0
	    }
	    if(document.getElementById('incident_sub_category')){
	       var incident_sub_category = jQuery.trim(document.getElementById('incident_sub_category').value);
	           
	    }else{
	       var incident_sub_category=0
	    }
	  
	    var detail = jQuery.trim(document.getElementById('detail').value);
	    var report_id ='<?php echo $report_id;?>';
	  
	    var curdate='<?php echo date('m-d-Y'); ?>';
            var dataStr = $("#add_report_incident_form").serialize();
            var rootpath='<?php echo $this->webroot ?>';
	    if(date_incident!=''){
	    if(curdate<date_incident){
	       document.getElementById('date_incident_error').innerHTML='Incident date less than current date';
	       document.getElementById('date_incident').focus();
	       return false;
	     }
	    
	    }
 
  
	    /* if(incident_time=='0' && incident_severity=='0' && date_incident=='' && loss_time=='0' && damage_category=='0'  && incident_severity='0' && detail=='' && incident_summary==''){
	      document.getElementById('loader').innerHTML='Please select atleast one value';
	      return false;
	    }*/
	    document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
              $.ajax({
			  type: "POST",
			  url: rootpath+"Sqreports/sqncidentprocess/",
			  data:"data="+dataStr+"&report_id="+report_id+"&incident_no=<?php echo $incident_no ?>",
			  success: function(res)
			  {
			 if(res=='fail'){
				   document.getElementById('loader').innerHTML='<font color="red">Please try again</font>';  
	                     }else if(res=='add'){
				   document.getElementById('loader').innerHTML='<font color="green">Incident Data Added Successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Sqreports/report_sq_incident_list/<?php echo base64_encode($report_id); ?>';
			     }else if(res=='update'){
				   document.getElementById('loader').innerHTML='<font color="green">Incident Data Update Successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Sqreports/report_sq_incident_list/<?php echo base64_encode($report_id); ?>';
				   
                             }
                          
                           
                             
                 }
		 
	});
	

	return false;
 
}



function check_character(){
  
        var incident_summary =document.getElementById('incident_summary').value;
        if(incident_summary.length>5){
	    var summary_holder=incident_summary.substring(0,100);
	    document.getElementById('incident_summary').value=summary_holder;
	  	  
      }
}




 
 </script>
 
 <div class="wrapall">

<aside>
<?php echo $this->Element('left_menu'); ?>
</aside>
 
 <section>
 <?php
           echo $this->Element('sqtab');

  ?>
     <script language="javascript" type="text/javascript">
     
      
     $(document).ready(function() {
	   $("#main").removeClass("selectedtab");
	   $("#clientdata").removeClass("selectedtab");
	   $("#personnel").removeClass("selectedtab");
	   $("#incident").addClass("selectedtab");
	   $("#investigation").removeClass("selectedtab");
	   $("#investigationdata").removeClass("selectedtab");
	   $("#remidialaction").removeClass("selectedtab");
	   $("#attachment").removeClass("selectedtab");
	   $("#clientfeedback").removeClass("selectedtab");
	   $("#view").removeClass("selectedtab");
	 
      });
     
     
     
  </script>   
    <?php
     
    echo $this->Form->create('add_report_incident_form', array('controller' => 'Reports','name'=>"add_report_incident_form", 'id'=>"add_report_incident_form", 'method'=>'post','class'=>'adminform'));
    echo $this->Form->input('id', array('type'=>'hidden', 'id'=>'id', 'value'=>$incident_id));
 ?>

 <h2><?php echo $heading; ?>&nbsp;&nbsp;(<?php echo $report_number; ?>)</h2>
 <br/>
<span><label><?PHP echo __("Incident No:");?></label><label><?php echo $incident_no; ?></label></span>
<div class="clearflds"></div>
<label><?PHP echo __("Time of Incident:");?></label>
<select name="incident_time" id="incident_time">
     <option value="0">Select One</option>
<?php
for ($i = 0; $i <= 23; $i++)
{ for ($j = 0; $j <= 59; $j+=15)
    {
		$time = str_pad($i, 2, '0', STR_PAD_LEFT).':'.str_pad($j, 2, '0', STR_PAD_LEFT);
	?>
        <option value="<?php echo $time; ?>"<?php if($incident_time==$time){echo "selected";}else{} ?>><?php echo $time; ?></option>
<?php } ?>
<?php } ?>

?>
</select>
<div class="clearflds"></div>
<label><?PHP echo __("Date of Incident:");?></label><?PHP echo $this->Form->input('date_incident', array('type'=>'text', 'id'=>'date_incident','name'=>'date_incident','readonly'=>'readonly','value'=>$date_incident,'label' => false,"onclick"=>"displayDatePicker('date_incident',this);",'div' => false)); ?><span class="textcmpul" id="date_incident_error"></span>


<div class="clearflds"></div>
<label><?PHP echo __("Incident Severity:");?></label
			   <span id="incident_severity_section">
			       <select id="incident_severity" name="incident_severity">
			      <?php for($i=0;$i<count($incidentSeverity);$i++){?>
			     <option value="<?php echo $incidentSeverity[$i]['IncidentSeverity']['id']; ?>" <?php if($incident_servirity==$incidentSeverity[$i]['IncidentSeverity']['id']){echo "selected";}else{} ?>><?php echo $incidentSeverity[$i]['IncidentSeverity']['type']; ?></option>
			     <?php } ?>
			     </select>
		        
		   </span>	

<div class="clearflds"></div>



<label> <?PHP echo __("Loss Time:");?></label>
	  <select name="loss_time" id="loss_time">
	   	    <?php
		    for ($i = 0; $i <= 23; $i++)
		    { for ($j = 0; $j <= 59; $j+=15)
			{
				    $time = str_pad($i, 2, '0', STR_PAD_LEFT).':'.str_pad($j, 2, '0', STR_PAD_LEFT);
			    ?>
			    <option value="<?php echo $time; ?>"<?php if($loss_time==$time){echo "selected";}else{} ?>><?php echo $time; ?></option>
		       <?php } ?>
		    <?php } ?>
		    
		    ?>
	  </select>
			   
<div class="clearflds"></div>

<span id="incident_category_section">
        <label><?PHP echo __("Damage Category:");?></label>
	        <select id="damage_category" name="damage_category" onchange="assign_id();">	
                          <option value="0">Select One</option>
		      <?php for($i=0;$i<count($sqdamage);$i++){?>
		         <option value="<?php echo $sqdamage[$i]['SqDamage']['id']; ?>" <?php if($damage_category==$sqdamage[$i]['SqDamage']['id']){echo "selected";}else{} ?>><?php echo $sqdamage[$i]['SqDamage']['type']; ?></option>
		      <?php } ?>
	           </select>
	       

     
          <div class="clearflds"></div>
     
</span>

<span id="damage_sub_category_section">
     
      <?php
          if($sub_category!=''){
		       
	       ?>
	        <label><?PHP echo __("Sub Category:");?></label>
	       <select id="sub_category" name="sub_category" >
			     <option value="0">Select One</option>
                             <?php for($i=0;$i<count($sqdamagesubdetail);$i++){?>
			     <option value="<?php echo $sqdamagesubdetail[$i]['SqDamage']['id']; ?>" <?php if($sub_category==$sqdamagesubdetail[$i]['SqDamage']['id']){echo "selected";}else{} ?> ><?php echo $sqdamagesubdetail[$i]['SqDamage']['type']; ?></option>
		    	     <?php } ?>
		     </select>
	       
	  <?php } ?>
          <div class="clearflds"></div>
     
</span>
<span id="afected_service">
        <label><?PHP echo __("Affected Service:");?></label>
	        <select id="affected_Service" name="affected_Service">	
                          <option value="0">Select One</option>
		      <?php for($i=0;$i<count($sqservice);$i++){?>
		         <option value="<?php echo $sqservice[$i]['SqService']['id']; ?>" <?php if($affected_service==$sqservice[$i]['SqService']['id']){echo "selected";}else{} ?>><?php echo $sqservice[$i]['SqService']['type']; ?></option>
		      <?php } ?>
	           </select>
	       

     
          <div class="clearflds"></div>
     
</span>

<label><?PHP echo __("Incident Summary:");?></label><?PHP echo $this->Form->input('incident_summary', array('type'=>'textarea', 'id'=>'incident_summary','name'=>'incident_summary','value'=>$incident_summary,'label' => false,'div' => false,"onkeyup" =>"check_character();")); ?>
<div class="clearflds"></div>
<label>&nbsp;</label><lable style="font-size: 11px;">Only 100 characters allow for summary</lable>
<div class="clearflds"></div>
<label><?PHP echo __("Details:");?></label><?PHP echo $this->Form->input('detail', array('type'=>'textarea', 'id'=>'detail','name'=>'detail','value'=>$detail,'label' => false,'div' => false)); ?>
<div class="clearflds"></div>


<?php echo $this->Form->end(); ?>
<div class="buttonpanel">
<span id="loader" class="textcmpul"  style="float:left;font-size: 13px;"></span><input type="button" name="save" id="save" class="buttonsave" onclick="add_sq_incident();" value="<?php echo $button; ?>" />

</div>
</section>
