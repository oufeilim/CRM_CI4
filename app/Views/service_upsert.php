<div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?= $mode ?> Service</h1>
    </div>

    <form ng-controller="serviceFormCtrl" name="serviceForm" ng-submit="submitForm()" enctype="multipart/form-data" novalidate>
        <input ng-model="mode" type="hidden" name="mode">
        <input ng-model="id" type="hidden" name="id">

        <div class="form-group my-2">
            <label class="form-label" for="title">Title</label>
            <input ng-model="title" name="title" id="title" type="text" class="form-control"  required>
            <div ng-messages="serviceForm.title.$error" ng-if="submitted" class="text-danger">
                <div ng-message="required">Title is required.</div>
            </div>
        </div>

        <div class="form-group my-2">
            <label class="form-label" for="desc">Description</label>
            <textarea ng-model="desc" class="form-control" name="desc" id="desc" required></textarea>
            <div ng-messages="serviceForm.desc.$error" ng-if="submitted" class="text-danger">
                <div ng-message="required">Description is required.</div>
            </div>
        </div>

        <div class="row">
            <div class="col-4 form-group my-2">
                <label class="form-label" for="service_type">Service Type</label>
                <select class="form-control" ng-model="service_type" name="service_type" id="service_type" required>
                    <option value="0">Domestic</option>
                    <option value="1">International</option>
                </select>
                <div ng-messages="serviceForm.service_type.$error" ng-if="submitted" class="text-danger">
                    <div ng-message="required">Service Type is required.</div>
                </div>
            </div>

            <div class="col-4 form-group my-2">
                <label class="form-label" for="status">Status</label>
                <select class="form-control" ng-model="status" name="status" id="status" required>
                    <option value="0">Inactive</option>
                    <option value="1">Active</option>
                </select>
                <div ng-messages="serviceForm.status.$error" ng-if="submitted" class="text-danger">
                    <div ng-message="required">Status is required.</div>
                </div>
            </div>

            <div class="col-4 form-group my-2">
                <label class="form-label" for="base_weight">Base Weight (kg)</label>
                <input ng-model="base_weight" name="base_weight" id="base_weight" type="number" min="1" step="0.01" class="form-control" required>
                <div ng-messages="serviceForm.base_weight.$error" ng-if="submitted" class="text-danger">
                    <div ng-message="required">Base Weight is required.</div>
                </div>
            </div>
        </div>

        <div class="form-group my-2">
            <label class="form-label" for="logo">Image</label>
            <input ng-model="logo" type="file" onchange="angular.element(this).scope().fileSelected(this)" name="logo" id="logo" accept="image/*" class="form-control">
            
            <div class="mt-2" ng-if="logoPreview">
                <img ng-src="{{ logoPreview }}" alt="Selected logo" class="img-thumbnail" style="max-width: 200px;">
            </div>

            <div class="form-group mt-2" ng-if="!logoPreview && ori_logo_url">
                <label class="form-label d-block" for="ori_logo">Original Logo</label>
                <img name="ori_logo" id="ori_logo" ng-src="{{ baseUrl + 'uploads/service/logo/' + ori_logo_url }}" alt="Selected logo" class="img-thumbnail mt-1" style="max-width: 200px;">
            </div>
        </div>

        <hr />

        <input class="btn btn-sm btn-primary my-2" type="submit" value="<?= $mode ?>" />

    </form>
</div>

<script>
    angular.module('myApp').controller('serviceFormCtrl', function($scope, $http, $window) {

        $scope.logoPreview = '';

        $scope.fileSelected = function(input) {
            const file = input.files[0];

            if(file && file.type.startsWith('image/')) {
                $scope.logo = file;
                const reader = new FileReader();

                reader.onload = function(e) {
                    $scope.$apply(function () {
                        $scope.logoPreview = e.target.result;
                    });
                };

                reader.readAsDataURL(file);
            } else {
                $scope.$apply(function () {
                    $scope.logoPreview = '';
                    $scope.logoFile = null;
                });
            }

        };

        // ifelse for the error message
        $scope.submitted = false;

        $scope.mode             = '<?= $mode ?>';
        $scope.id               = '<?= isset($id) ? $id : ''?>';
        
        $scope.title            = '<?= esc(isset($serviceData) ? $serviceData['title'] : '') ?>';
        $scope.desc             = '<?= esc(isset($serviceData) ? $serviceData['description'] : '') ?>';
        $scope.service_type     = '<?= esc(isset($serviceData) ? $serviceData['service_type'] : 0) ?>';
        $scope.status           = '<?= esc(isset($serviceData) ? $serviceData['status'] : 1) ?>';
        $scope.base_weight      = <?= isset($serviceData) ? $serviceData['base_weight'] : 1 ?>;

        $scope.baseUrl          = '<?= base_url() ?>';
        $scope.ori_logo_url     = '<?= esc(isset($serviceData) ? basename($serviceData['logo']) : '' ) ?>';

        $scope.submitForm = function () {
            if(confirm("Are you sure?")) {
                //set to true for form validation
                $scope.submitted = true;

                if($scope.serviceForm.$valid) {

                    const formData = new FormData();

                    formData.append('mode', $scope.mode);
                    formData.append('id', $scope.id);
                    formData.append('title', $scope.title);
                    formData.append('description', $scope.desc);
                    formData.append('service_type', $scope.service_type);
                    formData.append('status', $scope.status);
                    formData.append('base_weight', $scope.base_weight);

                    if($scope.logo) {
                        formData.append('logo', $scope.logo);
                    }

                    $http.post('<?= base_url('service_submit') ?>', formData, {
                            'headers': { 'Content-Type': undefined },
                            transformRequest: angular.identity
                        })
                        .then((res) => {
                            if(res.data.status == "Success") {
                                alert('Done');
                                $window.location.href = '<?= base_url('service_list') ?>'
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