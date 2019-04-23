$(document).ready(function(){
  $("#submit").click(function(){
    var username = $("#nname").val();

    var privacy = $(".privacy:checked").val();
    var privacySetting = 0;
    if(privacy == 'pub'){
      privacySetting = 0;
    } else {
      privacySetting = 1;
    }

    var dataString = 'nname1=' + username + 'privacy=' + privacySetting;

    if(username == '')
    {
      alert("Please fill out fields.");
    }
    else
    {
      $.ajax({
        type: "POST",
        url: "insertAcct.php",
        data: dataString,
        cache: false,
        success: function(result){
	  if(result.includes("Success"))
	  {
	    window.location.href="account.php";
          } else {
	    alert(result);
	  }
	}
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

