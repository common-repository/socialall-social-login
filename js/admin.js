jQuery(document).ready(function($) {
	// for tabs
	$( '.sk-soclall-login-tab' ).click(function() {
		$( '.sk-soclall-login-tab' ).removeClass( 'sk-soclall-login-active-tab' );
		$(this).addClass( 'sk-soclall-login-active-tab' );
		$( '.sk-soclall-login-tab-content' ).hide();
		$( '#for_' + $(this).attr( 'id' ) ).show();
        
        if( $(this).attr( 'id' ) == 'sk_soclall_login_tab_shortcode_guide' )
            $('#sk_soclall_login_bottom').hide();
        else
            $('#sk_soclall_login_bottom').show();
	});
    
    // for how to get API config
    $('#sk_soclall_login_how_to').click(function() {
        if ( $( '.sk-soclall-login-how-to-info' ).css('display') == 'none' ) {
            $( '.sk-soclall-login-how-to-info' ).show( 'slow' );
            $( '#sk_soclall_login_how_to_hide' ).show();
            $( '#sk_soclall_login_how_to_show' ).hide();
        } else {
            $( '.sk-soclall-login-how-to-info' ).hide( 'slow' );
            $( '#sk_soclall_login_how_to_show' ).show();
            $( '#sk_soclall_login_how_to_hide' ).hide();
        }
    });
    
	// for custom Url
	$( 'input[name=\'sk_soclall_login_redirection\']' ).change(function() {
		var ele = $( 'input[name=\'sk_soclall_login_custom_url\']' );
		if ($( '#sk_soclall_login_custom_url' ).is( ':checked' )) {
			if (ele.parent().css( 'display' ) == 'none') ele.parent().show( 'slow' );
		} else
            ele.parent().hide( 'slow' );
	});
    
    // for radio active
    $('.sk-soclall-login-container-radio label').click(function() {
        var ele = $(this).find( 'input[type=\'radio\']' );
        if( ele.is( ':checked' ) ) {
            $( '.sk-soclall-login-container-radio input[name="' + ele.attr('name') + '"]' ).parent().removeClass('sk-soclall-login-radio-active');
            $(this).addClass('sk-soclall-login-radio-active');
        }
    });
    
    // for message
    setTimeout(function() {
        if( $('.sk-soclall-login-message').length ) {
            $('.sk-soclall-login-message').hide('slow');
        }
    }, 3000);
    
    // for select all networks   
    $( '#sk_soclall_login_select_all' ).change(function() {
        if ( $( this ).is( ':checked' ) )
            $( 'input[name=\'sk_soclall_login_networks[]\']' ).prop( 'checked', true);
        else {
            $( 'input[name=\'sk_soclall_login_networks[]\']' ).prop( 'checked', false);
        }
    });
    
    // for custom URL text
    $( 'input[name=\'sk_soclall_login_custom_url\']' ).focus( function() {
        var $this = $( this );
        $this.select();
        
        $this.mouseup(function() {
            // Prevent further mouseup intervention
            $this.unbind("mouseup");
            return false;
        });
    });
    
    // for customize theme
    $('#soclall_customize_theme select[name=\'sk_soclall_login_theme_col\']').change(function() {
    	var col = $(this).val();
    	var review = $('#soclall_customize_theme_review div.col');
    	for (i = 1; i <= 5; i++) {
    		if (review.hasClass('col' + i)) {
    			review.removeClass('col' + i);
    			break;
    		}
    	}
    	review.addClass('col' + col);
    });
    
    $('#soclall_customize_theme select[name=\'sk_soclall_login_theme_pos\']').change(function() {
    	var pos = $(this).val();
    	var review = $('#soclall_customize_theme_review div.col');
    	var position = ['l', 'c', 'r'];
    	for (i = 0; i < position.length; i++) {
    		if (review.hasClass('pos-' + position[i])) {
    			review.removeClass('pos-' + position[i]);
    			break;
    		}
    	}
    	review.addClass('pos-' + pos);
    });
    
    $('#soclall_customize_theme select[name=\'sk_soclall_login_theme_align\']').change(function() {
    	var align = $(this).val();
    	var review = $('#soclall_customize_theme_review span.sa');
    	var position = ['l', 'c', 'r'];
    	for (i = 0; i < position.length; i++) {
    		if (review.hasClass('txt-' + position[i])) {
    			review.removeClass('txt-' + position[i]);
    			break;
    		}
    	}
    	review.addClass('txt-' + align);
    });
    
    $('#soclall_customize_theme select#sk_soclall_login_theme_resize').change(function() {
    	var size = $(this).val();
    	var review = $('#soclall_customize_theme_review span.sa');
    	var sizes = ['100', '75', '50'];
    	for (i = 0; i < sizes.length; i++) {
    		if (review.hasClass('sa-' + sizes[i])) {
    			review.removeClass('sa-' + sizes[i]);
    			break;
    		}
    	}
    	review.addClass('sa-' + size);
    });
    
    $('#soclall_customize_theme input[name=\'sk_soclall_login_theme_text\']').keyup(function() {
    	var btn_text = $(this).val();   	
    	$('#soclall_customize_theme_review span.sa').each(function() {
    		network_text = $(this).find('span').html();
    		$(this).html(btn_text + '<span>' + network_text + '</span>');
    	});
    });
    
    $('#soclall_customize_theme input[name=\'sk_soclall_login_theme_width\']').keyup(function() {
    	var btn_width = $(this).val();
        if( parseInt(btn_width) >= 100 )
            $('#soclall_customize_theme_review span.sa').css('width', '');
    	else $('#soclall_customize_theme_review span.sa').css('width', btn_width + '%');
    });
});

