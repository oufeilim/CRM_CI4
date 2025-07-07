<div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?= $mode ?> Sales Order</h1>
    </div>

    <form ng-controller="salesOrderFormCtrl" name="salesOrderForm" ng-submit="submitForm()" novalidate>
        <input ng-model="mode" type="hidden" name="mode">
        <input ng-model="id" type="hidden" name="id">

        <div class="form-group my-2">
            <label class="form-label" for="order_date">Order date</label>
            <input ng-model="order_date" name="order_date" id="order_date" type="text" class="form-control">
        </div>
        
        <div class="row">
            <div class="col-2 form-group my-2">
                <label class="form-label" for="total_amount">Total amount</label>
                <input ng-model="total_amount" name="total_amount" id="total_amount" type="text" class="form-control" price-input disabled required>
            </div>

            <div class="col-2 form-group my-2">
                <label class="form-label" for="discount_amount">Discount amount</label>
                <input ng-model="discount_amount" name="discount_amount" id="discount_amount" type="text" class="form-control" price-input readonly required>
            </div>

            <div class="col-2 form-group my-2">
                <label class="form-label" for="total_weight">Total Weight</label>
                <input ng-model="total_weight" name="total_weight" id="total_weight" type="text" class="form-control" readonly disabled required>
            </div>

            <div class="col-2 form-group my-2">
                <label class="form-label" for="service">Service</label>
                <select class="form-control" name="service" id="service" ng-model="service" ng-options="service.service_id as service.title for service in serviceList">
                </select>
            </div>

            <div class="col-2 form-group my-2">
                <label class="form-label" for="shipping_fee">Shipping Fee</label>
                <input ng-model="shipping_fee" name="shipping_fee" id="shipping_fee" type="text" class="form-control" price-input required>
            </div>

            <div class="col-2 form-group my-2">
                <label class="form-label" for="final_amount">Final amount</label>
                <input ng-model="final_amount" name="final_amount" id="final_amount" type="text" class="form-control" price-input disabled required>
            </div>
        </div>

        <div class="row">
            <div class="col-3 form-group my-2">
                <label class="form-label" for="user">User</label>
                <select class="form-control" ng-model="user" name="user" id="user" ng-options="user.user_id as user.name for user in userList" ng-change="onUserChange()" required>
                    <option value="" disabled>-- Select User --</option>
                </select>
                <div ng-messages="salesOrderForm.user.$error" ng-if="submitted" class="text-danger">
                    <div ng-message="required">User is required.</div>
                </div>
            </div>


            <div class="col-3 form-group my-2">
                <label class="form-label" for="user_name">User name</label>
                <input ng-model="user_name" name="user_name" id="user_name" type="text" class="form-control" required>
                <div ng-messages="salesOrderForm.user.$error" ng-if="submitted" class="text-danger">
                    <div ng-message="required">User is required.</div>
                </div>
            </div>

            <div class="col-3 form-group my-2">
                <label class="form-label" for="user_email">User email</label>
                <input ng-model="user_email" name="user_email" id="user_email" type="text" class="form-control" required>
                <div ng-messages="salesOrderForm.user.$error" ng-if="submitted" class="text-danger">
                    <div ng-message="required">User is required.</div>
                </div>
            </div>

            <div class="col-3 form-group my-2">
                <label class="form-label" for="user_contact">Contact Number</label>
                <input ng-model="user_contact" name="user_contact" id="user_contact" type="text" class="form-control" required>
                <div ng-messages="salesOrderForm.user.$error" ng-if="submitted" class="text-danger">
                    <div ng-message="required">User is required.</div>
                </div>
            </div>
        </div>
        
        <div class="form-group my-2">
            <label class="form-label" for="user_addr">Address</label>
            <input ng-model="user_addr" name="user_addr" id="user_addr" type="text" class="form-control" required>
            <div ng-messages="salesOrderForm.user.$error" ng-if="submitted" class="text-danger">
                <div ng-message="required">User is required.</div>
            </div>
        </div>

        <div class="row">
            <div class="col-3 form-group my-2">
                <label class="form-label" for="order_status">Order status</label>
                <select class="form-control" ng-model="order_status" name="order_status" id="order_status" required>
                    <option value="" disabled>-- Select Order Status --</option>
                    <option value="0">Pending</option>
                    <option value="1">Success</option>
                    <option value="2">Cancel</option>
                </select>
                <div ng-messages="salesOrderForm.order_status.$error" ng-if="submitted" class="text-danger">
                    <div ng-message="required">Order Status is required.</div>
                </div>
            </div>

            <div class="col-3 form-group my-2">
                <label class="form-label" for="payment_status">Payment status</label>
                <select class="form-control" ng-model="payment_status" name="payment_status" id="payment_status" required>
                    <option value="" disabled>-- Select Payment Status --</option>
                    <option value="0">Pending</option>
                    <option value="1">Paid</option>
                    <option value="2">Failed</option>
                </select>
                <div ng-messages="salesOrderForm.payment_status.$error" ng-if="submitted" class="text-danger">
                    <div ng-message="required">Payment Status is required.</div>
                </div>
            </div>

            <div class="col-3 form-group my-2">
                <label class="form-label" for="payment_date">Payment date</label>
                <input ng-model="payment_date" name="payment_date" id="payment_date" type="text" class="form-control">
            </div>

            <div class="col-3 form-group my-2">
                <label class="form-label" for="payment_method">Payment method</label>
                <select class="form-control" ng-model="payment_method" name="payment_method" id="payment_method" required>
                    <option value="" disabled>-- Select Payment Method --</option>
                    <option value="0">Bank Transfer</option>
                    <option value="1">Payment Gateway</option>
                    <option value="2">E-wallet</option>
                </select>
                <div ng-messages="salesOrderForm.payment_method.$error" ng-if="submitted" class="text-danger">
                    <div ng-message="required">Payment method is required.</div>
                </div>
            </div>
        </div>

        <div class="form-group my-2">
            <label class="form-label" for="admin_remark">Admin remark</label>
            <textarea ng-model="admin_remark" class="form-control" name="admin_remark" id="admin_remark"></textarea>
        </div>

        <hr />

        <input class="btn btn-sm btn-primary my-2" type="submit" value="<?= $mode ?>" />

        <hr />

        <div class="form-group my-4">
            <label class="form-label">Sales Order Detail</label>
            
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 30%;">Product</th>
                        <th style="width: 10%;">Quantity</th>
                        <th style="width: 10%;">Price</th>
                        <th style="width: 10%;">Weight</th>
                        <th style="width: 10%;">Total Weight</th>
                        <th style="width: 10%;">Total</th>
                        <th style="width: 10%;" class="text-center align-middle"><button type="button" class="btn btn-primary btn-sm" ng-click="addProductRow()">+</button></th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="item in productItems">
                        <td>
                            <select class="form-control" ng-model="item.product" name="item.product_id" id="item.product_id" ng-options="product as product.name disable when isProductSelected(product.product_id, $index) for product in productList track by product.product_id" ng-change="onProductSelect(item)" ng-attr-id="product_{{$index}}">
                            </select>
                            <input type="hidden" ng-model="item.product_image_url">
                            <input type="hidden" ng-model="item.product_name">
                        </td>
                        <td><input type="number" class="form-control" ng-model="item.product_qty" ng-change="updateItemTotal(item)" min="1"></td>
                        <td>{{ item.product_price | number:2 }}</td>
                        <td>{{ item.product_weight | number:2 }}</td>
                        <td>{{ item.product_qty * item.product_weight | number:2 }}</td>
                        <td>{{ item.total | number:2 }}</td>
                        <td class="text-center align-middle"><button type="button" class="btn btn-danger btn-sm" ng-click="removeProductRow($index)">×</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>

