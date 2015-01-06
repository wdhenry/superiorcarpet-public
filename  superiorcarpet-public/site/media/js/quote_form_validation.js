$(document).ready(function() {
    $("#submit_btn").click(function() { 
        //get input field values
        var user_name       = $('input[name=quotename]').val(); 
        var user_email      = $('input[name=quoteemail]').val();
        var user_phone      = $('input[name=quotephone]').val();
        var user_message    = $('textarea[name=quotemessage]').val();
		var user_size    = $('input[name=quotesize]').val();
		var user_rooms    = $('input[name=quoterooms]').val();
        
        //simple validation at client's end
        //we simply change border color to red if empty field using .css()
        var proceed = true;
        if(user_name==""){ 
            $('input[name=quotename]').css('border-color','red'); 
            proceed = false;
        }
        if(user_email==""){ 
            $('input[name=quoteemail]').css('border-color','red'); 
            proceed = false;
        }
        if(user_phone=="") {    
            $('input[name=quotephone]').css('border-color','red'); 
            proceed = false;
        }
        if(user_message=="") {  
            $('textarea[name=quotemessage]').css('border-color','red'); 
            proceed = false;
        }
		if(user_rooms=="") {  
            $('input[name=quoterooms]').css('border-color','red'); 
            proceed = false;
        }
		/*if(user_size=="") {  
            $('input[name=quotesize]').css('border-color','red'); 
            proceed = false;
        }*/

        //everything looks good! proceed...
        if(proceed) 
        {
            //data to be sent to server
            post_data = {'userName':user_name, 'userEmail':user_email, 'userPhone':user_phone, 'userMessage':user_message, 'userSize':user_size, 'userRooms':user_rooms};
            
            //Ajax post data to server
            $.post('http://www.superiorcarpetandupholsterycare.com/php/_lib/quote_email.php', post_data, function(response){  
                
                //load json data from server and output message     
                if(response.type == 'error')
                {
                    output = '<div class="error">'+response.text+'</div>';
                }else{
                
                    output = '<div class="success">'+response.text+'</div>';
                    
                    //reset values in all input fields
                    $('#quote_form input').val(''); 
                    $('#quote_form textarea').val(''); 
                }
                
                $("#result").hide().html(output).slideDown();
            }, 'json');
            
        } else {
			output = '<div class="error">Please fill out the red text boxes.  Filling out this information will help us better serve you!</div>';
			$("#result").hide().html(output).slideDown();
		}
    });
    
    //reset previously set border colors and hide all message on .keyup()
    $("#quote_form input, #quote_form textarea").keyup(function() { 
        $("#quote_form input, #quote_form textarea").css('border-color',''); 
        $("#result").slideUp();
    });
    
});