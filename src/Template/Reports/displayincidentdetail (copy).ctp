<?php
if($edit_tab==1){
echo $ltype.'~'.$incident_summary.'~'.$immediate_cause;
if(count($couseList)>0){ ?>
~<select  id="immeditaet_sub_cus" name="immeditaet_sub_cus" >
              <option value="0">Select One</option>
        	      <?php for($i=0;$i<count($couseList);$i++){?>
			        <option value='<?php echo $couseList[$i]['ImmediateSubCause']['id']; ?>' <?php if($immediate_sub_cause==$couseList[$i]['ImmediateSubCause']['id']){echo "selected";}else{} ?>><?php echo $couseList[$i]['ImmediateSubCause']['type']; ?></option>
		       <?php } ?>
</select>
<?php }else{
	   echo '~no';
}
echo '~'.$comments;
echo '~';
for($i=0;$i<count($remidialList);$i++){?>
    
    <tr id='<?php echo $remidialList[$i]['HsseRemidial']['id'] ?>'>
        <td width="12%" align="left" valign="middle"  ><?php echo 'REM-'.$remidialList[$i]['HsseRemidial']['id']; ?></td>
        <td width="78%" align="left" valign="middle"  ><?php echo $remidialList[$i]['HsseRemidial']['remidial_closer_summary'] ?></td>
        <td width="10%" align="left" valign="middle" >
            <a href="javascript:void(0);" onclick="remove_child(<?php echo $remidialList[$i]['HsseRemidial']['id'] ?>);">Remove</a>
        </td>
    </tr>
    
<?php }
echo '~';
if(count($rootCuseListVal)>0){
for($i=0;$i<count($rootCuseListVal);$i++)
{
	      
	      if($rootCuseListVal[$i])
	      {
				
			    ?>
			    
			    <tr id='<?php echo $rootCuseList[$i]; ?>'>
                                            <td width="22%" align="left" valign="middle">
	                                                  <select id="<?php echo  'root_cause-'.$rootCuseList[$i]; ?>" name="<?php echo  'root_cause-'.$rootCuseList[$i]; ?>"  onchange="add_root_cause('<?php echo 'root_cause-'.$rootCuseList[$i]; ?>',<?php echo $rootCuseList[$i]; ?>)">
                                                                        <option value="0">Select One</option>	  
										     <?php for($j=0;$j<count($rootCuseListVal[$i]);$j++){?>
										                  <option value="<?php echo $rootCuseListVal[$i][$j]['RootCause']['id']; ?>" <?php if($rootCuseListVal[$i][$j]['RootCause']['id']==$rootCuseList[$i+1]){echo "selected";}else{} ?>><?php echo $rootCuseListVal[$i][$j]['RootCause']['type']; ?></option>
										      <?php } ?>
							   </select>
	                                     </td>
	                                     <td id="error_msg" width="68%" class="textcmpul" style="color: red;"></td>
	                                     <td width="10%" align="right" valign="middle" >&nbsp;</td>
                           </tr>
			    
	      <?php		   
	      }
}
}else{
?>
   <tr id='<?php echo $rootCuseList[$i]; ?>'>
       <td width="22%" align="left" valign="middle">
   <select id="root_cause" name="root_cause"  onchange="add_root_cause('root_cause',0)">
	      <option value="0">Select One</option>	  
	      <?php for($i=0;$i<count($zeroParrent);$i++){ ?>
              <option value="<?php echo $zeroParrent[$i]['RootCause']['id']; ?>"><?php echo $zeroParrent[$i]['RootCause']['type']; ?></option>
	      <?php } ?>	    
	
	      
    </select>
   </td>
  <td id="error_msg" width="68%" class="textcmpul" style="color: red;"></td>
  <td width="10%" align="right" valign="middle" >&nbsp;</td>
  </tr>
	      
<?php	      
}
echo '~'.$rootCuseList[0];
}else{
	      
}

?>