<script>
    angular.module('myApp').directive('priceInput', [ '$timeout', function($timeout) {
        return {
            restrict: 'A',
            require: 'ngModel',
            link: function(scope, element, attrs, ngModelCtrl) {
                let rawInput = '';
                let isTyping = false;

                function formatPrice(raw) {
                    if (!raw) return '0.00';
                    const padded = raw.padStart(3, '0');
                    const intPart = padded.slice(0, -2);
                    const decimalPart = padded.slice(-2);
                    return `${parseInt(intPart)}.${decimalPart}`;
                }

                function updateView() {
                    const formatted = formatPrice(rawInput);
                    ngModelCtrl.$setViewValue(formatted);
                    ngModelCtrl.$render();
                }

                // Watch model value
                scope.$watch(() => ngModelCtrl.$modelValue, function (newVal, oldVal) {
                    if (!isTyping) {
                        // Skip formatting if it's already a valid decimal value
                        if (!isNaN(parseFloat(newVal)) && String(newVal).includes('.')) {
                            rawInput = ''; // Clear to avoid messing with it
                            return;
                        }

                        // Only format raw digits like '1234' -> '12.34'
                        if (/^\d+$/.test(newVal)) {
                            rawInput = newVal;
                            updateView();
                        }
                    }
                });

                // Handle keyboard input
                element.on('keydown', function (event) {
                    const key = event.key;
                    isTyping = true;

                    if (/^[0-9]$/.test(key)) {
                        rawInput += key;
                        updateView();
                    } else if (key === 'Backspace') {
                        rawInput = rawInput.slice(0, -1);
                        updateView();
                    } else if (['Tab', 'ArrowLeft', 'ArrowRight'].includes(key)) {
                        // allow navigation
                    } else {
                        event.preventDefault();
                    }

                    event.preventDefault();
                    isTyping = false;
                });

                // initialize
                // ngModelCtrl.$setViewValue('0.00');
                // ngModelCtrl.$render();
                $timeout(function () {
                    if (!ngModelCtrl.$modelValue) {
                        updateView();
                    }
                });
            }
        };
    }]);

    angular.module('myApp').controller('salesOrderFormCtrl', function($scope, $timeout, $http, $window) {
        $scope.order_date = '';
        $scope.payment_date = '';
        $scope.userList = [];
        $scope.productItems = [];
        $scope.productList = [];
        $scope.serviceList = [];

        // #region Fetch data for edit
        $scope.user = null;

        $scope.mode                 = '<?= $mode ?>';
        $scope.id                   = '<?= isset($id) ? $id : '' ?>';

        $scope.serial_number        = '<?= esc(isset($salesOrderData) ? $salesOrderData['serial_number'] : '') ?>';

        $scope.order_date           = '<?= esc(isset($salesOrderData) ? $salesOrderData['order_date'] : '') ?>';
        $scope.total_amount         = '<?= esc(isset($salesOrderData) ? $salesOrderData['total_amount'] : '') ?>';
        $scope.discount_amount      = '<?= esc(isset($salesOrderData) ? $salesOrderData['discount_amount'] : '') ?>';
        $scope.total_weight         = '<?= esc(isset($salesOrderData) ? $salesOrderData['total_weight'] : '') ?>';
        $scope.service              = '<?= esc(isset($salesOrderData) ? $salesOrderData['service_id'] : 0) ?>';
        $scope.shipping_fee         = '<?= esc(isset($salesOrderData) ? $salesOrderData['shipping_fee'] : '') ?>';
        $scope.final_amount         = '<?= esc(isset($salesOrderData) ? $salesOrderData['final_amount'] : '') ?>';
        $scope.order_status         = '<?= esc(isset($salesOrderData) ? $salesOrderData['order_status'] : 0) ?>';
        $scope.user_id              = '<?= esc(isset($salesOrderData) ? $salesOrderData['user_id'] : '') ?>';
        $scope.payment_status       = '<?= esc(isset($salesOrderData) ? $salesOrderData['payment_status'] : 0) ?>';
        $scope.payment_date         = '<?= esc(isset($salesOrderData) ? $salesOrderData['payment_date'] : '') ?>';
        $scope.payment_method       = '<?= esc(isset($salesOrderData) ? $salesOrderData['payment_method'] : 0) ?>';
        $scope.admin_remark         = '<?= esc(isset($salesOrderData) ? $salesOrderData['admin_remark'] : '') ?>';

        $scope.sales_order_detail   = <?= json_encode($salesOrderDetailData ?? []) ?>;
        // #endregion

        if ($scope.sales_order_detail && $scope.sales_order_detail.length > 0) {
            $scope.sales_order_detail.forEach(function (row) {
                $scope.productItems.push({
                    product_id:        row.product_id,
                    product_name:      row.product_name,
                    product_image_url: row.product_image_url,

                    /* numbers must be numbers or maths will give NaN */
                    product_qty:   +row.qty,
                    product_price: +row.unit_price,
                    product_weight:+row.weight,
                    total:         +row.total_amount,

                    /* placeholder for full catalogue object (added later) */
                    product: null
                });
            });
        }

        // #region Datepicker
        $timeout(function() {
            const commonOptions = {
                dateFormat: "Y-m-d",
                time_24hr: true,
                defaultDate: $scope.order_date ? new Date($scope.order_date) : new Date(),
                onReady: function(selectedDates, dateStr, instance) {
                    const inputId = instance.input.id;
                    if (inputId === 'order_date') {
                        $scope.order_date = dateStr;
                    } else if (inputId === 'payment_date') {
                        $scope.payment_date = dateStr;
                    }
                    $scope.$apply();
                },
                onChange: function(selectedDates, dateStr, instance) {
                    const inputId = instance.input.id;
                    if(inputId === 'order_date') {
                        $scope.order_date = dateStr;
                    } else if (inputId === 'payment_date') {
                        $scope.payment_date = dateStr;
                    }
                    $scope.$apply();
                }
            }

            flatpickr("#order_date", commonOptions);
            flatpickr("#payment_date", commonOptions);
        }, 0);
        // #endregion


        // #region Retrive User List
        $http.get('<?= base_url('api/fetchUserList') ?>')
                .then((res) => {
                    $scope.userList = res.data;
                    if ($scope.user_id) {
                        $scope.setUserDetails($scope.user_id);
                    }
                })
                .catch((err) => {
                    console.log('Error fetching user list.');
                    console.log(err);
                })

        $scope.setUserDetails = function(user_id) {
            const selectedUser = $scope.userList.find(u => u.user_id === user_id);
            if (selectedUser) {
                $scope.user = selectedUser.user_id;
                $scope.user_name = selectedUser.name;
                $scope.user_email = selectedUser.email;
                $scope.user_contact = selectedUser.phonenum;
                $scope.user_addr = selectedUser.address;
            }
        };

        $scope.onUserChange = function () {
            $scope.setUserDetails($scope.user);
        }
        
        // #endregion

        // #region Retrive Service
        $http.get('<?= base_url('api/fetchServiceList') ?>')
                .then((res) => {
                    if(res.data.status == 'Error') {
                        console.log(res.data.errors);
                        $scope.serviceList = [];
                        $scope.serviceList.unshift({service_id: '0', title: 'No Shipping'});
                    } else {
                        $scope.serviceList = res.data;
                        $scope.serviceList.unshift({service_id: '0', title: 'No Shipping'});
                    }
                })

        // #region Sales Order Detail
        // Get Product List
        $http.get('<?= base_url('api/fetchProductList') ?>')
                .then((res) => {
                    $scope.productList = res.data;
                    console.log($scope.productList);

                    $scope.productItems.forEach(function (item) {
                    if (item.product_id) {
                        item.product = $scope.productList.find(function (product) {
                            return product.product_id === item.product_id;
                        });
                    }
                });

                /* Totals might change if the catalogue price differs – re‑calc */
                $scope.updateOrderTotal();
                })
                .catch((err) => {
                    console.log('Error retrive product list.');
                    console.log(err);
                })

        $scope.addProductRow = function () {
            $scope.productItems.push({
                product_id: '',
                product_name: '',
                product_image_url: '',
                product_qty: 1,
                product_weight: 1,
                product_price: 0.00,
                total: 0.00
            });
        };

        // Remove a row by index
        $scope.removeProductRow = function (index) {
            $scope.productItems.splice(index, 1);
            $scope.updateOrderTotal();
        };

        // Update total when qty or price changes
        $scope.updateItemTotal = function (item) {
            const qty = parseFloat(item.product_qty) || 0;
            const price = parseFloat(item.product_price) || 0;
            item.total = qty * price;
            $scope.updateOrderTotal();
        };

        $scope.isProductSelected = function (productId, rowIndex) {
            return $scope.productItems.some((item, index) =>
                index !== rowIndex && item.product && item.product.product_id === productId
            );
        };

        // Action after selection
        $scope.onProductSelect = function(item) {
            const selectedProduct = item.product;
            if (selectedProduct) {
                item.product_id = selectedProduct.product_id;
                item.product_name = selectedProduct.name;
                item.product_image_url = selectedProduct.image_url;
                item.product_price = parseFloat(selectedProduct.price).toFixed(2);;
                item.product_qty = 1;
                item.product_weight = selectedProduct.weight;
                $scope.updateItemTotal(item);
            }
        }
        // #endregion

        // #region Sales order detail product
        $scope.updateOrderTotal = function () {
            let total = 0;
            let totalWeight = 0;

            $scope.productItems.forEach(item => {
                const qty = parseFloat(item.product_qty) || 0;
                const price = parseFloat(item.product_price) || 0;
                const weight = parseFloat(item.product_weight) || 0;

                item.total = qty * price;
                total += item.total;
                totalWeight += qty * weight;
            });

            $scope.total_amount = total.toFixed(2);
            $scope.total_weight = totalWeight.toFixed(2);
            
            const discount = parseFloat($scope.discount_amount);
            const shipping = parseFloat($scope.shipping_fee);
            const final = (total - discount) + shipping;

            $scope.final_amount = final.toFixed(2);
        }

        $scope.$watch('discount_amount', function () {
            $scope.updateOrderTotal();
        });
        $scope.$watch('shipping_fee', function () {
            $scope.updateOrderTotal();
        });
        // #endregion

        // #region Submit Form
        $scope.submitForm = function () {
            if(confirm('Are you sure?')) {
                $scope.submitted = true;

                // Variable
                const postData = {
                    'mode'              : $scope.mode,
                    'id'                : $scope.id,
                    'serial_number'     : $scope.serial_number,
                    'order_date'        : $scope.order_date,
                    'total_amount'      : $scope.total_amount,
                    'discount_amount'   : $scope.discount_amount,
                    'total_weight'      : $scope.total_weight,
                    'service_id'        : $scope.service,
                    'shipping_fee'      : $scope.shipping_fee,
                    'final_amount'      : $scope.final_amount,
                    'user_id'           : $scope.user,
                    'user_name'         : $scope.user_name,
                    'user_email'        : $scope.user_email,
                    'user_contact'      : $scope.user_contact,
                    'user_address'      : $scope.user_addr,
                    'order_status'      : $scope.order_status,
                    'payment_status'    : $scope.payment_status,
                    'payment_date'      : $scope.payment_date,
                    'payment_method'    : $scope.payment_method,
                    'admin_remark'      : $scope.admin_remark,
                    'sales_order_detail': $scope.productItems,
                }

                if($scope.salesOrderForm.$valid) {
                    if(!($scope.productItems.length > 0)) {
                        alert('Sales order detail must have at least 1 item!')
                        return;
                    }

                    // Now validate individual item fields
                    let hasError = false;

                    $scope.productItems.forEach((item, index) => {
                        if (!item.product_id || !item.product_name || !item.product_qty || item.product_qty <= 0) {
                            hasError = true;
                        }
                    });

                    if (hasError) {
                        alert('Each item must have a selected product and valid quantity.');
                        return;
                    }

                    console.log(postData);

                    $http.post('<?= base_url('sales_order_submit') ?>', postData)
                            .then((res) => {
                                if(res.data.status == "Success") {
                                    alert('Done');
                                    $window.location.href = '<?= base_url('sales_order_list') ?>'
                                }
                            })
                            .catch((err) => {
                                console.log('Error submit sales order.');
                                console.log(err);
                            })

                }

            } else {
                alert('Operation cancel.')
            }
        }
        // #endregion
    });
</script>