<aside>
<?php echo $this->Element('left_menu'); ?>
</aside>
<section>
      <?php
            echo $this->Element('certificationtab');
    
     ?>
       <script language="javascript" type="text/javascript">
     
      
      $(document).ready(function() {
	       $("#main").removeClass("selectedtab");
	       $("#attachment").removeClass("selectedtab");
	       $("#view").addClass("selectedtab");
	       $("#print").removeClass("selectedtab");
         });

 
     
    
  </script>
      
  <?php 
	     if(count($contentHolder)>0){?>
	          <div class="view_sub_contentwrap">
                    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="viewreporttable">
	     
		<?php   for($c=0;$c<count($contentHolder);$c++){ ?>
		    <tr>
			<td  align="center" valign="middle" colspan="5" class="titles"><?php echo 'Certification list for &nbsp;&nbsp;'.ucwords($contentHolder[$c][0]['certificate_user_name']); ?></td>
		    </tr>
		    <tr>
		
                        <td width="30%" align="left" valign="middle" class="titles">Cert Name</td>
			<td width="20%" align="left" valign="middle" class="titles">Cert Date</td>
			<td width="10%" align="left" valign="middle" class="titles">Valid (Days)</td>
			<td width="20" align="left" valign="middle" class="titles">Expiry Date</td>
			<td width="20%" align="left" valign="middle" class="titles">Trainer</td>
	           </tr>
		  
		<?php        for($s=0;$s<count($contentHolder[$c]);$s++){
		  
		  
		  ?>
			
		<tr>
		
                        <td width="30%" align="left" valign="middle" class="label1"><?php echo $certificatetHolder[$c][$s]['type']; ?></td>
			<td width="20%" align="left" valign="middle" class="label1"><?php echo $contentHolder[$c][$s]['cert_date_val']; ?></td>
			<td width="10%" align="left" valign="middle" class="label1"><?php echo $contentHolder[$c][$s]['valid_date']; ?></td>
			<td width="20" align="left" valign="middle" class="label1"><?php echo $contentHolder[$c][$s]['expire_date_val']; ?></td>
			<td width="20%" align="left" valign="middle" class="label1"><?php echo $contentHolder[$c][$s]['triner']; ?></td>
	           </tr>
			
		 <?php }
		   }
		 ?>
		  </table>
     	
               </div>
		 
	     <?php 	  
	     }
	     ?>
   </section>