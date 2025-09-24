<?php

namespace App\Controller;

use App\Controller\AppController;

class ReportsController extends AppController
{
	
	public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
		// load models explicitly instead of $uses
        $this->loadModel('Reports');
        $this->loadModel('AdminMasters');
        $this->loadModel('RoleMasters');
    }

	// public $name = 'Reports';
	// public $uses = array('Report','RemidialEmailList','DocumentMain','JobLink','SuggestionMain','JhaMain','LessonMain','JnReportMain','AuditReportMain','SqReportMain','JobReportMain','Incident','BusinessType','Fieldlocation','Client','HsseLink','IncidentLocation','IncidentSeverity','Country','AdminMaster','HsseClient','HsseIncident','HssePersonnel','RolePermission','RoleMaster','AdminMenu','IncidentCategory','Loss','IncidentSubCategory','Residual','HsseAttachment','HsseRemidial','Priority','ImmediateCause','ImmediateSubCause','HsseInvestigation','RootCause','HsseInvestigationData','Potential','HsseClientfeedback');
	// public $helpers = array('Html','Form','Session','Js');
	// public $components = array('RequestHandler', 'Cookie', 'Resizer');

	
	/*public function reportHsseList(){
		
		dd("sdfgjkl");

		debug('reportHsseList() called');
   		die(); 
		$this->_checkAdminSession();
	        $this->_getRoleMenuPermission();
		$this->grid_access();
		$this->report_hsse_link();
		
		$this->viewBuilder()->setLayout('after_adminlogin_template');
	
		$data = $this->request->getData();
		if(!empty($data))
		{
			$action = $data['Report']['action'] ?? 'all';
			$this->set('action',$action);
			$limit = $data['Report']['limit'] ?? 50;
			$this->set('limit', $limit);
		}
		else
		{
			$action = 'all';
			$this->set('action','all');
			$limit = 50;
			$this->set('limit', $limit);
		}
	
		$session = $this->request->getSession();
		$session->write('limit', $limit);
			
		$session->delete('filter');
		$session->delete('value');
		$session->delete('idBollen');
		$idBoolen=array();
		
		// Load Reports model
		$this->loadModel('Reports');
		
		$adminA = $this->Reports->find()
			->where(['Reports.isdeleted' => 'N'])
			->order(['Reports.id' => 'DESC'])
			->limit($limit)
			->all()
			->toArray();
			
		$adminData = $session->read('adminData');
		for($i=0;$i<count($adminA);$i++){
			if(($adminData['AdminMaster']['id']==$adminA[$i]['created_by']) || ($adminData['RoleMaster']['id']==1)){
			   array_push($idBoolen,1);	
				
			}else{
			  array_push($idBoolen,0);
			}
			
		}
		$session->write('idBollen',$idBoolen);
		
		// Pass data to view
		$this->set('reports', $adminA);
	}*/
	public function reportHsseList()
    {
        $this->_checkAdminSession();      // assuming this exists in AppController
        $this->_getRoleMenuPermission();  // legacy helper method
        $this->grid_access();
        $this->report_hsse_link();

        $this->viewBuilder()->setLayout('after_adminlogin_template');

        $data = $this->request->getData();
        $limit = $data['Report']['limit'] ?? 50;
        $action = $data['Report']['action'] ?? 'all';

        $this->set(compact('limit', 'action'));

        $session = $this->request->getSession();
        $session->write('limit', $limit);
        $session->delete('filter');
        $session->delete('value');
        $session->delete('idBollen');

        $reports = $this->Reports->find()
            ->where(['Reports.isdeleted' => 'N'])
            ->order(['Reports.id' => 'DESC'])
            ->limit($limit)
            ->all()
            ->toArray();

        // handle session admin data (must be array-accessible)
        $adminData = $session->read('adminData');
        $idBoolen = [];

        foreach ($reports as $report) {
            if (
                ($adminData['AdminMaster']['id'] ?? null) == $report['created_by']
                || ($adminData['RoleMaster']['id'] ?? null) == 1
            ) {
                $idBoolen[] = 1;
            } else {
                $idBoolen[] = 0;
            }
        }

        $session->write('idBollen', $idBoolen);

        $this->set('reports', $reports);
    }
	public function get_all_report($action ='all')
	{
		Configure::write('debug', '2');  
		$this->layout = "ajax"; 
		$this->_checkAdminSession();
		$condition="";
		$condition = "Report.isdeleted = 'N'";
                if(isset($_REQUEST['filter'])){
		switch($_REQUEST['filter']){
			case'report_no':
		$condition .= "AND ucase(Report.".$_REQUEST['filter'].") like '".$_REQUEST['value']."%'";	
			break;
		        case'client_name':
			$clientCondition= " ucase(Client.name) like '".$_REQUEST['value']."%'";
			$clientDetatail=$this->Client->find('all', array('conditions' =>$clientCondition));
          		$condition .= "AND Report.Client =".$clientDetatail[0]['Client']['id'];	
			break;
		        case'creater_name':
			$spliNAME=explode(" ",$_REQUEST['value']);
			$spliLname=$spliNAME[count($spliNAME)-1];
			$spliFname=$spliNAME[0];
			$adminCondition="AdminMaster.first_name like '%".$spliFname."%' AND AdminMaster.last_name like '%".$spliLname."%'";
			$userDetail = $this->AdminMaster->find('all',array('conditions'=>$adminCondition));
			$addimid=$userDetail[0]['AdminMaster']['id'];
			$condition .= "AND Report.created_by ='".$addimid."'";	
			break;
		        case'event_date_val':
		        $explodemonth=explode('/',$_REQUEST['value']);
			$day=$explodemonth[0];
			$month=date('m', strtotime($explodemonth[1]));
			$year="20$explodemonth[2]";
			$createon=$year."-".$month."-".$day;
			$condition .= "AND Report.event_date ='".$createon."'";	
		        break;
		}
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			//$condition .= " order by Category.id DESC";
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}
		$count = $this->Report->find('count' ,array('conditions' => $condition));
		$adminArray = array();
		
      
                $adminA = $this->Report->find('all' ,array('conditions' => $condition,'order' => 'Report.id DESC','limit'=>$limit));
		
		$idBoolen=array();
		unset($_SESSION['idBollen']);
		$i = 0;
		foreach($adminA as $rec)
		{

			
			if(($_SESSION['adminData']['AdminMaster']['id']==$rec['Report']['created_by']) || ($_SESSION['adminData']['RoleMaster']['id']==1)){
				array_push($idBoolen,1);
				$adminA[$i]['Report']['edit_permit'] ="false";
				$adminA[$i]['Report']['view_permit'] ="false";
				$adminA[$i]['Report']['delete_permit'] ="false";
				$adminA[$i]['Report']['block_permit'] ="false";
				$adminA[$i]['Report']['unblock_permit'] ="false";
				$adminA[$i]['Report']['checkbox_permit'] ="false";
				
				
				if($rec['Report']['isblocked'] == 'N')
			            {
					$adminA[$i]['Report']['blockHideIndex'] = "true";
					$adminA[$i]['Report']['unblockHideIndex'] = "false";
				        
			         }else{
					$adminA[$i]['Report']['blockHideIndex'] = "false";
					$adminA[$i]['Report']['unblockHideIndex'] = "true";
					
			        }
				
					
			}else{
				
				array_push($idBoolen,0);
				$adminA[$i]['Report']['edit_permit'] ="true";
				$adminA[$i]['Report']['view_permit'] ="false";
				$adminA[$i]['Report']['delete_permit'] ="true";
				$adminA[$i]['Report']['block_permit'] ="true";
				$adminA[$i]['Report']['unblock_permit'] ="true";
			        $adminA[$i]['Report']['blockHideIndex'] = "true";
				$adminA[$i]['Report']['unblockHideIndex'] = "true";
				$adminA[$i]['Report']['checkbox_permit'] ="true";
				
				
			}
				
			
			   
			$eventdate=explode("-",$rec['Report']['event_date']);
			$evDT=date("d/M/y", mktime(0, 0, 0, $eventdate[1],$eventdate[2],$eventdate[0]));
			$adminA[$i]['Report']['event_date_val']=$evDT;
		        $adminA[$i]['Report']['incident_severity_name']='<font color='.$rec['IncidentSeverity']['color_code'].'>'.$rec['IncidentSeverity']['type'].'</font>';
			$adminA[$i]['Report']['client_name']=$rec['Client']['name'];
			$adminA[$i]['Report']['creater_name']=$rec['AdminMaster']['first_name']." ".$rec['AdminMaster']['last_name'];
		    $i++;
		}
		
	
	        $remidial_close=array();
		$remidial_open=array();
		
		for($i=0;$i<count($adminA);$i++){
			
	            
			
			if(count($adminA[$i]['HsseRemidial'])>0){

			

				for($r=0;$r<count($adminA[$i]['HsseRemidial']);$r++){
	
					if(isset($adminA[$i]['HsseRemidial'][$r]['id'])){
		
				

					if($adminA[$i]['HsseRemidial'][$r]['remidial_closure_date']!='0000-00-00'){
		
					    $remidial_close[]=$adminA[$i]['HsseRemidial'][$r]['id'];
					   
					}elseif($adminA[$i]['HsseRemidial'][$r]['remidial_closure_date']=='0000-00-00'){
					     $remidial_open[]=$adminA[$i]['HsseRemidial'][$r]['id'];
					 
			
					}
					
				    }
					
				}
			$adminA[$i]['Report']['remidial']=count($remidial_close).'/'.count($remidial_open);	
			}else{
			$adminA[$i]['Report']['remidial']='';	
			}
			
							

		}

		
		$adminArray = Set::extract($adminA, '{n}.Report');
		$this->set('total', $count);  //send total to the view
		for($i=0;$i<count($adminArray);$i++){
			
	            if($adminArray[$i]['client']!=0){
		        $client=$this->Client->find('all', array('conditions' => array('id' =>$adminArray[$i]['client'])));
		        $adminArray[$i]['client_name']=$client[0]['Client']['name'];
		     }elseif($adminArray[$i]['client']==0){
			$adminArray[$i]['client_name']='N/A';
		     }
		      $user=$this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$adminArray[$i]['created_by'])));
		      if(count($user)>0){
		        $adminArray[$i]['creater_name']=$user[0]['AdminMaster']['first_name']." ".$user[0]['AdminMaster']['last_name'];
		      }else{
			$adminArray[$i]['creater_name']='';
		      }
		  
		}

		if($count==0){
			$adminArray=array();
		  }else{
			 $adminArray = Set::extract($adminA, '{n}.Report');
		  }
	       $this->Session->write('idBollen',$idBoolen);
		//$this->set('idBollen', $idBoolen);
	        $this->set('admins', $adminArray);  //send products to the view
		$this->set('status', $action);
	}

	
	
	
	function report_block($id = null)
	{
          
	
		if(!$id)
		{
			  // $this->Session->setFlash('Invalid id for admin');
			   $this->redirect(array('action'=>report_list), null, true);
		}
		else
		{
			 $idArray = explode("^", $id);
			 
			 
		
			foreach($idArray as $id)
			{
				   $id = $id;
				   $this->request->data['Report']['id'] = $id;
				   $this->request->data['Report']['isblocked'] = 'Y';
				   $this->Report->save($this->request->data,false);				
			}	     
			exit;			 
		}
	}
	
	function report_unblock($id = null)
	{

		
		if(!$id)
		{
	
			$this->redirect(array('action'=>report_list), null, true);
		}
		else
		{
			
			
			$idArray = explode("^", $id);
		
			foreach($idArray as $id)
			{
				$id = $id;
				$this->request->data['Report']['id'] = $id;
				$this->request->data['Report']['isblocked'] = 'N';
				$this->Report->save($this->request->data,false);
			}	
			exit;
		 
		}
	}
	
	function main_delete()
	     {
		      $this->layout="ajax";
	      
		      if($this->data['id']!=''){
			$idArray = explode("^", $this->data['id']);
                           foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['Report']['id'] =$id;
					  $this->request->data['Report']['isdeleted'] = 'Y';
											
						 $deleteHsse_incident = "DELETE FROM `hsse_incidents` WHERE `report_id` = {$id}";
                                                 $dHI=$this->HsseIncident->query($deleteHsse_incident);
						 $deleteHsse_investigation_datas = "DELETE FROM `hsse_investigation_datas` WHERE `report_id` = {$id}";
                                                 $dHINV=$this->HsseInvestigationData->query($deleteHsse_investigation_datas);
                                                 $deleteHsse_personnel_datas = "DELETE FROM `hsse_personnels` WHERE `report_id` = {$id}";
                                                 $dHP=$this->HssePersonnel->query($deleteHsse_personnel_datas);
					         $deleteHsse_remidials = "DELETE FROM `hsse_remidials` WHERE `report_no` = {$id}";
                                                 $dHR=$this->HsseRemidial->query($deleteHsse_remidials);
			
				                 $deleteHsse_attachments = "DELETE FROM `hsse_attachments` WHERE `report_id` = {$id}";
                                                 $dHA=$this->HsseAttachment->query($deleteHsse_attachments);
			                         
						 $deleteHsse_clients = "DELETE FROM `hsse_clients` WHERE `report_id` = {$id}";
                                                 $dHC=$this->HsseClient->query( $deleteHsse_clients);
						 
						 $deleteHsse_remidials_email = "DELETE FROM `remidial_email_lists` WHERE `report_id` = {$id} AND `report_type`='hsse'";
                                                 $dHRem=$this->RemidialEmailList->query($deleteHsse_remidials_email);
						 
						 
						 $deleteHsse_mains = "DELETE FROM `reports` WHERE `id` = {$id}";
                                                 $dHC=$this->Report->query($deleteHsse_mains);
				    
					
					
					
					  
					  
			       }
		
			       echo 'ok';
			        exit;
		      }else{
			 $this->redirect(array('action'=>report_hsse_list), null, true);
		      }
			 
			      
			      
			       
	}
	
	
	
	
	
	
	
	
	
	function displayteam()
	{
		$this->layout="ajax";
		
		if(!empty($this->data))
		{
		  $teamlist=$this->Team->find('all', array('conditions' => array('division_id' =>$this->data['id'])));
		
			if(count($teamlist)>0)
			{
			        $this->set('teamlist',$teamlist);
			}	
			
		}		
	}	
	
	
        public function add_report_main($id=null)
	
	{        
	      
		 $this->_checkAdminSession();
		 $this->grid_access();
		 $this->_getRoleMenuPermission();
                 $this->layout="after_adminlogin_template";
	          $incidentDetail = $this->Incident->find('all', array('conditions' => array('incident_type' =>'hsse')));
		 $businessDetail = $this->BusinessType->find('all',array('conditions' => array('rtype' =>'all')));
		 $fieldlocationDetail = $this->Fieldlocation->find('all');
		 $clientDetail = $this->Client->find('all');
		 $incidentSeverityDetail = $this->IncidentSeverity->find('all',array('conditions' => array('servrity_type' =>'ssh')));

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
		 $clientdetail = $this->HsseClient->find('all', array('conditions' => array('HsseClient.report_id' =>base64_decode($id))));
		    
		  if(count($clientdetail)>0){
			 if($clientdetail[0]['HsseClient']['clientreviewed']==3){
			      $this->set('client_feedback',1);
		           }else if($clientdetail[0]['HsseClient']['clientreviewed']!=3){
			      $this->set('client_feedback',0);
			
		          }
			
		  }else{
			$this->set('client_feedback',0);
		  }
		if($id==null)
		{
          
		  $this->set('id','0');
		  $this->set('event_date','');
		  $this->set('since_event_hidden',0);
		  $this->set('heading','Add HSSE Report (Main)');
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
		 
		 }else if(base64_decode($id)!=null){
			
	          $this->hsse_client_tab();
		  $reportdetail = $this->Report->find('all', array('conditions' => array('Report.id' =>base64_decode($id))));
                  $adminDATA = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$reportdetail[0]['Report']['created_by'])));
		  $this->Session->write('report_create',$reportdetail[0]['Report']['created_by']);
		   if($reportdetail[0]['Report']['client']==9){
			$this->set('clienttab',0);
			
		    }else if($reportdetail[0]['Report']['client']!=9){
			$this->set('clienttab',1);
			
		    }
		  
		  
		    $clientdetail = $this->HsseClient->find('all', array('conditions' => array('HsseClient.report_id' =>base64_decode($id))));
		    
		  if(count($clientdetail)>0){
			 if($clientdetail[0]['HsseClient']['clientreviewed']==3){
			      $this->set('client_feedback',1);
		           }else if($clientdetail[0]['HsseClient']['clientreviewed']!=3){
			      $this->set('client_feedback',0);
			
		          }
			
		  }else{
			$this->set('client_feedback',0);
			}
		 
		  
		  
		  $this->set('id',base64_decode($id));
		  if($reportdetail[0]['Report']['closer_date']!=''){
		  $crtd=explode("-",$reportdetail[0]['Report']['closer_date']);
		  $closedt=$crtd[1]."-".$crtd[2]."-".$crtd[0];
		  }else{
		  $closedt='';	
		  }
		  
		 if($reportdetail[0]['Report']['event_date']!=''){
		  
		  $evndt=explode("-",$reportdetail[0]['Report']['event_date']);
		  $event_date=$evndt[1]."-".$evndt[2]."-".$evndt[0];
		  
		  }else{
		  $event_date=''; 	
		  }
	  	  $this->set('event_date',$event_date);
		  $this->set('since_event_hidden',$reportdetail[0]['Report']['since_event']);
		  $this->set('since_event',$reportdetail[0]['Report']['since_event']);
		  $this->set('heading','Update HSSE Report (Main)');
		  $this->set('button','Update');
		  $this->set('reportno',$reportdetail[0]['Report']['report_no']);
  		  $this->set('closer_date',$closedt);
		  $this->set('incident_type',$reportdetail[0]['Report']['incident_type']);
	          $this->set('cnt',$reportdetail[0]['Report']['country']);
		  $this->set('created_date','');
		  $this->set('business_unit',$reportdetail[0]['Report']['business_unit']);
		  $this->set('client',$reportdetail[0]['Report']['client']);
		   
		  
		  $this->set('field_location',$reportdetail[0]['Report']['field_location']);
		  $this->set('incident_severity',$reportdetail[0]['Report']['incident_severity']);
		  $this->set('recordable',$reportdetail[0]['Report']['recorable']);
		  $this->set('potential',$reportdetail[0]['Report']['potential']);
		  $this->set('residual',$reportdetail[0]['Report']['residual']);
		  $this->set('summary',$reportdetail[0]['Report']['summary']);
		  $this->set('details',$reportdetail[0]['Report']['details']);
		  $this->set('reporter',$reportdetail[0]['Report']['reporter']);
		  $this->set('created_by',$adminDATA[0]['AdminMaster']['first_name']." ".$adminDATA[0]['AdminMaster']['last_name']);
		 
		 
		 }
	}
	function reportprocess()
	 { 
	   $this->layout = "ajax";
	   $this->_checkAdminSession();
	   $reportData=array();
	   
          if($this->data['add_report_main_form']['id']==0){
		   $res='add';
 	                
	   }else{
		   $res='update';
		   $reportData['Report']['id']=$this->data['add_report_main_form']['id'];
		  
	    }
	    
	     
	     
         if($this->data['event_date']!=''){
           $evndate=explode("-",$this->data['event_date']);
	   $reportData['Report']['event_date']=$evndate[2]."-".$evndate[0]."-".$evndate[1];
	   }else{
	   $reportData['Report']['event_date']='';	
	   }
	   
	   
	  if(isset($this->data['closer_date'])){
	     $clsdate=explode("-",$this->data['closer_date']);
	     $reportData['Report']['closer_date']=$clsdate[2]."-".$clsdate[0]."-".$clsdate[1];
	   }else{
	     $reportData['Report']['closer_date']='0000-00-00';	
	   }
	   $reportData['Report']['incident_type']=$this->data['incident_type']; 
	   $reportData['Report']['business_unit'] =$this->data['business_unit'];  
	   $reportData['Report']['client']=$this->data['client'];
	   $reportData['Report']['field_location']=$this->data['field_location'];
	   $reportData['Report']['country']=$this->data['country'];
	   $reportData['Report']['reporter']=$this->data['reporter'];
	   $reportData['Report']['incident_severity']=$this->data['incident_severity'];
	   $reportData['Report']['recorable']=$this->data['recorable'];
	   $reportData['Report']['report_no']=$this->data['report_no']; 
	   $reportData['Report']['since_event']=$this->data['since_event'];
	   $reportData['Report']['created_by']=$_SESSION['adminData']['AdminMaster']['id'];
	   $reportData['Report']['reporter']=$this->data['reporter'];
	   $reportData['Report']['potential']=$this->data['potential'];
	   $reportData['Report']['residual']=$this->data['residual'];
	   $reportData['Report']['summary']=$this->data['add_report_main_form']['summary'];
	   $reportData['Report']['details']=$this->data['add_report_main_form']['details'];
	   
	  if($this->Report->save($reportData)){
		 if($res=='add'){
			 $lastReport=base64_encode($this->Report->getLastInsertId());
		 }elseif($res=='update'){
			 $lastReport=base64_encode($this->data['add_report_main_form']['id']);
		 }
		 
		if($this->data['client']==0){
			$redirect='Personal';
		}elseif($this->data['client']!=0){
			$redirect='Client';
			
		}
		echo $res."~".$lastReport."~".$redirect;
	    }else{
		echo 'fail~0~0';
	    }
                
		
	   exit;
	}
	
	 public function add_report_client($id=null){
		 $this->_checkAdminSession();
         	 $this->_getRoleMenuPermission();
		 $this->grid_access();
                 $this->layout="after_adminlogin_template";
		 $this->set('id','0');
		
		   $reportdetail = $this->Report->find('all', array('conditions' => array('Report.id' =>base64_decode($id))));
		   $clientdetail = $this->HsseClient->find('all', array('conditions' => array('HsseClient.report_id' =>base64_decode($id))));
		   
		    
         	   $this->set('report_number',$reportdetail[0]['Report']['report_no']); 
		   if(count($clientdetail)>0){
			
			    $this->set('heading','Update Client Data');
		            $this->set('button','Update');
			    $this->set('id',$clientdetail[0]['HsseClient']['id']);
		            $this->set('well',$clientdetail[0]['HsseClient']['well']);
		            $this->set('rig',$clientdetail[0]['HsseClient']['rig']);
		            $this->set('clientncr',$clientdetail[0]['HsseClient']['clientncr']);
		            $this->set('clientreviewed',$clientdetail[0]['HsseClient']['clientreviewed']);
  		            $this->set('report_id',$clientdetail[0]['HsseClient']['report_id']);
			    
			    
			    if($clientdetail[0]['HsseClient']['clientreviewed']==3){
				$this->set('clientreviewed_style','style="display:block"');
				$this->set('clientreviewer',$clientdetail[0]['HsseClient']['clientreviewer']);
				$this->set('client_feedback',1);
			     }else{
				$this->set('clientreviewed_style','style="display:none"');
				$this->set('clientreviewer','');
				$this->set('client_feedback',0);
			     }
			            
			    $this->set('wellsiterep',$clientdetail[0]['HsseClient']['wellsiterep']);
			
                          
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
			    $this->set('client_feedback',0);
			    
		       }
	
		 
	        }
	 
	        function hsseclientprocess(){ 
	                $this->layout = "ajax";
	                $this->_checkAdminSession();
	                $hsseClientData=array();
	         	$clientdetail = $this->HsseClient->find('all', array('conditions' => array('HsseClient.report_id' =>$this->data['report_id'])));
				if(count($clientdetail)>0){
				       $res='update';
				       $hsseClientData['HsseClient']['id']=$clientdetail[0]['HsseClient']['id'];
				}else{
				       $res='add';
				}
					$hsseClientData['HsseClient']['well']=$this->data['well']; 
					$hsseClientData['HsseClient']['rig'] =$this->data['rig'];  
					$hsseClientData['HsseClient']['clientncr']=$this->data['clientncr'];
					$hsseClientData['HsseClient']['clientreviewed']=$this->data['clientreviewed'];
					$hsseClientData['HsseClient']['report_id']=$this->data['report_id'];
					$hsseClientData['HsseClient']['clientreviewer']=$this->data['clientreviewer'];
					$hsseClientData['HsseClient']['wellsiterep']=$this->data['wellsiterep'];
				if($this->HsseClient->save($hsseClientData)){
				     echo $res;
				 }else{
				     echo 'fail';
				 }
				 
     
	          exit;
	        }
	 
	 
	 function add_report_view($id=null){
		 $this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
		 $this->grid_access();
		 $this->hsse_client_tab();
                 $this->layout="after_adminlogin_template";
		 $reportdetail = $this->Report->find('all', array('conditions' => array('Report.id' =>base64_decode($id))));

		 
		 $this->Session->write('report_create',$reportdetail[0]['Report']['created_by']);
		 
		 
	          $this->set('id',base64_decode($id));
                	if($reportdetail[0]['Report']['event_date']!=''){
				 $evndt=explode("-",$reportdetail[0]['Report']['event_date']);
				 $event_date=$evndt[2]."/".$evndt[1]."/".$evndt[0];
			}else{
				 $event_date='';	
			}
		  
                 $clientdetail = $this->HsseClient->find('all', array('conditions' => array('HsseClient.report_id' =>base64_decode($id)))); 
		  if($reportdetail[0]['Report']['client']==10){
		        $this->set('clienttabshow',0);
			
			$this->set('clienttab',0);
				     
		   }else if($reportdetail[0]['Report']['client']!=10){
		        $this->set('clienttabshow',1);
			if(count($clientdetail)>0){
				$this->set('clienttab',1);
			}else{
				$this->set('clienttab',0);
			}
			
	            }
		  

		    
		  if(count($clientdetail)>0){
			 if($clientdetail[0]['HsseClient']['clientreviewed']==3){
			      $this->set('client_feedback',1);
		           }else if($clientdetail[0]['HsseClient']['clientreviewed']!=3){
			      $this->set('client_feedback',0);
			
		          }
			
		  }else{
			$this->set('client_feedback',0);
		  }
		  
		  
	  	  $this->set('event_date',$event_date);
		  $this->set('since_event_hidden',$reportdetail[0]['Report']['since_event']);
		  $this->set('since_event',$reportdetail[0]['Report']['since_event']);
		  $this->set('reportno',$reportdetail[0]['Report']['report_no']);
		  
		  
		  if($reportdetail[0]['Report']['closer_date']!='0000-00-00'){
				 $clsdt=explode("-",$reportdetail[0]['Report']['closer_date']);
				 $closedt=$clsdt[2]."/".$clsdt[1]."/".$clsdt[0];
			}else{
				 $closedt='00/00/0000';	
			}
		  
		  $this->set('closer_date',$closedt);
		  
		  $incident_detail= $this->Incident->find('all', array('conditions' => array('Incident.id' =>$reportdetail[0]['Report']['incident_type'])));
		  $this->set('incident_type',$incident_detail[0]['Incident']['type']);
		  
		  
		  $user_detail= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$reportdetail[0]['Report']['created_by'])));
		  $this->set('created_by',$user_detail[0]['AdminMaster']['first_name']." ".$user_detail[0]['AdminMaster']['last_name']);
		  
		  $this->set('created_date','');
		  
		  $business_unit_detail= $this->BusinessType->find('all', array('conditions' => array('BusinessType.id' =>$reportdetail[0]['Report']['business_unit'])));
		  $this->set('business_unit',$business_unit_detail[0]['BusinessType']['type']);
		 

		  
		   $client_detail= $this->Client->find('all', array('conditions' => array('Client.id' =>$reportdetail[0]['Report']['client'])));
		   $this->set('client',$client_detail[0]['Client']['name']);
		  
		  
		   $fieldlocation= $this->Fieldlocation->find('all', array('conditions' => array('Fieldlocation.id' =>$reportdetail[0]['Report']['field_location'])));
		   $this->set('fieldlocation',$fieldlocation[0]['Fieldlocation']['type']);
		  
		   $incidentLocation= $this->IncidentLocation->find('all', array('conditions' => array('IncidentLocation.id' =>$reportdetail[0]['Report']['field_location'])));
		   $this->set('incidentLocation',$incidentLocation[0]['IncidentLocation']['type']);
		  
		  
		   $countrty= $this->Country->find('all', array('conditions' => array('Country.id' =>$reportdetail[0]['Report']['country'])));
		   $this->set('countrty',$countrty[0]['Country']['name']);
		  
		   $report_detail= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$reportdetail[0]['Report']['reporter'],'AdminMaster.isdeleted'=>'N','AdminMaster.isblocked'=>'N')));
		   $this->set('reporter',$report_detail[0]['AdminMaster']['first_name']." ".$report_detail[0]['AdminMaster']['last_name']);
	   
		   $incidentSeverity_detail= $this->IncidentSeverity->find('all', array('conditions' => array('IncidentSeverity.id' =>$reportdetail[0]['Report']['incident_severity'])));
		   $this->set('incidentseveritydetail',$incidentSeverity_detail[0]['IncidentSeverity']['type']);
		   $this->set('incidentseveritydetailcolor','style="background-color:'.$incidentSeverity_detail[0]['IncidentSeverity']['color_code'].'"');
		   
		   
		   $residual_detail= $this->Residual->find('all', array('conditions' => array('Residual.id' =>$reportdetail[0]['Report']['residual'])));
		   $this->set('residual',$residual_detail[0]['Residual']['type']);
		   if($residual_detail[0]['Residual']['color_code']!=''){
		    $this->set('residualcolor','style="background-color:'.$residual_detail[0]['Residual']['color_code'].'"');
		   }else{
		    $this->set('residualcolor','');	
		   }
	   
		   if($reportdetail[0]['Report']['recorable']==1){
			$this->set('recorable','Yes');
			$this->set('recorablecolor','style="background-color:#FF0000"');
		   }elseif($reportdetail[0]['Report']['recorable']==2){
			$this->set('recorable','No');
			$this->set('recorablecolor','style="background-color:#40FF00"');
		   }
		   
		     $potentilal_detail= $this->Potential->find('all', array('conditions' => array('Potential.id' =>$reportdetail[0]['Report']['potential'])));
		   $this->set('potential',$potentilal_detail[0]['Potential']['type']);
		   if($potentilal_detail[0]['Potential']['color_code']!=''){
		    $this->set('potentialcolor','style="background-color:'.$potentilal_detail[0]['Potential']['color_code'].'"');
		   }else{
		    $this->set('potentialcolor','');	
		   }
	   
		
		   $this->set('summary',$reportdetail[0]['Report']['summary']);
		  $this->set('details',$reportdetail[0]['Report']['details']);
		  $this->set('created_by',$_SESSION['adminData']['AdminMaster']['first_name']." ".$_SESSION['adminData']['AdminMaster']['last_name']);
		  
		  /************************clientdata***************************/
		  $clientdetail = $this->HsseClient->find('all', array('conditions' => array('HsseClient.report_id' =>base64_decode($id))));
		  
		  
		   if(count($clientdetail)>0){
			
			    $this->set('well',$clientdetail[0]['HsseClient']['well']);
		            $this->set('rig',$clientdetail[0]['HsseClient']['rig']);
		            $this->set('clientncr',$clientdetail[0]['HsseClient']['clientncr']);
			    if($clientdetail[0]['HsseClient']['clientreviewed']==1){
				$this->set('clientreviewed','N/A');
			    }elseif($clientdetail[0]['HsseClient']['clientreviewed']==2){
				$this->set('clientreviewed','N/A');
			    }if($clientdetail[0]['HsseClient']['clientreviewed']==3){
				$this->set('clientreviewed','Yes');
				
			    }
		            
  		            $this->set('report_id',$clientdetail[0]['HsseClient']['report_id']);
		            $this->set('clientreviewer',$clientdetail[0]['HsseClient']['clientreviewer']);
			    $this->set('wellsiterep',$clientdetail[0]['HsseClient']['wellsiterep']);
			}else{
			     $this->set('well','');
			     $this->set('rig','');
			     $this->set('clientncr','');
			     $this->set('clientreviewed','');
			     $this->set('clientreviewer','');
			     $this->set('wellsiterep','');
			
			   	
				
			}
			
		  /************************indidentdata***************************/
		  
		  
		  $incidentdetail = $this->HsseIncident->find('all', array('conditions' => array('HsseIncident.report_id' =>base64_decode($id),'HsseIncident.isdeleted'=>'N','HsseIncident.isblocked'=>'N')));
		  
		 //echo '<pre>';
		 //print_r($incidentdetail);
	         
		  $incidentdetailHolder=array();
	           if(count($incidentdetail)>0){
			for($i=0;$i<count($incidentdetail);$i++){
	       
	                     if($incidentdetail[$i]['HsseIncident']['date_incident']!=''){
				    $incidt=explode("-",$incidentdetail[$i]['HsseIncident']['date_incident']);
				    $incidentdetailHolder[$i]['date_incident']=$incidt[2]."/".$incidt[1]."/".$incidt[0];
			         }else{
				    $incidentdetailHolder[$i]['date_incident']='';	
			        }
				
				if($incidentdetail[$i]['HsseIncident']['incident_time']!=''){
				
				$incidentdetailHolder[$i]['incident_time']=$incidentdetail[$i]['HsseIncident']['incident_time'];
				}else{
				$incidentdetailHolder[$i]['incident_time']='';	
				}
						
				if($incidentdetail[$i]['HsseIncident']['incident_severity']!=0){
				$incidentSeverity_type= $this->IncidentSeverity->find('all', array('conditions' => array('IncidentSeverity.id' =>$incidentdetail[$i]['HsseIncident']['incident_severity'])));
				$incidentdetailHolder[$i]['incident_severity']=$incidentSeverity_type[0]['IncidentSeverity']['type'];
				}else{
				$incidentdetailHolder[$i]['incident_severity']='';	
				}
				
				if($incidentdetail[$i]['HsseIncident']['incident_loss']!=0){
		                $incidentLoss_type= $this->Loss->find('all', array('conditions' => array('Loss.id' =>$incidentdetail[$i]['HsseIncident']['incident_loss'])));
				$incidentdetailHolder[$i]['incident_loss']=$incidentLoss_type[0]['Loss']['type'];
				}else{
				$incidentdetailHolder[$i]['incident_loss']='';	
				}
				
				if($incidentdetail[$i]['HsseIncident']['incident_category']!=0){
				 $incident_category_type= $this->IncidentCategory->find('all', array('conditions' => array('IncidentCategory.id' =>$incidentdetail[$i]['HsseIncident']['incident_category'])));
				 $incidentdetailHolder[$i]['incident_category']=$incident_category_type[0]['IncidentCategory']['type'];
				}else{
				  $incidentdetailHolder[$i]['incident_category']='';	
				}
			        
		               if($incidentdetail[$i]['HsseIncident']['incident_sub_category']!=0){
			           $incident_sub_category_type= $this->IncidentSubCategory->find('all', array('conditions' => array('IncidentSubCategory.id' =>$incidentdetail[$i]['HsseIncident']['incident_sub_category'])));
				   $incidentdetailHolder[$i]['incident_sub_category']=$incident_sub_category_type[0]['IncidentSubCategory']['type'];
			       }else{
				  $incidentdetailHolder[$i]['incident_sub_category']='';
			       }
			      	       
			       if($incidentdetail[$i]['HsseIncident']['incident_summary']!=''){
			       $incidentdetailHolder[$i]['incident_summary']=$incidentdetail[$i]['HsseIncident']['incident_summary'];
			       }else{
				 $incidentdetailHolder[$i]['incident_summary']='';
			       }
			       if($incidentdetail[$i]['HsseIncident']['detail']!=''){
			       $incidentdetailHolder[$i]['detail']=$incidentdetail[$i]['HsseIncident']['detail'];
			       }else{
				$incidentdetailHolder[$i]['detail']='';
			       }
			       
			       $incidentdetailHolder[$i]['id']=$incidentdetail[$i]['HsseIncident']['id'];
		        
			
			      $incidentdetailHolder[$i]['isblocked']=$incidentdetail[$i]['HsseIncident']['isblocked'];
			      $incidentdetailHolder[$i]['isdeleted']=$incidentdetail[$i]['HsseIncident']['isdeleted'];
			      $incidentdetailHolder[$i]['incident_no']=$incidentdetail[$i]['HsseIncident']['incident_no'];  
			if(count($incidentdetail[$i]['HsseInvestigationData'])>0){
				
				for($v=0;$v<count($incidentdetail[$i]['HsseInvestigationData']);$v++){
					 $immidiate_cause=explode(",",$incidentdetail[$i]['HsseInvestigationData'][$v]['immediate_cause']);
					  if($immidiate_cause[0]!=0 || $immidiate_cause[0]!=''){
			                         $imdCause = $this->ImmediateCause->find('all', array('conditions' => array('ImmediateCause.id'=>$immidiate_cause[0])));
						 if(count($imdCause)>0){
						 $incidentdetailHolder[$i]['imd_cause'][]=$imdCause[0]['ImmediateCause']['type'];
						 }          
					   if($immidiate_cause[1]!=0 || $immidiate_cause[1]!=''){

			                          $imdSubCause = $this->ImmediateSubCause->find('all', array('conditions' => array('ImmediateSubCause.id'=>$immidiate_cause[1])));
						  if(count($imdSubCause)>0){
					              $incidentdetailHolder[$i]['imd_sub_cause'][]=$imdSubCause[0]['ImmediateSubCause']['type'];
						  }
                                           }
					  }
					   if($incidentdetail[$i]['HsseInvestigationData'][$v]['comments']!=''){
				                $incidentdetailHolder[$i]['comment']=$incidentdetail[$i]['HsseInvestigationData'][$v]['comments'];
			                    }
					    $incidentdetailHolder[$i]['investigation_block']=$incidentdetail[$i]['HsseInvestigationData'][$v]['isblocked'];
					    $incidentdetailHolder[$i]['investigation_delete']=$incidentdetail[$i]['HsseInvestigationData'][$v]['isdeleted'];
					    $incidentdetailHolder[$i]['investigation_no'][]=$incidentdetail[$i]['HsseInvestigationData'][$v]['investigation_no'];
					    $incidentdetailHolder[$i]['incident_no_investigation']=$incidentdetail[$i]['HsseInvestigationData'][$v]['incident_no'];
					   if($incidentdetail[$i]['HsseInvestigationData'][$v]['root_cause_id']!=0){
				                $explode_rootcause=explode(",",$incidentdetail[$i]['HsseInvestigationData'][$v]['root_cause_id']);
				 
							for($j=0;$j<count($explode_rootcause);$j++){
								if($explode_rootcause[$j]!=0){
								 $rootCauseDetail = $this->RootCause->find('all', array('conditions' => array('RootCause.id' =>$explode_rootcause[$j])));
								 $incidentdetailHolder[$i]['root_cause_val'][$v][$j]=$rootCauseDetail[0]['RootCause']['type'];
								}
								
								
							}
				 
			                      }
					   if($incidentdetail[$i]['HsseInvestigationData'][$v]['remedila_action_id']!=''){
				                 $explode_remedila_action_id=explode(",",$incidentdetail[$i]['HsseInvestigationData'][$v]['remedila_action_id']);
				    		 for($k=0;$k<count($explode_remedila_action_id);$k++){
							if(isset($explode_remedila_action_id[$k])){
							 $remDetail = $this->HsseRemidial->find('all', array('conditions' => array('HsseRemidial.id' =>$explode_remedila_action_id[$k],'HsseRemidial.isdeleted'=>'N','HsseRemidial.isblocked'=>'N')));
							 $incidentdetailHolder[$i]['rem_val'][$v][$k]=$remDetail[0]['HsseRemidial']['remidial_summery'];
							 $incidentdetailHolder[$i]['rem_val_id'][$v][$k]=$remDetail[0]['HsseRemidial']['id'];
							}
							
						}
				
			                   }
					   
				  $incidentdetailHolder[$i]['view'][]='yes';       
				}
	
		            
		        }else{
			     $incidentdetailHolder[$i]['view'][]='no';
		        }
		     
			
		     }
			
			
		  }
		  
		  
		  //print_r($incidentdetailHolder);   
		     
         	  $this->set('incidentdetailHolder',$incidentdetailHolder);
		  
	  
		  
		  
		  /*************Incident - Personnel****************************/
		  $personeldetail = $this->HssePersonnel->find('all', array('conditions' => array('HssePersonnel.report_id' =>base64_decode($id),'HssePersonnel.isdeleted'=>'N','HssePersonnel.isblocked'=>'N')));
		  if(count($personeldetail)>0){
			$this->set('personeldata',1);
			for($i=0;$i<count($personeldetail);$i++){
				$user_detail= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$personeldetail[$i]['HssePersonnel']['personal_data'],'AdminMaster.isblocked'=>'N','AdminMaster.isdeleted'=>'N')));
				if(count($user_detail)>0){
					$seniorty=explode(" ",$user_detail[0]['AdminMaster']['created']);	
					$snr=explode("-",  $seniorty[0]);
					$snrdt=$snr[2]."/".$snr[1]."/".$snr[0];
					$personeldetail[$i]['HssePersonnel']['seniorty']=$snrdt;
					$personeldetail[$i]['HssePersonnel']['name']=$user_detail[0]['AdminMaster']['first_name']."  ".$user_detail[0]['AdminMaster']['last_name'];
					$personeldetail[$i]['HssePersonnel']['position']=$user_detail[0]['RoleMaster']['role_name'];
				}
				
			}
			 $this->set('personeldetail',$personeldetail);
		  }else{
			$this->set('personeldata',0);
		  }
		 /*************Attachments****************************/
		  $attachmentData = $this->HsseAttachment->find('all', array('conditions' => array('HsseAttachment.report_id' =>base64_decode($id),'HsseAttachment.isdeleted'=>'N','HsseAttachment.isblocked'=>'N')));
		  if(count($attachmentData)>0){
		      $this->set('attachmentData',$attachmentData);
		      $this->set('attachmentTab',1);
		  }else{
		       $this->set('attachmentData','');
		       $this->set('attachmentTab',0);
		  }
		  /***********REMIDIAL ACTION****************************/
		  $remidialdetail = $this->HsseRemidial->find('all', array('conditions' => array('HsseRemidial.report_no' =>base64_decode($id),'HsseRemidial.isblocked'=>'N','HsseRemidial.isdeleted'=>'N')));
		  if(count($remidialdetail)>0){
			$this->set('remidial',1);
			for($i=0;$i<count($remidialdetail);$i++){
				 $user_detail_createby= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$remidialdetail[$i]['HsseRemidial']['remidial_createby'])));
				 $remidialdetail[$i]['HsseRemidial']['remidial_createby']=$user_detail_createby[0]['AdminMaster']['first_name']."  ".$user_detail_createby[0]['AdminMaster']['last_name'];
				 $user_detail_reponsibilty= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$remidialdetail[$i]['HsseRemidial']['remidial_responsibility'])));
				 $remidialdetail[$i]['HsseRemidial']['remidial_responsibility']=$user_detail_reponsibilty[0]['AdminMaster']['first_name']."  ".$user_detail_reponsibilty[0]['AdminMaster']['last_name'];
				 $priority_detail= $this->Priority->find('all', array('conditions' => array('Priority.id' =>$remidialdetail[$i]['HsseRemidial']['remidial_priority'])));
				 $remidialdetail[$i]['HsseRemidial']['priority']=$priority_detail[0]['Priority']['type'];
				 $remidialdetail[$i]['HsseRemidial']['priority_color']='style="background-color:'.$priority_detail[0]['Priority']['colorcoder'].'"';
				 $lastupdated=explode(" ",$remidialdetail[$i]['HsseRemidial']['modified']);
				 $lastupdatedate=explode("-",$lastupdated[0]);
				 $remidialdetail[$i]['HsseRemidial']['lastupdate']=$lastupdatedate[1].'/'.$lastupdatedate[2].'/'.$lastupdatedate[0];
				 $createdate=explode("-",$remidialdetail[$i]['HsseRemidial']['remidial_create']);
				 $remidialdetail[$i]['HsseRemidial']['createRemidial']=date("d-M-y", mktime(0, 0, 0, $createdate[1], $createdate[2], $createdate[0]));
				 
				 if($remidialdetail[$i]['HsseRemidial']['remidial_closure_date']=='0000-00-00'){
				   	  $closerDate=explode("-",$remidialdetail[$i]['HsseRemidial']['remidial_closure_date']);
				          $remidialdetail[$i]['HsseRemidial']['closeDate']='';
					  $remidialdetail[$i]['HsseRemidial']['remidial_closer_summary']='';
					
				 }elseif($remidialdetail[$i]['HsseRemidial']['remidial_closure_date']!='0000-00-00'){
					 $closerDate=explode("-",$remidialdetail[$i]['HsseRemidial']['remidial_closure_date']);
				         $remidialdetail[$i]['HsseRemidial']['closeDate']=date("d-M-y", mktime(0, 0, 0, $closerDate[1], $closerDate[2], $closerDate[0]));
					
				 }
				
				 
			}
			
			  
			
			 $this->set('remidialdetail',$remidialdetail);
		  }else{
			 $this->set('remidialdetail',array());
			$this->set('remidial',0);
		  }
		  
		  /****************Investigation Team******************/
		   $invetigationDetail = $this->HsseInvestigation->find('all', array('conditions' => array('HsseInvestigation.report_id' =>base64_decode($id))));
		   if(count($invetigationDetail)){
			$condition = "AdminMaster.isblocked = 'N' AND AdminMaster.isdeleted = 'N' AND AdminMaster.id IN (".$invetigationDetail[0]['HsseInvestigation']['team_user_id'].")";
			$investigation_team = $this->AdminMaster->find('all', array('conditions' =>  $condition));
			$this->set('invetigationDetail',$invetigationDetail);
			$this->set('investigation_team',$investigation_team);
		   }else{
		       $this->set('investigation_team',array());	
		   }
		   
		    /****************Incident Investigation******************/
		     $incidentInvestigationDetail = $this->HsseInvestigationData->find('all', array('conditions' => array('HsseInvestigationData.report_id' =>base64_decode($id),'HsseInvestigationData.isdeleted'=>'N','HsseInvestigationData.isblocked'=>'N'),'recursive'=>2));
			     
	             if(count($incidentInvestigationDetail)>0){
		     for($i=0;$i<count($incidentInvestigationDetail);$i++){
			
			 $incidentDetail = $this->HsseIncident->find('all', array('conditions' => array('HsseIncident.id' =>$incidentInvestigationDetail[$i]['HsseInvestigationData']['incident_id'])));
			 $incidentInvestigationDetail[$i]['HsseInvestigationData']['incident_summary']=$incidentDetail[0]['HsseIncident']['incident_summary'];
			 $lossDetail = $this->Loss->find('all', array('conditions' => array('id' =>$incidentDetail[0]['HsseIncident']['incident_loss'])));
			 $incidentInvestigationDetail[$i]['HsseInvestigationData']['loss']=$lossDetail[0]['Loss']['type'];
		         $immidiate_cause=explode(",",$incidentInvestigationDetail[$i]['HsseInvestigationData']['immediate_cause']);
			 
		         if($immidiate_cause[0]!=''){
			   $incidentInvestigationDetail[$i]['HsseInvestigationData']['imd_cause'] = $this->ImmediateCause->find('all', array('conditions' => array('ImmediateCause.id'=>$immidiate_cause[0])));
			    }
			  
		        if(isset($immidiate_cause[1])){

			   $incidentInvestigationDetail[$i]['HsseInvestigationData']['imd_sub_cause'] = $this->ImmediateSubCause->find('all', array('conditions' => array('ImmediateSubCause.id'=>$immidiate_cause[1])));
					
                          }
			  
			  if($incidentInvestigationDetail[$i]['HsseInvestigationData']['root_cause_id']!=0){
				$explode_rootcause=explode(",",$incidentInvestigationDetail[$i]['HsseInvestigationData']['root_cause_id']);
				
				for($j=0;$j<count($explode_rootcause);$j++){
					if($explode_rootcause[$j]!=0){
					 $rootCauseDetail = $this->RootCause->find('all', array('conditions' => array('RootCause.id' =>$explode_rootcause[$j])));
					 $incidentInvestigationDetail[$i]['HsseInvestigationData']['root_cause_val'][$j]=$rootCauseDetail[0]['RootCause']['type'];
					}
					
					
				}
				
			  }
			  
			  if($incidentInvestigationDetail[$i]['HsseInvestigationData']['remedila_action_id']!=''){
				$explode_remedila_action_id=explode(",",$incidentInvestigationDetail[$i]['HsseInvestigationData']['remedila_action_id']);
				
				for($k=0;$k<count($explode_remedila_action_id);$k++){
					if(isset($explode_remedila_action_id[$k])){
					$remDetail = $this->HsseRemidial->find('all', array('conditions' => array('HsseRemidial.id' =>$explode_remedila_action_id[$k],'HsseRemidial.isblocked'=>'N','HsseRemidial.isdeleted'=>'N')));
					
			         	    $incidentInvestigationDetail[$i]['HsseInvestigationData']['rem_val'][$k]=$remDetail[0]['HsseRemidial']['remidial_summery'];
				   
				    
				     $incidentInvestigationDetail[$i]['HsseInvestigationData']['rem_val_id'][$k]=$remDetail[0]['HsseRemidial']['id'];
					}
					
				}
				
			  }
			  
		
		     }
		        $this->set('incidentInvestigationDetail',$incidentInvestigationDetail);
	             }else{
			$this->set('incidentInvestigationDetail',array());
			
		     }
		/****************Client Feedback******************/
		
	
		 if(count($clientdetail)>0){
			 if($clientdetail[0]['HsseClient']['clientreviewed']==3){
				  $clientfeeddetail = $this->HsseClientfeedback->find('all', array('conditions' => array('HsseClientfeedback.report_id' =>base64_decode($id))));
				  

			   if(count($clientfeeddetail)>0){
				  
				  
				if($clientfeeddetail[0]['HsseClientfeedback']['client_summary']!=''){
				       $this->set('clientfeedback_summary',$clientfeeddetail[0]['HsseClientfeedback']['client_summary']);
				}else{
				       $this->set('clientfeedback_summary','');
				}
			       
		                if($clientfeeddetail[0]['HsseClientfeedback']['close_date']!='0000-00-00'){
			             $clientfeeddate=explode("-",$clientfeeddetail[0]['HsseClientfeedback']['close_date']);
			             $clsdt=date("d-M-y", mktime(0, 0, 0, $clientfeeddate[1],$clientfeeddate[2],$clientfeeddate[0]));
			             $this->set('feedback_date',$clsdt);
			 
		                }else{
			                $this->set('feedback_date','');
			          }
			   }else{
				$this->set('clientfeedback_summary','');
				 $this->set('feedback_date','');
				
			   }

			}
		  }
		    
		    
	 }
	 function add_report_personal($report_id=null,$personel_id=null){
	         $this->_checkAdminSession();
         	 $this->_getRoleMenuPermission();
		 $this->grid_access();
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
		 
		 $reportdetail = $this->Report->find('all', array('conditions' => array('Report.id' =>base64_decode($report_id))));
                 $clientdetail = $this->HsseClient->find('all', array('conditions' => array('HsseClient.report_id' =>base64_decode($report_id))));
		    
		  if(count($clientdetail)>0){
			 if($clientdetail[0]['HsseClient']['clientreviewed']==3){
			      $this->set('client_feedback',1);
		           }else if($clientdetail[0]['HsseClient']['clientreviewed']!=3){
			      $this->set('client_feedback',0);
			
		          }
			
		  }else{
			 $this->set('client_feedback',0);
		  }
		  	       
		   $this->set('report_number',$reportdetail[0]['Report']['report_no']); 
	          if($personel_id!=''){
			    $personneldetail = $this->HssePersonnel->find('all', array('conditions' => array('HssePersonnel.id' =>base64_decode($personel_id))));
			    $personal_info= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$personneldetail[0]['HssePersonnel']['personal_data'])));
					     
			      $seniority=explode(" ",$personal_info[0]['AdminMaster']['modified']);
			      $snr=explode("-",$seniority[0]);
		              $seniorty=$snr[2]."/".$snr[1]."/".$snr[0];
			      $roll_name=$personal_info[0]['RoleMaster']['role_name'];
			
			      $this->set('roll_name',$roll_name);
			      $this->set('snr',$seniorty);
			      $this->set('heading','Update Personal Data');
		              $this->set('button','Update');
			      $this->set('id',$personneldetail[0]['HssePersonnel']['id']);
			      $this->set('pid',$personneldetail[0]['HssePersonnel']['personal_data']);
		      	      $this->set('report_id',$personneldetail[0]['HssePersonnel']['report_id']);
			      $this->set('time_last_sleep',$personneldetail[0]['HssePersonnel']['last_sleep']);
			      $this->set('time_since_sleep',$personneldetail[0]['HssePersonnel']['since_sleep']);
			      $this->set('person',$personneldetail[0]['HssePersonnel']['personal_data']);
			      $this->set('styledisplay','style="display:block"');
			                                
	                }else{
			    $this->set('heading','Add Personal Data');
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
	 
	      function  hssepersonnelprocess(){
		         $this->layout="ajax";
		         $personnelArray=array();
			 $personalid=explode("~",$this->data['personal_data']);
			 $pid=$this->data['add_report_personnel_form']['pid'];
			 $res='';
		         if($this->data['add_report_personnel_form']['id']!=0){

					      
			      if($pid==$personalid[2]){	   
					  $personnelArray['HssePersonnel']['id']=$this->data['add_report_personnel_form']['id'];
					   $personnelArray['HssePersonnel']['personal_data']=$personalid[2];
					  $res='update';
					  
			      }
			      else if($pid!=$personalid[2]){
				      $personneldetail = $this->HssePersonnel->find('all', array('conditions' => array('HssePersonnel.personal_data'=>$personalid[2],'HssePersonnel.report_id'=>$this->data['report_id'])));
				      
				      if(count($personneldetail)>0){
					echo $res='avl';
					exit;
								
				      }else{
					 $personnelArray['HssePersonnel']['id']=$this->data['add_report_personnel_form']['id'];
					 $personnelArray['HssePersonnel']['personal_data']=$personalid[2];
					$res='update';
				      }
			     }
			   
			 }else{
				$personneldetail = $this->HssePersonnel->find('all', array('conditions' => array('HssePersonnel.report_id' =>$this->data['report_id'],'HssePersonnel.personal_data' =>$personalid[2])));
		
				 if(count($personneldetail)>0){
					 echo  $res='avl';
					  exit;
				 }else{
					 $personnelArray['HssePersonnel']['personal_data']=$personalid[2]; 
					 $res='add';
				 }
			  }
				
		   	  $personnelArray['HssePersonnel']['last_sleep']=$this->data['last_sleep'];
			  $personnelArray['HssePersonnel']['report_id']=$this->data['report_id']; 
		   	 
			  $personnelArray['HssePersonnel']['since_sleep']=$this->data['since_sleep'];
		           if($this->HssePersonnel->save($personnelArray)){
				     echo $res;
		                }else{
				     echo 'fail';
			        }
			        
   
		   exit;
		
	   }
	  public function report_hsse_perssonel_list($id=null){
		
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
		$this->grid_access();		
		$this->layout="after_adminlogin_template";
		$reportdetail = $this->Report->find('all', array('conditions' => array('Report.id' =>base64_decode($id))));
                $clientdetail = $this->HsseClient->find('all', array('conditions' => array('HsseClient.report_id' =>$reportdetail[0]['Report']['id'])));
		    
		  if(count($clientdetail)>0){
			 if($clientdetail[0]['HsseClient']['clientreviewed']==3){
			      $this->set('client_feedback',1);
		           }else if($clientdetail[0]['HsseClient']['clientreviewed']!=3){
			      $this->set('client_feedback',0);
			
		          }
			
		  }else{
			$this->set('client_feedback',0);
		  }
		 $this->set('report_number',$reportdetail[0]['Report']['report_no']);
		 $this->set('report_id',$id);
		 $this->set('id',base64_decode($id));
		if(!empty($this->request->data))
		{
			$action = $this->request->data['HssePersonnel']['action'];
			$this->set('action',$action);
			$limit = $this->request->data['HssePersonnel']['limit'];
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
	
	public function get_all_personnel_list($report_id)
	{
		Configure::write('debug', '2'); 
		$this->layout = "ajax"; 
		$this->_checkAdminSession();
		$condition="";
	
                $condition="HssePersonnel.report_id = $report_id  AND HssePersonnel.isdeleted = 'N'";
		
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
		         $condition .= "AND HssePersonnel.personal_data ='".$addimid."'";
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
		 $count = $this->HssePersonnel->find('count' ,array('conditions' => $condition));
		 $adminA = $this->HssePersonnel->find('all',array('conditions' => $condition,'order' => 'HssePersonnel.id DESC','limit'=>$limit));
		  
		$i = 0;
		foreach($adminA as $rec)
		{
	
			
			if($rec['HssePersonnel']['isblocked'] == 'N')
			{
				$adminA[$i]['HssePersonnel']['blockHideIndex'] = "true";
				$adminA[$i]['HssePersonnel']['unblockHideIndex'] = "false";
				$adminA[$i]['HssePersonnel']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['HssePersonnel']['blockHideIndex'] = "false";
				$adminA[$i]['HssePersonnel']['unblockHideIndex'] = "true";
				$adminA[$i]['HssePersonnel']['isdeletdHideIndex'] = "false";
			}
		        $adminA[$i]['HssePersonnel']['name'] = $rec['AdminMaster']['first_name']." ".$rec['AdminMaster']['last_name'];
			$sam=explode(" ",$rec['AdminMaster']['modified']);
			$snr=explode("-",$sam[0]);
			$seniorty=$snr[2]."/".$snr[1]."/".$snr[0];
			$adminA[$i]['HssePersonnel']['seniority']=$seniorty;
			
			$rollMaster_info= $this->RoleMaster->find('all', array('conditions' => array('RoleMaster.id' =>$rec['AdminMaster']['role_master_id'])));
		        if(count($rollMaster_info)>0){	
			$adminA[$i]['HssePersonnel']['position']=$rollMaster_info[0]['RoleMaster']['role_name'];
			}else{
			$adminA[$i]['HssePersonnel']['position']='';	
			}
		    $i++;
		}
		 
		  if($count==0){
			$adminArray=array();
		  }else{
			 $adminArray = Set::extract($adminA, '{n}.HssePersonnel');
		  }

                  $this->set('total', $count);  //send total to the view
		  $this->set('admins', $adminArray);  //send products to the view
		  //$this->set('status', $action);
	}
	
	
	   function personnel_block($id = null)
	     {
          
			if(!$id)
			{
		           $this->redirect(array('action'=>report_hsse_incident_list), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['HssePersonnel']['id'] = $id;
					  $this->request->data['HssePersonnel']['isblocked'] = 'Y';
					  $this->HssePersonnel->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	      function personnel_unblock($id = null)
	     {
          
			if(!$id)
			{
		           $this->redirect(array('action'=>admin), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['HssePersonnel']['id'] = $id;
					  $this->request->data['HssePersonnel']['isblocked'] = 'N';
					  $this->HssePersonnel->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	       function personnel_delete()
	     {
		      $this->layout="ajax";
		      
		      if($this->data['id']!=''){
			$idArray = explode("^", $this->data['id']);
                           foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['HssePersonnel']['id'] =$id;
					  $this->request->data['HssePersonnel']['isdeleted'] = 'Y';
					  $this->HssePersonnel->save($this->request->data,false);
					  
			       }
		
			       echo 'ok';
			        exit;
		      }else{
			 $this->redirect(array('action'=>report_hsse_list), null, true);
		      }
			       
			      
			       
	
	      }
	
	
	    public function report_hsse_incident_list($id=null){
		
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
                $this->grid_access();		
		$this->layout="after_adminlogin_template";
		$reportdetail = $this->Report->find('all', array('conditions' => array('Report.id' =>base64_decode($id))));
                $this->set('id',base64_decode($id));
		 
		  $clientdetail = $this->HsseClient->find('all', array('conditions' => array('HsseClient.report_id' =>$reportdetail[0]['Report']['id'])));
		    
		  if(count($clientdetail)>0){
			 if($clientdetail[0]['HsseClient']['clientreviewed']==3){
			      $this->set('client_feedback',1);
		           }else if($clientdetail[0]['HsseClient']['clientreviewed']!=3){
			      $this->set('client_feedback',0);
			
		          }
			
		  }else{
			  $this->set('client_feedback',0);
		  }
		 
		 $this->set('report_id',$id);
		 $this->set('report_number',$reportdetail[0]['Report']['report_no']);	 
		if(!empty($this->request->data))
		{
			$action = $this->request->data['HsseIncident']['action'];
			$this->set('action',$action);
			$limit = $this->request->data['HsseIncident']['limit'];
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
	
	
	
	
	
	
	
	public function get_all_incident_list($report_id)
	{
		Configure::write('debug', '2');  
		$this->layout = "ajax"; 
		$this->_checkAdminSession();
		$condition="";
		
		
		$condition="HsseIncident.report_id = $report_id AND HsseIncident.isdeleted = 'N'";
		
                if(isset($_REQUEST['filter'])){
		switch($_REQUEST['filter']){
			case'report_no':
			$condition .= "AND ucase(HsseIncident.".$_REQUEST['filter'].") like '".$_REQUEST['value']."%'";	
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
		 $count = $this->HsseIncident->find('count' ,array('conditions' => $condition));
		// $adminA = $this->HsseIncident->find('all',array('conditions' => $condition,'order' => 'HsseIncident.id DESC','limit'=>$limit));
		$adminA = $this->HsseIncident->find('all',array('conditions' => $condition,'limit'=>$limit));
		  
		$i = 0;
		foreach($adminA as $rec)
		{			
			if($rec['HsseIncident']['isblocked'] == 'N')
			{
				$adminA[$i]['HsseIncident']['blockHideIndex'] = "true";
				$adminA[$i]['HsseIncident']['unblockHideIndex'] = "false";
				$adminA[$i]['HsseIncident']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['HsseIncident']['blockHideIndex'] = "false";
				$adminA[$i]['HsseIncident']['unblockHideIndex'] = "true";
				$adminA[$i]['HsseIncident']['isdeletdHideIndex'] = "false";
			}
			
		    $i++;
		}
		
		  if($count==0){
			$adminArray=array();
		  }else{
			 $adminArray = Set::extract($adminA, '{n}.HsseIncident');
		  }
		  
		  
		  for($i=0;$i<count($adminArray);$i++){
			$adminArray[$i]['inc_no']=$i+1;
			
			if($adminArray[$i]['incident_severity']!=0){
				$incidentSeverity_type= $this->IncidentSeverity->find('all', array('conditions' => array('IncidentSeverity.id' =>$adminArray[$i]['incident_severity'])));
				$adminArray[$i]['incident_severity_type']=$incidentSeverity_type[0]['IncidentSeverity']['type'];
				}else{
				$adminArray[$i]['incident_severity_type']='';	
				}
				
				if($adminArray[$i]['incident_loss']!=0){
		                $incidentLoss_type= $this->Loss->find('all', array('conditions' => array('Loss.id' =>$adminArray[$i]['incident_loss'])));
				$adminArray[$i]['incident_loss_type']=$incidentLoss_type[0]['Loss']['type'];
				}else{
				$adminArray[$i]['incident_loss_type']='N/A';	
				}
				
				if($adminArray[$i]['incident_category']!=0){
				 $incident_category_type= $this->IncidentCategory->find('all', array('conditions' => array('IncidentCategory.id' =>$adminArray[$i]['incident_category'])));
				 $adminArray[$i]['incident_category_type']= $incident_category_type[0]['IncidentCategory']['type'];
				}else{
				  $adminArray[$i]['incident_category_type']='N/A';	
				}
			        
		               if($adminArray[$i]['incident_sub_category']!=0){
			           $incident_sub_category_type= $this->IncidentSubCategory->find('all', array('conditions' => array('IncidentSubCategory.id' =>$adminArray[$i]['incident_sub_category'])));
				   
				   $adminArray[$i]['incident_sub_category_type']=$incident_sub_category_type[0]['IncidentSubCategory']['type'];
			       }else{
				  $incidentdetailHolder[$i]['incident_sub_category_type']='N/A';
			       }
			
		  }
		  
                  $this->set('total', $count);  //send total to the view
		  
		  
		 
		  
		  
		  
		  
		  
		  $this->set('admins', $adminArray);  //send products to the view
		 // $this->set('status', $action);
	}
	 
	    function incident_block($id = null)
	     {
          
			if(!$id)
			{
		           $this->redirect(array('action'=>report_hsse_list), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['HsseIncident']['id'] = $id;
					  $this->request->data['HsseIncident']['isblocked'] = 'Y';
					  $this->HsseIncident->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	      function incident_unblock($id = null)
	     {
          
			if(!$id)
			{
		           $this->redirect(array('action'=>report_hsse_list), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['HsseIncident']['id'] = $id;
					  $this->request->data['HsseIncident']['isblocked'] = 'N';
					  $this->HsseIncident->save($this->request->data,false);				
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
						  $this->request->data['HsseIncident']['id'] =$id;
						  $this->request->data['HsseIncident']['isdeleted'] = 'Y';
						  $this->HsseIncident->save($this->request->data,false);
						  
				       }
			
				       echo 'ok';
				       exit;
		      }else{
			 $this->redirect(array('action'=>report_hsse_list), null, true);
		      }
	 
	      }
	

	 
	 
	 
	 
	 function add_hsse_incident($reoprt_id=null,$incident_id=null){
		$this->_checkAdminSession();
         	$this->_getRoleMenuPermission();
		$this->grid_access();
                $this->layout="after_adminlogin_template";
		 $incidentSeverityDetail = $this->IncidentSeverity->find('all',array('conditions' => array('IncidentSeverity.servrity_type' =>'ssh')));
		$incidentLossDetail = $this->Loss->find('all');
		$this->set('incidentLossDetail',$incidentLossDetail);
		 
		$this->set('incidentSeverityDetail',$incidentSeverityDetail);
		$report = $this->Report->find('all', array('conditions' => array('Report.isdeleted' =>'N')));
		$this->set('incidentSeverityDetail',$incidentSeverityDetail);
		$incidentdetail = $this->HsseIncident->find('all', array('conditions' => array('HsseIncident.id' =>base64_decode($incident_id))));
		$reportdetail = $this->Report->find('all', array('conditions' => array('Report.id' =>base64_decode($reoprt_id))));
                $clientdetail = $this->HsseClient->find('all', array('conditions' => array('HsseClient.report_id' =>$reportdetail[0]['Report']['id'])));
		if(count($clientdetail)>0){ 
			if($clientdetail[0]['HsseClient']['clientreviewed']==3){
			     $this->set('client_feedback',1);
			  }else if($clientdetail[0]['HsseClient']['clientreviewed']!=3){
			     $this->set('client_feedback',0);
		       
			 }
			
		}else{
		   $this->set('client_feedback',0);
		}
		   
		   		       
		
		$this->set('report_number',$reportdetail[0]['Report']['report_no']); 
	 	if(count($incidentdetail)>0){
			if($incidentdetail[0]['HsseIncident']['incident_loss']!=0){
			        $this->set('incident_loss',$incidentdetail[0]['HsseIncident']['incident_loss']);
					if($incidentdetail[0]['HsseIncident']['incident_category']==''){
					       $this->set('incident_category','');
					}else{
					       $this->set('incident_category',$incidentdetail[0]['HsseIncident']['incident_category']);
					}
				
			
			        $incidentCategoryDetail = $this->IncidentCategory->find('all',array('conditions' => array('IncidentCategory.loss_id' =>$incidentdetail[0]['HsseIncident']['incident_loss'])));
			        if(isset($incidentCategoryDetail[0]['IncidentCategory']['id'])){
			                 $this->set('incidentCategoryDetail',$incidentCategoryDetail);
			        }else{
			                  $this->set('incidentCategoryDetail',array());
			       
			        }
			   
			       if($incidentdetail[0]['HsseIncident']['incident_sub_category']==''){
			               $this->set('incident_sub_category','');
			        }else{
				       $this->set('incident_sub_category',$incidentdetail[0]['HsseIncident']['incident_sub_category']);
			        }
			    
			   
		               $incidentSubCategoryDetail = $this->IncidentSubCategory->find('all',array('conditions' => array('IncidentSubCategory.loss_id' =>$incidentCategoryDetail[0]['IncidentCategory']['id'])));
			       if(count($incidentSubCategoryDetail)>0)
				{
				     $this->set('incidentSubCategoryDetail',$incidentSubCategoryDetail);
				}else{
				     $this->set('incidentSubCategoryDetail',array());
				     
	            		}
			
			   
			    
			   
		        }else{
				 $this->set('incident_loss',0);
				 $this->set('incident_category','');
				 $this->set('incident_sub_category','');
				
			  }
					
				if($incidentdetail[0]['HsseIncident']['date_incident']!=''){
					$inc=explode("-",$incidentdetail[0]['HsseIncident']['date_incident']);
					$date_incident=$inc[1]."-".$inc[2]."-".$inc[0];
					$this->set('date_incident',$date_incident);
				}else{
					$this->set('date_incident','');	
				} 
			        $this->set('time_incident',$incidentdetail[0]['HsseIncident']['incident_time']);
			        $this->set('incident_summary',$incidentdetail[0]['HsseIncident']['incident_summary']);
			        $this->set('report_id',$incidentdetail[0]['HsseIncident']['report_id']);
		                $this->set('detail',$incidentdetail[0]['HsseIncident']['detail']);
			        $this->set('heading','Edit Incident Data');
			        $this->set('button','Update');
		                $this->set('incident_id',base64_decode($incident_id));
			        $this->set('incident_severity',$incidentdetail[0]['HsseIncident']['incident_severity']);
                                $this->set('incident_no',$incidentdetail[0]['HsseIncident']['incident_no']);      
                          
	        }else{
			       $incidentCategoryDetail = $this->IncidentCategory->find('all',array('conditions' => array('IncidentCategory.loss_id' =>$incidentLossDetail[0]['Loss']['id'])));
		               $incidentSubCategoryDetail = $this->IncidentSubCategory->find('all',array('conditions' => array('IncidentSubCategory.loss_category_id' =>$incidentCategoryDetail[0]['IncidentCategory']['id'])));
			       $incidentdetail = $this->HsseIncident->find('all', array('conditions' => array('HsseIncident.report_id' =>base64_decode($reoprt_id))));
			       if(count($incidentdetail)>0){
				     $incident_no=count($incidentdetail)+1;
			       }else{
				     $incident_no=1;
			       }
			       
		               $this->set('incidentCategoryDetail',$incidentCategoryDetail);
		               $this->set('incidentSubCategoryDetail',$incidentSubCategoryDetail);	
		               $this->set('heading','Add Incident Data');
		               $this->set('button','Submit');
			       $this->set('incident_id',0);
			       $this->set('incident_no',$incident_no);
			       $this->set('report_id',base64_decode($reoprt_id));
			       $this->set('incident_severity',''); 
			       $this->set('date_incident','');
			       $this->set('incident_category','');
			       $this->set('incident_sub_category','');
			       $this->set('incident_loss','');
			       $this->set('incident_summary','');
			       $this->set('detail','');
			       $this->set('time_incident','');
			  }
	 }
	 
	 function  hsseincidentprocess(){
		         $this->layout="ajax";
	                 $identArray=array();
			 
			
		         $incidentdetail = $this->HsseIncident->find('all', array('conditions' => array('HsseIncident.report_id' =>$this->data['report_id'])));
				if($this->data['add_report_incident_form']['id']!=0){
				       $res='update';
				       $incidentArray['HsseIncident']['id']=$this->data['add_report_incident_form']['id'];
				}else{
				       $res='add';
				}
		   	  $incidentArray['HsseIncident']['incident_time']=$this->data['incident_time'];
		   		if($this->data['date_incident']!=''){
				       $dateIncident=explode("-",$this->data['date_incident']);
				       $incidentArray['HsseIncident']['date_incident']=$dateIncident[2]."-".$dateIncident[0]."-".$dateIncident[1];
			        }else{
				       $incidentArray['HsseIncident']['date_incident']='';	
				}
				if($this->data['incident_severity']!=0){	
				       $incidentArray['HsseIncident']['incident_severity']=$this->data['incident_severity'];
				}else{
				       $incidentArray['HsseIncident']['incident_severity']=0;	
				}
			  $incidentArray['HsseIncident']['incident_loss']=$this->data['incident_loss'];
			  if(isset($this->data['incident_category'])){
				$incidentArray['HsseIncident']['incident_category']=$this->data['incident_category'];
			  }else{
			        $incidentArray['HsseIncident']['incident_category']=0;     
			  }
			  if(isset($this->data['incident_sub_category'])){
				 $incidentArray['HsseIncident']['incident_sub_category']=$this->data['incident_sub_category'];
			  }else{
			        $incidentArray['HsseIncident']['incident_sub_category']=0;     
			  }
		          
		         
		          $incidentArray['HsseIncident']['incident_summary']=$this->data['incident_summary'];
			  $incidentArray['HsseIncident']['report_id']=$this->data['report_id'];
				if($this->data['detail']!=''){
				      $incidentArray['HsseIncident']['detail']=$this->data['detail'];
				}else{
				      $incidentArray['HsseIncident']['detail']='';
				}
				if($this->data['incident_summary']!=''){
				      $incidentArray['HsseIncident']['incident_summary']=$this->data['incident_summary'];
				}else{
				      $incidentArray['HsseIncident']['incident_summary']='';
				}
		         $incidentArray['HsseIncident']['incident_no']=$this->data['incident_no'];
				
		          
                               if($this->HsseIncident->save($incidentArray)){
				     echo $res;
		                }else{
				     echo 'fail';
			        }
				
			
		  
		   exit;
		
	 }
	 
	 
	 function displaycontentforloss(){
		   $this->layout="ajax"; 
			if(!empty($this->data)){
			 switch($this->data['type']){
			   case'incident_loss':
			        $incidentCategoryDetail=$this->IncidentCategory->find('all', array('conditions' => array('loss_id' =>$this->data['id'])));
					if(count($incidentCategoryDetail)>0){ 
					   $this->set('incidentCategoryDetail',$incidentCategoryDetail);
					}else{
					   $this->set('incidentCategoryDetail',array());	
					}
			        $this->set('type',$this->data['type']);
			   break;
			   case'incident_category':
				$incidentSubCategoryDetail=$this->IncidentSubCategory->find('all', array('conditions' => array('loss_category_id' =>$this->data['id'])));
					if(count($incidentSubCategoryDetail)>0){
					 
					        $this->set('incidentSubCategoryDetail',$incidentSubCategoryDetail);
			                                    
					}else{
						$this->set('incidentSubCategoryDetail',array());	
					}
                           $this->set('type',$this->data['type']);						
			   break;
			   
			 }
			 
			}
			
		}
	     
	       function add_hsse_attachment($reoprt_id=null,$attachment_id=null){
		        $this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();   
		        $this->layout="after_adminlogin_template";

			$clientdetail = $this->HsseClient->find('all', array('conditions' => array('HsseClient.report_id' =>base64_decode($reoprt_id))));
			$reportList = $this->Report->find('all', array('conditions' => array('Report.isdeleted' =>'N')));
			$this->set('reportList',$reportList);
			   
			 if(count($clientdetail)>0){
				if($clientdetail[0]['HsseClient']['clientreviewed']==3){
				     $this->set('client_feedback',1);
				  }else if($clientdetail[0]['HsseClient']['clientreviewed']!=3){
				     $this->set('client_feedback',0);
			       
				 }
			       
			 } else{
				$this->set('client_feedback',0);
			 }
                             
    
			
			if($attachment_id!=''){
			       $attchmentdetail = $this->HsseAttachment->find('all', array('conditions' => array('HsseAttachment.id' =>base64_decode($attachment_id))));
			       
			       $imagepath=$this->file_edit_list($attchmentdetail[0]['HsseAttachment']['file_name'],'HsseAttachment',$attchmentdetail);
			       
				
				$this->set('heading','Update File');
			        $this->set('attachment_id',$attchmentdetail[0]['HsseAttachment']['id']);
			        $this->set('description',$attchmentdetail[0]['HsseAttachment']['description']);
			        $this->set('button','Update');
			        $this->set('imagepath',$imagepath);
			        $this->set('imagename',$attchmentdetail[0]['HsseAttachment']['file_name']);
				$this->set('attachmentstyle','style="display:block;"');
		
			}else{
				$this->set('ridHolder',array());
			  	$this->set('heading','Add attachment');
			        $this->set('attachment_id',0);
			        $this->set('description','');
			        $this->set('button','Add');
			        $this->set('imagepath','');
			        $this->set('imagename','');
				$this->set('attachmentstyle','style="display:none;"');
				$this->set('edit_id',0);
			        $this->set('id_holder',0);
				
			}
			

			$this->set('report_id',base64_decode($reoprt_id));
			
			$reportdetail = $this->Report->find('all', array('conditions' => array('Report.id' =>base64_decode($reoprt_id))));
			$this->set('report_number',$reportdetail[0]['Report']['report_no']);        
			
	       }
	        function uploadimage($upparname=NULL,$deleteImageName=NULL){
		        $this->layout="ajax";
			$allowed_image =$this->image_upload_type();
			$this->upload_image_func($upparname,'Reports',$allowed_image);
			exit;
	         }
	        function  hsseattachmentprocess(){
		         $this->layout="ajax";
		         $attachmentArray=array();
		 
		        if($this->data['Reports']['id']!=0){
				$res='update';
				$attachmentArray['HsseAttachment']['id']=$this->data['Reports']['id'];
			 }else{
				 $res='add';
			  }
			   if($this->data['attachment_description']!=''){
				$attachmentArray['HsseAttachment']['description']=$this->data['attachment_description'];
			   }else{
				$attachmentArray['HsseAttachment']['description']='';
			   }
		   	   
			   $attachmentArray['HsseAttachment']['file_name']=$this->data['hiddenFile'];
			   $attachmentArray['HsseAttachment']['report_id']=$this->data['report_id'];
			   if($this->HsseAttachment->save($attachmentArray)){
				     echo $res;
		                }else{
				     echo 'fail';
			        }
			
                         
		   exit;
		
	        }
		
		
		
		
		public function report_hsse_attachment_list($id=null){
			$this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();   
      			$this->layout="after_adminlogin_template";
		
		        $reportdetail = $this->Report->find('all', array('conditions' => array('Report.id' =>base64_decode($id))));
          	        $clientdetail = $this->HsseClient->find('all', array('conditions' => array('HsseClient.report_id' =>$reportdetail[0]['Report']['id'])));
		    
				if(count($clientdetail)>0){
				       if($clientdetail[0]['HsseClient']['clientreviewed']==3){
					    $this->set('client_feedback',1);
					 }else if($clientdetail[0]['HsseClient']['clientreviewed']!=3){
					    $this->set('client_feedback',0);
				      
					}
				      
				}else{
					$this->set('client_feedback',0);
				}
			$this->set('report_number',$reportdetail[0]['Report']['report_no']);	       
	                $this->set('id',base64_decode($id));
				if(!empty($this->request->data))
				{
					$action = $this->request->data['HsseAttachment']['action'];
					$this->set('action',$action);
					$limit = $this->request->data['HsseAttachment']['limit'];
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
		Configure::write('debug', '2'); 
		$this->layout = "ajax"; 
		$this->_checkAdminSession();
		$condition="";
		
		$condition="HsseAttachment.report_id = $report_id  AND  HsseAttachment.isdeleted = 'N'";
		
		
                if(isset($_REQUEST['filter'])){
			$condition .= "AND ucase(HsseAttachment.".$_REQUEST['filter'].") like '".trim($_REQUEST['value'])."%'";	
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			//$condition .= " order by Category.id DESC";
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}

	   	$adminArray = array();	
		 $count = $this->HsseAttachment->find('count' ,array('conditions' => $condition));
		 $adminA = $this->HsseAttachment->find('all',array('conditions' => $condition,'order' => 'HsseAttachment.id DESC','limit'=>$limit));
		  
		$i = 0;
		foreach($adminA as $rec)
		{			
			if($rec['HsseAttachment']['isblocked'] == 'N')
			{
				$adminA[$i]['HsseAttachment']['blockHideIndex'] = "true";
				$adminA[$i]['HsseAttachment']['unblockHideIndex'] = "false";
				$adminA[$i]['HsseAttachment']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['HsseAttachment']['blockHideIndex'] = "false";
				$adminA[$i]['HsseAttachment']['unblockHideIndex'] = "true";
				$adminA[$i]['HsseAttachment']['isdeletdHideIndex'] = "false";
			}
			
			//$this-->attachment_list(HsseAttachment,$rec);
		    $adminA[$i]['HsseAttachment']['image_src']=$this->attachment_list('HsseAttachment',$rec);
		    
		     $i++;
		     
		}

		  if(count($adminA)>0){
		      $adminArray = Set::extract($adminA, '{n}.HsseAttachment');
		  }else{
		      $adminArray =array();
		  }
		  $this->set('total', $count);  //send total to the view
		  $this->set('admins', $adminArray);  //send products to the view
		  //$this->set('status', $action);
	}
	 
	    function attachment_block($id = null)
	     {
          
			if(!$id)
			{
		           $this->redirect(array('action'=>admin), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['HsseAttachment']['id'] = $id;
					  $this->request->data['HsseAttachment']['isblocked'] = 'Y';
					  $this->HsseAttachment->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	      function attachment_unblock($id = null)
	     {
          
			if(!$id)
			{
		           $this->redirect(array('action'=>admin), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['HsseAttachment']['id'] = $id;
					  $this->request->data['HsseAttachment']['isblocked'] = 'N';
					  $this->HsseAttachment->save($this->request->data,false);				
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
				
					  $this->request->data['HsseAttachment']['id'] = $id;
					  $this->request->data['HsseAttachment']['isdeleted'] = 'Y';
					  $this->HsseAttachment->save($this->request->data,false);
					  
			       }
			       echo 'ok';
			   
			       exit;			 
		     
	      }
	      
	      function add_hsse_remidial($report_id=null,$remidial_id=null){
		 $this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
		 $this->grid_access();
		 $this->layout="after_adminlogin_template";
		 $priority = $this->Priority->find('all');
		 $userDetail = $this->AdminMaster->find('all');
		 $this->set('priority',$priority);
		 $this->set('responsibility',$userDetail);
		 $this->set('created_by',$_SESSION['adminData']['AdminMaster']['first_name']." ".$_SESSION['adminData']['AdminMaster']['last_name']);
		 $reportdetail = $this->Report->find('all', array('conditions' => array('Report.id' =>base64_decode($report_id))));
	         $clientdetail = $this->HsseClient->find('all', array('conditions' => array('HsseClient.report_id' =>base64_decode($report_id))));
		    
		  if(count($clientdetail)>0){
			 if($clientdetail[0]['HsseClient']['clientreviewed']==3){
			      $this->set('client_feedback',1);
		           }else if($clientdetail[0]['HsseClient']['clientreviewed']!=3){
			      $this->set('client_feedback',0);
			
		          }
			
		  }else{
			$this->set('client_feedback',0);
		  }
		 $this->set('report_number',$reportdetail[0]['Report']['report_no']);      
		 $this->set('reportno',base64_decode($report_id));
		 $countRem= $this->HsseRemidial->find('count',array('conditions'=>array('HsseRemidial.report_no'=>base64_decode($report_id))));      
		 if($remidial_id==''){
			 $this->set('id', 0);
			if($countRem!=0){
		                 $countRem=$countRem+1;	
			 }else{
				 $countRem=1;		
			}
			 $this->set('countRem', $countRem);
			 $this->set('heading', 'Add Remedial Action Item' );
			 $this->set('countRem', $countRem);
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
		         $remidialData= $this->HsseRemidial->find('all',array('conditions'=>array('HsseRemidial.id'=>base64_decode($remidial_id))));
			 $this->set('countRem',  $remidialData[0]['HsseRemidial']['remedial_no']);
			 $this->set('id', base64_decode($remidial_id));
			 $rempriority = $this->Priority->find('all',array('conditions'=>array('Priority.id'=>base64_decode($remidial_id))));
			 $this->set('heading', 'Edit Remedial Action Item' );
			 $this->set('button', 'Update');
			 $remidialcreate=explode("-",$remidialData[0]['HsseRemidial']['remidial_create']);
			 $this->set('remidial_create',$remidialcreate[1].'-'.$remidialcreate[2].'-'.$remidialcreate[0]);
			 $this->set('remidial_summery',$remidialData[0]['HsseRemidial']['remidial_summery']);
			 $this->set('remidial_style','style="display:block"');
			 $this->set('remidial_action',$remidialData[0]['HsseRemidial']['remidial_action']);
			 $this->set('remidial_priority',$remidialData[0]['HsseRemidial']['remidial_priority']);
			 $this->set('remidial_closure_target',$remidialData[0]['HsseRemidial']['remidial_closure_target']);
			 $this->set('remidial_responsibility',$remidialData[0]['HsseRemidial']['remidial_responsibility']);
			if($remidialData[0]['HsseRemidial']['remidial_closure_date']!='0000-00-00'){
			 $closerdate=explode("-",$remidialData[0]['HsseRemidial']['remidial_closure_date']);
			 $this->set('remidial_closure_date',$closerdate[1].'/'.$closerdate[2].'/'.$closerdate[0]);
			 $this->set('remidial_closer_summary',$remidialData[0]['HsseRemidial']['remidial_closer_summary']);
			 $this->set('remidial_button_style','style="display:none"');
			}else{
			  $this->set('remidial_closer_summary',' ');
			  $this->set('remidial_closure_date','');
			  $this->set('remidial_button_style','style="display:block"');
			}
			 
			 $this->set('remidial_reminder_data',$remidialData[0]['HsseRemidial']['remidial_reminder_data']);
			 }
		}
		
		function datecalculate(){
			$this->layout="ajax";
			$rempriority = $this->Priority->find('all',array('conditions'=>array('Priority.id'=>$this->data['remidial_priority'])));
			$ymd_format_array=explode('-',$this->data['remidial_create']);
			
			if($rempriority[0]['Priority']['id']==5){
				$date=date_create($this->data['remidial_create']);
				echo date_format($date,'d/m/Y H:i:s');
				exit;
			}else{

				switch($rempriority[0]['Priority']['time_type']){
					case'days':
						 
						       echo date('d/m/Y H:i:s', mktime(0,0,0,$ymd_format_array[0],$ymd_format_array[1]+$rempriority[0]['Priority']['time'],$ymd_format_array[2]));
					break;
					case'hrs':
			
						       $time=$rempriority[0]['Priority']['time'];
						       echo date('d/m/Y H:i:s', mktime($time,0,0,$ymd_format_array[0],$ymd_format_array[1],$ymd_format_array[2]));
									
				         break;
					
				}
			}
			exit;
		}
		
			     

		
		
		
		function remidialprocess(){
			$this->layout="ajax";
			$this->_checkAdminSession();
			
	                $remidialArray=array();
			 
				if($this->data['add_report_remidial_form']['id']!=0){
				       $res='update';
				       $remidialArray['HsseRemidial']['id']=$this->data['add_report_remidial_form']['id'];
				}else{
					$res='add';
				 }
			   $remidialdate=explode("-",$this->data['remidial_create']);
			   $remidialArray['HsseRemidial']['remidial_create']=$remidialdate[2].'-'.$remidialdate[0].'-'.$remidialdate[1];
			   $remidialArray['HsseRemidial']['remidial_createby']=$_SESSION['adminData']['AdminMaster']['id']; 
		           $remidialArray['HsseRemidial']['report_no']=$this->data['report_no'];
			   $remidialArray['HsseRemidial']['remedial_no']=$this->data['countRem'];
			   $prority=explode("~",$this->data['remidial_priority']);
			   $remidialArray['HsseRemidial']['remidial_priority']=$prority[0];
			   $remidialArray['HsseRemidial']['remidial_responsibility']=$this->data['responsibility'];
			   $remidialArray['HsseRemidial']['remidial_summery']=$this->data['add_report_remidial_form']['remidial_summery'];
			   $remidialArray['HsseRemidial']['remidial_closer_summary']=$this->data['add_report_remidial_form']['remidial_closer_summary'];
			   $remidialArray['HsseRemidial']['remidial_action']=$this->data['add_report_remidial_form']['remidial_action'];
			   $remidialArray['HsseRemidial']['remidial_reminder_data']=$this->data['add_report_remidial_form']['remidial_reminder_data'];
			   $remidialArray['HsseRemidial']['remidial_closure_target']=$this->data['add_report_remidial_form']['remidial_closure_target'];
			   if(isset($this->data['remidial_closure_date']) && $this->data['remidial_closure_date']!=''){
			     $closerdate=explode("-",$this->data['remidial_closure_date']);
			     $remidialArray['HsseRemidial']['remidial_closure_date']=$closerdate[0].'-'.$closerdate[1].'-'.$closerdate[2];
			     }else{
			     $remidialArray['HsseRemidial']['remidial_closure_date']=' ';
			     }
			     
			     $createON = $remidialArray['HsseRemidial']['remidial_create'].' 00:00:00';
		             $explodeCTR=explode(" ",$remidialArray['HsseRemidial']['remidial_closure_target']);
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
				
				
				
				   $deleteREL = "DELETE FROM `remidial_email_lists` WHERE  `remedial_no` = {$remidialArray['HsseRemidial']['remedial_no']} AND `report_id` = {$remidialArray['HsseRemidial']['report_no']} AND `report_type`='hsse'";
                                   $deleteval=$this->RemidialEmailList->query($deleteREL);
				   
				   
				 if($this->data['remidial_closure_date']==''){  
				   
				   

				       
				       for($d=0;$d<count($dateHolder);$d++){
					    
						       $remidialEmailList=array();
						       $userdetail = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$this->data['responsibility'])));
						       $fullname=$userdetail[0]['AdminMaster']['first_name'].' '.$userdetail[0]['AdminMaster']['last_name'];
						       $to=$userdetail[0]['AdminMaster']['admin_email'];
						       $remidialEmailList['RemidialEmailList']['report_id']=$remidialArray['HsseRemidial']['report_no'];
						       $remidialEmailList['RemidialEmailList']['remedial_no']=$remidialArray['HsseRemidial']['remedial_no'];
						       $remidialEmailList['RemidialEmailList']['report_type']='hsse';
						       $remidialEmailList['RemidialEmailList']['email']=$to;
						       $remidialEmailList['RemidialEmailList']['status']='N';
						       $remidialEmailList['RemidialEmailList']['email_date']=$dateHolder[$d];
						       $remidialEmailList['RemidialEmailList']['send_to']=$userdetail[0]['AdminMaster']['id'];
						       
						       $this->RemidialEmailList->create();
						       $this->RemidialEmailList->save($remidialEmailList);
					       
					 }
					 
					 
		                }
                          	 if($this->HsseRemidial->save($remidialArray)){
				     echo $res.'~hsse';
		                }else{
				     echo 'fail';
			        }
			        
                         

			exit;
			
		}
		
		function remidial_email_view($id,$remedial_no,$report_id){
			 $this->_checkAdminSession();
		         $this->_getRoleMenuPermission();
                         $this->grid_access();
			 $this->layout="ajax";
			 $remidialData= $this->HsseRemidial->find('all',array('conditions'=>array('HsseRemidial.report_no'=>$report_id,'HsseRemidial.remedial_no'=>$remedial_no)));
			 $reportData= $this->Report->find('all',array('conditions'=>array('Report.id'=>$remidialData[0]['HsseRemidial']['report_no'])));
			 $userData= $this->AdminMaster->find('all',array('conditions'=>array('AdminMaster.id'=>$remidialData[0]['HsseRemidial']['remidial_responsibility'])));
			 $this->set('fullname',$userData[0]['AdminMaster']['first_name']." ".$userData[0]['AdminMaster']['last_name']);
			 $this->set('report_no',$reportData[0]['Report']['report_no']);
			 $this->set('remidialData',$remidialData);
		
		}
		function report_hsse_remidial_list($id=null){
			//$month = 'Feb';
                        //echo date('m', strtotime($month));
			$this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();
			$this->layout="after_adminlogin_template";
			$this->set('report_no',base64_decode($id));
			$reportdetail = $this->Report->find('all', array('conditions' => array('Report.id' =>base64_decode($id))));
			$this->set('id',base64_decode($id));
	                $clientdetail = $this->HsseClient->find('all', array('conditions' => array('HsseClient.report_id' =>base64_decode($id))));
		    
			if(count($clientdetail)>0){
			       if($clientdetail[0]['HsseClient']['clientreviewed']==3){
				    $this->set('client_feedback',1);
				 }else if($clientdetail[0]['HsseClient']['clientreviewed']!=3){
				    $this->set('client_feedback',0);
			      
				}
			      
			}else{
			      $this->set('client_feedback',0);
			}
			
			$this->set('report_val',$id);
			$this->set('report_number',$reportdetail[0]['Report']['report_no']);
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
		
		}
	
	
		public function get_all_remidial_list($report_id)
		{
			Configure::write('debug', '2');  //set debug to 0 for this function because debugging info breaks the XMLHttpRequest
			$this->layout = "ajax"; //this tells the controller to use the Ajax layout instead of the default layout (since we're using ajax . . .)
			//pr($_REQUEST);
			$this->_checkAdminSession();
			$condition="";

			
			$condition="HsseRemidial.report_no = $report_id AND HsseRemidial.isdeleted = 'N'";
			
			if(isset($_REQUEST['filter'])){
				//echo '<pre>';
				//print_r($_REQUEST);
		
				switch($this->data['filter']){
					case'create_on':
						$explodemonth=explode('/',$this->data['value']);
						$day=$explodemonth[0];
						$month=date('m', strtotime($explodemonth[1]));
						$year="20$explodemonth[2]";
						 $createon=$year."-".$month."-".$day;
						$condition .= "AND HsseRemidial.remidial_create ='".$createon."'";	
					break;
				        case'remidial_create_name':
					     $spliNAME=explode(" ",$_REQUEST['value']);
			                     $spliLname=$spliNAME[count($spliNAME)-1];
			                     $spliFname=$spliNAME[0];
		                             $adminCondition="AdminMaster.first_name like '%".$spliFname."%' AND AdminMaster.last_name like '%".$spliLname."%'";
			                     $userDetail = $this->AdminMaster->find('all',array('conditions'=>$adminCondition));
					     $addimid=$userDetail[0]['AdminMaster']['id'];
		                             $condition .= "AND HsseRemidial.remidial_createby ='".$addimid."'";
						
					break;
				        case'responsibility_person':
					     $spliNAME=explode(" ",$_REQUEST['value']);
			                     $spliLname=$spliNAME[count($spliNAME)-1];
			                     $spliFname=$spliNAME[0];
		                             $adminCondition="AdminMaster.first_name like '%".$spliFname."%' AND AdminMaster.last_name like '%".$spliLname."%'";
			                     $userDetail = $this->AdminMaster->find('all',array('conditions'=>$adminCondition));
					     $addimid=$userDetail[0]['AdminMaster']['id'];
		                             $condition .= "AND HsseRemidial.remidial_responsibility ='".$addimid."'";	
					break;
				        case'remidial_priority_name':
					     $priorityCondition = "Priority.type='".trim($_REQUEST['value'])."'";
			                     $priorityDetail = $this->Priority->find('all',array('conditions'=>$priorityCondition));
					     $condition .= "AND HsseRemidial.remidial_priority ='".$priorityDetail[0]['Priority']['id']."'";	
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
			 $count = $this->HsseRemidial->find('count' ,array('conditions' => $condition));
			 $adminA = $this->HsseRemidial->find('all',array('conditions' => $condition,'order' => 'HsseRemidial.id DESC','limit'=>$limit));
	
			$i = 0;
			foreach($adminA as $rec)
			{			
			if($rec['HsseRemidial']['isblocked'] == 'N')
				{
					$adminA[$i]['HsseRemidial']['blockHideIndex'] = "true";
					$adminA[$i]['HsseRemidial']['unblockHideIndex'] = "false";
					$adminA[$i]['HsseRemidial']['isdeletdHideIndex'] = "true";
				}else{
					$adminA[$i]['HsseRemidial']['blockHideIndex'] = "false";
					$adminA[$i]['HsseRemidial']['unblockHideIndex'] = "true";
					$adminA[$i]['HsseRemidial']['isdeletdHideIndex'] = "false";
				}
				
			    $create_on=explode("-",$rec['HsseRemidial']['remidial_create']);
			    $adminA[$i]['HsseRemidial']['create_on']=date("d/M/y", mktime(0, 0, 0, $create_on[1], $create_on[2], $create_on[0]));
			    $lastupdated=explode(" ", $rec['HsseRemidial']['modified']);
			    $lastupdatedate=explode("-",$lastupdated[0]);
			    $adminA[$i]['HsseRemidial']['lastupdate']=date("d/M/y", mktime(0, 0, 0, $lastupdatedate[1], $lastupdatedate[2], $lastupdatedate[0]));
			    $createdate=explode("-",$rec['HsseRemidial']['remidial_create']);
			    $adminA[$i]['HsseRemidial']['createRemidial']=date("d/M/y", mktime(0, 0, 0, $createdate[1], $createdate[2], $createdate[0]));
			    $adminA[$i]['HsseRemidial']['remidial_priority_name'] ='<font color='.$rec['Priority']['colorcoder'].'>'.$rec['Priority']['type'].'</font>';
			    $adminA[$i]['HsseRemidial']['remidial_create_name'] = $rec['AdminMaster']['first_name']." ".$rec['AdminMaster']['last_name'];
			    $user_detail_reponsibilty= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$rec['HsseRemidial']['remidial_responsibility'])));
			    $adminA[$i]['HsseRemidial']['responsibility_person']=$user_detail_reponsibilty[0]['AdminMaster']['first_name']."  ".$user_detail_reponsibilty[0]['AdminMaster']['last_name'];
			    if($rec['HsseRemidial']['remidial_closure_date']!='0000-00-00'){
			       $rem_cls_date=explode("-",$rec['HsseRemidial']['remidial_closure_date']);
			       $adminA[$i]['HsseRemidial']['closure']=date("d/M/y", mktime(0, 0, 0, $rem_cls_date[1], $rem_cls_date[2], $rem_cls_date[0]));
			    }else{
			        $adminA[$i]['HsseRemidial']['closure']='';	
			    }
			    $i++;
			}
			
			if($count==0){
			   $adminArray=array();
		        }else{
			   $adminArray = Set::extract($adminA, '{n}.HsseRemidial');
		        }
			 

			  
			  
			  $this->set('total', $count);  //send total to the view
			  $this->set('admins', $adminArray);  //send products to the view
			  //$this->set('status', $action);
		}
		 
	    function remidial_block($id = null)
	     {

	     
	                if(!$id)
			{
		           $this->redirect(array('action'=>report_hsse_list), null, true);
			}
		       else
		       
		       
		       {
			    echo  $id;
	                 	$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['HsseRemidial']['id'] = $id;
					  $this->request->data['HsseRemidial']['isblocked'] = 'Y';
					  $this->HsseRemidial->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	     
	      }
	      
	      function remidial_unblock($id = null)
	     {
                  
			if(!$id)
			{
		           $this->redirect(array('action'=>admin), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['HsseRemidial']['id'] = $id;
					  $this->request->data['HsseRemidial']['isblocked'] = 'N';
					  $this->HsseRemidial->save($this->request->data,false);				
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
				
				 $remidialData= $this->HsseRemidial->find('all',array('conditions'=>array('HsseRemidial.id'=>$id)));
				 $deleteHsse_remidials_email = "DELETE FROM `remidial_email_lists` WHERE `report_id` = {$remidialData[0]['HsseRemidial']['report_no']}  AND  `remedial_no` = {$remidialData[0]['HsseRemidial']['remedial_no']} AND `report_type`='hsse'";
                                 $dHRem=$this->RemidialEmailList->query($deleteHsse_remidials_email);
				 $deleteHsse_remidials = "DELETE FROM `hsse_remidials` WHERE `id` = {$id}";
                                 $dHR=$this->HsseRemidial->query($deleteHsse_remidials);
					  
					  
			       }
			       echo 'ok';
			      
			   
			       exit;			 
		     
	      }
	      
	      
	      
	      	function hsse_remedila_email_list($id=null){
			$this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();
			$this->layout="after_adminlogin_template";
			$this->set('report_no',base64_decode($id));
			$reportdetail = $this->Report->find('all', array('conditions' => array('Report.id' =>base64_decode($id))));
			$this->set('id',base64_decode($id));
	                $clientdetail = $this->HsseClient->find('all', array('conditions' => array('HsseClient.report_id' =>base64_decode($id))));
		    
			if(count($clientdetail)>0){
			       if($clientdetail[0]['HsseClient']['clientreviewed']==3){
				    $this->set('client_feedback',1);
				 }else if($clientdetail[0]['HsseClient']['clientreviewed']!=3){
				    $this->set('client_feedback',0);
			      
				}
			      
			}else{
			      $this->set('client_feedback',0);
			}
			
			$this->set('report_val',$id);
			$this->set('report_number',$reportdetail[0]['Report']['report_no']);
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
		
			$condition="RemidialEmailList.report_id = $report_id AND report_type='hsse'";
			
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
				
			    $remC= $this->HsseRemidial->find('all', array('conditions' => array('HsseRemidial.report_no' =>$rec['RemidialEmailList']['report_id'],'HsseRemidial.remedial_no' =>$rec['RemidialEmailList']['remedial_no'])));
			    
			    $rrd=explode(" ",$remC[0]['HsseRemidial']['remidial_reminder_data']);
			    $rrdE=explode("/",$rrd[0]);
			    $adminA[$i]['RemidialEmailList']['reminder_data']=date("d/M/y", mktime(0, 0, 0, $rrdE[1], $rrdE[0], $rrdE[2]));
			    
			    $create_on=explode("-",$remC[0]['HsseRemidial']['remidial_create']);
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
				
                                     $dHRem=$this->RemidialEmailList->query($deleteHsse_remidials_email);
				     $deleteRem = "DELETE FROM `remidial_email_lists` WHERE `id` = {$id}";
                                     $deleteval=$this->RemidialEmailList->query($deleteRem);
						  
			       }
			       echo 'ok';
			      
			   
			       exit;			 
		     
	       }
	      
	      
	      
	      
	      
	      
	      function add_hsse_investigation($report_id,$investigation_id=null){
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
						  $user_detail[$u]['AdminMaster']['position_seniorty']=$user_detail[$u]['AdminMaster']['first_name']." ".$user_detail[$u]['AdminMaster']['last_name'].'~'.$user_detail[$u]['RoleMaster']['role_name'].'~'.$user_detail[$u]['AdminMaster']['user_seniority'].'~'.$user_detail[$u]['AdminMaster']['id'].'~'.$user_detail[$u]['AdminMaster']['position'];
						    
					}
			
			$clientdetail = $this->HsseClient->find('all', array('conditions' => array('HsseClient.report_id' =>base64_decode($report_id))));
		    
			if(count($clientdetail)>0){
			       if($clientdetail[0]['HsseClient']['clientreviewed']==3){
				    $this->set('client_feedback',1);
				 }else if($clientdetail[0]['HsseClient']['clientreviewed']!=3){
				    $this->set('client_feedback',0);
			      
				}
			      
			}else{
				  $this->set('client_feedback',0);
			}
		
		 
	                $this->set('userDetail',$user_detail);
			$this->set('report_id',base64_decode($report_id));
		       
			$lossDetail = $this->Loss->find('all');
			$reportdetail = $this->Report->find('all', array('conditions' => array('Report.id' =>base64_decode($report_id))));
    
	
			$this->set('report_number',$reportdetail[0]['Report']['report_no']);
			
			$investigationdetail = $this->HsseInvestigation->find('all', array('conditions' => array('report_id' =>base64_decode($report_id))));
			 
				
		        if(count($investigationdetail)>0){
			 	 
			  $this->set('investigation_team',explode(",",$investigationdetail[0]['HsseInvestigation']['team_user_id']));
			  $iteam=explode(",",$investigationdetail[0]['HsseInvestigation']['team_user_id']);
			  $investnameHolader=array();
			  for($i=0;$i<count($iteam);$i++){
				$team_info= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$iteam[$i])));
		        //echo '<pre>';var_dump($team_info); die();
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
		
			   
			  
			  if($investigationdetail[0]['HsseInvestigation']['people_title']!=''){
				 $this->set('epeoplet',$investigationdetail[0]['HsseInvestigation']['people_title']);
			  }else{
				$this->set('epeoplet','Enter People Title');
			  }
			  
			  
			  if($investigationdetail[0]['HsseInvestigation']['people_description']!=''){
				 $this->set('epeopled',$investigationdetail[0]['HsseInvestigation']['people_description']);
			  }else{
				$this->set('epeopled','Enter People Description');
			  }
			  
			  
			  if($investigationdetail[0]['HsseInvestigation']['position_title']!=''){
			     $this->set('eposplet',$investigationdetail[0]['HsseInvestigation']['position_title']);
			  }else{
				$this->set('eposplet','Enter Position Title');
			  }
			  
			 
			  if($investigationdetail[0]['HsseInvestigation']['position_description']!=''){
				 $this->set('epospled',$investigationdetail[0]['HsseInvestigation']['position_description']);
			  }else{
				$this->set('epospled','Enter Position Description');
			  }
			 

			 if($investigationdetail[0]['HsseInvestigation']['parts_title']!=''){
				$this->set('epartsplet',$investigationdetail[0]['HsseInvestigation']['parts_title']);
			  }else{
				$this->set('epartsplet','Enter Parts Title');
			  }
			 
			 
			 if($investigationdetail[0]['HsseInvestigation']['parts_description']!=''){
		          $this->set('epartspled',$investigationdetail[0]['HsseInvestigation']['parts_description']);
			  }else{
				$this->set('epartspled','Enter Parts Description');
			  }
			 
			 
			 if($investigationdetail[0]['HsseInvestigation']['paper_title']!=''){
		          $this->set('epapert',$investigationdetail[0]['HsseInvestigation']['paper_title']);
			  }else{
				$this->set('epapert','Enter Paper Title');
			  }
			 
		         if($investigationdetail[0]['HsseInvestigation']['paper_descrption']!=''){
		          $this->set('epaperd',$investigationdetail[0]['HsseInvestigation']['paper_descrption']);
			  }else{
				$this->set('epaperd','Enter Paper Description');
			  }
	
			  $this->set('button','Update');
			  $this->set('id_holder',$investigationdetail[0]['HsseInvestigation']['team_user_id']);
			}else{
			//$this->set('lossDetail',$lossDetail);
		       // $this->set('immediateCauseDetail',$immediateCauseDetail);
		     		       
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
	    function  save_hsse_investigation(){
		      $this->layout="ajax";
		      $investigation=array();
		      $investigationData = $this->HsseInvestigation->find('all', array('conditions' => array('HsseInvestigation.report_id' =>$this->data['report_id'])));
		      if(count($investigationData)>0){
			$res='Update';
			$investigation['HsseInvestigation']['id']=$investigationData[0]['HsseInvestigation']['id'];  
		      }else{
			$res='add';
						
		      }
		        $investigation['HsseInvestigation']['report_id']=$this->data['report_id'];
			$investigation['HsseInvestigation']['team_user_id']=$this->data['id_holder'];

			
			if($this->data['people_title']=='Enter People Title'){
				
				$investigation['HsseInvestigation']['people_title']='';
			}else{
			        $investigation['HsseInvestigation']['people_title']=$this->data['people_title'];
			}
			
				
			
			if($this->data['people_descrption']=='Enter People Description'){
				
				$investigation['HsseInvestigation']['people_description']='';
			}else{
			$investigation['HsseInvestigation']['people_description']=$this->data['people_descrption'];
			}
			
			
			if($this->data['position_title']=='Enter Position Title'){
				
				$investigation['HsseInvestigation']['position_title']='';
			}else{
			$investigation['HsseInvestigation']['position_title']=$this->data['position_title'];
			}
			
				
			
	              if($this->data['position_descrption']=='Enter Position Description'){
				
			$investigation['HsseInvestigation']['position_description']='';
			}else{
		$investigation['HsseInvestigation']['position_description']=$this->data['position_descrption'];
			}
			
				
			 if($this->data['part_title']=='Enter Parts Title'){
				
			$investigation['HsseInvestigation']['parts_title']='';
			}else{
			$investigation['HsseInvestigation']['parts_title']=$this->data['part_title'];
			}
			
			
			if($this->data['part_descrption']=='Enter Parts Description'){
				
			$investigation['HsseInvestigation']['parts_description']='';
			}else{
			
			$investigation['HsseInvestigation']['parts_description']=$this->data['part_descrption'];
			}
			
		        if($this->data['paper_title']=='Enter Paper Title'){
				
			$investigation['HsseInvestigation']['paper_title']='';
			}else{
			$investigation['HsseInvestigation']['paper_title']=$this->data['paper_title'];
			}
			
				
		        if($this->data['paper_descrption']=='Enter Paper Description'){
			$investigation['HsseInvestigation']['paper_descrption']='';
			}else{
		
			$investigation['HsseInvestigation']['paper_descrption']=$this->data['paper_descrption'];
			}
				
			if($this->HsseInvestigation->save($investigation)){
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
		
	     	function report_hsse_investigation_data_list($report_id){
		          $this->_checkAdminSession();
		          $this->_getRoleMenuPermission();
                          $this->grid_access();
		          $this->layout="after_adminlogin_template";
		          
			  
			  $reportdetail = $this->Report->find('all', array('conditions' => array('Report.id' =>base64_decode($report_id))));
				if($reportdetail[0]['Report']['client']==9){
				     $this->set('clienttab',0);
				     
			       }else if($reportdetail[0]['Report']['client']!=9){
				     $this->set('clienttab',1);
				     
			       }
			 $this->set('report_id',base64_decode($report_id));      
		         $this->set('report_number',$reportdetail[0]['Report']['report_no']);
			 $clientdetail = $this->HsseClient->find('all', array('conditions' => array('HsseClient.report_id' =>base64_decode($report_id))));
		    
			if(count($clientdetail)>0){
			       if($clientdetail[0]['HsseClient']['clientreviewed']==3){
				    $this->set('client_feedback',1);
				 }else if($clientdetail[0]['HsseClient']['clientreviewed']!=3){
				    $this->set('client_feedback',0);
			      
				}
			      
			}else{
				  $this->set('client_feedback',0);
			}
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
	
	
	     	public function get_all_hsse_investigation_data_list($report_id)
	{
		Configure::write('debug', '2');  
		$this->layout = "ajax";
		$this->_checkAdminSession();
		$condition="";

		$condition="HsseInvestigationData.report_id = $report_id  AND  HsseInvestigationData.isdeleted = 'N'";
		
	      if(isset($_REQUEST['filter'])){	
	       switch($_REQUEST['filter']){
		case'incident_loss':
		  $lossDetail = $this->Loss->find('all', array('conditions' => array('type' =>trim($_REQUEST['value']))));            if(count($lossDetail)>0){
		  $incidentLoss = $this->HsseIncident->find('all', array('conditions' => array('incident_loss' =>$lossDetail[0]['Loss']['id'],'report_id'=>$report_id)));
	          $incident_invest_id=array(); 
		  for($i=0;$i<count($incidentLoss);$i++){
			
			$incident_invest_id[]=$incidentLoss[$i]['HsseInvestigationData'][0]['id'];
			
		  }
		  $incident_id='';
                  if(count($incident_invest_id)>0){
		         $incident_id=implode(",",$incident_invest_id);
		  }
		  $condition = "HsseInvestigationData.id IN (".$incident_id.")";
		  }
	 	  break;
		  case'imd_cause_name':
		  $imd_cause_holder=array();	
		  $immdiateCause = $this->ImmediateCause->find('all', array('conditions' => array('type' =>trim($_REQUEST['value']))));
		  
		  if(count($immdiateCause)>0){
			 $investData = $this->HsseInvestigationData->find('all', array('conditions' => array('report_id' =>$report_id)));

			 if(count($investData)>0){
				for($i=0;$i<count($investData);$i++){
				$explode_imd_cause=explode(",",$investData[$i]['HsseInvestigationData']['immediate_cause']);              
					if($explode_imd_cause[0]==$immdiateCause[0]['ImmediateCause']['id']){
					  $imd_cause_holder[]=$investData[$i]['HsseInvestigationData']['id'];	
					}
				
				}
				if(count($imd_cause_holder)>0){
					$incident_id=implode(",",$imd_cause_holder);
					$condition = "HsseInvestigationData.id IN (".$incident_id.")";
				}
				
			 }
			 
			
		  }
		  $immdiateSubCause = $this->ImmediateSubCause->find('all', array('conditions' => array('type' =>trim($_REQUEST['value']))));
		  
		  if(count($immdiateSubCause)>0){
			 $investData = $this->HsseInvestigationData->find('all', array('conditions' => array('report_id' =>$report_id)));

			 if(count($investData)>0){
				for($i=0;$i<count($investData);$i++){
				$explode_imd_cause=explode(",",$investData[$i]['HsseInvestigationData']['immediate_cause']);              
					if($explode_imd_cause[1]==$immdiateSubCause[0]['ImmediateSubCause']['id']){
					  $imd_cause_holder[]=$investData[$i]['HsseInvestigationData']['id'];	
					}
				
				}
				if(count($imd_cause_holder)>0){
					$incident_id=implode(",",$imd_cause_holder);
					$condition = "HsseInvestigationData.id IN (".$incident_id.")";
				}
				
			 }
				
		  }
		  break;
		  case'root_cause_list':
			$rootCause_array=array();
			$rootCause = $this->RootCause->find('all', array('conditions' => array('type' =>trim($_REQUEST['value']))));
			if(count($rootCause)>0){
				 $investData = $this->HsseInvestigationData->find('all', array('conditions' => array('report_id' =>$report_id)));
				 
				if(count($investData)>0){
				for($i=0;$i<count($investData);$i++){
				$explode_root_cause=explode(",",$investData[$i]['HsseInvestigationData']['root_cause_id']);
						
				
					if(in_array($rootCause[0]['RootCause']['id'],$explode_root_cause)){
					  $rootCause_array[]=$investData[$i]['HsseInvestigationData']['id'];	
					}
				
				}
				if(count($rootCause_array)>0){
					$incident_id=implode(",",$rootCause_array);
					$condition = "HsseInvestigationData.id IN (".$incident_id.")";
				}
				
			 } 
				
			}
			
		  break;
		   
	       }
	      } 	
		
		
		
		
                /*if(isset($_REQUEST['filter'])){
			$condition .= "AND ucase(HsseInvestigationData.".$_REQUEST['filter'].") like '".$_REQUEST['value']."%'";	
		}
		*/
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					

		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}
                  
		 $adminArray = array();	
		 $count = $this->HsseInvestigationData->find('count' ,array('conditions' => $condition));
		 $adminA = $this->HsseInvestigationData->find('all',array('conditions' => $condition,'order' => 'HsseInvestigationData.id DESC','limit'=>$limit));
		  
		$i = 0;
		foreach($adminA as $rec)
		{			
			if($rec['HsseInvestigationData']['isblocked'] == 'N')
			{
				$adminA[$i]['HsseInvestigationData']['blockHideIndex'] = "true";
				$adminA[$i]['HsseInvestigationData']['unblockHideIndex'] = "false";
				$adminA[$i]['HsseInvestigationData']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['HsseInvestigationData']['blockHideIndex'] = "false";
				$adminA[$i]['HsseInvestigationData']['unblockHideIndex'] = "true";
				$adminA[$i]['HsseInvestigationData']['isdeletdHideIndex'] = "false";
			}
			
		    $i++;
		}
		 
		
		  $adminArray = Set::extract($adminA, '{n}.HsseInvestigationData');
		  $rootCauseval=array();
		  $rval="";
		            if(count($adminArray)>0){
			           
					for($i=0;$i<count($adminArray);$i++){
						 $incdent_loss_ID= $this->HsseIncident->find('all', array('conditions' => array('HsseIncident.id' =>$adminArray[$i]['incident_id'])));
						 $incdent_loss= $this->Loss->find('all', array('conditions' => array('Loss.id' => $incdent_loss_ID[0]['HsseIncident']['incident_loss'])));		 
					         $adminArray[$i]['incident_loss']=$incdent_loss[0]['Loss']['type'];
						 $explode_imd_cause=explode(",",$adminArray[$i]['immediate_cause']);
						 $immidiate_cause= $this->ImmediateCause->find('all', array('conditions' => array('ImmediateCause.id' =>$explode_imd_cause[0])));
						 if(isset($explode_imd_cause[1])){
							 $immidiate_sub_cause= $this->ImmediateSubCause->find('all', array('conditions' => array('ImmediateSubCause.id' =>$explode_imd_cause[1])));
							 if(count($immidiate_sub_cause)>0){
								$adminArray[$i]['imd_cause_name']=$immidiate_cause[0]['ImmediateCause']['type'].'<br/>'.$immidiate_sub_cause[0]['ImmediateSubCause']['type'];
								
							 }else{
								
								$adminArray[$i]['imd_cause_name']=$immidiate_cause[0]['ImmediateCause']['type'];
								
							 }
										           
						 }else{
							$adminArray[$i]['imd_cause_name']='';
						 }
						  $explode_root_cause=explode(",",$adminArray[$i]['root_cause_id']);
				                   
											  
						  for($r=0;$r<count($explode_root_cause);$r++){
							if($explode_root_cause[$r]!=0){
							    $root_cause= $this->RootCause->find('all', array('conditions' => array('RootCause.id' =>$explode_root_cause[$r])));
							    $rootCauseval[$i][]=$root_cause[0]['RootCause']['type'];
							}
							 
						  }
						   $adminArray[$i]['root_cause_list']=implode("<br/>",$rootCauseval[$i]);
						

					   }
			        }
		  
		  $this->set('total', $count);  //send total to the view
		  
		  $this->set('admins', $adminArray);  //send products to the view
		  //$this->set('status', $action);
	}
	
	
	
	
			 
	    function investigation_data_block($id = null)
	     {

	     
	                if(!$id)
			{
		           $this->redirect(array('action'=>report_hsse_list), null, true);
			}
		       else
		       
		       
		       {
			    
	                 	$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['HsseInvestigationData']['id'] = $id;
					  $this->request->data['HsseInvestigationData']['isblocked'] = 'Y';
					  $this->HsseInvestigationData->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	     
	      }
	      
	      function investigation_data_unblock($id = null)
	     {
                  
			if(!$id)
			{
		           $this->redirect(array('action'=>report_hsse_list), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['HsseInvestigationData']['id'] = $id;
					  $this->request->data['HsseInvestigationData']['isblocked'] = 'N';
					  $this->HsseInvestigationData->save($this->request->data,false);				
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
				
					  $this->request->data['HsseInvestigationData']['id'] = $id;
					  $this->request->data['HsseInvestigationData']['isdeleted'] = 'Y';
					  $this->HsseInvestigationData->save($this->request->data,false);
					  
			       }
			       echo 'ok';
			      
			   
			       exit;			 
		     
	      }
		
	
	       function add_investigation_data_analysis($report_id,$incident_id=null,$investigation_id=null){
		          $this->_checkAdminSession();
		          $this->_getRoleMenuPermission();
                          $this->grid_access();
		          $this->layout="after_adminlogin_template";
			  $immediateCauseDetail = $this->ImmediateCause->find('all');
			  $this->set('immediateCauseDetail',$immediateCauseDetail);
			  $rootParrentCauseData= $this->RootCause->find('all',array('conditions'=>array('parrent_id'=>0)));
		          $this->set('rootParrentCauseData',$rootParrentCauseData);
			  $remidialData= $this->HsseRemidial->find('all',array('conditions'=>array('HsseRemidial.report_no'=>base64_decode($report_id),'HsseRemidial.isblocked'=>'N','HsseRemidial.isdeleted'=>'N')));
            		  $this->set('remidialData',$remidialData);
			  $reportdetail = $this->Report->find('all', array('conditions' => array('Report.id' =>base64_decode($report_id))));
			  $this->set('report_id',base64_decode($report_id));      
		          $this->set('report_number',$reportdetail[0]['Report']['report_no']);
			  $incidentdetail = $this->HsseIncident->find('all', array('conditions' => array('HsseIncident.report_id' =>base64_decode($report_id),'HsseIncident.isblocked' =>'N','HsseIncident.isdeleted' =>'N')));
			  
			  $this->set('incidentdetail',$incidentdetail);
			  $clientdetail = $this->HsseClient->find('all', array('conditions' => array('HsseClient.report_id' =>base64_decode($report_id))));
			  if(count($clientdetail)>0){
			       if($clientdetail[0]['HsseClient']['clientreviewed']==3){
				    $this->set('client_feedback',1);
				 }else if($clientdetail[0]['HsseClient']['clientreviewed']!=3){
				    $this->set('client_feedback',0);
			      
				}
			      
			 }else{
				$this->set('client_feedback',0);
			 }

			  if($investigation_id!=''){
				$investigationDetail = $this->HsseInvestigationData->find('all', array('conditions' => array('HsseInvestigationData.id' =>base64_decode($investigation_id)),'recursive'=>2));
				$this->set('investigation_no',$investigationDetail[0]['HsseInvestigationData']['investigation_no']);
				$this->set('ltype',$investigationDetail[0]['HsseIncident']['Loss']['type']);
				$this->set('incident_summary', $investigationDetail[0]['HsseIncident']['incident_summary']);
				$this->set('comment',$investigationDetail[0]['HsseInvestigationData']['comments']);
				$explode_immd_cause=explode(",",$investigationDetail[0]['HsseInvestigationData']['immediate_cause']);
						
					
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
					  $this->set('immediate_cause',0);
					  $this->set('immediate_sub_cause',0);
				 }
				 
                                if($investigationDetail[0]['HsseInvestigationData']['remedila_action_id']!=''){
					
					  $condition = "HsseRemidial.id IN (".$investigationDetail[0]['HsseInvestigationData']['remedila_action_id'].")";
			                  $remidialList=$this->HsseRemidial->find('all', array('conditions' => $condition));
				          $this->set('remidialList',$remidialList);
					
				}else{
				     $this->set('remidialList',array());	
				}
				$childRoot=array();
				if($investigationDetail[0]['HsseInvestigationData']['root_cause_id']!=''){
					  $explode_root_cause=explode(",",$investigationDetail[0]['HsseInvestigationData']['root_cause_id']);
				          $this->set('rootParrentCausevalue',$explode_root_cause[0]);
					  $condition = "RootCause.id IN (".$investigationDetail[0]['HsseInvestigationData']['root_cause_id'].")";
			                  $rootCauseList=$this->RootCause->find('all', array('conditions' => $condition));
					  $this->set('parrentRoot',explode(",",$investigationDetail[0]['HsseInvestigationData']['root_cause_id']));				  
					  for($r=0;$r<count($rootCauseList);$r++){
						$childRoot[$rootCauseList[$r]['RootCause']['id']][]=$this->RootCause->find('all', array('conditions' => array('RootCause.parrent_id'=>$rootCauseList[$r]['RootCause']['id'])));
						
					  }
					  $this->set('childRoot',$childRoot);
					  $this->set('explode_root_cause',$explode_root_cause);
			 		  
 
				}else{
				         $this->set('explode_root_cause',array());	
				}
				$this->set('comments',$investigationDetail[0]['HsseInvestigationData']['comments']);
				$this->set('heading','Edit Investigation Data Analysis');
				$this->set('button','Update');
				$this->set('edit_investigation_id',base64_decode($investigation_id));
			        $this->set('disabled','disabled="disabled"');
				$this->set('edit_incident_id',base64_decode($incident_id));
				$this->set('incident_no',$investigationDetail[0]['HsseInvestigationData']['incident_no']);
				if($investigationDetail[0]['HsseInvestigationData']['remedila_action_id']!=''){
				   $tr_id=array();	
				   $idc=explode(',',$investigationDetail[0]['HsseInvestigationData']['remedila_action_id']);
				   for($c=0;$c<count($idc);$c++){
					$tr_id[]='tr'.$idc[$c];
					
				   }
				   		
				   $this->set('tr_id',implode(',',$tr_id));	
				   $this->set('idContent',$investigationDetail[0]['HsseInvestigationData']['remedila_action_id']);		
				}else{
				  $this->set('idContent','');
				   $this->set('tr_id','');
				}
				
				$this->set('id_holder',$investigationDetail[0]['HsseInvestigationData']['remedila_action_id']);
			  }else{
				 $this->set('investigation_no','');
				 $this->set('ltype','');
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
				
			  }
 
	       }
	       function displayincidentdetail(){
		         $this->layout="ajax";
		
			  $immediateCauseDetail = $this->ImmediateCause->find('all');
			  $rclval='';
			  $this->set('immediateCauseDetail',$immediateCauseDetail);
		          $incidentdetail = $this->HsseIncident->find('all', array('conditions' => array('HsseIncident.id' =>$this->data['incidentid'])));
			  $lossData = $this->Loss->find('all', array('conditions' => array('Loss.id' =>$incidentdetail[0]['HsseIncident']['incident_loss'])));
			
				  
			 $this->set('ltype',$lossData[0]['Loss']['type']);
			 $this->set('incident_summary', $incidentdetail[0]['HsseIncident']['incident_summary']);
			 $incidentdetailINvestigation = $this->HsseInvestigationData->find('all', array('conditions' => array('HsseInvestigationData.incident_id' =>$this->data['incidentid'])));
			 
			 
			 			                 
			 $zeroParrentId=$this->RootCause->find('all', array('conditions' => array("parrent_id"=>0)));
			 $this->set('zeroParrent',$zeroParrentId);
			 if(count($incidentdetailINvestigation)>0){
			   $investigation_no=count($incidentdetailINvestigation)+1;
			 }else{
			   $investigation_no=1;
			 }
			 echo $lossData[0]['Loss']['type'].'~'.$incidentdetail[0]['HsseIncident']['incident_summary'].'~'.$investigation_no.'~'.$incidentdetail[0]['HsseIncident']['incident_no']; 
			  
				
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
			$investigationData['HsseInvestigationData']['id']=$this->data['investigation_id'];
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
		 
		 
		   $investigationData['HsseInvestigationData']['investigation_no']=$this->data['investigation_no'];
		   $investigationData['HsseInvestigationData']['incident_no']=$this->data['incident_no'];
		   $investigationData['HsseInvestigationData']['report_id']=$this->data['report_id'];
		   $investigationData['HsseInvestigationData']['incident_id']=$this->data['incident_val'];
		   $investigationData['HsseInvestigationData']['immediate_cause']=$this->data['causeCont'];
		   $investigationData['HsseInvestigationData']['root_cause_id']=$this->data['rootCauseCont'];
		   $investigationData['HsseInvestigationData']['remedila_action_id']=$rH;
		   if($this->data['comments']!=''){
			$investigationData['HsseInvestigationData']['comments']=$this->data['comments'];
		   }else{
			$investigationData['HsseInvestigationData']['comments']='';
		   }
		 
		   
		    if($this->HsseInvestigationData->save($investigationData)){
			if($res=='add'){
			 
			 $last_incident_investigation=base64_encode($this->HsseInvestigationData->getLastInsertId());
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
	    function add_hsse_feedback($id=null){
		      $this->layout="after_adminlogin_template";
		      $this->_checkAdminSession();
		      $this->_getRoleMenuPermission();
		      $this->grid_access();
		       $clientdetail = $this->HsseClient->find('all', array('conditions' => array('HsseClient.report_id' =>base64_decode($id))));
		$reportdetail = $this->Report->find('all', array('conditions' => array('Report.id' =>base64_decode($id))));
			
		    
		  if(count($clientdetail)>0){
		      $this->set('clientreviewer',$clientdetail[0]['HsseClient']['clientreviewer']);
		      $this->set('clientfeedback_remark','');
		       $clientfeeddetail = $this->HsseClientfeedback->find('all', array('conditions' => array('HsseClientfeedback.report_id' =>base64_decode($id))));
		       $clientdetail = $this->HsseClient->find('all', array('conditions' => array('HsseClient.report_id' =>base64_decode($id))));
		    
			if(count($clientdetail)>0){
			       if($clientdetail[0]['HsseClient']['clientreviewed']==3){
				    $this->set('client_feedback',1);
				 }else if($clientdetail[0]['HsseClient']['clientreviewed']!=3){
				    $this->set('client_feedback',0);
			      
				}
			      
			      
			}else{
				$this->set('client_feedback',0);
			}
	
			$this->set('report_id',base64_decode($id));
		       if(count($clientfeeddetail)>0){
			 $cls_date=explode("-",$clientfeeddetail[0]['HsseClientfeedback']['close_date']);
			 $close_date=$cls_date[1].'-'.$cls_date[2].'-'.$cls_date[0];
			 $this->set('close_date',$close_date);
		         $this->set('heading','Edit Client Feedback');
		         $this->set('client_summary',$clientfeeddetail[0]['HsseClientfeedback']['client_summary']);  
			 $this->set('button','Update'); 
			 $this->set('id',$clientfeeddetail[0]['HsseClientfeedback']['id']);
			
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
		     $client_feed_back['HsseClientfeedback']['id']=$this->data['add_report_client_data_form']['id'];
		     $res='Update';
			
		   }else{
		     $res='Add';
		   }
		   
		   $client_feed_back['HsseClientfeedback']['report_id']=$this->data['report_id'];
		   if($this->data['close_date']!=''){
			$clsr=explode("-",$this->data['close_date']);
			$closer_date=$clsr[2]."-".$clsr[0]."-".$clsr[1];
			
		   }else{
			$closer_date='';
		   }
		   $client_feed_back['HsseClientfeedback']['close_date']=$closer_date;
		   if($this->data['add_report_client_data_form']['client_summary']!=''){
		   $client_feed_back['HsseClientfeedback']['client_summary']=$this->data['add_report_client_data_form']['client_summary'];
		   }
		    if($this->HsseClientfeedback->save($client_feed_back)){
		        echo $res;
		        }else{
	                echo 'fail';
	 	      }
		   exit;
		}
		
	 function print_view($id=null){
		 $this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
		 $this->grid_access();
		 $this->hsse_client_tab();
                 $this->layout="ajax";
		 $reportdetail = $this->Report->find('all', array('conditions' => array('Report.id' =>base64_decode($id))));

	          $this->set('id',base64_decode($id));
                	if($reportdetail[0]['Report']['event_date']!=''){
				 $evndt=explode("-",$reportdetail[0]['Report']['event_date']);
				 $event_date=$evndt[2]."/".$evndt[1]."/".$evndt[0];
			}else{
				 $event_date='';	
			}
		  
		   $clientdetail = $this->HsseClient->find('all', array('conditions' => array('HsseClient.report_id' =>base64_decode($id)))); 
		  if($reportdetail[0]['Report']['client']==10){
		        $this->set('clienttabshow',0);
			
			$this->set('clienttab',0);
				     
		   }else if($reportdetail[0]['Report']['client']!=10){
		        $this->set('clienttabshow',1);
			if(count($clientdetail)>0){
				$this->set('clienttab',1);
			}else{
				$this->set('clienttab',0);
			}
			
	            }
		    
		  if(count($clientdetail)>0){
			 if($clientdetail[0]['HsseClient']['clientreviewed']==3){
			      $this->set('client_feedback',1);
		           }else if($clientdetail[0]['HsseClient']['clientreviewed']!=3){
			      $this->set('client_feedback',0);
			
		          }
			
		  }else{
			$this->set('client_feedback',0);
		  }
		  
		  
	  	  $this->set('event_date',$event_date);
		  $this->set('since_event_hidden',$reportdetail[0]['Report']['since_event']);
		  $this->set('since_event',$reportdetail[0]['Report']['since_event']);
		  $this->set('reportno',$reportdetail[0]['Report']['report_no']);
		  
		  
		  if($reportdetail[0]['Report']['closer_date']!='0000-00-00'){
				 $clsdt=explode("-",$reportdetail[0]['Report']['closer_date']);
				 $closedt=$clsdt[2]."/".$clsdt[1]."/".$clsdt[0];
			}else{
				 $closedt='00/00/0000';	
			}
		  
		  $this->set('closer_date',$closedt);
		  
		  $incident_detail= $this->Incident->find('all', array('conditions' => array('Incident.id' =>$reportdetail[0]['Report']['incident_type'])));
		  $this->set('incident_type',$incident_detail[0]['Incident']['type']);
		  
		  
		  $user_detail= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$reportdetail[0]['Report']['created_by'])));
		  $this->set('created_by',$user_detail[0]['AdminMaster']['first_name']." ".$user_detail[0]['AdminMaster']['last_name']);
		  
		  $this->set('created_date','');
		  
		  $business_unit_detail= $this->BusinessType->find('all', array('conditions' => array('BusinessType.id' =>$reportdetail[0]['Report']['business_unit'])));
		  $this->set('business_unit',$business_unit_detail[0]['BusinessType']['type']);
		 

		  
		   $client_detail= $this->Client->find('all', array('conditions' => array('Client.id' =>$reportdetail[0]['Report']['client'])));
		   $this->set('client',$client_detail[0]['Client']['name']);
		  
		  
		   $fieldlocation= $this->Fieldlocation->find('all', array('conditions' => array('Fieldlocation.id' =>$reportdetail[0]['Report']['field_location'])));
		   $this->set('fieldlocation',$fieldlocation[0]['Fieldlocation']['type']);
		  
		   $incidentLocation= $this->IncidentLocation->find('all', array('conditions' => array('IncidentLocation.id' =>$reportdetail[0]['Report']['field_location'])));
		   $this->set('incidentLocation',$incidentLocation[0]['IncidentLocation']['type']);
		  
		  
		   $countrty= $this->Country->find('all', array('conditions' => array('Country.id' =>$reportdetail[0]['Report']['country'])));
		   $this->set('countrty',$countrty[0]['Country']['name']);
		  
		   $report_detail= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$reportdetail[0]['Report']['reporter'],'AdminMaster.isdeleted'=>'N','AdminMaster.isblocked'=>'N')));
		   $this->set('reporter',$report_detail[0]['AdminMaster']['first_name']." ".$report_detail[0]['AdminMaster']['last_name']);
	   
		   $incidentSeverity_detail= $this->IncidentSeverity->find('all', array('conditions' => array('IncidentSeverity.id' =>$reportdetail[0]['Report']['incident_severity'])));
		   $this->set('incidentseveritydetail',$incidentSeverity_detail[0]['IncidentSeverity']['type']);
		   $this->set('incidentseveritydetailcolor','style="background-color:'.$incidentSeverity_detail[0]['IncidentSeverity']['color_code'].'"');
		   
		   
		   $residual_detail= $this->Residual->find('all', array('conditions' => array('Residual.id' =>$reportdetail[0]['Report']['residual'])));
		   $this->set('residual',$residual_detail[0]['Residual']['type']);
		   if($residual_detail[0]['Residual']['color_code']!=''){
		    $this->set('residualcolor','style="background-color:'.$residual_detail[0]['Residual']['color_code'].'"');
		   }else{
		    $this->set('residualcolor','');	
		   }
	   
		   if($reportdetail[0]['Report']['recorable']==1){
			$this->set('recorable','Yes');
			$this->set('recorablecolor','style="background-color:#FF0000"');
		   }elseif($reportdetail[0]['Report']['recorable']==2){
			$this->set('recorable','No');
			$this->set('recorablecolor','style="background-color:#40FF00"');
		   }
		   
		     $potentilal_detail= $this->Potential->find('all', array('conditions' => array('Potential.id' =>$reportdetail[0]['Report']['potential'])));
		   $this->set('potential',$potentilal_detail[0]['Potential']['type']);
		   if($potentilal_detail[0]['Potential']['color_code']!=''){
		    $this->set('potentialcolor','style="background-color:'.$potentilal_detail[0]['Potential']['color_code'].'"');
		   }else{
		    $this->set('potentialcolor','');	
		   }
	   
		
		   $this->set('summary',$reportdetail[0]['Report']['summary']);
		  $this->set('details',$reportdetail[0]['Report']['details']);
		  $this->set('created_by',$_SESSION['adminData']['AdminMaster']['first_name']." ".$_SESSION['adminData']['AdminMaster']['last_name']);
		  
		  /************************clientdata***************************/
		  $clientdetail = $this->HsseClient->find('all', array('conditions' => array('HsseClient.report_id' =>base64_decode($id))));
		  
		  
		   if(count($clientdetail)>0){
			
			    $this->set('well',$clientdetail[0]['HsseClient']['well']);
		            $this->set('rig',$clientdetail[0]['HsseClient']['rig']);
		            $this->set('clientncr',$clientdetail[0]['HsseClient']['clientncr']);
			    if($clientdetail[0]['HsseClient']['clientreviewed']==1){
				$this->set('clientreviewed','N/A');
			    }elseif($clientdetail[0]['HsseClient']['clientreviewed']==2){
				$this->set('clientreviewed','N/A');
			    }if($clientdetail[0]['HsseClient']['clientreviewed']==3){
				$this->set('clientreviewed','Yes');
				
			    }
		            
  		            $this->set('report_id',$clientdetail[0]['HsseClient']['report_id']);
		            $this->set('clientreviewer',$clientdetail[0]['HsseClient']['clientreviewer']);
			    $this->set('wellsiterep',$clientdetail[0]['HsseClient']['wellsiterep']);
			}else{
			     $this->set('well','');
			     $this->set('rig','');
			     $this->set('clientncr','');
			     $this->set('clientreviewed','');
			     $this->set('clientreviewer','');
			     $this->set('wellsiterep','');
			
			   	
				
			}
			
		  /************************indidentdata***************************/
		  
		  
		  $incidentdetail = $this->HsseIncident->find('all', array('conditions' => array('HsseIncident.report_id' =>base64_decode($id),'HsseIncident.isdeleted'=>'N','HsseIncident.isblocked'=>'N')));
		  
		 //echo '<pre>';
		 //print_r($incidentdetail);
	         
		  $incidentdetailHolder=array();
	           if(count($incidentdetail)>0){
			for($i=0;$i<count($incidentdetail);$i++){
	       
	                     if($incidentdetail[$i]['HsseIncident']['date_incident']!=''){
				    $incidt=explode("-",$incidentdetail[$i]['HsseIncident']['date_incident']);
				    $incidentdetailHolder[$i]['date_incident']=$incidt[2]."/".$incidt[1]."/".$incidt[0];
			         }else{
				    $incidentdetailHolder[$i]['date_incident']='';	
			        }
				
				if($incidentdetail[$i]['HsseIncident']['incident_time']!=''){
				
				$incidentdetailHolder[$i]['incident_time']=$incidentdetail[$i]['HsseIncident']['incident_time'];
				}else{
				$incidentdetailHolder[$i]['incident_time']='';	
				}
						
				if($incidentdetail[$i]['HsseIncident']['incident_severity']!=0){
				$incidentSeverity_type= $this->IncidentSeverity->find('all', array('conditions' => array('IncidentSeverity.id' =>$incidentdetail[$i]['HsseIncident']['incident_severity'])));
				$incidentdetailHolder[$i]['incident_severity']=$incidentSeverity_type[0]['IncidentSeverity']['type'];
				}else{
				$incidentdetailHolder[$i]['incident_severity']='';	
				}
				
				if($incidentdetail[$i]['HsseIncident']['incident_loss']!=0){
		                $incidentLoss_type= $this->Loss->find('all', array('conditions' => array('Loss.id' =>$incidentdetail[$i]['HsseIncident']['incident_loss'])));
				$incidentdetailHolder[$i]['incident_loss']=$incidentLoss_type[0]['Loss']['type'];
				}else{
				$incidentdetailHolder[$i]['incident_loss']='';	
				}
				
				if($incidentdetail[$i]['HsseIncident']['incident_category']!=0){
				 $incident_category_type= $this->IncidentCategory->find('all', array('conditions' => array('IncidentCategory.id' =>$incidentdetail[$i]['HsseIncident']['incident_category'])));
				 $incidentdetailHolder[$i]['incident_category']=$incident_category_type[0]['IncidentCategory']['type'];
				}else{
				  $incidentdetailHolder[$i]['incident_category']='';	
				}
			        
		               if($incidentdetail[$i]['HsseIncident']['incident_sub_category']!=0){
			           $incident_sub_category_type= $this->IncidentSubCategory->find('all', array('conditions' => array('IncidentSubCategory.id' =>$incidentdetail[$i]['HsseIncident']['incident_sub_category'])));
				   $incidentdetailHolder[$i]['incident_sub_category']=$incident_sub_category_type[0]['IncidentSubCategory']['type'];
			       }else{
				  $incidentdetailHolder[$i]['incident_sub_category']='';
			       }
			      	       
			       if($incidentdetail[$i]['HsseIncident']['incident_summary']!=''){
			       $incidentdetailHolder[$i]['incident_summary']=$incidentdetail[$i]['HsseIncident']['incident_summary'];
			       }else{
				 $incidentdetailHolder[$i]['incident_summary']='';
			       }
			       if($incidentdetail[$i]['HsseIncident']['detail']!=''){
			       $incidentdetailHolder[$i]['detail']=$incidentdetail[$i]['HsseIncident']['detail'];
			       }else{
				$incidentdetailHolder[$i]['detail']='';
			       }
			       
			       $incidentdetailHolder[$i]['id']=$incidentdetail[$i]['HsseIncident']['id'];
		        
			
			      $incidentdetailHolder[$i]['isblocked']=$incidentdetail[$i]['HsseIncident']['isblocked'];
			      $incidentdetailHolder[$i]['isdeleted']=$incidentdetail[$i]['HsseIncident']['isdeleted'];
			      $incidentdetailHolder[$i]['incident_no']=$incidentdetail[$i]['HsseIncident']['incident_no'];  
			if(count($incidentdetail[$i]['HsseInvestigationData'])>0){
				
				for($v=0;$v<count($incidentdetail[$i]['HsseInvestigationData']);$v++){
					 $immidiate_cause=explode(",",$incidentdetail[$i]['HsseInvestigationData'][$v]['immediate_cause']);
					  if($immidiate_cause[0]!=''){
			                         $imdCause = $this->ImmediateCause->find('all', array('conditions' => array('ImmediateCause.id'=>$immidiate_cause[0])));
						 if(count($imdCause)>0){
						 $incidentdetailHolder[$i]['imd_cause'][]=$imdCause[0]['ImmediateCause']['type'];
						 }     
			                    
					   if(isset($immidiate_cause[1])){

			                          $imdSubCause = $this->ImmediateSubCause->find('all', array('conditions' => array('ImmediateSubCause.id'=>$immidiate_cause[1])));
						  if(count($imdSubCause)>0){
					              $incidentdetailHolder[$i]['imd_sub_cause'][]=$imdSubCause[0]['ImmediateSubCause']['type'];
						  }
                                           }
					  }
					   if($incidentdetail[$i]['HsseInvestigationData'][$v]['comments']!=''){
				                $incidentdetailHolder[$i]['comment']=$incidentdetail[$i]['HsseInvestigationData'][$v]['comments'];
			                    }
					    $incidentdetailHolder[$i]['investigation_block']=$incidentdetail[$i]['HsseInvestigationData'][$v]['isblocked'];
					    $incidentdetailHolder[$i]['investigation_delete']=$incidentdetail[$i]['HsseInvestigationData'][$v]['isdeleted'];
					    $incidentdetailHolder[$i]['investigation_no'][]=$incidentdetail[$i]['HsseInvestigationData'][$v]['investigation_no'];
					    $incidentdetailHolder[$i]['incident_no_investigation']=$incidentdetail[$i]['HsseInvestigationData'][$v]['incident_no'];
					   if($incidentdetail[$i]['HsseInvestigationData'][$v]['root_cause_id']!=0){
				                $explode_rootcause=explode(",",$incidentdetail[$i]['HsseInvestigationData'][$v]['root_cause_id']);
				 
							for($j=0;$j<count($explode_rootcause);$j++){
								if($explode_rootcause[$j]!=0){
								 $rootCauseDetail = $this->RootCause->find('all', array('conditions' => array('RootCause.id' =>$explode_rootcause[$j])));
								 $incidentdetailHolder[$i]['root_cause_val'][$v][$j]=$rootCauseDetail[0]['RootCause']['type'];
								}
								
								
							}
				 
			                      }
					   if($incidentdetail[$i]['HsseInvestigationData'][$v]['remedila_action_id']!=''){
				                 $explode_remedila_action_id=explode(",",$incidentdetail[$i]['HsseInvestigationData'][$v]['remedila_action_id']);
				    		 for($k=0;$k<count($explode_remedila_action_id);$k++){
							if(isset($explode_remedila_action_id[$k])){
							 $remDetail = $this->HsseRemidial->find('all', array('conditions' => array('HsseRemidial.id' =>$explode_remedila_action_id[$k],'HsseRemidial.isdeleted'=>'N','HsseRemidial.isblocked'=>'N')));
							 $incidentdetailHolder[$i]['rem_val'][$v][$k]=$remDetail[0]['HsseRemidial']['remidial_summery'];
							 $incidentdetailHolder[$i]['rem_val_id'][$v][$k]=$remDetail[0]['HsseRemidial']['id'];
							}
							
						}
				
			                   }
					   
				  $incidentdetailHolder[$i]['view'][]='yes';       
				}
	
		            
		        }else{
			     $incidentdetailHolder[$i]['view'][]='no';
		        }
		     
			
		     }
			
			
		  }
		  
		  
		  //print_r($incidentdetailHolder);   
		     
         	  $this->set('incidentdetailHolder',$incidentdetailHolder);
		  
	  
		  
		  
		  /*************Incident - Personnel****************************/
		  $personeldetail = $this->HssePersonnel->find('all', array('conditions' => array('HssePersonnel.report_id' =>base64_decode($id),'HssePersonnel.isdeleted'=>'N','HssePersonnel.isblocked'=>'N')));
		  if(count($personeldetail)>0){
			$this->set('personeldata',1);
			for($i=0;$i<count($personeldetail);$i++){
				$user_detail= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$personeldetail[$i]['HssePersonnel']['personal_data'],'AdminMaster.isblocked'=>'N','AdminMaster.isdeleted'=>'N')));
				if(count($user_detail)>0){
					$seniorty=explode(" ",$user_detail[0]['AdminMaster']['created']);	
					$snr=explode("-",  $seniorty[0]);
					$snrdt=$snr[2]."/".$snr[1]."/".$snr[0];
					$personeldetail[$i]['HssePersonnel']['seniorty']=$snrdt;
					$personeldetail[$i]['HssePersonnel']['name']=$user_detail[0]['AdminMaster']['first_name']."  ".$user_detail[0]['AdminMaster']['last_name'];
					$personeldetail[$i]['HssePersonnel']['position']=$user_detail[0]['RoleMaster']['role_name'];
				}
				
			}
			 $this->set('personeldetail',$personeldetail);
		  }else{
			$this->set('personeldata',0);
		  }
		 /*************Attachments****************************/
		  $attachmentData = $this->HsseAttachment->find('all', array('conditions' => array('HsseAttachment.report_id' =>base64_decode($id),'HsseAttachment.isdeleted'=>'N','HsseAttachment.isblocked'=>'N')));
		  if(count($attachmentData)>0){
		      $this->set('attachmentData',$attachmentData);
		      $this->set('attachmentTab',1);
		  }else{
		       $this->set('attachmentData','');
		       $this->set('attachmentTab',0);
		  }
		  /***********REMIDIAL ACTION****************************/
		  $remidialdetail = $this->HsseRemidial->find('all', array('conditions' => array('HsseRemidial.report_no' =>base64_decode($id),'HsseRemidial.isblocked'=>'N','HsseRemidial.isdeleted'=>'N')));
		  if(count($remidialdetail)>0){
			$this->set('remidial',1);
			for($i=0;$i<count($remidialdetail);$i++){
				 $user_detail_createby= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$remidialdetail[$i]['HsseRemidial']['remidial_createby'])));
				 $remidialdetail[$i]['HsseRemidial']['remidial_createby']=$user_detail_createby[0]['AdminMaster']['first_name']."  ".$user_detail_createby[0]['AdminMaster']['last_name'];
				 $user_detail_reponsibilty= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$remidialdetail[$i]['HsseRemidial']['remidial_responsibility'])));
				 $remidialdetail[$i]['HsseRemidial']['remidial_responsibility']=$user_detail_reponsibilty[0]['AdminMaster']['first_name']."  ".$user_detail_reponsibilty[0]['AdminMaster']['last_name'];
				 $priority_detail= $this->Priority->find('all', array('conditions' => array('Priority.id' =>$remidialdetail[$i]['HsseRemidial']['remidial_priority'])));
				 $remidialdetail[$i]['HsseRemidial']['priority']=$priority_detail[0]['Priority']['type'];
				 $remidialdetail[$i]['HsseRemidial']['priority_color']='style="background-color:'.$priority_detail[0]['Priority']['colorcoder'].'"';
				 $lastupdated=explode(" ",$remidialdetail[$i]['HsseRemidial']['modified']);
				 $lastupdatedate=explode("-",$lastupdated[0]);
				 $remidialdetail[$i]['HsseRemidial']['lastupdate']=$lastupdatedate[1].'/'.$lastupdatedate[2].'/'.$lastupdatedate[0];
				 $createdate=explode("-",$remidialdetail[$i]['HsseRemidial']['remidial_create']);
				 $remidialdetail[$i]['HsseRemidial']['createRemidial']=date("d-M-y", mktime(0, 0, 0, $createdate[1], $createdate[2], $createdate[0]));
				 
				 if($remidialdetail[$i]['HsseRemidial']['remidial_closure_date']=='0000-00-00'){
				   	  $closerDate=explode("-",$remidialdetail[$i]['HsseRemidial']['remidial_closure_date']);
				          $remidialdetail[$i]['HsseRemidial']['closeDate']='';
					  $remidialdetail[$i]['HsseRemidial']['remidial_closer_summary']='';
					
				 }elseif($remidialdetail[$i]['HsseRemidial']['remidial_closure_date']!='0000-00-00'){
					 $closerDate=explode("-",$remidialdetail[$i]['HsseRemidial']['remidial_closure_date']);
				         $remidialdetail[$i]['HsseRemidial']['closeDate']=date("d-M-y", mktime(0, 0, 0, $closerDate[1], $closerDate[2], $closerDate[0]));
					
				 }
				
				 
			}
			
			  
			
			 $this->set('remidialdetail',$remidialdetail);
		  }else{
			 $this->set('remidialdetail',array());
			$this->set('remidial',0);
		  }
		  
		  /****************Investigation Team******************/
		   $invetigationDetail = $this->HsseInvestigation->find('all', array('conditions' => array('HsseInvestigation.report_id' =>base64_decode($id))));
		   if(count($invetigationDetail)){
			$condition = "AdminMaster.isblocked = 'N' AND AdminMaster.isdeleted = 'N' AND AdminMaster.id IN (".$invetigationDetail[0]['HsseInvestigation']['team_user_id'].")";
			$investigation_team = $this->AdminMaster->find('all', array('conditions' =>  $condition));
			$this->set('invetigationDetail',$invetigationDetail);
			$this->set('investigation_team',$investigation_team);
		   }else{
		       $this->set('investigation_team',array());	
		   }
		   
		    /****************Incident Investigation******************/
		     $incidentInvestigationDetail = $this->HsseInvestigationData->find('all', array('conditions' => array('HsseInvestigationData.report_id' =>base64_decode($id),'HsseInvestigationData.isdeleted'=>'N','HsseInvestigationData.isblocked'=>'N'),'recursive'=>2));
			     
	             if(count($incidentInvestigationDetail)>0){
		     for($i=0;$i<count($incidentInvestigationDetail);$i++){
			
			 $incidentDetail = $this->HsseIncident->find('all', array('conditions' => array('HsseIncident.id' =>$incidentInvestigationDetail[$i]['HsseInvestigationData']['incident_id'])));
			 $incidentInvestigationDetail[$i]['HsseInvestigationData']['incident_summary']=$incidentDetail[0]['HsseIncident']['incident_summary'];
			 $lossDetail = $this->Loss->find('all', array('conditions' => array('id' =>$incidentDetail[0]['HsseIncident']['incident_loss'])));
			 $incidentInvestigationDetail[$i]['HsseInvestigationData']['loss']=$lossDetail[0]['Loss']['type'];
		         $immidiate_cause=explode(",",$incidentInvestigationDetail[$i]['HsseInvestigationData']['immediate_cause']);
			 
		         if($immidiate_cause[0]!=0 || $immidiate_cause[0]!=''){
			   $incidentInvestigationDetail[$i]['HsseInvestigationData']['imd_cause'] = $this->ImmediateCause->find('all', array('conditions' => array('ImmediateCause.id'=>$immidiate_cause[0])));
			    }
			  
		        if($immidiate_cause[1]!=0 || $immidiate_cause[1]!=''){

			   $incidentInvestigationDetail[$i]['HsseInvestigationData']['imd_sub_cause'] = $this->ImmediateSubCause->find('all', array('conditions' => array('ImmediateSubCause.id'=>$immidiate_cause[1])));
					
                          }
			  
			  if($incidentInvestigationDetail[$i]['HsseInvestigationData']['root_cause_id']!=0){
				$explode_rootcause=explode(",",$incidentInvestigationDetail[$i]['HsseInvestigationData']['root_cause_id']);
				
				for($j=0;$j<count($explode_rootcause);$j++){
					if($explode_rootcause[$j]!=0){
					 $rootCauseDetail = $this->RootCause->find('all', array('conditions' => array('RootCause.id' =>$explode_rootcause[$j])));
					 $incidentInvestigationDetail[$i]['HsseInvestigationData']['root_cause_val'][$j]=$rootCauseDetail[0]['RootCause']['type'];
					}
					
					
				}
				
			  }
			  
			  if($incidentInvestigationDetail[$i]['HsseInvestigationData']['remedila_action_id']!=''){
				$explode_remedila_action_id=explode(",",$incidentInvestigationDetail[$i]['HsseInvestigationData']['remedila_action_id']);
				
				for($k=0;$k<count($explode_remedila_action_id);$k++){
					if(isset($explode_remedila_action_id[$k])){
					$remDetail = $this->HsseRemidial->find('all', array('conditions' => array('HsseRemidial.id' =>$explode_remedila_action_id[$k],'HsseRemidial.isblocked'=>'N','HsseRemidial.isdeleted'=>'N')));
					
			         	    $incidentInvestigationDetail[$i]['HsseInvestigationData']['rem_val'][$k]=$remDetail[0]['HsseRemidial']['remidial_summery'];
				   
				    
				     $incidentInvestigationDetail[$i]['HsseInvestigationData']['rem_val_id'][$k]=$remDetail[0]['HsseRemidial']['id'];
					}
					
				}
				
			  }
			  
		
		     }
		        $this->set('incidentInvestigationDetail',$incidentInvestigationDetail);
	             }else{
			$this->set('incidentInvestigationDetail',array());
			
		     }
		/****************Client Feedback******************/
		
	
		 if(count($clientdetail)>0){
			 if($clientdetail[0]['HsseClient']['clientreviewed']==3){
				  $clientfeeddetail = $this->HsseClientfeedback->find('all', array('conditions' => array('HsseClientfeedback.report_id' =>base64_decode($id))));
				  

			   if(count($clientfeeddetail)>0){
				  
				  
				if($clientfeeddetail[0]['HsseClientfeedback']['client_summary']!=''){
				       $this->set('clientfeedback_summary',$clientfeeddetail[0]['HsseClientfeedback']['client_summary']);
				}else{
				       $this->set('clientfeedback_summary','');
				}
			       
		                if($clientfeeddetail[0]['HsseClientfeedback']['close_date']!='0000-00-00'){
			             $clientfeeddate=explode("-",$clientfeeddetail[0]['HsseClientfeedback']['close_date']);
			             $clsdt=date("d-M-y", mktime(0, 0, 0, $clientfeeddate[1],$clientfeeddate[2],$clientfeeddate[0]));
			             $this->set('feedback_date',$clsdt);
			 
		                }else{
			                $this->set('feedback_date','');
			          }
			   }else{
				$this->set('clientfeedback_summary','');
				 $this->set('feedback_date','');
				
			   }

			}
		  }
		    
		    
	 }
	 
	
   
	function  linkrocess(){
		   
		         $this->layout="ajax";
			 $this->_checkAdminSession();
         	         $this->_getRoleMenuPermission();
		         $this->grid_access();
			 $linkArray=array();
			 $explode_id_type=explode("_",$this->data['type']);
		          $sqlinkDetail = $this->HsseLink->find('all', array('conditions' => array('HsseLink.report_id' =>base64_decode($this->data['report_no']),'HsseLink.link_report_id' =>$explode_id_type[1],'HsseLink.type' =>$explode_id_type[0])));
			 if(count($sqlinkDetail)>0){
				echo 'avl';
				
			 }else{
			 $linkArray['HsseLink']['type']=$explode_id_type[0];
			 $linkArray['HsseLink']['report_id']=base64_decode($this->data['report_no']);
			 $linkArray['HsseLink']['link_report_id']=$explode_id_type[1];
			 $linkArray['HsseLink']['user_id']=$_SESSION['adminData']['AdminMaster']['id'];
			 
			 if($this->HsseLink->save($linkArray)){
				 echo 'ok';
			 }else{
			         echo 'fail';
			 }
			
			 
			 }
			   
			   
			
                        
		   exit;
		
	}
	  public function report_hsse_link_list($id=null,$typSearch){
		
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
                $this->grid_access();
		$this->hsse_client_tab();
		$this->layout="after_adminlogin_template";
		$sqlinkDetail = $this->HsseLink->find('all', array('conditions' => array('HsseLink.report_id' =>base64_decode($id))));
		$reportdetail = $this->Report->find('all', array('conditions' => array('Report.id' =>base64_decode($id))));            
		$this->set('report_number',$reportdetail[0]['Report']['report_no']);
		$this->set('report_id',$id);
		$this->set('id',base64_decode($id));
		$this->set('report_type','all');
		  $reportdetail = $this->Report->find('all', array('conditions' => array('Report.id' =>base64_decode($id))));     
		  $this->set('report_id_val',$id);
		  $this->AdminMaster->recursive=2;
		  $userDeatil = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$_SESSION['adminData']['AdminMaster']['id'])));
                  $clientdetail = $this->HsseClient->find('all', array('conditions' => array('HsseClient.report_id' =>base64_decode($id))));
		    
		  if(count($clientdetail)>0){
			 if($clientdetail[0]['HsseClient']['clientreviewed']==3){
			      $this->set('client_feedback',1);
		           }else if($clientdetail[0]['HsseClient']['clientreviewed']!=3){
			      $this->set('client_feedback',0);
			
		          }
			
		  }else{
			$this->set('client_feedback',0);
		  }
				  
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
	
                $condition="HsseLink.report_id =".base64_decode($report_id);
		
                if(isset($_REQUEST['filter'])){
	             $link_type=explode("~",$this->link_search($_REQUEST['value']));
		     
		     $condition .= " AND HsseLink.link_report_id ='".$link_type[0]."' AND HsseLink.type ='".$link_type[1]."'";
		     
		}
		
	        
	      
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}
	

		 $adminArray = array();	
		
		 
		 if($filterTYPE!='all'){
			
		    $condition .= " AND HsseLink.type ='".$filterTYPE."'";
		
		 }
		 
		   $count = $this->HsseLink->find('count' ,array('conditions' => $condition)); 
		   $adminA = $this->HsseLink->find('all',array('conditions' => $condition,'order' => 'HsseLink.id DESC','limit'=>$limit));
		  
		
		 
              
		  
		$i = 0;
		foreach($adminA as $rec)
		{
	
			if($rec['HsseLink']['isblocked'] == 'N')
			{
				$adminA[$i]['HsseLink']['blockHideIndex'] = "true";
				$adminA[$i]['HsseLink']['unblockHideIndex'] = "false";
				$adminA[$i]['HsseLink']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['HsseLink']['blockHideIndex'] = "false";
				$adminA[$i]['HsseLink']['unblockHideIndex'] = "true";
				$adminA[$i]['HsseLink']['isdeletdHideIndex'] = "false";
			}
			
			//$adminA[$i]['HsseLink']['link_report_no']=$this->link_grid($adminA[$i],$rec['HsseLink']['type'],'HsseLink',$rec);
	               
		      $link_type=$this->link_grid($adminA[$i],$rec['HsseLink']['type'],'HsseLink',$rec);
		      $explode_link_type=explode("~",$link_type);
		      $adminA[$i]['HsseLink']['link_report_no']=$explode_link_type[0];
		      $adminA[$i]['HsseLink']['type_name']=$explode_link_type[1];
		    $i++;
		}
		
     
		  
		  if($count==0){
			$adminArray=array();
		  }else{
			 $adminArray = Set::extract($adminA, '{n}.HsseLink');
		  }
	 
		  
                  $this->set('total', $count);  //send total to the view
		  $this->set('admins', $adminArray);  //send products to the view
		 
	  }
	  
	
	  
	
	  
	  
	  function link_block($id = null)
	     {
                        $this->_checkAdminSession();
			if(!$id)
			{
		           $this->redirect(array('action'=>'report_hsse_list'), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['HsseLink']['id'] = $id;
					  $this->request->data['HsseLink']['isblocked'] = 'Y';
					  $this->HsseLink->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	      function link_unblock($id = null)
	     {
                        $this->_checkAdminSession();
			if(!$id)
			{
		           $this->redirect(array('action'=>'report_hsse_list'), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['HsseLink']['id'] = $id;
					  $this->request->data['HsseLink']['isblocked'] = 'N';
					  $this->HsseLink->save($this->request->data,false);				
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
					  
					  $deleteproperty = "DELETE FROM `hsse_links` WHERE `id` = {$id}";
                                          $deleteval=$this->HsseLink->query($deleteproperty);
					  
			       }
		
			       echo 'ok';
			       exit;
		      }else{
			 $this->redirect(array('action'=>'report_sq_list'), null, true);
		      }
			       
			      
			       
	
	      }
	     
	      
	    
}
?>