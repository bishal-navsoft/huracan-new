<?PHP
class JobsController extends AppController
{
	public $name = 'Jobs';
	public $uses = array('JobCustomerFeedbackElement','JobCustomerFeedback','Priority','JobReportMain','Report','SqReportMain','JnReportMain','BusinessType','Fieldlocation','Client','Country','AdminMaster','RolePermission','RoleMaster','AdminMenu','JobWellData','WellStatus','Welldata','JobRemidial','Priority','ConveyanceType','Conveyed','ConveyanceType','GyroJobData','GyroSn','Conveyance','GaugeData','TecCable','YSplitter','TempRange','PressRange','WhoConnector','Sau','GaugeType','Manufacture','JobAttachment','JobLink','Report','SqReportMain','JnReportMain','AuditReportMain','JobReportMain','LessonMain','LessonLink','RemidialEmailList','DocumentMain');
	public $helpers = array('Html','Form','Session','Js');
	//public $components = array('RequestHandler', 'Cookie', 'Resizer');
	
        public function report_job_list(){
		$this->_checkAdminSession();
	        $this->_getRoleMenuPermission();
		$this->grid_access();
		//$this->report_hsse_link();
		
		$this->layout="after_adminlogin_template";
	
		if(!empty($this->request->data))
		{
			$action = $this->request->data['JobReportMain']['action'];
			$this->set('action',$action);
			$limit = $this->request->data['JobReportMain']['limit'];
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
		$jobIdBoolen=array();
		$condition="";
		$condition = "JobReportMain.isdeleted = 'N'";
		
		$adminA = $this->JobReportMain->find('all' ,array('conditions' => $condition,'order' => 'JobReportMain.id DESC','limit'=>$limit));
		for($i=0;$i<count($adminA);$i++){
			if(($_SESSION['adminData']['AdminMaster']['id']==$adminA[$i]['JobReportMain']['created_by']) || ($_SESSION['adminData']['RoleMaster']['id']==1)){
			   array_push($jobIdBoolen,1);	
				
			}else{
			  array_push($jobIdBoolen,0);
			}
			
		}
		$this->Session->write('jobIdBoolen',$jobIdBoolen);
	}
	public function get_all_report($action ='all')
	{
		Configure::write('debug', '2');  
		$this->layout = "ajax"; 
		$this->_checkAdminSession();
		$condition="";
		$condition = "JobReportMain.isdeleted = 'N'";
                if(isset($_REQUEST['filter'])){
		switch($_REQUEST['filter']){
			case'report_no':
	$condition .= "AND ucase(JobReportMain.".$_REQUEST['filter'].") like '".$_REQUEST['value']."%'";	
			break;
		        case'client_name':
			$clientCondition= " ucase(Client.name) like '".$_REQUEST['value']."%'";
			$clientDetatail=$this->Client->find('all', array('conditions' =>$clientCondition));
          		$condition .= "AND JobReportMain.Client =".$clientDetatail[0]['Client']['id'];	
			break;
		        case'creater_name':
			$spliNAME=explode(" ",$_REQUEST['value']);
			$spliLname=$spliNAME[count($spliNAME)-1];
			$spliFname=$spliNAME[0];
			$adminCondition="AdminMaster.first_name like '%".$spliFname."%' AND AdminMaster.last_name like '%".$spliLname."%'";
			$userDetail = $this->AdminMaster->find('all',array('conditions'=>$adminCondition));
			$addimid=$userDetail[0]['AdminMaster']['id'];
			$condition .= "AND JobReportMain.created_by ='".$addimid."'";	
			break;
		        case'report_date_val':
		        $explodemonth=explode('/',$_REQUEST['value']);
			$day=$explodemonth[0];
			$month=date('m', strtotime($explodemonth[1]));
			$year="20$explodemonth[2]";
			$createon=$year."-".$month."-".$day;
			$condition .= "AND JobReportMain.report_date ='".$createon."'";	
		        break;  
		}
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}
		$count = $this->JobReportMain->find('count' ,array('conditions' => $condition));
		$adminArray = array();
		
	        $adminA = $this->JobReportMain->find('all' ,array('conditions' => $condition,'order' => 'JobReportMain.id DESC','limit'=>$limit)); 
		

		$i = 0;
		$jobIdBoolen=array();
		unset($_SESSION['jobIdBoolen']);
		
		foreach($adminA as $rec)
		{
			
			if(($_SESSION['adminData']['AdminMaster']['id']==$rec['JobReportMain']['created_by']) || ($_SESSION['adminData']['RoleMaster']['id']==1)){
				array_push($jobIdBoolen,1);
				$adminA[$i]['JobReportMain']['edit_permit'] ="false";
				$adminA[$i]['JobReportMain']['view_permit'] ="false";
				$adminA[$i]['JobReportMain']['delete_permit'] ="false";
				$adminA[$i]['JobReportMain']['block_permit'] ="false";
				$adminA[$i]['JobReportMain']['unblock_permit'] ="false";
				$adminA[$i]['JobReportMain']['checkbox_permit'] ="false";
				
				if($rec['JobReportMain']['isblocked'] == 'N')
			            {
					$adminA[$i]['JobReportMain']['blockHideIndex'] = "true";
					$adminA[$i]['JobReportMain']['unblockHideIndex'] = "false";
				        
			         }else{
					$adminA[$i]['JobReportMain']['blockHideIndex'] = "false";
					$adminA[$i]['JobReportMain']['unblockHideIndex'] = "true";
					
			        }
				
					
			}else{
				array_push($jobIdBoolen,0);
				$adminA[$i]['JobReportMain']['edit_permit'] ="true";
				$adminA[$i]['JobReportMain']['view_permit'] ="false";
				$adminA[$i]['JobReportMain']['delete_permit'] ="true";
				$adminA[$i]['JobReportMain']['block_permit'] ="true";
				$adminA[$i]['JobReportMain']['unblock_permit'] ="true";
			        $adminA[$i]['JobReportMain']['blockHideIndex'] = "true";
				$adminA[$i]['JobReportMain']['unblockHideIndex'] = "true";
				$adminA[$i]['JobReportMain']['checkbox_permit'] ="true";
				
				
			}
			    
		    $adminA[$i]['JobReportMain']['client_name'] = $rec['Client']['name'];    
		    $adminA[$i]['JobReportMain']['creater_name'] = $rec['AdminMaster']['first_name'].' '.$rec['AdminMaster']['last_name'];
		    $rPD=explode("-",$rec['JobReportMain']['report_date']);
		    $adminA[$i]['JobReportMain']['report_date_val'] = date("d/M/y", mktime(0, 0, 0, $rPD[1],$rPD[2],$rPD[0]));
		    $i++;
		}
	        
		if($count==0){
			$adminArray=array();
		  }else{
			$adminArray = Set::extract($adminA, '{n}.JobReportMain');
		  }

		$this->Session->write('jobIdBoolen',$jobIdBoolen);  
		$this->set('total', $count);  //send total to the view
	        $this->set('admins', $adminArray);  //send products to the view
		$this->set('status', $action);
	}

	
	
	
	function job_report_block($id = null)
	{
          
	
		if(!$id)
		{
			  // $this->Session->setFlash('Invalid id for admin');
			   $this->redirect(array('action'=>report_job__list), null, true);
		}
		else
		{
			 $idArray = explode("^", $id);
			 
			 
		
			foreach($idArray as $id)
			{
				   $id = $id;
				   $this->request->data['JobReportMain']['id'] = $id;
				   $this->request->data['JobReportMain']['isblocked'] = 'Y';
				   $this->JobReportMain->save($this->request->data,false);				
			}	     
			exit;			 
		}
	}
	
	function job_report_unblock($id = null)
	{

		
		if(!$id)
		{
	
			$this->redirect(array('action'=>report_job__list), null, true);
		}
		else
		{
			$idArray = explode("^", $id);
		
			foreach($idArray as $id)
			{
				$id = $id;
				$this->request->data['JobReportMain']['id'] = $id;
				$this->request->data['JobReportMain']['isblocked'] = 'N';
				$this->JobReportMain->save($this->request->data,false);
			}	
			exit;
		 
		}
	}
	
	function job_report_delete()
	     {
		      $this->layout="ajax";
	      
		      if($this->data['id']!=''){
			$idArray = explode("^", $this->data['id']);
                           foreach($idArray as $id)
			       {
	
				   
				   $cfb= "DELETE FROM `job_customer_feedbacks` WHERE `report_id` = {$id}";
                                   $cfbD=$this->JobCustomerFeedback->query($cfb);
				   
				   $gd= "DELETE FROM `gauge_datas` WHERE `report_id` = {$id}";
                                   $gdD=$this->GaugeData->query($gd);
				   
				   $gjd= "DELETE FROM `gyro_job_datas` WHERE `report_id` = {$id}";
                                   $gjdD=$this->GyroJobData->query($gjd);
				   
				   $deletejr= "DELETE FROM `job_remidials` WHERE `report_no` = {$id}";
                                   $deleteRD=$this->JobRemidial->query($deletejr);
				   
				   $deleteLN= "DELETE FROM `job_links` WHERE `report_id` = {$id}";
                                   $deleteLND=$this->JobLink->query($deleteLN);
				   
				   $deleteJW = "DELETE FROM `job_well_datas` WHERE `report_id` = {$id}";
                                   $deleteJWD=$this->JobWellData->query($deleteJW);
				   
				   $deleteAttach = "DELETE FROM `job_attachments` WHERE `report_id` = {$id}";
                                   $deleteAttach=$this->JobAttachment->query($deleteAttach);
                  
		  
		                   $deleteSq_remidials_email = "DELETE FROM `remidial_email_lists` WHERE `report_id` = {$id} AND `report_type`='job'";
                                    $dHRem=$this->RemidialEmailList->query($deleteSq_remidials_email);
		  
				
                                   $deleteMain = "DELETE FROM `job_report_mains` WHERE `id` = {$id}";
                                   $deleteval=$this->JobReportMain->query($deleteMain);
				   
                  
					  
					  
			       }
		
			       echo 'ok';
			        exit;
		      }else{
			 $this->redirect(array('action'=>report_job__list), null, true);
		      }
			 
			      
			      
			       
	}
	
        public function add_job_report_main($id=null)
	
	{        
	      
		 $this->_checkAdminSession();
		 $this->grid_access();
		 $this->_getRoleMenuPermission();
                 $this->layout="after_adminlogin_template";
	      	 $businessDetail = $this->BusinessType->find('all',array('conditions' => array('rtype' =>'all')));
		 $fieldlocationDetail = $this->Fieldlocation->find('all');
		 $clientDetail = $this->Client->find('all');
		 $countryDetail = $this->Country->find('all');
		 $userDetail = $this->AdminMaster->find('all');
		 $this->set('businessDetail',$businessDetail);
		 $this->set('fieldlocationDetail',$fieldlocationDetail);
		 $this->set('clientDetail',$clientDetail);
		 $this->set('country',$countryDetail);
		 $this->set('userDetail',$userDetail);
		if($id==null)
		{
          
		  $this->set('id','0');
		  $this->set('start_job','');
		  $this->set('end_job','');
		  $this->set('sjt','');
		  $this->set('ejt','');
		  $this->set('loss_time','00:00');
		  $this->set('rbt','00:00');
		  $this->set('return_depart','');
		  $this->set('depart_dt','00:00');
		  $this->set('heading','Add Job Report (Main)');
		  $this->set('button','Submit');
		  $this->set('report_no','');
		  $this->set('return_base',''); 
  		  $this->set('closer_date','00-00-0000');
		  $this->set('business_unit','');
		  $this->set('client','');
		  $this->set('field_location','');
		  $this->set('summary','');
		  $this->set('well','');
		  $this->set('rig','');
		  $this->set('well_site','');
		  $this->set('oprating_time','');
		  $this->set('hidden_oprating_value','');
                  $this->set('loss_time','');
		  $this->set('wellsiterep','');
		  $this->set('revenue','');
		  $this->set('field_ticket','');
		  $this->set('comments','');
		  $this->set('cnt',13);
		  $this->set('report_id',0);
		  $this->set('created_by',$_SESSION['adminData']['AdminMaster']['first_name']." ".$_SESSION['adminData']['AdminMaster']['last_name']);
		  $reportno=date('YmdHis');
		  $this->set('reportno',$reportno);
		  $this->set('report_date', date("d-M-y", mktime(0, 0, 0, date("m"),date("d"),date("Y"))));
		 }else if(base64_decode($id)!=null){
			
	 
		  $reportdetail = $this->JobReportMain->find('all', array('conditions' => array('JobReportMain.id' =>base64_decode($id))));
		  
		  $this->Session->write('report_create',$reportdetail[0]['JobReportMain']['created_by']);
		  if($reportdetail[0]['JobReportMain']['business_unit']==1){
			$this->Session->write('business_type',1);
		  }elseif($reportdetail[0]['JobReportMain']['business_unit']==2){
			$this->Session->write('business_type',2);
		  }
		  
		  
                  $adminDATA = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$reportdetail[0]['JobReportMain']['created_by'])));
                  $this->set('id',base64_decode($id));
		  if($reportdetail[0]['JobReportMain']['closer_date']!=''){
		  $crtd=explode("-",$reportdetail[0]['JobReportMain']['closer_date']);
		  $closedt=$crtd[1]."-".$crtd[2]."-".$crtd[0];
		  }else{
		  $closedt='';	
		  }
		  
