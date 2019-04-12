let alert = ' <div class="alert alert-success alert-dismissible" role="alert">\n' +
    '            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>\n' +
    '            Оперциая успешно выполнена\n' +
    '            </div>'

$(function() {

    $('[data-action^="delete"]').on('click', function (e) {
        let id = $(this).data('id');
        let account = $(this).data('account');
        let row = $(this).parent().parent();

        $.ajax({
            'url': '/domains/api',
            'method': 'post',
            'dataType': 'json',
            'data': {action:'delete', account: account, id: id}
        }).done(function (e) {
            $('#alert').html(alert)
            $(row).remove();
            $('.alert').fadeIn();
        });

    })



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


    $('#rows').on('change', '[data-record-id]', function (e) {
        let key = $(this).data('record-key');

        let name = '';
        let type = '';
        let content = '';
        let status = $(this).prop('checked');
        let id = $(this).data('record-id');
        let account = $(this).data('account');
        let zone = $(this).data('zone');

        switch (key) {
            case 'name':
                name = $(this).val()
                type = $(this).data('record-type');
                content = $(this).data('record-content');
                break;
            case 'content':
                name = $(this).data('record-name');
                type = $(this).data('record-type');
                content = $(this).val();
                break;
            case 'status':
                name = $(this).data('record-name');
                type = $(this).data('record-type');
                content = $(this).data('record-content');
                break;
        }

        let data = {
            'action': 'update-dns',
            'account': account,
            'zone': zone,
            'type': type,
            'id' : id,
            'name': name,
            'content': content,
            'status': status
        };

        $.ajax({
            'url': '/domains/api',
            'method': 'post',
            'dataType': 'json',
            'data': data
        }).done(function (e) {


            //$('.alert').fadeIn();
        });

    });

    $('#rows').on('click', '[data-action]', function (e) {
        let row = $(this).parent().parent();

        let id = $(this).data('id');
        let zone = $(this).data('zone');
        let account = $(this).data('account');

        let data = {
            'action': 'delete-dns',
            'id': id,
            'zone': zone,
            'account': account

        };

        $.ajax({
            'url': '/domains/api',
            'method': 'post',
            'dataType': 'json',
            'data': data
        }).done(function (e) {
            $(row).remove();
        });


    })


    $('td.ip').on('click', function(e) {

        let data = $(this).find('.json').html();
        let json = JSON.parse(data)

        let rows = '';
        let account = $(this).data('account');
        let zone = $(this).data('id');

        for (let row of json) {
            let checked = row.proxied === true ? 'checked' : '';

            rows = rows + '<tr>' +
                '<td>'+row.type+'</td>' +
                '<td><input data-zone="'+zone+'" data-account="'+account+'" data-record-id="'+row.id+'" data-record-type="'+row.type+'" data-record-name="'+row.name+'" data-record-content="'+row.content+'" data-record-status="'+row.proxied+'" data-record-key="name" type="text" value="'+row.name+'" class="form-control input-sm"></td>' +
                '<td><input data-zone="'+zone+'" data-account="'+account+'" data-record-id="'+row.id+'" data-record-type="'+row.type+'" data-record-name="'+row.name+'" data-record-content="'+row.content+'" data-record-status="'+row.proxied+'" data-record-key="content" type="text" value="'+row.content+'" class="form-control input-sm"></td>' +
                '<td>'+row.ttl+'</td>' +
                '<td><input data-zone="'+zone+'" data-account="'+account+'" data-record-id="'+row.id+'" data-record-type="'+row.type+'" data-record-name="'+row.name+'" data-record-content="'+row.content+'" data-record-status="'+row.proxied+'" data-record-key="status" type="checkbox" data-toggle="toggle" data-size="small" '+checked+'></td>' +
                '<td><div class="btn btn-danger btn-sm" data-action="delete" data-zone="'+zone+'" data-id="'+row.id+'" data-account="'+account+'"><i class="glyphicon glyphicon-remove"></i> </div> </td>'
                '</tr>';
        }

        $('#ipModal #rows tbody').html(rows);


        $('#ipModal').modal()
        $('[data-record-key="status"]').bootstrapToggle();
        /*


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
        $('#ipModal').modal()*/
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