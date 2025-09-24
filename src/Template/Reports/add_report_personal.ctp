 <script language="javascript" type="text/javascript">

 function isNumberKey(evt)
    {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
	     return false;
             return true;
     }	     
 
	     
function add_report_personal()
{
             
  
            var personal_data = jQuery.trim(document.getElementById('personal_data').value);
	    if(personal_data==0){
	        document.getElementById('personal_data_error').innerHTML='Please select name';
	        return false;
	    }else{
	       document.getElementById('personal_data_error').innerHTML=''; 
	    }
	
	    var since_sleep = jQuery.trim(document.getElementById('since_sleep').value);
	    var report_id ='<?php echo $report_id;?>';
	    var last_sleep = jQuery.trim(document.getElementById('last_sleep').value);
            var dataStr = $("#add_report_personnel_form").serialize();
            var rootpath='<?php echo $this->webroot ?>';
	    document.getElementById('loader').innerHTML='<img src="<?php echo $this->webroot; ?>img/loader.gif" />';	
              $.ajax({
			  type: "POST",
			  url: rootpath+"Reports/hssepersonnelprocess/",
			  data:"data="+dataStr+"&personal_data="+personal_data+"&report_id="+report_id+"&last_sleep="+last_sleep+"&since_sleep="+since_sleep,
			  success: function(res)
			  {
		
		            if(res=='add'){
				   document.getElementById('loader').innerHTML='<font color="green">Personnel Data Added Successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Reports/report_hsse_perssonel_list/<?php echo base64_encode($report_id); ?>';
			     }else if(res=='update'){
				   document.getElementById('loader').innerHTML='<font color="green">Personnel Data Update Successfully</font>';
				   document.location='<?php echo $this->webroot; ?>Reports/report_hsse_perssonel_list/<?php echo base64_encode($report_id); ?>';
				   
                             }else if(res=='avl'){
				   document.getElementById('loader').innerHTML='<font color="red">Personnel Data Already Exist</font>';
				  
				   
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
	   $("#clientdata").removeClass("selectedtab");
	   $("#personnel").addClass("selectedtab");
	   $("#incident").removeClass("selectedtab");
	   $("#investigation").removeClass("selectedtab");
	   $("#investigationdata").removeClass("selectedtab");
	   $("#remidialaction").removeClass("selectedtab");
	   $("#attachment").removeClass("selectedtab");
	   $("#clientfeedback").removeClass("selectedtab");
	   $("#view").removeClass("selectedtab");
	 
      });
     
     
    function retrive_data(){
           var personal_info = jQuery.trim(document.getElementById('personal_data').value);
	   if(personal_info!=0){
	   var usre_info=personal_info.split("~");
	   document.getElementById('postion_seniorty').style.display='block';
	   document.getElementById('postion_seniorty').innerHTML='<label><?PHP echo __("Position:");?></label><label>'+usre_info[0]+'</label><div class="clearflds"></div><label><?PHP echo __("Seniority:");?></label><label>'+usre_info[1]+'</label><div class="clearflds"></div>';
	   }else if(personal_info==0){
	   document.getElementById('postion_seniorty').innerHTML='';
	   document.getElementById('postion_seniorty').style.display='none';
	       
	   }
    }
 </script>   
<?php echo $this->Element('personal_element'); ?>
</section>