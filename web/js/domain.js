let alert = ' <div class="alert alert-success alert-dismissible" role="alert">\n' +
    '            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>\n' +
    '            Оперциая успешно выполнена\n' +
    '            </div>'

$(function() {
    $('[data-action^="rewrite"]').on('change', function(e) {
        let id = $(this).data('id');
        let value = $(this).prop('checked');
        let account = $(this).data('account');
        $.ajax({
            'url': '/domains/api',
            'method': 'post',
            'dataType': 'json',
            'data': {action:'rewrite', account: account, id: id, value:value}
        }).done(function (e) {
            $('#alert').html(alert)
            $('.alert').fadeIn();
        });


    })

    $('[data-action^="dev"]').on('change', function(e) {
        let id = $(this).data('id');
        let value = $(this).prop('checked');
        let account = $(this).data('account');
        $.ajax({
            'url': '/domains/api',
            'method': 'post',
            'dataType': 'json',
            'data': {action:'dev', account: account, id: id, value:value}
        }).done(function (e) {
            $('#alert').html(alert)
            $('.alert').fadeIn();
        });
    })


    $('[data-action^="ssl"]').on('change', function(e) {
        let id = $(this).data('id');
        let value = $(this).prop('checked');
        let account = $(this).data('account');
        $.ajax({
            'url': '/domains/api',
            'method': 'post',
            'dataType': 'json',
            'data': {action:'ssl', account: account, id: id, value:value}
        }).done(function (e) {
            $('#alert').html(alert)
            $('.alert').fadeIn();
        });
    })

    $('[data-action^="tls"]').on('change', function(e) {
        let id = $(this).data('id');
        let account = $(this).data('account');
        let value = $(this).val();
        $.ajax({
            'url': '/domains/api',
            'method': 'post',
            'dataType': 'json',
            'data': {action:'tls', account: account, id: id, value:value}
        }).done(function (e) {
            $('#alert').html(alert)
            $('.alert').fadeIn();
        });
    })

    $('[data-action^="purge"]').on('click', function(e) {
        let id = $(this).data('id');
        let value = $(this).val();
        let account = $(this).data('account');
        $.ajax({
            'url': '/domains/api',
            'method': 'post',
            'dataType': 'json',
            'data': {action:'purge', account: account, id: id, value:value}
        }).done(function (e) {
            $('#alert').html(alert)
            $('.alert').fadeIn();
        });
    })

    $('[data-action^="sec_level"]').on('change', function(e) {
        let id = $(this).data('id');
        let value = $(this).prop('checked');
        let account = $(this).data('account');
        $.ajax({
            'url': '/domains/api',
            'method': 'post',
            'dataType': 'json',
            'data': {action:'security_level', account: account, id: id, value:value}
        }).done(function (e) {
            $('#alert').html(alert)
            $('.alert').fadeIn();
        });
    })


    $('td.ip').on('click', function(e) {
        let ip = $(this).html();
        let zone = $(this).data('id');
        let account = $(this).data('account');
        let record = $(this).data('record');
        let domain = $(this).data('domain');

        let input = $('[name="ip"]');

        $(input).val(ip);
        $(input).attr('data-id', zone);
        $(input).attr('data-account', account);
        $(input).attr('data-record', record);
        $(input).attr('data-domain', domain);
        $('#ipModal').modal()
    });

    const isValidIp = value => (/^(?:(?:^|\.)(?:2(?:5[0-5]|[0-4]\d)|1?\d?\d)){4}$/.test(value) ? true : false);

    $('#saveIp').on('click', function (e) {
        let input = $('.modal').find('[name="ip"]');
        let ip = $(input).val();
        let id = $(input).data('id');
        let account = $(input).data('account');
        let record = $(input).data('record');
        let domain = $(input).data('domain');

        if (isValidIp(ip) == false) {
            $('#error-ip').fadeIn()
            return;
        }
        $('#error-ip').hide()
        $.ajax({
            'url': '/domains/api',
            'method': 'post',
            'dataType': 'json',
            'data': {action:'recordA', account: account, id: id, value:ip, record: record, domain:domain}
        }).done(function (e) {
            $('.close').trigger("click");
            location.reload();
            //$('.alert').fadeIn();
        });

    })

/*
    $('[data-action^="ip]').on('click', function(e) {
        let id = $(this).data('id');
        let value = $(this).val();
        let account = $(this).data('account');
        let record_id = $(this).data('record');
        $.ajax({
            'url': '/domain/api',
            'method': 'post',
            'dataType': 'json',
            'data': {action:'recordA', account: account, id: id, value:value, record: record_id}
        }).done(function (e) {
            $('#alert').html(alert)
            $('.alert').fadeIn();
        });

    })*/

});