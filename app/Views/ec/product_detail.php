        <main ng-controller="ProductDetailView">
            <section class="py-5 text-center container">
                <div class="row py-lg-5 mb-3">
                    <div class="col-lg-6 col-md-8 mx-auto">
                        <h1 class="fw-light">Product Detail</h1>
                    </div>
                </div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('ec') ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('ec/category') ?>">Category</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('ec/category_product_list/') ?>{{ productData.category_id + '/' + productData.category_slug }}">{{ productData.category_title }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Product</li>
                    </ol>
                </nav>
            </section>
            <div class="album py-5 bg-body-tertiary">
                <div class="container">
                    <div class="row" style="min-height: 300px;">
                        <!-- Left: Image -->
                        <div class="col-md-5">
                            <img ng-src="<?= base_url() ?>{{ productData.image_url }}" width="400" height="200" class="img-fluid rounded shadow-sm" alt="Product Image">
                        </div>

                        <!-- Right: Product Details -->
                        <div class="col-md-7" style="min-height: 300px;">
                            <h2>{{ productData.name }}</h2>
                            <p class="text-muted">Category: <a class="text-decoration-none text-muted" href="<?= base_url('ec/category_product_list/') ?>{{ productData.category_id + '/' + productData.category_slug }}">{{ productData.category_title }}</a></p>
                            <p>{{ productData.description }}</p>
                            <p><strong>Price:</strong> {{ productData.price }} each</p>
                            <p><strong>Stock Available:</strong> {{ productData.stock_qty }}</p>
                            <div class="d-flex flex-column gap-2 mt-3" style="max-width: 200px;">
                                <!-- Quantity Selector -->
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary" type="button" ng-click="decreaseQty()">−</button>
                                    <input type="number" class="form-control text-center" ng-model="quantity" min="1" readonly>
                                    <button class="btn btn-outline-secondary" type="button" ng-click="increaseQty()">+</button>
                                </div>

                                <!-- Add to Cart Button -->
                                <button class="btn btn-primary" ng-click="addToCart()">Add to Cart</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <script>
            angular.module('myApp').controller('ProductDetailView', function($scope, $rootScope, $http, $window, cartService) {

                $scope.quantity = 1;

                $scope.increaseQty = function () {
                    if ($scope.quantity < $scope.productData.stock_qty) {
                        $scope.quantity++;
                    }
                };

                $scope.decreaseQty = function () {
                    if ($scope.quantity > 1) {
                        $scope.quantity--;
                    }
                };

                const pathParts = $window.location.pathname.split('/');
                const categoryId = pathParts[3];
                const categorySlug = pathParts[4];

                $scope.productData = [];

                $http.get('<?= base_url('/api/ec/getProductDetail/') ?>' + categoryId + '/' + categorySlug)
                    .then((res) => {
                        $scope.productData = res.data;
                    })
                    .catch((err) => {
                        // alert('Error retriveing data. See console for details.');
                        console.error(err);
                    })

                $scope.addToCart = function () {
                    if(confirm('Do you want to add the item into cart?')) {
                        const postData = {
                            user_id             : '3',
                            product_id          : $scope.productData.product_id,
                            product_name        : $scope.productData.name,
                            product_qty         : $scope.quantity,
                            product_price       : $scope.productData.price,
                            product_image_url   : $scope.productData.image_url,
                        };

                        $http.post('<?= base_url('/api/ec/addItemIntoCart') ?>', postData)
                                .then((res) => {
                                    if(res.data.status == "Success") {
                                        alert(`Added ${postData.product_qty} x "${postData.product_name}" to cart`);

                                        cartService.refreshCartList().then(function(items) {
                                            const totalQty = cartService.getCartQty();
                                            $rootScope.$broadcast('cartUpdated', totalQty);
                                        });
                                    }
                                })
                    }
                }

            });
        </script>