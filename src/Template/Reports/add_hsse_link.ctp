 <script language="javascript" type="text/javascript">
   var tr_id=new Array();
   var edit_id='<?php echo $edit_id; ?>';
          if(edit_id!=0){ 
	                   
		var spid=edit_id.split(',');
	        for(var i=0;i<spid.length;i++){
                     tr_id.push(spid[i]);
                 }
     
             }else{ 
                         var spid='';
             } 

     function remove_child(rid){
        removeid_cont=new Array();
	
         var child_element=document.getElementById(rid);
	  child_element.parentNode.removeChild(child_element);
    
	  tr_id = jQuery.grep(tr_id, function(value){
		  return value != rid;
	   });


	  var rH= document.getElementById("report_holder").getElementsByTagName("tr");
	  for(var k=0;k<rH.length;k++){
	       removeid_cont.push(rH[k].id);
	      	    
	  }
	  var idString=removeid_cont.toString();
	  alert(idString);
	  document.getElementById('id_holder').value=idString;
	
     }
     
   function add_link(){
   
          var idString='<?php echo $edit_id; ?>';
          var report_info = jQuery.trim(document.getElementById('report_data').value);
          if(report_info!=0){
	       var report_detail_info=report_info.split("~");

	       if(tr_id.length>0){
			 for(var i=0;i<tr_id.length;i++){
			
			      if(tr_id[i]==report_detail_info[0]){
		                    document.getElementById('error_msg').innerHTML=report_detail_info[1]+'  '+report_detail_info[2]+' already added in attachment link';
				    return false;
				
			      }
			 }
		}
     
		tr_id.push(report_detail_info[0]);
		var idString=tr_id.toString();
	
		document.getElementById('id_holder').value=idString;
		$('#report_holder').append('<tr id='+report_detail_info[0]+'><td width="20%" align="left" valign="middle">'+report_detail_info[2]+'</td><td width="20%" align="left" valign="middle">'+report_detail_info[1]+'</td><td width="50%" align="left" valign="middle">'+report_detail_info[3]+'</td><td width="10%" align="left" valign="middle" ><a href="javascript:void(0);" onclick="remove_child(\''+report_detail_info[0]+'\');">Remove</a></td></tr>');
	     
	  }
   }
   function add_hsse_link()
    {

                var dataStr = $("#add_report_link_form").serialize();
                   
                var rootpath='<?php echo $this->webroot ?>';
                document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
                  
                  
                   $.ajax({
                              type: "POST",
                              url: rootpath+"Reports/linkrocess/",
                              data:"data="+dataStr+"&report_no=<?php echo $report_id ?>",
                              success: function(res)
                              {

                                if(res=='ok'){
                                       document.getElementById('loader').innerHTML='&nbsp;<font color="green">Link  Data Added Successfully</font>';
                                       document.location='<?php echo $this->webroot; ?>Reports/add_hsse_link/<?php echo $report_id_val; ?>';
                                 }
                             
                             
                                 
                                 
                     }
                     
            });
            
    
            return false;
 
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
	       $("#investigationdata").removeClass("selectedtab");
	       $("#remidialaction").removeClass("selectedtab");
	       $("#attachment").removeClass("selectedtab");
               $("#link").addClass("selectedtab");
	       $("#clientfeedback").removeClass("selectedtab");
	       $("#view").removeClass("selectedtab");
	     
          });

     
     
  </script>   
    <?php
    //echo '<pre>';
    //print_r($reportDeatil);
    echo $this->Form->create('Report', array('type'=>'file','id' => 'add_report_link_form','name'=>'add_report_link_form','class'=>'adminform'));

    
    
 ?>

<div class="sub_contentwrap fixreport_table" >
    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttable" >
       <tr>
          <td colspan="3" align="center" valign="middle" class="titles">Link <?php echo $report_number; ?></td>
       <tr>
   </table>
