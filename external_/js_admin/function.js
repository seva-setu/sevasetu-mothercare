// JavaScript Document
function singleCheckDel(){
	if(confirm("Are you sure you want to delete?")){
		return true;
	}else{
		return false;
	}
}
//check approv
function customMsg(msg){
	if(confirm("Are you sure you want to "+msg+"?")){
		return true;
	}else{
		return false;
	}
}
var checked = false;
//function for check all
function checkedAll(formName) {
	if (checked == false){checked = true}else{checked = false}
	 for (var i = 0; i < document.getElementById(formName).elements.length; i++) {
			document.getElementById(formName).elements[i].checked = checked;
	}
}
//check delete
function checkDelete(msg){
	var y=0; var ans;
	y = getCheckCount();
	var actionvalue='Delete';
	if(y>0)
	{	ans = confirm("Are you sure you want to delete?");
		if(ans == true)
		{	
			//document.frmList.mode.value=actionvalue;
		    document.frmList.submit();
		}
		else
		{return false;}
	}
	else
	{	alert("No record selected");	return false;	}
}
function getCheckCount(){	
	var x=0;
	for(i=0;i <document.frmList.elements.length;i++){	
		if (document.frmList.elements[i].checked == true){
			var formid=document.frmList.elements[i].id.trim();
			if(formid!='title-table-checkbox' && formid!="")
				x++;
		}
	}
	return x;
}
function isNumberKey(evt){
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57)){
            return false;
		 }
         return true;
}
//redirect page
function redirectPage(pageName){
	window.location.href=pageName;
}
//check both password
function checkPassword(){
	var newPassword = $("#txtNewPassword").val();
	var confirmPass  = $("#txtConfirmPass").val();
	if(newPassword != confirmPass){
		alert("Password does not match");
		$("#txtConfirmPass").val("");
		$("#txtConfirmPass").focus();
		return false;
	}
}
//check user check or not
function checkMail(msg){
	var y=0;
	y = getCheckUser();
	if(y==0){
		alert("Please Select at least one Category!");	return false;		
	}	
	else{
		return true;
	}
}
//close lightbox master function
function masterCloseLight(className){
	$('.backdrop, .'+className+'').animate({'opacity':'0'}, 300, 'linear', function(){
		$('.backdrop, .'+className+'').css('display', 'none');
	});
}
//master lightbox
function openLightBox(className){
	$("#msgPress").html("");
	$('.backdrop, .'+className+'').animate({'opacity':'.50'}, 300, 'linear');
	$('.'+className+'').animate({'opacity':'1.00'}, 300, 'linear');
	$('.backdrop, .'+className+'').css('display', 'block');
}
//clear textbox
function removeText(text,id,value){
	if(text==value){
		$("#"+id+"").val("");
	}
}
//fill texbox
function addText(text,id,value){
	if(text==value || value==""){
		$("#"+id+"").val(text);
	}
}
//multiple mobile number insert in business
function addValue(objBtn){
	var indx = $("#insertMore").find("div").length;
	var row = '<label class="control-label" for="phoneNo">Phone No</label><div class="controls"><input type="text" name="txtPhoneNo[]" id="txtPhoneNo" ></div>';
	row += '</div><br/>';	
	$('#insertMore').append(row);
}
// multiple phone value insert in business
function addphonevalue(objph){
	var indx = $("#insertmoreMob").find("div").length;
	var row = '<label class="control-label" for="phoneNo">Mobile No</label><div class="controls"><input type="text" name="txtMobileno[]" id="txtMobileno" ></div>';
	row += '</div><br/>';	
	$('#insertmoreMob').append(row);
}
// multiple image upload in business
function addupload(objup){
	var indx = $("#insertuploadvalue").find("div").length;
	var row = '<label class="control-label" for="uploadimage">Upload Image</label><div class="controls"><input type="file" name="fileImage[]" id="fileImage"/></div>';
	row += '</div><br/>';	
	$('#insertuploadvalue').append(row);
}

// End of close peticularday in business
function openLightBox(className){
	$('.backdrop, .'+className+'').animate({'opacity':'.50'}, 300, 'linear');
	$('.'+className+'').animate({'opacity':'1.00'}, 300, 'linear');
	$('.backdrop, .'+className+'').css('display', 'block');
}
function masterCloseLight(className){
	$("#txtClientName").css({"border":"1px solid silver"});
	$('.backdrop, .'+className+'').animate({'opacity':'0'}, 300, 'linear', function(){
		$('.backdrop, .'+className+'').css('display', 'none');
	});
}



//sloat_machine js start here
function checkAll(id){
	if($('#chkOpertions'+id).is(':checked')) 
	{		
		$("input[id^='chk"+id+"']").prop('checked', true).next().val('1');
    }
	else
	{
		$("input[id^='chk"+id+"']").prop('checked', false).next().val('0');
	}
}

function chkHidden(chkObj)
{
	if($(chkObj).is(':checked')) 
	{
		$(chkObj).next().val('1');	
	}
	else
	{
		$(chkObj).next().val('0');			
	}
}

//multiple image upload
function addValue(objBtn){
	var indx = $("#uploadMultipleImages").find("div").length;
	var row = '<label class="control-label">Upload Image : </label><div class="controls"> <input type="file"  id="fileEventsImage" name="fileEventsImage[]" /></div>';
	row += '</div><br/>';	
	$('#uploadMultipleImages').append(row);

}