		 if($reportdetail[0]['JobReportMain']['start_job']!=''){
			 $start_day_time=explode(" ",$reportdetail[0]['JobReportMain']['start_job']);
			 $sD=explode("-",$start_day_time[0]);
			 $start_date=$sD[1]."-".$sD[2]."-".$sD[0];
			 $sxpHT=explode(":",$start_day_time[1]); 
			 $sxET=$sxpHT[0].':'.$sxpHT[1];
		  
		  }else{
		         $start_date='';
			 $sxET='00:00';
		  }
		  if($reportdetail[0]['JobReportMain']['return_base']!=''){
			 $rb_day_time=explode(" ",$reportdetail[0]['JobReportMain']['return_base']);
			 $rbt=explode("-",$rb_day_time[0]);
			 $returnbase_date=$rbt[1]."-".$rbt[2]."-".$rbt[0];
			 $rxpHT=explode(":",$rb_day_time[1]); 
			 $rxET=$rxpHT[0].':'.$rxpHT[1];
		  
		  }else{
		         $returnbase_date='';
			 $rxET='00:00';
		  }
		  
		  if($reportdetail[0]['JobReportMain']['end_job']!=''){
			 $end_day_time=explode(" ",$reportdetail[0]['JobReportMain']['end_job']);
		         $eD=explode("-",$end_day_time[0]);
		         $end_date=$eD[1]."-".$eD[2]."-".$eD[0];
			 $expHT=explode(":",$end_day_time[1]); 
			 $exET=$expHT[0].':'.$expHT[1];
			 
		  
		  }else{
		        $end_date='';
			$exET='00:00';
		  }
		  if($reportdetail[0]['JobReportMain']['depart_base']!=''){
			 $depart_day_time=explode(" ",$reportdetail[0]['JobReportMain']['depart_base']);
		         $ddt=explode("-",$depart_day_time[0]);
		         $deprt_date=$ddt[1]."-".$ddt[2]."-".$ddt[0];
			 $depxpHT=explode(":",$depart_day_time[1]); 
			 $dexET=$depxpHT[0].':'.$depxpHT[1];
			 
		  
		  }else{
		         $deprt_date='';
			 $dexET='00:00';
		  }
		   
		
		  $rdate_time=explode("-",$reportdetail[0]['JobReportMain']['report_date']);
		  $this->set('report_date', date("d-M-y", mktime(0, 0, 0, $rdate_time[1],$rdate_time[2],$rdate_time[0])));

