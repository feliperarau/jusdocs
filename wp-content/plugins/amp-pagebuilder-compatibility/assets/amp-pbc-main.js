jQuery(document).ready(function($){
    $('#amp-pbc-clearcss-data').click(function(e){
        $('#amp-pbc-clcss-msg').text(' Please wait').css({'line-height':'25px'});
        var datastr = {
			'action': 'amp_pbc_clear_css_transient',
			'nonce': $(this).attr('data-nonce')
		};
		jQuery.ajax({
                url: ajaxurl,
                data: datastr,
                dataType: 'json',
                success: function(response) {
			        if(response.status==200){
                        $('#amp-pbc-clcss-msg').text(' '+response.message).css({'line-height':'25px'});
                    }
                }
        });
    });



    $('.amp-pbc-checkbox').click(function(){
        if($(this).is(':checked')){
            $(this).parent('td').find('input[type=hidden]').val(1);
        }else{
            $(this).parent('td').find('input[type=hidden]').val(0);
        }
    })
    $('.amp-pbc-tabs a').click(function(){
        var tab = $(this).attr('data-tab');
        $('.amp-pbc-'+tab).siblings().hide();
        $('.amp-pbc-'+tab).show();
    });

});