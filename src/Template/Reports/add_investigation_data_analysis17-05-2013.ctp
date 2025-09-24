 <script language="javascript" type="text/javascript">

 function isNumberKey(evt)
    {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
	     return false;
             return true;
     }	     
  
 </script>
 
 <div class="wrapall">

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
	   $("#clientdata").removeClass("selectedtab");
	   $("#personnel").removeClass("selectedtab");
	   $("#incident").removeClass("selectedtab");
	   $("#investigation").removeClass("selectedtab");
           $("#investigationdata").addClass("selectedtab");
	   $("#remidialaction").removeClass("selectedtab");
	   $("#attachment").removeClass("selectedtab");
	   $("#clientfeedback").removeClass("selectedtab");
	   $("#view").removeClass("selectedtab");
	 
      });
     
     function incident_detail(){

	document.getElementById('error_msg').innerHTML='';
	var incident_val = jQuery.trim(document.getElementById('incident_val').value);
	if(incident_val!=0){
        var path='<?php echo $this->webroot; ?>';
        
	
         	  $.ajax({
			    type: "POST",
			    url: path+"Reports/displayincidentdetail/",
			    data:"incidentid="+incident_val,
			    success: function(res)
			    {
			      
			          var splitvalue=res.split("~");
				  
				   document.getElementById('loss_content').innerHTML='<img src="<?php echo $this->webroot; ?>img/ajaxloader.gif" />';
				   document.getElementById('loss_content').innerHTML=splitvalue[0];
				   document.getElementById('incident_content').innerHTML=splitvalue[1];
				  
				  if(splitvalue[2] ){
         			        for(var i = 0;i < document.getElementById("immediate_cause").length;i++)
					{
         			           if(document.getElementById("immediate_cause").options[i].value == splitvalue[2] ){
						 document.getElementById("immediate_cause").selectedIndex = i;
					     }
					 }
				  }
				  if(splitvalue[3]){
					if(splitvalue[3]!='no'){
					     
					  document.getElementById('immediate_cause_content').innerHTML='<img src="<?php echo $this->webroot; ?>img/ajaxloader.gif" />';   
					  document.getElementById('immediate_cause_content').innerHTML=splitvalue[3];
					     
					}
				   }else{
					   document.getElementById('immediate_cause_content').innerHTML='';
				         
				   }
				   if(splitvalue[4]){	
				          document.getElementById('comments').value=splitvalue[4];
				   }else{
					  document.getElementById('comments').value='';
				   }
				   if(splitvalue[5]){
				          $('#remedial_holder').append(splitvalue[5]);
				   }else{
				          document.getElementById('remedial_holder').innerHTML='';
				   }
				   if(splitvalue[6]){
					  document.getElementById('root_cause_container').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';
         			          document.getElementById('root_cause_container').innerHTML='';
				          $('#root_cause_container').append(splitvalue[6]);
				   }else{
					  document.getElementById('root_cause_container').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';
				          document.getElementById('root_cause_container').innerHTML='';	
				          $('#root_cause_container').append(splitvalue[2]);
				   }
				
				}
				
	        });

	return false;
	}else{
	   document.location="<?php echo $this->webroot; ?>Reports/add_investigation_data_analysis/<?php echo base64_encode($report_id); ?>";    
	  
	}
     }
     
   function immediate_cause_detail(){
                    var immediate_cause = jQuery.trim(document.getElementById('immediate_cause').value);
		    if(immediate_cause==0){
			 document.getElementById('immediate_cause_content').innerHTML=' ';
		    }else{
		    document.getElementById('immediate_cause_content').innerHTML='<img src="<?php echo $this->webroot; ?>img/ajaxloader.gif" />';   	   
		    var rootpath='<?php echo $this->webroot; ?>';  
                     $.ajax({
			  type: "POST",
			  url: rootpath+"Reports/retrivecause/",
			  data:"data=a&immediate_cause="+immediate_cause,
			  success: function(res)
			  {
	           
			   document.getElementById('immediate_cause_content').innerHTML=res;
	                                          
                        }
		 
	             });
	             return false;	  
      		    }	     
     
    }  
    var tr_id=new Array();
        
     function remove_child(rid){
      tr_id = jQuery.grep(tr_id, function(value){
              return value != rid;
       });
      var child_element=document.getElementById(rid);
      child_element.parentNode.removeChild(child_element);
      
      var idString=tr_id.toString();
      document.getElementById('id_holder').value=idString;  
    }
     
      function add_remedial(){
        document.getElementById('error_msg').innerHTML='';  
      
       var remedial_info = jQuery.trim(document.getElementById('remedial_data').value);
       
       if(remedial_info!=0){
	    
	  var rem_info=remedial_info.split("~");
  
	  if(tr_id.length>0){
	      
		    for(var i=0;i<tr_id.length;i++){

			 if(tr_id[i]==rem_info[0]){
	       document.getElementById('error_msg').innerHTML='REM-'+rem_info[0]+' already added in investigation team';
			       return false;
			   
			 }
		    }
	   }
	   
	   tr_id.push(rem_info[0]);
	   
	   var idString=tr_id.toString();
	   document.getElementById('id_holder').value=idString;
	   $('#remedial_holder').append('<tr id=tr'+rem_info[0]+'><td width="12%" align="left" valign="middle"  >REM-'+rem_info[0]+'</td><td width="78%" align="left" valign="middle"  >'+rem_info[1]+'</td><td width="10%" align="left" valign="middle"  ><a href="javascript:void(0);" onclick="remove_child('+rem_info[0]+');">Remove</a></td></tr>');
	
     }
     

}
     function  add_root_cause(rtc,tdid){

	              var root_cause_id = jQuery.trim(document.getElementById(rtc).value);
		      if(root_cause_id==0){
		 
			    var idArr = [];
			    var trd= document.getElementById("root_cause_container").getElementsByTagName("tr");
                            var deleteId=new Array();
		              
				
                                  for(var i=0;i<trd.length;i++)
				   {
				       var idval=trd[i].id.split("-");
					if(tdid<idval[1]){
						deleteId.push(trd[i].id);	   
					        					     
					}
					
				      
				   }
				
			          for(var j=0 ;j<deleteId.length;j++){
				  
				      var child_element=document.getElementById(deleteId[j]);
                                      child_element.parentNode.removeChild(child_element);
				     
				   
				}
			    
			   
		      }else   if(root_cause_id!=0){
		
			       var trd= document.getElementById("root_cause_container").getElementsByTagName("tr");
                               var deleteId=new Array();
		                  
                                  for(var i=0;i<trd.length;i++)
				   {
		          	       var idval=trd[i].id.split("-");
				   
					if(tdid<idval[1]){
						deleteId.push(trd[i].id);	   
					        					     
					}
					
				      
				   }
				
			          for(var j=0 ;j<deleteId.length;j++){
			
				      var child_element=document.getElementById(deleteId[j]);
                                      child_element.parentNode.removeChild(child_element);
				   }
			 
			 
			 
	                   var rootpath='<?php echo $this->webroot; ?>';
			  
			      $.ajax({
				  type: "POST",
				  url: rootpath+"Reports/retriverootcause/",
				  data:"id="+root_cause_id,
				  
				  success: function(res)
				  {
				      $('#root_cause_container').append(res);
							    
				 				  
				}
			 
			     });
			    return false;
		      }
	  
     }
     
     function add_investigation_data_analysis(){
	  
	  document.getElementById('error_msg').innerHTML='';
	  var select_val= document.getElementById("root_cause_container").getElementsByTagName("select");
	  var idContainer=new Array();
	  for(k=0;k<select_val.length;k++){
	       var ids=select_val[k].id;
	       var selectid=document.getElementById(ids).value;
	       idContainer.push(selectid);
	  }
	  var rootCauseCont=idContainer.toString();
	  
	  
	   var select_cause_val= document.getElementById("cause_content").getElementsByTagName("select");
	  var  causeContainer=new Array();
	  for(j=0;j<select_cause_val.length;j++){
	       var idsCause=select_cause_val[j].id;
	       var selectCauseId=document.getElementById(idsCause).value;
	       causeContainer.push(selectCauseId);
	  }
	   var causeCont=causeContainer.toString();
	  
	  var incident_val = jQuery.trim(document.getElementById('incident_val').value);
	  var id_holder = jQuery.trim(document.getElementById('id_holder').value);
	  var comments = jQuery.trim(document.getElementById('comments').value);

	  var report_id = <?php echo $report_id; ?>;
          if(incident_val==0){
	     
	       document.getElementById('incident_id_error').innerHTML='Please select incident id';
	       document.getElementById('incident_val').focus();
	       
	  }else{
	  
	  var dataStr = $("#add_report_investigation_form").serialize();
     document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
	  var rootpath='<?php echo $this->webroot; ?>';
			      $.ajax({
				  type: "POST",
				  url: rootpath+"Reports/save_date_analysis/",
				  data:"data="+dataStr+"&incident_val="+incident_val+"&rootCauseCont="+rootCauseCont+"&causeCont="+causeCont+"&remidial_holder="+id_holder+"&comments="+comments+"&report_id="+report_id,
				  success: function(res)
				  {
				 
				
				   document.getElementById('loader').innerHTML='<font color="green">Incident Invesitagtion Data  Added Successfully</font>';
					 				  
				}
			 
			     });
	   return false;
	 
       }
     }
  </script>   
    <?php

    
    
    if($edit_incident_id!=0){
       $disabled='disabled="disabled"';
    }else{
      $disabled='';
    }
   
    
    
     
    echo $this->Form->create('add_report_investigation_data_form', array('controller' => 'Reports','name'=>"add_report_investigation_data_form", 'id'=>"add_report_investigation_data_form", 'method'=>'post','class'=>'adminform'));
   
 ?>

 <h2><?php echo $heading; ?>&nbsp;&nbsp;(<?php echo $report_number; ?>)<span class="textcmpul">Field marked with * are compulsory  </span></h2>
 <br/>
     
       <div class="sub_contentwrap fixreport_table" >
	  <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttable" >
	       <tr>
                 <td colspan="3" align="center" valign="middle" class="titles">Incident</td>
      
              <tr>
	  </table>
     </div>
    <div class="sub_contentwrap">
	  <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttable" >
		    <tr>
		       <td align="left" width="22%" valign="middle" class="label"><?PHP echo __("Incident Id:");?><span style="color:red;">*</span></td>
		       <td>
			    <select name="incident_val" id="incident_val" onchange="incident_detail();" <?php echo $disabled; ?> >
				<option value="0">Select One</option>
				   <?php  for($inc=0;$inc<count($incidentdetail);$inc++){
					 if($incidentdetail[$inc]['HsseIncident']['incident_loss']!=0){
					?>
					      <option value="<?php echo $incidentdetail[$inc]['HsseIncident']['id']; ?>" <?php if($incidentdetail[$inc]['HsseIncident']['id']==$edit_incident_id){echo "selected";} ?>>Incident-<?php echo $inc+1; ?></option>
	    
				   <?php  }} ?>
	     
	     
			   </select><span class="textcmpul" id="incident_id_error"></span>
		       </td>
		    </tr>
		    <tr>
			 <td align="left" width="22%" valign="middle" class="label">Loss:</td>
			 <td id="loss_content"></td>
		    </tr>
		    <tr>
			 <td align="left" width="22%" valign="middle" class="label">Incident:</td>
			 <td id="incident_content"></td>
		    </tr>
			 
		   
	  </table>
    </div>
     <div class="sub_contentwrap fixreport_table" >
	  <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttable" >
	       <tr>
                 <td colspan="3" align="center" valign="middle" class="titles">Immediate Cause</td>
      
              <tr>
	  </table>
     </div>
    <div class="sub_contentwrap">
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttable" id="cause_content" >
		    <tr>
		       <td align="left" width="22%" valign="middle" class="label"><?PHP echo __("Immediate Cause:");?></td>
		       <td  valign="middle" >
			    <select name="immediate_cause" id="immediate_cause" onchange="immediate_cause_detail();">
                                           <option value="0">Select One</option>
				   <?php    for($imc=0;$imc<count($immediateCauseDetail);$imc++){ ?>
					    <option value="<?php echo $immediateCauseDetail[$imc]['ImmediateCause']['id']; ?>" <?php if($immediateCauseDetail[$imc]['ImmediateCause']['id']==$immediate_cause){echo "selected";}else{} ?>><?php echo $immediateCauseDetail[$imc]['ImmediateCause']['type']; ?></option>
				  
				    <?php  } ?>
     
     
                            </select>
			    <span id="immediate_cause_content"></span>
		       </td>
		    </tr>
		    <tr><td align="left" width="22%" valign="middle" class="label"><?PHP echo __("Comments:");?></td><td><?PHP echo $this->Form->input('comments', array('type'=>'text', 'id'=>'comments','name'=>'comments','value'=>$comments,'label' => false,'div' => false)); ?></td></tr>
		   
	  </table>
    </div>

    
    <div class="sub_contentwrap fixreport_table" >
	  <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttable" >
	       <tr>
                 <td colspan="3" align="center" valign="middle" class="titles">Remedial Action Item</td>
      
              <tr>
	  </table>
     </div>
    
    <div class="looptable_panel">
    <table width="100%" border="0" cellspacing="0" cellpadding="0"  >
      <tr>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0"  class="dyntable">
          <tr>
            <td width="22%" align="left" valign="middle">
	       <select id="remedial_data" name="remedial_data"   >
                 <option value="0">Select One</option>	  
		    <?php for($i=0;$i<count($remidialData);$i++){?>
<option value="<?php echo $remidialData[$i]['HsseRemidial']['id'].'~'.$remidialData[$i]['HsseRemidial']['remidial_closer_summary']; ?>"><?php echo 'REM-'.$remidialData[$i]['HsseRemidial']['id']; ?></option>
		    <?php } ?>
              </select>
	    </td>
	    <td id="error_msg" width="68%" class="textcmpul" style="color: red;"></td>
	   <td width="10%" align="right" valign="middle" >
	       <?php if($is_add==1){?>
	       <input type="button" name="button" value="Add" class="buttonsave" onclick="add_remedial();" />
	       <?php }else{?>
		    &nbsp;
	      <?php  }?>
	       <input type="hidden" id="id_holder" id="id_holder" value="" />
	   </td>
            
          </tr>
        </table></td>
      </tr>
    </table>
  </div>
    <div class="sub_contentwrap fixreport_table" >
     <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttable" id="remedial_holder">
     </table>	  
    </div>
        <div class="sub_contentwrap fixreport_table" >
	  <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttable" >
	       <tr>
                 <td colspan="3" align="center" valign="middle" class="titles">Root Cause</td>
      
              <tr>
	  </table>
     </div>
         <div class="looptable_panel">
    <table width="100%" border="0" cellspacing="0" cellpadding="0"  >
      <tr>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0"  class="dyntable" id="root_cause_container">
          <tr id="tr-0">
            <td width="22%" align="left" valign="middle">
	       <select id="root_cause" name="root_cause"  onchange="add_root_cause('root_cause',0)">
                 <option value="0">Select One</option>	  
		    <?php for($i=0;$i<count($rootParrentCauseData);$i++){?>
<option value="<?php echo $rootParrentCauseData[$i]['RootCause']['id']; ?>"><?php echo $rootParrentCauseData[$i]['RootCause']['type']; ?></option>
		    <?php } ?>
              </select>
	    </td>
	    <td id="error_msg" width="68%" class="textcmpul" style="color: red;"></td>
	   <td width="10%" align="right" valign="middle" >&nbsp;</td>
            <input type="hidden" id="root_cause_holder" id="root_cause_holder" value="" /> 
          </tr>
	  
        </table></td>
      </tr>
    </table>
  </div> 	
<div class="clearflds"></div>

<?php echo $this->Form->end(); ?>
<div class="buttonpanel">
<span id="loader" class="textcmpul"  style="float:left;font-size: 13px;"></span>
<?php if($button=='Submit'){
     if($is_add==1){?>
     <input type="button" name="save" id="save" class="buttonsave" onclick="add_investigation_data_analysis();" value="<?php echo $button; ?>" />
     <?php }  ?>

<?php }else{
     if($is_edit==1){?>
     
        <input type="button" name="save" id="save" class="buttonsave" onclick="add_investigation_data_analysis();" value="<?php echo $button; ?>" />

<?php } } ?>
</div>
</section>
     <?php

    if($edit_incident_id!=0){?>
   <script language="javascript" type="text/javascript">

         incident_detail();		   
    
   </script> 
    <?php } ?>
    