		  $this->set('report_id',$reportdetail[0]['JobReportMain']['report_no']);
		  $this->set('start_job',$start_date);
		  $this->set('end_job',$end_date);
		  $this->set('sjt',$sxET);
		  $this->set('ejt',$exET);
		  $this->set('return_depart',$deprt_date);
		  $this->set('depart_dt',$dexET);
		  $this->set('business_unit',$reportdetail[0]['JobReportMain']['business_unit']);
		  $this->set('return_base',$returnbase_date);
		  $this->set('rbt',$rxET);
		  $this->set('loss_time',$reportdetail[0]['JobReportMain']['loss_time']);
		  $this->set('hidden_oprating_value',$reportdetail[0]['JobReportMain']['operating_time']);
		  $this->set('oprating_time',$reportdetail[0]['JobReportMain']['operating_time']);
		  $this->set('hidden_total_value',$reportdetail[0]['JobReportMain']['total_time']);
		  $this->set('total_time',$reportdetail[0]['JobReportMain']['total_time']);
		  $this->set('heading','Update Job Report (Main)');
		  $this->set('button','Update');
		  $this->set('reportno',$reportdetail[0]['JobReportMain']['report_no']);
  		  $this->set('closer_date',$closedt);
	          $this->set('cnt',$reportdetail[0]['JobReportMain']['country']);
		  $this->set('well',$reportdetail[0]['JobReportMain']['well']);
		  $this->set('wellsiterep',$reportdetail[0]['JobReportMain']['wellsite_rep']);
		  $this->set('revenue',$reportdetail[0]['JobReportMain']['revenue']);
		  $this->set('rig',$reportdetail[0]['JobReportMain']['rig']);
		  $this->set('operating_time',$reportdetail[0]['JobReportMain']['operating_time']);
		  $this->set('hidden_oprating_value',$reportdetail[0]['JobReportMain']['operating_time']);
		  $this->set('client',$reportdetail[0]['JobReportMain']['client']);
	          $this->set('field_location',$reportdetail[0]['JobReportMain']['field_location']);
		  $this->set('field_ticket',$reportdetail[0]['JobReportMain']['field_ticket']);
		  $this->set('comments',$reportdetail[0]['JobReportMain']['comment']);
		  $this->set('created_by',$adminDATA[0]['AdminMaster']['first_name']." ".$adminDATA[0]['AdminMaster']['last_name']);
		 
		 
		 }
	}
	function jobprocess()
	 { 
	   $this->layout = "ajax";
	   $this->_checkAdminSession();
	   $reportData=array();
           if($this->data['add_job_main_form']['id']==0){
		   $res='add';
 	                
	   }else{
		   $res='update';
		   $reportData['JobReportMain']['id']=$this->data['add_job_main_form']['id'];
		  
	    }

	   if(trim($this->data['return_base'])!='00:00'){ 
	   $explode_retrun_base=explode(" ",$this->data['return_base']);
	   $explode_retrun_day=explode("-",$explode_retrun_base[0]);
	   $explode_retrun_days=$explode_retrun_day[2]."-".$explode_retrun_day[0]."-".$explode_retrun_day[1];
	   $retrun_base=$explode_retrun_days." ".$explode_retrun_base[1];
	   }else{
		$retrun_base='';
	   }
	   if(trim($this->data['strtjobval'])!='00:00'){ 
	   $explode_sov=explode(" ",$this->data['strtjobval']);
	   $explode_sov_day=explode("-", $explode_sov[0]);
	   $explode_retrun_days=$explode_sov_day[2]."-".$explode_sov_day[0]."-".$explode_sov_day[1];
	   $strtjobval=$explode_retrun_days." ".$explode_sov[1];
	   }else{
	   $strtjobval='';
	   }
	   if(trim($this->data['endjobval'])!='00:00'){ 
	   $explode_eov=explode(" ",$this->data['endjobval']);
	   $explode_eov_day=explode("-",$explode_eov[0]);
	   $explode_retrun_days= $explode_eov_day[2]."-". $explode_eov_day[0]."-".$explode_eov_day[1];
	   $endjobval=$explode_retrun_days." ".$explode_eov[1];
	   }else{
	   $endjobval='';
	   }
	   if(trim($this->data['return_depart_val'])!='00:00'){ 
	   $explode_dv=explode(" ",$this->data['return_depart_val']);
	   $explode_dv_day=explode("-",$explode_dv[0]);
	   $explode_retrun_deprts= $explode_dv_day[2]."-". $explode_dv_day[0]."-".$explode_dv_day[1];
	   $deprtbval=$explode_retrun_deprts." ".$explode_dv[1];
	   }else{
	   $deprtbval='';
	   }
	   $reportData['JobReportMain']['report_no']=$this->data['report_no']; 
	   $reportData['JobReportMain']['report_date'] =$this->data['rp_date'];  
	   $reportData['JobReportMain']['client']=$this->data['client'];
	   $reportData['JobReportMain']['field_location']=$this->data['field_location'];
	   $reportData['JobReportMain']['country']=$this->data['country'];
	   $reportData['JobReportMain']['loss_time']=$this->data['loss_time'];
	   $reportData['JobReportMain']['start_job']=$strtjobval;
	   $reportData['JobReportMain']['end_job']=$endjobval;
	   $reportData['JobReportMain']['business_unit']=$this->data['business_unit'];
	   $reportData['JobReportMain']['field_ticket']=$this->data['add_job_main_form']['field_ticket'];
	   $reportData['JobReportMain']['return_base']= $retrun_base;
	   $reportData['JobReportMain']['depart_base']= $deprtbval;
	   $reportData['JobReportMain']['created_by']=$_SESSION['adminData']['AdminMaster']['id'];
	   $reportData['JobReportMain']['well']=$this->data['add_job_main_form']['well'];
	   $reportData['JobReportMain']['wellsite_rep']=$this->data['add_job_main_form']['wellsiterep'];
	   $reportData['JobReportMain']['operating_time']=$this->data['operating_time'];
	   $reportData['JobReportMain']['total_time']=$this->data['total_time'];
	   $reportData['JobReportMain']['rig']=$this->data['add_job_main_form']['rig'];
	   $reportData['JobReportMain']['comment']=$this->data['add_job_main_form']['summary'];
	   $reportData['JobReportMain']['revenue']=$this->data['add_job_main_form']['revenue'];
	 
	   
	  if($this->JobReportMain->save($reportData)){
		
		 if($res=='add'){
			 $lastReport=base64_encode($this->JobReportMain->getLastInsertId());
			if($this->data['business_unit']==1){
			       $this->Session->write('business_type',1);
			}elseif($this->data['business_unit']==2){
			      $this->Session->write('business_type',2);
			}  
		 }elseif($res=='update'){
			 $lastReport=base64_encode($this->data['add_job_main_form']['id']);
		 }
                 
		  
		  
		echo $res."~".$lastReport;
	    }else{
		echo 'fail~0~0';
	    }
          
		
	   exit;
	}
	
	function dateprocess(){
		
		$this->layout = "ajax";
	        $this->_checkAdminSession();
		$this->layout="ajax";
		$startDT=explode(" ",$this->data['strtjobval']);
		$endDT=explode(" ",$this->data['endjobval']);
		$startD=explode("-",$startDT[0]);
		$startT=explode(":",$startDT[1]);
		$endD=explode("-",$endDT[0]);
		$endT=explode(":",$endDT[1]);
		
                $dateS =  mktime($startT[0],$startT[1], 0, $startD[0],$startD[1],$startD[2]);
		$dateE =  mktime($endT[0],$endT[1], 0, $endD[0],($endD[1]),$endD[2]); //mktime($endT[0],$endT[1], 0, $endD[0],($endD[1]+1),$endD[2]);
		if($dateE<=$dateS){
			echo 'wrong';
			
		}else{
		$interval =($dateE - $dateS)/(60*60);
		echo $interval. 'hrs';
		}
		exit;
		
	}
	function add_jobreport_view($id=null){
		
		 $this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
		 $this->grid_access();
                 $this->layout="after_adminlogin_template";
		 $reportdetail = $this->JobReportMain->find('all', array('conditions' => array('JobReportMain.id' =>base64_decode($id)),'recursive'=>2));

		 $jobwelldata = $this->JobWellData->find('all', array('conditions' => array('JobWellData.report_id' =>base64_decode($id))));
	 	
		 $this->Session->write('report_create',$reportdetail[0]['JobReportMain']['created_by']);
		 
		 if($reportdetail[0]['JobReportMain']['business_unit']==1){
			$this->Session->write('business_type',1);
		  }elseif($reportdetail[0]['JobReportMain']['business_unit']==2){
			$this->Session->write('business_type',2);
		  }
		 
		 
		  $rdate_time=explode("-",$reportdetail[0]['JobReportMain']['report_date']);
		  
		  $reportdetail[0]['JobReportMain']['report_date_val']=date("d-M-y", mktime(0, 0, 0, $rdate_time[1],$rdate_time[2],$rdate_time[0]));
	   
	         if($reportdetail[0]['JobReportMain']['start_job']!=''){ 
			$startjob=explode(" ",$reportdetail[0]['JobReportMain']['start_job']);
			$startjob_date=explode("-",$startjob[0]);
			$sjd=$startjob_date[2].'/'.$startjob_date[1].'/'.$startjob_date[0];
			$startjob_time=$startjob[1];
			$stjval=$sjd." ".$startjob_time;
			$reportdetail[0]['JobReportMain']['start_job_val']=$stjval;
		 }else{
			$reportdetail[0]['JobReportMain']['start_job_val']='';
		 }
		 
		 
		 
		  if($reportdetail[0]['JobReportMain']['end_job']!=''){ 
			$endjob=explode(" ",$reportdetail[0]['JobReportMain']['end_job']);
			$endjob_date=explode("-",$endjob[0]);
			$ejd=$endjob_date[2].'/'.$endjob_date[1].'/'.$endjob_date[0];
			$endjob_time=$endjob[1];
			$endjval=$ejd." ".$endjob_time;
			$reportdetail[0]['JobReportMain']['end_job_val']=$endjval;
		  }else{
			$reportdetail[0]['JobReportMain']['end_job_val']='';
		  }
		  
		  
		  
		  
		  if($reportdetail[0]['JobReportMain']['return_base']!=''){ 
			$returnBase=explode(" ",$reportdetail[0]['JobReportMain']['return_base']);
			$returnBase_date=explode("-",$returnBase[0]);
			$rbd=$returnBase_date[2].'/'.$returnBase_date[1].'/'.$returnBase_date[0];
			$returnBase_time=$returnBase[1];
			$rbval=$rbd." ".$returnBase_time;
		 $reportdetail[0]['JobReportMain']['return_base_val']= $rbval;
		 }else{
			$reportdetail[0]['JobReportMain']['return_base_val']='';
		  }
		 
		 
		 
		 
		if($reportdetail[0]['JobReportMain']['depart_base']!=''){ 
		 
			$departBase=explode(" ",$reportdetail[0]['JobReportMain']['depart_base']);
			$departBase_date=explode("-",$departBase[0]);
			$dbd=$departBase_date[2].'/'.$departBase_date[1].'/'.$departBase_date[0];
			$departBase_time=$departBase[1];
			$dbval=$dbd." ".$departBase_time;
			$reportdetail[0]['JobReportMain']['return_deprt_val']=$dbval;
		 }else{
			$reportdetail[0]['JobReportMain']['return_deprt_val']='';
		  }
		  
		  
		 
		 if(isset($reportdetail[0]['JobRemidial'][0])){
                        for($h=0;$h<count($reportdetail[0]['JobRemidial']);$h++){
			     

				$responsibility= $this->AdminMaster->find('all', array('conditions' =>array('AdminMaster.id'=>$reportdetail[0]['JobRemidial'][$h]['remidial_responsibility']))); 
				$reportdetail[0]['JobRemidial'][$h]['responsibility_person']=$responsibility[0]['AdminMaster']['first_name']." ".$responsibility[0]['AdminMaster']['last_name'];
				$reportdetail[0]['JobRemidial'][$h]['Priority']['prority_colorcode']='<font color='.$reportdetail[0]['JobRemidial'][$h]['Priority']['colorcoder'].'>'.$reportdetail[0]['JobRemidial'][$h]['Priority']['type'].'</font>';
                                $explode_remedial=explode("-",$reportdetail[0]['JobRemidial'][$h]['remidial_create']);     
                                $reportdetail[0]['JobRemidial'][$h]['createRemidial']=date("d-M-y", mktime(0, 0, 0, $explode_remedial[1], $explode_remedial[2], $explode_remedial[0]));
	 
				if($reportdetail[0]['JobRemidial'][$h]['remidial_closure_date']=='0000-00-00'){
					 $reportdetail[0]['JobRemidial'][$h]['closeDate']='';
					 $reportdetail[0]['JobRemidial'][$h]['remidial_closer_summary']='';
			       
				}elseif($reportdetail[0]['JobRemidial'][$h]['remidial_closure_date']!='0000-00-00'){
					$closerDate=explode("-",$reportdetail[0]['JobRemidial'][$h]['remidial_closure_date']);

					$reportdetail[0]['JobRemidial'][$h]['closeDate']=date("d-M-y", mktime(0, 0, 0, $closerDate[1], $closerDate[2], $closerDate[0])); 
			        }

				if($reportdetail[0]['JobRemidial'][$h]['remidial_closure_date']=='0000-00-00'){
						$reportdetail[0]['JobRemidial'][$h]['closeDate']='';
						$reportdetail[0]['JobRemidial'][$h]['remidial_closer_summary']='';
				      
				 }elseif($reportdetail[0]['JobRemidial'][$h]['remidial_closure_date']!='0000-00-00'){
					       $closerDate=explode("-",$reportdetail[0]['JobRemidial'][$h]['remidial_closure_date']);

					       $reportdetail[0]['JobRemidial'][$h]['closeDate']=date("d-M-y", mktime(0, 0, 0, $closerDate[1], $closerDate[2], $closerDate[0])); 
				 }

                               
                                
                               if($reportdetail[0]['JobRemidial'][$h]['remidial_reminder_data']!=''){
                                 $rrd=explode(" ",$reportdetail[0]['JobRemidial'][$h]['remidial_reminder_data']);
                       		 $exrrd=explode("/",$rrd[0]);
				 $reportdetail[0]['JobRemidial'][$h]['rem_rim_data']=date("d-M-y", mktime(0, 0, 0, $exrrd[1], $exrrd[0], $exrrd[2])); 
                                }else{
                                 $reportdetail[0]['JobRemidial'][$h]['rem_rim_data']='';
                                 }

                                if($reportdetail[0]['JobRemidial'][$h]['remidial_closure_target']!=''){
                                 $rcd=explode(" ",$reportdetail[0]['JobRemidial'][$h]['remidial_closure_target']);
				 $exrcd=explode("/",$rcd[0]);
				 $reportdetail[0]['JobRemidial'][$h]['rem_cls_trgt']=date("d-M-y", mktime(0, 0, 0, $exrcd[1], $exrcd[0], $exrcd[2])); 
                               }else{
                                  $reportdetail[0]['JobRemidial'][$h]['rem_cls_trgt']='';
                               }
		           }
                  }
		  
                   $jsl= $this->JobCustomerFeedbackElement->find('all');
		   $this->set('jsl',$jsl);
                   if(isset($reportdetail[0]['JobCustomerFeedback'][0]['name_value'])){
		   $explode_name_value=explode(",",$reportdetail[0]['JobCustomerFeedback'][0]['name_value']);
		   $explode_name=array();
		   $explode_value=array();
			for($k=0;$k<count($explode_name_value);$k++){
			       $expld=explode("~",$explode_name_value[$k]);
			       $explode_name[]=$expld[0];
			       $explode_value[]=$expld[1];
			       
			}
			
		   $this->set('explode_name',$explode_name);
		   $this->set('explode_value',$explode_value);
	           }else{
		   $this->set('explode_name',array());
		   $this->set('explode_value',array());	
		   }
		   $this->set('reportdetail', $reportdetail);
		   $this->set('jobwelldata',$jobwelldata);
		   
		   if(isset($reportdetail[0]['JobLink'][0])){
			      $linkHolder=array();
				$typeHolder=array();
				$linkDataHolder=array();
				
				for($i=0;$i<count($reportdetail[0]['JobLink']);$i++){
					$typeHolder[]=$reportdetail[0]['JobLink'][$i]['type'];
				}
			$uniqueType=array_values(array_unique($typeHolder));
			$madleVal=$this->JobLink;
			$linkDataHolder=$this->retrive_link_summary($madleVal,$uniqueType,'JobLink');
	            	if(count($linkDataHolder)>0){
					      $this->set('linkDataHolder',$linkDataHolder);
					 }else{
					      $this->set('linkDataHolder',array());	
					 }
			
			}else{
			    $this->set('linkDataHolder',array());
			}
		   
		   $jsl= $this->JobCustomerFeedbackElement->find('all');
		   $this->set('jsl',$jsl);
 
	       	}
	
		function print_view($id=null){
		
		 $this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
		 $this->grid_access();
                 $this->layout="ajax";
		 $reportdetail = $this->JobReportMain->find('all', array('conditions' => array('JobReportMain.id' =>base64_decode($id)),'recursive'=>2));
		 $jobwelldata = $this->JobWellData->find('all', array('conditions' => array('JobWellData.report_id' =>base64_decode($id))));
		 
		 if($reportdetail[0]['JobReportMain']['business_unit']==1){
			$this->Session->write('business_type',1);
		  }elseif($reportdetail[0]['JobReportMain']['business_unit']==2){
			$this->Session->write('business_type',2);
		  }
		 
		 
		  $rdate_time=explode("-",$reportdetail[0]['JobReportMain']['report_date']);
		  
		  $reportdetail[0]['JobReportMain']['report_date_val']=date("d-M-y", mktime(0, 0, 0, $rdate_time[1],$rdate_time[2],$rdate_time[0]));
	   
	         $startjob=explode(" ",$reportdetail[0]['JobReportMain']['start_job']);
	         $startjob_date=explode("-",$startjob[0]);
		 $sjd=$startjob_date[2].'/'.$startjob_date[1].'/'.$startjob_date[0];
	         $startjob_time=$startjob[1];
	         $stjval=$sjd." ".$startjob_time;
		 $reportdetail[0]['JobReportMain']['start_job_val']=$stjval;
		 
		 $endjob=explode(" ",$reportdetail[0]['JobReportMain']['end_job']);
	         $endjob_date=explode("-",$endjob[0]);
		 $ejd=$endjob_date[2].'/'.$endjob_date[1].'/'.$endjob_date[0];
	         $endjob_time=$endjob[1];
	         $endjval=$ejd." ".$endjob_time;
		 $reportdetail[0]['JobReportMain']['end_job_val']=$endjval;
		 
		 $returnBase=explode(" ",$reportdetail[0]['JobReportMain']['return_base']);
	         $returnBase_date=explode("-",$returnBase[0]);
		 $rbd=$returnBase_date[2].'/'.$returnBase_date[1].'/'.$returnBase_date[0];
	         $returnBase_time=$returnBase[1];
	         $rbval=$rbd." ".$returnBase_time;
		 $reportdetail[0]['JobReportMain']['return_base_val']= $rbval;
		 
		 
		 $departBase=explode(" ",$reportdetail[0]['JobReportMain']['depart_base']);
	         $departBase_date=explode("-",$departBase[0]);
		 $dbd=$departBase_date[2].'/'.$departBase_date[1].'/'.$departBase_date[0];
	         $departBase_time=$returnBase[1];
	         $dbval=$dbd." ".$departBase_time;
		 $reportdetail[0]['JobReportMain']['return_deprt_val']=$dbval;
		 
		 if(isset($reportdetail[0]['JobRemidial'][0])){
                        for($h=0;$h<count($reportdetail[0]['JobRemidial']);$h++){
			     

				$responsibility= $this->AdminMaster->find('all', array('conditions' =>array('AdminMaster.id'=>$reportdetail[0]['JobRemidial'][$h]['remidial_responsibility']))); 
				$reportdetail[0]['JobRemidial'][$h]['responsibility_person']=$responsibility[0]['AdminMaster']['first_name']." ".$responsibility[0]['AdminMaster']['last_name'];
				$reportdetail[0]['JobRemidial'][$h]['Priority']['prority_colorcode']='<font color='.$reportdetail[0]['JobRemidial'][$h]['Priority']['colorcoder'].'>'.$reportdetail[0]['JobRemidial'][$h]['Priority']['type'].'</font>';
                                $explode_remedial=explode("-",$reportdetail[0]['JobRemidial'][$h]['remidial_create']);     
                                $reportdetail[0]['JobRemidial'][$h]['createRemidial']=date("d-M-y", mktime(0, 0, 0, $explode_remedial[1], $explode_remedial[2], $explode_remedial[0]));
	 
				if($reportdetail[0]['JobRemidial'][$h]['remidial_closure_date']=='0000-00-00'){
					 $reportdetail[0]['JobRemidial'][$h]['closeDate']='';
					 $reportdetail[0]['JobRemidial'][$h]['remidial_closer_summary']='';
			       
				}elseif($reportdetail[0]['JobRemidial'][$h]['remidial_closure_date']!='0000-00-00'){
					$closerDate=explode("-",$reportdetail[0]['JobRemidial'][$h]['remidial_closure_date']);

					$reportdetail[0]['JobRemidial'][$h]['closeDate']=date("d-M-y", mktime(0, 0, 0, $closerDate[1], $closerDate[2], $closerDate[0])); 
			        }

				if($reportdetail[0]['JobRemidial'][$h]['remidial_closure_date']=='0000-00-00'){
						$reportdetail[0]['JobRemidial'][$h]['closeDate']='';
						$reportdetail[0]['JobRemidial'][$h]['remidial_closer_summary']='';
				      
				 }elseif($reportdetail[0]['JobRemidial'][$h]['remidial_closure_date']!='0000-00-00'){
					       $closerDate=explode("-",$reportdetail[0]['JobRemidial'][$h]['remidial_closure_date']);

					       $reportdetail[0]['JobRemidial'][$h]['closeDate']=date("d-M-y", mktime(0, 0, 0, $closerDate[1], $closerDate[2], $closerDate[0])); 
				 }

                               
                                
                               if($reportdetail[0]['JobRemidial'][$h]['remidial_reminder_data']!=''){
                                 $rrd=explode(" ",$reportdetail[0]['JobRemidial'][$h]['remidial_reminder_data']);
                       		 $exrrd=explode("/",$rrd[0]);
				 $reportdetail[0]['JobRemidial'][$h]['rem_rim_data']=date("d-M-y", mktime(0, 0, 0, $exrrd[1], $exrrd[0], $exrrd[2])); 
                                }else{
                                 $reportdetail[0]['JobRemidial'][$h]['rem_rim_data']='';
                                 }

                                if($reportdetail[0]['JobRemidial'][$h]['remidial_closure_target']!=''){
                                 $rcd=explode(" ",$reportdetail[0]['JobRemidial'][$h]['remidial_closure_target']);
				 $exrcd=explode("/",$rcd[0]);
				 $reportdetail[0]['JobRemidial'][$h]['rem_cls_trgt']=date("d-M-y", mktime(0, 0, 0, $exrcd[1], $exrcd[0], $exrcd[2])); 
                               }else{
                                  $reportdetail[0]['JobRemidial'][$h]['rem_cls_trgt']='';
                               }
		           }
                  }
		 $jsl= $this->JobCustomerFeedbackElement->find('all');
		 $this->set('jsl',$jsl); 
		 if(isset($reportdetail[0]['JobCustomerFeedback'][0]['name_value'])){
		   $explode_name_value=explode(",",$reportdetail[0]['JobCustomerFeedback'][0]['name_value']);
		   $explode_name=array();
		   $explode_value=array();
			for($k=0;$k<count($explode_name_value);$k++){
			       $expld=explode("~",$explode_name_value[$k]);
			       $explode_name[]=$expld[0];
			       $explode_value[]=$expld[1];
			       
			}
			
		   $this->set('explode_name',$explode_name);
		   $this->set('explode_value',$explode_value);
	           }else{
		   $this->set('explode_name',array());
		   $this->set('explode_value',array());	
		   }
 
		   $this->set('reportdetail', $reportdetail);
		   $this->set('jobwelldata',$jobwelldata);
		   
		    if(isset($reportdetail[0]['JobLink'][0])){
			      $linkHolder=array();
				$typeHolder=array();
				$linkDataHolder=array();
				
				for($i=0;$i<count($reportdetail[0]['JobLink']);$i++){
					$typeHolder[]=$reportdetail[0]['JobLink'][$i]['type'];
				}
			$uniqueType=array_values(array_unique($typeHolder));
			$madleVal=$this->JobLink;
			$linkDataHolder=$this->retrive_link_summary($madleVal,$uniqueType,'JobLink');
	            	if(count($linkDataHolder)>0){
					      $this->set('linkDataHolder',$linkDataHolder);
					 }else{
					      $this->set('linkDataHolder',array());	
					 }
			
			}else{
			    $this->set('linkDataHolder',array());
			}
		   
		 
		 
	}
	
	
	
	
	
	 public function welldata($id=null){
		 $this->_checkAdminSession();
         	 $this->_getRoleMenuPermission();
		 $this->grid_access();
                 $this->layout="after_adminlogin_template";
		 $this->set('id','0');
		 $reportdetail = $this->JobReportMain->find('all', array('conditions' => array('JobReportMain.id' =>base64_decode($id)),'recursive'=>2));
		 $welldataList = $this->Welldata->find('all');
		 $wellstatus = $this->WellStatus->find('all');
	         $this->set('report_number',$reportdetail[0]['JobReportMain']['report_no']);
		 $this->set('report_id',base64_decode($id));
		 $this->set('welldataList',$welldataList);
                 $this->set('wellstatus',$wellstatus);
	         $jobwelldatas = $this->JobWellData->find('all', array('conditions' => array('JobWellData.report_id' =>base64_decode($id))));

	         //$reportdetail = $this->JobReportMain->find('all', array('conditions' => array('JobReportMain.id' =>base64_decode($id))));

         	   if(count($jobwelldatas)>0){
 
			    
			    $this->set('heading','Update Well Data');
		            $this->set('button','Update');
			    $this->set('id',0);
		            $this->set('report_id',base64_decode($id));
		            //$this->set('rigname',$jobwelldatas[0]['JobWellData']['rig_name']);
		            $this->set('rig',$jobwelldatas[0]['JobWellData']['rig']);
			    //$this->set('well_name',$jobwelldatas[0]['JobWellData']['well_name']);
			    $this->set('well',$jobwelldatas[0]['JobWellData']['well']);
			    $this->set('fluid',$jobwelldatas[0]['JobWellData']['fluid_name']);
			    $this->set('depth',$jobwelldatas[0]['JobWellData']['depth']);
			    $this->set('devi',$jobwelldatas[0]['JobWellData']['devi']);
			    $this->set('shtemp',$jobwelldatas[0]['JobWellData']['shtemp']);
			    $this->set('bhtemp',$jobwelldatas[0]['JobWellData']['bhtemp']);
			    $this->set('density',$jobwelldatas[0]['JobWellData']['density']);
			    $this->set('whpres',$jobwelldatas[0]['JobWellData']['whpres']);
			    $this->set('welldata',$jobwelldatas[0]['JobWellData']['well_name']);
			    $this->set('bhpres',$jobwelldatas[0]['JobWellData']['bhpres']);
			    $this->set('wellstatusval',$jobwelldatas[0]['JobWellData']['staus_name']);
			    $this->set('hts',$jobwelldatas[0]['JobWellData']['hts']);
			    $this->set('cot',$jobwelldatas[0]['JobWellData']['cot']);
			
                          
	                }else{
			    $this->set('heading','Add Well Data');
		            $this->set('button','Submit');
			    $this->set('id',0);
		            $this->set('rigname','');
		            $this->set('rig','');
			    $this->set('well_name','');
			    $this->set('well','');
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
	
	                $JobWelldata=array();
	         	$jobwelldatas = $this->JobWellData->find('all', array('conditions' => array('JobWellData.report_id' =>$this->data['report_id'])));
				if(count($jobwelldatas )>0){
				       $res='update';
				        $JobWelldata['JobWellData']['id']=$jobwelldatas [0]['JobWellData']['id'];
				}else{
				       $res='add';
				}

					//echo '<pre>'; var_dump($this->data); die();

					//$JobWelldata['JobWellData']['well_name']=$this->data['well_name'];
					$JobWelldata['JobWellData']['well']=$this->data['well'];
					$JobWelldata['JobWellData']['report_id']=$this->data['report_id']; 
					//$JobWelldata['JobWellData']['rig_name'] =$this->data['rig_name'];
					$JobWelldata['JobWellData']['rig'] =$this->data['rig'];  
					$JobWelldata['JobWellData']['fluid_name']=$this->data['fluid_name'];
					$JobWelldata['JobWellData']['depth']=$this->data['depth'];
					$JobWelldata['JobWellData']['devi']=$this->data['devi'];
					$JobWelldata['JobWellData']['bhtemp']=$this->data['bhtemp'];
					$JobWelldata['JobWellData']['shtemp']=$this->data['shtemp'];
					$JobWelldata['JobWellData']['density']=$this->data['density']; 
					$JobWelldata['JobWellData']['whpres'] =$this->data['whpres'];  
					$JobWelldata['JobWellData']['bhpres']=$this->data['bhpres'];
					$JobWelldata['JobWellData']['staus_name']=$this->data['staus_name'];
					$JobWelldata['JobWellData']['hts']=$this->data['hts'];
					$JobWelldata['JobWellData']['cot']=$this->data['cot'];
				if($this->JobWellData->save($JobWelldata)){
				     echo $res;
				 }else{
				     echo 'fail';
				 }
				 
                       
	          exit;
	        }
		
	    function add_job_remidial($report_id=null,$remidial_id=null){
		 $this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
		 $this->grid_access();
		 $this->layout="after_adminlogin_template";
		 $priority = $this->Priority->find('all');
		 $userDetail = $this->AdminMaster->find('all');
		 $this->set('priority',$priority);
		 $this->set('responsibility',$userDetail);
		 $this->set('created_by',$_SESSION['adminData']['AdminMaster']['first_name']." ".$_SESSION['adminData']['AdminMaster']['last_name']);
		 $reportdetail = $this->JobReportMain->find('all', array('conditions' => array('JobReportMain.id' =>base64_decode($report_id))));
		 $countRem= $this->JobRemidial->find('count',array('conditions'=>array('JobRemidial.report_no'=>base64_decode($report_id))));      
      		 $this->set('report_number',$reportdetail[0]['JobReportMain']['report_no']);      
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
			
		         $remidialData= $this->JobRemidial->find('all',array('conditions'=>array('JobRemidial.id'=>base64_decode($remidial_id))));
			 $this->set('countRem',  $remidialData[0]['JobRemidial']['remedial_no']);
			 $this->set('id', base64_decode($remidial_id));
			 $rempriority = $this->Priority->find('all',array('conditions'=>array('id'=>base64_decode($remidial_id))));
			 $this->set('heading', 'Edit Remedial Action Item' );
			 $this->set('button', 'Update');
			 $remidialcreate=explode("-",$remidialData[0]['JobRemidial']['remidial_create']);
			 $this->set('remidial_create',$remidialcreate[1].'-'.$remidialcreate[2].'-'.$remidialcreate[0]);
			 $this->set('remidial_summery',$remidialData[0]['JobRemidial']['remidial_summery']);
			 
			 $this->set('remidial_action',$remidialData[0]['JobRemidial']['remidial_action']);
			 $this->set('remidial_priority',$remidialData[0]['JobRemidial']['remidial_priority']);
			 $this->set('remidial_closure_target',$remidialData[0]['JobRemidial']['remidial_closure_target']);
			 $this->set('remidial_responsibility',$remidialData[0]['JobRemidial']['remidial_responsibility']);
			if($remidialData[0]['JobRemidial']['remidial_closure_date']!='0000-00-00'){
			 $closerdate=explode("-",$remidialData[0]['JobRemidial']['remidial_closure_date']);
			 $this->set('remidial_closure_date',$closerdate[1].'/'.$closerdate[2].'/'.$closerdate[0]);
			 $this->set('remidial_closer_summary',$remidialData[0]['JobRemidial']['remidial_closer_summary']);
			 $this->set('remidial_button_style','style="display:none"');
			}else{
			  $this->set('remidial_closer_summary',' ');
			  $this->set('remidial_closure_date','');
			  $this->set('remidial_button_style','style="display:block"');
			}
			 
			 $this->set('remidial_style','style="display:block"');
			 $this->set('remidial_reminder_data',$remidialData[0]['JobRemidial']['remidial_reminder_data']);
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
				       $remidialArray['JobRemidial']['id']=$this->data['add_report_remidial_form']['id'];
				}else{
					$res='add';
				 }
			   $remidialdate=explode("-",$this->data['remidial_create']);	       
		   	   $remidialArray['JobRemidial']['remidial_create']=$remidialdate[2].'-'.$remidialdate[0].'-'.$remidialdate[1];
			   $remidialArray['JobRemidial']['remidial_createby']=$_SESSION['adminData']['AdminMaster']['id']; 
		           $remidialArray['JobRemidial']['report_no']=$this->data['report_no'];
			   $remidialArray['JobRemidial']['remedial_no']=$this->data['countRem'];
			   $prority=explode("~",$this->data['remidial_priority']);
			   $remidialArray['JobRemidial']['remidial_priority']=$prority[0];
			   $remidialArray['JobRemidial']['remidial_responsibility']=$this->data['responsibility'];
			   $remidialArray['JobRemidial']['remidial_summery']=$this->data['add_report_remidial_form']['remidial_summery'];
			   $remidialArray['JobRemidial']['remidial_closer_summary']=$this->data['add_report_remidial_form']['remidial_closer_summary'];
			   $remidialArray['JobRemidial']['remidial_action']=$this->data['add_report_remidial_form']['remidial_action'];
			   $remidialArray['JobRemidial']['remidial_reminder_data']=$this->data['add_report_remidial_form']['remidial_reminder_data'];
			   $remidialArray['JobRemidial']['remidial_closure_target']=$this->data['add_report_remidial_form']['remidial_closure_target'];
			   if(isset($this->data['remidial_closure_date']) && $this->data['remidial_closure_date']!=''){
			     $closerdate=explode("-",$this->data['remidial_closure_date']);
			     $remidialArray['JobRemidial']['remidial_closure_date']=$closerdate[0].'-'.$closerdate[1].'-'.$closerdate[2];
			     }else{
			     $remidialArray['JobRemidial']['remidial_closure_date']=' ';
			     }
			     
			     
			     $createON = $remidialArray['JobRemidial']['remidial_create'].' 00:00:00';
		             $explodeCTR=explode(" ",$remidialArray['JobRemidial']['remidial_closure_target']);
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
				
				
				
				   $deleteREL = "DELETE FROM `remidial_email_lists` WHERE  `remedial_no` = {$remidialArray['JobRemidial']['remedial_no']} AND `report_id` = {$remidialArray['JobRemidial']['report_no']} AND `report_type`='job'";
                                   $deleteval=$this->RemidialEmailList->query($deleteREL);
				 if($this->data['remidial_closure_date']==''){  
					for($d=0;$d<count($dateHolder);$d++){
					     
							$remidialEmailList=array();
							$userdetail = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$this->data['responsibility'])));
							$fullname=$userdetail[0]['AdminMaster']['first_name'].' '.$userdetail[0]['AdminMaster']['last_name'];
							$to=$userdetail[0]['AdminMaster']['admin_email'];
							$remidialEmailList['RemidialEmailList']['report_id']=$remidialArray['JobRemidial']['report_no'];
							$remidialEmailList['RemidialEmailList']['remedial_no']=$remidialArray['JobRemidial']['remedial_no'];
							$remidialEmailList['RemidialEmailList']['report_type']='job';
							$remidialEmailList['RemidialEmailList']['email']=$to;
							$remidialEmailList['RemidialEmailList']['status']='N';
							$remidialEmailList['RemidialEmailList']['email_date']=$dateHolder[$d];
							$remidialEmailList['RemidialEmailList']['send_to']=$userdetail[0]['AdminMaster']['id'];
							
							$this->RemidialEmailList->create();
							$this->RemidialEmailList->save($remidialEmailList);
						
					}   
				 }
			     
			  if($this->JobRemidial->save($remidialArray)){
				     echo $res.'~job';
		                }else{
				     echo 'fail';
			        }
			        
               

			exit;
			
		}
		
		
		function report_job_remidial_list($id=null){
			$this->_checkAdminSession();
			$this->_getRoleMenuPermission();
			$this->grid_access();
			$this->layout="after_adminlogin_template";
			$this->set('report_no',base64_decode($id));
			$reportdetail = $this->JobReportMain->find('all', array('conditions' => array('JobReportMain.id' =>base64_decode($id))));
			$this->set('id',base64_decode($id));
			

			
			$this->set('report_val',$id);
			$this->set('report_number',$reportdetail[0]['JobReportMain']['report_no']);
			if(!empty($this->request->data))
			{
				$action = $this->request->data['JobRemidial']['action'];
				$this->set('action',$action);
				$limit = $this->request->data['JobRemidial']['limit'];
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

			
			$condition="JobRemidial.report_no = $report_id AND JobRemidial.isdeleted = 'N'";
			
			if(isset($_REQUEST['filter'])){
				switch($this->data['filter']){
				case'create_on':
				    $explodemonth=explode('/',$this->data['value']);
				    $day=$explodemonth[0];
				    $month=date('m', strtotime($explodemonth[1]));
				    $year="20$explodemonth[2]";
				    $createon=$year."-".$month."-".$day;
				    $condition .= "AND JobRemidial.remidial_create ='".$createon."'";	
				break;
				case'remidial_create_name':
				     $spliNAME=explode(" ",$_REQUEST['value']);
				     $spliLname=$spliNAME[count($spliNAME)-1];
				     $spliFname=$spliNAME[0];
				     $adminCondition="AdminMaster.first_name like '%".$spliFname."%' AND AdminMaster.last_name like '%".$spliLname."%'";
				     $userDetail = $this->AdminMaster->find('all',array('conditions'=>$adminCondition));
				     $addimid=$userDetail[0]['AdminMaster']['id'];
				     $condition .= "AND JobRemidial.remidial_createby ='".$addimid."'";
					
				break;
				case'responsibility_person':
				     $spliNAME=explode(" ",$_REQUEST['value']);
				     $spliLname=$spliNAME[count($spliNAME)-1];
				     $spliFname=$spliNAME[0];
				     $adminCondition="AdminMaster.first_name like '%".$spliFname."%' AND AdminMaster.last_name like '%".$spliLname."%'";
				     $userDetail = $this->AdminMaster->find('all',array('conditions'=>$adminCondition));
				     $addimid=$userDetail[0]['AdminMaster']['id'];
				     $condition .= "AND JobRemidial.remidial_responsibility ='".$addimid."'";	
				break;
				case'remidial_priority_name':
				     $priorityCondition = "Priority.type='".trim($_REQUEST['value'])."'";
				     $priorityDetail = $this->Priority->find('all',array('conditions'=>$priorityCondition));
				     $condition .= "AND JobRemidial.remidial_priority ='".$priorityDetail[0]['Priority']['id']."'";	
				break;
				}
			}
			
			
			$limit=null;
			if($_REQUEST['limit'] == 'all'){
						
				
			}else{
				$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
			}
	
			 $adminArray = array();	
			 $count = $this->JobRemidial->find('count' ,array('conditions' => $condition));
			 $adminA = $this->JobRemidial->find('all',array('conditions' => $condition,'order' => 'JobRemidial.id DESC','limit'=>$limit));

			$i = 0;
			foreach($adminA as $rec)
			{			
				if($rec['JobRemidial']['isblocked'] == 'N')
				{
					$adminA[$i]['JobRemidial']['blockHideIndex'] = "true";
					$adminA[$i]['JobRemidial']['unblockHideIndex'] = "false";
					$adminA[$i]['JobRemidial']['isdeletdHideIndex'] = "true";
				}else{
					$adminA[$i]['JobRemidial']['blockHideIndex'] = "false";
					$adminA[$i]['JobRemidial']['unblockHideIndex'] = "true";
					$adminA[$i]['JobRemidial']['isdeletdHideIndex'] = "false";
				}
				
			    $create_on=explode("-",$rec['JobRemidial']['remidial_create']);
			    $adminA[$i]['JobRemidial']['create_on']=date("d/M/y", mktime(0, 0, 0, $create_on[1], $create_on[2], $create_on[0]));
			    $lastupdated=explode(" ", $rec['JobRemidial']['modified']);
			    $lastupdatedate=explode("-",$lastupdated[0]);
			    $adminA[$i]['JobRemidial']['lastupdate']=date("d/M/y", mktime(0, 0, 0, $lastupdatedate[1], $lastupdatedate[2], $lastupdatedate[0]));
			    $createdate=explode("-",$rec['JobRemidial']['remidial_create']);
			    $adminA[$i]['JobRemidial']['createRemidial']=date("d/M/y", mktime(0, 0, 0, $createdate[1], $createdate[2], $createdate[0]));
			    $adminA[$i]['JobRemidial']['remidial_priority_name'] ='<font color='.$rec['Priority']['colorcoder'].'>'.$rec['Priority']['type'].'</font>';
			    $adminA[$i]['JobRemidial']['remidial_create_name'] = $rec['AdminMaster']['first_name']." ".$rec['AdminMaster']['last_name'];
			    $user_detail_reponsibilty= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$rec['JobRemidial']['remidial_responsibility'])));
			    $adminA[$i]['JobRemidial']['responsibility_person']=$user_detail_reponsibilty[0]['AdminMaster']['first_name']."  ".$user_detail_reponsibilty[0]['AdminMaster']['last_name'];
			    if($rec['JobRemidial']['remidial_closure_date']!='0000-00-00'){
			       $rem_cls_date=explode("-",$rec['JobRemidial']['remidial_closure_date']);
			       $adminA[$i]['JobRemidial']['closure']=date("d-M-y", mktime(0, 0, 0, $rem_cls_date[1], $rem_cls_date[2], $rem_cls_date[0]));
			    }else{
			        $adminA[$i]['JobRemidial']['closure']='';	
			    }
			    $i++;
			}
			if($count==0){
			   $adminArray=array();
		        }else{
			  $adminArray = Set::extract($adminA, '{n}.JobRemidial');
		        }
			 
			  $this->set('total', $count); 
			  $this->set('admins', $adminArray); 
			 
		}
		 
	    function remidial_block($id = null)
	     {

	     
	                if(!$id)
			{
		           $this->redirect(array('action'=>report_job_list), null, true);
			}
		       else
		       
		       
		       {
			  
	                 	$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['JobRemidial']['id'] = $id;
					  $this->request->data['JobRemidial']['isblocked'] = 'Y';
					  $this->JobRemidial->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	     
	      }
	      
	      function remidial_unblock($id = null)
	     {
                  
			if(!$id)
			{
		           $this->redirect(array('action'=>report_job_list), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['JobRemidial']['id'] = $id;
					  $this->request->data['JobRemidial']['isblocked'] = 'N';
					  $this->JobRemidial->save($this->request->data,false);				
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
				
				 $remidialData= $this->JobRemidial->find('all',array('conditions'=>array('JobRemidial.id'=>$id)));
				 $deleteHsse_remidials_email = "DELETE FROM `remidial_email_lists` WHERE `report_id` = {$remidialData[0]['JobRemidial']['report_no']}  AND  `remedial_no` = {$remidialData[0]['JobRemidial']['remedial_no']} AND `report_type`='job'";
                                 $dHRem=$this->RemidialEmailList->query($deleteHsse_remidials_email);
				 $deleteHsse_remidials = "DELETE FROM `job_remidials` WHERE `id` = {$id}";
                                 $dHR=$this->JobRemidial->query($deleteHsse_remidials);
					  
					  
			       }
			       echo 'ok';
			      
			   
			       exit;			 
		     
	      }
	function report_gyro_job_list($id){
		$this->_checkAdminSession();
	        $this->_getRoleMenuPermission();
		$this->grid_access();
		//$this->report_hsse_link();
		
		$this->layout="after_adminlogin_template";
		$reportdetail = $this->JobReportMain->find('all', array('conditions' => array('JobReportMain.id' =>base64_decode($id))));
		$this->set('id',base64_decode($id));
		$this->set('report_val',$id);
		$this->set('report_number',$reportdetail[0]['JobReportMain']['report_no']);
	
		if(!empty($this->request->data))
		{
			$action = $this->request->data['GyroJobData']['action'];
			$this->set('action',$action);
			$limit = $this->request->data['GyroJobData']['limit'];
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
	function get_all_gyro_job(){
		
			$this->_checkAdminSession();
			$this->_getRoleMenuPermission();
			$this->grid_access();
			$this->layout="after_adminlogin_template";
			$this->set('report_no',base64_decode($id));
			$reportdetail = $this->JobReportMain->find('all', array('conditions' => array('JobReportMain.id' =>base64_decode($id))));
			$this->set('id',base64_decode($id));
			

			
			$this->set('report_val',$id);
			$this->set('report_number',$reportdetail[0]['JobReportMain']['report_no']);
			if(!empty($this->request->data))
			{
				$action = $this->request->data['GyroJobData']['action'];
				$this->set('action',$action);
				$limit = $this->request->data['GyroJobData']['limit'];
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
	
	
		public function get_all_gyro_list($report_id)
		{
			Configure::write('debug', '2');  //set debug to 0 for this function because debugging info breaks the XMLHttpRequest
			$this->layout = "ajax"; //this tells the controller to use the Ajax layout instead of the default layout (since we're using ajax . . .)
			//pr($_REQUEST);
			$this->_checkAdminSession();
			$condition="";

			
			$condition="GyroJobData.report_id = $report_id AND GyroJobData.isdeleted = 'N'";
			
                        if(isset($_REQUEST['filter'])){
				switch($_REQUEST['filter']){
				case'gyro_sn_val':
	                            $gsv = $this->GyroSn->find('all',array('conditions'=>array('type'=>$_REQUEST['value'])));
				    $condition .= "AND GyroJobData.gyro_sn_val ='".$gsv[0]['GyroSn']['id']."'";	
				break;
				case'conveyance_value':
				     $cnv = $this->Conveyance->find('all',array('conditions'=>array('type'=>$_REQUEST['value'])));
				     $condition .= "AND GyroJobData.Conveyance ='".$cnv[0]['Conveyance']['id']."'";	
					
				break;
				case'conveyance_by_value':
				     $ccvb = $this->Conveyed->find('all',array('conditions'=>array('type'=>$_REQUEST['value'])));
				     $condition .= "AND GyroJobData.conveyance_by ='".$ccvb[0]['Conveyed']['id']."'";	
				     
				break;
				case'conveyance_type_value':
				     $ccvt = $this->ConveyanceType->find('all',array('conditions'=>array('type'=>$_REQUEST['value'])));
				     $condition .= "AND GyroJobData.conveyance_type ='".$ccvt[0]['ConveyanceType']['id']."'";		
				   
				break;
				}
			}
			
			
			$limit=null;
			if($_REQUEST['limit'] == 'all'){
						
				
			}else{
				$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
			}
	
			 $adminArray = array();	
			 $count = $this->GyroJobData->find('count' ,array('conditions' => $condition));
			 $adminA = $this->GyroJobData->find('all',array('conditions' => $condition,'order' => 'GyroJobData.id DESC','limit'=>$limit));

			$i = 0;
			foreach($adminA as $rec)
			{
				
				if($rec['GyroJobData']['isblocked'] == 'N')
				{
					$adminA[$i]['GyroJobData']['blockHideIndex'] = "true";
					$adminA[$i]['GyroJobData']['unblockHideIndex'] = "false";
					$adminA[$i]['GyroJobData']['isdeletdHideIndex'] = "true";
				}else{
					$adminA[$i]['GyroJobData']['blockHideIndex'] = "false";
					$adminA[$i]['GyroJobData']['unblockHideIndex'] = "true";
					$adminA[$i]['GyroJobData']['isdeletdHideIndex'] = "false";
				}
				
			    $adminA[$i]['GyroJobData']['gyro_sn_val']=$rec['GyroSn']['type'];
			    $adminA[$i]['GyroJobData']['conveyance_value']=$rec['Conveyance']['type'];
			    $adminA[$i]['GyroJobData']['conveyance_by_value']=$rec['Conveyed']['type'];
			    $adminA[$i]['GyroJobData']['conveyance_type_value']=$rec['ConveyanceType']['type'];
			    $i++;
			}
	
			if($count==0){
			   $adminArray=array();
		        }else{
			  $adminArray = Set::extract($adminA, '{n}.GyroJobData');
		        }
			 
			  $this->set('total', $count); 
			  $this->set('admins', $adminArray); 
		
	}
	function add_gyro_job_data($id=null,$gyroID=null){
		$this->_checkAdminSession();
	        $this->_getRoleMenuPermission();
		$this->grid_access();
		$this->layout="after_adminlogin_template";
		$gyrosnDetail = $this->GyroSn->find('all');
		$this->set('gyrosnDetail',$gyrosnDetail);
		$conveyance = $this->Conveyance->find('all');
		$this->set('conveyance',$conveyance);
		$conveyed = $this->Conveyed->find('all');
		$this->set('conveyed',$conveyed);
		$conveyanceType= $this->ConveyanceType->find('all');
		$this->set('conveyanceType',$conveyanceType);
		$reportdetail = $this->JobReportMain->find('all', array('conditions' => array('JobReportMain.id' =>base64_decode($id))));
		$this->set('reportno',base64_decode($id));
		$this->set('report_val',$id);
		$this->set('report_number',$reportdetail[0]['JobReportMain']['report_no']);
		 $countGuyro= $this->GyroJobData->find('count',array('conditions'=>array('GyroJobData.report_id'=>base64_decode($id))));  
                if($gyroID==''){
			if($countGuyro!=0){
		                $countGuyro=$countGuyro+1;	
			 }else{
				$countGuyro=1;		
			}
			 $this->set('countGyro',$countGuyro);
		  	 $this->set('heading', 'Add Gyro Job Data' );
			 $this->set('button','Submit' );
		         $this->set('id',0);
			 $this->set('gsn','');
			 $this->set('cid','');
		         $this->set('cby','');
			 $this->set('ct','');
			 $this->set('top_survey','');
			 $this->set('buttom_survey','');
		         $this->set('latitude','');
			 $this->set('longitude','');
			 $this->set('comments','');
		   
		   
		}else{
		
			$this->set('heading', 'Edit Gyro Job Data' );
			$this->set('button','Update' );
			$gyroJobData= $this->GyroJobData->find('all',array('conditions'=>array('GyroJobData.id'=>base64_decode($gyroID))));
			 $this->set('countGyro',$gyroJobData[0]['GyroJobData']['gyro_no']);
			 $this->set('id',$gyroJobData[0]['GyroJobData']['id']);
			 $this->set('gsn',$gyroJobData[0]['GyroJobData']['gyro_sn']);
			 $this->set('cid',$gyroJobData[0]['GyroJobData']['conveyance']);
		         $this->set('cby',$gyroJobData[0]['GyroJobData']['conveyance_by']);
			 $this->set('ct',$gyroJobData[0]['GyroJobData']['conveyance_type']);
			 $this->set('top_survey',$gyroJobData[0]['GyroJobData']['top_survey']);
			 $this->set('buttom_survey',$gyroJobData[0]['GyroJobData']['buttom_survey']);
		         $this->set('latitude',$gyroJobData[0]['GyroJobData']['latitude']);
			 $this->set('longitude',$gyroJobData[0]['GyroJobData']['longitude']);
			 $this->set('comments',$gyroJobData[0]['GyroJobData']['comments']);
		   
		}
		
	}
	function gyroprocess(){
		$this->_checkAdminSession();
		$this->layout="ajax";
		
		$gyrArray=array();
			 
		if($this->data['add_gyrojob_form']['id']!=0){
		       $res='update';
		       $gyrArray['GyroJobData']['id']=$this->data['add_gyrojob_form']['id'];
		}else{
			$res='add';
		 }

		$gyrArray['GyroJobData']['gyro_no']=$this->data['gyro_no'];  
		$gyrArray['GyroJobData']['report_id']=$this->data['report_no']; 
                $gyrArray['GyroJobData']['gyro_sn']=$this->data['gyro_sn'];
		$gyrArray['GyroJobData']['conveyance']=$this->data['conveyance'];
		$gyrArray['GyroJobData']['conveyance_by']=$this->data['conveyance_by'];
		$gyrArray['GyroJobData']['conveyance_type']=$this->data['conveyance_type'];
		$gyrArray['GyroJobData']['top_survey']=$this->data['add_gyrojob_form']['top_survey'];
		$gyrArray['GyroJobData']['buttom_survey']=$this->data['add_gyrojob_form']['buttom_survey'];
		$gyrArray['GyroJobData']['latitude']=$this->data['add_gyrojob_form']['latitude'];
		$gyrArray['GyroJobData']['longitude']=$this->data['add_gyrojob_form']['longitude'];
		$gyrArray['GyroJobData']['comments']=$this->data['add_gyrojob_form']['comments'];
                if($this->GyroJobData->save($gyrArray)){
			if($res=='add'){	
			    $lastJob=base64_encode($this->GyroJobData->getLastInsertId());
			    echo $res.'~'.$lastJob;
			}elseif($res=='update'){
			    $lastJob=base64_encode($this->data['add_gyrojob_form']['id']);
			    echo $res.'~'.$lastJob;
				       
		        }else{
			    echo 'fail';
		       }  
				    
		    exit;
		
	        }
        }
	    function gyro_block($id = null)
	     {

	     
	                if(!$id)
			{
		           $this->redirect(array('action'=>report_job_list), null, true);
			}
		       else
		       
		       
		       {
			  
	                 	$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['GyroJobData']['id'] = $id;
					  $this->request->data['GyroJobData']['isblocked'] = 'Y';
					  $this->GyroJobData->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	     
	      }
	      
	      function gyro_unblock($id = null)
	     {
                  
			if(!$id)
			{
		           $this->redirect(array('action'=>report_job_list), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['GyroJobData']['id'] = $id;
					  $this->request->data['GyroJobData']['isblocked'] = 'N';
					  $this->GyroJobData->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	       function gyro_delete()
	     {
		        $this->layout="ajax";
			 $idArray = explode("^", $this->data['id']);
				
	            	        foreach($idArray as $id)
			       {
				
					  $this->request->data['GyroJobData']['id'] = $id;
					  $this->request->data['GyroJobData']['isdeleted'] = 'Y';
					  $this->GyroJobData->save($this->request->data,false);
					  
			       }
			       echo 'ok';
			      
			   
			       exit;			 
		     
	      }
	function add_gauge_job_data($id=null,$gaugeID=null){
		$this->_checkAdminSession();
	        $this->_getRoleMenuPermission();
		$this->grid_access();
		$this->layout="after_adminlogin_template";
		$techCableDetail = $this->TecCable->find('all');
		$this->set('techCableDetail',$techCableDetail);
		$ysplitter = $this->YSplitter->find('all');
		$this->set('ysplitter',$ysplitter);
		$presrange = $this->PressRange->find('all');
		$this->set('presrange',$presrange);
		$temprange= $this->TempRange->find('all');
		$this->set('temprange',$temprange);
		$whoconnector = $this->WhoConnector->find('all');
		$this->set('whoconnector',$whoconnector);
		$sauDetail= $this->Sau->find('all');
		$this->set('sauDetail',$sauDetail);
		
		$gaugetype= $this->GaugeType->find('all');
		$this->set('gaugetype',$gaugetype);
		
		$manufacture= $this->Manufacture->find('all');
		$this->set('manufacture',$manufacture);
			
		$reportdetail = $this->JobReportMain->find('all', array('conditions' => array('JobReportMain.id' =>base64_decode($id))));
		$this->set('reportno',base64_decode($id));
		$this->set('report_val',$id);
		$this->set('report_number',$reportdetail[0]['JobReportMain']['report_no']);
		 $countGouge=$this->GaugeData->find('count',array('conditions'=>array('GaugeData.report_id'=>base64_decode($id))));  
                if($gaugeID==''){
			if($countGouge!=0){
		                $countGouge=$countGouge+1;	
			 }else{
				$countGouge=1;		
			}
			 $this->set('countGouge',$countGouge);
		  	 $this->set('heading', 'Add Gouge Job Data' );
			 $this->set('button','Submit' );
		         $this->set('id',0);
			 $this->set('gt','');
			 $this->set('pr','');
		         $this->set('tr','');
			 $this->set('mf','');
			 $this->set('tc','');
			 $this->set('ys','');
		         $this->set('wc','');
			 $this->set('su','');
			 $this->set('comments','');
			 $this->set('gauge_set_depth','');
			 $this->set('gauge_sn','');
		   
		   
		}else{
		
			$this->set('heading', 'Edit Gyro Job Data' );
			$this->set('button','Update' );
			$gaugeData= $this->GaugeData->find('all',array('conditions'=>array('GaugeData.id'=>base64_decode($gaugeID))));
			 $this->set('countGouge',$gaugeData[0]['GaugeData']['gauge_no']);
		  	 $this->set('heading', 'Edit Gouge Job Data' );
			 $this->set('button','Update' );
		         $this->set('id',base64_decode($gaugeID));
			 $this->set('gt',$gaugeData[0]['GaugeData']['gauge_type']);
			 $this->set('pr',$gaugeData[0]['GaugeData']['press_range']);
		         $this->set('tr',$gaugeData[0]['GaugeData']['temp_range']);
			 $this->set('mf',$gaugeData[0]['GaugeData']['manufacture']);
			 $this->set('tc',$gaugeData[0]['GaugeData']['tec_cable']);
			 $this->set('ys',$gaugeData[0]['GaugeData']['ysplitre']);
		         $this->set('wc',$gaugeData[0]['GaugeData']['who_connector']);
			 $this->set('su',$gaugeData[0]['GaugeData']['sau']);
			 $this->set('comments',$gaugeData[0]['GaugeData']['comments']);
			 $this->set('gauge_set_depth',$gaugeData[0]['GaugeData']['gauge_set_depth']);
			 $this->set('gauge_sn',$gaugeData[0]['GaugeData']['gauge_sn']);
		   
		}
		
	}
		function gaugeprocess(){
		$this->_checkAdminSession();
		$this->layout="ajax";
	
		$gaugeArray=array();
			 
		if($this->data['add_gaugejob_form']['id']!=0){
		       $res='update';
		       $gaugeArray['GaugeData']['id']=$this->data['add_gaugejob_form']['id'];
		}else{
			$res='add';
		 }
		$gaugeArray['GaugeData']['report_id']=$this->data['report_no'];  
                $gaugeArray['GaugeData']['gauge_no']=$this->data['gauge_no']; 
		$gaugeArray['GaugeData']['gauge_type']=$this->data['gauge_type'];  
		$gaugeArray['GaugeData']['press_range']=$this->data['press_range']; 
                $gaugeArray['GaugeData']['temp_range']=$this->data['temp_range'];
		$gaugeArray['GaugeData']['manufacture']=$this->data['manufacture'];
		$gaugeArray['GaugeData']['tec_cable']=$this->data['tec_cable'];
		$gaugeArray['GaugeData']['ysplitre']=$this->data['ysplitre'];
		$gaugeArray['GaugeData']['who_connector']=$this->data['whoconnector'];
		$gaugeArray['GaugeData']['sau']=$this->data['sau'];
		$gaugeArray['GaugeData']['comments']=$this->data['add_gaugejob_form']['comments'];
		$gaugeArray['GaugeData']['gauge_sn']=$this->data['add_gaugejob_form']['gauge_sn'];
		$gaugeArray['GaugeData']['gauge_set_depth']=$this->data['add_gaugejob_form']['gauge_set_depth'];
                if($this->GaugeData->save($gaugeArray)){
			if($res=='add'){	
			    $lastJob=base64_encode($this->GaugeData->getLastInsertId());
			    echo $res.'~'.$lastJob;
			}elseif($res=='update'){
			    $lastJob=base64_encode($this->data['add_gaugejob_form']['id']);
			    echo $res.'~'.$lastJob;
				       
		        }else{
			    echo 'fail';
		       }  
				    
		    exit;
		
	        }
        }
	function report_gauge_job_list($id){
		$this->_checkAdminSession();
	        $this->_getRoleMenuPermission();
		$this->grid_access();
		//$this->report_hsse_link();
		
		$this->layout="after_adminlogin_template";
		$reportdetail = $this->JobReportMain->find('all', array('conditions' => array('JobReportMain.id' =>base64_decode($id))));
		$this->set('id',base64_decode($id));
		$this->set('report_val',$id);
		$this->set('report_number',$reportdetail[0]['JobReportMain']['report_no']);
	
		if(!empty($this->request->data))
		{
			$action = $this->request->data['GaugeData']['action'];
			$this->set('action',$action);
			$limit = $this->request->data['GaugeData']['limit'];
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
	
	public function get_all_gauge_list($report_id)
		{
			Configure::write('debug', '2');  
			$this->layout = "ajax"; 
			//pr($_REQUEST);
			$this->_checkAdminSession();
			$condition="";

			
			$condition="GaugeData.report_id = $report_id AND GaugeData.isdeleted = 'N'";
			
                        if(isset($_REQUEST['filter'])){
				switch($_REQUEST['filter']){
				case'gauge_type_val':
	                            $gtv = $this->GaugeType->find('all',array('conditions'=>array('type'=>$_REQUEST['value'])));
				    $condition .= "AND GaugeData.gauge_type ='".$gtv[0]['GaugeType']['id']."'";	
				break;
				case'tec_cable_value':
				     $tc = $this->TecCable->find('all',array('conditions'=>array('type'=>$_REQUEST['value'])));
				$condition .= "AND GaugeData.tech_cable ='".$tc[0]['TecCable']['id']."'";	
					
				break;
				case'temp_range_value':
				     $tr = $this->TempRange->find('all',array('conditions'=>array('type'=>$_REQUEST['value'])));
				     $condition .= "AND GaugeData.temp_range ='".$tr[0]['TempRange']['id']."'";	
				     
				break;
			        case'press_range_value':
				     $tr = $this->PressRange->find('all',array('conditions'=>array('type'=>$_REQUEST['value'])));
				$condition .= "AND GaugeData.press_range ='".$tr[0]['PressRange']['id']."'";	
				     
				break; 
				case'ysplitter_value':
				     $ysp = $this->YSplitter->find('all',array('conditions'=>array('type'=>$_REQUEST['value'])));
				     $condition .= "AND GaugeData.ysplitre ='".$ysp[0]['YSplitter']['id']."'";					   
				break;
			       case'press_range_value':
				     $pr = $this->PressRange->find('all',array('conditions'=>array('type'=>$_REQUEST['value'])));
				     $condition .= "AND GaugeData.ysplitre ='".$pr[0]['PressRange']['id']."'";					   
				break;
			        case'sau_value':
				     $su = $this->Sau->find('all',array('conditions'=>array('type'=>$_REQUEST['value'])));
				     $condition .= "AND GaugeType.sau ='".$su [0]['Sau']['id']."'";					   
				break;
			        case'manufacture_value':
				     $mn = $this->Manufacture->find('all',array('conditions'=>array('type'=>$_REQUEST['value'])));
				$condition .= "AND GaugeData.manufacture ='".$mn[0]['Manufacture']['id']."'";					   
				break;
				}
			}
			
			
			$limit=null;
			if($_REQUEST['limit'] == 'all'){
						
				
			}else{
				$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
			}
	
			 $adminArray = array();	
			 $count = $this->GaugeData->find('count' ,array('conditions' => $condition));
			 $adminA = $this->GaugeData->find('all',array('conditions' => $condition,'order' => 'GaugeData.id DESC','limit'=>$limit));

			$i = 0;
			foreach($adminA as $rec)
			{
				
				if($rec['GaugeData']['isblocked'] == 'N')
				{
					$adminA[$i]['GaugeData']['blockHideIndex'] = "true";
					$adminA[$i]['GaugeData']['unblockHideIndex'] = "false";
					$adminA[$i]['GaugeData']['isdeletdHideIndex'] = "true";
				}else{
					$adminA[$i]['GaugeData']['blockHideIndex'] = "false";
					$adminA[$i]['GaugeData']['unblockHideIndex'] = "true";
					$adminA[$i]['GaugeData']['isdeletdHideIndex'] = "false";
				}
				
			    $adminA[$i]['GaugeData']['gauge_type_val']=$rec['GaugeType']['type'];
			    $adminA[$i]['GaugeData']['tec_cable_value']=$rec['TecCable']['type'];
			    $adminA[$i]['GaugeData']['temp_range_value']=$rec['TempRange']['type'];
			    $adminA[$i]['GaugeData']['ysplitter_value']=$rec['YSplitter']['type'];
			    $adminA[$i]['GaugeData']['press_range_value']=$rec['PressRange']['type'];
			    $adminA[$i]['GaugeData']['sau_value']=$rec['Sau']['type'];
			    $adminA[$i]['GaugeData']['manufacture_value']=$rec['Manufacture']['type'];
			    $i++;
			}
	
			if($count==0){
			   $adminArray=array();
		        }else{
			  $adminArray = Set::extract($adminA, '{n}.GaugeData');
		        }
			 
			  $this->set('total', $count); 
			  $this->set('admins', $adminArray); 
		
	}
	  function gaugedata_block($id = null)
	     {

	     
	                if(!$id)
			{
		           $this->redirect(array('action'=>report_job_list), null, true);
			}
		       else
		       
		       
		       {
			  
	                 	$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['GaugeData']['id'] = $id;
					  $this->request->data['GaugeData']['isblocked'] = 'Y';
					  $this->GaugeData->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	     
	      }
	      
	      function gaugedata_unblock($id = null)
	     {
                  
			if(!$id)
			{
		           $this->redirect(array('action'=>report_job_list), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['GaugeData']['id'] = $id;
					  $this->request->data['GaugeData']['isblocked'] = 'N';
					  $this->GaugeData->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	       function gaugedata_delete()
	     {
		        $this->layout="ajax";
			 $idArray = explode("^", $this->data['id']);
				
	            	        foreach($idArray as $id)
			       {
				
					  $this->request->data['GaugeData']['id'] = $id;
					  $this->request->data['GaugeData']['isdeleted'] = 'Y';
					  $this->GaugeData->save($this->request->data,false);
					  
			       }
			       echo 'ok';
			      
			   
			       exit;			 
		     
	      }
	      public function report_job_attachment_list($id=null){
			$this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();   
      			$this->layout="after_adminlogin_template";
		
		        $reportdetail = $this->JobReportMain->find('all', array('conditions' => array('JobReportMain.id' =>base64_decode($id))));
			$this->set('report_number',$reportdetail[0]['JobReportMain']['report_no']);	       
	                $this->set('id',base64_decode($id));
				if(!empty($this->request->data))
				{
					$action = $this->request->data['JobReportMain']['action'];
					$this->set('action',$action);
					$limit = $this->request->data['JobReportMain']['limit'];
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
	
		
		$condition="JobAttachment.report_id = $report_id AND JobAttachment.isdeleted = 'N'";
		
		
                if(isset($_REQUEST['filter'])){
			$condition .= "AND ucase(JobAttachment.".$_REQUEST['filter'].") like '".$_REQUEST['value']."%'";	
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			//$condition .= " order by Category.id DESC";
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}

		//$count = $this->HsseIncident->find('count' ,array('conditions' => $condition)); 
		$adminArray = array();	
		 $count = $this->JobAttachment->find('count' ,array('conditions' => $condition));
		 $adminA = $this->JobAttachment->find('all',array('conditions' => $condition,'order' => 'JobAttachment.id DESC','limit'=>$limit));
		  
		$i = 0;
		foreach($adminA as $rec)
		{			
			if($rec['JobAttachment']['isblocked'] == 'N')
			{
				$adminA[$i]['JobAttachment']['blockHideIndex'] = "true";
				$adminA[$i]['JobAttachment']['unblockHideIndex'] = "false";
				$adminA[$i]['JobAttachment']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['JobAttachment']['blockHideIndex'] = "false";
				$adminA[$i]['JobAttachment']['unblockHideIndex'] = "true";
				$adminA[$i]['JobAttachment']['isdeletdHideIndex'] = "false";
			}
		      $adminA[$i]['JobAttachment']['image_src']=$this->attachment_list('JobAttachment',$rec);
		    $i++;
		}
		
		if($count==0){
			   $adminArray=array();
		}else{
			  $adminArray = Set::extract($adminA, '{n}.JobAttachment');
		}
		

		  $this->set('total', $count);  //send total to the view
		  $this->set('admins', $adminArray);  //send products to the view
		  //$this->set('status', $action);
	}
	      
	          function add_job_attachment($reoprt_id=null,$attachment_id=null){
		        $this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();   
		        $this->layout="after_adminlogin_template";
		
			if($attachment_id!=''){
				$attchmentdetail = $this->JobAttachment->find('all', array('conditions' => array('JobAttachment.id' =>base64_decode($attachment_id))));
				$imagepath=$this->file_edit_list($attchmentdetail[0]['JobAttachment']['file_name'],'JobAttachment',$attchmentdetail);
				$this->set('heading','Update File');
			        $this->set('attachment_id',$attchmentdetail[0]['JobAttachment']['id']);
			        $this->set('description',$attchmentdetail[0]['JobAttachment']['description']);
			        $this->set('button','Update');
			        $this->set('imagepath',$imagepath);
			        $this->set('imagename',$attchmentdetail[0]['JobAttachment']['file_name']);
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
			
			$reportdetail = $this->JobReportMain->find('all', array('conditions' => array('JobReportMain.id' =>base64_decode($reoprt_id))));
			$this->set('report_number',$reportdetail[0]['JobReportMain']['report_no']);        
			
	       }
	       
       
	       
	       
	          function uploadimage($upparname=NULL,$deleteImageName=NULL){
		        $this->layout="ajax";
			$allowed_image =$this->image_upload_type();
			$this->upload_image_func($upparname,'Jobs',$allowed_image);
			exit;
	       }
	           function  jobattachmentprocess(){
		         $this->layout="ajax";
	
		         $attachmentArray=array();
			 
		         if($this->data['Jobs']['id']!=0){
				$res='update';
				$attachmentArray['JobAttachment']['id']=$this->data['Jobs']['id'];
			 }else{
				 $res='add';
			  }
				
		   	   $attachmentArray['JobAttachment']['description']=$this->data['attachment_description'];
			   $attachmentArray['JobAttachment']['file_name']=$this->data['hiddenFile']; 
		           $attachmentArray['JobAttachment']['report_id']=$this->data['report_id'];
			   if($this->JobAttachment->save($attachmentArray)){
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
		           $this->redirect(array('action'=>report_job_list), null, true);
			}
		       else
		        
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['JobAttachment']['id'] = $id;
					  $this->request->data['JobAttachment']['isblocked'] = 'Y';
					  $this->JobAttachment->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	      function attachment_unblock($id = null)
	     {
          
			if(!$id)
			{
		           $this->redirect(array('action'=>report_job_list), null, true);
			}
		       else
		       {
			
				
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['JobAttachment']['id'] = $id;
					  $this->request->data['JobAttachment']['isblocked'] = 'N';
					  $this->JobAttachment->save($this->request->data,false);				
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
				
					  $this->request->data['JobAttachment']['id'] = $id;
					  $this->request->data['JobAttachment']['isdeleted'] = 'Y';
					  $this->JobAttachment->save($this->request->data,false);
					  
			       }
			       echo 'ok';
			   
			       exit;			 
		     
	      }
	      public function report_job_link_list($id=null,$typSearch){
		
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
                $this->grid_access();		
		$this->layout="after_adminlogin_template";
		$sqlinkDetail = $this->JobLink->find('all', array('conditions' => array('JobLink.report_id' =>base64_decode($id))));
		$reportdetail = $this->JobReportMain->find('all', array('conditions' => array('JobReportMain.id' =>base64_decode($id))));            
		$this->set('report_number',$reportdetail[0]['JobReportMain']['report_no']);
		$this->set('report_id',$id);
		$this->set('id',base64_decode($id));
		
		
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
	
                $condition="JobLink.report_id =".base64_decode($report_id);
		
               if(isset($_REQUEST['filter'])){
	             $link_type=explode("~",$this->link_search($_REQUEST['value']));
		     
		     $condition .= " AND JobLink.link_report_id ='".$link_type[0]."' AND JobLink.type ='".$link_type[1]."'";
		     
		}
		
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}

		 $adminArray = array();
		 if($filterTYPE!='all'){
			
		    $condition .= " AND JobLink.type ='".$filterTYPE."'";
		
		 }
		 $count = $this->JobLink->find('count' ,array('conditions' => $condition));
		 $adminA = $this->JobLink->find('all',array('conditions' => $condition,'order' => 'JobLink.id DESC','limit'=>$limit));
		 

		  
		$i = 0;
		foreach($adminA as $rec)
		{
	
			if($rec['JobLink']['isblocked'] == 'N')
			{
				$adminA[$i]['JobLink']['blockHideIndex'] = "true";
				$adminA[$i]['JobLink']['unblockHideIndex'] = "false";
				$adminA[$i]['JobLink']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['JobLink']['blockHideIndex'] = "false";
				$adminA[$i]['JobLink']['unblockHideIndex'] = "true";
				$adminA[$i]['JobLink']['isdeletdHideIndex'] = "false";
			}
			 $link_type=$this->link_grid($adminA[$i],$rec['JobLink']['type'],'JobLink',$rec);
		         $explode_link_type=explode("~",$link_type);
		         $adminA[$i]['JobLink']['link_report_no']=$explode_link_type[0];
		         $adminA[$i]['JobLink']['type_name']=$explode_link_type[1];
			

		
			
		    $i++;
		}
		
		  if($count==0){
			$adminArray=array();
		  }else{
			 $adminArray = Set::extract($adminA, '{n}.JobLink');
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
		           $this->redirect(array('action'=>'report_job_list'), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['JobLink']['id'] = $id;
					  $this->request->data['JobLink']['isblocked'] = 'Y';
					  $this->JobLink->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	      function link_unblock($id = null)
	     {
                        $this->_checkAdminSession();
			if(!$id)
			{
		           $this->redirect(array('action'=>'report_job_list'), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['JobLink']['id'] = $id;
					  $this->request->data['JobLink']['isblocked'] = 'N';
					  $this->JobLink->save($this->request->data,false);				
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
					  
					  $deleteproperty = "DELETE FROM `job_links` WHERE `id` = {$id}";
                                          $deleteval=$this->JobLink->query($deleteproperty);
					  
			       }
		
			       echo 'ok';
			       exit;
		      }else{
			 $this->redirect(array('action'=>'report_job_list'), null, true);
		      }
			       
			      
			       
	
	      }
	     function  linkrocess(){
		   
		         $this->layout="ajax";
			 $this->_checkAdminSession();
         	         $this->_getRoleMenuPermission();
		         $this->grid_access();
			 $linkArray=array();
			 $explode_id_type=explode("_",$this->data['type']);
		          $sqlinkDetail = $this->JobLink->find('all', array('conditions' => array('JobLink.report_id' =>base64_decode($this->data['report_no']),'JobLink.link_report_id' =>$explode_id_type[1],'JobLink.type' =>$explode_id_type[0])));
			 if(count($sqlinkDetail)>0){
				echo 'avl';
				
			 }else{
			 $linkArray['JobLink']['type']=$explode_id_type[0];
			 $linkArray['JobLink']['report_id']=base64_decode($this->data['report_no']);
			 $linkArray['JobLink']['link_report_id']=$explode_id_type[1];
			 $linkArray['JobLink']['user_id']=$_SESSION['adminData']['AdminMaster']['id'];
			 
			 if($this->JobLink->save($linkArray)){
				 echo 'ok';
			 }else{
			         echo 'fail';
			 }
			
			 
			 }
			   
			   
			
                        
		   exit;
		
	} 
			      
	function add_job_customer($reoprt_id=null){
		 $this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
                 $this->grid_access();   
		 $this->layout="after_adminlogin_template";
		 $reportdetail = $this->JobReportMain->find('all', array('conditions' => array('JobReportMain.id' =>base64_decode($reoprt_id))));            
		 $this->set('report_number',$reportdetail[0]['JobReportMain']['report_no']);
		 $this->set('report_id',base64_decode($reoprt_id));
		 $this->set('report_val',$reoprt_id);
		 $this->set('heading','Customer Satisfaction Report');
		 $this->set('button','Add');
		 $jsl= $this->JobCustomerFeedbackElement->find('all');
		 $this->set('jsl',$jsl);
		 $jslv= $this->JobCustomerFeedback->find('all',array('conditions' => array('JobCustomerFeedback.report_id' =>base64_decode($reoprt_id))));
		 if(count($jslv)>0){
		 $this->set('csr_value',$jslv[0]['JobCustomerFeedback']['total_val']); 
		 $this->set('comments',$jslv[0]['JobCustomerFeedback']['comments']);
		 $this->set('total_csr_val', $jslv[0]['JobCustomerFeedback']['total_val']); 
		 $this->set('name_value',$jslv[0]['JobCustomerFeedback']['name_value']);
		 $explode_name_value=explode(",",$jslv[0]['JobCustomerFeedback']['name_value']);
		 $explode_name=array();
		 $explode_value=array();
		 for($k=0;$k<count($explode_name_value);$k++){
			$expld=explode("~",$explode_name_value[$k]);
			$explode_name[]=$expld[0];
			$explode_value[]=$expld[1];
			
		 }
		 
		 $this->set('explode_name',$explode_name);
		 $this->set('explode_value',$explode_value);
		
		 }elseif(count($jslv)==0){
		 $this->set('csr_value',''); 
		 $this->set('comments','');
		 $this->set('total_csr_val',0); 
		 $this->set('name_value','');
		 $this->set('explode_name',array());
		 $this->set('explode_value',array());
		 }
		
	}
       function customsaticationprocess(){
	 $this->layout="ajax";
	 $this->_checkAdminSession();
	 $this->_getRoleMenuPermission();
         $this->grid_access();
         $jcf=array(); 

	        $cusdetail = $this->JobCustomerFeedback->find('all', array('conditions' => array('JobCustomerFeedback.report_id' =>$this->data['report_no'])));
          	if(count($cusdetail)>0){
			$res='update';
			$jcf['JobCustomerFeedback']['id']= $cusdetail[0]['JobCustomerFeedback']['id']; 
		}else{
			$res='add';
		}
		$jcf['JobCustomerFeedback']['report_id']=$this->data['report_no'];  
		$jcf['JobCustomerFeedback']['name_value']=$this->data['add_job_customer_form']['name_value']; 
		$jcf['JobCustomerFeedback']['total_val']=$this->data['add_job_customer_form']['total_csr_val'];
		$jcf['JobCustomerFeedback']['comments']=$this->data['add_job_customer_form']['comments'];
		$jcf['JobCustomerFeedback']['user_id']=$_SESSION['adminData']['AdminMaster']['id'];
	
		if($this->JobCustomerFeedback->save($jcf)){
				 echo $res;
                        }else{
			         echo 'fail';
			 }

	 exit;
       }
       
       
             	function job_remedila_email_list($id=null){
			$this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();
			$this->layout="after_adminlogin_template";
			$this->set('report_no',base64_decode($id));
			$reportdetail = $this->JobReportMain->find('all', array('conditions' => array('JobReportMain.id' =>base64_decode($id))));
			$this->set('id',base64_decode($id));
	               
	
			
			$this->set('report_val',$id);
			$this->set('report_number',$reportdetail[0]['JobReportMain']['report_no']);
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
		
			$condition="RemidialEmailList.report_id = $report_id AND report_type='job'";
			
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
				
			    $remC= $this->JobRemidial->find('all', array('conditions' => array('JobRemidial.report_no' =>$rec['RemidialEmailList']['report_id'],'JobRemidial.remedial_no' =>$rec['RemidialEmailList']['remedial_no'])));
			    
			    $rrd=explode(" ",$remC[0]['JobRemidial']['remidial_reminder_data']);
			    $rrdE=explode("/",$rrd[0]);
			    $adminA[$i]['RemidialEmailList']['reminder_data']=date("d/M/y", mktime(0, 0, 0, $rrdE[1], $rrdE[0], $rrdE[2]));
			    
			    $create_on=explode("-",$remC[0]['JobRemidial']['remidial_create']);
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
			 $remidialData= $this->JobRemidial->find('all',array('conditions'=>array('JobRemidial.report_no'=>$report_id,'JobRemidial.remedial_no'=>$remedial_no)));
			 $reportData= $this->JobReportMain->find('all',array('conditions'=>array('JobReportMain.id'=>$remidialData[0]['JobRemidial']['report_no'])));
			 $userData= $this->AdminMaster->find('all',array('conditions'=>array('AdminMaster.id'=>$remidialData[0]['JobRemidial']['remidial_responsibility'])));
			 $this->set('fullname',$userData[0]['AdminMaster']['first_name']." ".$userData[0]['AdminMaster']['last_name']);
			 $this->set('report_no',$reportData[0]['JobReportMain']['report_no']);
			 $this->set('remidialData',$remidialData);
		
		}
       

}
        
?>
