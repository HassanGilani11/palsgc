<tr>
	<td>
		<table>
			<tr>
				<td>
					<img src="<?php echo isset( $quote_values['image_url'] ) ? esc_url( $quote_values['image_url'] ) : ''; ?>"
						style="width:50px;height:50px;object-fit: cover;float: left; margin-right: 10px;" alt="">
				</td>
				<td>
					<p style="margin: 5px 0">
						<?php echo esc_html( $quote_values['title'] ); ?>
					</p>
				</td>
			</tr>
		</table>
	</td>
	<td>
	<?php if ( ( true !== $is_hide_price_enabled ) || ( 'quote-approved' === $order->get_status() ) ) { ?>
		<?php echo ( 0 !== $product_variation->get_price() && '' !==$product_variation->get_price() && '0' !== $product_variation->get_price() ) ? esc_html( number_format($product_variation->get_price() , 2) . ' ' . get_woocommerce_currency() ) : 0; ?>

		<?php } ?>
	</td>
	<td>
		<?php echo esc_html( $item->get_quantity() ); ?>
	</td>
	<td>
	<?php if ( ( true !== $is_hide_price_enabled ) || ( 'quote-approved' === $order->get_status() ) ) { ?>
		<?php echo ( 0 !== $item->get_subtotal() && '' !== $item->get_subtotal() && '0' !== $item->get_subtotal() ) ? esc_html( number_format( $item->get_subtotal() , 2) . ' ' . get_woocommerce_currency() ) : 0; ?>
	<?php } ?>
	</td>
</tr>
