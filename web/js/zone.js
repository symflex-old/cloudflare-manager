$(function(){

    $(function () {

        let el = $('[name="per-page"]').clone();
        $('[name="per-page"]').remove();
        $('#perpage').html(el);
    })



    const isValidIp = value => (/^(?:(?:^|\.)(?:2(?:5[0-5]|[0-4]\d)|1?\d?\d)){4}$/.test(value) ? true : false);
    $.ajaxSetup({
        beforeSend: function(xhr, settings) {
            $('footer #before-load').show();
            console.log(123);
        },
        complete: function () {
            $('footer #before-load').fadeOut();
        }
    });

    $('#ipModal').on('click', '#add-record', function (e) {
        let type = $('[data-insert="type"]').val();
        let name = $('[data-insert="name"]').val();
        let value = $('[data-insert="content"]').val();
        let _ttl = $('[data-insert="ttl"]').val();
        let status = $('[data-insert="status"]').prop('checked');

        let id = $('#ipModal').data('zone');

        if (isValidIp(value) == false) {
            return;
        }

        let data = {
            'id': id,
            'type': type,
            'name': name,
            'value': value,
            'ttl': _ttl,
            'status': status
        };

        $.ajax({
            'url': '/api/insert-dns',
            'method': 'post',
            'dataType': 'json',
            'data': data
        }).done(function (e) {
            let _id = e.id;

            let checked = status == true ? 'checked': '';
            let row = '<tr data-id="'+_id+'">' +
                '<td>'+type+'</td>' +
                '<td><input data-dns-action="name" type="text" value="'+name+'" class="form-control input-sm"></td>' +
                '<td><input data-dns-action="value" type="text" value="'+value+'" class="form-control input-sm"></td>' +
                '<td>'+ttl[_ttl]+'</td>' +
                '<td><input data-dns-action="status" type="checkbox" data-toggle="toggle" data-size="small" '+checked+'></td>' +
                '<td><div class="btn btn-danger btn-sm" data-dns-action="delete"><i class="glyphicon glyphicon-remove"></i> </div> </td>' +
                '</tr>';


            $('#ipModal #rows tbody').prepend(row);
            $('[data-dns-action="status"]').bootstrapToggle();
        });


    });


    $('[data-zone-dns]').on('click', function (e) {
        let id = $(this).data('zone-dns');
        let data = {
            'id': id
        };
        $.ajax({
            url: '/api/getdns',
            method: 'post',
            dataType: 'json',
            data: data
        }).done(function (result) {
            let rows = '';
            for (let record of result.records) {
                let checked = record.status === 1 ? 'checked' : '';
                rows = rows + '<tr data-id="'+record.id+'">' +
                    '<td>'+record.type+'</td>' +
                    '<td><input data-dns-action="name" type="text" value="'+record.name+'" class="form-control input-sm"></td>' +
                    '<td><input data-dns-action="value" type="text" value="'+record.value+'" class="form-control input-sm"></td>' +
                    '<td>'+ttl[record.ttl]+'</td>' +
                    '<td><input data-dns-action="status" type="checkbox" data-toggle="toggle" data-size="small" '+checked+'></td>' +
                    '<td><div class="btn btn-danger btn-sm" data-dns-action="delete"><i class="glyphicon glyphicon-remove"></i> </div> </td>'
                '</tr>';
            }
            $('#ipModal').attr('data-zone', result.zone);
            $('#ipModal #rows tbody').html(rows);
            $('#ipModal').modal()
            $('[data-dns-action="status"]').bootstrapToggle();
        });
    });

    $('#ipModal').on('change', '[data-dns-action]', function (e) {

        let key = $(this).data('dns-action');
        let value;
        let id;

        if (key == 'status') {
            id = $(this).parent().parent().parent().data('id');
            value = $(this).prop('checked');
        } else {
            id = $(this).parent().parent().data('id');
            value = $(this).val();
        }

        let data = {
            id: id,
            key:key,
            value:value
        };

        if (key == 'value' && !isValidIp(value)) {
            return;
        }

        $.ajax({
            url: '/api/update-dns',
            method: 'post',
            dataType: 'json',
            data:data
        }).done(function () {

        });

    });

    $('#ipModal').on('click', '[data-dns-action="delete"]', function () {
        let parent = $(this).parent().parent();
        let id = $(parent).data('id');

        let data = {
            'id': id
        };

        $.ajax({
            url: '/api/delete-dns',
            method: 'post',
            dataType: 'json',
            data:data
        }).done(function () {
            $(parent).remove();
        });
    });


    $('[data-sync]').on('click', function (e) {
        $.ajax({
            url: '/api/sync',
            method: 'post',
            dataType: 'json',
        }).done(function (result) {
            location.reload();
        });
    })
    
    $('[data-action]').on('click', function (e) {
        e.preventDefault();

        let id = $(this).data('id');
        let action = $(this).data('action');

        let data = {
            'id': id
        };

        $.ajax({
            url: '/api/' + action,
            method: 'post',
            dataType: 'json',
            data: data
        }).done(function (result) {
            location.reload();
        });

    })


    $('[data-action]').on('change', function (e) {

        let tag = $(this).prop('tagName');

        let id = $(this).data('id');
        let action = $(this).data('action');
        let value = '';

        if (tag == 'SELECT') {
            value = $(this).val();
        } else {
            value = $(this).prop('checked');
        }

        let data = {
            'id': id,
            'value': value
        };

        $.ajax({
            url: '/api/' + action,
            method: 'post',
            dataType: 'json',
            data: data
        }).done(function (result) {
            console.log(result);
        });
    })
});