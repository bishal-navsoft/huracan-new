 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Huracan</title></head><body style="padding:0; margin:0;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><td align="left" valign="top" style="font-size:0;"><img src="<?php echo $this->webroot ;?>images/spacer.png" height="20" width="1" /></td>
  </tr><tr><td align="center" valign="top"><table width="625" border="0" cellspacing="0" cellpadding="0">
      <tr><td align="left" valign="top" style="font-size:0;"><img src="<?php echo $this->webroot ;?>images/newstop.jpg" width="625" height="12" /></td>
      </tr><tr><td align="center" valign="top" bgcolor="#7b07de"><table width="600" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="10" align="left" valign="top" bgcolor="#FFFFFF" style="font-size:0;"><img src="<?php echo $this->webroot ;?>images/topleft.gif"/></td>
            <td width="579" bgcolor="#FFFFFF" style="font-size:0;"><img src="<?php echo $this->webroot ;?>images/spacer.png" height="7" width="1" /></td>
            <td width="11" align="right" valign="top" bgcolor="#FFFFFF" style="font-size:0;"><img src="<?php echo $this->webroot ;?>images/topright.gif"/></td>
            </tr>
          <tr>
            <td align="left" valign="top" bgcolor="#FFFFFF" style="font-size:0;"><img src="<?php echo $this->webroot ;?>images/spacer.png" height="20" width="1" /></td>
            <td align="left" valign="top" bgcolor="#FFFFFF"><table width="579" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" valign="top"><table width="579" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="146" align="left" valign="top" style="font-size:0;"><img src="<?php echo $this->webroot ;?>images/huracan_logo.png" alt="Huracan" title="Huracan" /></td>
                    <td width="433" align="right" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size:13px; color:#333;">Visit us on<br />
                      <a href="#" style="color:#7B07DE; text-decoration:none;">http://huracan.com.au</a></td>
                    </tr>
                  </table></td>
                </tr>
              <tr>
                <td style="font-family: Tahoma, Geneva, sans-serif; font-size:10px; color:#ccc;">................................................................................................................................................................................................</td>
                </tr>
              <tr>
                <td style="font-size:0;"><img src="<?php echo $this->webroot ;?>images/spacer.png" height="10" width="1" /></td>
                </tr>
              <tr>
                <td align="left" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size:13px; color:#333;"><p>Hello &nbsp;&nbsp;<strong><?php echo ucwords($fullname); ?>,</strong></p><br/>
                  <p><strong>ReportNo:&nbsp;<?php echo $report_no ;?></p></br>
                  <p>Report Type:&nbsp; SQ</p></br>
                  <p>Your Remedial Action No:&nbsp;<?php echo $remidialData[0]['SqRemidial']['remedial_no'];?></p></br>
                  <p>Summary: &nbsp;<?php echo $remidialData[0]['SqRemidial']['remidial_summery'];?></p></br>
                  
                  <p>Closure Date:&nbsp;<?php if($remidialData[0]['SqRemidial']['remidial_closure_date']!='0000-00-00'){ echo $remidialData[0]['SqRemidial']['remidial_closure_date']; }?></p></br>
                 </td>
                </tr>
              <tr>
                <td style="font-size:0;"><img src="<?php echo $this->webroot ;?>images/spacer.png" height="10" width="1" /></td>
                </tr>
              <tr>
                <td bgcolor="#f0f0f0"><table width="579" border="0" cellspacing="8" cellpadding="0">
                  <tr>
                    <td width="358" align="left" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size:18px; color:#7B07DE;">Thanks</td>
                    <td width="197" align="left" valign="top"><table width="197" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td height="22" align="left" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size:13px; color:#333;">Hurracan PTY Ltd</td>
                        </tr>
                      <tr>
                        <td height="22" align="left" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size:13px; color:#333;">PO-1070</td>
                        </tr>
                      <tr>
                        <td height="22" align="left" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size:13px; color:#333;">ROMA</td>
                        </tr>
                      <tr>
                        <td height="22" align="left" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size:13px; color:#333;">Queensland 4455</td>
                        </tr>
                      <tr>
                        <td height="22" align="left" valign="top" style="font-family: Tahoma, Geneva, sans-serif; font-size:13px; color:#333;">Email: <a href="#" style="color:#7B07DE; text-decoration:none;">info@huracan.com.au</a></td>
                        </tr>
                      </table></td>
                    </tr>
                  </table></td>
                </tr>
              </table></td>
            <td align="right" valign="top" bgcolor="#FFFFFF" style="font-size:0;"><img src="<?php echo $this->webroot ;?>images/spacer.png" height="20" width="1" /></td>
            </tr>
          <tr>
            <td align="left" valign="top" bgcolor="#FFFFFF" style="font-size:0;"><img src="<?php echo $this->webroot ;?>images/bottomleft.gif"/></td>
            <td bgcolor="#FFFFFF" style="font-size:0;"><img src="<?php echo $this->webroot ;?>images/spacer.png" height="7" width="1" /></td>
            <td align="right" valign="top" bgcolor="#FFFFFF" style="font-size:0;"><img src="<?php echo $this->webroot ;?>images/bottomright.gif"/></td>
            </tr>
          </table></td>
      </tr><tr><td align="left" valign="top" style="font-size:0;"><img src="<?php echo $this->webroot ;?>images/newsbottom.jpg" width="625" height="12" /></td>
      </tr></table></td></tr><tr><td align="left" valign="top" style="font-size:0;"><img src="<?php echo $this->webroot ;?>images/spacer.png" alt="" width="1" height="20" /></td>
  </tr></table></body></html>