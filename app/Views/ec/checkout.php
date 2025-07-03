<div class="container my-5">
    <form ng-controller="checkoutFormCtrl" name="checkoutForm" id="checkoutForm" ng-submit="submitForm()" novalidate>
        <div class="row">
            <!-- Left: Address + Payment Form -->
            <div class="col-md-7">
                <div class="card p-4 shadow-sm">
                    <h5 class="mb-3">Shipping Address</h5>

                    <div class="form-group">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input ng-model="user_name" name="user_name" id="user_name" type="text" class="form-control" placeholder="John Doe" required>
                        </div>
                        <div ng-messages="checkoutFormCtrl.user_name.$error" ng-if="submitted" class="text-danger">
                            <div ng-message="required">Name is required.</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input ng-model="user_email" name="user_email" id="user_email" type="text" class="form-control" placeholder="xxx@xxx.com">
                            <div ng-messages="checkoutFormCtrl.user_email.$error" ng-if="submitted" class="text-danger">
                                <div ng-message="required">Email is required.</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="mb-3">
                            <label class="form-label">Contact Number</label>
                            <input ng-model="user_contact" name="user_contact" id="user_contact" type="text" class="form-control" placeholder="0123456789">
                        </div>
                        <div ng-messages="checkoutFormCtrl.user_contact.$error" ng-if="submitted" class="text-danger">
                            <div ng-message="required">Contact Number is required.</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input ng-model="user_addr" name="user_addr" id="user_addr" type="text" class="form-control" placeholder="1, Jalan abc, Taman abc, xxxxx abc">
                        </div>
                        <div ng-messages="checkoutFormCtrl.user_addr.$error" ng-if="submitted" class="text-danger">
                            <div ng-message="required">Address is required.</div>
                        </div>
                    </div>
                    
                    

                    <hr>

                    <h5 class="mb-3">Payment Method</h5>

                    <div class="row">
                        <div class="col-6 form-group my-2">
                            <label class="form-label" for="payment_method">Payment method</label>
                            <select class="form-control" ng-model="payment_method" name="payment_method" id="payment_method" required>
                                <option value="0">Bank Transfer</option>
                                <option value="1">Payment Gateway</option>
                                <option value="2">E-wallet</option>
                            </select>
                            <div ng-messages="checkoutForm.payment_method.$error" ng-if="submitted" class="text-danger">
                                <div ng-message="required">Payment method is required.</div>
                            </div>
                        </div>
    
                        <div class="col-6 form-group my-2">
                            <label for="promo" class="form-label">Promo Code</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="promoCode" ng-model="promo.code" placeholder="Enter promo code">
                                <button class="btn btn-outline-secondary" type="button" ng-click="applyPromoCode()">Apply</button>
                            </div>
                            <small class="text-danger" ng-if="couponError">{{ couponError }}</small>
                            <small class="text-success" ng-if="couponSuccess">{{ couponSuccess }}</small>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-6 form-group my-2">
                            <div class="form-check">
                                <input class="form-check-input" ng-model="needShipping" ng-change="checkShipping()" type="checkbox" value="" id="needShipping">
                                <label class="form-check-label" for="needShipping">
                                    I want shipping
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row" ng-if="needShipping == true">
                        <div class="form-group my-2">
                            <label class="form-label" for="service">Service</label>
                            <select class="form-control" ng-model="formData.service" name="service" id="service" ng-options="service.service_id as service.title for service in serviceList" required>
                            </select>

                            <button type="button" class="btn btn-primary mt-3 w-100" ng-click="checkShippingRate()" ng-disabled="formData.service == null || formData.service == 0">Check shipping</button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right: Cart -->
            <div class="col-md-5">
            <!-- Cart Items Scrollable -->
            <div class="card mb-3 shadow-sm" style="max-height: 345px; overflow-y: auto;">
                <div class="card-body">
                    
                <h5 class="card-title">Cart Items</h5>
                <hr>

                <div class="d-flex mb-3" ng-repeat="item in cartList">
                    <img ng-src="<?= base_url() ?>{{ item.product_image_url }}" class="img-thumbnail object-fit-cover me-3" style="width: 80px; height: 80px;">
                    <div>
                        <h6 class="mb-1">{{ item.product_name }}</h6>
                        <p class="mb-0 text-muted">Quantity: {{ item.product_qty }}</p>
                        <p class="mb-0 text-muted">Price: ${{ item.product_price }}</p>
                    </div>
                </div>
                <!-- <div class="d-flex mb-3" *ngFor="item in cartItems">
                    <img src="https://picsum.photos/id/237/80/80" class="img-thumbnail me-3" style="width: 80px; height: 80px;">
                    <div>
                        <h6 class="mb-1">Product Name</h6>
                        <p class="mb-0 text-muted">Qty: 2</p>
                        <p class="mb-0 text-muted">RM 19.99</p>
                    </div>
                </div> -->
                <!-- Repeat above block for each cart item -->
                </div>
            </div>

            <!-- Summary -->
            <div class="card shadow-sm">
                <div class="card-body">
                <h5 class="card-title">Summary</h5>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <strong>RM {{ subTotal }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Discount</span>
                    <strong>-RM {{ discount }}</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Weight</span>
                    <strong>{{ totalWeight }} kg</strong>
                </div>
                <div class="d-flex justify-content-between mb-2" ng-if="needShipping == true">
                    <span>Shipping Fee</span>
                    <strong>RM {{ shippingFee }}</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <span>Total</span>
                    <strong>RM {{ finalAmount }}</strong>
                </div>
                <button type="submit" class="btn btn-success w-100">Place Order</button>
                </div>
            </div>
            </div>
        </div>
    </form>
</div>

<script>
angular.module('myApp').controller('checkoutFormCtrl', function($scope, $rootScope, $http, $window, cartService) {

    if (performance.getEntriesByType("navigation")[0]?.type === "reload") {
        localStorage.removeItem('promoInfo');
        localStorage.removeItem('tempFinalAmount');
        localStorage.setItem('discountAmount', 0.00.toFixed(2));
        // localStorage.setItem('finalAmount', $scope.subTotal);
    }

    $scope.formData = {
        service: null
    };
    $scope.userData         = [];
    $scope.cartList         = [];
    $scope.subTotal         = 0;
    $scope.payment_method   = '0'; // default
    $scope.promo = {
        code: '',
    }
    $scope.shippingFee      = parseFloat(0.00).toFixed(2);
    $scope.serviceList      = [];
    const storedDiscount = parseFloat(localStorage.getItem('discountAmount'));
    $scope.discount = isNaN(storedDiscount) ? 0 : storedDiscount.toFixed(2);
    const storedFinalAmount = parseFloat(localStorage.getItem('finalAmount'));
    $scope.finalAmount = isNaN(storedFinalAmount) ? 0 : storedFinalAmount.toFixed(2);
    $scope.totalWeight = localStorage.getItem('totalWeight');

    // #region Assign value
    $http.post('<?= base_url('/api/fetchUserOne') ?>', {user_id: 3})
            .then((res) => {
                $scope.userData = res.data;

                $scope.user_name = $scope.userData.name;
                $scope.user_email = $scope.userData.email;
                $scope.user_contact = $scope.userData.phonenum;
                $scope.user_addr = $scope.userData.address;

            })
            .catch((err) => {
                console.log("Error fetching user data.");
                console.log(err);
            })

    $http.get('<?= base_url('/api/ec/fetchCartList') ?>')
            .then((res) => {
                if(res.data.status == "Success"){
                    console.log("Fetched Cart List.");
                    $scope.cartList = res.data.data;
                    console.log($scope.cartList)
                    calcSubtotal();
                } else {
                    $scope.cartList = [];
                }
            })
            .catch((err) => {
                console.log('Error fetching cart list.');
                console.log(err);
            })
    // #endregion

    // #region Calculate Total
    function calcSubtotal () {
        let subtotal = 0;

        $scope.cartList.forEach(function (it) {
            // Calculate item total and assign to item
            it.total = (+it.product_price * it.product_qty).toFixed(2);

            // Accumulate subtotal
            subtotal += +it.total;
        });

        $scope.subTotal = subtotal.toFixed(2);
        $scope.finalAmount = ($scope.subTotal - ($scope.discount || 0)).toFixed(2);
        localStorage.setItem('finalAmount', $scope.finalAmount);
    };
    // #endregion

    const promoInfo = JSON.parse(localStorage.getItem('promoInfo'));
    if (promoInfo && promoInfo.code) {
        $scope.promo = promoInfo;
        const result = cartService.checkDiscount($scope.subTotal, promoInfo.type, promoInfo.value, promoInfo.maxcap);

        $scope.discount = parseFloat(result.deductAmount).toFixed(2);
        $scope.finalAmount = parseFloat(result.finalAmount).toFixed(2);
    }

    $scope.checkShipping = function () {
        if($scope.needShipping) {
            localStorage.setItem('tempFinalAmount', $scope.finalAmount);

            $http.get('<?= base_url('api/fetchServiceList?shipping_addr=') ?>'+$scope.user_addr)
                    .then((res) => {
                        if(res.data.status == 'Error') {
                            console.log(res.data.errors);
                        } else {
                            $scope.serviceList = res.data;
                            console.log('Service List Structure:', $scope.serviceList); // Add this
                            // Check if each service has service_id property
                            $scope.serviceList.forEach(service => {
                                console.log('Service:', service.service_id, service.title);
                            });
                        }
                    })
                    .catch((err) => {
                        $scope.serviceList = [];
                        console.log(err);
                    })
            
        } else {
            $scope.shippingFee      = parseFloat(0.00).toFixed(2);
            $scope.formData.service = '';
            $scope.serviceList      = [];
            $scope.finalAmount = localStorage.getItem('tempFinalAmount');
            localStorage.setItem('finalAmount', $scope.finalAmount);
            localStorage.removeItem('tempFinalAmount');
        }
    }
    
    // #region Insert Sales Order
    $scope.submitForm = function () {
        if(confirm('Do you really want to place your order?')) {
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-based
            const dd = String(today.getDate()).padStart(2, '0');

            const formatted = `${yyyy}-${mm}-${dd}`;

            const postData = {
                'mode'              : 'consumerAdd',
                'id'                : '',
                'serial_number'     : '',
                'company_addr'      : 'Johor',
                'order_date'        : formatted,
                'total_amount'      : $scope.subTotal,
                'discount_amount'   : $scope.discount,
                'service_id'        : $scope.needShipping == true ? $scope.formData.service : 0,
                'total_weight'      : $scope.totalWeight,
                'shipping_fee'      : $scope.shippingFee,
                'final_amount'      : $scope.finalAmount,
                'user_id'           : $scope.userData.user_id,
                'user_name'         : $scope.user_name,
                'user_email'        : $scope.user_email,
                'user_contact'      : $scope.user_contact,
                'user_address'      : $scope.user_addr,
                'payment_date'      : formatted,
                'payment_method'    : $scope.payment_method,
                'sales_order_detail': $scope.cartList,
            }

            console.log(postData);

            $http.post('<?= base_url('sales_order_submit') ?>', postData)
                    .then((res) => {
                        if(res.data.status == "Success") {
                            alert('Success');
                            const sn = res.data.serial_num;
                            localStorage.removeItem('promoInfo');
                            localStorage.removeItem('discountAmount');
                            localStorage.removeItem('finalAmount');
                            localStorage.removeItem('tempFinalAmount');
                            const cartIds = $scope.cartList.map(item => item.cart_id);

                            cartService.bulkDeleteCartItems(cartIds)
                                .then(function(res) {
                                    console.log('Cart cleared:', res.data);
                                    $scope.cartList = [];
                                    $rootScope.$broadcast('cartUpdated', 0);  // reset cart badge
                                })
                                .catch(function(err) {
                                    console.error('Cart not cleared:', err);
                                });

                            $window.location.href = '<?= base_url('ec/checkout_success?serial_num=') ?>'+sn
                        }
                    })
                    .catch((err) => {
                        console.log('Fail to place order.');
                        console.log(err);
                    })
        } else {
            alert('Cancel.');
        }
    }
    // #endregion

    $scope.applyPromoCode = function () {
        $scope.submittedPromoCode = true;

        if($scope.discount <= 0) {
            // Simple validation
            if (!$scope.promo.code || $scope.promo.code.trim() === '') {
                $scope.couponError = 'Please enter a valid promo code.';
                $scope.couponSuccess = '';
                return;
            }

            $http.post('<?= base_url('api/fetchPromoCodeOne') ?>', { 'code': $scope.promo.code })
                .then((res) => {
                    $scope.couponError = '';
                    $scope.couponSuccess = 'Added!';

                    $scope.promo = {
                        code: res.data.code,
                        type: res.data.type,
                        value: res.data.value,
                        maxcap: res.data.max_cap
                    }

                    var result = cartService.checkDiscount($scope.subTotal, $scope.promo.type, $scope.promo.value, $scope.promo.maxcap);

                    $scope.discount = parseFloat(result.deductAmount).toFixed(2);
                    $scope.finalAmount = result.finalAmount.toFixed(2);

                    // Store amounts
                    localStorage.setItem('discountAmount', $scope.discount);
                    localStorage.setItem('finalAmount', $scope.finalAmount);
                    localStorage.setItem('promoInfo', JSON.stringify($scope.promo));
                })
                .catch((err) => {
                    $scope.couponError = 'Please enter a valid promo code.'
                    $scope.couponSuccess = '';
                    console.log(err);
                })
        } else {
            $scope.couponError = 'Discount has already applied.';
            $scope.couponSuccess = '';
        }
    }

    $scope.checkShippingRate = function () {
        if($scope.shippingFee != '0.00') return;

        const data = {
            'company_addr'  : 'Johor',
            'shipping_addr' : $scope.user_addr,
            'service_id'    : $scope.formData.service,
            'weight'        : $scope.totalWeight,
        }

        $http.post('<?= base_url('api/get_service_price') ?>', data)
                .then((res) => {
                    if(res.data.status == 'Error') {
                        console.log(res.data);
                    } else {
                        $scope.shippingFee = res.data;
                        $scope.finalAmount = +$scope.shippingFee + +$scope.finalAmount;
                        localStorage.setItem('finalAmount', $scope.finalAmount);
                        $scope.shippingFee = $scope.shippingFee.toFixed(2);
                        $scope.finalAmount = $scope.finalAmount.toFixed(2);
                    }
                })
    }
});
</script>