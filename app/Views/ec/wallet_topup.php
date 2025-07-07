        <main ng-controller="walletFormCtrl">
            <section class="py-5 text-center container">
                <div class="row">
                    <div class="col-lg-6 col-md-8 mx-auto">
                        <h1 class="fw-light">Wallet Topup</h1>
                    </div>
                </div>
            </section>
            <div class="album py-5 bg-body-tertiary">
                <div class="container">
                    <form action="">
                       <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="name" class="form-control" id="name" ng-model="formData.name">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" ng-model="formData.email">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="mobile" class="form-label">Mobile</label>
                                <input type="mobile" class="form-control" id="mobile" ng-model="formData.mobile">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="topup_amount" class="form-label">Topup Amount</label>
                                <input type="topup_amount" class="form-control" id="topup_amount" ng-model="formData.topup_amount">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="payment_method" class="form-label">Payment Method</label>
                                <select class="form-select" ng-model="formData.payment_method">
                                    <option value="0">Bank Transfer</option>
                                    <option value="1">Payment Gateway</option>
                                    <option value="2">E-wallet</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="attachment">Attachment</label>
                                    <input ng-model="attachment" type="file" onchange="angular.element(this).scope().fileSelected(this)" name="attachment" id="attachment" accept="image/*" class="form-control">
                                </div>
                                    
                                <div class="col-md-6">
                                    <div class="mt-2" ng-if="attachmentPreview">
                                        <img ng-src="{{ attachmentPreview }}" alt="Selected attachment" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-primary" ng-click="topupSubmit()" type="button">Topup</button>

                    </form>
                </div>
            </div>
        </main>

<script>
angular.module('myApp').controller('walletFormCtrl', function($scope, $http, $window) {
        // variable
        $scope.formData = {
            name            : '',
            email           : '',
            mobile          : '',
            topup_amount    : (100).toFixed(2),
            payment_method  : '0',
        }

        $scope.fileSelected = function(input) {
            const file = input.files[0];

            if(file && file.type.startsWith('image/')) {
                $scope.attachment = file;
                const reader = new FileReader();

                reader.onload = function(e) {
                    $scope.$apply(function () {
                        $scope.attachmentPreview = e.target.result;
                    });
                };

                reader.readAsDataURL(file);
            } else {
                $scope.$apply(function () {
                    $scope.attachmentPreview = '';
                    $scope.attachmentFile = null;
                });
            }

        };

        // assign value
        $http.post('<?= base_url('api/fetchUserOne') ?>', {user_id: 3})
                .then((res) => {
                    let userData = res.data;

                    $scope.formData.name    = userData.name;
                    $scope.formData.email   = userData.email;
                    $scope.formData.mobile  = userData.phonenum;
                })
                .catch((err) => {
                    console.log(err);
                })
        
        $scope.topupSubmit = function () {
            if(confirm('Do you want to topup?')) {
                let formData = new FormData();

                formData.append('mode', 'consumerAdd');
                formData.append('id', '');
                formData.append('user_id', '3');
                formData.append('name', $scope.formData.name);
                formData.append('email', $scope.formData.email);
                formData.append('mobile', $scope.formData.mobile);
                formData.append('amount', $scope.formData.topup_amount);
                formData.append('payment_method', $scope.formData.payment_method);

                if($scope.attachment) {
                    formData.append('attachment', $scope.attachment);
                }

                for (let [key, value] of formData.entries()) {
                    console.log(key, value);

                    if (value instanceof File) {
                        console.log('File name:', value.name);
                        console.log('File size:', value.size);
                        console.log('File type:', value.type);
                    }
                }

                $http.post('<?= base_url('topup_request_submit') ?>', formData, {
                        'headers': { 'Content-Type': undefined },
                        transformRequest: angular.identity,
                    })
                    .then((res) => {
                        if(res.data.status == 'Success') {
                            alert('Topup request submitted!');
                            $window.location.href = '<?= base_url('ec/topup_log') ?>'
                        }
                    })
            }
        }

});
</script>