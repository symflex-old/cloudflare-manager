$(function() {
    $('#create').on('click', function (e) {
        e.preventDefault();

        let template = '<tr>' +
            '        <td>' +
            '        </td>' +
            '        <td>' +
            '            <input class="form-control input-sm" name="email">' +
            '        </td>' +
            '        <td>' +
            '            <input class="form-control input-sm" name="api_key">' +
            '        </td>' +
            '        <td class="act">' +
            '            <button class="btn btn-danger btn-sm" id="delete" data-action="delete"><i class="glyphicon glyphicon-trash"></i> </button>' +
            '            <button class="btn btn-success btn-sm" id="save" data-action="save"><i class="glyphicon glyphicon-floppy-saved"></i> </button>' +
            '        </td>' +
            '    </tr>';
        $('table tbody').prepend(template);

    });

    $('table').on('click', '[data-action="delete"]', function (e) {
        e.preventDefault();

        let id = $(this).data('id');

        $.ajax({
            'url': '/accounts/delete',
            'method': 'post',
            'dataType': 'json',
            'data': {id:id},
        }).done(function (e) {
            if (e.error) {
                $('.alert-danger').fadeIn();
            } else {
                $('.alert-success').fadeIn();
            }

        });

    })

    $('table').on('click', '[data-action="save"]', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        let parent = $(this).parent().parent();

        let email = $(parent).find('[name="email"]').val();
        let apiKey = $(parent).find('[name="api_key"]').val();

        let action = 'create';

        if (id) {
            action = 'update?id=' + id;
        }

        $('.alert-danger').hide();
        $('.alert-success').hide();
        $.ajax({
            'url': '/accounts/' + action,
            'method': 'post',
            'dataType': 'json',
            'data': {Account:{email:email, api_key: apiKey}},
        }).done(function (e) {

            if (e.error) {
                $('.alert-danger').fadeIn();
            } else {
                $('.alert-success').fadeIn();
            }

        });

    });


});