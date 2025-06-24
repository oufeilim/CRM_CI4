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
                    <img src="<?= base_url() ?>{{ item.product_image_url }}" class="img-thumbnail object-fit-cover me-3" style="width: 80px; height: 80px;">
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
                    <strong>$ {{ subTotal }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Discount</span>
                    <strong>-$ {{ discount }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Total</span>
                    <strong>$ {{ finalAmount }}</strong>
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

    $scope.userData         = [];
    $scope.cartList         = [];
    $scope.subTotal         = 0;
    $scope.payment_method   = '0'; // default
    $scope.promo = {
        code: '',
    }

    const promoInfo = JSON.parse(localStorage.getItem('promoInfo'));
    $scope.discount = parseFloat(localStorage.getItem('discountAmount')).toFixed(2) || 0;
    $scope.finalAmount = parseFloat(localStorage.getItem('finalAmount')).toFixed(2) || 0;

    
    if (promoInfo) {
        $scope.couponCode = promoInfo.code;
    }

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
    };
    // #endregion

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
                'order_date'        : formatted,
                'total_amount'      : $scope.subTotal,
                'discount_amount'   : $scope.discount,
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

            $http.post('<?= base_url('sales_order_submit') ?>', postData)
                    .then((res) => {
                        if(res.data.status == "Success") {
                            alert('Success');
                            const sn = res.data.serial_num;
                            localStorage.removeItem('promoInfo');
                            localStorage.removeItem('discountAmount');
                            localStorage.removeItem('finalAmount');
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

                    var result = cartService.checkDiscount($scope.subtotal, $scope.promo.type, $scope.promo.value, $scope.promo.maxcap);

                    $scope.discount = parseFloat(result.deductAmount).toFixed(2);
                    $scope.final_amount = result.finalAmount.toFixed(2);

                    // Store amounts
                    localStorage.setItem('discountAmount', $scope.discount);
                    localStorage.setItem('finalAmount', $scope.final_amount);
                })
                .catch((err) => {
                    $scope.couponError = 'Please enter a valid promo code.'
                    console.log(err);
                })
        } else {
            $scope.couponError = 'Discount has already applied.';
        }
    }

});
</script>