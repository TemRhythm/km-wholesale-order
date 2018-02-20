<?php

//function km_wholesale_order_menu(){
//	add_submenu_page(
//		'tools.php',
//		'Оптовый заказ',
//		'Оптовый заказ',
//		'manage_options',
//		'wholesale-order',
//		'km_wholesale_order_page');
//}
//
//add_action('admin_menu', 'km_wholesale_order_menu');

function km_wholesale_order_page() {
?>
	<div id="woocommerce-order-items" class="postbox">
		<div class="inside">
			<div class="woocommerce_order_items_wrapper wc-order-items-editable">
				<table cellpadding="0" cellspacing="0" class="woocommerce_order_items">
					<thead>
                    <?php get_template_part('templates','order_item'); ?>
					</thead>
					<tbody id="order_line_items">
					<tr class="item new_row" data-order_item_id="1">
						<td class="thumb">
							<div class="wc-order-item-thumbnail"><img width="150" height="150" src="8080/wp-content/uploads/2017/09/cap-150x150.jpg" class="attachment-thumbnail size-thumbnail wp-post-image" alt="" title=""></div>	</td>
						<td class="name" data-sort-value="Cap">
							<a href="http://localhost:8080/wp-admin/post.php?post=28&amp;action=edit" class="wc-order-item-name">Cap</a>		<input type="hidden" class="order_item_id" name="order_item_id[]" value="1">
							<input type="hidden" name="order_item_tax_class[1]" value="">

							<div class="view" style="display: none;">
							</div>
							<div class="edit" style="">
								<table class="meta" cellspacing="0">
									<tbody class="meta_items">
									</tbody>
									<tfoot>
									<tr>
										<td colspan="4"><button class="add_order_item_meta button">Добавить&nbsp;мета</button></td>
									</tr>
									</tfoot>
								</table>
							</div>
						</td>


						<td class="item_cost" width="1%" data-sort-value="16.00">
							<div class="view" style="display: none;">
								<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">₽</span>16.00</span>		</div>
						</td>
						<td class="quantity" width="1%">
							<div class="view" style="display: none;">
								<small class="times">×</small> 1		</div>
							<div class="edit" style="">
								<input type="number" step="1" min="0" autocomplete="off" name="order_item_qty[1]" placeholder="0" value="1" data-qty="1" size="4" class="quantity">
							</div>
							<div class="refund" style="display: none;">
								<input type="number" step="1" min="0" max="1" autocomplete="off" name="refund_order_item_qty[1]" placeholder="0" size="4" class="refund_order_item_qty">
							</div>
						</td>
						<td class="line_cost" width="1%" data-sort-value="16">
							<div class="view" style="display: none;">
								<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">₽</span>16.00</span>		</div>
							<div class="edit" style="">
								<div class="split-input">
									<div class="input">
										<label>Пред-скидка:</label>
										<input type="text" name="line_subtotal[1]" placeholder="0" value="16" class="line_subtotal wc_input_price" data-subtotal="16">
									</div>
									<div class="input">
										<label>Всего:</label>
										<input type="text" name="line_total[1]" placeholder="0" value="16" class="line_total wc_input_price" data-tip="После расчета скидок на налог." data-total="16">
									</div>
								</div>
							</div>
							<div class="refund" style="display: none;">
								<input type="text" name="refund_line_total[1]" placeholder="0" class="refund_line_total wc_input_price">
							</div>
						</td>

						<td class="wc-order-edit-line-item" width="1%">
							<div class="wc-order-edit-line-item-actions">
								<a class="edit-order-item tips" href="#" style="display: none;"></a><a class="delete-order-item tips" href="#"></a>
							</div>
						</td>
					</tr>
					</tbody>
					<tbody id="order_shipping_line_items">
					</tbody>
					<tbody id="order_fee_line_items">
					</tbody>
					<tbody id="order_refunds">
					</tbody>
				</table>
			</div>
		</div>
	</div>
<?php
}