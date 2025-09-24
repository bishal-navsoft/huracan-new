<?PHP
class AuditsController extends AppController
{
	public $name = 'Audits';
	public $uses = array('AuditReportMain','Report','SqReportMain','RemidialEmailList','JnReportMain','AuditClient','Incident','AuditType','AuditReportMain','AuditRemidial','AuditAttachment','AuditLink','BusinessType','Fieldlocation','Client','HsseLink','IncidentLocation','IncidentSeverity','Country','AdminMaster','HsseClient','HsseIncident','HssePersonnel','RolePermission','RoleMaster','AdminMenu','IncidentCategory','Priority','IncidentSubCategory','Residual','HsseAttachment','HsseRemidial','Priority','ImmediateCause','ImmediateSubCause','HsseInvestigation','RootCause','HsseInvestigationData','Potential','HsseClientfeedback','LessonMain','DocumentMain');
	public $helpers = array('Html','Form','Session','Js');
	//public $components = array('RequestHandler', 'Cookie', 'Resizer');
	
        public function report_audit_list(){
		$this->_checkAdminSession();
	        $this->_getRoleMenuPermission();
		$this->grid_access();
		//$this->report_hsse_link();
		
		$this->layout="after_adminlogin_template";
	
		if(!empty($this->request->data))
		{
			$action = $this->request->data['AuditReportMain']['action'];
			$this->set('action',$action);
			$limit = $this->request->data['AuditReportMain']['limit'];
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
		$auditIdBoolen=array();
		$condition="";
		$condition = "AuditReportMain.isdeleted = 'N'";
		
		$adminA = $this->AuditReportMain->find('all' ,array('conditions' => $condition,'order' => 'AuditReportMain.id DESC','limit'=>$limit));
		for($i=0;$i<count($adminA);$i++){
			if(($_SESSION['adminData']['AdminMaster']['id']==$adminA[$i]['AuditReportMain']['created_by']) || ($_SESSION['adminData']['RoleMaster']['id']==1)){
			   array_push($auditIdBoolen,1);	
				
			}else{
			  array_push($auditIdBoolen,0);
			}
			
		}
		$this->Session->write('auditIdBoolen',$auditIdBoolen);
		
	}
	public function get_all_report($action ='all')
	{
		Configure::write('debug', '2');  
		$this->layout = "ajax"; 
		$this->_checkAdminSession();
		$condition="";
		$condition = "AuditReportMain.isdeleted = 'N'";
                if(isset($_REQUEST['filter'])){
		switch($_REQUEST['filter']){
		       case'report_no':
	$condition .= "AND ucase(AuditReportMain.".$_REQUEST['filter'].") like '".$_REQUEST['value']."%'";	
			break;
		        case'client_name':
			$clientCondition= " ucase(Client.name) like '".$_REQUEST['value']."%'";
			$clientDetatail=$this->Client->find('all', array('conditions' =>$clientCondition));
          		$condition .= "AND AuditReportMain.Client =".$clientDetatail[0]['Client']['id'];	
			break;
		        case'creater_name':
			$spliNAME=explode(" ",$_REQUEST['value']);
			$spliLname=$spliNAME[count($spliNAME)-1];
			$spliFname=$spliNAME[0];
			$adminCondition="AdminMaster.first_name like '%".$spliFname."%' AND AdminMaster.last_name like '%".$spliLname."%'";
			$userDetail = $this->AdminMaster->find('all',array('conditions'=>$adminCondition));
			$addimid=$userDetail[0]['AdminMaster']['id'];
			$condition .= "AND AuditReportMain.created_by ='".$addimid."'";	
			break;
		        case'event_date_val':
		        $explodemonth=explode('/',$_REQUEST['value']);
			$day=$explodemonth[0];
			$month=date('m', strtotime($explodemonth[1]));
			$year="20$explodemonth[2]";
			$createon=$year."-".$month."-".$day;
			$condition .= "AND AuditReportMain.event_date ='".$createon."'";	
		        break;
		}
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}
		$count = $this->AuditReportMain->find('count' ,array('conditions' => $condition));
		$adminArray = array();	
	         $adminA = $this->AuditReportMain->find('all' ,array('conditions' => $condition,'order' => 'AuditReportMain.id DESC','limit'=>$limit));
		

		$i = 0;
		$auditIdBoolen=array();
		unset($_SESSION['auditIdBoolen']);
		foreach($adminA as $rec)
		{
			
			
			if(($_SESSION['adminData']['AdminMaster']['id']==$rec['AuditReportMain']['created_by']) || ($_SESSION['adminData']['RoleMaster']['id']==1)){
				array_push($auditIdBoolen,1);
				$adminA[$i]['AuditReportMain']['edit_permit'] ="false";
				$adminA[$i]['AuditReportMain']['view_permit'] ="false";
				$adminA[$i]['AuditReportMain']['delete_permit'] ="false";
				$adminA[$i]['AuditReportMain']['block_permit'] ="false";
				$adminA[$i]['AuditReportMain']['unblock_permit'] ="false";
				$adminA[$i]['AuditReportMain']['checkbox_permit'] ="false";
				
				if($rec['AuditReportMain']['isblocked'] == 'N')
			            {
					$adminA[$i]['AuditReportMain']['blockHideIndex'] = "true";
					$adminA[$i]['AuditReportMain']['unblockHideIndex'] = "false";
				        
			         }else{
					$adminA[$i]['AuditReportMain']['blockHideIndex'] = "false";
					$adminA[$i]['AuditReportMain']['unblockHideIndex'] = "true";
					
			        }
				
					
			}else{
				array_push($auditIdBoolen,0);
				$adminA[$i]['AuditReportMain']['edit_permit'] ="true";
				$adminA[$i]['AuditReportMain']['view_permit'] ="false";
				$adminA[$i]['AuditReportMain']['delete_permit'] ="true";
				$adminA[$i]['AuditReportMain']['block_permit'] ="true";
				$adminA[$i]['AuditReportMain']['unblock_permit'] ="true";
			        $adminA[$i]['AuditReportMain']['blockHideIndex'] = "true";
				$adminA[$i]['AuditReportMain']['unblockHideIndex'] = "true";
				$adminA[$i]['AuditReportMain']['checkbox_permit'] ="true";
				
				
			}
			
			
	            $eventdate=explode("-",$rec['AuditReportMain']['event_date']);
		    $evDT=date("d/M/y", mktime(0, 0, 0, $eventdate[1],$eventdate[2],$eventdate[0]));
		    $adminA[$i]['AuditReportMain']['event_date_val']=$evDT; 		    
		    $adminA[$i]['AuditReportMain']['client_name'] = $rec['Client']['name'];    
		    $adminA[$i]['AuditReportMain']['creater_name'] = $rec['AdminMaster']['first_name'].' '.$rec['AdminMaster']['last_name'];
		    $adminA[$i]['AuditReportMain']['audit_type_name'] = $rec['AuditType']['type'];
		    $i++;
		}
	        
		if($count==0){
			$adminArray=array();
		  }else{
			$adminArray = Set::extract($adminA, '{n}.AuditReportMain');
		  }
		$this->Session->write('auditIdBoolen',$auditIdBoolen);  
		$this->set('total', $count);  //send total to the view
	        $this->set('admins', $adminArray);  //send products to the view
		$this->set('status', $action);
	}

	
	
	
	function audit_report_block($id = null)
	{
          
	
		if(!$id)
		{
			  // $this->Session->setFlash('Invalid id for admin');
			   $this->redirect(array('action'=>audit_report_list), null, true);
		}
		else
		{
			 $idArray = explode("^", $id);
			 
			 
		
			foreach($idArray as $id)
			{
				   $id = $id;
				   $this->request->data['AuditReportMain']['id'] = $id;
				   $this->request->data['AuditReportMain']['isblocked'] = 'Y';
				   $this->AuditReportMain->save($this->request->data,false);				
			}	     
			exit;			 
		}
	}
	
	function audit_report_unblock($id = null)
	{

		
		if(!$id)
		{
	
			$this->redirect(array('action'=>audit_report_list), null, true);
		}
		else
		{
			$idArray = explode("^", $id);
		
			foreach($idArray as $id)
			{
				$id = $id;
				$this->request->data['AuditReportMain']['id'] = $id;
				$this->request->data['AuditReportMain']['isblocked'] = 'N';
				$this->AuditReportMain->save($this->request->data,false);
			}	
			exit;
		 
		}
	}
	
	function audit_report_delete()
	     {
		      $this->layout="ajax";
	      
		      if($this->data['id']!=''){
			$idArray = explode("^", $this->data['id']);
                           foreach($idArray as $id)
			       {
				
				   $deleteClient = "DELETE FROM `audit_clients` WHERE `report_id` = {$id}";
                                   $deleteC=$this->AuditClient->query( $deleteClient);
				   
				   $deleteRemidial = "DELETE FROM `audit_remidials` WHERE `report_no` = {$id}";
                                   $deleteR=$this->AuditRemidial->query($deleteRemidial);
				   
				   $deleteAttachment = "DELETE FROM `audit_attachments` WHERE `report_id` = {$id}";
                                   $deleteA=$this->AuditAttachment->query($deleteAttachment);
				   
				  $deleteLInk = "DELETE FROM `audit_links` WHERE `report_id` = {$id}";
                                  $deleteL=$this->AuditLink->query($deleteLInk);
				  
				  
				  $deleteSq_remidials_email = "DELETE FROM `remidial_email_lists` WHERE `report_id` = {$id} AND `report_type`='audit'";
                                 $dHRem=$this->RemidialEmailList->query($deleteSq_remidials_email);
				  
				  
				
                                   $deleteMain = "DELETE FROM `audit_report_mains` WHERE `id` = {$id}";
                                   $deleteval=$this->AuditReportMain->query($deleteMain);
                  
					  
					  
			       }
		
			       echo 'ok';
			        exit;
		      }else{
			 $this->redirect(array('action'=>report_hsse_list), null, true);
		      }
			 
			      
			      
			       
	}
	
        public function audit_report_main($id=null)
	
	{        
	      
		 $this->_checkAdminSession();
		 $this->grid_access();
		 $this->_getRoleMenuPermission();
                 $this->layout="after_adminlogin_template";
	         $auditTypeDetail = $this->AuditType->find('all');
		 $businessDetail = $this->BusinessType->find('all');
		 $fieldlocationDetail = $this->Fieldlocation->find('all');
		 $clientDetail = $this->Client->find('all');
		 $countryDetail = $this->Country->find('all');
		 $userDetail = $this->AdminMaster->find('all');
		 $this->set('auditTypeDetail',$auditTypeDetail);
		 $this->set('businessDetail',$businessDetail);
		 $this->set('fieldlocationDetail',$fieldlocationDetail);
		 $this->set('clientDetail',$clientDetail);
		 $this->set('country',$countryDetail);
		 $this->set('userDetail',$userDetail);
		if($id==null)
		{
          
		  $this->set('id','0');
		  $this->set('event_date','');
		  $this->set('since_event_hidden',0);
		  $this->set('heading','Add Audit Report (Main)');
		  $this->set('button','Submit');
		  $this->set('report_no','');
		  $this->set('official',1);
  		  $this->set('closer_date','00-00-0000');
		  $this->set('audit_type',1);
		  $this->set('business_unit','');
		  $this->set('client','');
		  $this->set('field_location','');
		  $this->set('summary','');
		  $this->set('details','');
		  $this->set('reporter','');
		  $this->set('cnt',13);
		  $this->set('created_by',$_SESSION['adminData']['AdminMaster']['first_name']." ".$_SESSION['adminData']['AdminMaster']['last_name']);
		  $reportno=date('YmdHis');
		  $this->set('reportno',$reportno);
		 
		 }else if(base64_decode($id)!=null){
			
	 
		  $reportdetail = $this->AuditReportMain->find('all', array('conditions' => array('AuditReportMain.id' =>base64_decode($id))));
		   $this->Session->write('report_create',$reportdetail[0]['AuditReportMain']['created_by']);
		  if($reportdetail[0]['AuditReportMain']['client']==10){
			     $this->Session->write('audit_client', 'N/A');
		   }else{
			     $this->Session->write('audit_client', 'Yes');
		    }
		  
		  
                  $adminDATA = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$reportdetail[0]['AuditReportMain']['created_by'])));
                  $this->set('id',base64_decode($id));
		  if($reportdetail[0]['AuditReportMain']['closer_date']!=''){
		  $crtd=explode("-",$reportdetail[0]['AuditReportMain']['closer_date']);
		  $closedt=$crtd[1]."-".$crtd[2]."-".$crtd[0];
		  }else{
		  $closedt='';	
		  }
		  
