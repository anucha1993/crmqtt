function searachcustomer (data)
{
    var url =  '/api/customer/list'
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: window.location.origin + url,
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            data: data,
            success: function(data) {
                // console.log(data);
                $('#datatable').html(data.datas);
                $('#box_tltal').html(data.total);
                $('#pagination').html(data.pagination);
            }
        });

      }).then(function(){
          return true;
      })
}
