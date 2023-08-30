$(document).ready(function(){

  var url=window.location.origin+'/eDiary';

  $('.idLogOut').click(function(){

    $.ajax({
       method:'POST',
       url:url+'/files/login_register.php',
       data:{'adminLogout':'adminLogout'},
      success:function(response){
        var response=$.parseJSON(response);
        if (response.success) 
        {
          if (response.url) 
          {
            setTimeout(function(){
              window.location=response.url;
            },2000);
          }

          $('.alertModelError').html('<br><center><div class="custom-alert alert alert-success" style="background-color:#d4edda;border-color:#c3e6cb;color:#155724;"><a href="#" class="close" data-dismiss="alert">&times;</a>'+response.message+'</div><center>');
        }
      }
    });

  });

  $('.updateBtn').click(function(){
    var appointmentID=$(this).attr('appointmentID');

    $.ajax({
       method:'POST',
       url:url+'/files/appointment.php',
       data:{'appointmentID':appointmentID,'updateAppointment':'updateAppointment'},
      success:function(response){
        var response=$.parseJSON(response);
        if (response.success) 
        {
          $('.appointmentUpdatedData').html(response.message)
        }

        $('select[name="u_remainder"]').change(function(){
          var selectedOption = $(this).val();
          if (selectedOption === 'custom') {
            $('.custom-options').show();
          } else {
            $('.custom-date').val('');
            $('.custom-time').val('');
            $('.custom-options').hide();
          }
        });

      }
    });
  });

  $(".searchAppointment").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $(".allAppointment tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });

  $('select[name="remainder"]').change(function() {
    var selectedOption = $(this).val();
    if (selectedOption === 'custom') {
      $('.custom-options').show();
    } else {
      $('.custom-date').val('');
      $('.custom-time').val('');
      $('.custom-options').hide();
    }
  });


$('.deleteBtnAppointment').click(function(){
    var deleteID=$(this).attr('appointmentID');

    $('.yesDeleteAppointment').click(function(){
       $.ajax({
         method:'POST',
         url:url+'/files/appointment.php',
         data:{'deleteAppointment':'deleteAppointment','deleteID':deleteID},
        success:function(response){
          var response=$.parseJSON(response);
          if (response.success) 
          {
            $('.alertModelError').html('<br><center><div class="custom-alert alert alert-success" style="background-color:#d4edda;border-color:#c3e6cb;color:#155724;"><a href="#" class="close" data-dismiss="alert">&times;</a>'+response.message+'</div><center>');
          }
          else if(response.error)
          {
             $('.alertModelError').html('<br><center><div class="custom-alert alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times; </a> '+response.message+'</div></center>');
          }
        }
    });
  });
});


$('.appointmentStatusComplete').click(function(){

   var appointmentID=$(this).attr('appointmentID');

    $('.yesChangeStatus').click(function(){
    $.ajax({
           method:'POST',
           url:url+'/files/appointment.php',
           data:{'completeAppointment':'completeAppointment','appointmentID':appointmentID},
          success:function(response){
            var response=$.parseJSON(response);
            if (response.success) 
            {
              $('.alertModelError').html('<br><center><div class="custom-alert alert alert-success" style="background-color:#d4edda;border-color:#c3e6cb;color:#155724;"><a href="#" class="close" data-dismiss="alert">&times;</a>'+response.message+'</div><center>');
            }
            else if(response.error)
            {
               $('.alertModelError').html('<br><center><div class="custom-alert alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times; </a> '+response.message+'</div></center>');
            }
          }
      });
  });
});


 $("#appointmentComplete,#appointmentDelete").on('hide.bs.modal', function(){
   window.location.reload();
 });



  $('.adminLink li a').click(function(){
    var link=$(this).attr('href');

    if(link=='#logoutID')
    {
      $("#logoutID").modal('show');
    }
    else
    {
      window.location=url+"/"+link;
    }
  })


function sendReminderEmail() {
  $.ajax({
    method: 'POST',
    url: url + '/files/reminder_email.php',
    success: function(response) {
      // Handle the response if needed

      // Call the function again after a 2-second delay
      setTimeout(sendReminderEmail, 2000);
    },
    error: function(xhr, status, error) {
      // Handle any errors that occur during the AJAX request

      // Call the function again after a 2-second delay
      setTimeout(sendReminderEmail, 2000);
    }
  });
}

// Call the function to start the continuous execution
sendReminderEmail();

	
$('.submitForm').on('submit', function(e) {
  e.preventDefault();
  var form = $(this);
  var formData = form.serialize();

  submitForm(formData, form);
});


function submitForm(formData, form) 
{
  var file1 = form.get(0).image;
  if (file1 === undefined || file1 == null || file1 === '') { 
    $.ajax({
      url: form.attr('action'),
      method: form.attr('method'),
      data: formData,
      success: function(response) {
        show_message(response);
      }
    });
  } 
  else 
  {
    var formData = new FormData(form[0]);
    $.ajax({
      method: form.attr('method'),
      url: form.attr('action'),
      data: formData,
      success: function(response) {
        show_message(response);
      },
      cache: false,
      contentType: false,
      processData: false,
    });
  }    
}

  function show_message(response)
  {
    var response=$.parseJSON(response);

    if (response.success) 
    {
      if (response.signout)
      {
        setTimeout(function(){
          window.location.reload();
        },2000);
      }
      else if(response.url) 
      {
        setTimeout(function(){
          window.location=response.url;
        },2000);
      }
       $('.submitForm')[0].reset();
      $('.alertError').html('<br><center><div class="custom-alert alert alert-success" style="background-color:#d4edda;border-color:#c3e6cb;color:#155724;"><a href="#" class="close" data-dismiss="alert">&times;</a>'+response.message+'</div><center>');
    }
    else if(response.error)
    {
       $('.alertError').html('<br><center><div class="custom-alert alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times; </a> '+response.message+'</div></center>');
    }
  }
});



