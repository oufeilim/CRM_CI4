        <main>
            <section class="py-5 text-center container">
                <div class="row py-lg-5 mb-3">
                    <div class="col-lg-6 col-md-8 mx-auto">
                        <h1 class="fw-light">Category Product</h1>
                    </div>
                </div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('ec') ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('ec/category') ?>">Category</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Category Product</li>
                    </ol>
                </nav>
            </section>
            <div class="album py-5 bg-body-tertiary" ng-controller="categoryProductListView">
                <div class="container">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3" style="min-height: 300px;">
                        <div class="col" ng-repeat="product in productList">
                            <a class="text-decoration-none text-dark" href="<?= base_url('ec/product_detail/') ?>{{ product.product_id + '/' + product.product_slug }}">
                                <div class="card shadow-sm">
                                    <!-- <svg aria-label="Placeholder: Thumbnail" class="bd-placeholder-img card-img-top" height="225" preserveAspectRatio="xMidYMid slice" role="img" width="100%" xmlns="http://www.w3.org/2000/svg">
                                        <title>Placeholder</title>
                                        <rect width="100%" height="100%" fill="#55595c"></rect>
                                        <text x="50%" y="50%" fill="#eceeef" dy=".3em">{{ product.name }}</text>
                                    </svg> -->
                                    <img class="card-img-top img-fluid" ng-src="<?= base_url() ?>{{ product.image_url }}" alt="{{ product.name }}"
                                    style="height: 225px; object-fit: cover;" />

                                    <div class="card-body">
                                        <h5>{{ product.name }}</h5>
                                        <p class="card-text">{{ product.description }}</p>

                                        <div class="d-flex justify-content-end align-items-center">
                                            <small class="text-body-secondary">{{ product.price }}</small>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <script>
            angular.module('myApp').controller('categoryProductListView', function($scope, $http, $window) {

                const pathParts = $window.location.pathname.split('/');
                const categoryId = pathParts[3];
                const categorySlug = pathParts[4];

                $scope.productList = [];


                $http.get('<?= base_url('/api/ec/getCategoryProductList/') ?>' + categoryId + '/' + categorySlug)
                    .then((res) => {
                        $scope.productList = res.data;
                    })
                    .catch((err) => {
                        // alert('Error retriveing data. See console for details.');
                        console.error(err);
                    })

        });
        </script>