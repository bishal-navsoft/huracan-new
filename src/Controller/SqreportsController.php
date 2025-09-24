<?PHP
class SqreportsController extends AppController
{
	public $name = 'Sqreports';
	public $uses = array('SqReportMain','Report','DocumentMain','RemidialEmailList','LessonMain','JnReportMain','AuditReportMain','Incident','IncidentLocation','WellStatus','Welldata','SqWellData','SqDamage','SqService','SqReportIncident','SqInvestigation','SqRemidial','SqClientfeedback','BusinessType','Fieldlocation','Client','IncidentLocation','IncidentSeverity','Country','AdminMaster','SqPersonnel','RolePermission','RoleMaster','AdminMenu','IncidentCategory','SqDamage','IncidentSubCategory','Residual','SqAttachment','Priority','ImmediateCause','ImmediateSubCause','RootCause','SqInvestigationData','Potential','SqLink','SqReportIncident');
	public $helpers = array('Html','Form','Session','Js');
	//public $components = array('RequestHandler', 'Cookie', 'Resizer');
	
        public function report_sq_list(){
		$this->report_sq_link();
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
		$this->grid_access();		
		$this->layout="after_adminlogin_template";
	
		if(!empty($this->request->data))
		{
			$action = $this->request->data['Sqreports']['action'];
			$this->set('action',$action);
			$limit = $this->request->data['Sqreports']['limit'];
			$this->set('limit', $limit);
		}
		else
		{
			$action = 'all';
			$this->set('action','all');
			$limit =50;
			$this->set('limit', $limit);
		}
		$this->Session->write('action', $action);
		$this->Session->write('limit', $limit);
			
		unset($_SESSION['filter']);
		unset($_SESSION['value']);
		unset($_SESSION['idBollen']);
		$sqIdBoolen=array();
		$condition="";
		$condition = "SqReportMain.isdeleted = 'N'";
		
		$adminA = $this->SqReportMain->find('all' ,array('conditions' => $condition,'order' => 'SqReportMain.id DESC','limit'=>$limit));
		for($i=0;$i<count($adminA);$i++){
			if(($_SESSION['adminData']['AdminMaster']['id']==$adminA[$i]['SqReportMain']['created_by']) || ($_SESSION['adminData']['RoleMaster']['id']==1)){
			   array_push($sqIdBoolen,1);	
				
			}else{
			  array_push($sqIdBoolen,0);
			}
			
		}
		$this->Session->write('sqIdBoolen',$sqIdBoolen);
		
	}
	public function get_all_report($action ='all')
	{
		Configure::write('debug', '2');  
		$this->layout = "ajax"; 
		$this->_checkAdminSession();
		$condition="";
		$condition = "SqReportMain.isdeleted = 'N'";
                if(isset($_REQUEST['filter'])){
		switch($_REQUEST['filter']){
			case'report_no':
		        $condition .= "AND ucase(SqReportMain.".$_REQUEST['filter'].") like '".$_REQUEST['value']."%'";	
			break;
		        case'client_name':
			$clientCondition= " ucase(Client.name) like '".$_REQUEST['value']."%'";
			$clientDetatail=$this->Client->find('all', array('conditions' =>$clientCondition));
          		$condition .= "AND SqReportMain.Client =".$clientDetatail[0]['Client']['id'];	
			break;
		        case'creater_name':
			$spliNAME=explode(" ",$_REQUEST['value']);
			$spliLname=$spliNAME[count($spliNAME)-1];
			$spliFname=$spliNAME[0];
			$adminCondition="AdminMaster.first_name like '%".$spliFname."%' AND AdminMaster.last_name like '%".$spliLname."%'";
			$userDetail = $this->AdminMaster->find('all',array('conditions'=>$adminCondition));
			$addimid=$userDetail[0]['AdminMaster']['id'];
			$condition .= "AND SqReportMain.created_by ='".$addimid."'";	
			break;
		        case'event_date_val':
		         		
		        $explodemonth=explode('/',$_REQUEST['value']);
			$day=$explodemonth[0];
			$month=date('m', strtotime($explodemonth[1]));
			$year="20$explodemonth[2]";
			$createon=$year."-".$month."-".$day;
			$condition .= "AND SqReportMain.event_date ='".$createon."'";	
		        break; 
		}
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}
		
		$count = $this->SqReportMain->find('count' ,array('conditions' => $condition)); 
		$adminArray = array();
		

				
		 $adminA = $this->SqReportMain->find('all' ,array('conditions' => $condition,'order' => 'SqReportMain.id DESC','limit'=>$limit)); 
          
	        $sqIdBoolen=array();
		unset($_SESSION['sqIdBoolen']);
		$i = 0;
		foreach($adminA as $rec)
		{
		
		
		   if(($_SESSION['adminData']['AdminMaster']['id']==$rec['SqReportMain']['created_by']) || ($_SESSION['adminData']['RoleMaster']['id']==1)){
				array_push($sqIdBoolen,1);
				$adminA[$i]['SqReportMain']['edit_permit'] ="false";
				$adminA[$i]['SqReportMain']['view_permit'] ="false";
				$adminA[$i]['SqReportMain']['delete_permit'] ="false";
				$adminA[$i]['SqReportMain']['block_permit'] ="false";
				$adminA[$i]['SqReportMain']['unblock_permit'] ="false";
				$adminA[$i]['SqReportMain']['checkbox_permit'] ="false";
				
				if($rec['SqReportMain']['isblocked'] == 'N')
			            {
					$adminA[$i]['SqReportMain']['blockHideIndex'] = "true";
					$adminA[$i]['SqReportMain']['unblockHideIndex'] = "false";
				        
			         }else{
					$adminA[$i]['SqReportMain']['blockHideIndex'] = "false";
					$adminA[$i]['SqReportMain']['unblockHideIndex'] = "true";
					
			        }
				
					
			}else{
				array_push($sqIdBoolen,0);
				$adminA[$i]['SqReportMain']['edit_permit'] ="true";
				$adminA[$i]['SqReportMain']['view_permit'] ="false";
				$adminA[$i]['SqReportMain']['delete_permit'] ="true";
				$adminA[$i]['SqReportMain']['block_permit'] ="true";
				$adminA[$i]['SqReportMain']['unblock_permit'] ="true";
			        $adminA[$i]['SqReportMain']['blockHideIndex'] = "true";
				$adminA[$i]['SqReportMain']['unblockHideIndex'] = "true";
				$adminA[$i]['SqReportMain']['checkbox_permit'] ="true";
				
				
			}
		
			$adminA[$i]['SqReportMain']['incident_serverity'] ='<font color='.$rec['IncidentSeverity']['color_code'].'>'.$rec['IncidentSeverity']['type'].'</font>';
			if($rec['SqReportMain']['client']==0){
			  $adminA[$i]['SqReportMain']['client_name']='N/A';	
			}else{
			  $adminA[$i]['SqReportMain']['client_name'] =$rec['Client']['name'];	
			}
			$adminA[$i]['SqReportMain']['creater_name'] =$rec['AdminMaster']['first_name']." ".$rec['AdminMaster']['last_name'];
	                $eventdate=explode("-",$rec['SqReportMain']['event_date']);
			$evDT=date("d/M/y", mktime(0, 0, 0, $eventdate[1],$eventdate[2],$eventdate[0]));
			$adminA[$i]['SqReportMain']['event_date_val']=$evDT;
		  
		    $i++;
		}
		
                for($i=0;$i<count($adminA);$i++){
		        if(count($adminA[$i]['SqReportIncident'])>0){
			   for($j=0;$j<count($adminA[$i]['SqReportIncident']);$j++){
				$hm=explode(":",$adminA[$i]['SqReportIncident'][$j]['loss_time']);
				$hour=$hm[0];
				$minute=$hm[1];
				$lossTime['hr'][]=$hour;
				$lossTime['min'][]=$minute;
			    
			    }
					 
		         $hoursum=array_sum($lossTime['hr']);
			 $minsum=array_sum($lossTime['min']);
				if($minsum<60){
				       
				       if($hoursum<10){
					       $hoursum="0$hoursum";
					       
				       }else{
					   $hoursum=$hoursum;	
				       }
				       
				       if($minsum<10){
					  $minsum="0$minsum";	
				       }else{
					 $$minsum=$minsum;		
				       }
				       
				   $hour_minute=$hoursum.":".$minsum;	
				}elseif($minsum>60){
				       $hourminute=round($minsum/60);
				       
				       $minute=$minsum%60;
				       $hr=$hourminute+$hoursum;
				       if($hr<10){
					       $hr="0$hr";
					       
				       }else{
					   $hr=$hr;	
				       }
				       
				       if($minute<10){
					  $minute="0$minute";	
				       }else{
					 $minute=$minute;		
				       }
				       
				       $hour_minute=$hr.":".$minute;	
				}
			$adminA[$i]['SqReportMain']['loss_time']=$hour_minute;      
		        }else{
			$adminA[$i]['SqReportMain']['loss_time']='00:00';	
			}
		}
		if($count==0){
			$adminArray=array();
	        }else{
			$adminArray = Set::extract($adminA, '{n}.SqReportMain');
		      }
		
		
		$this->Session->write('sqIdBoolen',$sqIdBoolen);
		$this->set('total', $count);  //send total to the view
		$this->set('admins', $adminArray);  //send products to the view
         	$this->set('status', $action);
	}

	
	
	
	function sqreport_block($id = null)
	{
          
	
		if(!$id)
		{

			   $this->redirect(array('action'=>report_sq_list), null, true);
		}
		else
		{
			 $idArray = explode("^", $id);
			 
			 
		
			foreach($idArray as $id)
			{
				   $id = $id;
				   $this->request->data['SqReportMain']['id'] = $id;
				   $this->request->data['SqReportMain']['isblocked'] = 'Y';
				   $this->SqReportMain->save($this->request->data,false);				
			}	     
			exit;			 
		}
	}
	
	function sqreport_unblock($id = null)
	{

		
		if(!$id)
		{
	
			$this->redirect(array('action'=>report_sq_list), null, true);
		}
		else
		{
			$idArray = explode("^", $id);
		
			foreach($idArray as $id)
			{
				$id = $id;
				$this->request->data['SqReportMain']['id'] = $id;
				$this->request->data['SqReportMain']['isblocked'] = 'N';
				$this->SqReportMain->save($this->request->data,false);
			}	
			exit;
		 
		}
	}
	
	function sqreport_main_delete()
	     {
		      $this->layout="ajax";
		      
	      
		      if($this->data['id']!=''){
			$idArray = explode("^", $this->data['id']);
                           foreach($idArray as $id)
			       {
					 
			
			       
				    $deleteSq_incident = "DELETE FROM `sq_report_incidents` WHERE `report_id` = {$id}";
				    $dHI=$this->SqReportIncident->query($deleteSq_incident);
				    
				    $deleteSq_investigation_datas = "DELETE FROM `sq_investigation_datas` WHERE `report_id` = {$id}";
				    $dHINV=$this->SqInvestigationData->query($deleteSq_investigation_datas);
				    
				    $deleteSq_personnel_datas = "DELETE FROM `sq_personnels` WHERE `report_id` = {$id}";
				    $dHP=$this->SqPersonnel->query($deleteSq_personnel_datas);
				    
				     $deleteSq_remidials="DELETE FROM `sq_remidials` WHERE `report_no` = {$id}";
				     $sr=$this->SqRemidial->query($deleteSq_remidials);
				     $deleteSq_attachments="DELETE FROM `sq_attachments` WHERE `report_id` = {$id}";
				     $sa=$this->SqAttachment->query($deleteSq_attachments);
				     
				     $deleteSq_clients="DELETE FROM `sq_well_datas` WHERE `report_id` = {$id}";
				     $swd=$this->SqWellData->query($deleteSq_clients);
				     
				     $deleteSq_remidials_email = "DELETE FROM `remidial_email_lists` WHERE `report_id` = {$id} AND `report_type`='sq'";
                                     $dHRem=$this->RemidialEmailList->query($deleteSq_remidials_email);
							
				     $deleteSq_mains="DELETE FROM `sq_report_mains` WHERE `id` = {$id}";
				     $srm=$this->SqReportMain->query($deleteSq_mains);

				 	       
					
					  
			       }
		
			       echo 'ok';
			        exit;
		      }else{
			 $this->redirect(array('action'=>report_hsse_list), null, true);
		      }
			 
			      
			      
			       
	}
	
	
	

	
        public function add_sq_report_main($id=null)
		
