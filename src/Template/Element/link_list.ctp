       <select id="report_data" name="report_data" >
                                           <option value="0">Select One</option>
                                            <?php
                                           if(count($reportDeatil['Report'])>0){
                                                 for($i=0;$i<count($reportDeatil['Report']);$i++){?>
                                                       <option value="<?php echo 'hsse_'.$reportDeatil['Report'][$i]['id'][0]; ?>" ><?php echo $reportDeatil['Report'][$i]['report_name'][0]; ?></option>
                                                 <?php }
                                           } 
                                           if(count($reportDeatil['SqReportMain'])>0){
                                                 for($i=0;$i<count($reportDeatil['SqReportMain']);$i++){?>
                                                        <option value="<?php echo 'sq_'.$reportDeatil['SqReportMain'][$i]['id'][0];?>" ><?php echo $reportDeatil['SqReportMain'][$i]['report_name'][0]; ?></option>
                                                 <?php }
                                           }
                                           
                                            if(count($reportDeatil['JobReportMain'])>0){
                                                 for($i=0;$i<count($reportDeatil['JobReportMain']);$i++){?>
                                                       <option value="<?php echo 'job_'.$reportDeatil['JobReportMain'][$i]['id'][0]; ?>" ><?php echo $reportDeatil['JobReportMain'][$i]['report_name'][0]; ?></option>
                                                 <?php }
                                           }  
					   if(count($reportDeatil['AuditReportMain'])>0){
                                                 for($i=0;$i<count($reportDeatil['AuditReportMain']);$i++){?>
                                                       <option value="<?php echo 'audit_'.$reportDeatil['AuditReportMain'][$i]['id'][0]; ?>" ><?php echo $reportDeatil['AuditReportMain'][$i]['report_name'][0]; ?></option>
                                                 <?php }
                                           } 
					   if(count($reportDeatil['JnReportMain'])>0){
                                                 for($i=0;$i<count($reportDeatil['JnReportMain']);$i++){?>
                                                       <option value="<?php echo 'jn_'.$reportDeatil['JnReportMain'][$i]['id'][0]; ?>" ><?php echo $reportDeatil['JnReportMain'][$i]['report_name'][0]; ?></option>
                                                 <?php }
                                           }
                                           if(count($reportDeatil['LessonMain'])>0){
                                                 for($i=0;$i<count($reportDeatil['LessonMain']);$i++){?>
                                                       <option value="<?php echo 'lesson_'.$reportDeatil['LessonMain'][$i]['id'][0]; ?>" ><?php echo $reportDeatil['LessonMain'][$i]['report_name'][0]; ?></option>
                                                 <?php }
                                           }if(count($reportDeatil['DocumentMain'])>0){
                                                 for($i=0;$i<count($reportDeatil['DocumentMain']);$i++){?>
                                                       <option value="<?php echo 'document_'.$reportDeatil['DocumentMain'][$i]['id'][0]; ?>" ><?php echo $reportDeatil['DocumentMain'][$i]['report_name'][0]; ?></option>
                                                 <?php }
                                           }if(count($reportDeatil['SuggestionMain'])>0){
                                                 for($i=0;$i<count($reportDeatil['SuggestionMain']);$i++){?>
                                                       <option value="<?php echo 'suggestion_'.$reportDeatil['SuggestionMain'][$i]['id'][0]; ?>" ><?php echo $reportDeatil['SuggestionMain'][$i]['report_name'][0]; ?></option>
                                                 <?php }
                                            }
					    if(count($reportDeatil['JhaMain'])>0){
                                                 for($i=0;$i<count($reportDeatil['JhaMain']);$i++){?>
                                                       <option value="<?php echo 'jha_'.$reportDeatil['JhaMain'][$i]['id'][0]; ?>" ><?php echo $reportDeatil['JhaMain'][$i]['report_name'][0]; ?></option>
                                                 <?php }
                                            } 
                                          ?>
                               </select>