		 if($reportdetail[0]['AuditReportMain']['event_date']!=''){
		  
		  $evndt=explode("-",$reportdetail[0]['AuditReportMain']['event_date']);
		  $event_date=$evndt[1]."-".$evndt[2]."-".$evndt[0];
		  
		  }else{
		  $event_date=''; 	
		  }
	  	  $this->set('event_date',$event_date);
		  $this->set('since_event_hidden',$reportdetail[0]['AuditReportMain']['since_event']);
		  $this->set('since_event',$reportdetail[0]['AuditReportMain']['since_event']);
		  $this->set('heading','Update Audit Report (Main)');
		  $this->set('button','Update');
		  $this->set('reportno',$reportdetail[0]['AuditReportMain']['report_no']);
  		  $this->set('closer_date',$closedt);
		  $this->set('official',$reportdetail[0]['AuditReportMain']['official']);
		  $this->set('audit_type',$reportdetail[0]['AuditReportMain']['audit_type']);
	          $this->set('cnt',$reportdetail[0]['AuditReportMain']['country']);
		  $this->set('business_unit',$reportdetail[0]['AuditReportMain']['business_unit']);
		  $this->set('client',$reportdetail[0]['AuditReportMain']['client']);
	          $this->set('field_location',$reportdetail[0]['AuditReportMain']['field_location']);
		  $this->set('summary',$reportdetail[0]['AuditReportMain']['summary']);
		  $this->set('details',$reportdetail[0]['AuditReportMain']['details']);
		  $this->set('reporter',$reportdetail[0]['AuditReportMain']['reporter']);
		  $this->set('created_by',$adminDATA[0]['AdminMaster']['first_name']." ".$adminDATA[0]['AdminMaster']['last_name']);
		 
		 
		 }
	}
	function reportprocess()
	 { 
	   $this->layout = "ajax";
	   $this->_checkAdminSession();
	   $reportData=array();

          if($this->data['add_audit_main_form']['id']==0){
		   $res='add';
 	                
	   }else{
		   $res='update';
		   $reportData['AuditReportMain']['id']=$this->data['add_audit_main_form']['id'];
		  
	    }
	      
	     
         if($this->data['event_date']!=''){
		$evndate=explode("-",$this->data['event_date']);
		$reportData['AuditReportMain']['event_date']=$evndate[2]."-".$evndate[0]."-".$evndate[1];
	   }else{
		$reportData['AuditReportMain']['event_date']='';	
	   }
	   
	   
	  if(isset($this->data['closer_date'])){
		$clsdate=explode("-",$this->data['closer_date']);
		$reportData['AuditReportMain']['closer_date']=$clsdate[2]."-".$clsdate[0]."-".$clsdate[1];
	   }else{
		$reportData['AuditReportMain']['closer_date']='0000-00-00';	
	   }
	   $reportData['AuditReportMain']['audit_type']=$this->data['audit_type']; 
	   $reportData['AuditReportMain']['business_unit'] =$this->data['business_unit'];  
	   $reportData['AuditReportMain']['client']=$this->data['client'];
	   $reportData['AuditReportMain']['field_location']=$this->data['field_location'];
	   $reportData['AuditReportMain']['country']=$this->data['country'];
	   $reportData['AuditReportMain']['reporter']=$this->data['reporter'];
	   $reportData['AuditReportMain']['report_no']=$this->data['report_no']; 
	   $reportData['AuditReportMain']['since_event']=$this->data['since_event'];
	   $reportData['AuditReportMain']['official']=$this->data['op'];
	   $reportData['AuditReportMain']['created_by']=$_SESSION['adminData']['AdminMaster']['id'];
	   $reportData['AuditReportMain']['reporter']=$this->data['reporter'];
	   $reportData['AuditReportMain']['summary']=$this->data['add_audit_main_form']['summary'];
	   $reportData['AuditReportMain']['details']=$this->data['add_audit_main_form']['details'];
	  
	   
	  if($this->AuditReportMain->save($reportData)){
		 if($res=='add'){
			 
			 $lastReport=base64_encode($this->AuditReportMain->getLastInsertId());
			 
		 }elseif($res=='update'){
			 $lastReport=base64_encode($this->data['add_audit_main_form']['id']);
		 }
		echo $res."~".$lastReport;
		if($this->data['client']==10){
			     $this->Session->write('audit_client', 'N/A');
		}else{
			     $this->Session->write('audit_client', 'Yes');
		}
	    }else{
		echo 'fail~0~0';
	    }
                
		
	   exit;
	}
	public function add_audit_client($id=null){
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
		$this->grid_access();
		$this->layout="after_adminlogin_template";
		$this->set('id','0');
		$reportdetail = $this->AuditReportMain->find('all', array('conditions' => array('AuditReportMain.id' =>base64_decode($id))));
		$clientdetail = $this->AuditClient->find('all', array('conditions' => array('AuditClient.report_id' =>base64_decode($id))));
	        $this->set('report_number',$reportdetail[0]['AuditReportMain']['report_no']); 
	        if(count($clientdetail)>0){
			if($clientdetail[0]['AuditClient']['review_date']!=''){
		  
				$evndt=explode("-",$clientdetail[0]['AuditClient']['review_date']);
				$rev_date=$evndt[1]."-".$evndt[2]."-".$evndt[0];
			
			}else{
				$rev_date=''; 	
			}
			if($clientdetail[0]['AuditClient']['client_feedback_date']!=''){
		  
				$cfd=explode("-",$clientdetail[0]['AuditClient']['client_feedback_date']);
				$cfd_date=$cfd[1]."-".$cfd[2]."-".$cfd[0];
			
			}else{
				$cfd_date=''; 	
			}      
			$this->set('heading','Update Client Data');
			$this->set('button','Update');
			$this->set('id',$clientdetail[0]['AuditClient']['id']);
			$this->set('well',$clientdetail[0]['AuditClient']['well']);
			$this->set('rig',$clientdetail[0]['AuditClient']['rig']);
			
			$this->set('clientreviewed',$clientdetail[0]['AuditClient']['clientreviewed']);
			$this->set('report_id',$clientdetail[0]['AuditClient']['report_id']);
			$this->set('review_date',$rev_date);
			$this->set('wellsiterep',$clientdetail[0]['AuditClient']['wellsiterep']);
			$this->set('client_feedback',$clientdetail[0]['AuditClient']['client_feedback']);
			$this->set('client_feedback_date',$cfd_date);
				
			if($clientdetail[0]['AuditClient']['clientreviewed']==3){
				$this->set('clientreviewed_style','style="display:block"');
				$this->set('clientreviewer',$clientdetail[0]['AuditClient']['clientreviewer']);
				
			 }else{
				$this->set('clientreviewed_style','style="display:none"');
				$this->set('clientreviewer','');
			 }
                }else{
			$this->set('heading','Add Client Data');
			$this->set('button','Submit');
			$this->set('id',0);
			$this->set('well','');
			$this->set('rig','');
			$this->set('clientncr','');
			$this->set('clientreviewed',1);
			$this->set('report_id',base64_decode($id));
			$this->set('clientreviewer','');
			$this->set('wellsiterep','');
			$this->set('clientreviewed_style','style="display:none"');
			$this->set('clientreviewer','');
			$this->set('client_feedback','');
			$this->set('client_feedback_date','');
			$this->set('review_date','');
			
		   
	      }

	
       }

       function auditprocess(){
	
	       $this->layout = "ajax";
	       $this->_checkAdminSession();
	       $auditClientData=array();
	       $clientdetail = $this->AuditClient->find('all', array('conditions' => array('AuditClient.report_id' =>$this->data['report_id'])));
		if(count($clientdetail)>0){
		       $res='update';
		       $auditClientData['AuditClient']['id']=$clientdetail[0]['AuditClient']['id'];
		}else{
		       $res='add';
		}
		
		if($this->data['review_date']!=''){
		  
			  $evndt=explode("-",$this->data['review_date']);
			  $audit_review_date=$evndt[2]."-".$evndt[0]."-".$evndt[1];
		  
		}else{
			 $audit_review_date=''; 	
		}
		

		if($this->data['client_feedback_date']!=''){
		  
			  $clv=explode("-",$this->data['client_feedback_date']);
			  $client_feed_backdate= $clv[2]."-". $clv[0]."-". $clv[1];
		  
		}else{
			 $client_feed_backdate=''; 	
		}
	
		$auditClientData['AuditClient']['well']=$this->data['well']; 
		$auditClientData['AuditClient']['rig'] =$this->data['rig'];
		$auditClientData['AuditClient']['review_date'] =$audit_review_date;  
		$auditClientData['AuditClient']['clientreviewed']=$this->data['clientreviewed'];
		$auditClientData['AuditClient']['report_id']=$this->data['report_id'];
		$auditClientData['AuditClient']['clientreviewer']=$this->data['clientreviewer'];
		$auditClientData['AuditClient']['wellsiterep']=$this->data['wellsiterep'];
		$auditClientData['AuditClient']['client_feedback']=$this->data['client_feedback'];
		$auditClientData['AuditClient']['client_feedback_date']=$client_feed_backdate;
		if($this->AuditClient->save($auditClientData)){
		     echo $res;
		 }else{
		     echo 'fail';
		 }
		

	 exit;
       }
       function add_audit_view($id){
	
		 $this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
		 $this->grid_access();
		 $this->layout="after_adminlogin_template";
		 $reportdetail = $this->AuditReportMain->find('all', array('conditions' => array('AuditReportMain.id' =>base64_decode($id)),'recursive'=>2));
		  $this->Session->write('report_create',$reportdetail[0]['AuditReportMain']['created_by']);
		 $ed=explode("-",$reportdetail[0]['AuditReportMain']['event_date']);
		 $reportdetail[0]['AuditReportMain']['event_date_format']=date("d-M-y", mktime(0, 0, 0, $ed[1], $ed[2], $ed[0]));
		 if($reportdetail[0]['AuditReportMain']['closer_date']!='0000-00-00'){
				 $clsdt=explode("-",$reportdetail[0]['AuditReportMain']['closer_date']);
				 $closedt=$clsdt[2]."/".$clsdt[1]."/".$clsdt[0];
			}else{
				 $closedt='00/00/0000';	
			}
		if($reportdetail[0]['AuditReportMain']['client']==10){
			     $this->Session->write('audit_client', 'N/A');
		}else{
			     $this->Session->write('audit_client', 'Yes');
		}	
		 if($reportdetail[0]['AuditReportMain']['official']==1){
			$reportdetail[0]['AuditReportMain']['official_format']='Yes';
		 }elseif($reportdetail[0]['AuditReportMain']['official']==2){
		        $reportdetail[0]['AuditReportMain']['official_format']='No';
		 }
		 $reporter_detail= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$reportdetail[0]['AuditReportMain']['reporter'])));
		 if(count($reporter_detail)>0){
                           $reportdetail[0]['AuditReportMain']['reporter_name']=$reporter_detail[0]['AdminMaster']['first_name']." ".$reporter_detail[0]['AdminMaster']['last_name'];
		   }else{
		           $reportdetail[0]['AuditReportMain']['reporter_name']='';  	
		   }
		if(isset($reportdetail[0]['AuditClient'][0])){
			
			if($reportdetail[0]['AuditClient'][0]['clientreviewed']==1){
					$this->set('clientreviewed','N/A');
			}elseif($reportdetail[0]['AuditClient'][0]['clientreviewed']==2){
					$this->set('clientreviewed','N/A');
			}if($reportdetail[0]['AuditClient'][0]['clientreviewed']==3){
					$this->set('clientreviewed','Yes');
			}
			if($reportdetail[0]['AuditClient'][0]['review_date']!='0000-00-00'){
			       $rd=explode("-",$reportdetail[0]['AuditClient'][0]['review_date']);
			       $reportdetail[0]['AuditReportMain']['review_date_format']=date("d-M-y", mktime(0, 0, 0, $rd[1], $rd[2], $rd[0]));
			}else{
				$reportdetail[0]['AuditReportMain']['review_date_format']='';
			}
			if($reportdetail[0]['AuditClient'][0]['client_feedback_date']!='0000-00-00'){
			       $fd=explode("-",$reportdetail[0]['AuditClient'][0]['client_feedback_date']);
			       $reportdetail[0]['AuditReportMain']['client_feedback_date_format']=date("d-M-y", mktime(0, 0, 0, $fd[1], $fd[2], $fd[0]));
			}else{
			       $reportdetail[0]['AuditReportMain']['client_feedback_date_format']='';
			}
		}
		           
                if(isset($reportdetail[0]['AuditRemidial'][0])){

		        for($h=0;$h<count($reportdetail[0]['AuditRemidial']);$h++){
				$responsibility= $this->AdminMaster->find('all', array('conditions' =>array('AdminMaster.id'=>$reportdetail[0]['AuditRemidial'][$h]['remidial_responsibility']))); 
                                $reportdetail[0]['AuditRemidial'][$h]['responsibility_person']=$responsibility[0]['AdminMaster']['first_name']." ".$responsibility[0]['AdminMaster']['last_name'];
                                $reportdetail[0]['AuditRemidial'][$h]['Priority']['prority_colorcode']='<font color='.$reportdetail[0]['AuditRemidial'][$h]['Priority']['colorcoder'].'>'.$reportdetail[0]['AuditRemidial'][$h]['Priority']['type'].'</font>';
                                $explode_remedial=explode("-",$reportdetail[0]['AuditRemidial'][$h]['remidial_create']);     
                                $reportdetail[0]['AuditRemidial'][$h]['createRemidial']=date("d-M-y", mktime(0, 0, 0, $explode_remedial[1], $explode_remedial[2], $explode_remedial[0]));
	 
				if($reportdetail[0]['AuditRemidial'][$h]['remidial_closure_date']=='0000-00-00'){
					 $reportdetail[0]['AuditRemidial'][$h]['closeDate']='';
					 $reportdetail[0]['AuditRemidial'][$h]['remidial_closer_summary']='';
			       
				}elseif($reportdetail[0]['AuditRemidial'][$h]['remidial_closure_date']!='0000-00-00'){
					$closerDate=explode("-",$reportdetail[0]['AuditRemidial'][$h]['remidial_closure_date']);

					$reportdetail[0]['AuditRemidial'][$h]['closeDate']=date("d-M-y", mktime(0, 0, 0, $closerDate[1], $closerDate[2], $closerDate[0])); 
			        }

                                if($reportdetail[0]['AuditRemidial'][$h]['remidial_closure_date']=='0000-00-00'){
						  $reportdetail[0]['AuditRemidial'][$h]['closeDate']='';
						  $reportdetail[0]['AuditRemidial'][$h]['remidial_closer_summary']='';
					
				}elseif($reportdetail[0]['AuditRemidial'][$h]['remidial_closure_date']!='0000-00-00'){
						 $closerDate=explode("-",$reportdetail[0]['AuditRemidial'][$h]['remidial_closure_date']);
                                                 $reportdetail[0]['AuditRemidial'][$h]['closeDate']=date("d-M-y", mktime(0, 0, 0, $closerDate[1], $closerDate[2], $closerDate[0])); 
                                }
                        
                                
                               if($reportdetail[0]['AuditRemidial'][$h]['remidial_reminder_data']!=''){
						$rrd=explode(" ",$reportdetail[0]['AuditRemidial'][$h]['remidial_reminder_data']);
						$exrrd=explode("/",$rrd[0]);
						$reportdetail[0]['AuditRemidial'][$h]['rem_rim_data']=date("d-M-y", mktime(0, 0, 0, $exrrd[1], $exrrd[0], $exrrd[2])); 
                                }else{
						$reportdetail[0]['AuditRemidial'][$h]['rem_rim_data']='';
                                 }

                                if($reportdetail[0]['AuditRemidial'][$h]['remidial_closure_target']!=''){
						$rcd=explode(" ",$reportdetail[0]['AuditRemidial'][$h]['remidial_closure_target']);
						$exrcd=explode("/",$rcd[0]);
						$reportdetail[0]['AuditRemidial'][$h]['rem_cls_trgt']=date("d-M-y", mktime(0, 0, 0, $exrcd[1], $exrcd[0], $exrcd[2])); 
                                }else{
                                                $reportdetail[0]['AuditRemidial'][$h]['rem_cls_trgt']='';
                                }
		           }
                  }
		 $this->set('id',base64_decode($id));
		 $this->set('closer_date',$closedt); 	
	         $this->set('reportdetail',$reportdetail);
       }
              function print_view($id){
	
		 $this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
		 $this->grid_access();
		 $this->layout="ajax";
		 $reportdetail = $this->AuditReportMain->find('all', array('conditions' => array('AuditReportMain.id' =>base64_decode($id)),'recursive'=>2));
		 $ed=explode("-",$reportdetail[0]['AuditReportMain']['event_date']);
		 $reportdetail[0]['AuditReportMain']['event_date_format']=date("d-M-y", mktime(0, 0, 0, $ed[1], $ed[2], $ed[0]));
		 if($reportdetail[0]['AuditReportMain']['closer_date']!='0000-00-00'){
				 $clsdt=explode("-",$reportdetail[0]['AuditReportMain']['closer_date']);
				 $closedt=$clsdt[2]."/".$clsdt[1]."/".$clsdt[0];
			}else{
				 $closedt='00/00/0000';	
			}
		 if($reportdetail[0]['AuditReportMain']['official']==1){
			$reportdetail[0]['AuditReportMain']['official_format']='Yes';
		 }elseif($reportdetail[0]['AuditReportMain']['official']==2){
		        $reportdetail[0]['AuditReportMain']['official_format']='No';
		 }
		 $reporter_detail= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$reportdetail[0]['AuditReportMain']['reporter'])));
		 if(count($reporter_detail)>0){
                           $reportdetail[0]['AuditReportMain']['reporter_name']=$reporter_detail[0]['AdminMaster']['first_name']." ".$reporter_detail[0]['AdminMaster']['last_name'];
		   }else{
		           $reportdetail[0]['AuditReportMain']['reporter_name']='';  	
		   }
		if(isset($reportdetail[0]['AuditClient'][0])){
			
			if($reportdetail[0]['AuditClient'][0]['clientreviewed']==1){
					$this->set('clientreviewed','N/A');
			}elseif($reportdetail[0]['AuditClient'][0]['clientreviewed']==2){
					$this->set('clientreviewed','N/A');
			}if($reportdetail[0]['AuditClient'][0]['clientreviewed']==3){
					$this->set('clientreviewed','Yes');
			}
			if($reportdetail[0]['AuditClient'][0]['review_date']!='0000-00-00'){
			       $rd=explode("-",$reportdetail[0]['AuditClient'][0]['review_date']);
			       $reportdetail[0]['AuditReportMain']['review_date_format']=date("d-M-y", mktime(0, 0, 0, $rd[1], $rd[2], $rd[0]));
			}else{
				$reportdetail[0]['AuditReportMain']['review_date_format']='';
			}
			if($reportdetail[0]['AuditClient'][0]['client_feedback_date']!='0000-00-00'){
			       $fd=explode("-",$reportdetail[0]['AuditClient'][0]['client_feedback_date']);
			       $reportdetail[0]['AuditReportMain']['client_feedback_date_format']=date("d-M-y", mktime(0, 0, 0, $fd[1], $fd[2], $fd[0]));
			}else{
			       $reportdetail[0]['AuditReportMain']['client_feedback_date_format']='';
			}
		}
		           
                if(isset($reportdetail[0]['AuditRemidial'][0])){

		        for($h=0;$h<count($reportdetail[0]['AuditRemidial']);$h++){
				$responsibility= $this->AdminMaster->find('all', array('conditions' =>array('AdminMaster.id'=>$reportdetail[0]['AuditRemidial'][$h]['remidial_responsibility']))); 
                                $reportdetail[0]['AuditRemidial'][$h]['responsibility_person']=$responsibility[0]['AdminMaster']['first_name']." ".$responsibility[0]['AdminMaster']['last_name'];
                                $reportdetail[0]['AuditRemidial'][$h]['Priority']['prority_colorcode']='<font color='.$reportdetail[0]['AuditRemidial'][$h]['Priority']['colorcoder'].'>'.$reportdetail[0]['AuditRemidial'][$h]['Priority']['type'].'</font>';
                                $explode_remedial=explode("-",$reportdetail[0]['AuditRemidial'][$h]['remidial_create']);     
                                $reportdetail[0]['AuditRemidial'][$h]['createRemidial']=date("d-M-y", mktime(0, 0, 0, $explode_remedial[1], $explode_remedial[2], $explode_remedial[0]));
	 
				if($reportdetail[0]['AuditRemidial'][$h]['remidial_closure_date']=='0000-00-00'){
					 $reportdetail[0]['AuditRemidial'][$h]['closeDate']='';
					 $reportdetail[0]['AuditRemidial'][$h]['remidial_closer_summary']='';
			       
				}elseif($reportdetail[0]['AuditRemidial'][$h]['remidial_closure_date']!='0000-00-00'){
					$closerDate=explode("-",$reportdetail[0]['AuditRemidial'][$h]['remidial_closure_date']);

					$reportdetail[0]['AuditRemidial'][$h]['closeDate']=date("d-M-y", mktime(0, 0, 0, $closerDate[1], $closerDate[2], $closerDate[0])); 
			        }

                                if($reportdetail[0]['AuditRemidial'][$h]['remidial_closure_date']=='0000-00-00'){
						  $reportdetail[0]['AuditRemidial'][$h]['closeDate']='';
						  $reportdetail[0]['AuditRemidial'][$h]['remidial_closer_summary']='';
					
				}elseif($reportdetail[0]['AuditRemidial'][$h]['remidial_closure_date']!='0000-00-00'){
						 $closerDate=explode("-",$reportdetail[0]['AuditRemidial'][$h]['remidial_closure_date']);
                                                 $reportdetail[0]['AuditRemidial'][$h]['closeDate']=date("d-M-y", mktime(0, 0, 0, $closerDate[1], $closerDate[2], $closerDate[0])); 
                                }
                        
                                
                               if($reportdetail[0]['AuditRemidial'][$h]['remidial_reminder_data']!=''){
						$rrd=explode(" ",$reportdetail[0]['AuditRemidial'][$h]['remidial_reminder_data']);
						$exrrd=explode("/",$rrd[0]);
						$reportdetail[0]['AuditRemidial'][$h]['rem_rim_data']=date("d-M-y", mktime(0, 0, 0, $exrrd[1], $exrrd[0], $exrrd[2])); 
                                }else{
						$reportdetail[0]['AuditRemidial'][$h]['rem_rim_data']='';
                                 }

                                if($reportdetail[0]['AuditRemidial'][$h]['remidial_closure_target']!=''){
						$rcd=explode(" ",$reportdetail[0]['AuditRemidial'][$h]['remidial_closure_target']);
						$exrcd=explode("/",$rcd[0]);
						$reportdetail[0]['AuditRemidial'][$h]['rem_cls_trgt']=date("d-M-y", mktime(0, 0, 0, $exrcd[1], $exrcd[0], $exrcd[2])); 
                                }else{
                                                $reportdetail[0]['AuditRemidial'][$h]['rem_cls_trgt']='';
                                }
		           }
                  }
		 $this->set('id',base64_decode($id));
		 $this->set('closer_date',$closedt); 	
	         $this->set('reportdetail',$reportdetail);
       }
       function add_audit_remidial($report_id=null,$remidial_id=null){
		 $this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
		 $this->grid_access();
		 $this->layout="after_adminlogin_template";
		 $priority = $this->Priority->find('all');
		 $userDetail = $this->AdminMaster->find('all');
		 $this->set('priority',$priority);
		 $this->set('responsibility',$userDetail);
		 $this->set('created_by',$_SESSION['adminData']['AdminMaster']['first_name']." ".$_SESSION['adminData']['AdminMaster']['last_name']);
		 $reportdetail = $this->AuditReportMain->find('all', array('conditions' => array('AuditReportMain.id' =>base64_decode($report_id))));
		 $countRem= $this->AuditRemidial->find('count',array('conditions'=>array('AuditRemidial.report_no'=>base64_decode($report_id))));      
      		 $this->set('report_number',$reportdetail[0]['AuditReportMain']['report_no']);      
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
			
		         $remidialData= $this->AuditRemidial->find('all',array('conditions'=>array('AuditRemidial.id'=>base64_decode($remidial_id))));
			 $this->set('countRem',  $remidialData[0]['AuditRemidial']['remedial_no']);
			 $this->set('id', base64_decode($remidial_id));
			 $rempriority = $this->Priority->find('all',array('conditions'=>array('id'=>base64_decode($remidial_id))));
			 $this->set('heading', 'Edit Remedial Action Item' );
			 $this->set('button', 'Update');
			 $remidialcreate=explode("-",$remidialData[0]['AuditRemidial']['remidial_create']);
			 $this->set('remidial_create',$remidialcreate[1].'-'.$remidialcreate[2].'-'.$remidialcreate[0]);
			 $this->set('remidial_summery',$remidialData[0]['AuditRemidial']['remidial_summery']);
			 
			 $this->set('remidial_action',$remidialData[0]['AuditRemidial']['remidial_action']);
			 $this->set('remidial_priority',$remidialData[0]['AuditRemidial']['remidial_priority']);
			 $this->set('remidial_closure_target',$remidialData[0]['AuditRemidial']['remidial_closure_target']);
			 $this->set('remidial_responsibility',$remidialData[0]['AuditRemidial']['remidial_responsibility']);
			if($remidialData[0]['AuditRemidial']['remidial_closure_date']!='0000-00-00'){
				$closerdate=explode("-",$remidialData[0]['AuditRemidial']['remidial_closure_date']);
				$this->set('remidial_closure_date',$closerdate[1].'/'.$closerdate[2].'/'.$closerdate[0]);
				$this->set('remidial_closer_summary',$remidialData[0]['AuditRemidial']['remidial_closer_summary']);
				$this->set('remidial_button_style','style="display:none"');
			}else{
				$this->set('remidial_closer_summary',' ');
				$this->set('remidial_closure_date','');
				$this->set('remidial_button_style','style="display:block"');
			}
			 
			 $this->set('remidial_style','style="display:block"');
			 $this->set('remidial_reminder_data',$remidialData[0]['AuditRemidial']['remidial_reminder_data']);
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
				       $remidialArray['AuditRemidial']['id']=$this->data['add_report_remidial_form']['id'];
				}else{
					$res='add';
				 }
			   $remidialdate=explode("-",$this->data['remidial_create']);	       
		   	   $remidialArray['AuditRemidial']['remidial_create']=$remidialdate[2].'-'.$remidialdate[0].'-'.$remidialdate[1];
			   $remidialArray['AuditRemidial']['remidial_createby']=$_SESSION['adminData']['AdminMaster']['id']; 
		           $remidialArray['AuditRemidial']['report_no']=$this->data['report_no'];
			   $remidialArray['AuditRemidial']['remedial_no']=$this->data['countRem'];
			   $prority=explode("~",$this->data['remidial_priority']);
			   $remidialArray['AuditRemidial']['remidial_priority']=$prority[0];
			   $remidialArray['AuditRemidial']['remidial_responsibility']=$this->data['responsibility'];
			   $remidialArray['AuditRemidial']['remidial_summery']=$this->data['add_report_remidial_form']['remidial_summery'];
			   $remidialArray['AuditRemidial']['remidial_closer_summary']=$this->data['add_report_remidial_form']['remidial_closer_summary'];
			   $remidialArray['AuditRemidial']['remidial_action']=$this->data['add_report_remidial_form']['remidial_action'];
			   $remidialArray['AuditRemidial']['remidial_reminder_data']=$this->data['add_report_remidial_form']['remidial_reminder_data'];
			   $remidialArray['AuditRemidial']['remidial_closure_target']=$this->data['add_report_remidial_form']['remidial_closure_target'];
			   if(isset($this->data['remidial_closure_date']) && $this->data['remidial_closure_date']!=''){
			     $closerdate=explode("-",$this->data['remidial_closure_date']);
			     $remidialArray['AuditRemidial']['remidial_closure_date']=$closerdate[0].'-'.$closerdate[1].'-'.$closerdate[2];
			   }else{
			     $remidialArray['AuditRemidial']['remidial_closure_date']=' ';
			   }
			     
			     
			     $createON = $remidialArray['AuditRemidial']['remidial_create'].' 00:00:00';
		             $explodeCTR=explode(" ",$remidialArray['AuditRemidial']['remidial_closure_target']);
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
				
				
				
				   $deleteREL = "DELETE FROM `remidial_email_lists` WHERE  `remedial_no` = {$remidialArray['AuditRemidial']['remedial_no']} AND `report_id` = {$remidialArray['AuditRemidial']['report_no']} AND `report_type`='audit'";
                                   $deleteval=$this->RemidialEmailList->query($deleteREL);
			     if($this->data['remidial_closure_date']==''){ 	
					for($d=0;$d<count($dateHolder);$d++){
					     
							$remidialEmailList=array();
							$userdetail = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$this->data['responsibility'])));
							$fullname=$userdetail[0]['AdminMaster']['first_name'].' '.$userdetail[0]['AdminMaster']['last_name'];
							$to=$userdetail[0]['AdminMaster']['admin_email'];
							$remidialEmailList['RemidialEmailList']['report_id']=$remidialArray['AuditRemidial']['report_no'];
							$remidialEmailList['RemidialEmailList']['remedial_no']=$remidialArray['AuditRemidial']['remedial_no'];
							$remidialEmailList['RemidialEmailList']['report_type']='audit';
							$remidialEmailList['RemidialEmailList']['email']=$to;
							$remidialEmailList['RemidialEmailList']['status']='N';
							$remidialEmailList['RemidialEmailList']['email_date']=$dateHolder[$d];
							$remidialEmailList['RemidialEmailList']['send_to']=$userdetail[0]['AdminMaster']['id'];
							
							$this->RemidialEmailList->create();
							$this->RemidialEmailList->save($remidialEmailList);
						
					}  
		 
			     }
					     

			 if($this->AuditRemidial->save($remidialArray)){
				     echo $res.'~audit';
		                }else{
				     echo 'fail';
			        }
			        
               

			exit;
			
		}
		
		
		function report_audit_remidial_list($id=null){
			$this->_checkAdminSession();
			$this->_getRoleMenuPermission();
			$this->grid_access();
			$this->layout="after_adminlogin_template";
			$this->set('report_no',base64_decode($id));
			$reportdetail = $this->AuditReportMain->find('all', array('conditions' => array('AuditReportMain.id' =>base64_decode($id))));
			$this->set('id',base64_decode($id));
			

			
			$this->set('report_val',$id);
			$this->set('report_number',$reportdetail[0]['AuditReportMain']['report_no']);
			if(!empty($this->request->data))
			{
				$action = $this->request->data['AuditRemidial']['action'];
				$this->set('action',$action);
				$limit = $this->request->data['AuditRemidial']['limit'];
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

			
			$condition="AuditRemidial.report_no = $report_id AND AuditRemidial.isdeleted = 'N'";
			
			if(isset($_REQUEST['filter'])){
				switch($this->data['filter']){
				case'create_on':
				    $explodemonth=explode('/',$this->data['value']);
				    $day=$explodemonth[0];
				    $month=date('m', strtotime($explodemonth[1]));
				    $year="20$explodemonth[2]";
				    $createon=$year."-".$month."-".$day;
				    $condition .= "AND AuditRemidial.remidial_create ='".$createon."'";	
				break;
				case'remidial_create_name':
				     $spliNAME=explode(" ",$_REQUEST['value']);
				     $spliLname=$spliNAME[count($spliNAME)-1];
				     $spliFname=$spliNAME[0];
				     $adminCondition="AdminMaster.first_name like '%".$spliFname."%' AND AdminMaster.last_name like '%".$spliLname."%'";
				     $userDetail = $this->AdminMaster->find('all',array('conditions'=>$adminCondition));
				     $addimid=$userDetail[0]['AdminMaster']['id'];
				     $condition .= "AND AuditRemidial.remidial_createby ='".$addimid."'";
					
				break;
				case'responsibility_person':
				     $spliNAME=explode(" ",$_REQUEST['value']);
				     $spliLname=$spliNAME[count($spliNAME)-1];
				     $spliFname=$spliNAME[0];
				     $adminCondition="AdminMaster.first_name like '%".$spliFname."%' AND AdminMaster.last_name like '%".$spliLname."%'";
				     $userDetail = $this->AdminMaster->find('all',array('conditions'=>$adminCondition));
				     $addimid=$userDetail[0]['AdminMaster']['id'];
				     $condition .= "AND AuditRemidial.remidial_responsibility ='".$addimid."'";	
				break;
				case'remidial_priority_name':
				     $priorityCondition = "Priority.type='".trim($_REQUEST['value'])."'";
				     $priorityDetail = $this->Priority->find('all',array('conditions'=>$priorityCondition));
				     $condition .= "AND AuditRemidial.remidial_priority ='".$priorityDetail[0]['Priority']['id']."'";	
				break;
				}
			}
			
			
			$limit=null;
			if($_REQUEST['limit'] == 'all'){
						
				
			}else{
				$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
			}
	
			 $adminArray = array();	
			 $count = $this->AuditRemidial->find('count' ,array('conditions' => $condition));
			 $adminA = $this->AuditRemidial->find('all',array('conditions' => $condition,'order' => 'AuditRemidial.id DESC','limit'=>$limit));

			$i = 0;
			foreach($adminA as $rec)
			{			
				if($rec['AuditRemidial']['isblocked'] == 'N')
				{
					$adminA[$i]['AuditRemidial']['blockHideIndex'] = "true";
					$adminA[$i]['AuditRemidial']['unblockHideIndex'] = "false";
					$adminA[$i]['AuditRemidial']['isdeletdHideIndex'] = "true";
				}else{
					$adminA[$i]['AuditRemidial']['blockHideIndex'] = "false";
					$adminA[$i]['AuditRemidial']['unblockHideIndex'] = "true";
					$adminA[$i]['AuditRemidial']['isdeletdHideIndex'] = "false";
				}
				
			    $create_on=explode("-",$rec['AuditRemidial']['remidial_create']);
			    $adminA[$i]['AuditRemidial']['create_on']=date("d/M/y", mktime(0, 0, 0, $create_on[1], $create_on[2], $create_on[0]));
			    $lastupdated=explode(" ", $rec['AuditRemidial']['modified']);
			    $lastupdatedate=explode("-",$lastupdated[0]);
			    $adminA[$i]['AuditRemidial']['lastupdate']=date("d/M/y", mktime(0, 0, 0, $lastupdatedate[1], $lastupdatedate[2], $lastupdatedate[0]));
			    $createdate=explode("-",$rec['AuditRemidial']['remidial_create']);
			    $adminA[$i]['AuditRemidial']['createRemidial']=date("d/M/y", mktime(0, 0, 0, $createdate[1], $createdate[2], $createdate[0]));
			    $adminA[$i]['AuditRemidial']['remidial_priority_name'] ='<font color='.$rec['Priority']['colorcoder'].'>'.$rec['Priority']['type'].'</font>';
			    $adminA[$i]['AuditRemidial']['remidial_create_name'] = $rec['AdminMaster']['first_name']." ".$rec['AdminMaster']['last_name'];
			    $user_detail_reponsibilty= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$rec['AuditRemidial']['remidial_responsibility'])));
			    $adminA[$i]['AuditRemidial']['responsibility_person']=$user_detail_reponsibilty[0]['AdminMaster']['first_name']."  ".$user_detail_reponsibilty[0]['AdminMaster']['last_name'];
			    if($rec['AuditRemidial']['remidial_closure_date']!='0000-00-00'){
			              $rem_cls_date=explode("-",$rec['AuditRemidial']['remidial_closure_date']);
			              $adminA[$i]['AuditRemidial']['closure']=date("d-M-y", mktime(0, 0, 0, $rem_cls_date[1], $rem_cls_date[2], $rem_cls_date[0]));
			    }else{
			              $adminA[$i]['AuditRemidial']['closure']='';	
			    }
			    $i++;
			}
			if($count==0){
			       $adminArray=array();
		        }else{
			       $adminArray = Set::extract($adminA, '{n}.AuditRemidial');
		        }
			 
			  $this->set('total', $count); 
			  $this->set('admins', $adminArray); 
			 
		}
		 
	    function remidial_block($id = null)
	     {

	     
	                if(!$id)
			{
		           $this->redirect(array('action'=>report_audit_list), null, true);
			}
		       else
		       
		       
		       {
			  
	                 	$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['AuditRemidial']['id'] = $id;
					  $this->request->data['AuditRemidial']['isblocked'] = 'Y';
					  $this->AuditRemidial->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	     
	      }
	      
	      function remidial_unblock($id = null)
	     {
                  
			if(!$id)
			{
		           $this->redirect(array('action'=>report_audit_list), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['AuditRemidial']['id'] = $id;
					  $this->request->data['AuditRemidial']['isblocked'] = 'N';
					  $this->AuditRemidial->save($this->request->data,false);				
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
				
				 $remidialData= $this->AuditRemidial->find('all',array('conditions'=>array('AuditRemidial.id'=>$id)));
				 $deleteHsse_remidials_email = "DELETE FROM `remidial_email_lists` WHERE `report_id` = {$remidialData[0]['AuditRemidial']['report_no']}  AND  `remedial_no` = {$remidialData[0]['AuditRemidial']['remedial_no']} AND `report_type`='audit'";
                                 $dHRem=$this->RemidialEmailList->query($deleteHsse_remidials_email);
				 $deleteHsse_remidials = "DELETE FROM `audit_remidials` WHERE `id` = {$id}";
                                 $dHR=$this->AuditRemidial->query($deleteHsse_remidials);
					  
					  
			       }
			       echo 'ok';
			      
			   
			       exit;			 
		     		 
		     
	      }
	       	public function report_audit_attachment_list($id=null){
			$this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();   
      			$this->layout="after_adminlogin_template";
		
		        $reportdetail = $this->AuditReportMain->find('all', array('conditions' => array('AuditReportMain.id' =>base64_decode($id))));
			$this->set('report_number',$reportdetail[0]['AuditReportMain']['report_no']);	       
	                $this->set('id',base64_decode($id));
				if(!empty($this->request->data))
				{
					$action = $this->request->data['AuditAttachment']['action'];
					$this->set('action',$action);
					$limit = $this->request->data['AuditAttachment']['limit'];
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
	
		
		$condition="AuditAttachment.report_id = $report_id AND    AuditAttachment.isdeleted = 'N'";
		
		
                if(isset($_REQUEST['filter'])){
			$condition .= "AND ucase(AuditAttachment.".$_REQUEST['filter'].") like '".$_REQUEST['value']."%'";	
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			//$condition .= " order by Category.id DESC";
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}

		//$count = $this->HsseIncident->find('count' ,array('conditions' => $condition)); 
		$adminArray = array();	
		 $count = $this->AuditAttachment->find('count' ,array('conditions' => $condition));
		 $adminA = $this->AuditAttachment->find('all',array('conditions' => $condition,'order' => 'AuditAttachment.id DESC','limit'=>$limit));
		  
		$i = 0;
		foreach($adminA as $rec)
		{
			
			
			if($rec['AuditAttachment']['isblocked'] == 'N')
			{
				$adminA[$i]['AuditAttachment']['blockHideIndex'] = "true";
				$adminA[$i]['AuditAttachment']['unblockHideIndex'] = "false";
				$adminA[$i]['AuditAttachment']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['AuditAttachment']['blockHideIndex'] = "false";
				$adminA[$i]['AuditAttachment']['unblockHideIndex'] = "true";
				$adminA[$i]['AuditAttachment']['isdeletdHideIndex'] = "false";
			}
			$adminA[$i]['AuditAttachment']['image_src']=$this->attachment_list('AuditAttachment',$rec);
		    $i++;
		}

		  $this->set('total', $count);  //send total to the view
		  
		  if($count==0){
			$adminArray=array();
	             }else{
			$adminArray = Set::extract($adminA, '{n}.AuditAttachment');
		  }
		  
		  $this->set('admins', $adminArray);  //send products to the view
		  //$this->set('status', $action);
	}
	      
	          function add_audit_attachment($reoprt_id=null,$attachment_id=null){
		        $this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();   
		        $this->layout="after_adminlogin_template";
		
			if($attachment_id!=''){
				$attchmentdetail = $this->AuditAttachment->find('all', array('conditions' => array('AuditAttachment.id' =>base64_decode($attachment_id))));
			      $imagepath=$this->file_edit_list($attchmentdetail[0]['AuditAttachment']['file_name'],'AuditAttachment',$attchmentdetail);
				$this->set('heading','Update File');
			        $this->set('attachment_id',$attchmentdetail[0]['AuditAttachment']['id']);
			        $this->set('description',$attchmentdetail[0]['AuditAttachment']['description']);
			        $this->set('button','Update');
			        $this->set('imagepath',$imagepath);
			        $this->set('imagename',$attchmentdetail[0]['AuditAttachment']['file_name']);
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
	              	$reportdetail = $this->AuditReportMain->find('all', array('conditions' => array('AuditReportMain.id' =>base64_decode($reoprt_id))));
			$this->set('report_number',$reportdetail[0]['AuditReportMain']['report_no']);        
			
	       }
	       
       
	       
	       
	          function uploadimage($upparname=NULL,$deleteImageName=NULL){
		      
	                $this->layout="ajax";
			$allowed_image =$this->image_upload_type();
			$this->upload_image_func($upparname,'Audits',$allowed_image);
			exit;
	       }
	           function  auditattachmentprocess(){
		         $this->layout="ajax";
	
		         $attachmentArray=array();
			 
		         if($this->data['Audits']['id']!=0){
				$res='update';
				$attachmentArray['AuditAttachment']['id']=$this->data['Audits']['id'];
			 }else{
				 $res='add';
			  }
				
		   	   $attachmentArray['AuditAttachment']['description']=$this->data['attachment_description'];
			   $attachmentArray['AuditAttachment']['file_name']=$this->data['hiddenFile']; 
		           $attachmentArray['AuditAttachment']['report_id']=$this->data['report_id'];
			   if($this->AuditAttachment->save($attachmentArray)){
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
		           $this->redirect(array('action'=>report_audit_list), null, true);
			}
		       else
		        
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['AuditAttachment']['id'] = $id;
					  $this->request->data['AuditAttachment']['isblocked'] = 'Y';
					  $this->AuditAttachment->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	      function attachment_unblock($id = null)
	     {
          
			if(!$id)
			{
		           $this->redirect(array('action'=>report_audit_list), null, true);
			}
		       else
		       {
			
				
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['AuditAttachment']['id'] = $id;
					  $this->request->data['AuditAttachment']['isblocked'] = 'N';
					  $this->AuditAttachment->save($this->request->data,false);				
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
				
					  $this->request->data['AuditAttachment']['id'] = $id;
					  $this->request->data['AuditAttachment']['isdeleted'] = 'Y';
					  $this->AuditAttachment->save($this->request->data,false);
					  
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
		          $sqlinkDetail = $this->AuditLink->find('all', array('conditions' => array('AuditLink.report_id' =>base64_decode($this->data['report_no']),'AuditLink.link_report_id' =>$explode_id_type[1],'AuditLink.type' =>$explode_id_type[0])));
			 if(count($sqlinkDetail)>0){
				echo 'avl';
				
			 }else{
			 $linkArray['AuditLink']['type']=$explode_id_type[0];
			 $linkArray['AuditLink']['report_id']=base64_decode($this->data['report_no']);
			 $linkArray['AuditLink']['link_report_id']=$explode_id_type[1];
			 $linkArray['AuditLink']['user_id']=$_SESSION['adminData']['AdminMaster']['id'];
			 
			 if($this->AuditLink->save($linkArray)){
				 echo 'ok';
			 }else{
			         echo 'fail';
			 }
			
			 
			 }
			   
			   
			
                        
		   exit;
		
	}
	  public function report_audit_link_list($id=null,$typSearch){
		
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
                $this->grid_access();		
		$this->layout="after_adminlogin_template";
		$sqlinkDetail = $this->AuditLink->find('all', array('conditions' => array('AuditLink.report_id' =>base64_decode($id))));
		$reportdetail = $this->AuditReportMain->find('all', array('conditions' => array('AuditReportMain.id' =>base64_decode($id))));            
		$this->set('report_number',$reportdetail[0]['AuditReportMain']['report_no']);
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
	
                $condition="AuditLink.report_id =".base64_decode($report_id);
		
                if(isset($_REQUEST['filter'])){
	             $link_type=explode("~",$this->link_search($_REQUEST['value']));
		     
		     $condition .= " AND AuditLink.link_report_id ='".$link_type[0]."' AND AuditLink.type ='".$link_type[1]."'";
		     
		}

	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}

		 $adminArray = array();
		  if($filterTYPE!='all'){
			
		    $condition .= " AND AuditLink.type ='".$filterTYPE."'";
		
		 }
		 $count = $this->AuditLink->find('count' ,array('conditions' => $condition));
		 $adminA = $this->AuditLink->find('all',array('conditions' => $condition,'order' => 'AuditLink.id DESC','limit'=>$limit));
		 

		  
		$i = 0;
		foreach($adminA as $rec)
		{
	
			if($rec['AuditLink']['isblocked'] == 'N')
			{
				$adminA[$i]['AuditLink']['blockHideIndex'] = "true";
				$adminA[$i]['AuditLink']['unblockHideIndex'] = "false";
				$adminA[$i]['AuditLink']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['AuditLink']['blockHideIndex'] = "false";
				$adminA[$i]['AuditLink']['unblockHideIndex'] = "true";
				$adminA[$i]['AuditLink']['isdeletdHideIndex'] = "false";
			}
			$link_type=$this->link_grid($adminA[$i],$rec['AuditLink']['type'],'AuditLink',$rec);
		        $explode_link_type=explode("~",$link_type);
		        $adminA[$i]['AuditLink']['link_report_no']=$explode_link_type[0];
		        $adminA[$i]['AuditLink']['type_name']=$explode_link_type[1];

		
			
		    $i++;
		}
		
		  if($count==0){
			$adminArray=array();
		  }else{
			 $adminArray = Set::extract($adminA, '{n}.AuditLink');
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
		           $this->redirect(array('action'=>'report_audit_list'), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['AuditLink']['id'] = $id;
					  $this->request->data['AuditLink']['isblocked'] = 'Y';
					  $this->AuditLink->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	      function link_unblock($id = null)
	     {
                        $this->_checkAdminSession();
			if(!$id)
			{
		           $this->redirect(array('action'=>'report_audit_list'), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['AuditLink']['id'] = $id;
					  $this->request->data['AuditLink']['isblocked'] = 'N';
					  $this->AuditLink->save($this->request->data,false);				
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
					  
					  $deleteproperty = "DELETE FROM `audit_links` WHERE `id` = {$id}";
                                          $deleteval=$this->AuditLink->query($deleteproperty);
					  
			       }
		
			       echo 'ok';
			       exit;
		      }else{
			 $this->redirect(array('action'=>'report_audit_list'), null, true);
		      }
			       
			      
			       
	
	      }
	      
	      
	      function audit_remedila_email_list($id=null){
			$this->_checkAdminSession();
			$this->_getRoleMenuPermission();
			$this->grid_access();
			$this->layout="after_adminlogin_template";
			$this->set('report_no',base64_decode($id));
			$reportdetail = $this->AuditReportMain->find('all', array('conditions' => array('AuditReportMain.id' =>base64_decode($id))));
			$this->set('id',base64_decode($id));
			

			
			$this->set('report_val',$id);
			$this->set('report_number',$reportdetail[0]['AuditReportMain']['report_no']);
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
		
			$condition="RemidialEmailList.report_id = $report_id AND report_type='audit'";
			
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
				
			    $remC= $this->AuditRemidial->find('all', array('conditions' => array('AuditRemidial.report_no' =>$rec['RemidialEmailList']['report_id'],'AuditRemidial.remedial_no' =>$rec['RemidialEmailList']['remedial_no'])));
			    
			    $rrd=explode(" ",$remC[0]['AuditRemidial']['remidial_reminder_data']);
			    $rrdE=explode("/",$rrd[0]);
			    $adminA[$i]['RemidialEmailList']['reminder_data']=date("d/M/y", mktime(0, 0, 0, $rrdE[1], $rrdE[0], $rrdE[2]));
			    
			    $create_on=explode("-",$remC[0]['AuditRemidial']['remidial_create']);
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
			 $remidialData= $this->AuditRemidial->find('all',array('conditions'=>array('AuditRemidial.report_no'=>$report_id,'AuditRemidial.remedial_no'=>$remedial_no)));
			 $reportData= $this->AuditReportMain->find('all',array('conditions'=>array('AuditReportMain.id'=>$remidialData[0]['AuditRemidial']['report_no'])));
			 $userData= $this->AdminMaster->find('all',array('conditions'=>array('AdminMaster.id'=>$remidialData[0]['AuditRemidial']['remidial_responsibility'])));
			 $this->set('fullname',$userData[0]['AdminMaster']['first_name']." ".$userData[0]['AdminMaster']['last_name']);
			 $this->set('report_no',$reportData[0]['AuditReportMain']['report_no']);
			 $this->set('remidialData',$remidialData);
		
		}
  
}

?>
