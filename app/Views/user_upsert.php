<div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?= $mode ?> User</h1>
    </div>

    <form ng-controller="userFormCtrl" name="userForm" ng-submit="submitForm()" novalidate>
        <input ng-model="mode" type="hidden" name="mode">
        <input ng-model="id" type="hidden" name="id">

        <div class="form-group my-2">
            <label class="form-label" for="user_name">Name</label>
            <input ng-model="user_name" name="user_name" id="user_name" type="text" class="form-control"  required>
            <div ng-messages="userForm.user_name.$error" ng-if="submitted" class="text-danger">
                <div ng-message="required">Name is required.</div>
            </div>
        </div>

        <div class="form-group my-2">
            <label class="form-label" for="user_email">Email</label>
            <input ng-model="user_email" name="user_email" id="user_email" type="text" class="form-control" required>
            <div ng-messages="userForm.user_email.$error" ng-if="submitted" class="text-danger">
                <div ng-message="required">Email is required.</div>
            </div>
        </div>

        <div class="form-group my-2">
            <label class="form-label" for="user_phonenum">Phone Number</label>
            <input ng-model="user_phonenum" name="user_phonenum" id="user_phonenum" type="text" class="form-control" required>
            <div ng-messages="userForm.user_phonenum.$error" ng-if="submitted" class="text-danger">
                <div ng-message="required">Phone number is required.</div>
            </div>
        </div>

        <hr />

        <div class="form-group my-2">
            <label class="mb-2">Company</label>
            <div ng-repeat="company in companyList">
                <label>
                    <input type="checkbox" ng-checked="isCompanySelected(company.company_id)" ng-click="toggleClick(company.company_id)">
                    {{ company.name }}
                </label>
            </div>
        </div>
            

        <input class="btn btn-sm btn-primary my-2" type="submit" value="<?= $mode ?>" />

    </form>
</div>

<script>
    angular.module('myApp').controller('userFormCtrl', function($scope, $http, $window) {
        // company list
        $scope.companyList = [];
        $scope.selectedCompanyList = <?= json_encode($cuData ?? []) ?> || [];

        // get company list
        $http.get('<?= base_url('api/fetchCompanyList') ?>')
        .then((res) => {
            console.log(res.data);
            $scope.companyList = res.data;
        })
        .catch((err) => {
            alert('Error retrive company. See console for details.');
            console.error(err);
        })
        
        // Add into selected array
        $scope.toggleClick = function (id) {
            const idx = $scope.selectedCompanyList.indexOf(id);
            if (idx > -1) {
                // Already selected, remove it
                $scope.selectedCompanyList.splice(idx, 1);
            } else {
                // Not selected, add it
                $scope.selectedCompanyList.push(id);
            }
            console.log($scope.selectedCompanyList);
        }

        // Check if checked, push the value into array
        $scope.isCompanySelected = function(id) {
            return $scope.selectedCompanyList.includes(id);
        };

        // ifelse for the error message
        $scope.submitted = false;

        $scope.mode             = '<?= $mode ?>';
        $scope.id               = '<?= isset($id) ? $id : ''?>';
        $scope.user_name        = '<?= esc(isset($userData) ? $userData['name'] : '') ?>';
        $scope.user_email       = '<?= esc(isset($userData) ? $userData['email'] : '') ?>';
        $scope.user_phonenum    = '<?= esc(isset($userData) ? $userData['phonenum'] : '') ?>';

        $scope.submitForm = function () {
            if(confirm("Are you sure?")) {
                //set to true for form validation
                $scope.submitted = true;

                if($scope.userForm.$valid) {
                    const postData = {
                        'mode'            : $scope.mode,
                        'id'              : $scope.id,
                        'name'            : $scope.user_name,
                        'email'           : $scope.user_email,
                        'phonenum'        : $scope.user_phonenum,
                        'company_user'    : $scope.selectedCompanyList
                    };

                    $http.post('<?= base_url('user_submit') ?>', postData)
                        .then((res) => {
                            if(res.data.status == "Success") {
                                alert('Done');
                                $window.location.href = '<?= base_url('user_list') ?>'
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