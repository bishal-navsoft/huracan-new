Ext.onReady(function(){
// CUSTOM FUNCTIONS //

function onFilterItemCheck(item, checked){
        if(checked) {
            Ext.get('filterlabel').update('['+item.text+']');    
        }
    }

//Function for unblock selected records
function delete_remidial(id){

		$.ajax({
			  type: "POST",
			  url: path+"Jhas/remidial_email_delete/",
			  data:"id="+id,
			  success: function(res)
			  {
							
				if(res=='ok'){
				     document.location=path+'Jhas/jha_email_list/'+report_val;
				   }
				
				
                             }
	});
	
}



function view_remedial(id,leid){
	jQuery.fancybox({
			'autoScale': true,
			'transitionIn'		: 'fade',
			'transitionOut'		: 'fade',
			'href'			:  path+"Jhas/jha_email_view/"+id+"/"+leid,
			'hideOnOverlayClick' : false,
			'overlayShow'   :   false
			
		});
	
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
						          delete_remidial(selectedIds);
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
        proxy: new Ext.data.HttpProxy({url: AdminListPage+'Jhas/get_all_jha_email_list/'+report_id}),  //note that I used host in the url
        reader: new Ext.data.JsonReader({
        root: 'admins',
	totalProperty: 'total',
        remoteSort: true,
	fields: [
          {name: 'id'},
	  {name: 'create_date'},
	  {name: 'create_on_val'},
	  {name: 'leid'},
	  {name: 'type'},
	  {name: 'email_date'},
	  {name: 'revalidate_date'},
	  {name: 'revalidate_date_val'},
	  {name: 'status_value'},
	  {name: 'createby_person'},
	  {name: 'email'},
	  {name: 'isblocked'},
	  {name: 'blockHideIndex', type: 'boolean'},
	  {name: 'unblockHideIndex', type: 'boolean'},
	 
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
			qtip: dlt,
			iconCls: 'remove',
			callback:function(grid, records, action, groupId) {				
				var tp="View";
				var turl="block";
				if(is_delete == 1)
					{
					  delete_remidial(records['data']['id']);
					}
					else
					{
						Ext.Msg.alert(warning,not_allowed_access);
					}
				
			}
			
		},{
			qtip: view,
			iconCls: 'view',
			callback:function(grid, records, action, groupId) {				
				var tp="View";
				var turl="block";
				
				if(is_view == 1)
					{
					 view_remedial(records['data']['id'],records['data']['leid']);
					}
					else
					{
						Ext.Msg.alert(warning,not_allowed_access);
					}
				
			}
			
		},{}
		
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
	{header: create_on_val,sortable: true, dataIndex: 'create_on_val', width:105},
	{header: revalidate_date_val,sortable: true, dataIndex: 'revalidate_date_val', width:120},
        {header: email_date,sortable: true, dataIndex: 'email_date', width:105},
	{header: email,sortable: true, dataIndex: 'email', width:190},
	{header: createby_person,sortable: true, dataIndex: 'createby_person', width:190}, 
	{header: stts,sortable: true, dataIndex: 'status_value', width:190},
    	Actions
		
    ]);
	
	
	 Ext.QuickTips.init();
	 var toolBar = new Ext.Toolbar({
        items: [{
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
            text: email, 
            checked: true, 
            group: 'filter',
            id: 'email'
            ,checkHandler: onFilterItemCheck 
        }), new Ext.menu.CheckItem({ 
            text: email_date, 
            checked: true, 
            group: 'filter',
            id: 'email_date'
            ,checkHandler: onFilterItemCheck 
        }), new Ext.menu.CheckItem({ 
            text: createby_person, 
            checked: true, 
            group: 'filter',
            id: 'createby_person'
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
    Ext.get('filterlabel').update(email); 
     
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
