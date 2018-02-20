(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

    jQuery(function () {
        var csvData = [];
        var distCsvData = [];
        var customerOrderData = []
		var $tableBodyEl = $('#csvDataTable').find('tbody');
		var $kmWSProductSelect = $('#kmWSProductSelect');
		var euroCurse = parseFloat($('#kmWSEuroCurse').val());
		var deliveryPrice = parseFloat($('#kmWSDeliveryPrice').val());
		var docsPreparePrice = parseFloat($('#kmWSDocsPreparePrice').val().replace(',', '.'));

		var select2Options = {
            ajax: {
                url: "admin-ajax.php",
                quietMillis: 250,
                data:
                    function (term) {
                        var unlistedProductsSku = [];
                        $.each($tableBodyEl.find('.data-model'), function () {
                            unlistedProductsSku.push($(this).text().trim());
                        });
                        return {
                            'action': 'km_wholesale_order_search_products',
                            'search_term': term,
                            'unlisted_products_sku': unlistedProductsSku
                        };
                    },
                results: function (data, page) {
                    var results = [];
                    for(var i = 0; i < data.length; i++){
                        results.push(mapProductData(data[i]));
                    }
                    return { results: results };
                },
                width: '150px'
            }
        };
		$kmWSProductSelect.select2(select2Options);
		$('.expand-collapse-btn').click(function () {
		    var $button = $(this);
            var $target = $button.next();
            $target.toggle();
            $button.attr('data-is-expand', $target.is(":visible"));
        });

        var reCalcPrices = function () {
            var $modelCellEls = $tableBodyEl.find('.data-model');
            $.each($modelCellEls, function () {
                var price = $(this).closest('tr').find('.init-price').text();
                var weight = $(this).closest('tr').find('.product-weight').text();
                if(weight == '' || weight == 0){
                    $(this).parent().find('.data-csv-price').text('');
                }
                else {
                    if (!$(this).parent().find('.data-input-csv-price').hasClass('filled') && $(this).parent().find('.data-input-csv-price').val().trim() === ''){
                        for (var i = 0; i < csvData.length; i++) {
                            if ($(this).text().trim() === csvData[i][0].trim()) {
                                $(this).parent().find('.data-input-csv-price').val(parseFloat(csvData[i][1].toString().replace(',', '.')).toFixed(2));
                                $(this).parent().find('.data-input-csv-price').addClass('filled');
                                break;
                            }
                        }
                    }
                    $(this).parent().find('.data-csv-price').text(((parseFloat($(this).parent().find('.data-input-csv-price').val()) + deliveryPrice * parseFloat(weight)) * euroCurse * (1 + docsPreparePrice / 100)).toFixed(2));
                    for (var j = 0; j < distCsvData.length; j++) {
                        if ($(this).text().trim() === distCsvData[j][0].trim()) {
                            $(this).parent().find('.km-ws-product-price').text(parseFloat(distCsvData[j][3].toString().replace(' ', '').replace(',', '.')).toFixed(2));
                            break;
                        }
                    }
                }
                reCalcDiffs($(this).closest('tr'));
            });
        };

        $('#uploadFileWithPrices').submit(function (e) {
            e.preventDefault();
            getCsvDataFromForm($(this), function (data) {
                csvData = data;
            });
        });

        $('#uploadFileWithDistPrices').submit(function (e) {
            e.preventDefault();
            getCsvDataFromForm($(this), function (data) {
                distCsvData = data;
            });
        });

        $('#uploadCustomerOrder').submit(function (e) {
            e.preventDefault();
            getCsvDataFromForm($(this), function (data) {
                customerOrderData = data;
                addCustomerOrderProducts();
            })
        });

        function addCustomerOrderProducts() {
            var orderData = customerOrderData.slice(1);
            clearLog();
            $.each(orderData, function () {
                var row = this;
                var vendorCode = this[0];
                var modelWithName = this[1];
                var modelName = modelWithName.split(',')[1] ? modelWithName.split(',')[1].trim() : '';
                var count = this[2];
                var price = this[3];
                var $foundedRowEl = null;
                var foundBy = '';
                $.each($tableBodyEl.find('tr'), function () {
                    if($(this).find('.data-model').text().trim() === vendorCode.trim() || $(this).find('.product-model').text().trim() === modelName) {
                        $foundedRowEl = $(this).closest('tr');
                        foundBy = $(this).find('.data-model').text().trim() === vendorCode.trim() ? 'артикулу' : 'модели';
                    }
                });
                if($foundedRowEl) {
                    $foundedRowEl.find('.km-ws-product-quantity').val(count);
                    $foundedRowEl.find('.init-price').val(price);
                    //addLog(row.join(',') + ' Уже есть в списке. Найден по ' + foundBy + '. Обновлено кол-во');
                    reCalcPrices();
                }
                else {
                    loadingStart();
                    if (vendorCode.trim() === "")
                        addByModel();
                    else {
                        $.get('admin-ajax.php', {
                            'action': 'km_wholesale_order_search_products',
                            'search_term': vendorCode.trim(),
                            'unlisted_products_sku': []
                        }).done(function (data) {
                            if (data.length) {
                                var mappedData = mapProductData(data[0]);
                                mappedData.count = count;
                                mappedData.price = price;
                                //addLog(row.join(',') + ' Найден по артикулу.');
                                addNewItemToTable(mappedData);
                            }
                            else
                                addByModel();
                        });
                    }
                }

                function addByModel() {
                    if(modelName) {
                        $.get('admin-ajax.php', {
                            'action': 'km_wholesale_order_search_products',
                            'search_term': modelName,
                            'unlisted_products_sku': []
                        }).done(function (data) {
                            if (data.length) {
                                var mappedData = mapProductData(data[0]);
                                mappedData.count = count;
                                mappedData.price = price;
                                //addLog(row.join(',') + ' Найден по модели.');
                                addNewItemToTable(mappedData);
                            }
                            else
                                productNotFound();
                        });
                    }
                    else
                        productNotFound();
                }

                $(document).ajaxStop(function() {
                    loadingDone();
                });
                function productNotFound() {
                    addLog(row.join(',') + ' Продукт не найден: ' + modelWithName);
                }
            });

            function loadingDone(){
                $('#uploadCustomerOrder').find('.message').text('Файл загружен');
            }
            function loadingStart() {
                $('#uploadCustomerOrder').find('.message').text('Обработка...');
            }

            function clearLog() {
                $('#importOrderLog').html('');
            }

            function addLog(message) {
                $('#importOrderLog').append(message + '<br>');
            }
        }

        function addNewItemToTable(data) {
            toggleProductOptionDisable(true, data.sku);
            var foundedProductsInCsvData = $.grep(csvData, function (item) {
                return item[0].toString().trim() === data.sku;
            });
            var foundedProductsInDistCsvData = $.grep(distCsvData, function (item) {
                return item[0].toString().trim() === data.sku;
            });

            var inputCsvPrice = foundedProductsInCsvData.length > 0 ? parseFloat(foundedProductsInCsvData[0][1].toString().trim()) : '';
            var distCsvPrice = foundedProductsInDistCsvData.length > 0 ? parseFloat(foundedProductsInDistCsvData[0][3].replace(' ', '')) : '';
            $tableBodyEl.append('<tr>' +
                '<td style="display: none" class="init-price">' + parseFloat(data.price) + '</td>' +
                '<td style="display: none" class="product-weight">' + data.weight + '</td>' +
                '<td style="display: none" class="product-model">' + data.model + '</td>' +
                '<td class="data-model">' + data.sku + '</td>' +
                '<td>' + data.name + '</td>' +
                '<td><input type="text" class="data-input-csv-price"></td>' +
                '<td class="data-csv-price"></td>' +
                '<td class="km-ws-product-price">' + distCsvPrice + '</td>' +
                '<td><input type="number" value="'+data.count+'" min="0" class="km-ws-product-quantity"></td>' +
                '<td class="km-ws-price-diff-percent"></td>' +
                '<td class="km-ws-price-diff"></td>' +
                '<td><button class="km-ws-remove-product button">Удалить</button></td>' +
                '</tr>');
            $kmWSProductSelect.select2(select2Options).val(null).trigger("change");
            rebindTableRowEvents();
            reCalcPrices();
            reCalcTotal();
        }

        $('#kmWSPAddProductBtn').click(function () {
            var selectedData = $kmWSProductSelect.select2("data");
            addNewItemToTable(selectedData);
        });

        $('.km-ws-product-quantity').bind('keyup mouseup', function () {
            reCalcDiffs($(this).closest('tr'));
        });

        function rebindTableRowEvents() {
            $('.km-ws-remove-product').click(function () {
                toggleProductOptionDisable(false, $(this).closest('tr').find('.data-model').text());
                $(this).closest('tr').remove();
                reCalcTotal();
            });
            $('.km-ws-product-quantity').bind('keyup mouseup', function () {
                reCalcDiffs($(this).closest('tr'));
            });
            $('.data-input-csv-price').bind('keyup mouseup', function () {
                reCalcPrices();
                reCalcDiffs($(this).closest('tr'));
            });
        }

        function reCalcTotal() {
        	var total = 0;
        	var $priceEls = $tableBodyEl.find('.km-ws-product-price');
        	for(var i = 0; i < $priceEls.length; i++) {
        		total += parseFloat($($priceEls[i]).text());
			}
			$('#kmWSPProductTotal').text(total);
        }

        function reCalcDiffs($row) {
            var weight = $row.find('.product-weight').text();
            if(weight == '' || weight == 0){
                $row.find('.km-ws-price-diff').text('-');
                $row.find('.km-ws-price-diff-percent').text('-');
                return;
            }
            var csvPrice = parseFloat($row.find('.data-csv-price').text().trim());
            var price = parseFloat($row.find('.km-ws-product-price').text().trim());
            var quantity = parseInt($row.find('.km-ws-product-quantity').val());
            var priceDiff = ((price - csvPrice) * quantity);
            var priceDiffPercent = (price/csvPrice*100);
            $row.find('.km-ws-price-diff').text((isNaN(priceDiff) ? '' : priceDiff.toFixed(2)));
            $row.find('.km-ws-price-diff-percent').text(isNaN(priceDiffPercent) ? '' : priceDiffPercent.toFixed(2));
        }

        function toggleProductOptionDisable(isDisabled, model) {
        }

        $('#kmWSPSaveBtn').click(saveToOneS);
        function saveToOneS() {
            var data = [];
            $.each($tableBodyEl.find('tr'), function () {
                data.push({
                    sku: $(this).find('.data-model').text().trim(),
                    price: (parseFloat($(this).find('.data-csv-price').text().trim())).toFixed(2),
                    quantity: $(this).find('.km-ws-product-quantity').val()
                });
            });
            $.post('admin-ajax.php', {
                'action': 'km_wholesale_order_save_to_one_s',
                'data': data,
                'euro_curse': $('#kmWSEuroCurse').val(),
                'delivery_price': $('#kmWSDeliveryPrice').val(),
                'docs_prepare_price': $('#kmWSDocsPreparePrice').val()
            }, function () {
                window.location.href = 'tools.php?page=wholesale-order';
            });
        }

        $('#kmWSSaveSettings').click(saveSettings);
        function saveSettings() {
            euroCurse = parseFloat($('#kmWSEuroCurse').val());
            deliveryPrice = parseFloat($('#kmWSDeliveryPrice').val());
            docsPreparePrice = parseFloat($('#kmWSDocsPreparePrice').val().replace(',', '.'));
            reCalcPrices();
        }
        rebindTableRowEvents();

        $('#kmWSPExportCsv').click(function () {
            var exportCsv = [];
            exportCsv.push([
                'Артикул',
                'Наименование',
                'Цена в Евро (за штуку)',
                'Цена доставки В Евро (за штуку)',
                'Кол-во',
                'Стоимость доставки в Евро',
                'Стоимость общая в Евро',
                'Стоимость общая в Евро с учетом доставки'
            ]);
            $.each($tableBodyEl.find('tr'), function () {

                var weight = $(this).closest('tr').find('.product-weight').text();

                if(weight == '' || weight == 0){
                    return;
                }

                var model = $(this).find('.data-model').text();
                var dataCsvPrice = $(this).closest('tr').find('.data-input-csv-price').val().trim();
                dataCsvPrice = dataCsvPrice.trim() === '' ? 0 : parseFloat(dataCsvPrice.replace(',', '.').trim());

                if(dataCsvPrice === 0)
                    return;

                var count = $(this).find('.km-ws-product-quantity').val();
                var totalPrice =  count * dataCsvPrice;
                exportCsv.push([
                    model,
                    $(this).find('.data-model').next().text(),
                    dataCsvPrice,
                    parseFloat(weight) * deliveryPrice,
                    count,
                    parseFloat(weight) * deliveryPrice * count,
                    totalPrice,
                    totalPrice + parseFloat(weight) * deliveryPrice * count
                ]);
            });

            var csvContent = "data:text/csv;charset=utf-8,";

            $.each(exportCsv, function(index, value){
                var dataString = value.join(",");
                csvContent += index < exportCsv.length ? dataString+ "\n" : dataString;
            });

            var encodedUri = encodeURI(csvContent);
            var link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "my_data.csv");
            document.body.appendChild(link); // Required for FF

            link.click();

        });
        function getCsvDataFromForm(form, callback) {
            var fileReader = new FileReader();
            fileReader.onload = function (e) {
                if (e.target.error) {
                    console.log('Error while reading file');
                    return;
                }

                CSV.COLUMN_SEPARATOR = ';';
                CSV.DETECT_TYPES = false;
                callback(CSV.parse(e.target.result));
                form.find('.message').show();
                reCalcPrices();
            };
            fileReader.readAsText(form[0].file.files[0]);
        }

        function mapProductData(data) {
            return {
                id: data.sku,
                text: '(' + data.model + ') ' + data.sku,
                sku: data.sku,
                name: data.name,
                price: data.price,
                weight: data.weight,
                count: data.count ? data.count : 1,
                model: data.model
            }
        }

    });

})( jQuery );
