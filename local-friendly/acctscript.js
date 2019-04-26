$(document).ready(function(){
  $("#submit").click(function(){
    var username = $("#nname").val();

    var privacySetting = $('input[name="privacy"]:checked').val();

    var formData = {
      //'nname1' : username,
      'privacy' : privacySetting
    };

    var dataString = 'nname1=' + username + '&privacy=' + privacySetting;

    if(username == '' || privacySetting == '')
    {
      alert("Please fill out fields.");
    }
    else
    {
      $.ajax({
        type: "POST",
        url: "insertAcct.php",
        data: {
          data1 : 'value'
        },
        cache: false,
        dataType: 'json',
        encode: true
      })
      .done(function(data){
        console.log(data);
      });
    }
    return false;
  });
});

/*function successFunction(result) {
  if(result.localeCompare("1") == 0) {
    window.location.href="account.php";
  } else {
    alert("Username Taken");
  }*/
    
/*    var form = $('form')[0];
    var formData = new FormData(form);
    $.ajax({
      url: 'insertAcct.php',
      data: formData,
      type: 'POST',
      contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
      processData: false, // NEEDED, DON'T OMIT THIS
      // ... Other options like success and etc
      success: function(result){
        alert(result);
        window.location.href="account.php";
        //successFunction(result);
      }
    });
    return false;
  });
});*/

