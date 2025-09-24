 <script language="javascript" type="text/javascript">

 function isNumberKey(evt)
    {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
	     return false;
             return true;
     }	     
 
	     
function add_report_client()
{
             

  
  
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
            var dataStr = $("#add_report_client_form").serialize();
            var rootpath='<?php echo $this->webroot ?>';
	    document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/ajax-loader.gif" />';	
              $.ajax({
			  type: "POST",
			  url: rootpath+"Reports/hsseclientprocess/",
			  data:"data="+dataStr+"&well="+well+"&rig="+rig+"&report_id="+report_id+"&clientncr="+clientncr+"&clientreviewed="+clientreviewed+"&clientreviewer="+clientreviewer+"&wellsiterep="+wellsiterep,
			  success: function(res)
			  {
			   if(res=='fail'){
				   document.getElementById('loader').innerHTML='<font color="red">Please try again</font>';  
	                     }else if(res=='add'){
				   document.getElementById('loader').innerHTML='<font color="green">Client Data Added Successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Reports/add_report_client/<?php echo base64_encode($report_id); ?>';
			     }else if(res=='update'){
				   document.getElementById('loader').innerHTML='<font color="green">Client Data Update Successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Reports/add_report_client/<?php echo base64_encode($report_id); ?>';
				   
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

         echo $this->Element('hssetab');
   
  ?>
     <script language="javascript" type="text/javascript">
     
      
     $(document).ready(function() {
	   $("#main").removeClass("selectedtab");
	   $("#clientdata").addClass("selectedtab");
	   $("#personnel").removeClass("selectedtab");
	   $("#incident").removeClass("selectedtab");
	   $("#investigation").removeClass("selectedtab");
	   $("#investigationdata").removeClass("selectedtab");
	   $("#remidialaction").removeClass("selectedtab");
	   $("#attachment").removeClass("selectedtab");
	   $("#clientfeedback").removeClass("selectedtab");
	   $("#view").removeClass("selectedtab");
	 
      });
     
  
  function show_client_reviewer(){
      var clientreviewed_value = jQuery.trim(document.getElementById('clientreviewed').value);
      if(clientreviewed_value==3){
	  document.getElementById('client_revier_section').style.display="block";
	  
      }else if(clientreviewed_value!=3){
	  document.getElementById('client_revier_section').style.display="none";
	  
      }
  }
     
  </script>   
    <?php  
    echo $this->Form->create('add_report_client_form', array('controller' => 'Reports','name'=>"add_report_client_form", 'id'=>"add_report_client_form", 'method'=>'post','class'=>'adminform'));
    echo $this->Form->input('id', array('type'=>'hidden', 'id'=>'id', 'value'=>$id));
 ?>

 <h2><?php echo $heading; ?>&nbsp;&nbsp;(<?php echo $report_number; ?>)<span class="textcmpul">Field marked with * are compulsory  </span></h2>
 <br/>
     
<label><?PHP echo __("Well:");?></label><?PHP echo $this->Form->input('well', array('type'=>'text', 'id'=>'well','name'=>'well','value'=>$well,'label' => false,'maxlength'=>30,'div' => false)); ?>
<div class="clearflds"></div>
<label><?PHP echo __("Rig:");?></label><?PHP echo $this->Form->input('rig', array('type'=>'text', 'id'=>'rig','name'=>'rig','value'=>$rig,'label' => false,'maxlength'=>30,'div' => false)); ?>
<div class="clearflds"></div>
<label><?PHP echo __("Client NCR:");?></label><?PHP echo $this->Form->input('clientncr', array('type'=>'text', 'id'=>'clientncr','name'=>'clientncr','value'=>$clientncr,'label' => false,'maxlength'=>30,'div' => false)); ?>
<div class="clearflds"></div>
<label><?PHP echo __("Wellsite Rep:");?></label><?PHP echo $this->Form->input('wellsiterep', array('type'=>'text', 'id'=>'wellsiterep','name'=>'wellsiterep','value'=>$wellsiterep,'label' => false,'maxlength'=>30,'div' => false)); ?>
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
<label><?PHP echo __("Client Reviewer:"); ?><span>*</span></label><?PHP echo $this->Form->input('clientreviewer', array('type'=>'text', 'id'=>'clientreviewer','name'=>'clientreviewer','value'=>$clientreviewer,'label' => false,'maxlength'=>30,'div' => false)); ?><span class="textcmpul" id="client_reviewer_error"></span>
</span>
<div class="clearflds"></div>
<div class="buttonpanel">
<span id="loader" style="float:left;font-size: 13px;"></span><input type="button" name="save" id="save" class="buttonsave" onclick="add_report_client();" value="<?php echo $button; ?>" />
</div>
<?php echo $this->Form->end(); ?>
</section>