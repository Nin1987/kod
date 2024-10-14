<?php

add_action(
    'woocommerce_blocks_validate_location_contact_fields',
    function ( WP_Error $errors, $fields, $group ) {
        
        if ( $fields['invoice/fv'] === true ) {
            if(empty($fields['invoice/name'])  || empty($fields['invoice/street']) || empty($fields['invoice/city']) || empty($fields['invoice/zip_code']) || empty($fields['invoice/nip']))
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
            'id'       => 'invoice/fv',
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
				'id'            => 'invoice/name',
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
            'id'            => 'invoice/street',
            'label'         => 'Ulica',
            'location'      => 'contact',
            'required'      => false,
           
        )
);
add_action(
    'woocommerce_set_additional_field_value',
    function ( $key, $value, $group, $wc_object ) {
        if ( 'invoice/street' !== $key ) {
            return;
        }

        if ( 'billing' === $group ) {
            $my_plugin_address_key = '_invoice_street';
        } else {
            $my_plugin_address_key = '_invoice_street';
        }

        $wc_object->update_meta_data( $my_plugin_address_key, $value, true );
    },
    10,
    4
);
woocommerce_register_additional_checkout_field(
    array(
        'id'            => 'invoice/city',
        'label'         => 'Miasto',
        'location'      => 'contact',
        'required'      => false,
      
    )
);
add_action(
    'woocommerce_set_additional_field_value',
    function ( $key, $value, $group, $wc_object ) {
        if ( 'invoice/city' !== $key ) {
            return;
        }

        if ( 'billing' === $group ) {
            $my_plugin_address_key = '_invoice_city';
        } else {
            $my_plugin_address_key = '_invoice_city';
        }

        $wc_object->update_meta_data( $my_plugin_address_key, $value, true );
    },
    10,
    4
);
woocommerce_register_additional_checkout_field(
    array(
        'id'            => 'invoice/zip_code',
        'label'         => 'Kod pocztowy',
        'location'      => 'contact',
        'required'      => false,
      
    )
);
add_action(
    'woocommerce_set_additional_field_value',
    function ( $key, $value, $group, $wc_object ) {
        if ( 'invoice/zip_code' !== $key ) {
            return;
        }

        if ( 'billing' === $group ) {
            $my_plugin_address_key = '_invoice_zip_code';
        } else {
            $my_plugin_address_key = '_invoice_zip_code';
        }

        $wc_object->update_meta_data( $my_plugin_address_key, $value, true );
    },
    10,
    4
);
woocommerce_register_additional_checkout_field(
    array(
        'id'            => 'invoice/nip',
        'label'         => 'Nip',
        'location'      => 'contact',
        'required'      => false,
       
    )
);
add_action(
    'woocommerce_set_additional_field_value',
    function ( $key, $value, $group, $wc_object ) {
        if ( 'invoice/nip' !== $key ) {
            return;
        }

        if ( 'billing' === $group ) {
            $my_plugin_address_key = '_invoice_nip';
        } else {
            $my_plugin_address_key = '_invoice_nip';
        }

        $wc_object->update_meta_data( $my_plugin_address_key, $value, true );
    },
    10,
    4
);
add_action(
	'woocommerce_set_additional_field_value',
	function ( $key, $value, $group, $wc_object ) {
		if ( 'invoice/name' !== $key ) {
			return;
		}

		if ( 'billing' === $group ) {
			$my_plugin_address_key = '_invoice_name';
		} else {
			$my_plugin_address_key = '_invoice_name';
		}

		$wc_object->update_meta_data( $my_plugin_address_key, $value, true );
	},
	10,
	4
);
		add_action(
			'woocommerce_sanitize_additional_field',
			function ( $field_value, $field_key ) {
                if( 'invoice/fv' === $field_key ) {

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
                        if ( 'invoice/zip_code' === $field_key ) {
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
                            if ( 'invoice/nip' === $field_key ) {
                                
    
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
    #contact-invoice-name,#contact-invoice-nip, #contact-invoice-street,#contact-invoice-city,#contact-invoice-zip_code, #contact-invoice-name~label,#contact-invoice-nip~label, #contact-invoice-street~label,#contact-invoice-city~label,#contact-invoice-zip_code~label  { display:none; }
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
        var a = '#contact-invoice-fv';
        var b = '#contact-invoice-name,#contact-invoice-nip,#contact-invoice-street,#contact-invoice-city,#contact-invoice-zip_code';

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
