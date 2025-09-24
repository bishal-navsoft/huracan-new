 <script language="javascript" type="text/javascript">
function dsplay_msg(){
     
     var nr = jQuery.trim(document.getElementById('night_drive_required').value);
     if(nr==1){
	  document.getElementById('msg_display').style.display="block";
     }else{
	 document.getElementById('msg_display').style.display="none"; 
     }
     
     
}
     
     
function add_report_checklist()
{

	
	    var departure_performed = jQuery.trim(document.getElementById('departure_performed').value);
	    var night_drive_required = jQuery.trim(document.getElementById('night_drive_required').value);
	    var report_id ='<?php echo $report_id;?>';
            var dataStr = $("#add_jn_checklist").serialize();
            var rootpath='<?php echo $this->webroot ?>';
	    document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
              $.ajax({
			  type: "POST",
			  url: rootpath+"Jrns/jnchecklistprocess/",
			  data:"data="+dataStr+"&personal_data="+personal_data+"&report_id="+report_id+"&last_sleep="+last_sleep+"&since_sleep="+since_sleep+"&phone_no="+phone_no+"&vehicle_name="+vehicle_name,
			  success: function(res)
			  {
			
			/*		   	
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
                    
                            */
                         }
		 
	});
	

	return false;
 
}

      $(document).ready(function() {
	   $("#main").removeClass("selectedtab");
	   $("#clientdata").removeClass("selectedtab");
	   $("#personnel").addClass("selectedtab");
	   $("#incident").removeClass("selectedtab");
	   $("#investigation").removeClass("selectedtab");
	   $("#remidialaction").removeClass("selectedtab");
	   $("#attachment").removeClass("selectedtab");
	   $("#clientfeedback").removeClass("selectedtab");
	   $("#view").removeClass("selectedtab");
	 
      });



 
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
	   $("#personnel").removeClass("selectedtab");
	   $("#checklist").addClass("selectedtab");
	   $("#attachments").removeClass("selectedtab");
           $("#authorisation").removeClass("selectedtab");
	   $("#view").removeClass("selectedtab");
	 
      });
     
 </script>   
 <?php
    echo $this->Form->create('add_jn_checklist', array('controller' => 'Jnreports','name'=>"add_jn_checklist", 'id'=>"add_jn_checklist", 'method'=>'post','class'=>'adminform'));
 ?>

 <h2><?php echo $heading; ?><span class="textcmpul">Field marked with * are compulsory  </span></h2>
 <br/>

<div class="clearflds"></div>
<label><?PHP echo __("Pre-departure Performed?");?></label>
         <span id="vehicle_section">
			    <select id="departure_performed" name="departure_performed">	
                              <option value="0" <?php if($dp==0){ echo "selected";}else{} ?>>No</option>
			       <option value="1" <?php if($dp==1){ echo "selected";}else{} ?>>Yes</option>
			     </select>
		             
		</span>
<div class="clearflds"></div>
<label><?PHP echo __("Night Driving Required?");?></label>
         <span id="vehicle_section">
			    <select id="night_drive_required" name="night_drive_performed" onchange="dsplay_msg()">	
                              <option value="0" <?php if($nd==0){ echo "selected";}else{} ?>>No</option>
			       <option value="1" <?php if($nd==1){ echo "selected";}else{} ?>>Yes</option>
			     </select>
		             
		</span>
<span id="msg_display" style="display: none">Opration Manager Approval Required</span>	  
<?php echo $this->Form->end(); ?>
<div class="buttonpanel">
 <span id="loader" style="float:left;font-size: 13px;"></span><input type="button" name="save" id="save" class="buttonsave" onclick="add_report_checklist();" value="<?php echo $button; ?>" />
</div>
</section>
