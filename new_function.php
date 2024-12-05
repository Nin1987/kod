<?php
add_action('woocommerce_admin_order_item_headers', 'my_woocommerce_admin_order_item_headers', 100);
function my_woocommerce_admin_order_item_headers() {
    
    $column_name = 'Brutto';
    echo '<th>' . $column_name . '</th>';
}

// Add custom column values here
add_action('woocommerce_admin_order_item_values', 'my_woocommerce_admin_order_item_values', 3, 3);
function my_woocommerce_admin_order_item_values($_product, $item, $item_id) {

    $price_n = $item['subtotal']/$item['quantity'];
    $tax = $item['subtotal_tax']/$item['quantity'];
    $price = $price_n+ $tax;
   
    $value = number_format($price, 2, ',', ' ') .' zł';
    
    echo '<td>' . $value . '</td>';
}

//new fields register
add_action( 'woocommerce_checkout_before_customer_details', 'my_checkbox' );

function my_checkbox($checkout) {

    echo '<div class=""><h2>' . __('Dane faktury', 'woocommerce') . '</h2>';

    woocommerce_form_field( '_invoice_fv', array(
        'type'     => 'checkbox',
        'class'    => array('checkbox_field'),
        'label'    => __('Czy chcesz fakturę', 'woocommerce'),
        'id'       =>'_invoice_name',
        'required' => false,
    ));

    echo '<div class="faktura_view_off" id="faktura_memory_block">';

    woocommerce_form_field( '_invoice_name', array(
        'type'     => 'text',
        'class'    => array('input-text'),
        'label'    => __('Nazwa firmy', 'woocommerce'),
        'id'       =>'_invoice_name',
        'required' => false,
    ));
    woocommerce_form_field( '_invoice_street', array(
        'type'     => 'text',
        'class'    => array('input-text'),
        'label'    => __('Ulica', 'woocommerce'),
        'id'       =>'_invoice_street',
        'required' => false,
    ));
    woocommerce_form_field( '_invoice_city', array(
        'type'     => 'text',
        'class'    => array('input-text'),
        'label'    => __('Miasto', 'woocommerce'),
        'id'       =>'_invoice_city',
        'required' => false,
    ));
    woocommerce_form_field( '_invoice_zip_code', array(
        'type'     => 'text',
        'class'    => array('input-text'),
        'label'    => __('Kod pocztowy', 'woocommerce'),
        'id'       =>'_invoice_zip_code',
        'required' => false,
    ));
        woocommerce_form_field( '_invoice_nip', array(
            'type'     => 'number',
            'class'    => array('input-text'),
            'label'    => __('Nip', 'woocommerce'),
            'id'       =>'_invoice_nip',
            'required' => false,
        ));
        echo '</div>';
    echo '</div>';
}
//validation for new fields 
add_action( 'woocommerce_after_checkout_validation', 'misha_validate_fname_lname', 10, 3 );
 
function misha_validate_fname_lname( $fields, $errors ){

    if(isset($_POST['_invoice_fv']))
        if ( !preg_match( '/^\d{2}-\d{3}$/', $_POST[ '_invoice_zip_code' ] ) ){ 
            $errors->add( 'validation', __('To nie jest poprawny format kodu pocztowego', 'woocommerce') );
        }
}

//save new fields 
add_action( 'woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta' );

function my_custom_checkout_field_update_order_meta( $order_id ) {
    if ( isset($_POST['_invoice_fv']) )  {
        $order = wc_get_order( $order_id );
        $order->update_meta_data( '_invoice_fv', sanitize_text_field( 1 ) );

        $order->update_meta_data( '_invoice_name', sanitize_text_field( $_POST['_invoice_name'] ) );
        $order->update_meta_data( '_invoice_street', sanitize_text_field( $_POST['_invoice_street'] ) );
        $order->update_meta_data( '_invoice_city', sanitize_text_field( $_POST['_invoice_city'] ) );
        $order->update_meta_data( '_invoice_zip_code', sanitize_text_field( $_POST['_invoice_zip_code'] ) );

        if($_POST['_invoice_nip']>0)
        {
            $order->update_meta_data( '_invoice_nip', sanitize_text_field( $_POST['_invoice_nip'] ) );
        }
        $order->save_meta_data();
    }
}

//show new fields in order_admin
add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );

function my_custom_checkout_field_display_admin_order_meta( $order ){
    $fv = esc_html( $order->get_meta( '_invoice_fv', true ) );
    $nip = esc_html( $order->get_meta( '_invoice_nip', true ) );
    if($fv)
    {
        echo '<p><strong>' . esc_html__( 'Faktura' ) . ':</strong> Tak</p>';
        echo '<p><strong>' . esc_html__( 'Nazwa' ) . ':</strong> '.$order->get_meta( '_invoice_name', true ).'</p>';
        echo '<p><strong>' . esc_html__( 'Ulica' ) . ':</strong> '.$order->get_meta( '_invoice_street', true ).'</p>';
        echo '<p><strong>' . esc_html__( 'Miasto' ) . ':</strong> '.$order->get_meta( '_invoice_city', true ).'</p>';
        echo '<p><strong>' . esc_html__( 'Kod pocztowy' ) . ':</strong> '.$order->get_meta( '_invoice_zip_code', true ).'</p>';
     
        if($nip)
        {
            echo '<p><strong>' . esc_html__( 'Nip' ) . ':</strong> ' . $nip . '</p>';
        }
    }
    else
    {
        echo '<p><strong>' . esc_html__( 'Faktura:' ) . '</strong> Nie</p>';
    }
}

add_action( 'wp_enqueue_scripts', 'css_for_fields', 10 );
function css_for_fields()
{
    ?>
    <style>
.faktura_view_off
{
    display:none;
}
.faktura_view_on
{
    display:block;
}
    </style>
<?php
}

add_action('wp_enqueue_scripts', 'js_for_fields', 25);
function js_for_fields()
{
    ?>
    <script type="text/javascript">
      
        window.onload=function(){
            jQuery(function($){
        let a = '#_invoice_name';
        let b = '#faktura_memory_block';
        
        $(a).change(function() {
            if ( $(this).prop('checked') === true ) {
                $(b).show(function(){
                    $(b).removeClass('faktura_view_off');
                    $(b).addClass('faktura_view_on');
                   
                });
            }
            else if ( $(this).prop('checked') !== true) {
                
                $(b).fadeOut(function(){
                    $(b).removeClass('faktura_view_on');
                    $(b).addClass('faktura_view_off')
                });
                $(b+' input').val('');
            }
        });
    });
   
}
    </script>
        <?php
}

//menu style

add_action( 'wp_enqueue_scripts', 'menu_styles', 15 );
function menu_styles()
{
    ?>
    <style>
  
    .dropdown-menu1>ul
    {
        width:600%!important;
        display: flex!important;
        flex-wrap: wrap!important;
    }
    .dropdown-menu1>ul>li
    {
        flex: 0 0 auto!important;
        width: 25%!important;
    }
    .dropdown-menu1>ul>li>a
    {
        white-space: wrap;
    }

    @media(max-width:750px)
    {
        .dropdown-menu1>ul
        {
            width:100%!important; 
        }
    }
        </style>
    <?php
}
