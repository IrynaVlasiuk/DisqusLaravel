$(document).ready(function(){

    $('#add-msg').click(function(){
        var message = $('.textarea-message').val();
        var data = {message: message};
        ajaxHandler('POST', '/comment/add', data, successAddComment);
    });

    $('.msg-container').delegate('button[class="msg-edit"]', 'click', function() {
        var input = $(this).siblings('.input-hidden');
        var comment_id = parseInt($(this).siblings('.span-hidden').text());
        var new_comment = input.val();
        if($(this).parent().find( 'input:hidden').length > 0){
            var current_comment = $(this).siblings('.msg').text();
            $(this).siblings('.msg').hide();
            input.show();
            input.val(current_comment);
            $(this).text('save');
        }
        else {
            var data = {comment_id: comment_id , message: new_comment};
            ajaxHandler('POST', '/comment/update/' + comment_id, data, successEdit);
        }
    });

    $('.msg-container').delegate('button[class="btn-rating"]', 'click', function() {
        var comment_id = $(this).siblings('.span-hidden').text();
        var data = {comment_id: comment_id};
        var context = $(this);
        ajaxHandler('POST', '/comment/rating/add', data, function (params) {
            if(params.status == 200) {
                $('.info-msg-error').text('');
                var new_rating = params.data;
                context.siblings('.div-rating').children('.span-rating').text(new_rating);
            }
            else {
                $('.info-msg-error').text(params.message);
            }
        });
    });


    $('.msg-container').delegate('.div-reply button[class="btn-send-reply"]', 'click', function() {
        var parent_id = $(this).parent().siblings('.span-hidden').text();
        var message = $(this).siblings('.reply').val();
        var data = {message: message, parent_id: parent_id};
        ajaxHandler('POST', '/comment/add', data, successAddReply);
    });

    function successAddComment(data) {
        $('.info-msg-error').text('');
        if(data.status == 400){
            $('.info-msg-error').text(data.message.message[0]);
        }
        else {
            window.location.href = "/home";
        }
    }

    function successEdit(data) {
        if(data.status == 200){
            var id = data.data.id;
            $('.span-hidden').each(function(){
                if($(this).text() == id){
                    $(this).siblings('.input-hidden').hide();
                    $(this).siblings('.msg').show().text(data.data.message);
                    $(this).siblings('.msg-edit').text('Edit');
                    showMessages(data.message, false);
                }
            });
        }
        else {
            showMessages(data.message, true);
        }
    }

    function showMessages(string, isError) {
        $('.info-msg-error').text('');
        $('.info-msg-inform').text('');
        if(isError){
            $('.info-msg-error').text(string);
        } else {
            $('.info-msg-inform').text(string);
        }
    }

    function successAddReply(data) {
        if(data.status == 200) {
            $('.div-reply').siblings('.btn-reply').text('reply');
            $('.div-reply').remove();
            showMessages(data.message, false);
        }
        else{
            showMessages(data.message.message[0], true);
        }
    }
});