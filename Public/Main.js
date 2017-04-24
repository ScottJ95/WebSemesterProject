function start() {
	//ADD USERNAME HERE
	//document.getElementById("userName").innerHTML="";
                
	Groups('0');
}



function Groups(x){
document.getElementById("groupList").innerHTML="";
	$.ajax({
                   type: 'POST',
                   url:  'DBFuncs.php',
                   data: { functionName:'getGroups', argument:x},

                   success: function (response) {
                        var groupData = JSON.parse(response);

                        for(i = 0;i<groupData.length;i++)
                        {
                                document.getElementById("groupList").innerHTML+="<Button class=\"groupButton\" id=\"group\">"+groupData[i].group_name+" Size: "+groupData[i].group_numUsers+" Description: "+ groupData[i].group_description+"</button>";
                        }
                  }

                });
}

function groupButton(){
	//Send to Group page
	//window.location.href = "newpassword.html";
}
