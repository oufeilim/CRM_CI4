<div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?= $mode ?> Category</h1>
    </div>

    <form ng-controller="categoryFormCtrl" name="categoryForm" ng-submit="submitForm()" novalidate>
        <input ng-model="mode" type="hidden" name="mode">
        <input ng-model="id" type="hidden" name="id">

        <div class="form-group my-2">
            <label class="form-label" for="title">Category title</label>
            <input ng-model="title" name="title" id="title" type="text" class="form-control"  required>
            <div ng-messages="categoryForm.title.$error" ng-if="submitted" class="text-danger">
                <div ng-message="required">Title is required.</div>
            </div>
        </div>

        <div class="form-group my-2">
            <label class="form-label" for="desc">Description</label>
            <textarea ng-model="desc" class="form-control" name="desc" id="desc" required></textarea>
            <div ng-messages="categoryForm.desc.$error" ng-if="submitted" class="text-danger">
                <div ng-message="required">Email is required.</div>
            </div>
        </div>

        <div class="form-group my-2">
            <label class="form-label" for="priority">Priority</label>
            <input ng-model="priority" name="priority" id="priority" type="text" class="form-control" required>
            <div ng-messages="categoryForm.priority.$error" ng-if="submitted" class="text-danger">
                <div ng-message="required">Priority is required.</div>
            </div>
        </div>

        <hr />

        <input class="btn btn-sm btn-primary my-2" type="submit" value="<?= $mode ?>" />

    </form>
</div>

<script>
    angular.module('myApp').controller('categoryFormCtrl', function($scope, $http, $window) {

        // ifelse for the error message
        $scope.submitted = false;

        $scope.mode             = '<?= $mode ?>';
        $scope.id               = '<?= isset($id) ? $id : ''?>';
        $scope.title        = '<?= esc(isset($categoryData) ? $categoryData['title'] : '') ?>';
        $scope.desc       = '<?= esc(isset($categoryData) ? $categoryData['description'] : '') ?>';
        $scope.priority    = '<?= esc(isset($categoryData) ? $categoryData['priority'] : '') ?>';

        $scope.submitForm = function () {
            if(confirm("Are you sure?")) {
                //set to true for form validation
                $scope.submitted = true;

                if($scope.categoryForm.$valid) {
                    const postData = {
                        'mode'            : $scope.mode,
                        'id'              : $scope.id,
                        'title'           : $scope.title,
                        'description'     : $scope.desc,
                        'priority'        : $scope.priority,
                    };

                    $http.post('<?= base_url('category_submit') ?>', postData)
                        .then((res) => {
                            if(res.data.status == "Success") {
                                alert('Done');
                                $window.location.href = '<?= base_url('category_list') ?>'
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