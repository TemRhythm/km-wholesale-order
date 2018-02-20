<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Km_Wholesale_Order
 * @subpackage Km_Wholesale_Order/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <h1 class="wp-heading-inline">Оптовый заказ</h1>
    <a href="/wp-admin/tools.php?page=wholesale-order&create=" class="page-title-action">Создать заказ</a>
    <hr class="wp-header-end">
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>№ Заказа</th>
                <th>Дата и время заказа</th>
                <th>Контрагент</th>
                <th>Товары</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?php echo $order->Id; ?></td>
                <td><?php echo date('d-m-Y H:i', strtotime($order->Date)); ?></td>
                <td><?php echo $order->PartnerName; ?></td>
                <td>
                    <button data-is-expand="false" class="expand-collapse-btn">Товары</button>
                    <div class="expand-collapse-item" style="display: none;">
                        <table class="product-table">
                            <thead>
                                <tr>
                                    <th>Модель</th>
                                    <th>Цена</th>
                                    <th>Кол-во</th>
                                    <th>Стоимость</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($order->WProduct as $product): ?>
                                <tr>
                                    <td><?php echo $product->Sku; ?></td>
                                    <td><?php echo number_format($product->Price, 2); ?> руб.</td>
                                    <td><?php echo $product->Quantity; ?></td>
                                    <td><?php echo number_format($product->Price * $product->Quantity, 2); ?> руб.</td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </td>
                <td>
                    <a href="/wp-admin/tools.php?page=wholesale-order&create=<?php echo $order->Id; ?>" title="Будет создан заказ на основе этого заказа">Копировать</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


