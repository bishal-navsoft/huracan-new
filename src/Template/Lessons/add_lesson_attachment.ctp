 <?php echo $this->Html->css('imageupload'); ?>
 <script language="javascript" type="text/javascript">
function confirmFun(me)
    {
	  var imagename=$('#hiddenFile').val();
	  if(confirm("Do You Want To Delete?"))
	  {
	       var rootpath = '<?php echo $this->webroot;?>';
	       $('#loaderRes').html('<br /><img src='+rootpath+'/app/webroot/img/ajax-loader.gif>');
	       var  uploadPath = rootpath+'Lessons/uploadimage/delete/'+encodeURIComponent(imagename);
	       document.getElementById('displayFile').style.display="none";
	       document.getElementById('FileField').innerHTML='';
	       document.getElementById('del').style.display="none";
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
          document.getElementById('displayFile').style.display="block"; 
	  var rootpath = '<?php echo $this->webroot;?>';
	  $('#loaderRes').html('<br /><img src='+rootpath+'/app/webroot/img/ajax-loader.gif>');
	  var  uploadPath = rootpath+'Lessons/uploadimage/upload';
	  document.add_report_attachment_form.action =uploadPath;
	  document.add_report_attachment_form.target = "imgFrame";
	  document.add_report_attachment_form.submit();
     }


	     
function add_lesson_attach()
{
             
            var attachment_description = jQuery.trim(document.getElementById('attachment_description').value);
            var file_name = jQuery.trim(document.getElementById('hiddenFile').value);
	    var report_id ='<?php echo $report_id;?>';
            
	    if(attachment_description==''){
	        document.getElementById('description_error').innerHTML='Enter description';
		document.getElementById('attachment_description').focus();
		return false;
		
	    }else{
		  document.getElementById('description_error').innerHTML='';
		  
	    }
	    
	    if(file_name==''){
	        document.getElementById('image_upload_res').innerHTML='Please upload file';
		
		return false;
		
	    }else{
		 document.getElementById('image_upload_res').innerHTML='';  
	    }
            var dataStr = $("#add_report_attachment_form").serialize();
	       
            var rootpath='<?php echo $this->webroot ?>';
	    document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
              
	      
	       $.ajax({
			  type: "POST",
			  url: rootpath+"Lessons/lessonattachmentprocess/",
			  data:"data="+dataStr+"&report_id="+report_id,
			  success: function(res)
			  {
			 
				  
			if(res=='fail'){
				   document.getElementById('loader').innerHTML='<font color="red">Please try again</font>';  
	                     }else if(res=='add'){
				   document.getElementById('loader').innerHTML='<font color="green">Attachment  Data Added Successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Lessons/report_lesson_attachment_list/<?php echo base64_encode($report_id); ?>';
			     }else if(res=='update'){
				   document.getElementById('loader').innerHTML='<font color="green">Attachment Data Update Successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Lessons/report_lesson_attachment_list/<?php echo base64_encode($report_id); ?>';
				   
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
           echo $this->Element('lessontab');

  ?>
     <script language="javascript" type="text/javascript">
        $(document).ready(function() {
	       $("#main").removeClass("selectedtab");
	       $("#attachment").addClass("selectedtab");
	       $("#link").removeClass("selectedtab");
	       $("#view").removeClass("selectedtab");
	       $("#print").removeClass("selectedtab");
         });

     
     
  </script>  

<h2><?php echo $heading; ?> &nbsp; <?php echo $report_number; ?></h2>
  <?php
     echo $this->Form->create('Lessons', array('type'=>'file','id' => 'add_report_attachment_form','name'=>'add_report_attachment_form','class'=>'adminform'));
     echo $this->Form->input('id', array('type'=>'hidden', 'id'=>'id', 'value'=>$attachment_id));
 ?>
<label>Description</label>
<?PHP echo $this->Form->input('attachment_description', array('type'=>'text', 'id'=>'attachment_description','name'=>'attachment_description','value'=>$description,'label' => false, 'class'=>'textclass', 'div' => false)); ?><span class="textcmpul" id="description_error"></span>
<div class="clearflds"></div>
<label><?PHP echo __("Upload File");?></label>
<div class="upload-here">
      <input type="file" size="24"  name="data[Lessons][file_upload]" id="BrowserHidden" onchange="getElementById('FileField').value = getElementById('BrowserHidden').value;" />
      <div id="BrowserVisible"><input type="text" id="FileField" /></div><input type="button" name="save" id="save" class="buttonsave"  onclick="return uploadFile();" value="Upload" />
</div>
<div class="clearflds"></div>
<label>&nbsp;</label>
<div class="upload_filewrap" >
      <span id="file_upload">
      <span id="displayFile"><?php echo  $imagepath; ?></span>
      <div class="picnav"><a href="javascript:void(0);" id="del" <?php echo $attachmentstyle; ?>  onclick="return confirmFun(this);">Delete</a></div>
      <div class="clearupload"></div>
      </span>
<div class="upload_success" id="image_upload_res"></div>
<div class="clear"></div>
</div>
<div class="clearflds"></div>
<?php echo $this->Form->input('hiddenFile', array('type'=>'hidden','label' => false,'id'=>'hiddenFile','name'=>'hiddenFile','value'=>$imagename)); ?>
 <iframe id="imgFrame" name="imgFrame" src="" style="width:100px;height:100px;border:1px solid #D5E278; display:none"></iframe>
<?php echo $this->Form->end(); ?>
<div class="buttonpanel">
<span id="loader" style="font-size: 13px;"></span> <input type="button" name="save" id="save" class="buttonsave" onclick="add_lesson_attach();" value="<?php echo $button; ?>" />
</div>
<?php echo $this->Form->end(); ?>
</section>