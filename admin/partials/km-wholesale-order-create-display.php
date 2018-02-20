<div class="wrap">
    <h1 class="wp-heading-inline">Оптовый заказ - Создание</h1>
    <a href="/wp-admin/tools.php?page=wholesale-order" class="page-title-action">К списку заказов</a>
    <hr class="wp-header-end">
    <div class="km-ws-header-panels">
        <div class="km-ws-panel-left">
            <div class="panel postbox">
                <h2>Загрузка оптового прайса (CSV)</h2>
                <div class="forms">
                    <form id="uploadFileWithPrices" action="#">
                        <label for="fileWithPrices">Файл с ценами в евро</label><br><br>
                        <input id="fileWithPrices" type="file" name="file" accept="text/csv"><br><br>
                        <input type="submit" value="Загрузить" class="button button-primary">
                        <span class="message" style="display: none">Файл загружен</span>
                    </form>
                    <form id="uploadFileWithDistPrices" action="#">
                        <label for="fileWithDistPrices">Файл с ценами дистрибьютора</label><br><br>
                        <input id="fileWithDistPrices" type="file" name="file" accept="text/csv"><br><br>
                        <input type="submit" value="Загрузить" class="button button-primary">
                        <span class="message" style="display: none">Файл загружен</span>
                    </form>
                    <form id="uploadCustomerOrder" action="#">
                        <label for="fileCustomerOrder">Добавить номенклатуру из CSV</label><br><br>
                        <input id="fileCustomerOrder" type="file" name="file" accept="text/csv"><br><br>
                        <input type="submit" value="Загрузить" class="button button-primary">
                        <span class="message" style="display: none">Обработка...</span>
                    </form>
                </div>
            </div>
        </div>
        <div class="km-ws-panel-right">
            <div class="panel postbox">
                <h2>Параметры</h2>
                <label for="kmWSEuroCurse">Курс Евро</label><br>
                <input id="kmWSEuroCurse" type="text" value="<?php echo $euro_curse; ?>"><br><br>
                <label for="kmWSDeliveryPrice">Стоимость доставки</label><br>
                <input id="kmWSDeliveryPrice" type="text" value="<?php echo $delivery_price; ?>"><br><br>
                <label for="kmWSDocsPreparePrice">Оформление документов (% от общей стоимости)</label><br>
                <input id="kmWSDocsPreparePrice" type="text" value="<?php echo $docs_prepare_price; ?>"><br><br>
                <button id="kmWSSaveSettings" type="button" class="button button-primary">Применить настройки</button>
            </div>
        </div>
    </div>
    <div class="postbox">
        <table id="csvDataTable">
            <thead>
            <tr>
                <th>Артикул</th>
                <th>Наименование</th>
                <th>Цена входящая (в Евро)</th>
                <th>Цена входящая итоговая</th>
                <th>Цена Россия</th>
                <th>Кол-во</th>
                <th>Разница в %</th>
                <th>Разница в рублях</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($wc_products as $wc_product): ?>
                <tr>
                    <td style="display: none" class="init-price"></td>
                    <td style="display: none" class="product-weight"><?php echo $wc_product['product']->get_weight(); ?></td>
                    <td style="display: none" class="product-model"><?php echo get_post_meta($wc_product['product']->get_id(), '_custom_product_model', true); ?></td>
                    <td class="data-model"><?php echo $wc_product['product']->get_sku(); ?></td>
                    <td><?php echo $wc_product['product']->get_name(); ?></td>
                    <td><input type="text" class="data-input-csv-price"></td>
                    <td class="data-csv-price"></td>
                    <td class="km-ws-product-price"></td>
                    <td><input type="number" class="km-ws-product-quantity" min="0" value="<?php echo $wc_product['quantity']; ?>"></td>
                    <td class="km-ws-price-diff-percent"></td>
                    <td class="km-ws-price-diff"></td>
                    <td><button class="km-ws-remove-product button">Удалить</button></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
<!--            <tr>-->
<!--                <td colspan="5">Итого:</td>-->
<!--                <td id="kmWSPProductTotal">--><?php //echo $total; ?><!--</td>-->
<!--                <td></td>-->
<!--            </tr>-->
            <tr>
                <td colspan="6">
                    <label for="kmWSProductSelect">Выберите товар:</label>
                    <input type="hidden" id="kmWSProductSelect">
                    <button id="kmWSPAddProductBtn" class="button button-primary">Добавить товар</button>
                </td>
                <td>
                    <button id="kmWSPExportCsv" class="button button-primary">Экспорт в excel</button>
                </td>
                <td>
                    <button id="kmWSPSaveBtn" class="button button-primary">Отправить в 1С</button>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
    <div id="importOrderLog"></div>
</div>
