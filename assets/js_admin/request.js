//function to check dupliucate for registration
function checkduplicate(siteurladm, msgName, action)
{
	var GrNo = $("#txtGrNo").val();
	var id = $("#hdnId").val();
	$.ajax
	({
		type: "POST",
		url: siteurladm+"student/checkduplicate",
		data: {GrNo:GrNo, action:action, id:id},
		cache: false
	}).done(function(data)
		{
			if(data == '1')
			{
				$("#errorInsertion").html('<div class="alert"><button data-dismiss="alert" class="close">×</button>' +msgName+ ' already existed!</div>');
				return false;
			}
			else
			{
				if($("#frmRegistration").validate().form())
				{
					window.frmRegistration.submit();
				}
			}
		});
}


//function to check dupliucate for standard
function checkduplicatestandard(siteurladm, msgName, action)
{
	//siteurladm+
	var standard = $("#txtStandard").val();
	var id = $("#hdnId").val();
	$.ajax
	({
		type: "POST",
		url: siteurladm+"student/checkduplicate",
		data: {standard:standard, action:action, id:id},
		cache: false
	}).done(function(data)
		{
			if(data == 1)
			{
				$("#errorInsertion").html('<div class="alert"><button data-dismiss="alert" class="close">×</button>' +msgName+ ' already existed!</div>');
			}
			else
			{
				if($("#frmStandard").validate().form())
				{
					window.frmStandard.submit();
				}
			}
		});
		return false;	
}


//sort student list by standard selection
function sortByStandard(siteurladm)
{
	var standard = $("#selStandard").val();
	$.ajax
	({
		type: "POST",
		url: siteurladm+"student/sortByStandard",
		data: {standard:standard},
		cache: false
	}).done(function(data)
		{
			$("#standardSort").show();
			$("#studentDetails").html(data);
		});
}
