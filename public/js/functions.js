$(() => {

    console.log("woza")

    let hasUpvoted = false;
    let hasDownvoted = false;

    //onclick like function
    
        $(".upvote").on("click", function(){
            //get the current count set in the p tag of the div as a integer
            var currentCount = parseInt($(this).siblings(".vote_number").text()); 
            console.log(currentCount);
            //get the id of the mood that was clicked on with the data attr
            var questionId = $(this).parent('.voter').data("id");
            console.log(questionId);

            //perform AJAX
            $.ajax({
                url: "/",
                type: "POST",
                data: { id: questionId, voteType: '1' },
                dataType: "text",
                async: true,

                success: function(data) {
                    console.log(data);
                    //TODO: - update the UI if successful
                },
                error: function(error) {
                }
            });

            //update the UI after ajax done - even though error happened
            if(hasUpvoted == false) {
                $(this).siblings(".vote_number").text(currentCount + 1);
                hasUpvoted = true;
                hasDownvoted = false;
            }
        });

        $(".downvote").on("click", function(){
            //get the current count set in the p tag of the div as a integer
            var currentCount = parseInt($(this).siblings(".vote_number").text()); 
            console.log(currentCount);
            //get the id of the mood that was clicked on with the data attr
            var questionId = $(this).parent('.voter').data("id");
            console.log(questionId);
            //perform AJAX
            $.ajax({
                url: "/",
                type: "POST",
                data: { id: questionId, voteType: '0' },
                dataType: "text",
                async: true,

                success: function(data) {
                    console.log(data);
                    //TODO: - update the UI if successful
                },
                error: function(error) {
                }
            });

            //update the UI after ajax done - even though error happened
            if(hasDownvoted == false) {
                $(this).siblings(".vote_number").text(currentCount - 1);
                hasDownvoted = true;
                hasUpvoted = false;
            }
        });

});