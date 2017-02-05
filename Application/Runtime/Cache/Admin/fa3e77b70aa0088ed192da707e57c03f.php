<?php if (!defined('THINK_PATH')) exit();?><script language="javascript">
$(function(){
	var tableList="tableList_vps";
	var auForm ="addForm_vps";
	var saveBtn="saveBtn_vps";
	var addDiv="addDiv_vps";
	var cancelBtn="cancelBtn_vps";
	
	var th = $(".top").height();
	th = 204-th
	var wh = $(window).height()-th;
	var pr = 30;
	var pn = false;
	if(pr>0){
		pn = true;
	}
	$("#"+tableList).datagrid({
		//title:'用户列表',
		idField:'id',
		fix:true, 
		height:wh,
		autoRowHeight:false,
		singleSelect:true,
		striped:true,
		method:'get',
		sortName:'id',
		sortOrder:'desc',
		rownumbers:true,
		pagination:pn,
		pageSize:pr,
		pageList:[30,50,80,100],
		url:'/hbvpn/Admin/Vps/pageList',
		fitColumns:true,
		nowrap:true,
		selectOnCheck:false,
		animate:true,
		checkOnSelect:false,
		onBeforeLoad: function () {  
			
		},
		onDblClickRow:function(e,rowIndex,rowData){
			
		},
		toolbar:[{
		iconCls: 'fa fa-easyui fa-plus-square',
			text : '添加',
			handler: function(){
				$('#'+auForm).form('clear');
				$('#'+auForm).form('load',{
					
				});
				$("#"+addDiv).window('open');
			}
		},'-',{
			iconCls: 'fa fa-easyui fa-edit',
			text : '编辑',
			handler: function(){
				var selectedRow=$('#'+tableList).datagrid('getSelected');
				if(null==selectedRow){
					$.message.alert('警告','请先选择一行！','warning');
				}else{
					$('#'+auForm).form('load',{
						'data[id]':selectedRow.id,
						'data[ip]':selectedRow.ip,
						'data[sshport]':selectedRow.sshport,
						'data[account]':selectedRow.account,
						'data[password]':selectedRow.password,
						'data[server_id]':selectedRow.server_id,
					});
					$("#"+addDiv).window('open');
				}
			}
		},'-',{
			iconCls: 'fa fa-easyui fa-times',
			text : '删除',
			handler: function(){
				var selectedRow=$('#'+tableList).datagrid('getSelected');
				if(null==selectedRow){
					$.message.alert('警告','请先选择一行！','warning');
				}else{
					var ids=selectedRow.id;
					ajaxDelRowsDatagrid('/hbvpn/Admin/Vps/delRows',ids,tableList);
				}
			}
		},'-',{
			iconCls: 'fa fa-easyui fa-check-square-o',
			text : '取消选择',
			handler: function(){
				$('#'+tableList).datagrid('clearSelections'); 
			}
		},'-',{
			iconCls: 'fa fa-easyui fa-retweet',
			text : '重载',
			handler: function(){
				$("#"+tableList).datagrid('reload');
			}
		}],
		frozenColumns:[[   
			{field:'id',checkbox:true}
		]],
		columns:[[  
			{field:'ip',title:'服务器ip',width:40,sortable:true},
			{field:'sshport',title:'远程端口',width:40,sortable:true},
			{field:'account',title:'远程帐号',width:40,sortable:true},
			{field:'password',title:'远程密码',width:40,sortable:true},
			{field:'serverName',title:'服务商',width:40,sortable:true},
			{field:'action',title:'功能操作',width:40,sortable:true,
				formatter: function(value,row,index){
					var html='';
					if (row.id){
						html=html+'<a href="javascript:config_div_vps('+row.id+');" class="easyui-linkbutton" style="width:80px;color:black;">生成配置文件</a>';
					}
					return html;
				}		
			}
		]]
	});
	$("#"+saveBtn).click(function(){
		ajaxSubmitForm(auForm,'/hbvpn/Admin/Vps/addOrUpdate',addDiv,tableList);
	});
	$("#"+cancelBtn).click(function(){
		$('#'+addDiv).window('close');
	});
	
	$('#server_vps').combobox({
	    url:'/hbvpn/Admin/Server/getAll',
	    valueField:'id',
	    textField:'name'
	});
	
});

function config_div_vps(id){
	$("#config_vps_id_vps").val(id);
	
	var o={};
	o['vps_id']=id;
	
	var rows=ajaxReturnList("/hbvpn/Admin/Account/getAllByExample",o);
	for(var i=0;i<rows.length;i++){
		add_account_tr_vps(rows[i]);
	}
	
	$('#configDiv_vps').window('open');
}

