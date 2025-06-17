<div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?= $mode ?> Company</h1>
    </div>

    <form ng-controller="companyFormCtrl" name="companyForm" ng-submit="submitForm()" novalidate>
        <input ng-model="mode" type="hidden" name="mode">
        <input ng-model="id" type="hidden" name="id">

        <div class="form-group my-2">
            <label class="form-label" for="company_name">Company Name</label>
            <input ng-model="company_name" name="company_name" id="company_name" type="text" class="form-control"  required>
            <div ng-messages="companyForm.company_name.$error" ng-if="submitted" class="text-danger">
                <div ng-message="required">Company name is required.</div>
            </div>
        </div>
        
        <div class="form-group my-2">
            <label class="form-label" for="company_desc">Description</label>
            <textarea ng-model="company_desc" class="form-control" name="company_desc" id="company_desc" required></textarea>
            <div ng-messages="companyForm.company_desc.$error" ng-if="submitted" class="text-danger">
                <div ng-message="required">Company description is required.</div>
            </div>
        </div>

        <div class="form-group my-2">
            <label class="form-label" for="company_addr">Address</label>
            <input ng-model="company_addr" name="company_addr" id="company_addr" type="text" class="form-control" required>
            <div ng-messages="companyForm.company_addr.$error" ng-if="submitted" class="text-danger">
                <div ng-message="required">Company address is required.</div>
            </div>
        </div>

        <div class="form-group my-2">
            <label class="form-label" for="company_email">Email</label>
            <input ng-model="company_email" name="company_email" id="company_email" type="text" class="form-control" required>
            <div ng-messages="companyForm.company_email.$error" ng-if="submitted" class="text-danger">
                <div ng-message="required">Company email is required.</div>
            </div>
        </div>

        <div class="form-group my-2">
            <label class="form-label" for="company_phonenum">Phone Number</label>
            <input ng-model="company_phonenum" name="company_phonenum" id="company_phonenum" type="text" class="form-control" required>
            <div ng-messages="companyForm.company_phonenum.$error" ng-if="submitted" class="text-danger">
                <div ng-message="required">Company phone number is required.</div>
            </div>
        </div>
        
        <div class="form-group my-2">
            <label class="form-label" for="company_brn">Business Registration Number</label>
            <input ng-model="company_brn" name="company_brn" id="company_brn" type="text" class="form-control" required>
            <div ng-messages="companyForm.company_brn.$error" ng-if="submitted" class="text-danger">
                <div ng-message="required">Company business registration number is required.</div>
            </div>
        </div>

        <div class="form-group my-2">
            <label class="form-label" for="company_status">Status</label>
            <select class="form-control" ng-model="company_status" name="company_status" id="company_status" required>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            <div ng-messages="companyForm.company_status.$error" ng-if="submitted" class="text-danger">
                <div ng-message="required">Company status is required.</div>
            </div>
        </div>

        <input class="btn btn-sm btn-primary my-2" type="submit" value="<?= $mode ?>" />

    </form>
</div>

<script>
    angular.module('myApp').controller('companyFormCtrl', function($scope, $http, $window) {
        // ifelse for the error message
        $scope.submitted = false;

        $scope.mode             = '<?= $mode ?>';
        $scope.id               = '<?= isset($id) ? $id : ''?>';
        $scope.company_name     = '<?= esc(isset($companyData) ? $companyData['name'] : '') ?>';
        $scope.company_desc     = '<?= esc(isset($companyData) ? $companyData['description'] : '') ?>';
        $scope.company_addr     = '<?= esc(isset($companyData) ? $companyData['address'] : '') ?>';
        $scope.company_email    = '<?= esc(isset($companyData) ? $companyData['email'] : '') ?>';
        $scope.company_phonenum = '<?= esc(isset($companyData) ? $companyData['phonenum'] : '') ?>';
        $scope.company_brn      = '<?= esc(isset($companyData) ? $companyData['brn'] : '') ?>';
        $scope.company_status   = '<?= esc(isset($companyData) ? $companyData['status'] : '') ?>';

        $scope.submitForm = function () {
            if(confirm("Are you sure?")) {
                // set to true for form validation
                $scope.submitted = true;

                if($scope.companyForm.$valid) {
                    const postData = {
                        'mode'            : $scope.mode,
                        'id'              : $scope.id,
                        'name'            : $scope.company_name,
                        'description'     : $scope.company_desc,
                        'address'         : $scope.company_addr,
                        'email'           : $scope.company_email,
                        'phonenum'        : $scope.company_phonenum,
                        'brn'             : $scope.company_brn,
                        'status'          : $scope.company_status
                    };

                    $http.post('<?= base_url('company_submit') ?>', postData)
                        .then((res) => {
                            if(res.data.status == "Success") {
                                alert('Done');
                                $window.location.href = '<?= base_url() ?>'
                            }
                        })
                        .catch((err) => {
                            alert('Error submitting form. See console for details.');
                            console.error(err);
                        })
                }

            } else {
                alert('Operation cancel.')
            }
        }
    });
</script>