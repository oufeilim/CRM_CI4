<div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?= $mode ?> Service Zone</h1>
    </div>

    <form ng-controller="serviceZoneFormCtrl" name="serviceZoneForm" ng-submit="submitForm()" enctype="multipart/form-data" novalidate>
        <input ng-model="mode" type="hidden" name="mode">
        <input ng-model="id" type="hidden" name="id">

        <div class="form-group my-2">
            <label class="form-label" for="service">Service</label>
            <select class="form-control" ng-model="service" name="service" id="service" ng-options="item.service_id as item.title for item in serviceList" required>
            </select>
            <div ng-messages="serviceZoneForm.service.$error" ng-if="submitted" class="text-danger">
                <div ng-message="required">Service is required.</div>
            </div>
        </div>

        <div class="row">
            <div class="col-6 form-group my-2">
                <label class="form-label" for="zone">Zone</label>
                <input ng-model="zone" name="zone" id="zone" type="text" class="form-control" required>
                <div ng-messages="serviceZoneForm.zone.$error" ng-if="submitted" class="text-danger">
                    <div ng-message="required">Zone is required.</div>
                </div>
            </div>

            <div class="col-6 form-group my-2">
                <label class="form-label" for="title">Title</label>
                <input ng-model="title" name="title" id="title" type="text" class="form-control" required>
                <div ng-messages="serviceZoneForm.title.$error" ng-if="submitted" class="text-danger">
                    <div ng-message="required">Title is required.</div>
                </div>
            </div>
        </div>

        <hr />

        <input class="btn btn-sm btn-primary my-2" type="submit" value="<?= $mode ?>" />

    </form>
</div>

<script>
    angular.module('myApp').controller('serviceZoneFormCtrl', function($scope, $http, $window) {

        $scope.serviceList = [];
        $http.get('<?= base_url('api/fetchServiceList') ?>')
                .then((res) => {
                    if(res.data.status == 'Error') {
                        $scope.serviceList = [];
                        console.log(res.data.message);
                        console.log(res.data.errors);
                    } else {
                        $scope.serviceList = res.data;
                    }
                })

        // ifelse for the error message
        $scope.submitted = false;

        $scope.mode             = '<?= $mode ?>';
        $scope.id               = '<?= isset($id) ? $id : ''?>';
        
        $scope.service          = '<?= esc(isset($serviceZoneData) ? $serviceZoneData['service_id'] : '') ?>';
        $scope.zone             = '<?= esc(isset($serviceZoneData) ? $serviceZoneData['zone'] : '') ?>';
        $scope.title            = '<?= esc(isset($serviceZoneData) ? $serviceZoneData['title'] : '') ?>';

        $scope.submitForm = function () {
            if(confirm("Are you sure?")) {
                //set to true for form validation
                $scope.submitted = true;

                if($scope.serviceZoneForm.$valid) {

                    const formData = {
                        'mode'      : $scope.mode,
                        'id'        : $scope.id,
                        'service'   : $scope.service,
                        'zone'      : $scope.zone,
                        'title'     : $scope.title,
                    };

                    $http.post('<?= base_url('service_zone_submit') ?>', formData)
                        .then((res) => {
                            if(res.data.status == "Success") {
                                alert('Done');
                                $window.location.href = '<?= base_url('service_zone_list') ?>'
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