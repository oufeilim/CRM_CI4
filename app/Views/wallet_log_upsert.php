<div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?= $mode ?> Wallet Log</h1>
    </div>

    <form ng-controller="walletLogFormCtrl" name="walletLogForm" ng-submit="submitForm()" enctype="multipart/form-data" novalidate>
        <input ng-model="mode" type="hidden" name="mode">
        <input ng-model="id" type="hidden" name="id">

        <div class="row">
            <div class="col-md-4 form-group my-2">
                <label class="form-label" for="user">User</label>
                <select class="form-control" ng-model="user" name="user" id="user" ng-options="user.user_id as user.name for user in userList" required>
                    <option value="" disabled>-- Select User --</option>
                </select>
                <div ng-messages="walletLogForm.user.$error" ng-if="submitted" class="text-danger">
                    <div ng-message="required">User is required.</div>
                </div>
            </div>
            
            <div class="col-md-4 form-group my-2">
                <label class="form-label" for="amount">Amount</label>
                <input ng-model="amount" class="form-control" type="text" name="amount" id="amount" required>
                <div ng-messages="walletLogForm.amount.$error" ng-if="submitted" class="text-danger">
                    <div ng-message="required">Amount is required.</div>
                </div>
            </div>

            <div class="col-md-4 form-group my-2">
                <label class="form-label" for="balance">Balance</label>
                <input ng-model="balance" name="balance" id="balance" type="text" class="form-control" required>
                <div ng-messages="walletLogForm.balance.$error" ng-if="submitted" class="text-danger">
                    <div ng-message="required">Balance is required.</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 form-group my-2">
                <label class="form-label" for="title">Title</label>
                <input ng-model="title" name="title" id="title" type="text" class="form-control">
            </div>

            <div class="col-md-4 form-group my-2">
                <label class="form-label" for="ref_table">Reference Table</label>
                <input ng-model="ref_table" name="ref_table" id="ref_table" type="text" class="form-control">
            </div>

            <div class="col-md-4 form-group my-2">
                <label class="form-label" for="ref_id">Reference ID</label>
                <input type="text" ng-model="ref_id" class="form-control" name="ref_id" id="ref_id">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group my-2">
                <label class="form-label" for="desc">Description</label>
                <textarea ng-model="desc" class="form-control" name="desc" id="desc"></textarea>
            </div>

            <div class="col-md-6 form-group my-2">
                <label class="form-label" for="remark">Remark</label>
                <textarea ng-model="remark" class="form-control" name="remark" id="remark"></textarea>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <label class="form-label" for="attachment">Attachment</label>
                <input ng-model="attachment" type="file" onchange="angular.element(this).scope().fileSelected(this)" name="attachment" id="attachment" accept="image/*" class="form-control">
            </div>

            <div class="col-md-6" ng-if="!attachmentPreview && ori_attachment_url">
                <div class="mt-2" ng-if="attachmentPreview">
                    <img ng-src="{{ attachmentPreview }}" alt="Selected Attachment" class="img-thumbnail" style="max-width: 200px;">
                </div>

                <div class="mt-2">
                    <label class="form-label d-block" for="ori_attachment">Original Attachment</label>
                    <img name="ori_attachment" id="ori_attachment" ng-src="{{ baseUrl + 'uploads/wallet_log/' + ori_attachment_url }}" alt="Selected attachment" class="img-thumbnail mt-1" style="max-width: 200px;">
                </div>
            </div>
        </div>

        <input class="btn btn-sm btn-primary my-3" type="submit" value="<?= $mode ?>" />

    </form>
</div>

<script>
    angular.module('myApp').controller('walletLogFormCtrl', function($scope, $http, $window) {
        $scope.userList = [];
        $scope.submitted = false;

        $http.get('<?= base_url('api/fetchUserList') ?>')
                .then((res) => {
                    $scope.userList = res.data;
                })
                .catch((err) => {
                    $scope.userList = [];
                    console.log(err);
                })

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

        $scope.mode               = '<?= $mode ?>';
        $scope.id                 = '<?= isset($id) ? $id : ''?>';
        $scope.user               = '<?= esc(isset($walletLogData) ? $walletLogData['user_id'] : '') ?>';
        $scope.amount             = '<?= esc(isset($walletLogData) ? $walletLogData['amount'] : '0.00') ?>';
        $scope.balance            = '<?= esc(isset($walletLogData) ? $walletLogData['balance'] : '0.00') ?>';
        $scope.title              = '<?= esc(isset($walletLogData) ? $walletLogData['title'] : '') ?>';
        $scope.ref_table          = '<?= esc(isset($walletLogData) ? $walletLogData['ref_table'] : '') ?>';
        $scope.ref_id             = '<?= esc(isset($walletLogData) ? $walletLogData['ref_id'] : '0') ?>';
        $scope.desc               = '<?= esc(isset($walletLogData) ? $walletLogData['description'] : '') ?>';
        $scope.remark             = '<?= esc(isset($walletLogData) ? $walletLogData['remark'] : '') ?>';

        $scope.baseUrl            = '<?= base_url() ?>';
        $scope.ori_attachment_url = '<?= esc(isset($walletLogData) ? basename($walletLogData['attachment']) : '' ) ?>';

        $scope.submitForm = function () {
            if(confirm("Are you sure?")) {
                // set to true for form validation
                $scope.submitted = true;

                if($scope.walletLogForm.$valid) {
                    const postData = new FormData();

                    postData.append('mode', $scope.mode);
                    postData.append('id', $scope.id);
                    postData.append('user_id', $scope.user);
                    postData.append('amount', $scope.amount);
                    postData.append('balance', $scope.balance);
                    postData.append('title', $scope.title);
                    postData.append('ref_table', $scope.ref_table);
                    postData.append('ref_id', $scope.ref_id);
                    postData.append('desc', $scope.desc);
                    postData.append('remark', $scope.remark);

                    if($scope.attachment) {
                        postData.append('attachment', $scope.attachment);
                    }

                    console.log($scope.attachment);

                    $http.post('<?= base_url('wallet_log_submit') ?>', postData, {
                        'headers': { 'Content-Type': undefined },
                        transformRequest: angular.identity,
                    })
                        .then((res) => {
                            if(res.data.status == "Success") {
                                alert('Done');
                                $window.location.href = '<?= base_url('wallet_log_list') ?>'
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