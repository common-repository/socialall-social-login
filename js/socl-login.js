jQuery(document).ready(function($) {
    // for register email
    $( 'input[name=\'sk_soclall_login_email\']' ).keypress( function(e) {
        if (e.which == 13) {
            $( '#sk_soclall_login_regis_email' ).trigger( 'click' );
            return false;
        }
    });
    
    $( '#sk_soclall_login_regis_email' ).click( function() {
        $( '#login_error, #loading' ).hide();
        
        var email = $( 'input[name=\'sk_soclall_login_email\']' ).val();
        var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        if( !re.test( email ) ) {
            $( '#login_error' ).html( '<strong>ERROR</strong>: Invalid Email!' ).show('slow');
            return;
        }
        
        $.ajax({
            method: 'POST',
            data: {
                sk_soclall_login: 'check_email',
                sk_soclall_login_email: email,
                network: $( 'input[name=\'network\']' ).val()
            },
            dataType: 'json',
            beforeSend: function() {
                $( '#loading' ).show();
                $( '#sk_soclall_login_regis_email' ).parent().hide();
            },
            success: function( result ) {
                if(result['success'] == '0') {
                    $( '#login_error' ).html( '<strong>ERROR</strong>: ' + result['error'] ).show('slow');
                    if( $( '#loading' ).css('display') != 'none' )
                        $( '#loading' ).hide();
                    if( $( '#sk_soclall_login_regis_email' ).parent().css('display') == 'none' )
                        $( '#sk_soclall_login_regis_email' ).parent().show();
                } else if (result['success'] == '1') {
                    $.ajax({
                        method: 'POST',
                        data: {
                            sk_soclall_login: 'register_email',
                            sk_soclall_login_email: email,
                            token: $( 'input[name=\'token\']' ).val(),
                            network: $( 'input[name=\'network\']' ).val()
                        },
                        dataType: 'json',
                        success: function( result ) {
                            if(result['success'] == '0') {
                                $( '#login_error' ).html( '<strong>ERROR</strong>: ' + result['error'] ).show('slow');
                                if( $( '#loading' ).css('display') != 'none' )
                                    $( '#loading' ).hide();
                                if( $( '#sk_soclall_login_regis_email' ).parent().css('display') == 'none' )
                                    $( '#sk_soclall_login_regis_email' ).parent().show();
                            } else if (result['success'] == '1') {
                                window.location = result['url'];
                            }
                        },
                       	error: function(xhr, ajaxOptions, thrownError) {
                      		alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                       	}
                    });
                }
            },
           	error: function(xhr, ajaxOptions, thrownError) {
          		alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
           	}
        });
    });
    
    // for confirm password
    $('#sk_soclall_login_confirm_pass').click(function() {
        $( '#login_error, #loading' ).hide();
    	$.ajax({
    		method: 'POST',
    		data: {
    			sk_soclall_login: 'confirm_pass',
    			sk_soclall_login_pass: $('input[name=\'sk_soclall_login_pass\']').val(),
    			token: $('input[name=\'token\']').val(),
    			network: $('input[name=\'network\']').val()
    		},
    		dataType: 'json',
            beforeSend: function() {
                $( '#loading' ).show();
                $( '#sk_soclall_login_confirm_pass' ).parent().hide();
            },
    		success: function(result) {
    			if (result['success'] == '0') {
    				$('#login_error').html('<strong>ERROR</strong>: ' + result['error']).show('slow');
                    if( $( '#loading' ).css('display') != 'none' )
                        $( '#loading' ).hide();
                    if( $( '#sk_soclall_login_regis_email' ).parent().css('display') == 'none' )
                        $( '#sk_soclall_login_regis_email' ).parent().show();
    			} else if (result['success'] == '1') {
    				window.location = result['url'];
    			}
    		},
    		error: function(xhr, ajaxOptions, thrownError) {
    			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    		}
    	});
    });
});

function sk_soclall_login_check_rememberme( href, callback_url ) {
    jQuery(function($) {
        var rememberme = $( 'input[name=\'rememberme\']' );
        if( rememberme.length && rememberme.is( ':checked' ) ) {
            var new_url = callback_url + '&remember_me=1';
            var old_href = $( href ).attr( 'href' );
            $( href ).attr( 'href', old_href.replace( encodeURIComponent( callback_url ), encodeURIComponent( new_url ) ) );
        }
    });
    return true;
}