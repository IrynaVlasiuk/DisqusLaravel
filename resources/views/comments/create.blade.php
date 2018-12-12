@extends('layouts.app')

@section('content')
    <div class='container'>
        <div class="info-msg-error"></div>
        <div class='title'>Please enter your comment</div><br>
        <form method='post' name="add-message" id="add-message" action="" autocomplete="off">
            <div class='row justify-content-center'>
                <div class='col-md-10 offset-md-4'>
                    <textarea name='message' class="textarea-message"></textarea>
                </div>
                <div class='col-md-6 offset-md-4'>
                    <button type="button" class='btn-add-comment' id="add-msg">submit</button>
                </div>
            </div>
        </form>
    </div>
@endsection