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
function clearText(inputid){
	if ($("#"+inputid).val().length !=0) {
	            $("."+inputid).show();
	        } else {
	            $("."+inputid).hide();
	        }
}
function hidesearch(id) {
    $("#"+id).hide();
    $(".search-input").val("");
}
function changeLanguage(){
	$("#frmchangeLanguage").submit();
}
$("#txtProfilePic").on('change',function () {
	if(this.files.length > 0)
	{
		var name = this.files[0].name;
		var emailReg = new RegExp(/(GIF|gif|png|jpg|jpeg)$/g);
		var valid = emailReg.test(name);
		
		if(!valid)
		{
			return false;
		}
		$(this).parent('.fileUpload').next('label').text('[ '+this.files[0].name+' ]');
		//alert(name.match(/(gif|png|jpg|jpeg)$/));	
		 var reader = new FileReader();
		 var changeImg = $(this);
		  
	     reader.onload = function (e) {
	    	 changeImg.parent('div').find('img:first').attr('src', e.target.result);
	    	 $("#profileImg").find("img").attr('src',e.target.result);
	     }
	     reader.readAsDataURL(this.files[0]);
	 }
});
function filladress(id,zipcode){
$.ajax({
	url : siteurl+'admin/beneficiary/getAddressById',
    type: "POST",
    dataType: 'json',
    data:{'_token':token,'id':id,'zipcode':zipcode},
     }).done(function (result) {
    	 	$("#txtZipcode").focus();
 			$("#txtZipcode").blur();
    	 	$("#txtTaluka").val("");
    	 	$("#hdnZipcode").val("");
			$("#txtDistrict").val("");
			$("#txtState").val("");
			$("#txtCountry").val("");
			if(result!=null){				
			$("#hdnZipcode").val(result.bi_id);	
			$("#txtTaluka").val(result.v_taluka);
	 		$("#txtDistrict").val(result.v_district);
	 		$("#txtState").val(result.v_state);
	 		$("#txtCountry").val(result.v_country);
 		}
	     }).fail(function () {
     	//alert('Posts could not be loaded.');
     });
}
function fillvillage(zipcode){
	var str="";
	$.ajax({
		url : siteurl+'admin/beneficiary/getVillageByZipcode',
	    type: "POST",
	    dataType: 'json',
	    data:{'_token':token,'zipcode':zipcode},
	     }).done(function (result) {
	    	 str+="<option value=''>Select Village</option>";
	    	 if(!jQuery.isEmptyObject(result)){
	    		 $.each( result, function( index, value ){
	    			 str+="<option value="+value.bi_id+">"+value.v_village+" ("+value.v_village_pincode+")</option>";
	    		 });
	    		 $("#txtAddress").html(str);
	    	 }else{
	    		 $("#txtAddress").html("<option value=''>Select Village</option>");
	    	 }
	         }).fail(function () {
	     	//alert('Posts could not be loaded.');
	     });
}
$(document).on('keyup',"#txtZipcode",function(e) {
	if(e.keyCode!=37 && e.keyCode!=38 && e.keyCode!=39 && e.keyCode!=40 && e.keyCode!=13){
		$("#hdnZipcode").val('');
	}	
});
function setzipid(id){
	console.log(id);
	if(id!=""){
		$("#hdnZipcode").val(id);
	}
}
function myDateFormatter (dt) {
    var d = new Date(dt);
    var day = d.getDate();
    var month = d.getMonth() + 1;
    var year = d.getFullYear();
    if (day < 10) {
        day = "0" + day;
    }
    if (month < 10) {
        month = "0" + month;
    }
    var date = day + "/" + month + "/" + year;

    return date;
}
function formatDate (input) {
	  var datePart = input.match(/\d+/g),
	  year = datePart[0], // get only two digits
	  month = datePart[1], day = datePart[2];
//	  console.log(datePart[0]);	
	  return day+'/'+month+'/'+year;
	} 