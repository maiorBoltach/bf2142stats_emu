//************************************
// Util Scripts
//************************************




//************************************
// Form Validation
//************************************
function emailvalidation(entered, alertbox)
{
	// E-mail-Validation (c) Henrik Petersen / NetKontoret
	// Explained at www.echoecho.com/jsforms.htm
	// Please do not remove the this line and the two lines above.
	with (entered)
	{
		apos=value.indexOf("@");
		dotpos=value.lastIndexOf(".");
		lastpos=value.length-1;
		if (apos<1 || dotpos-apos<2 || lastpos-dotpos>3 || lastpos-dotpos<2) 
		{if (alertbox) {alert(alertbox);} return false;}
		else {return true;}
	}
}

function valuevalidation(entered, min, max, alertbox, datatype)
{
	// Value-Validation (c) Henrik Petersen / NetKontoret
	// Explained at www.echoecho.com/jsforms.htm
	// Please do not remove the this line and the two lines above.
	with (entered)
	{
		checkvalue=parseFloat(value);
		if (datatype)
		{smalldatatype=datatype.toLowerCase();
			if (smalldatatype.charAt(0)=="i") {checkvalue=parseInt(value)};
		}
		if ((parseFloat(min)==min && checkvalue<min) || (parseFloat(max)==max && checkvalue>max) || value!=checkvalue)
		{if (alertbox!="") {alert(alertbox);} return false;}
		else {return true;}
	}
}

function digitvalidation(entered, min, max, alertbox, datatype)
{
	// Digit-Validation (c) Henrik Petersen / NetKontoret
	// Explained at www.echoecho.com/jsforms.htm
	// Please do not remove the this line and the two lines above.
	with (entered)
	{
		checkvalue=parseFloat(value);
		if (datatype)
		{smalldatatype=datatype.toLowerCase();
			if (smalldatatype.charAt(0)=="i") {checkvalue=parseInt(value); if (value.indexOf(".")!=-1) {checkvalue=checkvalue+1}};
		}
		if ((parseFloat(min)==min && value.length<min) || (parseFloat(max)==max && value.length>max) || value!=checkvalue)
		{if (alertbox!="") {alert(alertbox);} return false;}
		else {return true;}
	}
}

function emptyvalidation(entered, alertbox)
{
	// Emptyfield-Validation (c) Henrik Petersen / NetKontoret
	// Explained at www.echoecho.com/jsforms.htm
	// Please do not remove the this line and the two lines above.
	with (entered)
	{
		if (value==null || value=="")
		{if (alertbox!="") {alert(alertbox);} return false;}
		else {return true;}
	}
}

function checkedvalidation(entered, alertbox)
{
	// Emptyfield-Validation (c) Henrik Petersen / NetKontoret
	// Explained at www.echoecho.com/jsforms.htm
	// Please do not remove the this line and the two lines above.
	with (entered)
	{
		if (checked==null || checked==0)
		{if (alertbox!="") {alert(alertbox);} return false;}
		else {return true;}
	}
}

function ipaddressvalidation(entered, alertbox)
{
	// Emptyfield-Validation (c) Henrik Petersen / NetKontoret
	// Explained at www.echoecho.com/jsforms.htm
	// Please do not remove the this line and the two lines above.
	var ipPattern = /^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/;
	invalid_ip = false;
	
	with (entered)
	{
		var ipArray = value.match(ipPattern);
		if (value == "0.0.0.0")
			invalid_ip = true;
		else if (value == "255.255.255.255")
			invalid_ip = true;
		
		if (ipArray == null)
			invalid_ip = true;
		else {
			for (i = 0; i < 4; i++) {
				thisSegment = ipArray[i];
				if (thisSegment > 255) {
					invalid_ip = true;
					i = 4;
				}
				if ((i == 0) && (thisSegment > 255)) {
					invalid_ip = true;
					i = 4;
				}
			}
		}
	
		if (invalid_ip == true)
			{if (alertbox!="") {alert(alertbox);} return false;}
		else {return true;}
	}
}

