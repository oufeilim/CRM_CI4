<div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?= $mode ?> Attribute</h1>
    </div>

    <form ng-controller="attributeFormCtrl" name="attributeForm" ng-submit="submitForm()" enctype="multipart/form-data" novalidate>
        <input ng-model="mode" type="hidden" name="mode">
        <input ng-model="id" type="hidden" name="id">

        <div class="form-group my-2">
            <label class="form-label" for="title">Title</label>
            <input ng-model="title" name="title" id="title" type="text" class="form-control"  required>
            <div ng-messages="attributeForm.title.$error" ng-if="submitted" class="text-danger">
                <div ng-message="required">Title is required.</div>
            </div>
        </div>

        <div class="row">
            <div class="col-6 form-group my-2">
                <label class="form-label" for="attributeParent">Parent</label>
                <select class="form-control" ng-model="attributeParent" name="attributeParent" id="attributeParent" ng-options="item.attribute_id as item.title for item in parentAttrList">
                </select>
            </div>

            <div class="col-6 form-group my-2">
                <label class="form-label" for="priority">Priority</label>
                <input ng-model="priority" name="priority" id="priority" type="text" class="form-control" required>
                <div ng-messages="attributeForm.priority.$error" ng-if="submitted" class="text-danger">
                    <div ng-message="required">Priority is required.</div>
                </div>
            </div>
        </div>        

        <div class="form-group my-2">
            <label class="form-label" for="desc">Description</label>
            <textarea ng-model="desc" class="form-control" name="desc" id="desc" required></textarea>
            <div ng-messages="attributeForm.desc.$error" ng-if="submitted" class="text-danger">
                <div ng-message="required">Description is required.</div>
            </div>
        </div>

        <hr />

        <input class="btn btn-sm btn-primary my-2" type="submit" value="<?= $mode ?>" />

    </form>
</div>

<script>
    angular.module('myApp').controller('attributeFormCtrl', function($scope, $http, $window) {
        
        $http.get('<?= base_url('api/fetchAttributeList') ?>')
            .then((res) => {
                $scope.attributeList = res.data;

                $scope.parentAttrList = $scope.attributeList.filter((item) => {
                    return item.parent_id == '0';
                });

                $scope.parentAttrList.unshift({
                    attribute_id: "0",
                    title: 'No Parent'
                });
            })
            .catch((err) => {
                $scope.parentAttrList = [];
                $scope.parentAttrList.unshift({
                    attribute_id: "0",
                    title: 'No Parent'
                });
                console.error(err);
            })

        // ifelse for the error message
        $scope.submitted = false;

        $scope.mode             = '<?= $mode ?>';
        $scope.id               = '<?= isset($id) ? $id : ''?>';
        
        $scope.title            = '<?= esc(isset($attributeData) ? $attributeData['title'] : '') ?>';
        $scope.attributeParent  = '<?= esc(isset($attributeData) ? $attributeData['parent_id'] : 0) ?>';
        $scope.priority         = '<?= esc(isset($attributeData) ? $attributeData['priority'] : 1) ?>';
        $scope.desc             = '<?= esc(isset($attributeData) ? $attributeData['description'] : '') ?>';

        $scope.submitForm = function () {
            if(confirm("Are you sure?")) {
                //set to true for form validation
                $scope.submitted = true;

                if($scope.attributeForm.$valid) {
                    const formData = {
                        'mode'          : $scope.mode,
                        'id'            : $scope.id,
                        'title'         : $scope.title,
                        'parent_id'     : $scope.attributeParent,
                        'priority'      : $scope.priority,
                        'description'   : $scope.desc,
                    }

                    $http.post('<?= base_url('attribute_submit') ?>', formData)
                        .then((res) => {
                            if(res.data.status == "Success") {
                                alert('Done');
                                $window.location.href = '<?= base_url('attribute_list') ?>'
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