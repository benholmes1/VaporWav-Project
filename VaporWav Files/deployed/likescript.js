$(document).ready(function(){

    // like click
    $(".like").click(function(){
        var id = this.id;   // Getting Button id
        var split_id = id.split("_");

        var text = split_id[0];
        var keyname = split_id[1];  // keyname

        // AJAX Request
        $.ajax({
            url: 'like.php',
            type: 'post',
            data: {userid:userid,keyname:keyname},
            dataType: 'json',
            success: function(data){
                var likes = data['likes'];

                $("#likes_"+postid).text(likes);        // setting likes
            
            }
        });

    });

});