        <main>
            <section class="py-5 text-center container">
                <div class="row">
                    <div class="col-lg-6 col-md-8 mx-auto">
                        <h1 class="fw-light">Cart</h1>
                    </div>
                </div>
            </section>
            <div class="album py-5 bg-body-tertiary">
            <div class="container" ng-controller="checkoutSuccessCtrl">
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm p-3">
                            <h5 class="card-title">Success</h5>
                            <hr>
                            <p>Your order id = {{ sn }} <br/><a class="text-muted" href="<?= base_url('ec/category') ?>">Go back to category</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
<script>
angular.module('myApp').controller('checkoutSuccessCtrl', function($scope, $rootScope, $http, $window, cartService) {
    const sn = new URLSearchParams(window.location.search).get('serial_num');
    $scope.sn = sn;
})


</script>