	{
		$this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
		 $this->grid_access();
                 $this->layout="after_adminlogin_template";
                 $incidentDetail = $this->Incident->find('all', array('conditions' => array('incident_type' =>'sq')));
                 $incidentLocation = $this->IncidentLocation->find('all');
                 $this->set('incidentLocation',$incidentLocation);
		 $businessDetail = $this->BusinessType->find('all',array('conditions' => array('rtype' =>'all')));
		 $fieldlocationDetail = $this->Fieldlocation->find('all');
		 $clientDetail = $this->Client->find('all');
		 $incidentSeverityDetail = $this->IncidentSeverity->find('all',array('conditions' => array('servrity_type' =>'sq')));
	 	 $residualDetail = $this->Residual->find('all');
		 $potentialDetail = $this->Potential->find('all');
		 $countryDetail = $this->Country->find('all');
		 $userDetail = $this->AdminMaster->find('all');
		 $this->set('residualDetail',$residualDetail);
		 $this->set('potentialDetail',$potentialDetail);
		 $this->set('incidentDetail',$incidentDetail);
		 $this->set('businessDetail',$businessDetail);
		 $this->set('fieldlocationDetail',$fieldlocationDetail);
		 $this->set('clientDetail',$clientDetail);
		 $this->set('country',$countryDetail);
		 $this->set('userDetail',$userDetail);
		 $this->set('incidentSeverityDetail',$incidentSeverityDetail);
		 $this->set('operating_time','');
		
		
		 if($id==null)
		 {
          
			$this->set('id','0');
			$this->set('event_date','');
			$this->set('since_event_hidden',0);
			$this->set('heading','Add SQ Report (Main)');
			$this->set('button','Submit');
			$this->set('report_no','');
			$this->set('closer_date','00-00-0000');
			$this->set('incident_type','');
			$this->set('created_date','');
			$this->set('business_unit','');
			$this->set('client','');
			$this->set('field_location','');
			$this->set('incident_severity','');
			$this->set('recordable','');
			$this->set('potential','');
			$this->set('residual','');
			$this->set('potential','');
			$this->set('summary','');
			$this->set('details','');
			$this->set('reporter','');
			$this->set('cnt',13);
			$this->set('created_by',$_SESSION['adminData']['AdminMaster']['first_name']." ".$_SESSION['adminData']['AdminMaster']['last_name']);
			$reportno=date('YmdHis');
			$this->set('reportno',$reportno);
			$this->set('well',''); 
			$this->set('loss_time','00:00');
			$this->set('severity','');
			$this->set('rig','');
			$this->set('clientncr','');
			$this->set('clientreviewed',1);
			$this->set('report_id',base64_decode($id));
			$this->set('clientreviewer','');
			$this->set('wellsiterep','');
			$this->set('operating_time','');
                        $this->set('field_ticket','');
                        $this->set('incidentLocation_id',0);
			
			$this->set('clientreviewed_style','style="display:none"');
		 
		 }else if(base64_decode($id)!=null){
			

		       $reportdetail = $this->SqReportMain->find('all', array('conditions' => array('SqReportMain.id' =>base64_decode($id))));
                       $this->Session->write('report_create',$reportdetail[0]['SqReportMain']['created_by']);
                       $adminDATA = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$reportdetail[0]['SqReportMain']['created_by'])));
		        if($reportdetail[0]['SqReportMain']['closer_date']!=''){
		                 $crtd=explode("-",$reportdetail[0]['SqReportMain']['closer_date']);
		                 $closedt=$crtd[1]."-".$crtd[2]."-".$crtd[0];
		        }else{
		                $closedt='';	
		        }
	 
                        if($reportdetail[0]['SqReportMain']['clientreviewed']==3){
                            
                                $this->Session->write('clientfeed_back','yes');
		                 
		        }else{
                               $this->Session->write('clientfeed_back','no');
		               
		        }
			


		       $lossTime=array();
		       
		       if(count($reportdetail[0]['SqReportIncident'])>0){
			   for($i=0;$i<count($reportdetail[0]['SqReportIncident']);$i++){
				$hm=explode(":",$reportdetail[0]['SqReportIncident'][$i]['loss_time']);
				$hour=$hm[0];
				$minute=$hm[1];
				$lossTime['hr'][]=$hour;
				$lossTime['min'][]=$minute;
			    
			    }
			    
			 if(count($lossTime['hr'])>0){		 
		         $hoursum=array_sum($lossTime['hr']);
			 $minsum=array_sum($lossTime['min']);
			 if($minsum<60){
				
				if($hoursum<10){
					$hoursum="0$hoursum";
					
				}else{
				    $hoursum=$hoursum;	
				}
				
				if($minsum<10){
				   $minsum="0$minsum";	
				}else{
				  $$minsum=$minsum;		
				}
			   	
			    $hour_minute=$hoursum.":".$minsum;
			    $this->set('loss_time',$hour_minute);
			 }elseif($minsum>60){
				$hourminute=round($minsum/60);
				
				$minute=$minsum%60;
				$hr=$hourminute+$hoursum;
				if($hr<10){
					$hr="0$hr";
					
				}else{
				    $hr=$hr;	
				}
				
				if($minute<10){
				   $minute="0$minute";	
				}else{
				  $minute=$minute;		
				}
				
				$hour_minute=$hr.":".$minute;
				$this->set('loss_time',$hour_minute);
			 }else{
				$this->set('loss_time','00:00');
			 }
			
		       }else{
			       $this->set('loss_time','00:00');
		       }
		
		       
		       }else{
			 $this->set('loss_time','00:00');
		       }
		       
		       $this->set('id',base64_decode($id));
		       
		       
		       
		       
		       
			if($reportdetail[0]['SqReportMain']['event_date']!=''){
			 
			         $evndt=explode("-",$reportdetail[0]['SqReportMain']['event_date']);
			         $event_date=$evndt[1]."-".$evndt[2]."-".$evndt[0];
			 
			}else{
			         $event_date=''; 	
			}
	  	        $this->set('event_date',$event_date);
		        $this->set('since_event_hidden',$reportdetail[0]['SqReportMain']['since_event']);
		        $this->set('since_event',$reportdetail[0]['SqReportMain']['since_event']);
		        $this->set('heading','Update SQ Report (Main)');
		        $this->set('button','Update');
			$this->set('closer_date',$closedt);
		        $this->set('reportno',$reportdetail[0]['SqReportMain']['report_no']);
			$this->set('incident_type',$reportdetail[0]['SqReportMain']['incident_type']);
			$this->set('cnt',$reportdetail[0]['SqReportMain']['country']);
			$this->set('created_date','');
			$this->set('business_unit',$reportdetail[0]['SqReportMain']['business_unit']);
			$this->set('client',$reportdetail[0]['SqReportMain']['client']);
				
			$this->set('field_location',$reportdetail[0]['SqReportMain']['field_location']);
			$this->set('severity',$reportdetail[0]['SqReportMain']['severity']);
		      
			$this->set('potential',$reportdetail[0]['SqReportMain']['potential']);
			$this->set('residual',$reportdetail[0]['SqReportMain']['residual']);
			$this->set('summary',$reportdetail[0]['SqReportMain']['summary']);
			$this->set('details',$reportdetail[0]['SqReportMain']['details']);
			$this->set('well',$reportdetail[0]['SqReportMain']['well']);
			$this->set('reporter',$reportdetail[0]['SqReportMain']['reporter']);
			$this->set('operating_time',$reportdetail[0]['SqReportMain']['operating_time']);
			  
			if($reportdetail[0]['SqReportMain']['clientreviewed']==3){
				     $this->set('clientreviewed_style','style="display:block"');
				     $this->set('clientreviewer',$reportdetail[0]['SqReportMain']['clientreviewer']);
				     $this->set('client_feedback',1);
			}else{
				     $this->set('clientreviewed_style','style="display:none"');
				     $this->set('clientreviewer','');
				     $this->set('client_feedback',0);
			}
				    
			$this->set('clientreviewed',$reportdetail[0]['SqReportMain']['clientreviewed']);
			$this->set('rig',$reportdetail[0]['SqReportMain']['rig']);
			$this->set('clientncr',$reportdetail[0]['SqReportMain']['clientncr']); 
			$this->set('report_id',base64_decode($id));
			$this->set('wellsiterep',$reportdetail[0]['SqReportMain']['wellsiterep']);
                        $this->set('incidentLocation_id',$reportdetail[0]['SqReportMain']['incident_location']);
                        $this->set('field_ticket',$reportdetail[0]['SqReportMain']['field_ticket']);  
			$this->set('created_by',$adminDATA[0]['AdminMaster']['first_name']." ".$adminDATA[0]['AdminMaster']['last_name']);
		 
		 
		 }
	}
	function sqreportprocess()
	 {
		
		
	   $this->layout = "ajax";
	   $this->_checkAdminSession();
           $sqreportMainData=array();
	   
	   
          if($this->data['add_sq_main_form']['id']==0){
		   $res='add';
 	                
	   }else{
		   $res='update';
		   $sqreportMainData['SqReportMain']['id']=$this->data['add_sq_main_form']['id'];
		  
	    }
	   
	      
         if($this->data['event_date']!=''){
           $evndate=explode("-",$this->data['event_date']);
	   $sqreportMainData['SqReportMain']['event_date']=$evndate[2]."-".$evndate[0]."-".$evndate[1];
	   }else{
	   $sqreportMainData['SqReportMain']['event_date']='';	
	   }
	   
	   $sqreportMainData['SqReportMain']['user_id']=$_SESSION['adminData']['AdminMaster']['id']; 
	   $sqreportMainData['SqReportMain']['incident_type']=$this->data['incident_type']; 
	   $sqreportMainData['SqReportMain']['business_unit'] =$this->data['business_unit'];  
	   $sqreportMainData['SqReportMain']['client']=$this->data['client'];
	   $sqreportMainData['SqReportMain']['field_location']=$this->data['field_location'];
	   $sqreportMainData['SqReportMain']['country']=$this->data['country'];
	   $sqreportMainData['SqReportMain']['reporter']=$this->data['reporter'];
	   $sqreportMainData['SqReportMain']['report_no']=$this->data['report_no']; 
	   $sqreportMainData['SqReportMain']['since_event']=$this->data['since_event'];
	   $sqreportMainData['SqReportMain']['operating_time']=$this->data['operating_time'];
	   $sqreportMainData['SqReportMain']['created_by']=$_SESSION['adminData']['AdminMaster']['id'];
	   $sqreportMainData['SqReportMain']['reporter']=$this->data['reporter'];
	   $sqreportMainData['SqReportMain']['potential']=$this->data['potential'];
	   $sqreportMainData['SqReportMain']['residual']=$this->data['residual'];
	   $sqreportMainData['SqReportMain']['summary']=$this->data['add_sq_main_form']['summary'];
	   $sqreportMainData['SqReportMain']['details']=$this->data['add_sq_main_form']['details'];
	   $sqreportMainData['SqReportMain']['well']=$this->data['add_sq_main_form']['well'];
	   $sqreportMainData['SqReportMain']['rig']=$this->data['add_sq_main_form']['rig'];
	   $sqreportMainData['SqReportMain']['severity']=$this->data['severity'];
           $sqreportMainData['SqReportMain']['incident_location']=$this->data['incident_location'];
	   $sqreportMainData['SqReportMain']['wellsiterep']=$this->data['add_sq_main_form']['wellsiterep'];
	   $sqreportMainData['SqReportMain']['clientncr']=$this->data['add_sq_main_form']['clientncr'];
	   $sqreportMainData['SqReportMain']['clientreviewer']=$this->data['add_sq_main_form']['clientreviewer'];
	   $sqreportMainData['SqReportMain']['clientreviewed']=$this->data['clientreviewed'];
           $sqreportMainData['SqReportMain']['field_ticket']=$this->data['add_sq_main_form']['field_ticket'];
	   $sqreportMainData['SqReportMain']['summary']=$this->data['add_sq_main_form']['summary'];
	   $sqreportMainData['SqReportMain']['details']=$this->data['add_sq_main_form']['details'];

	   
	 if($this->SqReportMain->save($sqreportMainData)){
		 if($res=='add'){
			 $lastReport=base64_encode($this->SqReportMain->getLastInsertId());
		 }elseif($res=='update'){
			 $lastReport=base64_encode($this->data['add_sq_main_form']['id']);
		 }
	 	
		echo $res.'~'.$lastReport;
	    }else{
		echo 'fail';
	    }
            
         
		
	   exit;
	}
	
	
	 public function welldata($id=null){
		 $this->_checkAdminSession();
         	 $this->_getRoleMenuPermission();
		 $this->grid_access();
                 $this->layout="after_adminlogin_template";
		 $this->set('id','0');
		 $reportdetail = $this->SqReportMain->find('all', array('conditions' => array('SqReportMain.id' =>base64_decode($id))));
		 $welldataList = $this->Welldata->find('all');
		 $wellstatus = $this->WellStatus->find('all');
	         $this->set('report_number',$reportdetail[0]['SqReportMain']['report_no']);
		 $this->set('report_id',base64_decode($id));
		 $this->set('welldataList',$welldataList);
                 $this->set('wellstatus',$wellstatus);
	         $sqwelldatas = $this->SqWellData->find('all', array('conditions' => array('SqWellData.report_id' =>base64_decode($id))));
         	   if(count($sqwelldatas)>0){
 
			    
			    $this->set('heading','Update Well Data');
		            $this->set('button','Update');
			    $this->set('id',0);
		            $this->set('report_id',base64_decode($id));
		            $this->set('rigname',$sqwelldatas[0]['SqWellData']['rig_name']);
			    $this->set('well_name',$sqwelldatas[0]['SqWellData']['well_name']);
			    $this->set('fluid',$sqwelldatas[0]['SqWellData']['fluid_name']);
			    $this->set('depth',$sqwelldatas[0]['SqWellData']['depth']);
			    $this->set('devi',$sqwelldatas[0]['SqWellData']['devi']);
			    $this->set('shtemp',$sqwelldatas[0]['SqWellData']['shtemp']);
			    $this->set('bhtemp',$sqwelldatas[0]['SqWellData']['bhtemp']);
			    $this->set('density',$sqwelldatas[0]['SqWellData']['density']);
			    $this->set('whpres',$sqwelldatas[0]['SqWellData']['whpres']);
			    $this->set('welldata',$sqwelldatas[0]['SqWellData']['well_name']);
			    $this->set('bhpres',$sqwelldatas[0]['SqWellData']['bhpres']);
			    $this->set('wellstatusval',$sqwelldatas[0]['SqWellData']['staus_name']);
			    $this->set('hts',$sqwelldatas[0]['SqWellData']['hts']);
			    $this->set('cot',$sqwelldatas[0]['SqWellData']['cot']);
			
                          
	                }else{
			    $this->set('heading','Add Well Data');
		            $this->set('button','Submit');
			    $this->set('id',0);
		            $this->set('rigname','');
			    $this->set('well_name','');
			    $this->set('fluid','');
			    $this->set('depth','');
			    $this->set('devi','');
			    $this->set('shtemp','');
			    $this->set('bhtemp','');
			    $this->set('density','');
			    $this->set('whpres','');
			    $this->set('welldata','');
			    $this->set('bhpres','');
			    $this->set('wellstatusval','');
			    $this->set('hts','');
			    $this->set('cot','');
			  
		  
	                }
	
		 
	        }
	 
	        function welldataprocess(){ 
	                $this->layout = "ajax";
	                $this->_checkAdminSession();
	
	                $SqWelldata=array();
	         	$sqwelldatas = $this->SqWellData->find('all', array('conditions' => array('SqWellData.report_id' =>$this->data['report_id'])));
				if(count($sqwelldatas)>0){
				       $res='update';
				        $SqWelldata['SqWellData']['id']=$sqwelldatas[0]['SqWellData']['id'];
				}else{
				       $res='add';
				}
					$SqWelldata['SqWellData']['well_name']=$this->data['well_name'];
					$SqWelldata['SqWellData']['report_id']=$this->data['report_id']; 
					$SqWelldata['SqWellData']['rig_name'] =$this->data['rig_name'];  
					$SqWelldata['SqWellData']['fluid_name']=$this->data['fluid_name'];
					$SqWelldata['SqWellData']['depth']=$this->data['depth'];
					$SqWelldata['SqWellData']['devi']=$this->data['devi'];
					$SqWelldata['SqWellData']['bhtemp']=$this->data['bhtemp'];
					$SqWelldata['SqWellData']['shtemp']=$this->data['shtemp'];
									
					$SqWelldata['SqWellData']['density']=$this->data['density']; 
					$SqWelldata['SqWellData']['whpres'] =$this->data['whpres'];  
					$SqWelldata['SqWellData']['bhpres']=$this->data['bhpres'];
					$SqWelldata['SqWellData']['staus_name']=$this->data['staus_name'];
					$SqWelldata['SqWellData']['hts']=$this->data['hts'];
					$SqWelldata['SqWellData']['cot']=$this->data['cot'];
				if($this->SqWellData->save($SqWelldata)){
				     echo $res;
				 }else{
				     echo 'fail';
				 }
				 
                       
	          exit;
	        }
	 
	 
	  function sqreportmain_close(){
		       $this->layout="ajax";
		       $reportData=array();
		       $reportData['SqReportMain']['id']=$this->data['report_id'];
		       if($this->data['type']=='close'){
		           $reportData['SqReportMain']['closer_date']=date("Y-m-d");
			   
		       }elseif($this->data['type']=='reopen'){
			   $reportData['SqReportMain']['closer_date']='0000-00-00';
			
		       }
		       if($this->SqReportMain->save($reportData)){
			  echo $this->data['type'].'~'.date("d/m/Y");
		       }
		       exit;
		
	      }
	 function add_sqreport_view($id=null){
		 $this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
		 $this->grid_access();
                 $this->layout="after_adminlogin_template";
		
		 
		 $reportdetail = $this->SqReportMain->find('all', array('conditions' => array('SqReportMain.id' =>base64_decode($id)),'recursive'=>3));
		   $this->Session->write('report_create',$reportdetail[0]['SqReportMain']['created_by']);
                 $lossTime=array();
		       
		       if(count($reportdetail[0]['SqReportIncident'])>0){
			   for($i=0;$i<count($reportdetail[0]['SqReportIncident']);$i++){
				$hm=explode(":",$reportdetail[0]['SqReportIncident'][$i]['loss_time']);
				$hour=$hm[0];
				$minute=$hm[1];
				$lossTime['hr'][]=$hour;
				$lossTime['min'][]=$minute;
			    
			    }
			    
			 if(count($lossTime['hr'])>0){		 
		         $hoursum=array_sum($lossTime['hr']);
			 $minsum=array_sum($lossTime['min']);
			 if($minsum<60){
				
				if($hoursum<10){
					$hoursum="0$hoursum";
					
				}else{
				    $hoursum=$hoursum;	
				}
				
				if($minsum<10){
				   $minsum="0$minsum";	
				}else{
				  $$minsum=$minsum;		
				}
			   	
			    $hour_minute=$hoursum.":".$minsum;
			    $this->set('loss_time',$hour_minute);
			 }elseif($minsum>60){
				$hourminute=round($minsum/60);
				
				$minute=$minsum%60;
				$hr=$hourminute+$hoursum;
				if($hr<10){
					$hr="0$hr";
					
				}else{
				    $hr=$hr;	
				}
				
				if($minute<10){
				   $minute="0$minute";	
				}else{
				  $minute=$minute;		
				}
				
				$hour_minute=$hr.":".$minute;
				$this->set('loss_time',$hour_minute);
			 }else{
				$this->set('loss_time','00:00');
			 }
			 
			 }else{
			        $this->set('loss_time','00:00');
			 }
		
		        
		       }else{
			        $this->set('loss_time','00:00');
		       }

	          $this->set('id',base64_decode($id));
                  if($reportdetail[0]['SqReportMain']['event_date']!=''){
				 $evndt=explode("-",$reportdetail[0]['SqReportMain']['event_date']);
				 $event_date=$evndt[2]."/".$evndt[1]."/".$evndt[0];
		  }else{
				 $event_date='';	
		  }
		  $reportdetail[0]['SqReportMain']['event_occure']=$event_date;
             

	
	          if($reportdetail[0]['SqReportMain']['clientreviewed']==3){
         
			      $this->set('client_feedback',1);
                              $this->Session->write('clientfeed_back','yes');
		  }else if($reportdetail[0]['SqReportMain']['clientreviewed']!=3){
                              $this->Session->write('clientfeed_back','no');
			      $this->set('client_feedback',0);
		 	
		  }
                
		 if($reportdetail[0]['SqReportMain']['client']==9){
			     $this->set('clienttabshow',0);
			     $this->set('clienttab',0);
			     
		 }else if($reportdetail[0]['SqReportMain']['client']!=9){
			     $this->set('clienttabshow',1);
			     $this->set('clienttab',1);
			     
		  }
   
            

		  
	  	  $this->set('event_date',$event_date);
		  $this->set('since_event_hidden',$reportdetail[0]['SqReportMain']['since_event']);
		  $this->set('since_event',$reportdetail[0]['SqReportMain']['since_event']);
		  $this->set('reportno',$reportdetail[0]['SqReportMain']['report_no']);
		  
		  
		  if($reportdetail[0]['SqReportMain']['closer_date']!='0000-00-00'){
				 $clsdt=explode("-",$reportdetail[0]['SqReportMain']['closer_date']);
				 $closedt=$clsdt[2]."/".$clsdt[1]."/".$clsdt[0];
			}else{
				 $closedt='00/00/0000';	
			}

		  $reportdetail[0]['SqReportMain']['closer_date']=$closedt;
                  $this->set('closer_date',$closedt); 

            
                   if(isset($reportdetail[0]['SqWellData'][0])){
			if($reportdetail[0]['SqWellData'][0]['rig_name']!=''){
			    $this->set('rig_name',$reportdetail[0]['SqWellData'][0]['rig_name']); 
			}else{
			    $this->set('rig_name','');	
			}
                   }else{
			$this->set('rig_name','');	
		   }

                  if($reportdetail[0]['SqReportMain']['clientreviewed']==1){
                      $reportdetail[0]['SqReportMain']['clientreviewed_value']='N/A'; 
                   }elseif($reportdetail[0]['SqReportMain']['clientreviewed']==2){
                      $reportdetail[0]['SqReportMain']['clientreviewed_value']='No';
                   }elseif($reportdetail[0]['SqReportMain']['clientreviewed']==3){
                      $reportdetail[0]['SqReportMain']['clientreviewed_value']='Yes';
                   }  
                 
                   $reportdetail[0]['SqReportMain']['potential_color']='<font color='.$reportdetail[0]['Potential']['color_code'].'>'.$reportdetail[0]['Potential']['type'].'</font>';
                   $reportdetail[0]['SqReportMain']['residula_color']='<font color='.$reportdetail[0]['Residual']['color_code'].'>'.$reportdetail[0]['Residual']['type'].'</font>';    
                   $reporter_detail= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$reportdetail[0]['SqReportMain']['reporter'])));
		   if(count($reporter_detail)>0){
                   $reportdetail[0]['SqReportMain']['reporter_name']=$reporter_detail[0]['AdminMaster']['first_name']." ".$reporter_detail[0]['AdminMaster']['last_name'];
		   }else{
		    $reportdetail[0]['SqReportMain']['reporter_name']='';  	
		   }
                   $reportdetail[0]['IncidentSeverity']['incidentSeverity_color']='<font color='.$reportdetail[0]['IncidentSeverity']['color_code'].'>'.$reportdetail[0]['IncidentSeverity']['type'].'</font>';
                  
                  if(count($reportdetail[0]['SqReportIncident'])>0){
                              for($j=0;$j<count($reportdetail[0]['SqReportIncident']);$j++){

				       $subDamage= $this->SqDamage->find('all', array('conditions' => array('SqDamage.id' =>$reportdetail[0]['SqReportIncident'][$j]['sub_category'])));
				       if(isset($subDamage[0])>0){
						if($subDamage[0]['SqDamage']['type']!=''){ 
						       $reportdetail[0]['SqReportIncident'][$j]['sub_category_name']=$subDamage[0]['SqDamage']['type'];
						}else{
						       $reportdetail[0]['SqReportIncident'][$j]['sub_category_name']='';
						}
				       }else{
					  $reportdetail[0]['SqReportIncident'][$j]['sub_category_name']='';
				       }
                             }
                    }
                  if(isset($reportdetail[0]['SqInvestigation'][0])){
                     $condition = "AdminMaster.isblocked = 'N' AND AdminMaster.isdeleted = 'N' AND AdminMaster.id IN (".$reportdetail[0]['SqInvestigation'][0]['team_user_id'].")";
                     $investigation_team_list= $this->AdminMaster->find('all', array('conditions' =>$condition)); 


		           for($v=0;$v<count($investigation_team_list);$v++){
                                 $reportdetail[0]['SqInvestigation']['0']['name'][]=$investigation_team_list[$v]['AdminMaster']['first_name']." ".$investigation_team_list[$v]['AdminMaster']['last_name'];
                                 $reportdetail[0]['SqInvestigation']['0']['roll_name'][]=$investigation_team_list[$v]['RoleMaster']['role_name'];
                                 $sam=explode(" ",$investigation_team_list[$v]['AdminMaster']['modified']);
			         $snr=explode("-",$sam[0]);
			         $seniorty=$snr[2]."/".$snr[1]."/".$snr[0];
			         $reportdetail[0]['SqInvestigation']['0']['seniorty'][]=$seniorty;
                                 
		           }
                   } 
                   if(isset($reportdetail[0]['SqInvestigationData'][0])){
		
			

                           for($v=0;$v<count($reportdetail[0]['SqInvestigationData']);$v++){
                                 
                                  if($reportdetail[0]['SqInvestigationData'][$v]['root_cause_id']!=0){
                                              $explode_invest=explode(",",$reportdetail[0]['SqInvestigationData'][$v]['root_cause_id']);   
                                              for($k=0;$k<count($explode_invest);$k++){
                                                       $root_couse= $this->RootCause->find('all', array('conditions' =>array('RootCause.id'=>$explode_invest[$k])));
						       if(count($root_couse)>0){
                                                          $reportdetail[0]['SqInvestigationData'][$v]['cause_name'][]=$root_couse[0]['RootCause']['type'];
						       }else{
							   $reportdetail[0]['SqInvestigationData'][$v]['cause_name'][]='';
						       }
							
                                              }               
                                   }
                                   if($reportdetail[0]['SqInvestigationData'][$v]['immediate_cause']!=0){

                                             $explode_invest_imd=explode(",",$reportdetail[0]['SqInvestigationData'][$v]['immediate_cause']);   
                                             $imd_couse= $this->ImmediateCause->find('all', array('conditions' =>array('ImmediateCause.id'=>$explode_invest_imd[0])));   
                                             $reportdetail[0]['SqInvestigationData'][$v]['imd_cause_name'][]= $imd_couse[0]['ImmediateCause']['type'];
					     if(isset($explode_invest_imd[1])){
                                               if($explode_invest_imd[1]!=0){
					     
                                                    $imd_sub_couse= $this->ImmediateSubCause->find('all', array('conditions' =>array('ImmediateSubCause.id'=>$explode_invest_imd[1])));   
                                                    $reportdetail[0]['SqInvestigationData'][$v]['imd_sub_couse_name'][]= $imd_sub_couse[0]['ImmediateSubCause']['type'];     
                                                 }else{
						    $reportdetail[0]['SqInvestigationData'][$v]['imd_sub_couse_name'][]='';	
						 }
					     }else{
						   $reportdetail[0]['SqInvestigationData'][$v]['imd_sub_couse_name'][]='';	
					     }
                                   }
                                 if($reportdetail[0]['SqInvestigationData'][$v]['remedila_action_id']!=0){

                                             $explode_remedila_action=explode(",",$reportdetail[0]['SqInvestigationData'][$v]['remedila_action_id']);

                                              for($r=0;$r<count($explode_remedila_action);$r++){

                                                    $remdial_action= $this->SqRemidial->find('all', array('conditions' =>array('SqRemidial.id'=>$explode_remedila_action[$r])));   
                                                    $reportdetail[0]['SqInvestigationData'][$v]['rem_no'][]=$remdial_action[0]['SqRemidial']['remedial_no'];     
                                                        
                                                    $reportdetail[0]['SqInvestigationData'][$v]['rem_summary'][]=$remdial_action[0]['SqRemidial']['remidial_summery'];         
                                                } 
                                               

                                  }

                           }
 

                   }
		   
		   
                   
                   if(isset($reportdetail[0]['SqRemidial'][0])){

		                  for($h=0;$h<count($reportdetail[0]['SqRemidial']);$h++){
			

                                        $responsibility= $this->AdminMaster->find('all', array('conditions' =>array('AdminMaster.id'=>$reportdetail[0]['SqRemidial'][$h]['remidial_responsibility']))); 
                                        $reportdetail[0]['SqRemidial'][$h]['responsibility_person']=$responsibility[0]['AdminMaster']['first_name']." ".$responsibility[0]['AdminMaster']['last_name'];
                                        $reportdetail[0]['SqRemidial'][$h]['Priority']['prority_colorcode']='<font color='.$reportdetail[0]['SqRemidial'][$h]['Priority']['colorcoder'].'>'.$reportdetail[0]['SqRemidial'][$h]['Priority']['type'].'</font>';
                           
		                     
		                      $explode_remedial=explode("-",$reportdetail[0]['SqRemidial'][$h]['remidial_create']);     

					 $reportdetail[0]['SqRemidial'][$h]['createRemidial']=date("d-M-y", mktime(0, 0, 0, $explode_remedial[1], $explode_remedial[2], $explode_remedial[0]));
	 
		                         if($reportdetail[0]['SqRemidial'][$h]['remidial_closure_date']=='0000-00-00'){
						  $reportdetail[0]['SqRemidial'][$h]['closeDate']='';
						  $reportdetail[0]['SqRemidial'][$h]['remidial_closer_summary']='';
					
					 }elseif($reportdetail[0]['SqRemidial'][$h]['remidial_closure_date']!='0000-00-00'){
						 $closerDate=explode("-",$reportdetail[0]['SqRemidial'][$h]['remidial_closure_date']);

                                                 $reportdetail[0]['SqRemidial'][$h]['closeDate']=date("d-M-y", mktime(0, 0, 0, $closerDate[1], $closerDate[2], $closerDate[0])); 


		                   }

                                  if($reportdetail[0]['SqRemidial'][$h]['remidial_closure_date']=='0000-00-00'){
						  $reportdetail[0]['SqRemidial'][$h]['closeDate']='';
						  $reportdetail[0]['SqRemidial'][$h]['remidial_closer_summary']='';
					
					 }elseif($reportdetail[0]['SqRemidial'][$h]['remidial_closure_date']!='0000-00-00'){
						 $closerDate=explode("-",$reportdetail[0]['SqRemidial'][$h]['remidial_closure_date']);

                                                 $reportdetail[0]['SqRemidial'][$h]['closeDate']=date("d-M-y", mktime(0, 0, 0, $closerDate[1], $closerDate[2], $closerDate[0])); 


		                   }

                               
                                
                               if($reportdetail[0]['SqRemidial'][$h]['remidial_reminder_data']!=''){
                                 $rrd=explode(" ",$reportdetail[0]['SqRemidial'][$h]['remidial_reminder_data']);
                       		 $exrrd=explode("/",$rrd[0]);
				 $reportdetail[0]['SqRemidial'][$h]['rem_rim_data']=date("d-M-y", mktime(0, 0, 0, $exrrd[1], $exrrd[0], $exrrd[2])); 
                                }else{
                                 $reportdetail[0]['SqRemidial'][$h]['rem_rim_data']='';
                                 }

                                if($reportdetail[0]['SqRemidial'][$h]['remidial_closure_target']!=''){
                                 $rcd=explode(" ",$reportdetail[0]['SqRemidial'][$h]['remidial_closure_target']);
				 $exrcd=explode("/",$rcd[0]);
				 $reportdetail[0]['SqRemidial'][$h]['rem_cls_trgt']=date("d-M-y", mktime(0, 0, 0, $exrcd[1], $exrcd[0], $exrcd[2])); 
                               }else{
                                  $reportdetail[0]['SqRemidial'][$h]['rem_cls_trgt']='';
                               }
		           }
                  }
		  
                   $this->set('reportdetail', $reportdetail); 
	  
		    
	 }
	 
	  function print_view($id=null){
		 $this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
		 $this->grid_access();
                 $this->layout="ajax";
		
		 
		 $reportdetail = $this->SqReportMain->find('all', array('conditions' => array('SqReportMain.id' =>base64_decode($id)),'recursive'=>3));
                 $lossTime=array();
		       
		       if(count($reportdetail[0]['SqReportIncident'])>0){
			   for($i=0;$i<count($reportdetail[0]['SqReportIncident']);$i++){
				$hm=explode(":",$reportdetail[0]['SqReportIncident'][$i]['loss_time']);
				$hour=$hm[0];
				$minute=$hm[1];
				$lossTime['hr'][]=$hour;
				$lossTime['min'][]=$minute;
			    
			    }
			    
			 if(count($lossTime['hr'])>0){		 
		         $hoursum=array_sum($lossTime['hr']);
			 $minsum=array_sum($lossTime['min']);
			 if($minsum<60){
				
				if($hoursum<10){
					$hoursum="0$hoursum";
					
				}else{
				    $hoursum=$hoursum;	
				}
				
				if($minsum<10){
				   $minsum="0$minsum";	
				}else{
				  $$minsum=$minsum;		
				}
			   	
			    $hour_minute=$hoursum.":".$minsum;
			    $this->set('loss_time',$hour_minute);
			 }elseif($minsum>60){
				$hourminute=round($minsum/60);
				
				$minute=$minsum%60;
				$hr=$hourminute+$hoursum;
				if($hr<10){
					$hr="0$hr";
					
				}else{
				    $hr=$hr;	
				}
				
				if($minute<10){
				   $minute="0$minute";	
				}else{
				  $minute=$minute;		
				}
				
				$hour_minute=$hr.":".$minute;
				$this->set('loss_time',$hour_minute);
			 }else{
				$this->set('loss_time','00:00');
			 }
			 
			 }else{
			        $this->set('loss_time','00:00');
			 }
		
		        
		       }else{
			        $this->set('loss_time','00:00');
		       }

	          $this->set('id',base64_decode($id));
                  if($reportdetail[0]['SqReportMain']['event_date']!=''){
				 $evndt=explode("-",$reportdetail[0]['SqReportMain']['event_date']);
				 $event_date=$evndt[2]."/".$evndt[1]."/".$evndt[0];
		  }else{
				 $event_date='';	
		  }
		  $reportdetail[0]['SqReportMain']['event_occure']=$event_date;
             

	
	          if($reportdetail[0]['SqReportMain']['clientreviewed']==3){
         
			      $this->set('client_feedback',1);
                              $this->Session->write('clientfeed_back','yes');
		  }else if($reportdetail[0]['SqReportMain']['clientreviewed']!=3){
                              $this->Session->write('clientfeed_back','no');
			      $this->set('client_feedback',0);
		 	
		  }
                
		 if($reportdetail[0]['SqReportMain']['client']==9){
			     $this->set('clienttabshow',0);
			     $this->set('clienttab',0);
			     
		 }else if($reportdetail[0]['SqReportMain']['client']!=9){
			     $this->set('clienttabshow',1);
			     $this->set('clienttab',1);
			     
		  }
   
            

		  
	  	  $this->set('event_date',$event_date);
		  $this->set('since_event_hidden',$reportdetail[0]['SqReportMain']['since_event']);
		  $this->set('since_event',$reportdetail[0]['SqReportMain']['since_event']);
		  $this->set('reportno',$reportdetail[0]['SqReportMain']['report_no']);
		  
		  
		  if($reportdetail[0]['SqReportMain']['closer_date']!='0000-00-00'){
				 $clsdt=explode("-",$reportdetail[0]['SqReportMain']['closer_date']);
				 $closedt=$clsdt[2]."/".$clsdt[1]."/".$clsdt[0];
			}else{
				 $closedt='00/00/0000';	
			}

		  $reportdetail[0]['SqReportMain']['closer_date']=$closedt;
                  $this->set('closer_date',$closedt); 

            
                   if(isset($reportdetail[0]['SqWellData'][0])){
			if($reportdetail[0]['SqWellData'][0]['rig_name']!=''){
			    $this->set('rig_name',$reportdetail[0]['SqWellData'][0]['rig_name']); 
			}else{
			    $this->set('rig_name','');	
			}
                   }else{
			$this->set('rig_name','');	
		   }

                  if($reportdetail[0]['SqReportMain']['clientreviewed']==1){
                      $reportdetail[0]['SqReportMain']['clientreviewed_value']='N/A'; 
                   }elseif($reportdetail[0]['SqReportMain']['clientreviewed']==2){
                      $reportdetail[0]['SqReportMain']['clientreviewed_value']='No';
                   }elseif($reportdetail[0]['SqReportMain']['clientreviewed']==3){
                      $reportdetail[0]['SqReportMain']['clientreviewed_value']='Yes';
                   }  
                 
                   $reportdetail[0]['SqReportMain']['potential_color']='<font color='.$reportdetail[0]['Potential']['color_code'].'>'.$reportdetail[0]['Potential']['type'].'</font>';
                   $reportdetail[0]['SqReportMain']['residula_color']='<font color='.$reportdetail[0]['Residual']['color_code'].'>'.$reportdetail[0]['Residual']['type'].'</font>';    
                   $reporter_detail= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$reportdetail[0]['SqReportMain']['reporter'])));
		   if(count($reporter_detail)>0){
                   $reportdetail[0]['SqReportMain']['reporter_name']=$reporter_detail[0]['AdminMaster']['first_name']." ".$reporter_detail[0]['AdminMaster']['last_name'];
		   }else{
		    $reportdetail[0]['SqReportMain']['reporter_name']='';  	
		   }
                   $reportdetail[0]['IncidentSeverity']['incidentSeverity_color']='<font color='.$reportdetail[0]['IncidentSeverity']['color_code'].'>'.$reportdetail[0]['IncidentSeverity']['type'].'</font>';
                  
                  if(count($reportdetail[0]['SqReportIncident'])>0){
                              for($j=0;$j<count($reportdetail[0]['SqReportIncident']);$j++){

				       $subDamage= $this->SqDamage->find('all', array('conditions' => array('SqDamage.id' =>$reportdetail[0]['SqReportIncident'][$j]['sub_category'])));
				       if(isset($subDamage[0])>0){
						if($subDamage[0]['SqDamage']['type']!=''){ 
						       $reportdetail[0]['SqReportIncident'][$j]['sub_category_name']=$subDamage[0]['SqDamage']['type'];
						}else{
						       $reportdetail[0]['SqReportIncident'][$j]['sub_category_name']='';
						}
				       }else{
					  $reportdetail[0]['SqReportIncident'][$j]['sub_category_name']='';
				       }
                             }
                    }
                  if(isset($reportdetail[0]['SqInvestigation'][0])){
                     $condition = "AdminMaster.isblocked = 'N' AND AdminMaster.isdeleted = 'N' AND AdminMaster.id IN (".$reportdetail[0]['SqInvestigation'][0]['team_user_id'].")";
                     $investigation_team_list= $this->AdminMaster->find('all', array('conditions' =>$condition)); 


		           for($v=0;$v<count($investigation_team_list);$v++){
                                 $reportdetail[0]['SqInvestigation']['0']['name'][]=$investigation_team_list[$v]['AdminMaster']['first_name']." ".$investigation_team_list[$v]['AdminMaster']['last_name'];
                                 $reportdetail[0]['SqInvestigation']['0']['roll_name'][]=$investigation_team_list[$v]['RoleMaster']['role_name'];
                                 $sam=explode(" ",$investigation_team_list[$v]['AdminMaster']['modified']);
			         $snr=explode("-",$sam[0]);
			         $seniorty=$snr[2]."/".$snr[1]."/".$snr[0];
			         $reportdetail[0]['SqInvestigation']['0']['seniorty'][]=$seniorty;
                                 
		           }
                   } 
                   if(isset($reportdetail[0]['SqInvestigationData'][0])){
		
			

                           for($v=0;$v<count($reportdetail[0]['SqInvestigationData']);$v++){
                                 
                                  if($reportdetail[0]['SqInvestigationData'][$v]['root_cause_id']!=0){
                                              $explode_invest=explode(",",$reportdetail[0]['SqInvestigationData'][$v]['root_cause_id']);   
                                              for($k=0;$k<count($explode_invest);$k++){
                                                       $root_couse= $this->RootCause->find('all', array('conditions' =>array('RootCause.id'=>$explode_invest[$k])));
						       if(count($root_couse)>0){
                                                          $reportdetail[0]['SqInvestigationData'][$v]['cause_name'][]=$root_couse[0]['RootCause']['type'];
						       }else{
							   $reportdetail[0]['SqInvestigationData'][$v]['cause_name'][]='';
						       }
							
                                              }               
                                   }
                                    if($reportdetail[0]['SqInvestigationData'][$v]['immediate_cause']!=0){

                                             $explode_invest_imd=explode(",",$reportdetail[0]['SqInvestigationData'][$v]['immediate_cause']);   
                                             $imd_couse= $this->ImmediateCause->find('all', array('conditions' =>array('ImmediateCause.id'=>$explode_invest_imd[0])));   
                                             $reportdetail[0]['SqInvestigationData'][$v]['imd_cause_name'][]= $imd_couse[0]['ImmediateCause']['type'];
					     if(isset($explode_invest_imd[1])){
                                               if($explode_invest_imd[1]!=0){
					     
                                                    $imd_sub_couse= $this->ImmediateSubCause->find('all', array('conditions' =>array('ImmediateSubCause.id'=>$explode_invest_imd[1])));   
                                                    $reportdetail[0]['SqInvestigationData'][$v]['imd_sub_couse_name'][]= $imd_sub_couse[0]['ImmediateSubCause']['type'];     
                                                 }else{
						    $reportdetail[0]['SqInvestigationData'][$v]['imd_sub_couse_name'][]='';	
						 }
					     }else{
						   $reportdetail[0]['SqInvestigationData'][$v]['imd_sub_couse_name'][]='';	
					     }
                                   }
                                 if($reportdetail[0]['SqInvestigationData'][$v]['remedila_action_id']!=0){

                                             $explode_remedila_action=explode(",",$reportdetail[0]['SqInvestigationData'][$v]['remedila_action_id']);

                                              for($r=0;$r<count($explode_remedila_action);$r++){

                                                    $remdial_action= $this->SqRemidial->find('all', array('conditions' =>array('SqRemidial.id'=>$explode_remedila_action[$r])));   
                                                    $reportdetail[0]['SqInvestigationData'][$v]['rem_no'][]=$remdial_action[0]['SqRemidial']['remedial_no'];     
                                                        
                                                    $reportdetail[0]['SqInvestigationData'][$v]['rem_summary'][]=$remdial_action[0]['SqRemidial']['remidial_summery'];         
                                                } 
                                               

                                  }

                           }
 

                   }
		   
		   
                   
                   if(isset($reportdetail[0]['SqRemidial'][0])){

		                  for($h=0;$h<count($reportdetail[0]['SqRemidial']);$h++){
			

                                        $responsibility= $this->AdminMaster->find('all', array('conditions' =>array('AdminMaster.id'=>$reportdetail[0]['SqRemidial'][$h]['remidial_responsibility']))); 
                                        $reportdetail[0]['SqRemidial'][$h]['responsibility_person']=$responsibility[0]['AdminMaster']['first_name']." ".$responsibility[0]['AdminMaster']['last_name'];
                                        $reportdetail[0]['SqRemidial'][$h]['Priority']['prority_colorcode']='<font color='.$reportdetail[0]['SqRemidial'][$h]['Priority']['colorcoder'].'>'.$reportdetail[0]['SqRemidial'][$h]['Priority']['type'].'</font>';
                           
		                     
		                      $explode_remedial=explode("-",$reportdetail[0]['SqRemidial'][$h]['remidial_create']);     

					 $reportdetail[0]['SqRemidial'][$h]['createRemidial']=date("d-M-y", mktime(0, 0, 0, $explode_remedial[1], $explode_remedial[2], $explode_remedial[0]));
	 
		                         if($reportdetail[0]['SqRemidial'][$h]['remidial_closure_date']=='0000-00-00'){
						  $reportdetail[0]['SqRemidial'][$h]['closeDate']='';
						  $reportdetail[0]['SqRemidial'][$h]['remidial_closer_summary']='';
					
					 }elseif($reportdetail[0]['SqRemidial'][$h]['remidial_closure_date']!='0000-00-00'){
						 $closerDate=explode("-",$reportdetail[0]['SqRemidial'][$h]['remidial_closure_date']);

                                                 $reportdetail[0]['SqRemidial'][$h]['closeDate']=date("d-M-y", mktime(0, 0, 0, $closerDate[1], $closerDate[2], $closerDate[0])); 


		                   }

                                  if($reportdetail[0]['SqRemidial'][$h]['remidial_closure_date']=='0000-00-00'){
						  $reportdetail[0]['SqRemidial'][$h]['closeDate']='';
						  $reportdetail[0]['SqRemidial'][$h]['remidial_closer_summary']='';
					
					 }elseif($reportdetail[0]['SqRemidial'][$h]['remidial_closure_date']!='0000-00-00'){
						 $closerDate=explode("-",$reportdetail[0]['SqRemidial'][$h]['remidial_closure_date']);

                                                 $reportdetail[0]['SqRemidial'][$h]['closeDate']=date("d-M-y", mktime(0, 0, 0, $closerDate[1], $closerDate[2], $closerDate[0])); 


		                   }

                               
                                
                               if($reportdetail[0]['SqRemidial'][$h]['remidial_reminder_data']!=''){
                                 $rrd=explode(" ",$reportdetail[0]['SqRemidial'][$h]['remidial_reminder_data']);
                       		 $exrrd=explode("/",$rrd[0]);
				 $reportdetail[0]['SqRemidial'][$h]['rem_rim_data']=date("d-M-y", mktime(0, 0, 0, $exrrd[1], $exrrd[0], $exrrd[2])); 
                                }else{
                                 $reportdetail[0]['SqRemidial'][$h]['rem_rim_data']='';
                                 }

                                if($reportdetail[0]['SqRemidial'][$h]['remidial_closure_target']!=''){
                                 $rcd=explode(" ",$reportdetail[0]['SqRemidial'][$h]['remidial_closure_target']);
				 $exrcd=explode("/",$rcd[0]);
				 $reportdetail[0]['SqRemidial'][$h]['rem_cls_trgt']=date("d-M-y", mktime(0, 0, 0, $exrcd[1], $exrcd[0], $exrcd[2])); 
                               }else{
                                  $reportdetail[0]['SqRemidial'][$h]['rem_cls_trgt']='';
                               }
		           }
                  }
		  
                   $this->set('reportdetail', $reportdetail); 
	  
		    
	 }
	 
	 function add_sq_report_personal($report_id=null,$personel_id=null){
	         $this->_checkAdminSession();
         	 $this->_getRoleMenuPermission();
                 $this->layout="after_adminlogin_template";
		 $this->set('id','0');
		 $user_detail= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.isdeleted' =>'N','AdminMaster.isblocked' =>'N')));
		 for($u=0;$u<count($user_detail);$u++){
		           $seniority=explode(" ",$user_detail[$u]['AdminMaster']['modified']);
			   $snr=explode("-",$seniority[0]);
		           $user_detail[$u]['AdminMaster']['user_seniority']=$snr[2]."/".$snr[1]."/".$snr[0];
			   $user_detail[$u]['AdminMaster']['position_seniorty']=$user_detail[$u]['RoleMaster']['role_name'].'~'.$user_detail[$u]['AdminMaster']['user_seniority'].'~'.$user_detail[$u]['AdminMaster']['id'];
		             
		 }
		 
	         $this->set('userDetail',$user_detail);
		 
		 $reportdetail = $this->SqReportMain->find('all', array('conditions' => array('SqReportMain.id' =>base64_decode($report_id))));
		  	       
		   $this->set('report_number',$reportdetail[0]['SqReportMain']['report_no']); 
	          if($personel_id!=''){
			    $personneldetail = $this->SqPersonnel->find('all', array('conditions' => array('SqPersonnel.id' =>base64_decode($personel_id))));
			    $personal_info= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$personneldetail[0]['SqPersonnel']['personal_data'])));
					     
			      $seniority=explode(" ",$personal_info[0]['AdminMaster']['modified']);
			      $snr=explode("-",$seniority[0]);
		              $seniorty=$snr[2]."/".$snr[1]."/".$snr[0];
			      $roll_name=$personal_info[0]['RoleMaster']['role_name'];
			
			      $this->set('roll_name',$roll_name);
			      $this->set('snr',$seniorty);
			      $this->set('heading','Update SQ Personal Data');
		              $this->set('button','Update');
			      $this->set('id',$personneldetail[0]['SqPersonnel']['id']);
			      $this->set('pid',$personneldetail[0]['SqPersonnel']['personal_data']);
		      	      $this->set('report_id',$personneldetail[0]['SqPersonnel']['report_id']);
			      $this->set('time_last_sleep',$personneldetail[0]['SqPersonnel']['last_sleep']);
			      $this->set('time_since_sleep',$personneldetail[0]['SqPersonnel']['since_sleep']);
			      $this->set('person',$personneldetail[0]['SqPersonnel']['personal_data']);
			      $this->set('styledisplay','style="display:block"');
			                                
	                }else{
			    $this->set('heading','Add SQ Personal Data');
		            $this->set('button','Submit');
			    $this->set('id',0);
			     $this->set('pid',0);
		    	    $this->set('report_id',base64_decode($report_id));
			    $this->set('time_last_sleep','');
			    $this->set('time_since_sleep','');
			    $this->set('person','');
			    $this->set('roll_name','');
			    $this->set('snr','');
			    $this->set('styledisplay','style="display:none"');
			    
		        }
		
			
		           
	 }
	 
	      function  sqpersonnelprocess(){
		         $this->layout="ajax";
		         $personnelArray=array();
			 $personalid=explode("~",$this->data['personal_data']);
			 //$personnelArray['SqPersonnel']['personal_data']=$personalid[2];
			 $pid=$this->data['add_report_personnel_form']['pid'];
			 $res='';
		         if($this->data['add_report_personnel_form']['id']!=0){

					      
			      if($pid==$personalid[2]){	   
					  $personnelArray['SqPersonnel']['id']=$this->data['add_report_personnel_form']['id'];
					   $personnelArray['SqPersonnel']['personal_data']=$personalid[2];
					  $res='update';
					  
			      }
			      else if($pid!=$personalid[2]){
				      $personneldetail = $this->SqPersonnel->find('all', array('conditions' => array('SqPersonnel.personal_data'=>$personalid[2],'SqPersonnel.report_id'=>$this->data['report_id'])));
				      
				      if(count($personneldetail)>0){
					echo $res='avl';
					exit;
								
				      }else{
					 $personnelArray['SqPersonnel']['id']=$this->data['add_report_personnel_form']['id'];
					 $personnelArray['SqPersonnel']['personal_data']=$personalid[2];
					$res='update';
				      }
			     }
			   
			 }else{
				$personneldetail = $this->SqPersonnel->find('all', array('conditions' => array('SqPersonnel.report_id' =>$this->data['report_id'],'SqPersonnel.personal_data' =>$personalid[2])));
		
				 if(count($personneldetail)>0){
					 echo  $res='avl';
					  exit;
				 }else{
					 $personnelArray['SqPersonnel']['personal_data']=$personalid[2]; 
					 $res='add';
				 }
			  }
				
		   	  $personnelArray['SqPersonnel']['last_sleep']=$this->data['last_sleep'];
			  $personnelArray['SqPersonnel']['report_id']=$this->data['report_id']; 
		   	 
			  $personnelArray['SqPersonnel']['since_sleep']=$this->data['since_sleep'];
		           if($this->SqPersonnel->save($personnelArray)){
				     echo $res;
		                }else{
				     echo 'fail';
			        }
			        
   
		   exit;
		
	   }
	  public function report_sq_perssonel_list($id=null){
		
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
                $this->grid_access();		
		$this->layout="after_adminlogin_template";
		 $reportdetail = $this->SqReportMain->find('all', array('conditions' => array('SqReportMain.id' =>base64_decode($id))));
		       
		 $this->set('report_number',$reportdetail[0]['SqReportMain']['report_no']);
		 $this->set('report_id',$id);
		 $this->set('id',base64_decode($id));
		if(!empty($this->request->data))
		{
			//$action = $this->request->data['HssePersonnel']['action'];
			//$this->set('action',$action);
			//$limit = $this->request->data['HssePersonnel']['limit'];
			//$this->set('limit', $limit);
		}
		else
		{
			$action = 'all';
			$this->set('action','all');
			$limit =50;
			$this->set('limit', $limit);
		}
		$this->Session->write('action', $action);
		$this->Session->write('limit', $limit);
			
		unset($_SESSION['filter']);
		unset($_SESSION['value']);
		
	}
	
	public function get_all_personnel_list($report_id)
	{
		Configure::write('debug', '2'); 
		$this->layout = "ajax"; 
		$this->_checkAdminSession();
		$condition="";
	
                $condition="SqPersonnel.report_id = $report_id  AND SqPersonnel.isdeleted = 'N'";
		
                if(isset($_REQUEST['filter'])){
		switch($_REQUEST['filter']){
			case'name':
			 $spliNAME=explode(" ",$_REQUEST['value']);
			 $spliLname=$spliNAME[count($spliNAME)-1];
			 $spliFname=$spliNAME[0];
		         $adminCondition="AdminMaster.first_name like '%".$spliFname."%' AND AdminMaster.last_name like '%".$spliLname."%'";
			 $userDetail = $this->AdminMaster->find('all',array('conditions'=>$adminCondition));
		       	 if(count($userDetail)>0){
				$addimid=$userDetail[0]['AdminMaster']['id'];
			 }else{
				$addimid=0;
			 }
		         $condition .= "AND SqPersonnel.personal_data ='".$addimid."'";
			 break;
		}
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}

		 $adminArray = array();	
		 $count = $this->SqPersonnel->find('count' ,array('conditions' => $condition));
		 $adminA = $this->SqPersonnel->find('all',array('conditions' => $condition,'order' => 'SqPersonnel.id DESC','limit'=>$limit));
		  
		$i = 0;
		foreach($adminA as $rec)
		{			
			if($rec['SqPersonnel']['isblocked'] == 'N')
			{
				$adminA[$i]['SqPersonnel']['blockHideIndex'] = "true";
				$adminA[$i]['SqPersonnel']['unblockHideIndex'] = "false";
				$adminA[$i]['SqPersonnel']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['SqPersonnel']['blockHideIndex'] = "false";
				$adminA[$i]['SqPersonnel']['unblockHideIndex'] = "true";
				$adminA[$i]['SqPersonnel']['isdeletdHideIndex'] = "false";
			}
			$adminA[$i]['SqPersonnel']['name'] = $rec['AdminMaster']['first_name']." ".$rec['AdminMaster']['last_name'];
			$sam=explode(" ",$rec['AdminMaster']['modified']);
			$snr=explode("-",$sam[0]);
			$seniorty=$snr[2]."/".$snr[1]."/".$snr[0];
			$adminA[$i]['SqPersonnel']['seniority']=$seniorty;
			$rollMaster_info= $this->RoleMaster->find('all', array('conditions' => array('RoleMaster.id' =>$rec['AdminMaster']['role_master_id'])));
			if(count($rollMaster_info)>0){
				$adminA[$i]['SqPersonnel']['position']=$rollMaster_info[0]['RoleMaster']['role_name'];
			}else{
				$adminA[$i]['SqPersonnel']['position']='';
			}
			
		    $i++;
		}
		  if($count==0){
			$adminArray=array();
		  }else{
			 $adminArray = Set::extract($adminA, '{n}.SqPersonnel');
		  }
	 
		  
                  $this->set('total', $count);  //send total to the view
		  $this->set('admins', $adminArray);  //send products to the view
		  //$this->set('status', $action);
	}
	
	
	   function personnel_block($id = null)
	     {
                        $this->_checkAdminSession();
			if(!$id)
			{
		           $this->redirect(array('action'=>'report_sq_list'), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['SqPersonnel']['id'] = $id;
					  $this->request->data['SqPersonnel']['isblocked'] = 'Y';
					  $this->SqPersonnel->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	      function personnel_unblock($id = null)
	     {
                        $this->_checkAdminSession();
			if(!$id)
			{
		           $this->redirect(array('action'=>'report_sq_list'), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['SqPersonnel']['id'] = $id;
					  $this->request->data['SqPersonnel']['isblocked'] = 'N';
					  $this->SqPersonnel->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	       function personnel_delete()
	     {
		      $this->layout="ajax";
		      $this->_checkAdminSession();
		      if($this->data['id']!=''){
			$idArray = explode("^", $this->data['id']);
                           foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['SqPersonnel']['id'] =$id;
					  $this->request->data['SqPersonnel']['isdeleted'] = 'Y';
					  $this->SqPersonnel->save($this->request->data,false);
					  
			       }
		
			       echo 'ok';
			        exit;
		      }else{
			 $this->redirect(array('action'=>'report_sq_list'), null, true);
		      }
			       
			      
			       
	
	      }
	
	
	    public function report_sq_incident_list($id=null){
		
		$this->_checkAdminSession();
	
		$this->_getRoleMenuPermission();
                $this->grid_access();		
		$this->layout="after_adminlogin_template";
		$reportdetail = $this->SqReportMain->find('all', array('conditions' => array('SqReportMain.id' =>base64_decode($id))));
                $this->set('id',base64_decode($id));

		 
		 $this->set('report_id',$id);
		 $this->set('report_number',$reportdetail[0]['SqReportMain']['report_no']);	 
		if(!empty($this->request->data))
		{
			$action = $this->request->data['SqReportIncident']['action'];
			$this->set('action',$action);
			$limit = $this->request->data['SqReportIncident']['limit'];
			$this->set('limit', $limit);
		}
		else
		{
			$action = 'all';
			$this->set('action','all');
			$limit =50;
			$this->set('limit', $limit);
		}
		$this->Session->write('action', $action);
		$this->Session->write('limit', $limit);
			
		unset($_SESSION['filter']);
		unset($_SESSION['value']);
	
		
	}
	
	
	
	
	
	
	
	public function get_all_sqincident_list($report_id)
	{
		Configure::write('debug', '2');  
		$this->layout = "ajax"; 
		$this->_checkAdminSession();
		$condition="";
		
		
		$condition="SqReportIncident.report_id = $report_id AND SqReportIncident.isdeleted = 'N'";
		
                if(isset($_REQUEST['filter'])){
		switch($_REQUEST['filter']){
			case'report_no':
			$condition .= "AND ucase(SqReportIncident.".$_REQUEST['filter'].") like '".$_REQUEST['value']."%'";	
			break;	
		}
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			//$condition .= " order by Category.id DESC";
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}

		//$count = $this->HsseIncident->find('count' ,array('conditions' => $condition)); 
		$adminArray = array();	
		 $count = $this->SqReportIncident->find('count' ,array('conditions' => $condition));
		 $adminA = $this->SqReportIncident->find('all',array('conditions' => $condition,'order' => 'SqReportIncident.id DESC','limit'=>$limit));
		  
		$i = 0;
		foreach($adminA as $rec)
		{			
			if($rec['SqReportIncident']['isblocked'] == 'N')
			{
				$adminA[$i]['SqReportIncident']['blockHideIndex'] = "true";
				$adminA[$i]['SqReportIncident']['unblockHideIndex'] = "false";
				$adminA[$i]['SqReportIncident']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['SqReportIncident']['blockHideIndex'] = "false";
				$adminA[$i]['SqReportIncident']['unblockHideIndex'] = "true";
				$adminA[$i]['SqReportIncident']['isdeletdHideIndex'] = "false";
			}
			$adminA[$i]['SqReportIncident']['sqservice_type'] = $rec['SqService']['type'];
			$adminA[$i]['SqReportIncident']['sqdamage_type'] = $rec['SqDamage']['type'];
			$adminA[$i]['SqReportIncident']['incidentseverity_type'] = $rec['IncidentSeverity']['type'];
			
		    $i++;
		}
		
		 if($count==0){
			$adminArray=array();
		  }else{
			 $adminArray = Set::extract($adminA, '{n}.SqReportIncident');
		  }

                  $this->set('total', $count);  //send total to the view
		  $this->set('admins', $adminArray);  //send products to the view
	}
	 
	    function incident_block($id = null)
	     {
          
			if(!$id)
			{
		           $this->redirect(array('action'=>report_sq_list), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['SqReportIncident']['id'] = $id;
					  $this->request->data['SqReportIncident']['isblocked'] = 'Y';
					  $this->SqReportIncident->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	      function incident_unblock($id = null)
	     {
          
			if(!$id)
			{
		           $this->redirect(array('action'=>report_sq_list), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['SqReportIncident']['id'] = $id;
					  $this->request->data['SqReportIncident']['isblocked'] = 'N';
					  $this->SqReportIncident->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	       function incident_delete()
	     {
		       $this->layout="ajax";
	 
			if($this->data['id']!=''){
				$idArray = explode("^", $this->data['id']);
				   foreach($idArray as $id)
				       {
						  $id = $id;
						  $this->request->data['SqReportIncident']['id'] =$id;
						  $this->request->data['SqReportIncident']['isdeleted'] = 'Y';
						  $this->SqReportIncident->save($this->request->data,false);
						  
				       }
			
				       echo 'ok';
				       exit;
		      }else{
			 $this->redirect(array('action'=>report_sq_list), null, true);
		      }
	 
	      }
	

	 
	 
	 
	 
	 function add_sq_incident($reoprt_id=null,$incident_id=null){
		 $this->_checkAdminSession();
         	 $this->_getRoleMenuPermission();
                 $this->layout="after_adminlogin_template";
		 $sqdamage = $this->SqDamage->find('all',array('conditions'=>array('SqDamage.parrent_id'=>0)));
		 $incidentSeverity = $this->IncidentSeverity->find('all',array('conditions'=>array('servrity_type'=>'sq')));
		 $sqservice = $this->SqService->find('all');
		 $this->set('sqdamage',$sqdamage);
		 $this->set('sqservice',$sqservice);
		 $this->set('incidentSeverity',$incidentSeverity);
		 $incidentdetail = $this->SqReportIncident->find('all', array('conditions' => array('SqReportIncident.id' =>base64_decode($incident_id))));
		 $count = $this->SqReportIncident->find('count', array('conditions' => array('SqReportIncident.report_id' =>base64_decode($reoprt_id))));
		 
		 $reportdetail = $this->SqReportMain->find('all', array('conditions' => array('SqReportMain.id' =>base64_decode($reoprt_id))));
	
		   $this->set('report_number',$reportdetail[0]['SqReportMain']['report_no']);
		   //echo '<pre>';
		   //print_r($incidentdetail);
	 	    if(count($incidentdetail)>0)
		        {
			
			        if($incidentdetail[0]['SqReportIncident']['incident_date']!=''){
					
					$dateInc=explode("-",$incidentdetail[0]['SqReportIncident']['incident_date']);
					$inc_date=$dateInc[1].'-'.$dateInc[2].'-'.$dateInc[0];
				}else{
					$inc_date='';
				}
			
		                $sqdamagesubdetail = $this->SqDamage->find('all',array('conditions'=>array('SqDamage.parrent_id'=>$incidentdetail[0]['SqReportIncident']['damage_category'])));
				$this->set('sqdamagesubdetail',$sqdamagesubdetail);
			        $this->set('heading','Edit SQ Incident Data');
				$this->set('button','Update');
				$this->set('incident_id',$incidentdetail[0]['SqReportIncident']['id']);
				$this->set('incident_no',$incidentdetail[0]['SqReportIncident']['incident_no']);
				$this->set('date_incident',$inc_date);
				$this->set('report_id',base64_decode($reoprt_id));
				$this->set('incident_summary',$incidentdetail[0]['SqReportIncident']['incident_summary']); 
				$this->set('affected_service',$incidentdetail[0]['SqReportIncident']['affected_service']);
				$this->set('incident_time',$incidentdetail[0]['SqReportIncident']['incident_time']);
				$this->set('damage_category',$incidentdetail[0]['SqReportIncident']['damage_category']);
				$this->set('loss_time',$incidentdetail[0]['SqReportIncident']['loss_time']);
				$this->set('incident_date','');
				$this->set('sub_category',$incidentdetail[0]['SqReportIncident']['sub_category']);
				$this->set('combination',$incidentdetail[0]['SqReportIncident']['combination']);
				$this->set('detail',$incidentdetail[0]['SqReportIncident']['details']);
				$this->set('incident_servirity',$incidentdetail[0]['SqReportIncident']['incident_servirity']);
	                        
	                }else{
		              
			       if($count!=0){
		                 $countInc=$count+1;	
				}else{
				 $countInc=1;		
				}
					     
				$this->set('heading','Add SQ Incident Data');
				$this->set('button','Submit');
				$this->set('incident_id',0);
				$this->set('incident_no',$countInc);
				$this->set('date_incident','');
				$this->set('report_id',base64_decode($reoprt_id));
				$this->set('incident_summary',''); 
				$this->set('affected_service','');
				$this->set('incident_time','');
				$this->set('damage_category','');
				$this->set('loss_time','');
				$this->set('incident_date','');
				$this->set('sub_category','');
				$this->set('combination','');
				$this->set('detail','');
				$this->set('incident_servirity','');
			}
	 }
	 
	 function  sqncidentprocess(){
		         $this->layout="ajax";
		
	                 $identArray=array();
				        
				if($this->data['add_report_incident_form']['id']!=0){
				       $res='update';
				       $incidentArray['SqReportIncident']['id']=$this->data['add_report_incident_form']['id'];
				}else{
				       $res='add';
				}
		   	       $incidentArray['SqReportIncident']['incident_time']=$this->data['incident_time'];
			       if($this->data['date_incident']!=''){
				       $dateIncident=explode("-",$this->data['date_incident']);
				       $incidentArray['SqReportIncident']['incident_date']=$dateIncident[2]."-".$dateIncident[0]."-".$dateIncident[1];
			        }else{
				       $incidentArray['SqReportIncident']['incident_date']='';	
				}
				if($this->data['incident_severity']!=0){	
				$incidentArray['SqReportIncident']['incident_servirity']=$this->data['incident_severity'];
				}else{
				 $incidentArray['SqReportIncident']['incident_servirity']=0;	
				}
				$incidentArray['SqReportIncident']['loss_time']=$this->data['loss_time'];
				if(isset($this->data['loss_time'])){
				      $incidentArray['SqReportIncident']['loss_time']=$this->data['loss_time'];
				}else{
				      $incidentArray['SqReportIncident']['loss_time']=0;     
				}
				        
									
				if(isset($this->data['damage_category'])){
				       $incidentArray['SqReportIncident']['damage_category']=$this->data['damage_category'];
				}else{
				      $incidentArray['SqReportIncident']['damage_category']=0;     
				}	
				if(isset($this->data['sub_category'])){
				       $incidentArray['SqReportIncident']['sub_category']=$this->data['sub_category'];
				}else{
				      $incidentArray['SqReportIncident']['sub_category']=0;     
				}

			        $incidentArray['SqReportIncident']['report_id']=$this->data['report_id'];
				$incidentArray['SqReportIncident']['incident_no']=$this->data['incident_no'];
				
				
				$incidentArray['SqReportIncident']['affected_service']=$this->data['affected_Service'];
				
				if($this->data['detail']!=''){
				      $incidentArray['SqReportIncident']['details']=$this->data['detail'];
				}else{
				      $incidentArray['SqReportIncident']['details']='';
				}
				if($this->data['incident_summary']!=''){
				      $incidentArray['SqReportIncident']['incident_summary']=$this->data['incident_summary'];
				}else{
				      $incidentArray['SqReportIncident']['incident_summary']='';
				}
		
		          
                               if($this->SqReportIncident->save($incidentArray)){
				     echo $res;
		                }else{
				     echo 'fail';
			        }
				
			
		  
		   exit;
		   
		
	 }
	 
	 
	 function displaycontentfordamage(){
		   $this->layout="ajax";
		if(!empty($this->data)){
	         	        $sqdamagedetail=$this->SqDamage->find('all', array('conditions' => array('SqDamage.parrent_id' =>$this->data['type'])));
					if(count($sqdamagedetail)>0){ 
					   $this->set('sqdamagesubdetail',$sqdamagedetail);
					}else{
					   $this->set('sqdamagesubdetail',array());	
					}
		 
			}
			
		}
	     

	  
	   
	 
	      
	      function add_sq_remidial($report_id=null,$remidial_id=null){
		 $this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
		 $this->grid_access();
		 $this->layout="after_adminlogin_template";
		 $priority = $this->Priority->find('all');
		 $userDetail = $this->AdminMaster->find('all');
		 $this->set('priority',$priority);
		 $this->set('responsibility',$userDetail);
		 $this->set('created_by',$_SESSION['adminData']['AdminMaster']['first_name']." ".$_SESSION['adminData']['AdminMaster']['last_name']);
		 $reportdetail = $this->SqReportMain->find('all', array('conditions' => array('SqReportMain.id' =>base64_decode($report_id))));
		 $countRem= $this->SqRemidial->find('count',array('conditions'=>array('SqRemidial.report_no'=>base64_decode($report_id))));      
      		 $this->set('report_number',$reportdetail[0]['SqReportMain']['report_no']);      
		 $this->set('reportno',base64_decode($report_id));     
		 if($remidial_id==''){
			 $this->set('id', 0);
			 if($countRem!=0){
		                 $countRem=$countRem+1;	
			 }else{
				 $countRem=1;		
			}
			 $this->set('countRem', $countRem);
			 $this->set('heading', 'Add Remedial Action Item' );
			 $this->set('button','Submit' );
			 $this->set('remidial_create','');
			 $this->set('remidial_summery','');
			 $this->set('remidial_closer_summary','');
			 $this->set('remidial_action','');
			 $this->set('remidial_priority','');
			 $this->set('remidial_closure_target','');
			 $this->set('remidial_responsibility','');
			 $this->set('remidial_reminder_data','');
			 $this->set('remidial_closer_summary',' ');
			 $this->set('remidial_closure_date','');
			 $this->set('remidial_style','style="display:none"');
			 $this->set('remidial_button_style','style="display:block"');
		 }else{
			
		         $remidialData= $this->SqRemidial->find('all',array('conditions'=>array('SqRemidial.id'=>base64_decode($remidial_id))));
			 $this->set('countRem',  $remidialData[0]['SqRemidial']['remedial_no']);
			 $this->set('id', base64_decode($remidial_id));
			 $rempriority = $this->Priority->find('all',array('conditions'=>array('id'=>base64_decode($remidial_id))));
			 $this->set('heading', 'Edit Remedial Action Item' );
			 $this->set('button', 'Update');
			 $remidialcreate=explode("-",$remidialData[0]['SqRemidial']['remidial_create']);
			 $this->set('remidial_create',$remidialcreate[1].'-'.$remidialcreate[2].'-'.$remidialcreate[0]);
			 $this->set('remidial_summery',$remidialData[0]['SqRemidial']['remidial_summery']);
			 
			 $this->set('remidial_action',$remidialData[0]['SqRemidial']['remidial_action']);
			 $this->set('remidial_priority',$remidialData[0]['SqRemidial']['remidial_priority']);
			 $this->set('remidial_closure_target',$remidialData[0]['SqRemidial']['remidial_closure_target']);
			 $this->set('remidial_responsibility',$remidialData[0]['SqRemidial']['remidial_responsibility']);
			if($remidialData[0]['SqRemidial']['remidial_closure_date']!='0000-00-00'){
			 $closerdate=explode("-",$remidialData[0]['SqRemidial']['remidial_closure_date']);
			 $this->set('remidial_closure_date',$closerdate[1].'/'.$closerdate[2].'/'.$closerdate[0]);
			 $this->set('remidial_closer_summary',$remidialData[0]['SqRemidial']['remidial_closer_summary']);
			 $this->set('remidial_button_style','style="display:none"');
			}else{
			  $this->set('remidial_closer_summary',' ');
			  $this->set('remidial_closure_date','');
			  $this->set('remidial_button_style','style="display:block"');
			}
			 $this->set('remidial_style','style="display:block"');
			 $this->set('remidial_reminder_data',$remidialData[0]['SqRemidial']['remidial_reminder_data']);
			 }
		}
		
		function datecalculate(){
			$this->layout="ajax";
			$rempriority = $this->Priority->find('all',array('conditions'=>array('id'=>$this->data['remidial_priority'])));
			$ymd_format_array=explode('-',$this->data['remidial_create']);
			switch($rempriority[0]['Priority']['time_type']){
				case'days':
					 
					       echo date('d/m/Y H:i:s', mktime(0,0,0,$ymd_format_array[0],$ymd_format_array[1]+$rempriority[0]['Priority']['time'],$ymd_format_array[2]));
				break;
				case'hrs':
		
					       $time=$rempriority[0]['Priority']['time'];
					       echo date('d/m/Y H:i:s', mktime($time,0,0,$ymd_format_array[0],$ymd_format_array[1],$ymd_format_array[2]));
								
			         break;
				
			}
			exit;
		}
		
			     

		
		
		
		function remidialprocess(){
			$this->layout="ajax";
			$this->_checkAdminSession();
			
	                $remidialArray=array();
			 
				if($this->data['add_report_remidial_form']['id']!=0){
				       $res='update';
				       $remidialArray['SqRemidial']['id']=$this->data['add_report_remidial_form']['id'];
				}else{
					$res='add';
				 }
			   $remidialdate=explode("-",$this->data['remidial_create']);	       
		   	   $remidialArray['SqRemidial']['remidial_create']=$remidialdate[2].'-'.$remidialdate[0].'-'.$remidialdate[1];
			   $remidialArray['SqRemidial']['remidial_createby']=$_SESSION['adminData']['AdminMaster']['id']; 
		           $remidialArray['SqRemidial']['report_no']=$this->data['report_no'];
			   $remidialArray['SqRemidial']['remedial_no']=$this->data['countRem'];
			   $prority=explode("~",$this->data['remidial_priority']);
			   $remidialArray['SqRemidial']['remidial_priority']=$prority[0];
			   $remidialArray['SqRemidial']['remidial_responsibility']=$this->data['responsibility'];
			   $remidialArray['SqRemidial']['remidial_summery']=$this->data['add_report_remidial_form']['remidial_summery'];
			   $remidialArray['SqRemidial']['remidial_closer_summary']=$this->data['add_report_remidial_form']['remidial_closer_summary'];
			   $remidialArray['SqRemidial']['remidial_action']=$this->data['add_report_remidial_form']['remidial_action'];
			   $remidialArray['SqRemidial']['remidial_reminder_data']=$this->data['add_report_remidial_form']['remidial_reminder_data'];
			   $remidialArray['SqRemidial']['remidial_closure_target']=$this->data['add_report_remidial_form']['remidial_closure_target'];
			   if(isset($this->data['remidial_closure_date']) && $this->data['remidial_closure_date']!=''){
			     $closerdate=explode("-",$this->data['remidial_closure_date']);
			     $remidialArray['SqRemidial']['remidial_closure_date']=$closerdate[0].'-'.$closerdate[1].'-'.$closerdate[2];
			     }else{
			     $remidialArray['SqRemidial']['remidial_closure_date']=' ';
			     }
			     
			     
			     $createON = $remidialArray['SqRemidial']['remidial_create'].' 00:00:00';
		             $explodeCTR=explode(" ",$remidialArray['SqRemidial']['remidial_closure_target']);
			     $explodeCTD=explode("/",$explodeCTR[0]);
			     $reminderON=$explodeCTD[1].'-'.$explodeCTD[0].'-'.$explodeCTD[2]." ".$explodeCTR[1];
			     $strCreateOn = strtotime($createON);
			     $strReminderOn = strtotime($reminderON);
                             $remdate=$explodeCTD[2].'-'.$explodeCTD[1].'-'.$explodeCTD[0];
			     $dateHolder=array($remdate);
			     $dateIndex=array(3,7,30);
			     
			     for($e=0;$e<count($dateIndex);$e++){
				      $emaildateBefore=date('Y-m-d', mktime(0,0,0,$explodeCTD[1],$explodeCTD[0]-$dateIndex[$e],$explodeCTD[2]));
			              $strEmailBefore=strtotime($emaildateBefore); 
				      
				       if($strCreateOn<$strEmailBefore){
					    $dateHolder[]= $emaildateBefore;
					
				       }
				       
				
			        }
				
				for($e=0;$e<count($dateIndex);$e++){
				      
				      $emaildateAfter=date('Y-m-d', mktime(0,0,0,$explodeCTD[1],$explodeCTD[0]+$dateIndex[$e],$explodeCTD[2]));
				      $strEmailAfter=strtotime($emaildateAfter);
			               if($strCreateOn<$strEmailAfter){
					    $dateHolder[]= $emaildateAfter;
					
				       }
				 
			        }
				
				
				
				   $deleteREL = "DELETE FROM `remidial_email_lists` WHERE  `remedial_no` = {$remidialArray['SqRemidial']['remedial_no']} AND `report_id` = {$remidialArray['SqRemidial']['report_no']} AND `report_type`='sq'";
                                   $deleteval=$this->RemidialEmailList->query($deleteREL);
				   
				if($this->data['remidial_closure_date']==''){  
					for($d=0;$d<count($dateHolder);$d++){
					     
							$remidialEmailList=array();
							$userdetail = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$this->data['responsibility'])));
							$fullname=$userdetail[0]['AdminMaster']['first_name'].' '.$userdetail[0]['AdminMaster']['last_name'];
							$to=$userdetail[0]['AdminMaster']['admin_email'];
							$remidialEmailList['RemidialEmailList']['report_id']=$remidialArray['SqRemidial']['report_no'];
							$remidialEmailList['RemidialEmailList']['remedial_no']=$remidialArray['SqRemidial']['remedial_no'];
							$remidialEmailList['RemidialEmailList']['report_type']='sq';
							$remidialEmailList['RemidialEmailList']['email']=$to;
							$remidialEmailList['RemidialEmailList']['status']='N';
							$remidialEmailList['RemidialEmailList']['email_date']=$dateHolder[$d];
							$remidialEmailList['RemidialEmailList']['send_to']=$userdetail[0]['AdminMaster']['id'];
							
							$this->RemidialEmailList->create();
							$this->RemidialEmailList->save($remidialEmailList);
						
					}
			     
				}			     

			 if($this->SqRemidial->save($remidialArray)){
				     echo $res.'~sq';
		                }else{
				     echo 'fail';
			        }
			        
               

			exit;
			
		}
		
		
		function report_sq_remidial_list($id=null){
			$this->_checkAdminSession();
			$this->_getRoleMenuPermission();
			$this->grid_access();
			$this->layout="after_adminlogin_template";
			$this->set('report_no',base64_decode($id));
			$reportdetail = $this->SqReportMain->find('all', array('conditions' => array('SqReportMain.id' =>base64_decode($id))));
			$this->set('id',base64_decode($id));
			

			
			$this->set('report_val',$id);
			$this->set('report_number',$reportdetail[0]['SqReportMain']['report_no']);
			if(!empty($this->request->data))
			{
				$action = $this->request->data['SqRemidial']['action'];
				$this->set('action',$action);
				$limit = $this->request->data['SqRemidial']['limit'];
				$this->set('limit', $limit);
			}
			else
			{
				$action = 'all';
				$this->set('action','all');
				$limit =50;
				$this->set('limit', $limit);
			}
		        $this->Session->write('action', $action);
		        $this->Session->write('limit', $limit);
			unset($_SESSION['filter']);
			unset($_SESSION['value']);
		
		}
	
	
		public function get_all_remidial_list($report_id)
		{
			Configure::write('debug', '2');  //set debug to 0 for this function because debugging info breaks the XMLHttpRequest
			$this->layout = "ajax"; //this tells the controller to use the Ajax layout instead of the default layout (since we're using ajax . . .)
			//pr($_REQUEST);
			$this->_checkAdminSession();
			$condition="";

			
			$condition="SqRemidial.report_no = $report_id AND SqRemidial.isdeleted = 'N'";
			
			if(isset($_REQUEST['filter'])){
				switch($this->data['filter']){
				case'create_on':
				    $explodemonth=explode('/',$this->data['value']);
				    $day=$explodemonth[0];
				    $month=date('m', strtotime($explodemonth[1]));
				    $year="20$explodemonth[2]";
				    $createon=$year."-".$month."-".$day;
				    $condition .= "AND SqRemidial.remidial_create ='".$createon."'";	
				break;
				case'remidial_create_name':
				     $spliNAME=explode(" ",$_REQUEST['value']);
				     $spliLname=$spliNAME[count($spliNAME)-1];
				     $spliFname=$spliNAME[0];
				     $adminCondition="AdminMaster.first_name like '%".$spliFname."%' AND AdminMaster.last_name like '%".$spliLname."%'";
				     $userDetail = $this->AdminMaster->find('all',array('conditions'=>$adminCondition));
				     $addimid=$userDetail[0]['AdminMaster']['id'];
				     $condition .= "AND SqRemidial.remidial_createby ='".$addimid."'";
					
				break;
				case'responsibility_person':
				     $spliNAME=explode(" ",$_REQUEST['value']);
				     $spliLname=$spliNAME[count($spliNAME)-1];
				     $spliFname=$spliNAME[0];
				     $adminCondition="AdminMaster.first_name like '%".$spliFname."%' AND AdminMaster.last_name like '%".$spliLname."%'";
				     $userDetail = $this->AdminMaster->find('all',array('conditions'=>$adminCondition));
				     $addimid=$userDetail[0]['AdminMaster']['id'];
				     $condition .= "AND SqRemidial.remidial_responsibility ='".$addimid."'";	
				break;
				case'remidial_priority_name':
				     $priorityCondition = "Priority.type='".trim($_REQUEST['value'])."'";
				     $priorityDetail = $this->Priority->find('all',array('conditions'=>$priorityCondition));
				     $condition .= "AND SqRemidial.remidial_priority ='".$priorityDetail[0]['Priority']['id']."'";	
				break;
				}
			}
			
			
			$limit=null;
			if($_REQUEST['limit'] == 'all'){
						
				
			}else{
				$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
			}
	
			 $adminArray = array();	
			 $count = $this->SqRemidial->find('count' ,array('conditions' => $condition));
			 $adminA = $this->SqRemidial->find('all',array('conditions' => $condition,'order' => 'SqRemidial.id DESC','limit'=>$limit));

			$i = 0;
			foreach($adminA as $rec)
			{			
				if($rec['SqRemidial']['isblocked'] == 'N')
				{
					$adminA[$i]['SqRemidial']['blockHideIndex'] = "true";
					$adminA[$i]['SqRemidial']['unblockHideIndex'] = "false";
					$adminA[$i]['SqRemidial']['isdeletdHideIndex'] = "true";
				}else{
					$adminA[$i]['SqRemidial']['blockHideIndex'] = "false";
					$adminA[$i]['SqRemidial']['unblockHideIndex'] = "true";
					$adminA[$i]['SqRemidial']['isdeletdHideIndex'] = "false";
				}
				
			    $create_on=explode("-",$rec['SqRemidial']['remidial_create']);
			    $adminA[$i]['SqRemidial']['create_on']=date("d/M/y", mktime(0, 0, 0, $create_on[1], $create_on[2], $create_on[0]));
			    $lastupdated=explode(" ", $rec['SqRemidial']['modified']);
			    $lastupdatedate=explode("-",$lastupdated[0]);
			    $adminA[$i]['SqRemidial']['lastupdate']=date("d/M/y", mktime(0, 0, 0, $lastupdatedate[1], $lastupdatedate[2], $lastupdatedate[0]));
			    $createdate=explode("-",$rec['SqRemidial']['remidial_create']);
			    $adminA[$i]['SqRemidial']['createRemidial']=date("d/M/y", mktime(0, 0, 0, $createdate[1], $createdate[2], $createdate[0]));
			    $adminA[$i]['SqRemidial']['remidial_priority_name'] ='<font color='.$rec['Priority']['colorcoder'].'>'.$rec['Priority']['type'].'</font>';
			    $adminA[$i]['SqRemidial']['remidial_create_name'] = $rec['AdminMaster']['first_name']." ".$rec['AdminMaster']['last_name'];
			    $user_detail_reponsibilty= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$rec['SqRemidial']['remidial_responsibility'])));
			    $adminA[$i]['SqRemidial']['responsibility_person']=$user_detail_reponsibilty[0]['AdminMaster']['first_name']."  ".$user_detail_reponsibilty[0]['AdminMaster']['last_name'];
			    if($rec['SqRemidial']['remidial_closure_date']!='0000-00-00'){
			       $rem_cls_date=explode("-",$rec['SqRemidial']['remidial_closure_date']);
			       $adminA[$i]['SqRemidial']['closure']=date("d-M-y", mktime(0, 0, 0, $rem_cls_date[1], $rem_cls_date[2], $rem_cls_date[0]));
			    }else{
			        $adminA[$i]['SqRemidial']['closure']='';	
			    }
			    $i++;
			}
			if($count==0){
			   $adminArray=array();
		        }else{
			  $adminArray = Set::extract($adminA, '{n}.SqRemidial');
		        }
			 
			  $this->set('total', $count); 
			  $this->set('admins', $adminArray); 
			 
		}
		 
	    function remidial_block($id = null)
	     {

	     
	                if(!$id)
			{
		           $this->redirect(array('action'=>report_sq_list), null, true);
			}
		       else
		       
		       
		       {
			  
	                 	$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['SqRemidial']['id'] = $id;
					  $this->request->data['SqRemidial']['isblocked'] = 'Y';
					  $this->SqRemidial->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	     
	      }
	      
	      function remidial_unblock($id = null)
	     {
                  
			if(!$id)
			{
		           $this->redirect(array('action'=>report_sq_list), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['SqRemidial']['id'] = $id;
					  $this->request->data['SqRemidial']['isblocked'] = 'N';
					  $this->SqRemidial->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	       function remidial_delete()
	     {
		        $this->layout="ajax";
			 $idArray = explode("^", $this->data['id']);
				
	            	        foreach($idArray as $id)
			       {
				
				 $remidialData= $this->SqRemidial->find('all',array('conditions'=>array('SqRemidial.id'=>$id)));
				 $delete_remidials_email = "DELETE FROM `remidial_email_lists` WHERE `report_id` = {$remidialData[0]['SqRemidial']['report_no']}  AND  `remedial_no` = {$remidialData[0]['SqRemidial']['remedial_no']} AND `report_type`='sq'";
                                 $dHRem=$this->RemidialEmailList->query($delete_remidials_email);
				 $delete_remidials = "DELETE FROM `sq_remidials` WHERE `id` = {$id}";
                                 $dHR=$this->SqRemidial->query($delete_remidials);
					  
					  
			       }
			       echo 'ok';
			      
			   
			       exit;			 
		     
	      }
	      function add_sq_investigation($report_id,$investigation_id=null){
		        $this->_checkAdminSession();
			$this->_getRoleMenuPermission();
			$this->grid_access();
			$this->layout="after_adminlogin_template";
			$immediateCauseDetail = $this->ImmediateCause->find('all');
			$user_detail= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.isdeleted' =>'N','AdminMaster.isblocked' =>'N')));
					for($u=0;$u<count($user_detail);$u++){
						  $seniority=explode(" ",$user_detail[$u]['AdminMaster']['modified']);
						  $snr=explode("-",$seniority[0]);
						  $user_detail[$u]['AdminMaster']['user_seniority']=$snr[2]."/".$snr[1]."/".$snr[0];
						  $user_detail[$u]['AdminMaster']['position_seniorty']=$user_detail[$u]['AdminMaster']['first_name']." ".$user_detail[$u]['AdminMaster']['last_name'].'~'.$user_detail[$u]['RoleMaster']['role_name'].'~'.$user_detail[$u]['AdminMaster']['user_seniority'].'~'.$user_detail[$u]['AdminMaster']['id'];
						    
					}
					
		
		 
	                $this->set('userDetail',$user_detail);
			$this->set('report_id',base64_decode($report_id));
		  
			$reportdetail = $this->SqReportMain->find('all', array('conditions' => array('SqReportMain.id' =>base64_decode($report_id))));
			$this->set('report_number',$reportdetail[0]['SqReportMain']['report_no']); 	
			       
			$investigationdetail = $this->SqInvestigation->find('all', array('conditions' => array('SqInvestigation.report_id' =>base64_decode($report_id))));
		
		        if(count($investigationdetail)>0){
			  $this->set('id_holder',$investigationdetail[0]['SqInvestigation']['team_user_id']);
			  $this->set('investigation_team',explode(",",$investigationdetail[0]['SqInvestigation']['team_user_id']));
			  $iteam=explode(",",$investigationdetail[0]['SqInvestigation']['team_user_id']);
			  $investnameHolader=array();
			  $investnameHolader=array();
			  for($i=0;$i<count($iteam);$i++){
				$team_info= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$iteam[$i])));
		          	$seniorities=explode(" ",$team_info[0]['AdminMaster']['modified']);
				$snri=explode("-",$seniorities[0]);
				$investnameHolader[$i]['id']=$team_info[0]['AdminMaster']['id'];
				$investnameHolader[$i]['first_name']=$team_info[0]['AdminMaster']['first_name'];
				$investnameHolader[$i]['last_name']=$team_info[0]['AdminMaster']['last_name'];
				$investnameHolader[$i]['user_seniority']=$snri[2]."/".$snri[1]."/".$snri[0];
				$investnameHolader[$i]['role_name']=$team_info[0]['RoleMaster']['role_name'];
				$investnameHolader[$i]['position_seniorty']=$team_info[0]['AdminMaster']['first_name']." ".$team_info[0]['AdminMaster']['last_name'].'~'.$team_info[0]['RoleMaster']['role_name'].'~'.$investnameHolader[$i]['user_seniority'].'~'.$team_info[0]['AdminMaster']['id'];
			  }
			  $this->set('investnameHolader',$investnameHolader);
			  $this->set('investnameHolader',$investnameHolader);
			  $this->set('id_holder',$investigationdetail[0]['SqInvestigation']['team_user_id']);
		
			  $this->set('epeoplet',$investigationdetail[0]['SqInvestigation']['people_title']);
			  $this->set('epeopled',$investigationdetail[0]['SqInvestigation']['people_description']);
			  $this->set('eposplet',$investigationdetail[0]['SqInvestigation']['position_title']);
			  $this->set('epospled',$investigationdetail[0]['SqInvestigation']['position_description']);
			  $this->set('epartsplet',$investigationdetail[0]['SqInvestigation']['parts_title']);
			  $this->set('epartspled',$investigationdetail[0]['SqInvestigation']['parts_description']);
			  $this->set('epapert',$investigationdetail[0]['SqInvestigation']['paper_title']);
			  $this->set('epaperd',$investigationdetail[0]['SqInvestigation']['paper_descrption']);	
			  $this->set('button','Update');		
			}else{

		     		       
		        $this->set('button','Add');
			$this->set('id_holder','');
			$this->set('investigation_team',array());
			$this->set('investnameHolader',array());
			$this->set('epeoplet','Enter People Title');
			$this->set('epeopled','Enter People Description');
			$this->set('eposplet','Enter Position Title');
			$this->set('epospled','Enter Position Description');
			$this->set('epartsplet','Enter Parts Title');
			$this->set('epartspled','Enter Parts Description');
			$this->set('epapert','Enter Paper Title');
			$this->set('epaperd','Enter Paper Description');
			}
	      }
	      function retrivecause(){
		
		       $this->layout="ajax";
		       $couseList=$this->ImmediateSubCause->find('all', array('conditions' => array('ImmediateSubCause.imm_cau_id' =>$this->data['immediate_cause'])));
		       $this->set('couseList',$couseList);
		
	       }
	    function  save_sq_investigation(){
		      $this->layout="ajax";
		      $investigation=array();
		      $investigationData = $this->SqInvestigation->find('all', array('conditions' => array('SqInvestigation.report_id' =>$this->data['report_id'])));
		      if(count($investigationData)>0){
			$res='Update';
			$investigation['SqInvestigation']['id']=$investigationData[0]['SqInvestigation']['id'];  
		      }else{
			$res='add';
						
		      }
		        $investigation['SqInvestigation']['report_id']=$this->data['report_id'];
			$investigation['SqInvestigation']['team_user_id']=$this->data['id_holder'];

			
			if($this->data['people_title']=='Enter People Title'){
				
				$investigation['SqInvestigation']['people_title']='';
			}else{
			        $investigation['SqInvestigation']['people_title']=$this->data['people_title'];
			}
			
				
			
			if($this->data['people_descrption']=='Enter People Description'){
				
				$investigation['SqInvestigation']['people_description']='';
			}else{
			$investigation['SqInvestigation']['people_description']=$this->data['people_descrption'];
			}
			
			
			if($this->data['position_title']=='Enter Position Title'){
				
				$investigation['SqInvestigation']['position_title']='';
			}else{
			$investigation['SqInvestigation']['position_title']=$this->data['position_title'];
			}
			
				
			
	              if($this->data['position_descrption']=='Enter Position Description'){
				
			$investigation['SqInvestigation']['position_description']='';
			}else{
		$investigation['SqInvestigation']['position_description']=$this->data['position_descrption'];
			}
			
				
			 if($this->data['part_title']=='Enter Parts Title'){
				
			$investigation['SqInvestigation']['parts_title']='';
			}else{
			$investigation['SqInvestigation']['parts_title']=$this->data['part_title'];
			}
			
			
			if($this->data['part_descrption']=='Enter Parts Description'){
				
			$investigation['SqInvestigation']['parts_description']='';
			}else{
			
			$investigation['SqInvestigation']['parts_description']=$this->data['part_descrption'];
			}
			
		        if($this->data['paper_title']=='Enter Paper Title'){
				
			$investigation['SqInvestigation']['paper_title']='';
			}else{
			$investigation['SqInvestigation']['paper_title']=$this->data['paper_title'];
			}
			
				
		        if($this->data['paper_descrption']=='Enter Paper Description'){
			$investigation['SqInvestigation']['paper_descrption']='';
			}else{
		
			$investigation['SqInvestigation']['paper_descrption']=$this->data['paper_descrption'];
			}
				
			if($this->SqInvestigation->save($investigation)){
				     echo $res;
		                }else{
				     echo 'fail';
			        }
		     

		      exit;
		}
		
		function date_calculate(){
			$this->layout="ajax";
			$lowedate_array=explode("-",$this->data['lowre_date']);
			$currentdate_array=explode("-",$this->data['current_date']);
	                $date1 =  mktime(0, 0, 0, $lowedate_array[0],$lowedate_array[1],$lowedate_array[2]);
			$date2 =  mktime(0, 0, 0, $currentdate_array[0],$currentdate_array[1],$currentdate_array[2]);
                        $interval =($date2 - $date1)/(3600*24);
			echo $interval;
			exit;
			
		}
	      function main_close(){
		       $this->layout="ajax";
		       $reportData=array();
		       $reportData['Report']['id']=$this->data['report_id'];
		       if($this->data['type']=='close'){
		           $reportData['Report']['closer_date']=date("Y-m-d");
			   
		       }elseif($this->data['type']=='reopen'){
			   $reportData['Report']['closer_date']='0000-00-00';
			
		       }
		       if($this->Report->save($reportData)){
			  echo $this->data['type'].'~'.date("d/m/Y");
		       }
		       exit;
		
	      }
	     function download_file(){
	        $this->layout="ajax";
		echo '<pre>';
		print_r($this->data);
	       $filename = 'http://'.$_SERVER['HTTP_HOST'].$this->webroot.'img/file_upload/'.$this->data['filename'];
	       header("Content-type: application/force-download"); 
	       header('Content-type: Image/jpeg');
               header('Content-Disposition: attachment; filename='.$filename);
              exit;
	     }
	   	function report_sq_investigation_data_list($report_id){
		          $this->_checkAdminSession();
		          $this->_getRoleMenuPermission();
                          $this->grid_access();
		          $this->layout="after_adminlogin_template";
		          
			  
			  $reportdetail = $this->SqReportMain->find('all', array('conditions' => array('SqReportMain.id' =>base64_decode($report_id))));
			  $this->set('report_id',base64_decode($report_id));      
		          $this->set('report_number',$reportdetail[0]['SqReportMain']['report_no']);

		        if(!empty($this->request->data))
			  {
				$action = $this->request->data['HsseRemidial']['action'];
				$this->set('action',$action);
				$limit = $this->request->data['HsseRemidial']['limit'];
				$this->set('limit', $limit);
			  }
			else
			 {
				$action = 'all';
				$this->set('action','all');
				$limit =50;
				$this->set('limit', $limit);
			 }
		          $this->Session->write('action', $action);
		          $this->Session->write('limit', $limit);
			  unset($_SESSION['filter']);
			  unset($_SESSION['value']);
		          $this->set('report_val', $report_id);
			  $this->set('report_id',base64_decode($report_id));
	       }
	
	
	     	public function get_all_sq_investigation_data_list($report_id)
	      {
		Configure::write('debug', '2');  
		$this->layout = "ajax";
		$this->_checkAdminSession();
		$condition="";

		$condition="SqInvestigationData.report_id = $report_id  AND  SqInvestigationData.isdeleted = 'N'";
			
		      if(isset($_REQUEST['filter'])){	
		       switch($_REQUEST['filter']){
			case'incident_loss':
			  $lossDetail = $this->Loss->find('all', array('conditions' => array('type' =>trim($_REQUEST['value']))));
			  if(count($lossDetail)>0){
				$incidentLoss = $this->SqIncident->find('all', array('conditions' => array('incident_loss' =>$lossDetail[0]['Loss']['id'],'report_id'=>$report_id)));
				$incident_invest_id=array(); 
				for($i=0;$i<count($incidentLoss);$i++){
				      
				      $incident_invest_id[]=$incidentLoss[$i]['SqInvestigationData'][0]['id'];
				      
				}
				$incident_id='';
				if(count($incident_invest_id)>0){
				       $incident_id=implode(",",$incident_invest_id);
				}
				$condition = "SqInvestigationData.id IN (".$incident_id.")";
				}
				break;
				case'imd_cause_name':
				$imd_cause_holder=array();	
				$immdiateCause = $this->ImmediateCause->find('all', array('conditions' => array('type' =>trim($_REQUEST['value']))));
				
				if(count($immdiateCause)>0){
				       $investData = $this->SqInvestigationData->find('all', array('conditions' => array('report_id' =>$report_id)));
	      
				       if(count($investData)>0){
					      for($i=0;$i<count($investData);$i++){
					      $explode_imd_cause=explode(",",$investData[$i]['SqInvestigationData']['immediate_cause']);              
						      if($explode_imd_cause[0]==$immdiateCause[0]['ImmediateCause']['id']){
							$imd_cause_holder[]=$investData[$i]['SqInvestigationData']['id'];	
						      }
					      
					      }
					      if(count($imd_cause_holder)>0){
						      $incident_id=implode(",",$imd_cause_holder);
						      $condition = "SqInvestigationData.id IN (".$incident_id.")";
					      }
					      
				       }
				       
				      
				}
				$immdiateSubCause = $this->ImmediateSubCause->find('all', array('conditions' => array('type' =>trim($_REQUEST['value']))));
				
				if(count($immdiateSubCause)>0){
				       $investData = $this->SqInvestigationData->find('all', array('conditions' => array('SqInvestigationData.report_id' =>$report_id)));
	      
				       if(count($investData)>0){
					      for($i=0;$i<count($investData);$i++){
					      $explode_imd_cause=explode(",",$investData[$i]['SqInvestigationData']['immediate_cause']);              
						      if($explode_imd_cause[1]==$immdiateSubCause[0]['ImmediateSubCause']['id']){
							$imd_cause_holder[]=$investData[$i]['SqInvestigationData']['id'];	
						      }
					      
					      }
					      if(count($imd_cause_holder)>0){
						      $incident_id=implode(",",$imd_cause_holder);
						      $condition = "SqInvestigationData.id IN (".$incident_id.")";
					      }
					      
				       }
					      
				}
				break;
				case'root_cause_list':
				      $rootCause_array=array();
				      $rootCause = $this->RootCause->find('all', array('conditions' => array('type' =>trim($_REQUEST['value']))));
				      if(count($rootCause)>0){
					       $investData = $this->SqInvestigationData->find('all', array('conditions' => array('SqInvestigationData.report_id' =>$report_id)));
					       
					      if(count($investData)>0){
					      for($i=0;$i<count($investData);$i++){
					      $explode_root_cause=explode(",",$investData[$i]['SqInvestigationData']['root_cause_id']);
							      
					      
						      if(in_array($rootCause[0]['RootCause']['id'],$explode_root_cause)){
							$rootCause_array[]=$investData[$i]['SqInvestigationData']['id'];	
						      }
					      
					      }
					      if(count($rootCause_array)>0){
						      $incident_id=implode(",",$rootCause_array);
						      $condition = "SqInvestigationData.id IN (".$incident_id.")";
					      }
					      
				       } 
					      
				      }
				      
				break;
				 
			     }
		      } 	
		

	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					

		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}
                  
		 $adminArray = array();	
		 $count = $this->SqInvestigationData->find('count' ,array('conditions' => $condition));
		 $adminA = $this->SqInvestigationData->find('all',array('conditions' => $condition,'order' => 'SqInvestigationData.id DESC','limit'=>$limit, 'recursive'=>2));
		  
		$i = 0;
		foreach($adminA as $rec)
		{			
			if($rec['SqInvestigationData']['isblocked'] == 'N')
			{
				$adminA[$i]['SqInvestigationData']['blockHideIndex'] = "true";
				$adminA[$i]['SqInvestigationData']['unblockHideIndex'] = "false";
				$adminA[$i]['SqInvestigationData']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['SqInvestigationData']['blockHideIndex'] = "false";
				$adminA[$i]['SqInvestigationData']['unblockHideIndex'] = "true";
				$adminA[$i]['SqInvestigationData']['isdeletdHideIndex'] = "false";
			}
			$adminA[$i]['SqInvestigationData']['damage'] = $rec['SqReportIncident']['SqDamage']['type'];
			$adminA[$i]['SqInvestigationData']['incidentSeverity'] = $rec['SqReportIncident']['IncidentSeverity']['type'];
			$adminA[$i]['SqInvestigationData']['SqService'] = $rec['SqReportIncident']['SqService']['type'];
					
			$explode_imd_cause=explode(",",$rec['SqInvestigationData']['immediate_cause']);
			$immidiate_cause= $this->ImmediateCause->find('all', array('conditions' => array('ImmediateCause.id' =>$explode_imd_cause[0])));
			if(count($immidiate_cause)>0){
				if(isset($explode_imd_cause[1])){
			        $immidiate_sub_cause= $this->ImmediateSubCause->find('all', array('conditions' => array('ImmediateSubCause.id' =>$explode_imd_cause[1])));
				        if(count($immidiate_sub_cause)>0){
						$adminA[$i]['SqInvestigationData']['imd_cause_name']=$immidiate_cause[0]['ImmediateCause']['type'].'<br/>'.$immidiate_sub_cause[0]['ImmediateSubCause']['type'];
					}else{
						$adminA[$i]['SqInvestigationData']['imd_cause_name']=$immidiate_cause[0]['ImmediateCause']['type'];
					}
			        }  
			}
			if($rec['SqInvestigationData']['root_cause_id']!=0){
				$explode_root_cause=explode(",",$rec['SqInvestigationData']['root_cause_id']);
					for($r=0;$r<count($explode_root_cause);$r++){
						if($explode_root_cause[$r]!=0){
						  $root_cause= $this->RootCause->find('all', array('conditions' => array('RootCause.id' =>$explode_root_cause[$r])));
						  $rootCauseval[$i][]=$root_cause[0]['RootCause']['type'];
						}
					}
				$adminA[$i]['SqInvestigationData']['root_cause_list']=implode("<br/>",$rootCauseval[$i]);
			}else{
				$adminA[$i]['SqInvestigationData']['root_cause_list']='';
			}
			if($rec['SqInvestigationData']['remedila_action_id']!=''){
			$remedila_action_id=explode(",",$rec['SqInvestigationData']['remedila_action_id']);

			
				for($r=0;$r<count($remedila_action_id);$r++){
					$rem_cause= $this->SqRemidial->find('all', array('conditions' => array('SqRemidial.id' =>$remedila_action_id[$r])));
					if(isset($rem_cause[0]['SqRemidial']['remidial_closer_summary'])){
					  $rem_Causeval[$i][]=$rem_cause[0]['SqRemidial']['remidial_closer_summary'];
					}else{
						$rem_Causeval[$i][]='';
					}
				}

		        $adminA[$i]['SqInvestigationData']['remidial_closer_summary']=implode("<br/>",$rem_Causeval[$i]);
			}else{
			$adminA[$i]['SqInvestigationData']['remidial_closer_summary']='';	
						
			}
			
			
		    $i++;
		}
		  if($count==0){
			$adminArray=array();
		  }else{
			$adminArray = Set::extract($adminA, '{n}.SqInvestigationData');
		  }
		  
		  $this->set('total', $count);  //send total to the view
		  
		  $this->set('admins', $adminArray);  //send products to the view
		  //$this->set('status', $action);
	}
	
	
	
	
			 
	    function investigation_data_block($id = null)
	     {

	     
	                if(!$id)
			{
		           $this->redirect(array('action'=>report_sq_list), null, true);
			}
		       else
		       
		       
		       {
			    
	                 	$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['SqInvestigationData']['id'] = $id;
					  $this->request->data['SqInvestigationData']['isblocked'] = 'Y';
					  $this->SqInvestigationData->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	     
	      }
	      
	      function investigation_data_unblock($id = null)
	     {
                  
			if(!$id)
			{
		           $this->redirect(array('action'=>report_sq_list), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['SqInvestigationData']['id'] = $id;
					  $this->request->data['SqInvestigationData']['isblocked'] = 'N';
					  $this->SqInvestigationData->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	       function investigation_data_delete()
	     {
		        $this->layout="ajax";
			 $idArray = explode("^", $this->data['id']);
				
	            	        foreach($idArray as $id)
			       {
				
					  $this->request->data['SqInvestigationData']['id'] = $id;
					  $this->request->data['SqInvestigationData']['isdeleted'] = 'Y';
					  $this->SqInvestigationData->save($this->request->data,false);
					  
			       }
			       echo 'ok';
			   		   
			       exit;			 
		     
	      }
		
	
	       function add_sq_investigation_data_analysis($report_id,$incident_id=null,$investigation_id=null){
		          $this->_checkAdminSession();
		          $this->_getRoleMenuPermission();
                          $this->grid_access();
		          $this->layout="after_adminlogin_template";
			  $immediateCauseDetail = $this->ImmediateCause->find('all');
			  $this->set('immediateCauseDetail',$immediateCauseDetail);
			  $remidialData= $this->SqRemidial->find('all',array('conditions'=>array('report_no'=>base64_decode($report_id),'SqRemidial.isblocked'=>'N','SqRemidial.isdeleted'=>'N')));
			  $this->set('remidialData',$remidialData);
			  $rootParrentCauseData= $this->RootCause->find('all',array('conditions'=>array('parrent_id'=>0)));
			  $this->set('rootParrentCauseData',$rootParrentCauseData);
			  $reportdetail = $this->SqReportMain->find('all', array('conditions' => array('SqReportMain.id' =>base64_decode($report_id))));
			  $this->set('report_id',base64_decode($report_id));      
		          $this->set('report_number',$reportdetail[0]['SqReportMain']['report_no']);
			  $incidentdetail = $this->SqReportIncident->find('all', array('conditions' => array('SqReportIncident.report_id' =>base64_decode($report_id),'SqReportIncident.isblocked' =>'N','SqReportIncident.isdeleted' =>'N')));
			  $this->set('incidentdetail',$incidentdetail);
			  if($investigation_id!=''){
				$investigationDetail = $this->SqInvestigationData->find('all', array('conditions' => array('SqInvestigationData.id' =>base64_decode($investigation_id)),'recursive'=>2));
				$this->set('investigation_no',$investigationDetail[0]['SqInvestigationData']['investigation_no']);
				$this->set('damage_type',$investigationDetail[0]['SqReportIncident']['SqDamage']['type']);
				$this->set('incident_summary', $investigationDetail[0]['SqReportIncident']['incident_summary']);
				$this->set('comments',$investigationDetail[0]['SqInvestigationData']['comments']);
				$this->set('heading','Edit Investigation Data Analysis');
				$this->set('button','Update');
				$this->set('edit_investigation_id',base64_decode($investigation_id));
			        $this->set('disabled','disabled="disabled"');
				$this->set('edit_incident_id',base64_decode($incident_id));
				$this->set('incident_no',$investigationDetail[0]['SqInvestigationData']['incident_no']);
				$explode_immd_cause=explode(",",$investigationDetail[0]['SqInvestigationData']['immediate_cause']);
			        if($explode_immd_cause[0]!=0){
					  $this->set('immediate_cause',$explode_immd_cause[0]);
					  if(isset($explode_immd_cause[1])){
					    $immediateSubCauseList= $this->ImmediateSubCause->find('all',array('conditions' => array('imm_cau_id'=>$explode_immd_cause[0])));
					    $this->set('immediateSubCauseList', $immediateSubCauseList);
					    $this->set('immediate_sub_cause',$explode_immd_cause[1]);
					  }else{
					    $this->set('immediate_sub_cause',0);	
					  }
					  
				}else{
					  
					  $this->set('immediateSubCauseList',array());
					  $this->set('immediate_cause',0);
					  $this->set('immediate_sub_cause',0);
				 }
				 if($investigationDetail[0]['SqInvestigationData']['remedila_action_id']!=''){
					
					  $condition = "SqRemidial.id IN (".$investigationDetail[0]['SqInvestigationData']['remedila_action_id'].")";
			                  $remidialList=$this->SqRemidial->find('all', array('conditions' => $condition));
				          $this->set('remidialList',$remidialList);
					
				}else{
				     $this->set('remidialList',array());	
				}
				$childRoot=array();
				if($investigationDetail[0]['SqInvestigationData']['root_cause_id']!=''){
					  $explode_root_cause=explode(",",$investigationDetail[0]['SqInvestigationData']['root_cause_id']);
				          $this->set('rootParrentCausevalue',$explode_root_cause[0]);
					  $condition = "RootCause.id IN (".$investigationDetail[0]['SqInvestigationData']['root_cause_id'].")";
			                  $rootCauseList=$this->RootCause->find('all', array('conditions' => $condition));
					  $this->set('parrentRoot',explode(",",$investigationDetail[0]['SqInvestigationData']['root_cause_id']));				  
					  for($r=0;$r<count($rootCauseList);$r++){
						$childRoot[$rootCauseList[$r]['RootCause']['id']][]=$this->RootCause->find('all', array('conditions' => array('RootCause.parrent_id'=>$rootCauseList[$r]['RootCause']['id'])));
						
					  }
					  $this->set('childRoot',$childRoot);
					  $this->set('explode_root_cause',$explode_root_cause);
			 		  
 
				}else{
				         $this->set('explode_root_cause',array());	
				}
				//echo '<pre>';
				//print_r($investigationDetail);
				if($investigationDetail[0]['SqInvestigationData']['remedila_action_id']!=''){
				   $tr_id=array();	
				   $idc=explode(',',$investigationDetail[0]['SqInvestigationData']['remedila_action_id']);
				   for($c=0;$c<count($idc);$c++){
					$tr_id[]='tr'.$idc[$c];
					
				   }
				   		
				   $this->set('tr_id',implode(',',$tr_id));	
				   $this->set('idContent',$investigationDetail[0]['SqInvestigationData']['remedila_action_id']);		
				}else{
				  $this->set('idContent','');
				   $this->set('tr_id','');
				}
				
				$this->set('id_holder',$investigationDetail[0]['SqInvestigationData']['remedila_action_id']);
			  }else{
				 $this->set('investigation_no','');
				 $this->set('damage_type','');
				 $this->set('incident_summary','');
				 $this->set('heading','Add Investigation Data Analysis');
			         $this->set('comments','');
			         $this->set('button','Submit');
				 $this->set('edit_investigation_id',0);
				 $this->set('disabled','');
				 $this->set('edit_incident_id',0);
				 $this->set('remidialList',array());
				 $this->set('immediate_cause',0);
				 $this->set('immediate_sub_cause',0);
				 $this->set('parrentRoot', array());
				 $this->set('rootParrentCausevalue',0);
				 $this->set('explode_root_cause',array());
				 $this->set('incident_no',0);
				 $this->set('id_holder',0);
				 $this->set('idContent',''); 
				 $this->set('tr_id','');
				 $this->set('immediateSubCauseList',array());
				
				
			  }
				  
	       }
	       function displayincidentdetail(){
		         $this->layout="ajax";
			  $immediateCauseDetail = $this->ImmediateCause->find('all');
			  $rclval='';
			  $this->set('immediateCauseDetail',$immediateCauseDetail);
		          $incidentdetail = $this->SqReportIncident->find('all', array('conditions' => array('SqReportIncident.id' =>$this->data['incidentid'])));
			  $damageData = $this->SqDamage->find('all', array('conditions' => array('SqDamage.id' =>$incidentdetail[0]['SqReportIncident']['damage_category'])));
					  
			 $this->set('ltype',$damageData[0]['SqDamage']['type']);
			 $this->set('incident_summary', $incidentdetail[0]['SqReportIncident']['incident_summary']);
			 $incidentdetailINvestigation = $this->SqInvestigationData->find('all', array('conditions' => array('SqInvestigationData.incident_id' =>$this->data['incidentid'])));
			 $zeroParrentId=$this->RootCause->find('all', array('conditions' => array("parrent_id"=>0)));
			 $this->set('zeroParrent',$zeroParrentId);
			 $condition = "SqRemidial.report_no=".$this->data['report_id']." AND SqRemidial.isblocked='N' AND SqRemidial.isdeleted='N'";
			 $remidialData=$this->SqRemidial->find('all', array('conditions' => $condition));
			 if(count($incidentdetailINvestigation)>0){
			   $investigation_no=count($incidentdetailINvestigation)+1;
			 }else{
			   $investigation_no=1;
			 }
			 echo $damageData[0]['SqDamage']['type'].'~'.$incidentdetail[0]['SqReportIncident']['incident_summary'].'~'.$investigation_no.'~'.$incidentdetail[0]['SqReportIncident']['incident_no'];
			
			
			
		      exit;
		
		
	       }
	       
	       function retriverootcause(){
		 $this->layout="ajax";
		 $rootChildCauseData= $this->RootCause->find('all',array('conditions'=>array('parrent_id'=>$this->data['id'])));
		 if(count($rootChildCauseData)>0){
		     $this->set('rootChildCauseData',$rootChildCauseData);
		     $this->set('parrentid',$this->data['id']);
		
		 }else{
			exit;
			
		 }
	
		
	       }
	       function save_date_analysis(){
		 $this->layout="ajax";
		  $investigationData=array();
		
		   if($this->data['investigation_id']!=0){
			$investigationData['SqInvestigationData']['id']=$this->data['investigation_id'];
			$res='update';
		  }elseif($this->data['investigation_id']==0){
			$res='add';
		  }
		  
		  
		  if($this->data['remidial_holder']!=''){
		       $rH=implode(",",array_values(array_unique(explode(",",$this->data['remidial_holder']))));
		  
		  }else{
		      $rH=''; 
		  }
		   
		  if($rH!='' &&  $rH[0]==','){
			
		    $rH=substr($rH, 1);
		  }
		   $investigationData['SqInvestigationData']['investigation_no']=$this->data['investigation_no'];
		   $investigationData['SqInvestigationData']['incident_no']=$this->data['incident_no'];
		   $investigationData['SqInvestigationData']['report_id']=$this->data['report_id'];
		   $investigationData['SqInvestigationData']['incident_id']=$this->data['incident_val'];
		   $investigationData['SqInvestigationData']['immediate_cause']=$this->data['causeCont'];
		   $investigationData['SqInvestigationData']['root_cause_id']=$this->data['rootCauseCont'];
		   $investigationData['SqInvestigationData']['remedila_action_id']=$rH;
		   if($this->data['comments']!=''){
			$investigationData['SqInvestigationData']['comments']=$this->data['comments'];
		   }else{
			$investigationData['SqInvestigationData']['comments']='';
		   }
		   if($this->SqInvestigationData->save($investigationData)){
			if($res=='add'){
			 
			 $last_incident_investigation=base64_encode($this->SqInvestigationData->getLastInsertId());
			}elseif($res=='update'){
			  $last_incident_investigation=base64_encode($this->data['investigation_id']);
			}
		 	$report_id=base64_encode($this->data['report_id']);
			 $incident_id=base64_encode($this->data['incident_val']);
		        echo $res.'~'.$report_id.'~'.$incident_id.'~'.$last_incident_investigation;
		        }else{
	                echo 'fail~0~0~0';
	 	      }
   
		 exit;
		 
		
	       }
	    function add_sq_feedback($id=null){
		      $this->layout="after_adminlogin_template";
		      $this->_checkAdminSession();
	             $this->_getRoleMenuPermission();
                     $this->grid_access();	

		     $reportdetail = $this->SqReportMain->find('all', array('conditions' => array('SqReportMain.id' =>base64_decode($id))));

		    
		  if($reportdetail[0]['SqReportMain']['clientreviewed']==3){
		           $this->set('clientreviewer', $reportdetail[0]['SqReportMain']['clientreviewer']);
		           $this->set('clientfeedback_remark','');
		           $clientfeeddetail = $this->SqClientfeedback->find('all', array('conditions' => array('SqClientfeedback.report_id' =>base64_decode($id))));
                          $this->set('report_id',base64_decode($id));
			       if(count($clientfeeddetail)>0){
					 $cls_date=explode("-",$clientfeeddetail[0]['SqClientfeedback']['close_date']);
					 $close_date=$cls_date[1].'-'.$cls_date[2].'-'.$cls_date[0];
					 $this->set('close_date',$close_date);
					 $this->set('heading','Edit Client Feedback');
					 $this->set('client_summary',$clientfeeddetail[0]['SqClientfeedback']['client_summary']);  
					 $this->set('button','Update'); 
					 $this->set('id',$clientfeeddetail[0]['SqClientfeedback']['id']);
			
			       }else{
					  $this->set('close_date','');
					  $this->set('heading','Add Client Feedback');
					  $this->set('client_summary','');
					  $this->set('button','Add');
					  $this->set('id',0);
			    		
			       }
		     
		    }
		    
		   
	    }
	        function clientfeedbackprocess()
		{
		   $this->layout="ajax";
	   	   if($this->data['add_report_client_data_form']['id']!=0){
		     $client_feed_back['SqClientfeedback']['id']=$this->data['add_report_client_data_form']['id'];
		     $res='Update';
			
		   }else{
		     $res='Add';
		   }
		   
		   $client_feed_back['SqClientfeedback']['report_id']=$this->data['report_id'];
		   if($this->data['close_date']!=''){
			$clsr=explode("-",$this->data['close_date']);
			$closer_date=$clsr[2]."-".$clsr[0]."-".$clsr[1];
			
		   }else{
			$closer_date='';
		   }
		   $client_feed_back['SqClientfeedback']['close_date']=$closer_date;
		   if($this->data['add_report_client_data_form']['client_summary']!=''){
		   $client_feed_back['SqClientfeedback']['client_summary']=$this->data['add_report_client_data_form']['client_summary'];
		   }
		    if($this->SqClientfeedback->save($client_feed_back)){
		        echo $res;
		        }else{
	                echo 'fail';
	 	      }
		   exit;
		}
         
		public function report_sq_attachment_list($id=null){
			$this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();   
      			$this->layout="after_adminlogin_template";
		
		        $reportdetail = $this->SqReportMain->find('all', array('conditions' => array('SqReportMain.id' =>base64_decode($id))));
			$this->set('report_number',$reportdetail[0]['SqReportMain']['report_no']);	       
	                $this->set('id',base64_decode($id));
				if(!empty($this->request->data))
				{
					$action = $this->request->data['SqAttachment']['action'];
					$this->set('action',$action);
					$limit = $this->request->data['SqAttachment']['limit'];
					$this->set('limit', $limit);
				}
				else
				{
					$action = 'all';
					$this->set('action','all');
					$limit =50;
					$this->set('limit', $limit);
				}
		        $this->Session->write('action', $action);
		        $this->Session->write('limit', $limit);
			
		        unset($_SESSION['filter']);
		        unset($_SESSION['value']);
		
	      }
	    public function get_all_attachment_list($report_id)
	    
	{
		Configure::write('debug', '2');  //set debug to 0 for this function because debugging info breaks the XMLHttpRequest
		$this->layout = "ajax"; //this tells the controller to use the Ajax layout instead of the default layout (since we're using ajax . . .)
		//pr($_REQUEST);
		$this->_checkAdminSession();
		$condition="";
	
		
		$condition="SqAttachment.report_id = $report_id AND    SqAttachment.isdeleted = 'N'";
		
		
                if(isset($_REQUEST['filter'])){
			$condition .= "AND ucase(SqAttachment.".$_REQUEST['filter'].") like '".$_REQUEST['value']."%'";	
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			//$condition .= " order by Category.id DESC";
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}

		//$count = $this->HsseIncident->find('count' ,array('conditions' => $condition)); 
		$adminArray = array();	
		 $count = $this->SqAttachment->find('count' ,array('conditions' => $condition));
		 $adminA = $this->SqAttachment->find('all',array('conditions' => $condition,'order' => 'SqAttachment.id DESC','limit'=>$limit));
		  
		$i = 0;
		foreach($adminA as $rec)
		{			
			if($rec['SqAttachment']['isblocked'] == 'N')
			{
				$adminA[$i]['SqAttachment']['blockHideIndex'] = "true";
				$adminA[$i]['SqAttachment']['unblockHideIndex'] = "false";
				$adminA[$i]['SqAttachment']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['SqAttachment']['blockHideIndex'] = "false";
				$adminA[$i]['SqAttachment']['unblockHideIndex'] = "true";
				$adminA[$i]['SqAttachment']['isdeletdHideIndex'] = "false";
			}
			 $adminA[$i]['SqAttachment']['image_src']=$this->attachment_list('SqAttachment',$rec);
			
		    $i++;
		}
		
		
		 if($count==0){
			   $adminArray=array();
		 }else{
			  $adminArray = Set::extract($adminA, '{n}.SqAttachment');
		 }
		
		
		  
		  $this->set('total', $count);  //send total to the view
		  $this->set('admins', $adminArray);  //send products to the view
		  //$this->set('status', $action);
	}
	      
	          function add_sq_attachment($reoprt_id=null,$attachment_id=null){
		        $this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();   
		        $this->layout="after_adminlogin_template";
		
			if($attachment_id!=''){
				$attchmentdetail = $this->SqAttachment->find('all', array('conditions' => array('SqAttachment.id' =>base64_decode($attachment_id))));
				$imagepath=$this->file_edit_list($attchmentdetail[0]['SqAttachment']['file_name'],'SqAttachment',$attchmentdetail);
				$this->set('heading','Update File');
			        $this->set('attachment_id',$attchmentdetail[0]['SqAttachment']['id']);
			        $this->set('description',$attchmentdetail[0]['SqAttachment']['description']);
			        $this->set('button','Update');
			        $this->set('imagepath',$imagepath);
			        $this->set('imagename',$attchmentdetail[0]['SqAttachment']['file_name']);
				$this->set('attachmentstyle','style="display:block;"');
			}else{
			  	$this->set('heading','Add File');
			        $this->set('attachment_id',0);
			        $this->set('description','');
			        $this->set('button','Add');
			        $this->set('imagepath','');
			        $this->set('imagename','');
				$this->set('attachmentstyle','style="display:none;"');
				
			}
			

			$this->set('report_id',base64_decode($reoprt_id));
			
			$reportdetail = $this->SqReportMain->find('all', array('conditions' => array('SqReportMain.id' =>base64_decode($reoprt_id))));
			$this->set('report_number',$reportdetail[0]['SqReportMain']['report_no']);        
			
	       }
	       
       
	       
	       
	         function uploadimage($upparname=NULL,$deleteImageName=NULL){
		        $this->layout="ajax";
			$allowed_image =$this->image_upload_type();
			$this->upload_image_func($upparname,'Sqreports',$allowed_image);
			exit;
	          }
	           function  sqattachmentprocess(){
		         $this->layout="ajax";
	
		         $attachmentArray=array();
			 
		         if($this->data['Sqreports']['id']!=0){
				$res='update';
				$attachmentArray['SqAttachment']['id']=$this->data['Sqreports']['id'];
			 }else{
				 $res='add';
			  }
				
		   	   $attachmentArray['SqAttachment']['description']=$this->data['attachment_description'];
			   $attachmentArray['SqAttachment']['file_name']=$this->data['hiddenFile']; 
		           $attachmentArray['SqAttachment']['report_id']=$this->data['report_id'];
			   if($this->SqAttachment->save($attachmentArray)){
				     echo $res;
		                }else{
				     echo 'fail';
			        }
                         
		   exit;
		
	        }
		   function attachment_block($id = null)
	     {
         
			if(!$id)
			{
		           $this->redirect(array('action'=>report_sq_list), null, true);
			}
		       else
		        
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['SqAttachment']['id'] = $id;
					  $this->request->data['SqAttachment']['isblocked'] = 'Y';
					  $this->SqAttachment->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	      function attachment_unblock($id = null)
	     {
          
			if(!$id)
			{
		           $this->redirect(array('action'=>report_sq_list), null, true);
			}
		       else
		       {
			
				
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['SqAttachment']['id'] = $id;
					  $this->request->data['SqAttachment']['isblocked'] = 'N';
					  $this->SqAttachment->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	       function attachment_delete()
	     {
		        $this->layout="ajax";

			 $idArray = explode("^", $this->data['id']);
				
	            	        foreach($idArray as $id)
			       {
				
					  $this->request->data['SqAttachment']['id'] = $id;
					  $this->request->data['SqAttachment']['isdeleted'] = 'Y';
					  $this->SqAttachment->save($this->request->data,false);
					  
			       }
			       echo 'ok';
			   
			       exit;			 
		     
	      }
	   
	function  linkrocess(){
		   
		         $this->layout="ajax";
			 $this->_checkAdminSession();
         	         $this->_getRoleMenuPermission();
		         $this->grid_access();
		         $linkArray=array();
			 $explode_id_type=explode("_",$this->data['type']);
		          $sqlinkDetail = $this->SqLink->find('all', array('conditions' => array('SqLink.report_id' =>base64_decode($this->data['report_no']),'SqLink.link_report_id' =>$explode_id_type[1],'SqLink.type' =>$explode_id_type[0])));
			 if(count($sqlinkDetail)>0){
				echo 'avl';
				
			 }else{
			 $linkArray['SqLink']['type']=$explode_id_type[0];
			 $linkArray['SqLink']['report_id']=base64_decode($this->data['report_no']);
			 $linkArray['SqLink']['link_report_id']=$explode_id_type[1];
			 $linkArray['SqLink']['user_id']=$_SESSION['adminData']['AdminMaster']['id'];
			 
			 if($this->SqLink->save($linkArray)){
				 echo 'ok';
			 }else{
			         echo 'fail';
			 }
			
			 
			 }
			   
			   
			
                        
		   exit;
		
	}
	  public function report_sq_link_list($id=null,$typSearch){
		
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
                $this->grid_access();		
		$this->layout="after_adminlogin_template";
		$sqlinkDetail = $this->SqLink->find('all', array('conditions' => array('SqLink.report_id' =>base64_decode($id))));
		$reportdetail = $this->SqReportMain->find('all', array('conditions' => array('SqReportMain.id' =>base64_decode($id))));            
		$this->set('report_number',$reportdetail[0]['SqReportMain']['report_no']);
		$this->set('report_id',$id);
		$this->set('id',base64_decode($id));
		
		  $reportdetail = $this->SqReportMain->find('all', array('conditions' => array('SqReportMain.id' =>base64_decode($id))));     
		  $this->set('report_id_val',$id);
		  $this->AdminMaster->recursive=2;
		  $userDeatil = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$_SESSION['adminData']['AdminMaster']['id'])));
            
		 $reportDeatil=$this->derive_link_data($userDeatil); 
		  
	  
		  $this->set('reportDeatil',$reportDeatil);
		
		
		if(!empty($this->request->data))
		{
			//$action = $this->request->data['HssePersonnel']['action'];
			//$this->set('action',$action);
			//$limit = $this->request->data['HssePersonnel']['limit'];
			//$this->set('limit', $limit);
		}
		else
		{
			$action = 'all';
			$this->set('action','all');
			$limit =50;
			$this->set('limit', $limit);
		}
		$this->set('typSearch', base64_decode($typSearch));
		$this->Session->write('action', $action);
		$this->Session->write('limit', $limit);
			
		unset($_SESSION['filter']);
		unset($_SESSION['value']);
		
	}
	public function get_all_link_list($report_id,$filterTYPE)
	{
		Configure::write('debug', '2'); 
		$this->layout = "ajax"; 
		$this->_checkAdminSession();
		$condition="";
	
                $condition="SqLink.report_id =".base64_decode($report_id);
		
               if(isset($_REQUEST['filter'])){
	             $link_type=explode("~",$this->link_search($_REQUEST['value']));
		     $condition .= " AND SqLink.link_report_id ='".$link_type[0]."' AND SqLink.type ='".$link_type[1]."'";
		     
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}

		 $adminArray = array();
		  if($filterTYPE!='all'){
			
		    $condition .= " AND SqLink.type ='".$filterTYPE."'";
		
		 }
		 $count = $this->SqLink->find('count' ,array('conditions' => $condition));
		 $adminA = $this->SqLink->find('all',array('conditions' => $condition,'order' => 'SqLink.id DESC','limit'=>$limit));
		 

		  
		$i = 0;
		foreach($adminA as $rec)
		{
	
			if($rec['SqLink']['isblocked'] == 'N')
			{
				$adminA[$i]['SqLink']['blockHideIndex'] = "true";
				$adminA[$i]['SqLink']['unblockHideIndex'] = "false";
				$adminA[$i]['SqLink']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['SqLink']['blockHideIndex'] = "false";
				$adminA[$i]['SqLink']['unblockHideIndex'] = "true";
				$adminA[$i]['SqLink']['isdeletdHideIndex'] = "false";
			}
			$link_type=$this->link_grid($adminA[$i],$rec['SqLink']['type'],'SqLink',$rec);
		        $explode_link_type=explode("~",$link_type);
		        $adminA[$i]['SqLink']['link_report_no']=$explode_link_type[0];
		        $adminA[$i]['SqLink']['type_name']=$explode_link_type[1];

		
			
		    $i++;
		}
		  if($count==0){
			$adminArray=array();
		  }else{
			 $adminArray = Set::extract($adminA, '{n}.SqLink');
		  }
	 
		  
                  $this->set('total', $count);  //send total to the view
		  $this->set('admins', $adminArray);  //send products to the view
		  //$this->set('status', $action);
	}
	  function link_block($id = null)
	     {
                        $this->_checkAdminSession();
			if(!$id)
			{
		           $this->redirect(array('action'=>'report_sq_list'), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['SqLink']['id'] = $id;
					  $this->request->data['SqLink']['isblocked'] = 'Y';
					  $this->SqLink->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	      function link_unblock($id = null)
	     {
                        $this->_checkAdminSession();
			if(!$id)
			{
		           $this->redirect(array('action'=>'report_sq_list'), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['SqLink']['id'] = $id;
					  $this->request->data['SqLink']['isblocked'] = 'N';
					  $this->SqLink->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	       function link_delete()
	     {
		      $this->layout="ajax";
		      $this->_checkAdminSession();
		      if($this->data['id']!=''){
			$idArray = explode("^", $this->data['id']);
                           foreach($idArray as $id)
			       {
					  $id = $id;
					  $deleteproperty = "DELETE FROM `sq_links` WHERE `id` = {$id}";
                                          $deleteval=$this->SqLink->query($deleteproperty);
					  
					  
			       }
		
			       echo 'ok';
			        exit;
		      }else{
			 $this->redirect(array('action'=>'report_sq_list'), null, true);
		      }
			       
			      
			       
	
	      }
	    function report_sq_remedila_email_list($id=null){
			$this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();
			$this->layout="after_adminlogin_template";
			$this->set('report_no',base64_decode($id));
			$reportdetail = $this->SqReportMain->find('all', array('conditions' => array('SqReportMain.id' =>base64_decode($id))));
			$this->set('id',base64_decode($id));
	               	$this->set('report_val',$id);
			$this->set('report_number',$reportdetail[0]['SqReportMain']['report_no']);
			if(!empty($this->request->data))
			{
				$action = $this->request->data['RemidialEmailList']['action'];
				$this->set('action',$action);
				$limit = $this->request->data['RemidialEmailList']['limit'];
				$this->set('limit', $limit);
			}
			else
			{
				$action = 'all';
				$this->set('action','all');
				$limit =50;
				$this->set('limit', $limit);
			}
		        $this->Session->write('action', $action);
		        $this->Session->write('limit', $limit);
			unset($_SESSION['filter']);
			unset($_SESSION['value']);
		
		}
	
	
		public function get_all_remidial_email_list($report_id)
		{
			Configure::write('debug', '2');  //set debug to 0 for this function because debugging info breaks the XMLHttpRequest
			$this->layout = "ajax"; //this tells the controller to use the Ajax layout instead of the default layout (since we're using ajax . . .)
			//pr($_REQUEST);
			$this->_checkAdminSession();
			$condition="";

			
			$condition="RemidialEmailList.report_id = $report_id AND report_type='sq'";
			
			if(isset($_REQUEST['filter'])){
				//echo '<pre>';
				//print_r($_REQUEST);
		
				switch($this->data['filter']){
					case'email_date':
						$explodemonth=explode('/',$this->data['value']);
						$day=$explodemonth[0];
						$month=date('m', strtotime($explodemonth[1]));
						$year="20$explodemonth[2]";
						$createon=$year."-".$month."-".$day;
						$condition .= " AND RemidialEmailList.email_date ='".$createon."'";	
					break;
				        case'email':
			                        $condition .= " AND RemidialEmailList.email like '%".$_REQUEST['value']."%'";
						
					break;
				        case'responsibility_person':
					     $spliNAME=explode(" ",$_REQUEST['value']);
			                     $spliLname=$spliNAME[count($spliNAME)-1];
			                     $spliFname=$spliNAME[0];
		                             $adminCondition="AdminMaster.first_name like '%".$spliFname."%' AND AdminMaster.last_name like '%".$spliLname."%'";
			                     $userDetail = $this->AdminMaster->find('all',array('conditions'=>$adminCondition));
					     $addimid=$userDetail[0]['AdminMaster']['id'];
		                             $condition .= " AND RemidialEmailList.send_to ='".$addimid."'";	
					break;
				      				
				}
				
				
				
			}
			
			
			
			$limit=null;
			if($_REQUEST['limit'] == 'all'){
						
				//$condition .= " order by Category.id DESC";
			}else{
				$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
			}
	
			//$count = $this->HsseIncident->find('count' ,array('conditions' => $condition)); 
			 $adminArray = array();	
			 $count = $this->RemidialEmailList->find('count' ,array('conditions' => $condition));
			 $adminA = $this->RemidialEmailList->find('all',array('conditions' => $condition,'order' => 'RemidialEmailList.id DESC','limit'=>$limit));
	
			$i = 0;
			foreach($adminA as $rec)
			{			
			  if($rec['RemidialEmailList']['status'] == 'N')
				{
					$adminA[$i]['RemidialEmailList']['status_value'] = 'Not Send';
								
				}else{
					$adminA[$i]['RemidialEmailList']['status_value'] = "Sent";
					
				
				}
			    $remC= $this->SqRemidial->find('all', array('conditions' => array('SqRemidial.report_no' =>$rec['RemidialEmailList']['report_id'],'SqRemidial.remedial_no' =>$rec['RemidialEmailList']['remedial_no'])));
			    
			    $rrd=explode(" ",$remC[0]['SqRemidial']['remidial_reminder_data']);
			    $rrdE=explode("/",$rrd[0]);
			    $adminA[$i]['RemidialEmailList']['reminder_data']=date("d/M/y", mktime(0, 0, 0, $rrdE[1], $rrdE[0], $rrdE[2]));		
				
			    $create_on=explode("-",$remC[0]['SqRemidial']['remidial_create']);
			    $adminA[$i]['RemidialEmailList']['create_on']=date("d/M/y", mktime(0, 0, 0, $create_on[1], $create_on[2], $create_on[0]));
			    $user_detail_reponsibilty= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$rec['RemidialEmailList']['send_to'])));
			   	    
			    $adminA[$i]['RemidialEmailList']['responsibility_person']=$user_detail_reponsibilty[0]['AdminMaster']['first_name']."  ".$user_detail_reponsibilty[0]['AdminMaster']['last_name'];
			    $explodemail=explode("-",$rec['RemidialEmailList']['email_date']);
                            $adminA[$i]['RemidialEmailList']['email_date']=date("d/M/y", mktime(0, 0, 0, $explodemail[1], $explodemail[2], $explodemail[0]));
			    $i++;
			
			}
			
			if($count==0){
			   $adminArray=array();
		        }else{
			   $adminArray = Set::extract($adminA, '{n}.RemidialEmailList');
		        }
			 

			  
			  
			  $this->set('total', $count);  //send total to the view
			  $this->set('admins', $adminArray);  //send products to the view
			  //$this->set('status', $action);
		}
	      
	        function remidial_email_delete()
	        {
		        $this->layout="ajax";
		         $idArray = explode("^",$this->data['id']);
				
	            	        foreach($idArray as $id)
			       {
				
					 $deleteRem = "DELETE FROM `remidial_email_lists` WHERE `id` = {$id}";
                                         $deleteval=$this->RemidialEmailList->query($deleteRem);
						  
			       }
			       echo 'ok';
			      
			   
			       exit;			 
		     
	       }
	       
	       
	       function remidial_email_view($id,$remedial_no,$report_id){
			 $this->_checkAdminSession();
		         $this->_getRoleMenuPermission();
                         $this->grid_access();
			 $this->layout="ajax";
			 $remidialData= $this->SqRemidial->find('all',array('conditions'=>array('SqRemidial.report_no'=>$report_id,'SqRemidial.remedial_no'=>$remedial_no)));
			 $reportData= $this->SqReportMain->find('all',array('conditions'=>array('SqReportMain.id'=>$remidialData[0]['SqRemidial']['report_no'])));
			 $userData= $this->AdminMaster->find('all',array('conditions'=>array('AdminMaster.id'=>$remidialData[0]['SqRemidial']['remidial_responsibility'])));
			 $this->set('fullname',$userData[0]['AdminMaster']['first_name']." ".$userData[0]['AdminMaster']['last_name']);
			 $this->set('report_no',$reportData[0]['SqReportMain']['report_no']);
			 $this->set('remidialData',$remidialData);
		
		}
	       
}         
?>
