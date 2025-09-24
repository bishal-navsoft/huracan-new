<section class="adminwrap" id="printdiv">
<script language="javascript" type="text/javascript">
            

    function Clickheretoprint()
{
    
      document.getElementById('print_button_area').style.display="none";     
      var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
     disp_setting+="scrollbars=yes,width=830, height=600, left=100, top=25"; 
     //jQuery('#checkout-review-submit').hide();
  var content_vlue = document.getElementById("printdiv").innerHTML; 
 var docprint=window.open("","",disp_setting); 
   docprint.document.open();
    
  docprint.document.write('<html xmlns="http://www.w3.org/1999/xhtml">');
  docprint.document.write('<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title></title></head>');
   docprint.document.write('<style type="text/css">@charset "utf-8";section.adminwrap{ float:none; background:#f2e3ff; position:relative; width:auto; padding:10px; z-index:1; /*min-height:575px*/height: auto; min-height: 487px;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px; behavior: url(PIE.htc);}section.adminwrap h2.headingtop{ font-size:25px; color:#fff; background:#4a0585; position:relative; display:block;border-bottom:none !important; padding:5px; margin:0 0 5px 0;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px; behavior: url(PIE.htc);}*+html section.adminwrap h2.headingtop{ font-weight:normal;}.admintable_wrap{ float:left; width:83.5%; background:#fff; position:relative; border:1px solid #7b07de;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px; behavior: url(PIE.htc);}.admintable_wrap input[type=checkbox]{ float:none;}.admintable_wrap table tr:hover{ background:#f9f2ff;}.admintable_wrap table tr td.topcell{ background:#e6e6e6; padding:6px; font-size:14px; color:#7b07de; border-bottom:1px solid #c584ff;}.admintable_wrap table tr.sunheadingbg{ background:#f1f1f1;}.admintable_wrap table tr td.subcellheading{ padding:5px; font-size:13px; color:#7b07de; font-weight:bold;}.admintable_wrap table tr td.cellinfo{ padding:5px; font-size:13px; color:#444; padding:5px 5px 5px 20px;}.admintable_wrap table tr td input[type=checkbox]{ float:none;}.view_sub_contentwrap{clear:both; padding:0 0 0 0;border: 2px solid #c9a5e4;margin-top: 10px;}table.viewreporttable{ background:#ccc;}table.viewreporttable tr td.titles{ background:#d8beeb; padding:4px; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; font-weight:bold;}table.viewreporttable tr td{ background:#fff; padding:4px; font-size:12px; color:#666;border-bottom: 2px solid #C9A5E4;border-right: 2px solid #C9A5E4;}table.viewreporttable tr td.label{ font-size:12px; color:#333; font-weight:bold;border-bottom: 2px solid #C9A5E4;border-right: 2px solid #C9A5E4;} table.viewreporttable tr td.label1{ font-size:12px; color:#333; font-weight:normal;border-bottom: 2px solid #C9A5E4;border-right: 2px solid #C9A5E4;font-family: arial}table.reporttableHeader{ background:#ccc;}table.reporttableHeader tr td.titles{ background:#d8beeb; padding:4px; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; font-weight:bold;}table.reporttableHeader tr td{ background:#fff; padding:4px; font-size:12px; color:#666;}table.reporttableHeader tr td.label{ font-size:12px; color:#333; font-weight:bold;}table.reporttablewithoutborder{ background:#ccc;}table.reporttablewithoutborder tr td.titles{ background:#d8beeb; padding:4px; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; font-weight:bold;}table.reporttablewithoutborder tr td{ background:#fff; padding:4px; font-size:12px; color:#666;border-bottom: 2px solid #FFFFFF;border-right: 2px solid #FFFFFF;}table.reporttablewithoutborder tr td.label{ font-size:12px; color:#333; font-weight:bold;border-bottom: 2px solid #FFFFFF;border-right: 2px solid #FFFFFF;}table.viewreporttable{ background:#ccc;}table.viewreporttable tr td.titles{ background:#d8beeb; padding:4px; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; font-weight:bold;}table.viewreporttable tr td{ background:#fff; padding:4px; font-size:12px; color:#666;border-bottom: 2px solid #C9A5E4; border-right: 2px solid #C9A5E4;}table.viewreporttable tr td.label{ font-size:12px; color:#333; font-weight:bold;border-bottom: 2px solid #C9A5E4;    border-right: 2px solid #C9A5E4;}table.viewreporttable tr td.label1{ font-size:12px; color:#333; font-weight:normal;border-bottom: 2px solid #C9A5E4; border-right: 2px solid #C9A5E4;font-family: arial}.buttonview{ position:relative; border:none; background:#5200a6; padding:4px 10px; text-align:center; font-size:13px; color:#fff; cursor:pointer;-webkit-border-radius: 3px;-moz-border-radius:3px;border-radius: 3px; behavior: url(PIE.htc);}.buttonview:hover{ background:#222;}table { border-collapse: collapse; border-spacing: 0;}table.reporttableHeader{ background:#ccc;}table.reporttableHeader tr td.titles{ background:#d8beeb; padding:4px; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; font-weight:bold;}table.reporttableHeader tr td{ background:#fff; padding:4px; font-size:12px; color:#666;}table.reporttableHeader tr td.label{ font-size:12px; color:#333; font-weight:bold;}</style>')
   docprint.document.write('<body onLoad="self.print()" style="-webkit-print-color-adjust:exact;">');
   docprint.document.write(content_vlue);          
   docprint.document.write('</html>');
   docprint.document.close(); 
   docprint.focus();

    
}
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
			<td width="20%" align="left" valign="middle" class="titles">Trainer </td>
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
		 <tr><td colspan="5" align="right" valign="middle" class="label1">&nbsp;<span id="print_button_area"><input type="button" name="save" id="print_button" class="buttonview" onclick="Clickheretoprint();"  value="Print" /></span></td></tr>
		  </table>
     	
               </div>
		 
	     <?php 	  
	     }
	     ?>
  </section>