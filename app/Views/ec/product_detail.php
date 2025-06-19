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
                            <!-- <div class="d-flex gap-2 mt-3">
                                <button class="btn btn-primary">Buy Now</button>
                                <button class="btn btn-outline-secondary">Add to Cart</button>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <script>
            angular.module('myApp').controller('ProductDetailView', function($scope, $http, $window) {

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


            });
        </script>