function pathvalidation(entered, alertbox)
{
	// Emptyfield-Validation (c) Henrik Petersen / NetKontoret
	// Explained at www.echoecho.com/jsforms.htm
	// Please do not remove the this line and the two lines above.
	var iChars = "*|,\"<>[]{}`\';()@&$#%\\";
	var endChars = "/";
	invalid_path = false;
	
	with (entered)
	{
		if (!value) {
			invalid_path = true;
		} else {
			for (var i = 0; i < value.length; i++) {
				if (iChars.indexOf(value.charAt(i)) != -1) {
					invalid_path = true;
				}
			}
			if (endChars.indexOf(value.charAt(value.length-1)) == -1) {
				invalid_path = true;
			}
		}
		
		if (invalid_path == true)
			{if (alertbox!="") {alert(alertbox);} return false;}
		else {return true;}
	}
}

function filevalidation(entered, alertbox)
{
	// Emptyfield-Validation (c) Henrik Petersen / NetKontoret
	// Explained at www.echoecho.com/jsforms.htm
	// Please do not remove the this line and the two lines above.
	var iChars = "*|,\"<>[]{}`\';()@&$#%\\";
	var endChars = "/";
	invalid_path = false;
	
	with (entered)
	{
		if (!value) {
			invalid_path = true;
		} else {
			for (var i = 0; i < value.length; i++) {
				if (iChars.indexOf(value.charAt(i)) != -1) {
					invalid_path = true;
				}
			}
			if (endChars.indexOf(value.charAt(value.length-1)) != -1) {
				invalid_path = true;
			}
		}
		
		if (invalid_path == true)
			{if (alertbox!="") {alert(alertbox);} return false;}
		else {return true;}
	}
}

function extvalidation(entered, alertbox)
{
	// Emptyfield-Validation (c) Henrik Petersen / NetKontoret
	// Explained at www.echoecho.com/jsforms.htm
	// Please do not remove the this line and the two lines above.
	var iChars = "*|,\":<>[]{}`\';()@&$#%/\\";
	invalid_ext = false;
	
	with (entered)
	{
		if (!value) {
			invalid_ext = true;
		} else {
			for (var i = 0; i < value.length; i++) {
				if (iChars.indexOf(value.charAt(i)) != -1) {
					invalid_ext = true;
				}
			}
		}
		
		if (invalid_ext == true)
			{if (alertbox!="") {alert(alertbox);} return false;}
		else {return true;}
	}
}

function configvalidation(thisform)
{
	with (thisform)
	{
		// Database information
		if (emptyvalidation(cfg__db_host,"The textfield is empty")==false) {cfg__db_host.focus(); return false;};
		if (emptyvalidation(cfg__db_name,"The textfield is empty")==false) {cfg__db_name.focus(); return false;};
		if (emptyvalidation(cfg__db_user,"The textfield is empty")==false) {cfg__db_user.focus(); return false;};
		if (emptyvalidation(cfg__db_pass,"The textfield is empty")==false) {cfg__db_pass.focus(); return false;};
		
		// Stats Processing
		if (emptyvalidation(cfg__stats_ext,"The textfield is empty")==false) {cfg__stats_ext.focus(); return false;};
		if (extvalidation(cfg__stats_ext,"The extension entered contains invalid charcters")==false) {cfg__stats_ext.focus(); return false;};
		if (emptyvalidation(cfg__stats_logs,"The textfield is empty")==false) {cfg__stats_logs.focus(); return false;};
		if (pathvalidation(cfg__stats_logs,"The path entered contains invalid charcters")==false) {cfg__stats_logs.focus(); return false;};
		if (emptyvalidation(cfg__stats_logs_store,"The textfield is empty")==false) {cfg__stats_logs_store.focus(); return false;};
		if (pathvalidation(cfg__stats_logs_store,"The path entered contains invalid charcters")==false) {cfg__stats_logs_store.focus(); return false;};
		if (valuevalidation(cfg__stats_players_min,1,64,"'Player Stats MIN' MUST be in the range 1-64","I")==false) {cfg__stats_players_min.focus(); return false;};
		if (valuevalidation(cfg__stats_players_max,1,256,"'Player Stats MAX' MUST be in the range 1-256","I")==false) {cfg__stats_players_max.focus(); return false;};
		if (valuevalidation(cfg__stats_rank_tenure,1,365,"'Player Rank Tenure' MUST be in the range 1-365","I")==false) {cfg__stats_rank_tenure.focus(); return false;};
		if (emptyvalidation(cfg__stats_local_pids,"The textfield is empty")==false) {cfg__stats_local_pids.focus(); return false;};
		
		// Game Server
		if (emptyvalidation(cfg__game_hosts,"The textfield is empty")==false) {cfg__game_hosts.focus(); return false;};
		if (valuevalidation(cfg__game_custom_mapid,700,999,"'Custom MapID' MUST be in the range 700-999","I")==false) {cfg__game_custom_mapid.focus(); return false;};
		if (valuevalidation(cfg__game_default_pid,10000000,29000000,"'Default Player ID' MUST be in the range 10000000-29000000","I")==false) {cfg__game_default_pid.focus(); return false;};
		
		// Debug
		if (emptyvalidation(cfg__debug_log,"The textfield is empty")==false) {cfg__debug_log.focus(); return false;};
		if (filevalidation(cfg__debug_log,"The filename entered contains invalid charcters")==false) {cfg__debug_log.focus(); return false;};
		
		// Admin System
		if (emptyvalidation(cfg__admin_user,"The textfield is empty")==false) {cfg__admin_user.focus(); return false;};
		if (emptyvalidation(cfg__admin_pass,"The textfield is empty")==false) {cfg__admin_pass.focus(); return false;};
		if (emptyvalidation(cfg__admin_hosts,"The textfield is empty")==false) {cfg__admin_hosts.focus(); return false;};
		if (emptyvalidation(cfg__admin_backup_path,"The textfield is empty")==false) {cfg__admin_backup_path.focus(); return false;};
		if (pathvalidation(cfg__admin_backup_path,"The path entered contains invalid charcters")==false) {cfg__admin_backup_path.focus(); return false;};
		if (emptyvalidation(cfg__admin_backup_ext,"The textfield is empty")==false) {cfg__admin_backup_ext.focus(); return false;};
		if (extvalidation(cfg__admin_backup_ext,"The extension entered contains invalid charcters")==false) {cfg__admin_backup_ext.focus(); return false;};
		if (cfg__admin_log.value != '') {if (filevalidation(cfg__admin_log,"The filename entered contains invalid charcters")==false) {cfg__admin_log.focus(); return false;};}
		
		
		// Confirm
		if (checkedvalidation(confirm,"You MUST confirm that you wish to continue!")==false) {confirm.focus(); return false;};
	}
}

