<div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?= $mode ?> Promo Code</h1>
    </div>

    <form ng-controller="promoCodeFormCtrl" name="promoCodeForm" ng-submit="submitForm()" novalidate>
        <input ng-model="mode" type="hidden" name="mode">
        <input ng-model="id" type="hidden" name="id">

        <div class="form-group my-2">
            <label class="form-label" for="promo_code_name">Promo Code Name</label>
            <input ng-model="promo_code_name" name="promo_code_name" id="promo_code_name" type="text" class="form-control"  required>
            <div ng-messages="promoCodeForm.promo_code_name.$error" ng-if="submitted" class="text-danger">
                <div ng-message="required">Promo Code Name is required.</div>
            </div>
        </div>

        <div class="row">
            <div class="col-4 form-group my-2">
                <label class="form-label" for="promo_code_type">Promo Code Type</label>
                <select class="form-control" ng-model="promo_code_type" name="promo_code_type" id="promo_code_type" required>
                    <option value="0">Fixed</option>
                    <option value="1">Percentage</option>
                </select>
                <div ng-messages="promoCodeForm.promo_code_type.$error" ng-if="submitted" class="text-danger">
                    <div ng-message="required">Promo Code Type is required.</div>
                </div>
            </div>


            <div class="col-4 form-group my-2">
                <label class="form-label" for="promo_code_value">Value</label>
                <input ng-model="promo_code_value" name="promo_code_value" id="promo_code_value" type="text" class="form-control" required>
                <div ng-messages="promoCodeForm.promo_code_value.$error" ng-if="submitted" class="text-danger">
                    <div ng-message="required">Value is required.</div>
                </div>
            </div>

            <div class="col-4 form-group my-2">
                <label class="form-label" for="promo_code_maxcap">Max Cap</label>
                <input ng-model="promo_code_maxcap" name="promo_code_maxcap" id="promo_code_maxcap" type="text" class="form-control" ng-disabled="promo_code_type == 0" ng-required="promo_code_type == 1">
                <div ng-messages="promoCodeForm.promo_code_maxcap.$error" ng-if="submitted" class="text-danger">
                    <div ng-message="required">Max Cap is required.</div>
                </div>
            </div>
        </div>

        <hr />

        <input class="btn btn-sm btn-primary my-2" type="submit" value="<?= $mode ?>" />

    </form>
</div>

<script>
    angular.module('myApp').controller('promoCodeFormCtrl', function($scope, $http, $window) {

        $scope.submitted = false;

        $scope.mode = '<?= $mode ?>';
        $scope.id = '<?= isset($id) ? $id : ''?>';
        $scope.promo_code_name = '<?= esc(isset($promoCodeData) ? $promoCodeData['code'] : '') ?>';
        $scope.promo_code_type = '<?= esc(isset($promoCodeData) ? $promoCodeData['type'] : '') ?>';
        $scope.promo_code_value = '<?= esc(isset($promoCodeData) ? $promoCodeData['value'] : '') ?>';
        $scope.promo_code_maxcap = '<?= esc(isset($promoCodeData) ? $promoCodeData['max_cap'] : '') ?>';
        
        $scope.submitForm = function () {
            if(confirm('Do you want to add this promo code?')) {
                
                $scope.submitted = true;

                console.log($scope.promoCodeForm.$valid);

                if($scope.promoCodeForm.$valid) {
                    const formData = {
                        'mode': $scope.mode,
                        'id': $scope.id,
                        'promo_code_name': $scope.promo_code_name,
                        'promo_code_type': $scope.promo_code_type,
                        'promo_code_value': $scope.promo_code_value,
                        'promo_code_maxcap': $scope.promo_code_type == '0' ? '0' : $scope.promo_code_maxcap,
                    }

                    $http.post('<?= base_url('promo_code_submit') ?>', formData)
                            .then((res) => {
                                if(res.data.status == "Success") {
                                    alert('Done');
                                    $window.location.href = '<?= base_url('promo_code_list') ?>'
                                }
                            })
                            .catch((err) => {
                                console.log("Error submitting form, see console for details.");
                                console.log(err);
                            })

                }
            } else {
                alert('Operation cancel.')
            }

        }
        
    });
</script>