function add_config_vps(){
	if($('#configForm_vps').form('validate')){
		var o={};
		o['vps_id']=$("#config_vps_id_vps").val();
		o['vps_count']=$("#config_vps_count_vps").combobox('getValue');
		o['secret_way']=$("#config_secret_way_vps").combobox('getValue');
		var rtr=ajaxReturnRow("/hbvpn/Admin/account/addRows",o);
		$("#configTable_vps").empty();
		for(var i=0;i<rtr['rows'].length;i++){
			add_account_tr_vps(rtr['rows'][i]);
		}
		
		$("#show_config_vps").text(rtr['ss_config']);
	}	
}

function add_account_tr_vps(rtr){
	$("#configTable_vps").append('<tr id="idcard-del-control-'+rtr.id+'" class="idcard-del-control">'
								+'<td></td><td>'+rtr.ip+'</td>'+'<td></td><td>'+rtr.port+'</td>'
								+'<td></td><td>'+rtr.password+'</td>'+'<td></td><td>'+rtr.secret_way+'</td>'
							+'</tr>');
}
</script>
<div>
	<table id="tableList_vps"></table>
</div>
<div id="addDiv_vps" class="easyui-window" title="添加" data-options="iconCls:'fa fa-dot-circle-o',closed:true" style="width:600px;height:360px;padding:5px;">
	<div class="easyui-layout" data-options="fit:true">
		<div data-options="region:'center',border:true">
			<form id="addForm_vps" class="easyui-form" method="post" data-options="novalidate:true">
				<input name="data[id]" type="hidden" />
		    	<table cellpadding="5">
		    		<tr>
		    			<td><div style="width:62px;text-align: right;">服务器ip:</div></td>
		    			<td><input class="easyui-textbox" name="data[ip]" data-options="required:true" style="width:455px;height:30px;" type="text"></input></td>
		    			
		    		</tr>
		    		<tr>
		    			<td><div style="width:62px;text-align: right;">远程端口:</div></td>
		    			<td><input class="easyui-textbox" name="data[sshport]" data-options="required:true" style="width:455px;height:30px;" type="text"></input></td>
		    		</tr>
		    		<tr>
		    			<td><div style="width:62px;text-align: right;">远程帐号:</div></td>
		    			<td><input class="easyui-textbox" name="data[account]" data-options="required:true" style="width:455px;height:30px;" type="text"></input></td>
		    		</tr>
		    		
		    		<tr>
		    			<td><div style="width:62px;text-align: right;">远程密码:</div></td>
		    			<td><input class="easyui-textbox" name="data[password]" data-options="required:true" style="width:455px;height:30px;" type="text"></input></td>
		    		</tr>
		    		<tr>
		    			<td><div style="width:62px;text-align: right;">服务商:</div></td>
		    			<td>
		    				<select id="server_vps" name="data[server_id]" class="easyui-combobox" data-options="required:true" style="width:455px;height:30px;" ></select>
		    			</td>
		    		</tr>
		    	</table>
		    </form>
		</div>
		<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
			<a id="saveBtn_vps" class="easyui-linkbutton" data-options="iconCls:'fa fa-easyui fa-check'" href="javascript:void(0)" style="width:80px">保存</a>
			<a id="cancelBtn_vps" class="easyui-linkbutton" data-options="iconCls:'fa fa-easyui fa-times'" href="javascript:void(0)" style="width:80px">取消</a>
		</div>
	</div>
</div>

<div id="configDiv_vps" class="easyui-window" title="生成配置帐号文件" data-options="iconCls:'fa fa-dot-circle-o',closed:true" style="width:700px;height:600px;padding:5px;">
	<div class="easyui-layout" data-options="fit:true">
		<div data-options="region:'center',border:true">
			<form id="configForm_vps" class="easyui-form" method="post" data-options="novalidate:true">
				<input id="config_vps_id_vps" name="data[vps_id]" type="hidden" />
				<table>
					<tr>
		    			<td><div style="width:62px;text-align: right;">帐号数量:</div></td>
		    			<td>
		    				<select id="config_vps_count_vps" name="data[vps_count]" class="easyui-combobox" data-options="editable:false" style="width:200px;height:26px;">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="10">10</option>
							</select>
						</td>
						<td><div style="width:62px;text-align: right;">加密方式:</div></td>
		    			<td>
		    				<select id="config_secret_way_vps" name="data[secret_way]" class="easyui-combobox" data-options="editable:false" style="width:200px;height:26px;">
								<option value="aes-256-cfb">aes-256-cfb</option>
							</select>
						</td>
						<td><a href="javascript:add_config_vps();" class="easyui-linkbutton" data-options="iconCls:'icon-add'">添加</a></td>
		    		</tr>
				</table>
				<table id="configTable_vps" cellpadding="5"></table>
				<div id="show_config_vps"></div>
		    </form>
		</div>
	</div>
</div>