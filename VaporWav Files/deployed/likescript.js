$(document).ready(function(){

    // like click
    $(".like").click(function(){
        var id = this.id;   // Getting Button id
        var split_id = id.split("_");

        var type = split_id[0];
        var keyname = id.split(/_(.+)/)[1];
        
        var data = {
            key: keyname,
        };

        // AJAX Request
        $.ajax({
            url: 'like.php',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function(data){
                var likes = data['likes'];

                $("#likes_"+keyname).text(likes);        // setting likes
            
            },
            error: function() {
                alert('Error');
            }
        });

    });

});