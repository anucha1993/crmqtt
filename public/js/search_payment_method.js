function search_payment_method(data,url)
{
    var urls =  window.location.origin+'/payment_method/'+url
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
