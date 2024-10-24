function searachreport (data,url)
{
    var urls =  window.location.origin+'/report/'+url
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: urls,
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            data: data,
            success: function(data) {
                $('#datatable').html(data.datas);
                $('#box_tltal').html(data.total);
                $('#pagination').html(data.pagination);
            }
        });

      }).then(function(){
          return true;
      })
}