var sk_soclall_login_theme_customize = '';

function soclLoginApplyTheme( ele, theme ) {
    jQuery(function($) {
        $('input[name=\'sk_soclall_login_apply_theme\']').val(theme);
                
        $('div.sk-soclall-login-theme-block').removeClass('active');
        $(ele).parent().addClass('active');
                
        $('div.sk-soclall-login-theme-block input[type=button]').show();
        $(ele).hide();
        
        $('div.sk-soclall-login-theme-block span.check-theme-applied').hide();
        $(ele).parent().find('span.check-theme-applied').show();
        
        if($(ele).parent().find('select').length)
            $('input[name=\'sk_soclall_login_theme_resize\']').val( $(ele).parent().find('select').val() );
    });
}

function soclLoginResizeTheme( ele, check ) {
    jQuery(function($) {
        resize = $(ele).val();
        theme = $(ele).parent().parent().find('div.sa-frame span');
        
        if(check == $('input[name=\'sk_soclall_login_apply_theme\']').val())
            $('input[name=\'sk_soclall_login_theme_resize\']').val(resize);
        
        sizes = ['100','75','50'];
        for( i = 0; i < sizes.length; i++ ) {
            if( theme.hasClass('sa-' + sizes[i]) ) {
                theme.removeClass( 'sa-' + sizes[i] );
                break;
            }
        }
        
        theme.addClass( 'sa-' + resize );
    });
}

function soclLoginCustomTheme( ele, theme ) {
    jQuery(function($) {
        sk_soclall_login_theme_customize = theme;
        var icons_content = $(ele).parent().find('div.sa-frame').html();
        $('div#soclall_customize_theme_review').html(icons_content);
            
        if(theme == 'no7')
            $('#soclall_customize_theme select#sk_soclall_login_theme_resize').parent().parent().show();
        else $('#soclall_customize_theme select#sk_soclall_login_theme_resize').parent().parent().hide();
        
        var theme_customized = {};
        if(theme == $('input[name=\'sk_soclall_login_apply_theme\']').val()) {
            theme_customized = {size: $('input[name=\'sk_soclall_login_theme_resize\']').val(), col: sk_soclall_login_col, width: sk_soclall_login_width, text: sk_soclall_login_text, align: sk_soclall_login_align, position: sk_soclall_login_pos};
        } else {
            theme_customized = {size: '100', col: '1', width: '100', text: sk_soclall_login_text_default, align: 'l', position: 'l'};
        }
        
        $('#soclall_customize_theme select#sk_soclall_login_theme_resize').val(theme_customized.size);
        $('#soclall_customize_theme select[name=\'sk_soclall_login_theme_col\']').val(theme_customized.col);
        $('#soclall_customize_theme input[name=\'sk_soclall_login_theme_text\']').val(theme_customized.text);
        $('#soclall_customize_theme select[name=\'sk_soclall_login_theme_align\']').val(theme_customized.align);
        $('#soclall_customize_theme select[name=\'sk_soclall_login_theme_pos\']').val(theme_customized.position);
        $('#soclall_customize_theme input[name=\'sk_soclall_login_theme_width\']').val(theme_customized.width);
                
        $('div#soclall_manage_theme').hide();
        $('div#soclall_customize_theme').show();
    });
}

function soclLoginCancelCustomTheme() {
    jQuery(function($) {
        $('#soclall_customize_theme select[name=\'sk_soclall_login_theme_col\']').val(sk_soclall_login_col);
        $('#soclall_customize_theme input[name=\'sk_soclall_login_theme_text\']').val(sk_soclall_login_text);
        $('#soclall_customize_theme select[name=\'sk_soclall_login_theme_align\']').val(sk_soclall_login_align);
        $('#soclall_customize_theme select[name=\'sk_soclall_login_theme_pos\']').val(sk_soclall_login_pos);
        $('#soclall_customize_theme input[name=\'sk_soclall_login_theme_width\']').val(sk_soclall_login_width);
        
        $('div#soclall_customize_theme').hide();
        $('div#soclall_manage_theme').show();
    });
}

function soclLoginApplyCustomTheme() {
    jQuery(function($) {    
        var icons_content = $('div#soclall_customize_theme_review .sa-' + sk_soclall_login_theme_customize).html();
        $('div#soclall_manage_theme .sa-' + sk_soclall_login_theme_customize).html(icons_content);
        
        soclLoginApplyTheme($('div#soclall_manage_theme .sa-' + sk_soclall_login_theme_customize).parent().parent().find('#sk_soclall_login_apply'), sk_soclall_login_theme_customize);
        
        if(sk_soclall_login_theme_customize == 'no7')
            $('input[name=\'sk_soclall_login_theme_resize\']').val($('#soclall_customize_theme select#sk_soclall_login_theme_resize').val());
        
        $('div#soclall_customize_theme').hide();
        $('div#soclall_manage_theme').show();
    });
}