</div>
    <div class="looptable_panel">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" >
      <tr>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0"  class="dyntable">
          <tr>
            <td width="31%" align="left" valign="middle">
	       <select id="report_data" name="report_data" >
                 <option value="0">Select One</option>
                    
		          <?php
                          if(count($reportDeatil['Report'])>0){
                                for($i=0;$i<count($reportDeatil['Report']);$i++){?>
                                      <option value="<?php echo 'hsse_'.$reportDeatil['Report'][$i]['id'][0].'~'.$reportDeatil['Report'][$i]['type'][0].'~'.$reportDeatil['Report'][$i]['report_name'][0].'~'.$reportDeatil['Report'][$i]['summary'][0];?>" ><?php echo $reportDeatil['Report'][$i]['report_name'][0]; ?></option>
                                <?php }
                          }?>
                          <?php
                          if(count($reportDeatil['SqReportMain'])>0){
                                for($i=0;$i<count($reportDeatil['SqReportMain']);$i++){?>
                                       <option value="<?php echo 'sq_'.$reportDeatil['SqReportMain'][$i]['id'][0].'~'.$reportDeatil['SqReportMain'][$i]['type'][0].'~'.$reportDeatil['SqReportMain'][$i]['report_name'][0].'~'.$reportDeatil['SqReportMain'][$i]['summary'][0];?>" ><?php echo $reportDeatil['SqReportMain'][$i]['report_name'][0]; ?></option>
                                <?php }
                          }?>
              </select>
	    </td>
	    <td width="59%" align="left" valign="middle" id="error_msg" style="color: red"></td>
            <td width="10%" align="right" valign="middle" >
              <?php if($is_add==1){ ?>
	       <input type="button" name="button" value="Add" class="buttonsave" onclick="add_link();" />
               <?php } ?>
	       </td>
          </tr>
        </table></td>
      </tr>
    </table>
  <table width="100%" border="0" cellspacing="1" cellpadding="0" class="reporttable" id="report_holder">
    <tr>
      <td width="20%" align="left" valign="middle" class="label">Report No</td>
      <td width="20%" align="left" valign="middle" class="label">Type</td>
      <td width="50%" align="left" valign="middle" class="label">Summary</td>
      <td width="10%" align="left" valign="middle" class="label">Action</td>
    </tr>
    <?php
    if(count($linkDetail)>0){
      
       for($i=0;$i<count($linkDetail);$i++){?>
       
        <tr id="<?php echo $linkDetail[$i]['HsseLink']['type'].'_'.$linkDetail[$i]['HsseLink']['report_id']; ?>">
           <td width="20%" align="left" valign="middle" ><a href="<?php echo $this->webroot.$linkDetail[$i]['HsseLink']['controller'].'/'.$linkDetail[$i]['HsseLink']['view'].'/'.base64_encode($linkDetail[$i]['HsseLink']['report_id']); ?>" target="_blank"><?php echo $linkDetail[$i]['HsseLink']['report_num']; ?></a></td>
	   <td width="20%" align="left" valign="middle" ><?php echo $linkDetail[$i]['HsseLink']['report_type']; ?></td>
           <td width="50%" align="left" valign="middle" ><?php echo $linkDetail[$i]['HsseLink']['summary'];?></td>
           <td width="10%" align="left" valign="middle" >
	        <a href="javascript:void(0);" onclick="remove_child('<?php echo $linkDetail[$i]['HsseLink']['type'].'_'.$linkDetail[$i]['HsseLink']['report_id']; ?>');">Remove</a>
            </td>
          </tr>
	  
    <?php }
       
       } ?>
  </table>
  <input type="hidden" id='id_holder'  name='id_holder'  value='<?php echo $edit_id; ?>'/>
  </div>
  <div class="sub_contentwrap fixreport_table" >
    <table width="100%" border="0" cellspacing="1" cellpadding="0" >
      <tr>
	  <td colspan="3" width="100%">
	       <input type="button" name="save" id="save" class="buttonsave" onclick="add_hsse_link();" value="<?php echo $button; ?>" /><span id="loader" style="font-size: 13px;"></span>
	  </td>
      </tr>
    </table>
 </div>


<?php echo $this->Form->end(); ?>
</section>