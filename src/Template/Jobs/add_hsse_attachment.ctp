 <script language="javascript" type="text/javascript">
function confirmFun(me)
    {
	  var imagename=$('#hiddenFile').val();
	  if(confirm("Do You Want To Delete?"))
	  {
	       var rootpath = '<?php echo $this->webroot;?>';
	       $('#loaderRes').html('<br /><img src='+rootpath+'/app/webroot/img/ajax-loader.gif>');
	       var  uploadPath = rootpath+'Reports/uploadimage/delete/'+encodeURIComponent(imagename);
	       document.getElementById('file_upload').innerHTML='';
	       document.add_report_attachment_form.action =uploadPath;
	       document.add_report_attachment_form.target = "imgFrame";
	       document.add_report_attachment_form.submit();
	       return true;
	  }
	  else
	  {
	       me.checked = false;
	       return false;
	  }
     }
function uploadFile()
     {
	  var rootpath = '<?php echo $this->webroot;?>';
	  $('#loaderRes').html('<br /><img src='+rootpath+'/app/webroot/img/ajax-loader.gif>');
	  var  uploadPath = rootpath+'Reports/uploadimage/upload';
	  document.add_report_attachment_form.action =uploadPath;
	  document.add_report_attachment_form.target = "imgFrame";
	  document.add_report_attachment_form.submit();
     }


	     
function add_hsse_attach()
{
             
            var attachment_description = jQuery.trim(document.getElementById('attachment_description').value);
            var file_name = jQuery.trim(document.getElementById('hiddenFile').value);
	    var report_id ='<?php echo $report_id;?>';
            
	    if(attachment_description==''){
	        document.getElementById('description_error').innerHTML='Enter description';
		document.getElementById('attachment_description').focus();
		return false;
		
	    }
	    if(file_name==''){
	        document.getElementById('image_upload_res').innerHTML='Please upload file';
		
		return false;
		
	    }
            var dataStr = $("#add_report_attachment_form").serialize();
	       
            var rootpath='<?php echo $this->webroot ?>';
	    document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
              
	      
	       $.ajax({
			  type: "POST",
			  url: rootpath+"Reports/hsseattachmentprocess/",
			  data:"data="+dataStr+"&report_id="+report_id,
			  success: function(res)
			  {
			 
				  
			if(res=='fail'){
				   document.getElementById('loader').innerHTML='<font color="red">Please try again</font>';  
	                     }else if(res=='add'){
				   document.getElementById('loader').innerHTML='<font color="green">Attachment  Data Added Successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Reports/report_hsse_attachment_list/<?php echo base64_encode($report_id); ?>';
			     }else if(res=='update'){
				   document.getElementById('loader').innerHTML='<font color="green">Attachment Data Update Successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Reports/report_hsse_attachment_list/<?php echo base64_encode($report_id); ?>';
				   
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
	       $("#attachment").addClass("selectedtab");
	       $("#clientfeedback").removeClass("selectedtab");
	       $("#view").removeClass("selectedtab");
	     
          });

     
     
  </script>   
    <?php
    echo $this->Form->create('Report', array('type'=>'file','id' => 'add_report_attachment_form','name'=>'add_report_attachment_form','class'=>'adminform'));
    echo $this->Form->input('id', array('type'=>'hidden', 'id'=>'id', 'value'=>$attachment_id));
 ?>

<div class="sub_contentwrap">
     <table width="100%" border="0" cellspacing="1" cellpadding="0" class="investigationtable">
	 <tr>
	    <td colspan="3" align="center" valign="middle" class="titles">Attachment</td>
	  <tr>
	  <tr>
	     <td class="label"><?PHP echo __("Description");?></td>
	     <td><?PHP echo $this->Form->input('attachment_description', array('type'=>'text', 'id'=>'attachment_description','name'=>'attachment_description','value'=>$description,'label' => false, 'class'=>'textclass', 'div' => false)); ?>
	     <span id="description_error" class="textcmpul"></span>
	     </td>
	 </tr>
	 <tr>
	     <td class="label"><?PHP echo __("Upload File");?></td>
	     <td>
		  <input type="file" name="data[Reports][file_upload]"  id="file_upload"  /> <input type="button" name="save" id="save" class="buttonview" onclick="return uploadFile();" value="Upload" />
		  <div id="displayFile"><?php echo $imagepath; ?></div>
		  <a href="javascript:void(0);" id="del" <?php echo $attachmentstyle; ?>  onclick="return confirmFun(this);"><label>Delete</label></a><div id="image_upload_res" style="font-size: 13px;"></div>
		  <?php echo $this->Form->input('hiddenFile', array('type'=>'hidden','label' => false,'id'=>'hiddenFile','name'=>'hiddenFile','value'=>$imagename)); ?>
		  <iframe id="imgFrame" name="imgFrame" src="" style="width:100px;height:100px;border:1px solid #D5E278; display:none"></iframe>
		   <span id="image_upload_res" class="textcmpul"></span>
	     </td>
	  </tr>
     </table>
</div> 
  <div class="sub_contentwrap fixreport_table" >
    <table width="100%" border="0" cellspacing="1" cellpadding="0" >
      <tr>
	  <td colspan="3" width="100%">
	       <input type="button" name="save" id="save" class="buttonsave" onclick="add_hsse_attach();" value="<?php echo $button; ?>" /><span id="loader" style="font-size: 13px;"></span>
	  </td>
      </tr>
    </table>
 </div>


<?php echo $this->Form->end(); ?>
</section>