  <?php
 $insVes=array();
 $edit_id='';
 if(count($investigation_team)>0){ 
     
            for($i=0;$i<count($investigation_team);$i++){
	        $insVes[]=$investigation_team[$i];
	    }
	  $edit_id= implode(',',$insVes); 
	  
 }else{
    $insVes=array();
     $edit_id='';
 }
 
 
 
 
 ?>
 <aside>
<?php echo $this->Element('left_menu'); ?>
</aside>
 <section>
 <?php

         echo $this->Element('sqtab');
   
  ?>
  
<script language="javascript" type="text/javascript">
      var tr_id=new Array();
      <?php if($edit_id!=''){ ?>
      var eid='<?php echo  $edit_id; ?>';
      var spid=eid.split(',');
      for(var i=0;i<spid.length;i++){
	   tr_id.push(spid[i]);
       }
     
      <?php }else{ ?>
      var spid='';
      <?php } ?>
 function remove_child(rid){
      tr_id = jQuery.grep(tr_id, function(value){
              return value != rid;
       });
      var child_element=document.getElementById(rid);
      child_element.parentNode.removeChild(child_element);
      
      var idString=tr_id.toString();
      document.getElementById('id_holder').value=idString;  
 }
 function add_seniority(){
     var idString='<?php echo $edit_id; ?>';
     document.getElementById('error_msg').innerHTML='';
     var personal_info = jQuery.trim(document.getElementById('personal_data').value);
      if(personal_info!=0){
	  var usre_info=personal_info.split("~");
    
	  if(tr_id.length>0){
		    for(var i=0;i<tr_id.length;i++){
			 if(tr_id[i]==usre_info[3]){
	       document.getElementById('error_msg').innerHTML=usre_info[0]+' already added in investigation team';
			       return false;
			   
			 }
		    }
	   }

	   tr_id.push(usre_info[3]);
	   
	   var idString=tr_id.toString();
	   
	   document.getElementById('id_holder').value=idString;
	   $('#seniority_holder').append('<tr id='+usre_info[3]+'><td width="30%" align="left" valign="middle"  >'+usre_info[0]+'</td><td width="30%" align="left" valign="middle"  >'+usre_info[2]+'</td><td width="30%" align="left" valign="middle"  >'+usre_info[1]+'</td><td width="10%" align="left" valign="middle"  ><a href="javascript:void(0);" onclick="remove_child('+usre_info[3]+');">Remove</a></td></tr>');
	
     }
}
   


     
      
     $(document).ready(function() {
	   $("#main").removeClass("selectedtab");
	   $("#clientdata").removeClass("selectedtab");
	   $("#personnel").removeClass("selectedtab");
	   $("#incident").removeClass("selectedtab");
	   $("#investigation").addClass("selectedtab");
	   $("#remidialaction").removeClass("selectedtab");
	   $("#attachment").removeClass("selectedtab");
	   $("#clientfeedback").removeClass("selectedtab");
	   $("#view").removeClass("selectedtab");
	 
      });
     
     
     
  </script>
     <?php echo $this->Form->create('add_report_investigation_form', array('controller' => 'Sqreports','name'=>"add_report_investigation_form", 'id'=>"add_report_investigation_form", 'method'=>'post','class'=>'investigation'));
 echo $this->Element('investigation_element'); ?>    
   
<div class="buttonpanel">
<input type="hidden" id='report_id'  name='report_id'  value='<?php echo $report_id; ?>' />       
<input type="hidden" id='id_holder'  name='id_holder'  value='<?php echo $id_holder; ?>' />
<span id='loader' style="float:left;font-size: 13px;"></span>
<?php if($is_add==1){ ?>
<input type="button" name="button" value="<?php echo $button; ?>" class="buttonsave" onclick="add_report_investigation('<?php echo $this->webroot.'Sqreports/save_sq_investigation' ?>');"  />
<?php } ?>
</div>
<?php echo $this->Form->end(); ?>
</section>