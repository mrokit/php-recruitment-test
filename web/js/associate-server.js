const websiteCheckbox = $('.associate-website-server');

$(websiteCheckbox).click(function () {

    if ($(this).is(':checked')) {
        let value = $(this).val();

        $.ajax({
            url: '/varnish',
            type: 'PUT',
            data: value,
            success: function(data) {
                console.log(data);
                alert('Load was performed.');
            }
        });
    }
    else {
        $.ajax({
            url: '/varnish',
            type: 'DELETE',
            data: value,
            success: function(data) {
                alert('Load was performed.');
            }
        });
    }

});