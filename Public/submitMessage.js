function submitMessage(){
	var message	= $('inputMessage').val();//get messageBody from the textArea

	if(message) {
		$.ajax({
				type: 	'post',
				url: 	'Chats.php',
				data:	{functionname: 'submitNewMessage',argument:x;},
				        
						success: function (response) {}

                });
	}
}
