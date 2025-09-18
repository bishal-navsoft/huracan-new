Ext.onReady(function(){
// CUSTOM FUNCTIONS //

function onFilterItemCheck(item, checked){
        if(checked) {
            Ext.get('filterlabel').update('['+item.text+']');    
        }
    }

//Function for unblock selected records
function delete_incident_investigation(id){
	
	$.ajax({
			  type: "POST",
			  url: path+"Sqreports/investigation_data_delete/",
			  data:"data=a&id="+id,
			  success: function(res)
			  {
				
		               if(res=='ok'){
				   document.location=path+'Sqreports/report_sq_investigation_data_list/'+report_val;
				 }
				
                             }
	});
	
	
	
	
}
function unblockSelected()
{
						
	var selectedArray = new Array();
        selectedArray = checkBox.getSelections();
	if(selectedArray.length == 0)
	{
		alert(select_one_record);
		return false;
	}	
	
	if(is_block == 1)
	{
		Ext.Msg.show({
				title:activ_select_record
			       ,msg:activate_select_record + '</b><br/>.'
			       ,icon:Ext.Msg.QUESTION
			       ,buttons:Ext.Msg.YESNO
			       ,scope:this
			       ,fn:function(response) {
				       if('yes' !== response) {
					       return;
				       }
				       else
				       {
					       var box = Ext.MessageBox.wait(please_wait, performing_actions);
					       var selectedIds = "";
					       for(var i=0; i<selectedArray.length; i++)
					       {
						       if(i==0)
						       {
							       selectedIds = selectedArray[i]["data"]["id"];
						       }else
						       {
							       selectedIds = selectedIds+"^"+selectedArray[i]["data"]["id"];
						       }						
						       
						       
	
					       }
					       Ext.Ajax.request(
										       {
													url: path+'Sqreports/investigation_data_unblock/'+selectedIds+'/'
													,method:'GET'
													,success: function(response){
													       ds.reload();
													       box.hide();
												       }
												       ,failure: function(response){
													       box.hide();
													       Ext.Msg.alert(err, err_unblock);
													       
													       //ds.load();
												       }
												       ,scope: this
															
										       })
														       
					       
				       }
	//              console.info('Deleting record');
			       }
	});
	
	}
	else
	{
		Ext.Msg.alert(warning,not_allowed_access);
	}	
	
}	

function blockSelected()
{
	var selectedArray = new Array();
    selectedArray = checkBox.getSelections();
	if(selectedArray.length == 0)
	{
		alert(select_one_record);
		return false;
	}
	if(is_block == 1)
	{
		Ext.Msg.show({
					title:deactiv_select_record
				       ,msg:deactivate_select_record + '</b><br/>.'
				       ,icon:Ext.Msg.QUESTION
				       ,buttons:Ext.Msg.YESNO
				       ,scope:this
				       ,fn:function(response) {
					       if('yes' !== response) {
						       return;
					       }
					       else
					       {
						       var box = Ext.MessageBox.wait(please_wait, performing_actions);										
							   var selectedIds = "";
						       for(var i=0; i<selectedArray.length; i++)
						       {
							       if(i==0)
							       {
								       selectedIds = selectedArray[i]["data"]["id"];
							       }else
							       {
								       selectedIds = selectedIds+"^"+selectedArray[i]["data"]["id"];
							       }						
							       
							       

						       }
						       Ext.Ajax.request(
											       {
														url: path+'Sqreports/investigation_data_block/'+selectedIds+'/'
														,method:'GET'
														,success: function(response){
														       ds.reload();
														       box.hide();
													       }
													       ,failure: function(response){
														       box.hide();
														       Ext.Msg.alert(err, err_block);
														       
														       //ds.load();
													       }
													       ,scope: this
																
											       })
										       
						       
					       }
	       //              console.info('Deleting record');
				       }
	});
	}
	else
	{
		Ext.Msg.alert(warning,not_allowed_access);
	}			
	
	
}

function deleteSelected()
{

	var selectedArray = new Array();
    selectedArray = checkBox.getSelections();
	if(selectedArray.length == 0)
	{
		
		return false;
	}
	if(is_delete == 1)
	{
		Ext.Msg.show({
					title:del_select_record
				       ,msg:delete_select_record + '</b><br/>' + no_undo
				       ,icon:Ext.Msg.QUESTION
				       ,buttons:Ext.Msg.YESNO
				       ,scope:this
				       ,fn:function(response) {
					       if('yes' !== response) {
						       return;
					       }
					       else
					       {
						       var box = Ext.MessageBox.wait(please_wait, performing_actions);
						       var selectedIds = "";
						       for(var i=0; i<selectedArray.length; i++)
						       {
							       if(i==0)
							       {
								       selectedIds = selectedArray[i]["data"]["id"];
								       
							       }else
							       {
								       selectedIds = selectedIds+"^"+selectedArray[i]["data"]["id"];
							       }						
							       
							       

						       }
						
			                    if(is_delete == 1)
						{
						  delete_incident_investigation(selectedIds);
						}
						else
						{
							Ext.Msg.alert(warning,not_allowed_access);
						}
				
						       
						       
										       
						       
					       }
	       //              console.info('Deleting record');
				       }
	});
					
	}
	else
	{
		Ext.Msg.alert(warning,not_allowed_access);
	}
	
}

var ds = new Ext.data.Store({	
        proxy: new Ext.data.HttpProxy({url: AdminListPage+'Sqreports/get_all_sq_investigation_data_list/'+report_id}),  //note that I used host in the url
        reader: new Ext.data.JsonReader({
        root: 'admins',
	totalProperty: 'total',
        remoteSort: true,
		fields: [
          {name: 'id'},
	  {name: 'report_id'},
	  {name: 'report_number'},
	  {name: 'incident_id'},
	  {name: 'damage'},
	  {name: 'investigation_no'},
	  {name: 'incident_no'},
	  {name: 'imd_cause_name'},
	  {name: 'incidentSeverity'},
	  {name: 'SqService'},
	  {name: 'root_cause_list'},
	  {name: 'comments'},
	  {name: 'remidial_closer_summary'},
	  {name: 'isblocked'},
	  {name: 'blockHideIndex', type: 'boolean'},
	  {name: 'unblockHideIndex', type: 'boolean'},
	  {name: 'isdeletdHideIndex', type: 'boolean'},
	]
	})
    });  
	
	var pagingBar = new Ext.PagingToolbar({
        pageSize: eval(pagelmt),
        store: ds,
        displayInfo: true,
        displayMsg: display_topics, 
        emptyMsg: no_display_records
        
    });
	//alert(eval(pagelmt));
	
	
	
	
		var checkBox = new Ext.grid.CheckboxSelectionModel();

		var Actions = new Ext.ux.grid.RowActions({
				header:acts	
				,dataIndex: 0
				,actions: [{
					qtip: edt,
					iconCls: 'edit',
					callback:function(grid, records, action, groupId) {	
						if(is_edit == 1)
						{
							//location.href = path+"Users/add_mobilesite/"+records['data']['id']+"/";
							location.href = path+"Sqreports/add_sq_investigation_data_analysis/"+Base64.encode(records['data']['report_id'])+"/"+Base64.encode(records['data']['incident_id'])+"/"+Base64.encode(records['data']['id']);
						}
						else
						{
							Ext.Msg.alert(warning,not_allowed_access);
						}					
				}
		     },{
			qtip: activ,
			iconCls: 'unblock',
			hideIndex : 'blockHideIndex',
			callback:function(grid, records, action, groupId) {	
				
				var tp="Activate";
				var turl="unblock";
				if(records['data']['isblocked']=="Y")
				{
					if(is_block == 1)
					{
					Ext.Msg.show({
						title:tp + ' record'
						,msg:activ_select_record + '<br/>'
						,icon:Ext.Msg.QUESTION
						,buttons:Ext.Msg.YESNO
						,scope:this
						,fn:function(response) {
							if('yes' !== response) {
							return;
							}
							else
							{
								var box = Ext.MessageBox.wait(please_wait, performing_actions);
								Ext.Ajax.request(
								{
									url: path+'Sqreports/investigation_data_unblock/'+records['data']['id']+'/'
									,method:'GET'
									,success: function(response){
										ds.reload();
										box.hide();
									}
									,failure: function(response){
										Ext.Msg.alert(err, err_unblock);
										//ds.load();
									}
									,scope: this
												 
								});	
							}
				//              	console.info('Deleting record');
						}
						});
					}
				else
				{
					Ext.Msg.alert(warning,not_allowed_access);
				}
				}else{
					  Ext.Msg.alert(message,already_delivered);
				}
			}
		},{
			qtip: deactiv,
			iconCls: 'block',
			hideIndex : 'unblockHideIndex',
			callback:function(grid, records, action, groupId) {				
				var tp="Deactivate";
				var turl="block";
				if(records['data']['isblocked']=="N")
				{
					if(is_block == 1)
					{
					
					Ext.Msg.show({
						title:tp + ' record'
						,msg:deactiv_select_record + '<br/>'
						,icon:Ext.Msg.QUESTION
						,buttons:Ext.Msg.YESNO
						,scope:this
						,fn:function(response) {
							if('yes' !== response) {
								return;
							}
							else
							{
								var box = Ext.MessageBox.wait(please_wait, performing_actions);
								Ext.Ajax.request(
								{
									 url: path+'Sqreports/investigation_data_block/'+records['data']['id']+'/'
									 ,method:'GET'
   //,params:{id:record.data.id,con:'games',act:'movetoup'}
									,success: function(response){
										ds.reload();
										box.hide();
									}
									,failure: function(response){
										Ext.Msg.alert(err, err_unblock);
										//ds.load();
									}
									,scope: this
											 
								 });						 
							}
							//console.info('Deleting record');
						}
						});
					}
					else
					{
						Ext.Msg.alert(warning,not_allowed_access);
					}
				}
				else
				{
					Ext.Msg.alert(message,already_not_delivered);
				}
			}
			
		},{
			qtip: dlt,
			iconCls: 'remove',
			callback:function(grid, records, action, groupId) {				
				var tp="View";
				var turl="block";
				if(is_delete == 1)
					{
					  delete_incident_investigation(records['data']['id']);
					}
					else
					{
						Ext.Msg.alert(warning,not_allowed_access);
					}
				
			}
			
		}
		
		  ]
		});
	
		
	function status(val)
	{
		if(val == "N"){ return act;}		
		else{ return inact;}
	}
	
    //This is the column model.  This defines the columns in my datagrid.
    //It also maps each column with the appropriate json data from my database (dataIndex).
    var cm = new Ext.grid.ColumnModel([
	 checkBox,
        /*{header: "ID", dataIndex: 'id', width: 100, hidden: true},*/
	{header: investigation_no,sortable: true, dataIndex: 'investigation_no', width:100},
	{header: incident_no,sortable: true, dataIndex: 'incident_no', width:100},
        {header: damage,sortable: true, dataIndex: 'damage', width:150},
	{header: comment,sortable: true, dataIndex: 'comments', width:175},
	/*{header: remidial_closer_summary,sortable: true, dataIndex: 'remidial_closer_summary', width:120},*/
	{header: imd_cause_name,sortable: true, dataIndex: 'imd_cause_name', width:190},
	{header: root_cause_list,sortable: true, dataIndex: 'root_cause_list', width:320},
	{header: stts, sortable: true, renderer: status, dataIndex: 'isblocked', width:83},
	Actions
		
    ]);
	
	
	 Ext.QuickTips.init();
	 var toolBar = new Ext.Toolbar({
        items: [{text:activate_selected,
            tooltip:activ_select_record,
            iconCls:'unblock',
			enableToggle: true,
			toggleHandler: unblockSelected		
		},'-',{
            text:deactivate_selected,
            tooltip:deactiv_select_record,
            iconCls:'block',
			enableToggle: true,
			toggleHandler: blockSelected				
                 
        },'-',{
            text:delete_selected,
            tooltip:delete_select_record,
            iconCls:'remove',
			enableToggle: true,
			toggleHandler: deleteSelected				
                 
        }]
    });
    
	
    //Here's where we define our datagrid.  
    //We have to specify our dataStore and our columnModel.
    var grid = new Ext.grid.GridPanel({
		ds: ds,
		cm: cm,
		sm: checkBox,    
        buttonAlign:'center',
		trackMouseOver:true,
		stripeRows: true,  
        disableSelection:true,
        loadMask: true,
        // inline toolbars
        tbar: toolBar,
		plugins: [Actions],
        //frame:true,
        iconCls:'icon-grid',
		stripeRows: true,
		height: 'auto',
		width: 'auto',
		//style: 'color:#0093a8',
		title: admins,
		bbar: pagingBar
		,cls: 'test'
		});
		grid.render('grid-paging'); 
		
	  var filterMenuItems = [
	    new Ext.menu.CheckItem({ 
            text: damage, 
            checked: true, 
            group: 'filter',
            id: 'damage'
            ,checkHandler: onFilterItemCheck 
        }),
	 new Ext.menu.CheckItem({ 
            text: 'Immediate', 
            checked: true, 
            group: 'filter',
            id: 'imd_cause_name'
            ,checkHandler: onFilterItemCheck 
        }),
	 new Ext.menu.CheckItem({ 
            text: root_cause_list, 
            checked: true, 
            group: 'filter',
            id: 'root_cause_list'
            ,checkHandler: onFilterItemCheck 
        })
    ];
    var filterMenu = new Ext.menu.Menu({
	    id: 'filterMenu',
	    items: filterMenuItems
    });
	
	toolBar.addSeparator();
    
    toolBar.add({
	    text: search_by,
	    tooltip: set_column_search,
	    //icon: 'find.png',
	    cls: 'x-btn-text-icon btn-search-icon',
	    menu: filterMenu
    });

    var filterlabel = toolBar.addDom({
	    tag: 'div',
	    id: 'filterlabel',
        style:'color:#1099AC;padding:0 4px;width:80px;text-align:center;font-size: 14px;'
    });
    Ext.get('filterlabel').update(damage); 
     
    var filter = toolBar.addDom({
	    tag: 'input',
	    id: 'sfilter',
	    type: 'text',
	    size: 30,
	    value: '',
	    style: 'background: #ffffff;'
    });
	
	 var combo = new Ext.ux.form.HistoryClearableComboBox({
        emptyText:search_text,
        selectOnFocus:true,
        resizable:true,
        hideClearButton: false,
	    hideTrigger: false,
	    typeAhead: true,
	    triggerAction: 'all',
		applyTo:'sfilter'
    });
	
	var button = toolBar.addButton( {
						text: '',
						disabled:false,
						iconCls:'search',
						tooltip:go,
						handler : function(){                         
						 var filterCol = filterMenuItems.filter(function(element, index, array) {
							return element.checked;
						})[0].id;
						
						if(combo.getValue() == "")
						{
							alert(provide_search_string);
							return false;
						}
						
						ds.load({params:{start: 0, limit: pagelmt, filter: filterCol, value: combo.getValue()}});
						 
                         }
                     }); 

    //toolBar.addSeparator();

	ds.load({params:{start: 0, limit: pagelmt}});
    //ds.loadData(<?php echo '{"total":'.$total.', "products":'.$javascript->Object($products).'}'; ?>); //This loads data from the database into the datastore.
 
	//grid.render('grid-paging');  //This renders our grid to the grid-paging div in our index.ctp view.
});
