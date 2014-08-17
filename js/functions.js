$(document).ready(function(){
    $('#todo').on('click', function(){
        var todo = $(this).attr('todo');

        switch(todo) {
            case 'page':
                start_progress('Menghitung halaman yang harus ditelusuri...');
                get_page();
                break;
            case 'auctions':
                var limit = $('#limit').val();
                start_progress('Mencari lelang yang harus diperiksa...');
                get_auctions(limit);
                break;
            case 'winners':
                var ids = $('input[name="id[]"]:checkbox:checked').map(function(){
                    return $(this).val();
                }).get();

                if (ids == '') {
                    alert('Pilih minimal satu lelang untuk diperiksa.');
                    return false;
                }

                start_progress('Memeriksa lelang terpilih...');
                get_winners(ids);
                break;
        }
    });
});

function get_page()
{
    $.ajax({
        url: base_url,
        data: {'do': 'page'},
        type: 'post',
        dataType: 'json'
    })
    .error(function(){
        show_error();
    })
    .success(function(response){
        if (response.page_count == null) show_error();

        $('#todo').text('Lanjut').attr('todo', 'auctions');

        var data = '';
        data += '<p>';
        data += '   <b>['+response.execution_time+']</b> Ditemukan '+response.page_count+' halaman yang harus ditelusuri.<br/>';
        data += '   <label>Batasi pencarian sebanyak <input id="limit" name="limit" type="number" value="10" min="0" max="'+response.page_count+'" step="1" placeholder="0"> halaman.</label> (0 = tidak dibatasi, default = 10)';
        data += '</p>';
        $('#message_text').html(data);
    })
    .complete(function(){
        stop_progress();
    });
}

function get_auctions(limit)
{
    $.ajax({
        url: base_url,
        data: {'do': 'auctions', 'limit': limit},
        type: 'post',
        dataType: 'json'
    })
    .error(function(){
        stop_progress();
        show_error();
    })
    .success(function(response){
        if (response.page == null || response.page_count == null || response.auctions == null) show_error();

        update_progress(response.page, response.page_count);

        if (response.page < response.page_count) {
            get_auctions();
        } else {
            stop_progress();

            $('#todo').text('Lanjut').attr('todo', 'winners');

            $('#message_text').html('<p><b>['+response.execution_time+']</b> Ditemukan '+response.auctions.length+' lelang yang harus diperiksa.</p>');

            var data = '';

            data += '<table>';
            data += '    <thead>';
            data += '        <tr>';
            data += '            <th><input id="checkall" name="checkall" type="checkbox" value="checkall"></th>';
            data += '            <th>ID Lelang</th>';
            data += '            <th>Status Lelang</th>';
            data += '        </tr>';
            data += '    </thead>';
            data += '    <tbody>';

            $.each(response.auctions, function(index, value){
            data += '        <tr>';
            data += '            <td><input name="id[]" type="checkbox" value="'+value.id+'"></td>';
            data += '            <td><a href="'+base_target_url+'eproc/lelang/pemenang/'+value.id+'" target="_blank">'+value.id+'</a></td>';
            data += '            <td>'+value.status+'</td>';
            data += '        </tr>';
            });

            data += '    </tbody>';
            data += '</table>';

            $('#data').html(data);

            $('#checkall').on('click', function(){
                $('input:checkbox[name="id[]"]').prop('checked', this.checked);
            });
        }
    });
}

function get_winners(ids)
{
    $.ajax({
        url: base_url,
        data: {'do': 'winners', 'ids': ids},
        type: 'post',
        dataType: 'json'
    })
    .error(function(){
        stop_progress();
        show_error();
        alert('asu');
    })
    .success(function(response){
        if (response.ids == null || response.index == null || response.winners == null) show_error();

        update_progress(response.index, response.ids.length);

        if (response.index < response.ids.length) {
            get_winners();
        } else {
            stop_progress();

            $('#todo').hide();

            $('#message_text').html('<p><b>['+response.execution_time+']</b> Berhasil memeriksa '+response.winners.length+' lelang.<br/><a href="'+base_url+'">Kembali ke awal</a></p>');

            var data = '';

            data += '<table>';
            data += '    <thead>';
            data += '        <tr>';
            data += '            <th>ID Lelang</th>';
            data += '            <th>Nama Lelang</th>';
            data += '            <th>Kategori</th>';
            data += '            <th>Satker</th>';
            data += '            <th>Status Lelang</th>';
            data += '            <th>Nama Pemenang</th>';
            data += '            <th>NPWP</th>';
            data += '            <th>Harga Penawaran</th>';
            data += '        </tr>';
            data += '    </thead>';
            data += '    <tbody>';

            $.each(response.winners, function(index, value){
            data += '        <tr>';
            data += '            <td><a href="'+base_target_url+'eproc/lelang/pemenang/'+value.id+'" target="_blank">'+value.id+'</a></td>';
            data += '            <td>'+value.title+'</td>';
            data += '            <td>'+value.category+'</td>';
            data += '            <td>'+value.satker+'</td>';
            data += '            <td>'+value.status+'</td>';
            data += '            <td>'+value.winner+'</td>';
            data += '            <td nowrap>'+value.npwp+'</td>';
            data += '            <td nowrap>'+value.price+'</td>';
            data += '        </tr>';
            });

            data += '    </tbody>';
            data += '</table>';

            $('#data').html(data);
        }
    });
}

function start_progress(message)
{
    $('#favicon').attr('href', 'loading.gif');
    $('#progress_bar').width('0%');
    $('#progress_text').text('0%');

    $('#message_text').text(message);
    $('#loading').show();

    $('#todo').attr('disabled', 'disabled');

    $('#data').html('');
}

function update_progress(progress_index, progress_count)
{
    var progress = Math.floor((progress_index * 100) / progress_count);
    $('#progress_bar').width(progress + '%');
    $('#progress_text').text(progress + '%');
}

function stop_progress()
{
    $('#favicon').attr('href', 'lpse.png');
    $('#loading').hide();

    $('#todo').removeAttr('disabled');
}


function show_error()
{
    $('#message_text').html('<p>Error!</p>');
    return false;
}