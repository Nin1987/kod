add_action(
    'woocommerce_blocks_validate_location_contact_fields',
    function ( WP_Error $errors, $fields, $group ) {
        
        if ( $fields['invoke/fv'] === true ) {
            if(empty($fields['invoke/name']) || empty($fields['invoke/email']) || empty($fields['invoke/street']) || empty($fields['invoke/city']) || empty($fields['invoke/zip_code']) || empty($fields['invoke/nip']))
            {
                $errors->add( 'error', 'Do faktury potrzebne są wszystkie pola' );
            }
        }
    },
    10,
    3
);

add_action(	'woocommerce_init', 'my_fv_fields');

function my_fv_fields() {

  

    woocommerce_register_additional_checkout_field(
        array(
            'id'       => 'invoke/fv',
            'label'    => 'Czy chcesz otrzymać fakturę',
            'location' => 'contact',
            'type'     => 'checkbox',
            'checked' => false,
            'required' => false,
    
            'options' => array(
                'checked' => ''
            )
         
        )
    );
		woocommerce_register_additional_checkout_field(
			array(
				'id'            => 'invoke/name',
				'label'         => 'Nazwa firmy',
				'location'      => 'contact',
				'required'      => false,
                'attributes'    => array(
					'title'        => 'To pole nie może być puste',
				),
           
			),
        );
        woocommerce_register_additional_checkout_field(
            array(
                'id'            => 'invoke/email',
                'label'         => 'Email',
                'location'      => 'contact',
                'required'      => false,
               
            )
    );
    add_action(
        'woocommerce_set_additional_field_value',
        function ( $key, $value, $group, $wc_object ) {
            if ( 'invoke/email' !== $key ) {
                return;
            }
    
            if ( 'billing' === $group ) {
                $my_plugin_address_key = '_invoke_email';
            } else {
                $my_plugin_address_key = '_invoke_email';
            }
    
            $wc_object->update_meta_data( $my_plugin_address_key, $value, true );
        },
        10,
        4
    );
    woocommerce_register_additional_checkout_field(
        array(
            'id'            => 'invoke/street',
            'label'         => 'Ulica',
            'location'      => 'contact',
            'required'      => false,
           
        )
);
add_action(
    'woocommerce_set_additional_field_value',
    function ( $key, $value, $group, $wc_object ) {
        if ( 'invoke/street' !== $key ) {
            return;
        }

        if ( 'billing' === $group ) {
            $my_plugin_address_key = '_invoke_street';
        } else {
            $my_plugin_address_key = '_invoke_street';
        }

        $wc_object->update_meta_data( $my_plugin_address_key, $value, true );
    },
    10,
    4
);
woocommerce_register_additional_checkout_field(
    array(
        'id'            => 'invoke/city',
        'label'         => 'Miasto',
        'location'      => 'contact',
        'required'      => false,
      
    )
);
add_action(
    'woocommerce_set_additional_field_value',
    function ( $key, $value, $group, $wc_object ) {
        if ( 'invoke/city' !== $key ) {
            return;
        }

        if ( 'billing' === $group ) {
            $my_plugin_address_key = '_invoke_city';
        } else {
            $my_plugin_address_key = '_invoke_city';
        }

        $wc_object->update_meta_data( $my_plugin_address_key, $value, true );
    },
    10,
    4
);
woocommerce_register_additional_checkout_field(
    array(
        'id'            => 'invoke/zip_code',
        'label'         => 'Kod pocztowy',
        'location'      => 'contact',
        'required'      => false,
      
    )
);
add_action(
    'woocommerce_set_additional_field_value',
    function ( $key, $value, $group, $wc_object ) {
        if ( 'invoke/zip_code' !== $key ) {
            return;
        }

        if ( 'billing' === $group ) {
            $my_plugin_address_key = '_invoke_zip_code';
        } else {
            $my_plugin_address_key = '_invoke_zip_code';
        }

        $wc_object->update_meta_data( $my_plugin_address_key, $value, true );
    },
    10,
    4
);
woocommerce_register_additional_checkout_field(
    array(
        'id'            => 'invoke/nip',
        'label'         => 'Nip',
        'location'      => 'contact',
        'required'      => false,
       
    )
);
add_action(
    'woocommerce_set_additional_field_value',
    function ( $key, $value, $group, $wc_object ) {
        if ( 'invoke/nip' !== $key ) {
            return;
        }

        if ( 'billing' === $group ) {
            $my_plugin_address_key = '_invoke_nip';
        } else {
            $my_plugin_address_key = '_invoke_nip';
        }

        $wc_object->update_meta_data( $my_plugin_address_key, $value, true );
    },
    10,
    4
);
add_action(
	'woocommerce_set_additional_field_value',
	function ( $key, $value, $group, $wc_object ) {
		if ( 'invoke/name' !== $key ) {
			return;
		}

		if ( 'billing' === $group ) {
			$my_plugin_address_key = '_invoke_name';
		} else {
			$my_plugin_address_key = '_invoke_name';
		}

		$wc_object->update_meta_data( $my_plugin_address_key, $value, true );
	},
	10,
	4
);
		add_action(
			'woocommerce_sanitize_additional_field',
			function ( $field_value, $field_key ) {
                if( 'invoke/fv' === $field_key ) {

                }
				return $field_value;
			},
			10,
			2
		);
        //validacja
        add_action(
            'woocommerce_validate_additional_field',
                function ( WP_Error $errors, $field_key, $field_value ) {
                    if ( 'invoke/email' === $field_key ) {
                        if(!filter_var($field_value, FILTER_VALIDATE_EMAIL))
                        {
                            $errors->add( 'invalid_mail', 'Proszę podać poprawny adres mail.' );
                        }
                    }
                    return $errors;
                },
                10,
                3
            );

            add_action(
                'woocommerce_validate_additional_field',
                    function ( WP_Error $errors, $field_key, $field_value ) {
                        if ( 'invoke/zip_code' === $field_key ) {
                            $pattern = array('/^\d{2}-\d{3}$/');

                            if(!preg_match($pattern[0], $field_value))
                            {
                                $errors->add('invalid_zip_code','To nie jest poprawny format kodu pocztowego.');
                            }
                        }
                        return $errors;
                    },
                    10,
                    3
                );

                add_action(
                    'woocommerce_validate_additional_field',
                        function ( WP_Error $errors, $field_key, $field_value ) {
                            if ( 'invoke/nip' === $field_key ) {
                                
    
                                if(!is_numeric($field_value) || $field_value < 1000000000)
                                {
                                    $errors->add('invalid_nip','To nie jest poprawny format numeru NIP.');
                                }
                            }
                            return $errors;
                        },
                        10,
                        3
                    );
}

