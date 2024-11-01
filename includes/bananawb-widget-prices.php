<?php
// Register and load the widget "one"
function bananawb_load_widget_prices() {
    register_widget( 'bananawb_widget_prices' );
}

add_action( 'widgets_init', 'bananawb_load_widget_prices' );
 
// Creating the widget 
class bananawb_widget_prices extends WP_Widget {
 
function __construct() {
	parent::__construct(
		 
		// Base ID of your widget
		'bananawb_widget_prices',
		 
		// Widget name will appear in UI
		__('Bitcoin Quotes Widget', 'bananawb_widget_prices_domain'),
		 
		// Widget description
		array( 'description' => __( 'Quotes for BTC to USD(EUR,RUR,UAH) widget', 'bananawb_widget_prices_domain' ), )
	);
}
 
// Creating widget front-end
 
public function widget( $args, $instance ) {
	$title = apply_filters( 'widget_title', $instance['title'] );
	if($instance['quantity'] === 'one') {
        $currencies = $instance['currency'];
	} else {
        $currencies = ['USD', 'EUR', 'RUB', 'UAH'];
	}

	$copyright = $instance['copyright'];
	$copyright_div = '';

    // before and after widget arguments are defined by themes
    echo $args['before_widget'];
    if ( ! empty( $title ) )
//        echo $args['before_title'] . $title . $args['after_title'];

    echo '<div id="index-quotes-widget" class="widget-prices'.($instance["style"]==="vertical" ? " widget-prices-vertical" : " widget-prices-horizontal").' '.($instance["quantity"]==="many" ? " widget-prices-many" : " widget-prices-solo").'">';

    if ($copyright == 'show') {
        $copyright_div = '<div class="heading-copyright"><a href="https://www.itez.com" target="_blank" title="Buy bitcoin with Itez">Powered by <div class="heading-logo"></div></a></div>';
    } else {
        $copyright_div = '';
    }

    //Service for interaction with bitcoins
    $url = 'https://api.itez.com/api/frame/v1/public/exchange';

    if($instance['quantity'] === 'many') {
        foreach ($currencies as $currency) {
            $data = array("fromCurrency" => $currency,"toCurrency" => "btc","toAmount"=>1);


            $req = wp_remote_post($url, array(
                    'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
                    'body'        => json_encode($data),
                    'method'      => 'POST',
                    'data_format' => 'body',
                )
            );

            $result = json_decode(wp_remote_retrieve_body($req), true);

            $date =  date("d.m.Y H:i:s"); // Current date/time

            echo __( '<div id="widget-prices-container"><div class="heading-one">1 BTC =</div><div class="heading-two">'.$result['data']['fromAmount'].' '.$currency.'</div><div class="heading-three"></div></div>', 'wb2_widget_prices_domain' );

        }
    } else {
        $data = array("fromCurrency" => $currencies,"toCurrency" => "btc","toAmount"=>1);


        $req = wp_remote_post($url, array(
                'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
                'body'        => json_encode($data),
                'method'      => 'POST',
                'data_format' => 'body',
            )
        );

        $result = json_decode(wp_remote_retrieve_body($req), true);

        $date =  date("d.m.Y H:i:s"); // Current date/time

        echo __( '<div id="widget-prices-container"><div class="heading-one">1 BTC =</div><div class="heading-two">'.$result['data']['fromAmount'].' '.$currencies.'</div><div class="heading-three"></div></div>', 'wb2_widget_prices_domain' );

    }

    echo '</div>';
    echo '<div class="widget-prices-info"><div class="widget-prices-date">'.$date.'</div><div class="widget-prices-copyright">'.$copyright_div.'</div></div>';

    echo $args['after_widget'];
}

// Widget Backend 
public function form( $instance ) {
	if ( isset( $instance[ 'title' ] ) ) {
		$title = $instance[ 'title' ];
	} else {
		$title = __( 'New title', 'bananawb_widget_prices_domain' );
	}
	if ( isset( $instance[ 'currency' ] ) ) {
		$currency = $instance[ 'currency' ];
	} else {
		$currency = __( 'USD', 'bananawb_widget_prices_domain' );
	}

	if ( isset( $instance[ 'copyright' ] ) ) {
		$copyright = $instance[ 'copyright' ];
	} else {
		$copyright = __( 'hide', 'bananawb_widget_prices_domain' );
	}

    if ( isset( $instance[ 'quantity' ] ) ) {
        $quantity = $instance[ 'quantity' ];
    } else {
        $quantity = __( 'Quantity', 'bananawb_widget_prices_domain' );
    }

    if ( isset( $instance[ 'style' ] ) ) {
        $style = $instance[ 'style' ];
    } else {
        $style = __( 'Style', 'bananawb_widget_prices_domain' );
    }
	// Widget admin form
	?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'quantity' ); ?>"><?php _e( 'Quantity of elements' ); ?></label>
		<select id="<?php echo $this->get_field_id( 'quantity' ); ?>" name="<?php echo $this->get_field_name( 'quantity' ); ?>" >
			<option value="one" <?php if (esc_attr( $quantity ) == 'one') { echo "selected"; } ?>>One currency</option>
			<option value="many" <?php if (esc_attr( $quantity ) == 'many') { echo "selected"; } ?>>Many currencies</option>
		</select>
	</p>
	<p id="bananawb_widget_currency" style="<?php if (esc_attr( $quantity ) === 'many') echo 'display:none'?>">
	<label for="<?php echo $this->get_field_id( 'currency' ); ?>"><?php _e( 'From currency:' ); ?></label>
		<select id="<?php echo $this->get_field_id( 'currency' ); ?>" name="<?php echo $this->get_field_name( 'currency' ); ?>" >
		  <option value="USD" <?php if (esc_attr( $currency ) == 'USD') { echo "selected"; } ?>>US Dollar</option>
		  <option value="EUR" <?php if (esc_attr( $currency ) == 'EUR') { echo "selected"; } ?>>EURO</option>
		  <option value="RUB" <?php if (esc_attr( $currency ) == 'RUB') { echo "selected"; } ?>>Russian Ruble</option>
		  <option value="UAH" <?php if (esc_attr( $currency ) == 'UAH') { echo "selected"; } ?>>Ukrainian Hryvnia</option>
		</select>
	</p>
	<p id="bananawb_widget_style" style="<?php if (esc_attr( $quantity ) === 'one') echo 'display:none'?>">
		<label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php _e( 'Style of showing' ); ?></label>
		<select id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>" >
			<option value="vertical" <?php if (esc_attr( $style ) == 'vertical') { echo "selected"; } ?>>Vertical</option>
			<option value="horizontal" <?php if (esc_attr( $style ) == 'horizontal') { echo "selected"; } ?>>Horizontal</option>
		</select>
	</p>
	<p>
	<label for="<?php echo $this->get_field_id( 'copyright' ); ?>"><?php _e( 'Support project :) Developer link:' ); ?></label> 
		<select id="<?php echo $this->get_field_id( 'copyright' ); ?>" name="<?php echo $this->get_field_name( 'copyright' ); ?>" >
		  <option value="show" <?php if (esc_attr( $copyright ) == 'show') { echo "selected"; } ?>>Show</option>
		  <option value="hide" <?php if (esc_attr( $copyright ) == 'hide') { echo "selected"; } ?>>Hide</option>
		</select>
	</p>
	<script>
        document.getElementById("widget-bananawb_widget_prices-2-quantity").onchange = function () {
            if(this.value === 'many') {
                document.querySelector("#widgets-right #bananawb_widget_currency").style.display = "none";
                document.querySelector("#widgets-right #bananawb_widget_style").style.display = "block";

            } else {
                document.querySelector("#widgets-right #bananawb_widget_currency").style.display = "block";
                document.querySelector("#widgets-right #bananawb_widget_style").style.display = "none";
            }
        }
	</script>
	<?php 
}
     
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
	$instance = array();
	$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
	$instance['currency'] = ( ! empty( $new_instance['currency'] ) ) ? strip_tags( $new_instance['currency'] ) : '';
	$instance['copyright'] = ( ! empty( $new_instance['copyright'] ) ) ? strip_tags( $new_instance['copyright'] ) : '';
    $instance['quantity'] = ( ! empty( $new_instance['quantity'] ) ) ? strip_tags( $new_instance['quantity'] ) : '';
    $instance['style'] = ( ! empty( $new_instance['style'] ) ) ? strip_tags( $new_instance['style'] ) : '';
		return $instance;
	}
} // Class bananawb_widget_prices ends here

function bananawb_script_set_prices() {
?>
	<script type="text/javascript">
		// ajax quotes
	</script>
<?php
}
add_action( 'wp_footer', 'bananawb_script_set_prices' );