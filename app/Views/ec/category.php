        <main>
            <section class="py-5 text-center container">
                <div class="row py-lg-5 mb-3">
                    <div class="col-lg-6 col-md-8 mx-auto">
                        <h1 class="fw-light">Category</h1>
                    </div>
                </div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('ec') ?>">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Category</li>
                    </ol>
                </nav>
            </section>
            <div class="album py-5 bg-body-tertiary" ng-controller="categoryListView">
                <div class="container">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3" style="min-height: 300px;">
                        <div class="col" ng-repeat="category in categoryList">
                            <a class="text-decoration-none text-dark" href="<?= base_url('ec/category_product_list/') ?>{{ category.category_id + '/' + category.slug }}">
                                <div class="card shadow-sm">
                                    <svg aria-label="Placeholder: Thumbnail" class="bd-placeholder-img card-img-top" height="225" preserveAspectRatio="xMidYMid slice" role="img" width="100%" xmlns="http://www.w3.org/2000/svg">
                                        <title>Placeholder</title>
                                        <rect width="100%" height="100%" fill="#55595c"></rect>
                                        <text x="50%" y="50%" fill="#eceeef" dy=".3em">{{ category.title }}</text>
                                    </svg>
                                    <div class="card-body">
                                        <p class="card-text">{{ category.description }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <script>
            angular.module('myApp').controller('categoryListView', function($scope, $http) {

            $scope.categoryList = [];

            function loadCategoryList() {
                $http.get('<?= base_url('api/ec/fetchCategoryList') ?>')
                .then((res) => {
                    $scope.categoryList = res.data;
                })
                .catch((err) => {
                    alert('Error retriveing data. See console for details.');
                    console.error(err);
                })
            }

            loadCategoryList();
        });
        </script>