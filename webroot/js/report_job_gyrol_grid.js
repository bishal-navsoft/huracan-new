Ext.onReady(function(){
// CUSTOM FUNCTIONS //

function onFilterItemCheck(item, checked){
        if(checked) {
            Ext.get('filterlabel').update('['+item.text+']');    
        }
    }

//Function for unblock selected records
function delete_hsse_report_main(id){
	$.ajax({
			  type: "POST",
			  url: path+"Jobs/gyro_delete/",
			  data:"id="+id,
			  success: function(res)
			  {
						
		             if(res=='ok'){
				   document.location=path+'Jobs/report_gyro_job_list/'+Base64.encode(records['data']['report_id']);
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
													url: path+'Jobs/gyro_unblock/'+selectedIds+'/'
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
														url: path+'Jobs/gyro_block/'+selectedIds+'/'
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
						
				                       delete_hsse_report_main(selectedIds);

						       
						       
										       
						       
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
        proxy: new Ext.data.HttpProxy({url: AdminListPage+'Jobs/get_all_gyro_list/'+report_id}),  //note that I used host in the url
        reader: new Ext.data.JsonReader({
        root: 'admins',
	totalProperty: 'total',
        remoteSort: true,
		fields: [
        {name: 'id'},
	{name: 'gyro_sn'},
	{name: 'gyro_no'},
	{name: 'gyro_sn_val'}, 
	{name: 'report_id'},
        {name: 'conveyance'},
	{name: 'conveyance_value'},
        {name: 'conveyance_by'},
	{name: 'conveyance_by_value'},
	{name: 'conveyance_type'},
	{name: 'conveyance_type_value'},
	{name: 'top_survey'},
	{name: 'buttom_survey'},
	{name: 'latitude'},
	{name: 'longitude'},
	{name: 'comments'},
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
							
							document.location=path+"Jobs/add_gyro_job_data/"+Base64.encode(records['data']['report_id'])+"/"+Base64.encode(records['data']['id']);
							
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
						,msg:activate_mobile_site + '<br/>'
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
									url: path+'Jobs/gyro_unblock/'+records['data']['id']+'/'
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
						,msg:deactivate_mobile_site + '<br/>'
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
									 url: path+'Jobs/gyro_block/'+records['data']['id']+'/'
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
					  delete_hsse_report_main(records['data']['id']);
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
	
 
       
        {header: gyro_no,sortable: true, dataIndex: 'gyro_no', width:80},
        {header: gyro_sn_val,sortable: true, dataIndex: 'gyro_sn_val', width:80},
	{header: conveyance_value,sortable: true, dataIndex: 'conveyance_value', width:100},
	{header: conveyance_by_value,sortable: true, dataIndex: 'conveyance_by_value', width:110},
	{header: conveyance_type_value,sortable: true, dataIndex: 'conveyance_type_value', width:140},
	{header: top_survey,sortable: true, dataIndex: 'top_survey', width:100},
        {header: latitude,sortable: true, dataIndex: 'latitude', width:100},
        {header: longitude,sortable: true, dataIndex: 'longitude', width:100},
	{header: stts, sortable: true, renderer: status, dataIndex: 'isblocked', width: 80},
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
		  text: gyro_sn_val, 
		  checked: true, 
		  group: 'filter',
		  id: 'gyro_sn_val'
		  ,checkHandler: onFilterItemCheck 
	      }), new Ext.menu.CheckItem({ 
		  text: conveyance_value, 
		  checked: true, 
		  group: 'filter',
		  id: 'conveyance_value'
		  ,checkHandler: onFilterItemCheck 
	      }), new Ext.menu.CheckItem({ 
		  text: conveyance_by_value, 
		  checked: true, 
		  group: 'filter',
		  id: 'conveyance_by_value'
		  ,checkHandler: onFilterItemCheck 
	      }), new Ext.menu.CheckItem({ 
		  text: conveyance_type_value, 
		  checked: true, 
		  group: 'filter',
		  id: 'conveyance_type_value'
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
    Ext.get('filterlabel').update(gyro_sn_val); 
     
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
