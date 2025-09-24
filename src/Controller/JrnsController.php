<?PHP
class JrnsController extends AppController
{
	public $name = 'Jrns';
	public $uses = array('SqReportMain','Report','JnChecklist','LessonMain','JobReportMain','AuditReportMain','JnLink','JnAttachment','JnReportMain','Incident','Vehicle','JnPersonnel','IncidentLocation','WellStatus','Welldata','SqWellData','SqDamage','SqService','SqReportIncident','SqInvestigation','SqRemidial','SqClientfeedback','BusinessType','Fieldlocation','Client','IncidentLocation','IncidentSeverity','Country','AdminMaster','SqPersonnel','RolePermission','RoleMaster','AdminMenu','IncidentCategory','SqDamage','IncidentSubCategory','Residual','SqAttachment','Priority','ImmediateCause','ImmediateSubCause','RootCause','SqInvestigationData','Potential','SqLink','SqReportIncident','DocumentMain');
	public $helpers = array('Html','Form','Session','Js');
	//public $components = array('RequestHandler', 'Cookie', 'Resizer');
	
        public function report_jrn_list(){
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
		
		unset($_SESSION['jnIdBollen']);
		$jnIdBollen=array();
		$condition="";
		$condition = "JnReportMain.isdeleted = 'N'";
		
		$adminA = $this->JnReportMain->find('all' ,array('conditions' => $condition,'order' => 'JnReportMain.id DESC','limit'=>$limit));
		for($i=0;$i<count($adminA);$i++){
			if(($_SESSION['adminData']['AdminMaster']['id']==$adminA[$i]['JnReportMain']['created_by']) || ($_SESSION['adminData']['RoleMaster']['id']==1)){
			   array_push($jnIdBollen,1);	
				
			}else{
			  array_push($jnIdBollen,0);
			}
			
		}
		$this->Session->write('jnIdBollen',$jnIdBollen);
		
		
	}
	public function get_all_report($action ='all')
	{
		Configure::write('debug', '2');  
		$this->layout = "ajax"; 
		$this->_checkAdminSession();
		$condition="";
		$condition = "JnReportMain.isdeleted = 'N'";
		
		
                if(isset($_REQUEST['filter'])){
		switch($_REQUEST['filter']){
			case'trip_number':
			$condition .= "AND ucase(JnReportMain.".$_REQUEST['filter'].") like '".$_REQUEST['value']."%'";	
			break;
			case'client_name':
			$clientCondition= " ucase(Client.name) like '".$_REQUEST['value']."%'";
			$clientDetatail=$this->Client->find('all', array('conditions' =>$clientCondition));
			$condition .= "AND JnReportMain.Client =".$clientDetatail[0]['Client']['id'];	
			break;
			case'creater_name':
			$spliNAME=explode(" ",$_REQUEST['value']);
			$spliLname=$spliNAME[count($spliNAME)-1];
			$spliFname=$spliNAME[0];
			$adminCondition="AdminMaster.first_name like '%".$spliFname."%' AND AdminMaster.last_name like '%".$spliLname."%'";
			$userDetail = $this->AdminMaster->find('all',array('conditions'=>$adminCondition));
			$addimid=$userDetail[0]['AdminMaster']['id'];
			$condition .= "AND JnReportMain.created_by ='".$addimid."'";	
			break;
			case'trip_date_val':
			$explodemonth=explode('/',$_REQUEST['value']);
			$day=$explodemonth[0];
			$month=date('m', strtotime($explodemonth[1]));
			$year="20$explodemonth[2]";
			$createon=$year."-".$month."-".$day;
			$condition .= "AND JnReportMain.trip_date='".$createon."'";	
			break;
	      
		}
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}
		
		$count = $this->JnReportMain->find('count' ,array('conditions' => $condition)); 
		$adminArray = array();
		
	
		$adminA = $this->JnReportMain->find('all' ,array('conditions' => $condition,'order' => 'JnReportMain.id DESC','limit'=>$limit)); 

		$i = 0;
		$jnIdBollen=array();
		unset($_SESSION['jnIdBollen']);
		foreach($adminA as $rec)
		{
		
		
		    if(($_SESSION['adminData']['AdminMaster']['id']==$rec['JnReportMain']['created_by']) || ($_SESSION['adminData']['RoleMaster']['id']==1)){
				array_push($jnIdBollen,1);
				$adminA[$i]['JnReportMain']['edit_permit'] ="false";
				$adminA[$i]['JnReportMain']['view_permit'] ="false";
				$adminA[$i]['JnReportMain']['delete_permit'] ="false";
				$adminA[$i]['JnReportMain']['block_permit'] ="false";
				$adminA[$i]['JnReportMain']['unblock_permit'] ="false";
				$adminA[$i]['JnReportMain']['checkbox_permit'] ="false";
				
				if($rec['JnReportMain']['isblocked'] == 'N')
			            {
					$adminA[$i]['JnReportMain']['blockHideIndex'] = "true";
					$adminA[$i]['JnReportMain']['unblockHideIndex'] = "false";
				        
			         }else{
					$adminA[$i]['JnReportMain']['blockHideIndex'] = "false";
					$adminA[$i]['JnReportMain']['unblockHideIndex'] = "true";
					
			        }
				
					
			}else{
				array_push($jnIdBollen,1);
				$adminA[$i]['JnReportMain']['edit_permit'] ="true";
				$adminA[$i]['JnReportMain']['view_permit'] ="false";
				$adminA[$i]['JnReportMain']['delete_permit'] ="true";
				$adminA[$i]['JnReportMain']['block_permit'] ="true";
				$adminA[$i]['JnReportMain']['unblock_permit'] ="true";
			        $adminA[$i]['JnReportMain']['blockHideIndex'] = "true";
				$adminA[$i]['JnReportMain']['unblockHideIndex'] = "true";
				$adminA[$i]['JnReportMain']['checkbox_permit'] ="true";
				
				
			}
		
			$eventdate=explode("-",$rec['JnReportMain']['trip_date']);
			$evDT=date("d/M/y", mktime(0, 0, 0, $eventdate[1],$eventdate[2],$eventdate[0]));
			$adminA[$i]['JnReportMain']['trip_date_val']=$evDT;
			
			if($rec['JnReportMain']['client']==0){
			  $adminA[$i]['JnReportMain']['client_name']='N/A';	
			}else{
			  $adminA[$i]['JnReportMain']['client_name'] =$rec['Client']['name'];	
			}
			  $adminA[$i]['JnReportMain']['creater_name'] =$rec['AdminMaster']['first_name']." ".$rec['AdminMaster']['last_name'];
	                
		  
		    $i++;
		}
		
               
		if($count==0){
			$adminArray=array();
	        }else{
			$adminArray = Set::extract($adminA, '{n}.JnReportMain');
		      }
		
		
		$this->Session->write('jnIdBollen',$jnIdBollen);
		$this->set('total', $count);  //send total to the view
		$this->set('admins', $adminArray);  //send products to the view
         	$this->set('status', $action);
	}

	
	
	
	function jnreport_block($id = null)
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
				   $this->request->data['JnReportMain']['id'] = $id;
				   $this->request->data['JnReportMain']['isblocked'] = 'Y';
				   $this->JnReportMain->save($this->request->data,false);				
			}	     
			exit;			 
		}
	}
	
	function jnreport_unblock($id = null)
	{

		
		if(!$id)
		{
	
			$this->redirect(array('action'=>report_jrn_list), null, true);
		}
		else
		{
			$idArray = explode("^", $id);
		
			foreach($idArray as $id)
			{
				$id = $id;
				$this->request->data['JnReportMain']['id'] = $id;
				$this->request->data['JnReportMain']['isblocked'] = 'N';
				$this->JnReportMain->save($this->request->data,false);
			}	
			exit;
		 
		}
	}
	
	function jnreport_main_delete()
	     {
		      $this->layout="ajax";
		      
	      
		      if($this->data['id']!=''){
			$idArray = explode("^", $this->data['id']);
                           foreach($idArray as $id)
			       {
				   $deletePersonnel = "DELETE FROM `jn_personnels` WHERE `report_id` = {$id}";
                                   $deleteP=$this->JnPersonnel->query($deletePersonnel);
				   
				   $deleteChecklist = "DELETE FROM `jn_checklists` WHERE `report_id` = {$id}";
                                   $deleteC=$this->JnChecklist->query($deleteChecklist);
				   
				   $deleteLink = "DELETE FROM `jn_links` WHERE `report_id` = {$id}";
                                   $deleteL=$this->JnLink->query($deleteLink);
				   
				   $deleteAttachment = "DELETE FROM `jn_attachments` WHERE `report_id` = {$id}";
                                   $deleteA=$this->JnAttachment->query($deleteAttachment);
				
                                   $deleteMain = "DELETE FROM `jn_report_mains` WHERE `id` = {$id}";
                                   $deleteval=$this->JnReportMain->query($deleteMain);
                  

					  
			       }
		
			       echo 'ok';
			        exit;
		      }else{
			 $this->redirect(array('action'=>report_jrn_list), null, true);
		      }
			 
	
			       
	}
	
	
	

	
     public function add_jn_report_main($id=null)
		
	{
		 $this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
		 $this->grid_access();
                 $this->layout="after_adminlogin_template";

       $businessDetail = $this->BusinessType->find('all',array('conditions' => array('BusinessType.rtype' =>'all')));

		 $fieldlocationDetail = $this->Fieldlocation->find('all');
		 $clientDetail = $this->Client->find('all');
		 $countryDetail = $this->Country->find('all');
		 $userDetail = $this->AdminMaster->find('all');
		 $this->set('businessDetail',$businessDetail);
		 $this->set('fieldlocationDetail',$fieldlocationDetail);
		 $this->set('clientDetail',$clientDetail);
		 $this->set('country',$countryDetail);
		 $this->set('operating_time','');
		 
		 if($id==null)
		 {
          
			$this->set('id','0');
		        $this->set('cnt',13);
			$this->set('created_by',$_SESSION['adminData']['AdminMaster']['first_name']." ".$_SESSION['adminData']['AdminMaster']['last_name']);
                        $this->set('heading','Add Journey Report (Main)');
			$this->set('departure_time','00:00');
		        $this->set('button','Update');
		        $this->set('trip_number','');
			$this->set('trip_date','');
			$this->set('cert_number','');
			$this->set('cnt',13);
			$this->set('business_unit','');
			$this->set('client','');
			$this->set('field_location','');
			$this->set('summary','');
			$this->set('details','');
			$this->set('well','');
			$this->set('rig','');
			$this->set('weed_hygiene',0);
			$this->set('checked_weed_hygiene','');
			$trip_number=date('YmdHis');
			$this->set('trip_number',$trip_number);
			$this->set('report_id',0);
	                $this->set('created_by',$_SESSION['adminData']['AdminMaster']['first_name']." ".$_SESSION['adminData']['AdminMaster']['last_name']);
		 
		 
		 }else if(base64_decode($id)!=null){
			
	          
		       $reportdetail = $this->JnReportMain->find('all', array('conditions' => array('JnReportMain.id' =>base64_decode($id))));
                       $adminDATA = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$reportdetail[0]['JnReportMain']['created_by'])));
	                $this->Session->write('report_create',$reportdetail[0]['JnReportMain']['created_by']);
		       
		       $this->set('id',base64_decode($id));
			
			if($reportdetail[0]['JnReportMain']['trip_date']!=''){
			 
			         $evndt=explode("-",$reportdetail[0]['JnReportMain']['trip_date']);
			         $trip_date=$evndt[1]."-".$evndt[2]."-".$evndt[0];
			 
			}else{
			         $event_date=''; 	
			}
	  	        $this->set('trip_date',$trip_date);

			if($reportdetail[0]['JnReportMain']['weed_hygiene']==1){
				$this->set('weed_hygiene',1);
			        $this->set('checked_weed_hygiene','checked');
				
			}elseif($reportdetail[0]['JnReportMain']['weed_hygiene']==0){
				$this->set('weed_hygiene',0);
				$this->set('checked_weed_hygiene','');
			}
					
		        $this->set('heading','Update Journey Report (Main)');
		        $this->set('button','Update');
		        $this->set('trip_number',$reportdetail[0]['JnReportMain']['trip_number']);
			$this->set('trip_date',$trip_date);
			$this->set('cert_number',$reportdetail[0]['JnReportMain']['cert_number']);
			$this->set('departure_time',$reportdetail[0]['JnReportMain']['departure_time']);
			$this->set('cnt',$reportdetail[0]['JnReportMain']['country']);
			$this->set('business_unit',$reportdetail[0]['JnReportMain']['business_unit']);
			$this->set('client',$reportdetail[0]['JnReportMain']['client']);
			$this->set('field_location',$reportdetail[0]['JnReportMain']['field_location']);
			$this->set('summary',$reportdetail[0]['JnReportMain']['summary']);
			$this->set('details',$reportdetail[0]['JnReportMain']['details']);
			$this->set('well',$reportdetail[0]['JnReportMain']['well']);
			$this->set('rig',$reportdetail[0]['JnReportMain']['rig']);
	           	$this->set('report_id',base64_decode($id));
			$this->set('created_by',$adminDATA[0]['AdminMaster']['first_name']." ".$adminDATA[0]['AdminMaster']['last_name']);
		 
		 
		 }
	}
	
	function jnmainprocess()
	 {
		
		
	   $this->layout = "ajax";
	   $this->_checkAdminSession();
           $jnReportMainData=array();
	   
          if($this->data['add_jn_main_form']['id']==0){
		   $res='add';
 	                
	   }else{
		   $res='update';
		   $jnReportMainData['JnReportMain']['id']=$this->data['add_jn_main_form']['id'];
		   
	
		  
	    }

      
         if($this->data['trip_date']!=''){
           $trip_date=explode("-",$this->data['trip_date']);
	   $jnReportMainData['JnReportMain']['trip_date']=$trip_date[2]."-".$trip_date[0]."-".$trip_date[1];
	   }else{
	   $jnReportMainData['JnReportMain']['trip_date']='';	
	   }
	   
	   $jnReportMainData['JnReportMain']['user_id']=$_SESSION['adminData']['AdminMaster']['id']; 
	   $jnReportMainData['JnReportMain']['trip_number']=$this->data['trip_number']; 
	   $jnReportMainData['JnReportMain']['country']=$this->data['country'];
	   $jnReportMainData['JnReportMain']['cert_number']=$this->data['add_jn_main_form']['cert_number'];
	   $jnReportMainData['JnReportMain']['business_unit']=$this->data['business_unit'];
	   $jnReportMainData['JnReportMain']['client']=$this->data['client'];
	   $jnReportMainData['JnReportMain']['field_location']=$this->data['field_location'];
	   $jnReportMainData['JnReportMain']['weed_hygiene']=$this->data['wH'];
	   $jnReportMainData['JnReportMain']['summary']=$this->data['add_jn_main_form']['summary']; 
	   $jnReportMainData['JnReportMain']['details']=$this->data['add_jn_main_form']['details'];
	   $jnReportMainData['JnReportMain']['well']=$this->data['add_jn_main_form']['well'];
	   $jnReportMainData['JnReportMain']['rig']=$this->data['add_jn_main_form']['rig'];
           $jnReportMainData['JnReportMain']['created_by']=$_SESSION['adminData']['AdminMaster']['id'];
	   $jnReportMainData['JnReportMain']['departure_time']=$this->data['departure_time'];
         	if($this->JnReportMain->save($jnReportMainData)){
			if($res=='add'){
				$lastReport=base64_encode($this->JnReportMain->getLastInsertId());
			}elseif($res=='update'){
				$lastReport=base64_encode($this->data['add_jn_main_form']['id']);
			}
		       
		       echo $res.'~'.$lastReport;
		   }else{
		       echo 'fail';
		   }
            
         
		
	   exit;
	}
	 function add_jrnreport_view($id=null){
		 $this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
		 $this->grid_access();
                 $this->layout="after_adminlogin_template";
		
		 
		 $reportdetail = $this->JnReportMain->find('all', array('conditions' => array('JnReportMain.id' =>base64_decode($id)),'recursive'=>2));
		 $this->Session->write('report_create',$reportdetail[0]['JnReportMain']['created_by']);
                 $explode_tDate=explode("-",$reportdetail[0]['JnReportMain']['trip_date']);     
		 $reportdetail[0]['JnReportMain']['trip_date']=date("d-M-y", mktime(0, 0, 0, $explode_tDate[1], $explode_tDate[2], $explode_tDate[0]));
		 
		 if($reportdetail[0]['JnReportMain']['weed_hygiene']==1){
			$reportdetail[0]['JnReportMain']['weed_hygiene_value']='Yes';
		 }else{
			$reportdetail[0]['JnReportMain']['weed_hygiene_value']='N/a';
		 }
	      
		 
	              $this->set('reportdetail', $reportdetail); 
		 }
		 
		 
        function print_view($id=null){
		 $this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
		 $this->grid_access();
                 $this->layout="ajax";
		
		 
		 $reportdetail = $this->JnReportMain->find('all', array('conditions' => array('JnReportMain.id' =>base64_decode($id)),'recursive'=>2));
                 $explode_tDate=explode("-",$reportdetail[0]['JnReportMain']['trip_date']);     
		 $reportdetail[0]['JnReportMain']['trip_date']=date("d-M-y", mktime(0, 0, 0, $explode_tDate[1], $explode_tDate[2], $explode_tDate[0]));
		 if($reportdetail[0]['JnReportMain']['weed_hygiene']==1){
			$reportdetail[0]['JnReportMain']['weed_hygiene_value']='Yes';
		 }else{
			$reportdetail[0]['JnReportMain']['weed_hygiene_value']='N/a';
		 }
	      
		 
	              $this->set('reportdetail', $reportdetail); 
		 }
	    		 
	    
	public function add_jn_report_personnel($report_id=null,$personel_id=null){
		 $this->_checkAdminSession();
		 $this->_getRoleMenuPermission();
		 $this->grid_access();
                 $this->layout="after_adminlogin_template";
		 $user_detail= $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.isdeleted' =>'N','AdminMaster.isblocked' =>'N')));
		 for($u=0;$u<count($user_detail);$u++){
		           $seniority=explode(" ",$user_detail[$u]['AdminMaster']['modified']);
			   $snr=explode("-",$seniority[0]);
		           $user_detail[$u]['AdminMaster']['user_seniority']=$snr[2]."/".$snr[1]."/".$snr[0];
			   $user_detail[$u]['AdminMaster']['position_seniorty']=$user_detail[$u]['RoleMaster']['role_name'].'~'.$user_detail[$u]['AdminMaster']['user_seniority'].'~'.$user_detail[$u]['AdminMaster']['id'].'~'.$user_detail[$u]['AdminMaster']['phone'];
		             
		 }
		 $this->set('report_id',base64_decode($report_id));
	         $this->set('userDetail',$user_detail);
		 
	         $this->set('userDetail',$user_detail);
		 $vehicleDATA = $this->Vehicle->find('all');
		 $this->set('vehicleDATA', $vehicleDATA);
		 if($personel_id==''){
			$this->set('vid',1);
			$this->set('phone_no','');
			$this->set('person',0);
			$this->set('heading','Add Journey Personal Data');
		        $this->set('button','Submit');
			$this->set('id',0);
			$this->set('pid',0);
		
			$this->set('time_last_sleep','');
			$this->set('time_since_sleep','');
			$this->set('person','');
			$this->set('roll_name','');
			$this->set('snr','');
			$this->set('styledisplay','style="display:none"');
			
		 }elseif($personel_id!=''){
	                $personneldetail = $this->JnPersonnel->find('all', array('conditions' => array('JnPersonnel.id' =>base64_decode($personel_id)),'recursive'=>2));
			$seniority=explode(" ",$personneldetail [0]['AdminMaster']['modified']);
			$snr=explode("-",$seniority[0]);
			$seniorty=$snr[2]."/".$snr[1]."/".$snr[0];
			$roll_name=$personneldetail[0]['AdminMaster']['RoleMaster']['role_name'];
		  	$this->set('roll_name',$roll_name);
			$this->set('snr',$seniorty);
			$this->set('heading','Update Journey Personal Data');
			$this->set('button','Update');
			$this->set('id',$personneldetail[0]['JnPersonnel']['id']);
			$this->set('pid',$personneldetail[0]['JnPersonnel']['personal_data']);
			$this->set('vid',$personneldetail[0]['JnPersonnel']['vehicle']);
			$this->set('time_last_sleep',$personneldetail[0]['JnPersonnel']['last_sleep']);
			$this->set('time_since_sleep',$personneldetail[0]['JnPersonnel']['since_sleep']);
			$this->set('person',$personneldetail[0]['JnPersonnel']['personal_data']);
			$this->set('phone_no',$personneldetail[0]['JnPersonnel']['phone_no']);
			$this->set('styledisplay','style="display:block"');
			
			
		 }
		 
	}
	      function  jnpersonnelprocess(){
		         $this->layout="ajax";
		         $personnelArray=array();
			 $personalid=explode("~",$this->data['personal_data']);
			 $pid=$this->data['add_jn_personal_form']['pid'];
			 $res='';
		         if($this->data['add_jn_personal_form']['id']!=0){

					      
			      if($pid==$personalid[2]){	   
					  $personnelArray['JnPersonnel']['id']=$this->data['add_jn_personal_form']['id'];
					  $personnelArray['JnPersonnel']['personal_data']=$personalid[2];
					  $res='update';
					  
			      }
			      else if($pid!=$personalid[2]){
				      $personneldetail = $this->JnPersonnel->find('all', array('conditions' => array('JnPersonnel.personal_data'=>$personalid[2],'JnPersonnel.report_id'=>$this->data['report_id'])));
				      
				      if(count($personneldetail)>0){
					echo $res='avl';
					exit;
								
				      }else{
					 $personnelArray['JnPersonnel']['id']=$this->data['add_jn_personal_form']['id'];
					 $personnelArray['JnPersonnel']['personal_data']=$personalid[2];
					$res='update';
				      }
			     }
			   
			 }else{
				$personneldetail = $this->JnPersonnel->find('all', array('conditions' => array('JnPersonnel.report_id' =>$this->data['report_id'],'JnPersonnel.personal_data' =>$personalid[2])));
		
				 if(count($personneldetail)>0){
					 echo  $res='avl';
					  exit;
				 }else{
					 $personnelArray['JnPersonnel']['personal_data']=$personalid[2]; 
					 $res='add';
				 }
			  }
			
			  $personnelArray['JnPersonnel']['phone_no']=$this->data['phone_no'];
			  $personnelArray['JnPersonnel']['vehicle']=$this->data['vehicle_name'];	
		   	  $personnelArray['JnPersonnel']['last_sleep']=$this->data['last_sleep'];
			  $personnelArray['JnPersonnel']['report_id']=$this->data['report_id']; 
		   	 
			  $personnelArray['JnPersonnel']['since_sleep']=$this->data['since_sleep'];
		           if($this->JnPersonnel->save($personnelArray)){
				     echo $res;
		                }else{
				     echo 'fail';
			        }
			        
   
		   exit;
		
	   }
	  public function report_jn_perssonel_list($id=null){
		
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
                $this->grid_access();		
		$this->layout="after_adminlogin_template";
		 $reportdetail = $this->JnReportMain->find('all', array('conditions' => array('JnReportMain.id' =>base64_decode($id))));
		       
		 $this->set('report_number',$reportdetail[0]['JnReportMain']['trip_number']);
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
	
                $condition="JnPersonnel.report_id = $report_id  AND JnPersonnel.isdeleted = 'N'";
		
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
		         $condition .= "AND JnPersonnel.personal_data ='".$addimid."'";
			 break;
		}
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}

		 $adminArray = array();	
		 $count = $this->JnPersonnel->find('count' ,array('conditions' => $condition));
		 $adminA = $this->JnPersonnel->find('all',array('conditions' => $condition,'order' => 'JnPersonnel.id DESC','limit'=>$limit));
		  
		$i = 0;
		foreach($adminA as $rec)
		{			
			if($rec['JnPersonnel']['isblocked'] == 'N')
			{
				$adminA[$i]['JnPersonnel']['blockHideIndex'] = "true";
				$adminA[$i]['JnPersonnel']['unblockHideIndex'] = "false";
				$adminA[$i]['JnPersonnel']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['JnPersonnel']['blockHideIndex'] = "false";
				$adminA[$i]['JnPersonnel']['unblockHideIndex'] = "true";
				$adminA[$i]['JnPersonnel']['isdeletdHideIndex'] = "false";
			}
			$adminA[$i]['JnPersonnel']['name'] = $rec['AdminMaster']['first_name']." ".$rec['AdminMaster']['last_name'];
			$sam=explode(" ",$rec['AdminMaster']['modified']);
			$snr=explode("-",$sam[0]);
			$seniorty=$snr[2]."/".$snr[1]."/".$snr[0];
			$adminA[$i]['JnPersonnel']['seniority']=$seniorty;
			$rollMaster_info= $this->RoleMaster->find('all', array('conditions' => array('RoleMaster.id' =>$rec['AdminMaster']['role_master_id'])));
			if(count($rollMaster_info)>0){
				$adminA[$i]['JnPersonnel']['position']=$rollMaster_info[0]['RoleMaster']['role_name'];
			}else{
				$adminA[$i]['JnPersonnel']['position']='';
			}
			
		    $i++;
		}
		  if($count==0){
			$adminArray=array();
		  }else{
			 $adminArray = Set::extract($adminA, '{n}.JnPersonnel');
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
		           $this->redirect(array('action'=>'report_jrn_list'), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['JnPersonnel']['id'] = $id;
					  $this->request->data['JnPersonnel']['isblocked'] = 'Y';
					  $this->JnPersonnel->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	      function personnel_unblock($id = null)
	     {
                        $this->_checkAdminSession();
			if(!$id)
			{
		           $this->redirect(array('action'=>'report_jrn_list'), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['JnPersonnel']['id'] = $id;
					  $this->request->data['JnPersonnel']['isblocked'] = 'N';
					  $this->JnPersonnel->save($this->request->data,false);				
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
					  $this->request->data['JnPersonnel']['id'] =$id;
					  $this->request->data['JnPersonnel']['isdeleted'] = 'Y';
					  $this->JnPersonnel->save($this->request->data,false);
					  
			       }
		
			       echo 'ok';
			        exit;
		      }else{
			 $this->redirect(array('action'=>'report_jrn_list'), null, true);
		      }
	     }
	     
	     function add_jn_checklist($report_id=null,$checkid_id=null){
		         $this->_checkAdminSession();
		         $this->_getRoleMenuPermission();
                         $this->grid_access();
		         $this->set('report_id',base64_decode($report_id));
			$this->layout="after_adminlogin_template";
			if($checkid_id==''){
			    $this->set('nd',0);
			    $this->set('dp',0);
			    $this->set('id',0);
			    $this->set('heading','Add Checklist');
		            $this->set('button','Submit');
			    $styleC="style='display:none';";
			    $this->set('styleC', $styleC);
			    
			}else{
			   $checklistDetail = $this->JnChecklist->find('all', array('conditions' => array('JnChecklist.id' =>$checkid_id)));
			   $this->set('nd',$checklistDetail[0]['JnChecklist']['night_drive_required']);
			   if($checklistDetail[0]['JnChecklist']['night_drive_required']==1){
				$styleC="style='display:block'";
				
			   }else{
				$styleC="style='display:none'";
			   }
			   $this->set('styleC',$styleC);
			   $this->set('dp',$checklistDetail[0]['JnChecklist']['departure_performed']);
			   $this->set('id',$checkid_id);
			   $this->set('heading','Edit Checklist');
		           $this->set('button','Update');
			}
	     }
	     
	   function  jnchecklistprocess(){
		          $this->layout="ajax";
		          $checkListArray=array();
			  $res='';
			  $checklistDetail = $this->JnChecklist->find('all', array('conditions' => array('JnChecklist.report_id' =>$this->data['report_id'])));
			  if(count($checklistDetail)>0){
				$checkListArray['JnChecklist']['id']=$checklistDetail[0]['JnChecklist']['id'];
				$res='Update';
			  }else{
				$res='Add'; 
				
			  }
			  $checkListArray['JnChecklist']['night_drive_required']=$this->data['night_drive_performed'];
		   	  $checkListArray['JnChecklist']['departure_performed']=$this->data['departure_performed'];
			  $checkListArray['JnChecklist']['report_id']=$this->data['report_id'];
			  
	                  if($this->JnChecklist->save($checkListArray)){
				     echo $res;
		                }else{
				     echo 'fail';
			        }
			        
   
		   exit;
		
	   }
	   
	 	public function report_jn_attachment_list($id=null){
			$this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();   
      			$this->layout="after_adminlogin_template";
		
		        $reportdetail = $this->JnReportMain->find('all', array('conditions' => array('JnReportMain.id' =>base64_decode($id))));
			$this->set('report_number',$reportdetail[0]['JnReportMain']['trip_number']);	       
	                $this->set('id',base64_decode($id));
				if(!empty($this->request->data))
				{
					$action = $this->request->data['JnAttachment']['action'];
					$this->set('action',$action);
					$limit = $this->request->data['JnAttachment']['limit'];
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
	
		
		$condition="JnAttachment.report_id = $report_id AND    JnAttachment.isdeleted = 'N'";
		
		
                if(isset($_REQUEST['filter'])){
			$condition .= "AND ucase(JnAttachment.".$_REQUEST['filter'].") like '".$_REQUEST['value']."%'";	
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			//$condition .= " order by Category.id DESC";
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}

		//$count = $this->HsseIncident->find('count' ,array('conditions' => $condition)); 
		$adminArray = array();	
		 $count = $this->JnAttachment->find('count' ,array('conditions' => $condition));
		 $adminA = $this->JnAttachment->find('all',array('conditions' => $condition,'order' => 'JnAttachment.id DESC','limit'=>$limit));
		  
		$i = 0;
		foreach($adminA as $rec)
		{
			
			
			if($rec['JnAttachment']['isblocked'] == 'N')
			{
				$adminA[$i]['JnAttachment']['blockHideIndex'] = "true";
				$adminA[$i]['JnAttachment']['unblockHideIndex'] = "false";
				$adminA[$i]['JnAttachment']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['JnAttachment']['blockHideIndex'] = "false";
				$adminA[$i]['JnAttachment']['unblockHideIndex'] = "true";
				$adminA[$i]['JnAttachment']['isdeletdHideIndex'] = "false";
			}
			$adminA[$i]['JnAttachment']['image_src']=$this->attachment_list('JnAttachment',$rec);
		    $i++;
		}

		  $this->set('total', $count);  //send total to the view
		  
		  if($count==0){
			$adminArray=array();
	             }else{
			$adminArray = Set::extract($adminA, '{n}.JnAttachment');
		  }
		  
		  $this->set('admins', $adminArray);  //send products to the view
		  //$this->set('status', $action);
	}
	      
	          function add_jn_attachment($reoprt_id=null,$attachment_id=null){
		        $this->_checkAdminSession();
		        $this->_getRoleMenuPermission();
                        $this->grid_access();   
		        $this->layout="after_adminlogin_template";
		
			if($attachment_id!=''){
				$attchmentdetail = $this->JnAttachment->find('all', array('conditions' => array('JnAttachment.id' =>base64_decode($attachment_id))));
				$imagepath=$this->file_edit_list($attchmentdetail[0]['JnAttachment']['file_name'],'JnAttachment',$attchmentdetail);
				$this->set('heading','Update File');
			        $this->set('attachment_id',$attchmentdetail[0]['JnAttachment']['id']);
			        $this->set('description',$attchmentdetail[0]['JnAttachment']['description']);
			        $this->set('button','Update');
			        $this->set('imagepath',$imagepath);
			        $this->set('imagename',$attchmentdetail[0]['JnAttachment']['file_name']);
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
	              	$reportdetail = $this->JnReportMain->find('all', array('conditions' => array('JnReportMain.id' =>base64_decode($reoprt_id))));
			$this->set('report_number',$reportdetail[0]['JnReportMain']['trip_number']);        
			
	       }
	       
       
	       
	       
	          function uploadimage($upparname=NULL,$deleteImageName=NULL){
		        $this->layout="ajax";
			$allowed_image =$this->image_upload_type();
			$this->upload_image_func($upparname,'Jrns',$allowed_image);
			exit;
	         }
	           function  jnattachmentprocess(){
		         $this->layout="ajax";
	
		         $attachmentArray=array();
			 
		         if($this->data['Jrns']['id']!=0){
				$res='update';
				$attachmentArray['JnAttachment']['id']=$this->data['Jrns']['id'];
			 }else{
				 $res='add';
			  }
				
		   	   $attachmentArray['JnAttachment']['description']=$this->data['attachment_description'];
			   $attachmentArray['JnAttachment']['file_name']=$this->data['hiddenFile']; 
		           $attachmentArray['JnAttachment']['report_id']=$this->data['report_id'];
			   if($this->JnAttachment->save($attachmentArray)){
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
					  $this->request->data['JnAttachment']['id'] = $id;
					  $this->request->data['JnAttachment']['isblocked'] = 'Y';
					  $this->JnAttachment->save($this->request->data,false);				
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
					  $this->request->data['JnAttachment']['id'] = $id;
					  $this->request->data['JnAttachment']['isblocked'] = 'N';
					  $this->JnAttachment->save($this->request->data,false);				
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
				
					  $this->request->data['JnAttachment']['id'] = $id;
					  $this->request->data['JnAttachment']['isdeleted'] = 'Y';
					  $this->JnAttachment->save($this->request->data,false);
					  
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
		          $sqlinkDetail = $this->JnLink->find('all', array('conditions' => array('JnLink.report_id' =>base64_decode($this->data['report_no']),'JnLink.link_report_id' =>$explode_id_type[1],'JnLink.type' =>$explode_id_type[0])));
			 if(count($sqlinkDetail)>0){
				echo 'avl';
				
			 }else{
			 $linkArray['JnLink']['type']=$explode_id_type[0];
			 $linkArray['JnLink']['report_id']=base64_decode($this->data['report_no']);
			 $linkArray['JnLink']['link_report_id']=$explode_id_type[1];
			 $linkArray['JnLink']['user_id']=$_SESSION['adminData']['AdminMaster']['id'];
			 
			 if($this->JnLink->save($linkArray)){
				 echo 'ok';
			 }else{
			         echo 'fail';
			 }
			
			 
			 }
			   
			   
			
                        
		   exit;
		
	}
	  public function report_jn_link_list($id=null,$typSearch){
		
		$this->_checkAdminSession();
		$this->_getRoleMenuPermission();
                $this->grid_access();		
		$this->layout="after_adminlogin_template";
		$sqlinkDetail = $this->JnLink->find('all', array('conditions' => array('JnLink.report_id' =>base64_decode($id))));
		$reportdetail = $this->JnReportMain->find('all', array('conditions' => array('JnReportMain.id' =>base64_decode($id))));            
		$this->set('report_number',$reportdetail[0]['JnReportMain']['trip_number']);
		$this->set('report_id',$id);
		$this->set('id',base64_decode($id));
		
		
		  $this->set('report_id_val',$id);
		  $this->AdminMaster->recursive=2;
		  $userDeatil = $this->AdminMaster->find('all', array('conditions' => array('AdminMaster.id' =>$_SESSION['adminData']['AdminMaster']['id'])));
           	  $reportDeatil=$this->derive_link_data($userDeatil); 
		  $this->set('reportDeatil',$reportDeatil);
		  $this->set('typSearch', base64_decode($typSearch));
		
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
		public function get_all_link_list($report_id,$filterTYPE)
	{
		Configure::write('debug', '2'); 
		$this->layout = "ajax"; 
		$this->_checkAdminSession();
		$condition="";
	
                $condition="JnLink.report_id =".base64_decode($report_id);
		
               if(isset($_REQUEST['filter'])){
	             $link_type=explode("~",$this->link_search($_REQUEST['value']));
		     $condition .= " AND JnLink.link_report_id ='".$link_type[0]."' AND JnLink.type ='".$link_type[1]."'";
		     
		}
	 	$limit=null;
		if($_REQUEST['limit'] == 'all'){
					
			
		}else{
			$limit = $_REQUEST['start'].", ".$_REQUEST['limit'];			
		}

		 if($filterTYPE!='all'){
			
		    $condition .= " AND JnLink.type ='".$filterTYPE."'";
		
		 }
		 $adminArray = array();	
		 $count = $this->JnLink->find('count' ,array('conditions' => $condition));
		 $adminA = $this->JnLink->find('all',array('conditions' => $condition,'order' => 'JnLink.id DESC','limit'=>$limit));
		 
                
		  
		
		$i = 0;
		foreach($adminA as $rec)
		{
	
			if($rec['JnLink']['isblocked'] == 'N')
			{
				$adminA[$i]['JnLink']['blockHideIndex'] = "true";
				$adminA[$i]['JnLink']['unblockHideIndex'] = "false";
				$adminA[$i]['JnLink']['isdeletdHideIndex'] = "true";
			}else{
				$adminA[$i]['JnLink']['blockHideIndex'] = "false";
				$adminA[$i]['JnLink']['unblockHideIndex'] = "true";
				$adminA[$i]['JnLink']['isdeletdHideIndex'] = "false";
			}
			$link_type=$this->link_grid($adminA[$i],$rec['JnLink']['type'],'JnLink',$rec);
			$explode_link_type=explode("~",$link_type);
		        $adminA[$i]['JnLink']['link_report_no']=$explode_link_type[0];
		        $adminA[$i]['JnLink']['type_name']=$explode_link_type[1];
			

		
			
		    $i++;
		}
		
		  if($count==0){
			$adminArray=array();
		  }else{
			 $adminArray = Set::extract($adminA, '{n}.JnLink');
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
		           $this->redirect(array('action'=>'report_jrn_list'), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['JnLink']['id'] = $id;
					  $this->request->data['JnLink']['isblocked'] = 'Y';
					  $this->JnLink->save($this->request->data,false);				
			       }	     
			       exit;			 
		       }
	      }
	      
	      function link_unblock($id = null)
	     {
                        $this->_checkAdminSession();
			if(!$id)
			{
		           $this->redirect(array('action'=>'report_jrn_list'), null, true);
			}
		       else
		       {
				$idArray = explode("^", $id);
				
	            	        foreach($idArray as $id)
			       {
					  $id = $id;
					  $this->request->data['JnLink']['id'] = $id;
					  $this->request->data['JnLink']['isblocked'] = 'N';
					  $this->JnLink->save($this->request->data,false);				
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
					  
					  $deleteproperty = "DELETE FROM `jn_links` WHERE `id` = {$id}";
                                          $deleteval=$this->JnLink->query($deleteproperty);
					  
			       }
		
			       echo 'ok';
			       exit;
		      }else{
			 $this->redirect(array('action'=>'report_jrn_list'), null, true);
		      }
			       
			      
			       
	
	      }  

}
?>
