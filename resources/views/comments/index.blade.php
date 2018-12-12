
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="info-msg-error"></div>
            <div class="info-msg-inform"></div>
            <div class="container">
                @foreach ($comments->all() as $comment)
                    @if($comment->user_id == Auth::user()->id)
                        <div class="msg-container">
                            <input class="input-hidden"/>
                            <div class="msg">{{ $comment->message }}</div>
                            <div class="msg-data">date: {{ $comment->created_at }}</div>
                            <div class="div-rating">rating:<span class="span-rating">{{ $comment->ratings->count() }}</span></div>
                            <button class="msg-edit">edit</button>
                            <span class="span-hidden" hidden>{{ $comment->id }}</span>
                            <button class="msg-delete">delete</button>
                            <button class="btn-show-replies">show replies</button>
                            <button class="btn-reply">reply</button>
                            <div class="replies"></div>
                        </div>
                    @else
                        <div class="msg-container">
                            <input class="input-hidden"/>
                            <div class="msg">{{ $comment->message }}</div>
                            <div class="msg-data">date: {{ $comment->created_at }}</div>
                            <div class="div-rating">rating:<span class="span-rating">{{ $comment->ratings->count() }}</span></div>
                            <span class="span-hidden" hidden>{{ $comment->id }}</span>
                            <button class="btn-rating">+1</button>
                            <button class="btn-show-replies">show replies</button>
                            <button class="btn-reply">reply</button>
                            <div class="replies"></div>
                        </div>
                    @endif
                @endforeach

            </div>
            <script type="text/javascript">
                $(document).ready(function(){

                    $('.msg-container').delegate(' button[class="btn-reply"]', 'click', function() {
                        if($(this).siblings('.div-reply').length > 0){
                            $(this).siblings('.div-reply').remove();
                            $(this).text('reply');
                        }
                        else {
                            $('<div class="div-reply"><textarea class="reply"></textarea><button class="btn-send-reply">send</button><div>').insertAfter($(this));
                            $(this).text('cancel');
                        }
                    });


                    $('.msg-container').delegate('button[class="msg-delete"]', 'click', function() {
                        var comment_id = $(this).siblings('.span-hidden').text();
                        if(confirm("Are you sure you want to delete this comment?")) {
                            ajaxHandler('DELETE', '/comment/delete/' + comment_id, null, successDeleteMsg);
                        }
                        else {
                            return false;
                        }
                    });

                    function successDeleteMsg(data) {
                        $('.span-hidden').each(function(){
                            if($(this).text() == data.id){
                                $(this).parent().remove();
                            }
                        });
                        alert('Your comment has been successfully deleted');
                    }

                    $('.msg-container').delegate('button[class="btn-show-replies"]', 'click', function() {
                        var parent_id = $(this).siblings('.span-hidden').text();
                        var parentDiv = $(this).siblings('.replies');
                        var context  = $(this);
                        if(context.siblings('.replies').children().length == 0) {
                            ajaxHandler('GET', '/comment/replies/show/' + parent_id, null, function (params) {
                                if (params.data.length == 0) {
                                    context.text('show replies');
                                    context.siblings('.replies').remove();
                                }
                                else {
                                    parentDiv.empty();
                                    context.text('hide replies');
                                    renderElements(params.data, parentDiv);
                                }
                            });
                        }
                        else {
                            context.text('show replies');
                            context.siblings('.replies').empty();
                        }
                    });

                    function renderElements(elements, parentDiv) {
                        var user_id = {!! Auth::user()->id !!};
                        for(var i in elements){
                            if(elements[i].user_id == user_id) {
                                parentDiv.append('<div class="msg-container"><input class="input-hidden"/><div class="msg">'+ elements[i].message +'</div>'
                                    +'<div class="msg-data">date: '+ elements[i].updated_at +'</div><div class="div-rating">rating:<span class="span-rating">' + elements[i].count +'</span>'
                                    +'</div><button class="msg-edit">edit</button><span class="span-hidden" hidden>'+ elements[i].id +'</span><button class="msg-delete">delete</button>'
                                    +'<button class="btn-show-replies">show replies</button><button class="btn-reply">reply</button><div class="replies"></div></div>');
                            }
                            else{
                                parentDiv.append('<div class="msg-container"><div>'+ elements[i].message +'</div><div class="msg-data">date: '+ elements[i].updated_at +'</div>'
                                    +'<div class="div-rating">rating:<span class="span-rating">'+ elements[i].count + '</span></div><span class="span-hidden" hidden>'+ elements[i].id +'</span>'
                                    +'<button class="btn-rating">+1</button><button class="btn-show-replies">show replies</button><button class="btn-reply">reply</button><div class="replies"></div></div>');
                            }
                        }
                    }
                });
            </script>
        </div>
    </div>
@endsection
