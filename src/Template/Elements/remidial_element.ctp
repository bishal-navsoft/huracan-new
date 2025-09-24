<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style>
#priority_specific_date{
	width: 90px;
}

.fa-calendar:before {
    position: relative;
    content: "\f073";
    left: -42px;
    top: 5px;
}

</style>

<script langauge="javascript" type="text/javascript">

  	$( function() {
    	$( "#priority_specific_date" ).datepicker({
    		dateFormat: 'dd-mm-yy',
	    	onSelect: function(dateText, inst) {
	        	var date = $(this).val();
	        	var remidial_create=date;
	        	
	        	
				var path='<?php echo $this->webroot; ?>'; 
				var remidial_priority = 5; //from database value
				
	  			var responsibility=$("#responsibility").val();

				if(date!=''){
					$.ajax({
					    type: "POST",
					    url: path+"Reports/datecalculate/",
					    data:"remidial_create="+remidial_create+"&remidial_priority="+remidial_priority+"&responsibility="+responsibility,
					    success: function(res)
					    {
					    	
					    	document.getElementById('create_on_error').innerHTML='';
	       					document.getElementById("remedial_content").style.display="block";

							document.getElementById('reminder_date_holder').innerHTML=res;
							document.getElementById('remidial_reminder_data').value=res;
							document.getElementById('remidial_closure_holder').innerHTML=res;
							document.getElementById('remidial_closure_target').value=res;
							
					    }           
			        });
				}
	        
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
	     
  
  

    
      
  
	     
function add_report_remidial(path)
{

            var remidial_create = jQuery.trim(document.getElementById('remidial_create').value);
	    var remidial_priority = jQuery.trim(document.getElementById('remidial_priority').value);
	    var report_no ='<?php echo $reportno;?>';
            
	    if(remidial_create==''){
	       document.getElementById('remidial_create').focus();
	       document.getElementById('create_on_error').innerHTML='Enter created on';
	       return false;
	    }else if(remidial_priority==0){
	       document.getElementById('remidial_priority').focus();
	       document.getElementById('priority_error').innerHTML='Please select priority';
	       return false;
	    }
	    
	  
			 var dataStr = $("#add_report_remidial_form").serialize();
			 var rootpath='<?php echo $this->webroot ?>';
			 document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
			   $.ajax({
				       type: "POST",
				       url: path,
				       data:"data="+dataStr+"&report_no="+report_no+"&countRem=<?php echo $countRem; ?>",
				       success: function(res)
				       {
				
					var resval=res.split("~");
					   if(resval[0]=='fail'){
						document.getElementById('loader').innerHTML='<font color="red">Please try again</font>';  
					  }else if(resval[0]=='add'){
						document.getElementById('loader').innerHTML='<font color="green">Remidial Action Item Added Successfully</font>';
						if(resval[1]=='sq'){
						   document.location='<?php echo $this->webroot; ?>Sqreports/report_sq_remidial_list/<?php echo base64_encode($reportno) ?>';
						}else if(resval[1]=='hsse'){
						   document.location='<?php echo $this->webroot; ?>Reports/report_hsse_remidial_list/<?php echo base64_encode($reportno) ?>';
						}else if(resval[1]=='audit'){
						   document.location='<?php echo $this->webroot; ?>Audits/report_audit_remidial_list/<?php echo base64_encode($reportno) ?>';
						}else if(resval[1]=='job'){
						   document.location='<?php echo $this->webroot; ?>Jobs/report_job_remidial_list/<?php echo base64_encode($reportno) ?>';
						}else if(resval[1]=='suggestion'){
						   document.location='<?php echo $this->webroot; ?>Suggestions/report_suggestion_remidial_list/<?php echo base64_encode($reportno) ?>';
						}
						
						
						
						
					  }else if(resval[0]=='update'){
						document.getElementById('loader').innerHTML='<font color="green">Remidial Action Item Updated Successfully</font>';
						
						if(resval[1]=='sq'){
						   document.location='<?php echo $this->webroot; ?>Sqreports/report_sq_remidial_list/<?php echo base64_encode($reportno) ?>';
						}else if(resval[1]=='hsse'){
						   document.location='<?php echo $this->webroot; ?>Reports/report_hsse_remidial_list/<?php echo base64_encode($reportno) ?>';
						}else if(resval[1]=='audit'){
						   document.location='<?php echo $this->webroot; ?>Audits/report_audit_remidial_list/<?php echo base64_encode($reportno) ?>';
						}else if(resval[1]=='job'){
						   document.location='<?php echo $this->webroot; ?>Jobs/report_job_remidial_list/<?php echo base64_encode($reportno) ?>';
						}else if(resval[1]=='suggestion'){
						   document.location='<?php echo $this->webroot; ?>Suggestions/report_suggestion_remidial_list/<?php echo base64_encode($reportno) ?>';
						}
											
						
					       }
					  
				       }
			      
		     });
		     
		     
	     	  return false;
	
 
}

function retrive_value(){
         
      var path='<?php echo $this->webroot; ?>'; 
	  var remidial_create=$("#remidial_create").val();
	  var responsibility=$("#responsibility").val();
	  var remidial_priority=document.getElementById('remidial_priority').value;
		
	  var priority_specific_date = $('#priority_specific_date').val();	
	  if(remidial_priority==5){
	  	$('#priority_specific_date').show();
	  	$('#dateicon').show();
	  	return false;

	  }else{
	  	$('#priority_specific_date').hide();
	  	$('#priority_specific_date').val('');
	  	$('#dateicon').hide();
	  }

	  if(remidial_priority==0){
	       document.getElementById('reminder_date_holder').innerHTML='';
	       document.getElementById('remidial_reminder_data').value='';
	       document.getElementById("remedial_content").style.display="none";
	       return false;
	       
	  }else if(remidial_priority!=0){
	  if(remidial_create==''){
	      document.getElementById('remidial_create').focus(); 
	      document.getElementById('create_on_error').innerHTML='Enter Created On';
	      for(var i = 0;i < document.getElementById("remidial_priority").length;i++)
		 {
		     if(document.getElementById("remidial_priority").options[i].value == 0){
			    document.getElementById("remidial_priority").selectedIndex = i;
			}
		}
	       return false; 
	  }else{
	     
	      var remidial_create=$("#remidial_create").val();
               document.getElementById('create_on_error').innerHTML='';
	       document.getElementById("remedial_content").style.display="block";

	  }
	   document.getElementById('reminder_date_holder').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
	  $.ajax({
			    type: "POST",
			    url: path+"Reports/datecalculate/",
			    data:"remidial_create="+remidial_create+"&remidial_priority="+remidial_priority+"&responsibility="+responsibility,
			    success: function(res)
			    {
			      
			      //console.log(res); return false;
			                 
				document.getElementById('reminder_date_holder').innerHTML=res;
				document.getElementById('remidial_reminder_data').value=res;
				document.getElementById('remidial_closure_holder').innerHTML=res;
				document.getElementById('remidial_closure_target').value=res;
					
			    }           
	        });
	        
	    
	   return false;
	  }		
}

	/*function plotSpecificDate(){
		var specific_date = $('#priority_specific_date').val();
		if(specific_date!=''){
			console.log(specific_date);
			$('#reminder_date_holder').show();
			$('#reminder_date_holder').html(specific_date);
			$('#remidial_reminder_data').html(specific_date);
		}

	}*/


      function close_reopen(type){
	  
	        var remidial_closer_summary = jQuery.trim(document.getElementById('remidial_closer_summary').value);
	        if(remidial_closer_summary==''){
		  document.getElementById('remidial_closer_summary').focus();
		  document.getElementById('remidial_closer_summary_error').innerHTML='Please enter closer summary';
		  return false;
		 
	       }else{
		  document.getElementById('remidial_closer_summary_error').innerHTML='';  
	       }
	    
                if(type=='close'){
		    document.getElementById('date_holder').innerHTML='<lable style="font-size: 13px;"><?php echo date("d/m/Y"); ?></lable>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="save" id="save" class="buttonview" onclick="close_reopen(\'reopen\');"  value="Reopen" />';
		     document.getElementById('remidial_closure_date').value='<?php echo date('Y-m-d') ?>';
		    
		}else if(type=='reopen'){
		    document.getElementById('date_holder').innerHTML='<input type="button" name="save" id="save" class="buttonview" onclick="close_reopen(\'close\');"  value="Close" />';
		    document.getElementById('remidial_closure_date').value='';
		    document.getElementById('remidial_button_area').style.display='block';
		    
		}
		
     
    }


function check_character(){
  
        var summary =document.getElementById('remidial_summery').value;
        if(summary.length>5){
	    var summary_holder=summary.substring(0,100);
	    document.getElementById('remidial_summery').value=summary_holder;
	  	  
      }
}

function  check_close_character(){
     var rem_sum_summary = document.getElementById('remidial_closer_summary').value;
        if(rem_sum_summary>5){
	    var rem_summary_holder=rem_sum_summary.substring(0,100);
	    document.getElementById('remidial_closer_summary').value=rem_summary_holder;
	  	  
      }
     
}

function change_prority(){
                for(var i = 0;i < document.getElementById("remidial_priority").length;i++)
		 {
		     if(document.getElementById("remidial_priority").options[i].value == 0){
			    document.getElementById("remidial_priority").selectedIndex = i;
			}
		}
                document.getElementById('reminder_date_holder').innerHTML='';
		document.getElementById('remidial_closure_holder').innerHTML='';
		document.getElementById('remedial_content').style.display='none';
		    
}
</script>

<span><label><?PHP echo __("Remedial No:");?></label><label><?php echo $countRem; ?></label></span>
<div class="clearflds"></div>
<label><?PHP echo __("Created On:");?><span>*</span></label><?PHP echo $this->Form->input('remidial_create', array('type'=>'text', 'id'=>'remidial_create','name'=>'remidial_create', 'value'=>$remidial_create, 'readonly'=>'readonly','size'=>30,'maxlength'=>'40', 'label' => false,'div' => false,"onclick"=>"displayDatePicker('remidial_create',this);change_prority();" )); ?><span class="textcmpul" id="create_on_error" ></span>
<div class="clearflds"></div>
<label><?PHP echo __("Created By:");?><span>*</span></label><label style="font-size: 14px;"><?php echo $created_by; ?></label>
<div class="clearflds"></div>
<label><?PHP echo __("Responsibility:");?><span>*</span></label>
                         <span id="responsibility_section">
			    <select id="responsibility" name="responsibility">	
                             <?php for($i=0;$i<count($responsibility);$i++){?>
			     <option value="<?php echo $responsibility[$i]['AdminMaster']['id']; ?>" <?php if($remidial_responsibility==$responsibility[$i]['AdminMaster']['id']){echo "selected";}else{} ?>><?php echo $responsibility[$i]['AdminMaster']['first_name']." ".$responsibility[$i]['AdminMaster']['last_name']; ?></option>
			     <?php } ?>
			     </select>
		             
		       </span>
<div class="clearflds"></div>
<label><?PHP echo __("Summary:");?></label> <?PHP echo $this->Form->input('remidial_summery', array('type'=>'textarea', 'id'=>'remidial_summery','value'=>$remidial_summery, 'label' => false,'div' => false, "onkeyup" =>"check_character();")); ?>
<div class="clearflds"></div>	
<label>&nbsp;</label><span style="font-size: 11px;">Only 100 characters allow for summary</span>
<div class="clearflds"></div>	
<label><?PHP echo __("Action Item:");?></label> <?PHP echo $this->Form->input('remidial_action', array('type'=>'textarea', 'id'=>'remidial_action','value'=>$remidial_action, 'label' => false,'div' => false)); ?>
<div class="clearflds"></div>

<label><?PHP echo __("Priority:");?><span>*</span></label>
			    <select id="remidial_priority" name="remidial_priority" onchange="retrive_value();">	
                              <option value="0" selected="selected">Select One</option>
			     <?php for($i=0;$i<count($priority);$i++){?>
			      <option value="<?php echo $priority[$i]['Priority']['id'];?>" <?php if($remidial_priority==$priority[$i]['Priority']['id']){echo "selected" ;}else{} ?> ><?php echo $priority[$i]['Priority']['type']; ?></option>
			     <?php } ?>
			     </select><span class="textcmpul" id="priority_error" ></span>

<input style="display:none;" type="text"  id="priority_specific_date" readonly="readonly" name="return_depart_date" style="width:90px;"   ><i id="dateicon" style="display:none;" class="fa fa-calendar hasdatepicker"></i>



			    
<span id="remedial_content" <?php echo $remidial_style; ?>>			    
<div class="clearflds"></div>
<span><label><?PHP echo __("Reminder Date:");?></label><label id="reminder_date_holder" style="width: 165px;"><?php echo $remidial_reminder_data; ?></label><?php echo $this->Form->input('remidial_reminder_data', array('type'=>'hidden', 'id'=>'remidial_reminder_data', 'value'=>$remidial_reminder_data)); ?></span>

<div class="clearflds"></div>
<span><label><?PHP echo __("Closure Target:");?></label><label id="remidial_closure_holder" style="width: 165px;"><?php echo $remidial_closure_target; ?></label><?php echo $this->Form->input('remidial_closure_target', array('type'=>'hidden', 'id'=>'remidial_closure_target', 'value'=>$remidial_closure_target)); ?></span>

<div class="clearflds"></div>

</span>
<div class="clearflds"></div>
<?php  if($_SESSION['adminData']['RoleMaster']['id']==1 && $remidial_closure_date==''){ ?>
<label><?PHP echo __("Closure Summary:");?></label> <?PHP echo $this->Form->input('remidial_closer_summary', array('type'=>'textarea', 'id'=>'remidial_closer_summary','value'=>$remidial_closer_summary,  'label' => false, "onkeyup" =>"check_close_character();",'div' => false)); ?><span class="textcmpul" id="remidial_closer_summary_error" ></span>
<div class="clearflds"></div>
<label>&nbsp;</label><span style="font-size: 11px;">Only 100 characters allow for summary</span>
<div class="clearflds"></div>
<label><?PHP echo __("Close Date:");?><span>*</span></label><span id="date_holder"><input type="button" name="save" id="save" class="buttonview" onclick="close_reopen('close');"  value="Close" /></span>
<input type="hidden" name="remidial_closure_date" id="remidial_closure_date" value="<?php echo $remidial_closure_date ?>" />

<?php } ?>


<?php  if($_SESSION['adminData']['RoleMaster']['id']==1 && $remidial_closure_date!=''){ ?>
<label><?PHP echo __("Closure Summary:");?></label> <?PHP echo $this->Form->input('remidial_closer_summary', array('type'=>'textarea', 'id'=>'remidial_closer_summary','value'=>$remidial_closer_summary, 'label' => false,"onkeyup" =>"check_close_character();",'div' => false)); ?><span class="textcmpul" id="remidial_closer_summary_error" ></span>
<div class="clearflds"></div>
<label>&nbsp;</label><span style="font-size: 11px;">Only 100 characters allow for summary</span>
<div class="clearflds"></div>
<label><?PHP echo __("Closure Date:");?><span>*</span></label><span id="date_holder"><label style="font-size: 13px;"><?php echo $remidial_closure_date; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="save" id="save" class="buttonview" onclick="close_reopen('reopen');"  value="Reopen" /></span>
<input type="hidden" name="remidial_closure_date" id="remidial_closure_date" value="<?php echo $remidial_closure_date ?>" />
<div class="clearflds"></div>
<?php } ?>

<div class="clearflds"></div>