function confirmvalidation(thisform)
{
	with (thisform)
	{
		if (checkedvalidation(confirm,"You MUST confirm that you wish to continue!")==false) {confirm.focus(); return false;};
	}
}


//************************************
// Row Selector Checkboxes
//************************************
function Toggle(e) {
	if(e.checked) {
		Highlight(e);
		document.adminform.toggleAllC.checked = AllChecked();
	} else {
		UnHighlight(e);
		document.adminform.toggleAllC.checked = false;
	}
}

function ToggleAll(e) {
	if(e.checked) CheckAll();
	else ClearAll();
}

function CheckAll() {
	var ml = document.adminform;
	var len = ml.elements.length;
	for(var i=0; i<len; ++i) {
		var e = ml.elements[i];
		if(e.name == "selitems[]") {
			e.checked = true;
			Highlight(e);
		}
	}
	ml.toggleAllC.checked = true;
}

function ClearAll() {
	var ml = document.adminform;
	var len = ml.elements.length;
	for (var i=0; i<len; ++i) {
		var e = ml.elements[i];
		if(e.name == "selitems[]") {
			e.checked = false;
			UnHighlight(e);
		}
	}
	ml.toggleAllC.checked = false;
}

function AllChecked() {
	ml = document.adminform;
	len = ml.elements.length;
	for(var i=0; i<len; ++i) {
		if(ml.elements[i].name == "selitems[]" && !ml.elements[i].checked) return false;
	}
	return true;
}

function NumChecked() {
	ml = document.adminform;
	len = ml.elements.length;
	num = 0;
	for(var i=0; i<len; ++i) {
		if(ml.elements[i].name == "selitems[]" && ml.elements[i].checked) ++num;
	}
	return num;
}


// Row highlight
function Highlight(e) {
	var r = null;
	if(e.parentNode && e.parentNode.parentNode) {
		r = e.parentNode.parentNode;
	} else if(e.parentElement && e.parentElement.parentElement) {
		r = e.parentElement.parentElement;
	}
	if(r && r.className=="rowdata") {
		r.className = "rowdatasel";
	}
}

function UnHighlight(e) {
	var r = null;
	if(e.parentNode && e.parentNode.parentNode) {
		r = e.parentNode.parentNode;
	} else if (e.parentElement && e.parentElement.parentElement) {
		r = e.parentElement.parentElement;
	}
	if(r && r.className=="rowdatasel") {
		r.className = "rowdata";
	}
}