function css_add_2()
{
    
    ?>
 
    <style>
    #contact-invoke-name,#contact-invoke-nip,#contact-invoke-email, #contact-invoke-street,#contact-invoke-city,#contact-invoke-zip_code, #contact-invoke-name~label,#contact-invoke-nip~label,#contact-invoke-email~label, #contact-invoke-street~label,#contact-invoke-city~label,#contact-invoke-zip_code~label  { display:none; }
    .on, .on~label{display: block !important;}
    .off, .off~label{display: none !important;}

    </style>


<?php
}

function add_js_2()
{
    
    ?>
    <script type="text/javascript">
      
        window.onload=function(){
            jQuery(function($){
        var a = '#contact-invoke-fv';
        var b = '#contact-invoke-name,#contact-invoke-nip,#contact-invoke-email, #contact-invoke-street,#contact-invoke-city,#contact-invoke-zip_code';

        $(a).change(function() {
            if ( $(this).prop('checked') === true ) {
                $(b).show(function(){
                    $(b).removeClass('off');
                    $(b).addClass('on');
                   
                });
            }
            else if ( $(this).prop('checked') !== true) {
                
                $(b).fadeOut(function(){
                    $(b).removeClass('on');
                    $(b).addClass('off')
                });
                $(b+' input').val('');
            }
        });
    });
   
}
    </script>
    
        <?php
}

add_action( 'wp_enqueue_scripts', 'css_add_2', 10 );
add_action('wp_enqueue_scripts', 'add_js_2', 25);
