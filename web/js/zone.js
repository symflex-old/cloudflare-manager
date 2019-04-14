$(function(){

    $.ajaxSetup({
        beforeSend: function(xhr, settings) {
            $('footer #before-load').show();
            console.log(123);
        },
        complete: function () {
            $('footer #before-load').fadeOut();
        }
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
            for (let record of result) {
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

            $('#ipModal #rows tbody').html(rows);
            $('#ipModal').modal()
            $('[data-dns-action="status"]').bootstrapToggle();
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