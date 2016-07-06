jQuery.validator.addMethod('validatePhone',function(txtPhoneNumber){
    var a = $("#txtPhoneNumber").val();
	var filter = /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/;
    if (filter.test(a)) {
        return true;
    }
    else {
        return false;
    }
});
jQuery.validator.addMethod('validateNumber',function(txtPhoneNumber){
    var a = txtPhoneNumber;
    var filter = /^[0-9]{1,15}$/;
    if (filter.test(a)) {
        return true;
    }
    else {
        return false;
    }
});
jQuery.validator.addMethod('validName',function($email) {
	  var emailReg = /^[A-Za-z0-9]+[A-Za-z0-9.\' ]*$/gi;
	  return emailReg.test( $email );
});
jQuery.validator.addMethod('userName',function($email) {
	  var emailReg = /^[A-Za-z]+[A-Za-z.\' ]*$/gi;
	  return emailReg.test( $email );
});
jQuery.validator.addMethod('validfield',function($field) {
		var DeliveryDate=$.trim($("#txtDeliveryDate").val());
		var DueDate=$.trim($("#txtDueDate").val());
		console.log(DeliveryDate+"-"+DueDate);
		if(DeliveryDate.length>0 || DueDate.length>0){
			return true;
		}else{
			return false;
		}		
});
jQuery.validator.addMethod('validEmail',function($email) {
	  var emailReg = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i;
	  return emailReg.test( $email );
});

//Check for proper age
jQuery.validator.addMethod('ofAge', function(value, element) {
    var valid = true;
    var age = 18
    
    
    // First we're going to check if all three fields were provided. If not return true and check will fire after they're supplied
    if (value != '') {
    
        // Convert to javascript date - Remember to subtrack 1 from month since javascript month starts at 0
        var JSbirthdate = new Date(value)
        
        // Set current date
        var JScurrdate = new Date()
        
        // Subtract age from current year
        JScurrdate.setFullYear(JScurrdate.getFullYear() - age)
        
        // compare dates and return boolean
        valid = (JScurrdate - JSbirthdate) >= 0
    }

    return valid
})
jQuery.validator.addMethod('validFilesize',function($file) {
	console.log($('#txtProfilePic').val()); 
	if($('#txtProfilePic').val()!=""){
		var file_size = $('#txtProfilePic')[0].files[0].size;
		if(file_size>2097152){
			return false;
		} 
	}
	return true;
});
//check box validation
$.validator.addMethod("checkbox", function (value, element) {
  return $('.checkbox:checked').length > 0;
  }, js_languagevalid);
//check file extension
jQuery.validator.addMethod("extension", function(value, element, param) {
	param = typeof param === "string" ? param.replace(/,/g, '|') : "GIF|gif|png|jpg|jpeg";
	return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
});
jQuery.validator.addMethod('validMobileNumber',function($email) {
	  var emailReg = /^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/;
	  return emailReg.test( $email );
});
jQuery.validator.addMethod('validPassword',function($pass) {
	  var emailReg = /^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])([a-zA-Z0-9]{6})$/;
	  return emailReg.test( $pass );
});
jQuery.validator.addMethod('selectcheck', function (value) {
    return (value != '0' && value != '');
});