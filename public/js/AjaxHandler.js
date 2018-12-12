function ajaxHandler(type, url, data, success, error) {
    $.ajax({
        type: type,
        url: url,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: data,
        success: success,
        error: error
    })
}