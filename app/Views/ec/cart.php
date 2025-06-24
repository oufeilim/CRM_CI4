        <main ng-controller="cartListFormCtrl">
            <section class="py-5 text-center container">
                <div class="row">
                    <div class="col-lg-6 col-md-8 mx-auto">
                        <h1 class="fw-light">Cart</h1>
                    </div>
                </div>
            </section>
            <div class="album py-5 bg-body-tertiary">
                <div class="container">
                    <div class="row" ng-if="cartItems.length > 0">
                        <!-- Removed row-cols grid; now we add a table -->
                        <!-- Table Column -->
                        <div class="col-md-8">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped align-middle text-center">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="width: 7%"></th>
                                            <th style="width: 40%">Product</th>
                                            <th style="width: 15%">Price</th>
                                            <th style="width: 15%">Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="item in cartItems">
                                            <td><button type="button" class="btn btn-danger btn-sm" ng-click="removeProductRow($index)">×</button></td>
                                            <td>
                                                <a href="#" class="d-flex align-items-center text-decoration-none text-white">
                                                    <img src="<?= base_url() ?>{{ item.product_image_url }}" class="img-thumbnail me-3" style="width: 100px; height: 100px; object-fit: cover;" alt="product" />
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-semibold text-start text-break">{{ item.product_name }}</span>
                                                        <small class="text-muted text-break">This is a short description of the product.</small>
                                                    </div>
                                                </a>
                                            </td>
                                            <td>{{ item.product_price }}</td>
                                            <td>
                                                <div class="input-group justify-content-center">
                                                    <button class="btn btn-outline-secondary btn-sm" ng-click="changeQty(item, -1)" ng-disabled="item.product_qty <= 1">
                                                        &minus;
                                                    </button>

                                                    <input type="text" class="form-control text-center" style="width:60px" ng-model="item.product_qty" ng-change="inputQtyChanged(item)" ng-pattern="/^\d+$/">
                                                    
                                                    <button class="btn btn-outline-secondary btn-sm" ng-click="changeQty(item, 1)">
                                                        &plus;
                                                    </button>
                                                </div>    
                                            </td>
                                        </tr>
                                        <!-- <tr>
                                            <td>2</td>
                                            <td>
                                                <a href="#" class="d-flex align-items-center text-decoration-none text-white">
                                                    <img src="https://picsum.photos/id/237/500/300" class="img-thumbnail me-3" style="width: 100px; height: 100px; object-fit: cover;" alt="product" />
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-semibold text-start text-break">Sample Product</span>
                                                        <small class="text-muted text-break">This is a short description of the product.</small>
                                                    </div>
                                                </a>
                                            </td>
                                            <td>$7.50</td>
                                            <td>1</td>
                                        </tr> -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Summary Column -->
                        <div class="col-md-4">

                            <div class="card shadow-sm p-3">
                                <h5 class="card-title">Cart Summary</h5>
                                <hr>
                                <div class="mb-3">
                                    <label for="coupon" class="form-label">Promo Code</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="promoCode" id="promoCode" ng-model="promo.code" placeholder="Enter Promo Code">
                                        <button class="btn btn-outline-secondary" type="button" ng-click="applyPromoCode($event)">Apply</button>
                                    </div>
                                    <small class="text-danger" ng-if="couponError">{{ couponError }}</small>
                                    <small class="text-success" ng-if="couponSuccess">{{ couponSuccess }}</small>
                                </div>
                                <div class="mb-2 d-flex justify-content-between">
                                    <span>Subtotal:</span>
                                    <strong>$ {{ subtotal }}</strong>
                                </div>
                                <div class="mb-2 d-flex justify-content-between">
                                    <span>Discount:</span>
                                    <strong>$ {{ discount }}</strong>
                                </div>
                                <div class="mb-3 d-flex justify-content-between">
                                    <span>Total:</span>
                                    <strong>$ {{ final_amount }}</strong>
                                </div>
                                <a href="<?= base_url('ec/checkout') ?>" class="btn btn-primary w-100">Checkout</a>
                            </div>

                        </div>
                    </div>

                    <div class="row" ng-if="cartItems.length === 0">
                        <div class="col-12">
                            <div class="card shadow-sm p-3">
                                <h5 class="card-title">Empty cart</h5>
                                <hr>
                                <p><a class="text-muted" href="<?= base_url('ec/category') ?>">Add new item</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

<script>
angular.module('myApp').controller('cartListFormCtrl', function($scope, $rootScope, $http, cartService) {
    $scope.cartItems = [];
    $scope.submittedPromoCode = false;
    $scope.promo = {
        code: '',
    };
    $scope.discount = (0.00).toFixed(2);

    cartService.getCartItems().then(function(items) {
        $scope.cartItems = items;
        calcSubTotal();
        calcFinalAmount();
    });

    function refreshCartMeta() {
        cartService.refreshCartList().then(function (items) {
            $scope.cartItems = items;
            calcSubTotal();
            calcFinalAmount();

            const total = cartService.getCartQty();
            $rootScope.$broadcast('cartUpdated', total);  // updates header badge
        });
    }

    $scope.changeQty = function (item, val) {
        const newQty = (+item.product_qty) + val;
        if (newQty < 1) return;

        const oldQty = item.product_qty;
        item.product_qty = newQty;
        calcSubTotal();
        calcFinalAmount();

        cartService.updateCartQty(item.cart_id, newQty)
        .then(refreshCartMeta)
        .catch(function () {
            item.product_qty = oldQty;
            calcSubTotal();
            calcFinalAmount();
            alert('Sorry, couldn\'t update the quantity. Please try again.');
        });
    };

    $scope.inputQtyChanged = _.debounce(function (item) {   // lodash debounce (250 ms)
        const qty = parseInt(item.product_qty, 10) || 1;
        if (qty < 1) { item.product_qty = 1; return; }
        cartService.updateCartQty(item.cart_id, qty)
            .then(refreshCartMeta)
            .catch(function () { alert('Could not update the quantity'); });
        calcSubTotal();
        calcFinalAmount();
    }, 250);

    function calcSubTotal () {
        $scope.subtotal = $scope.cartItems.reduce(function (acc, it) {
            return acc + (+it.product_price * it.product_qty);
    }, 0).toFixed(2)};

    function calcFinalAmount () {
        if($scope.discount <= 0) {
            $scope.final_amount = $scope.subtotal;
        } else {
            var result = cartService.checkDiscount($scope.subtotal, $scope.promo.type, $scope.promo.value, $scope.promo.maxcap);

            $scope.discount = parseFloat(result.deductAmount).toFixed(2);
            $scope.final_amount = result.finalAmount.toFixed(2);
        }

        localStorage.setItem('discountAmount', $scope.discount);
        localStorage.setItem('finalAmount', $scope.final_amount);
    }

    $scope.removeProductRow = function (index) {
    const item = $scope.cartItems[index];

        if (!confirm('Are you sure you want to remove this item from cart?')) return;

        cartService.deleteCartItem(item.cart_id)
            .then(function () {
                $scope.cartItems.splice(index, 1);  // Remove item from view
                calcSubTotal();
                calcFinalAmount();

                const totalQty = cartService.getCartQty();
                $rootScope.$broadcast('cartUpdated', totalQty);
            })
            .catch(function () {
                alert('Failed to delete the item.');
            });
    };

    $scope.applyPromoCode = function () {
        $scope.submittedPromoCode = true;

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
    }

});
</script>