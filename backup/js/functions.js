$(document).ready(function (){
    $('#todo').on('click',function(){
        reset_progress();

        var todo = $('#todo').attr('data-todo');

        switch(todo) {
            case 'page' :
                get_page();
                break;
            case 'auctions' :
                get_auctions();
                break;
            case 'winners' :
                get_winners();
                break;
        }
    });

    function get_page()
    {
        in_progress(true, 'Menghitung halaman yang harus ditelusuri...');

        $.ajax({
            url: base_url,
            data: {'do': 'page'},
            type: 'post',
            dataType: 'json'
        })
        .success(function(response){
            in_progress(false, '');

            if (response.page_count != null) {
                $('#message span').text('Ditemukan '+response.page_count+' halaman yang harus ditelusuri.');

                update_button('auctions');
            } else {
                $('#message span').text('Error!');
            }
        })
    }

    function get_auctions()
    {
        in_progress(true, 'Menelusuri halaman...');

        $.ajax({
            url: base_url,
            data: {'do': 'auctions'},
            type: 'post',
            dataType: 'json'
        })
        .success(function(response){
            if (response.progress == null) {
                in_progress(false, '');

                $('#message span').text('Error!');

                return false;
            }
            update_progress(response.progress);

            if (response.progress < 100) {
                get_auctions();
            } else {
                in_progress(false, '');

                if (Object.prototype.toString.call(response.auctions) === '[object Array]') {
                    $('#message span').text('Ditemukan '+response.auctions.length+' lelang yang harus diperiksa.');

                    update_button('winners');
                } else {
                    $('#message span').text('Error!');
                }
            }
        })
    }

    function get_winners()
    {
        in_progress(true, 'Memeriksa lelang...');

        $.ajax({
            url: base_url,
            data: {'do': 'winners'},
            type: 'post',
            dataType: 'json'
        })
        .success(function(response){
            if (response.progress == null) {
                in_progress(false, '');

                $('#message span').text('Error!');

                return false;
            }
            update_progress(response.progress);

            if (response.progress < 100) {
                get_winners();
            } else {
                in_progress(false, '');

                if (Object.prototype.toString.call(response.winners) === '[object Array]') {
                    $('#message span').text('Berhasil memeriksa '+response.winners.length+' lelang.');

                    $('#todo').hide();

                    var data = '';
                    data += '<table>';
                    data += '<thead>';
                    data += '<tr>';
                    data += '<th>ID Lelang</th>';
                    data += '<th>Nama Lelang</th>';
                    data += '<th>Kategori</th>';
                    data += '<th>Satker</th>';
                    data += '<th>Status Lelang</th>';
                    data += '<th>Nama Pemenang</th>';
                    data += '<th>NPWP</th>';
                    data += '<th>Harga Penawaran</th>';
                    data += '</tr>';
                    data += '</thead>';
                    data += '<tbody>';
                    $.each(response.winners, function(key, value) {
                        data += '<tr>';
                        data += '<td><a href="'+base_target_url+'eproc/lelang/pemenang/'+value.id+'" target="_blank">'+value.id+'</a></td>';
                        data += '<td>'+value.title+'</td>';
                        data += '<td>'+value.category+'</td>';
                        data += '<td>'+value.satker+'</td>';
                        data += '<td>'+value.status+'</td>';
                        data += '<td nowrap>'+value.winner+'</td>';
                        data += '<td nowrap>'+value.npwp+'</td>';
                        data += '<td nowrap>'+value.price+'</td>';
                        data += '</tr>';
                    });
                    data += '</tbody>';
                    data += '</table>';

                    $('#result').html(data);
                } else {
                    $('#message span').text('Error!');
                }
            }
        })
    }

    function reset_progress()
    {
        in_progress(false);
        update_progress(0);
    }

    function update_progress(progress)
    {
        $('#progress div').width(progress + '%');
        $('#progress span').text(progress + '%');
    }

    function in_progress(in_progress, message)
    {
        if (in_progress) {
            $('#favicon').attr('href', 'loading.gif');
            $('#message img').show();
            $('#message span').text(message);
            $('#todo').attr('disabled','disabled');
        } else {
            $('#favicon').attr('href', 'lpse.png');
            $('#message img').hide();
            $('#message span').text('');
            $('#todo').removeAttr('disabled');
        }
    }

    function update_button(task)
    {
        $('#todo').attr('data-todo', task);
        $('#todo').text('Lanjut');
    }
});