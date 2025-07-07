<div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?= $mode ?> Topup Request</h1>
    </div>

    <form ng-controller="topupRequestFormCtrl" name="topupRequestForm" ng-submit="submitForm()" enctype="multipart/form-data" novalidate>
        <input ng-model="mode" type="hidden" name="mode">
        <input ng-model="id" type="hidden" name="id">

        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6 my-2">
                        <label class="form-label" for="serial_no">Serial No.</label>
                        <input ng-model="serial_no" class="form-control" type="text" name="serial_no" id="serial_no" disabled>
                    </div>
    
                    <div class="col-md-6 my-2">
                        <label class="form-label" for="date">Date</label>
                        <input ng-model="date" class="form-control" type="text" name="date" id="date">
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-md-6 my-2">
                        <label class="form-label" for="user">User</label>
                        <select class="form-control" ng-model="user" name="user" id="user" ng-options="user.user_id as user.name for user in userList" ng-change="onUserChange()" required>
                            <option value="" disabled>-- Select User --</option>
                        </select>
                        <div ng-messages="topupRequestForm.user.$error" ng-if="submitted" class="text-danger">
                            <div ng-message="required">User is required.</div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 my-2">
                        <label class="form-label" for="name">Name</label>
                        <input ng-model="name" class="form-control" type="text" name="name" id="name" required>
                        <div ng-messages="topupRequestForm.name.$error" ng-if="submitted" class="text-danger">
                            <div ng-message="required">Name is required.</div>
                        </div>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-md-6 my-2">
                        <label class="form-label" for="email">Email</label>
                        <input ng-model="email" name="email" id="email" type="text" class="form-control" required>
                        <div ng-messages="topupRequestForm.email.$error" ng-if="submitted" class="text-danger">
                            <div ng-message="required">Email is required.</div>
                        </div>
                    </div>
    
                    <div class="col-md-6 my-2">
                        <label class="form-label" for="mobile">Mobile</label>
                        <input ng-model="mobile" name="mobile" id="mobile" type="text" class="form-control" required>
                        <div ng-messages="topupRequestForm.mobile.$error" ng-if="submitted" class="text-danger">
                            <div ng-message="required">Mobile is required.</div>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="col-md-6">
                <!-- <div class="row">
                    <div class="my-2">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select" name="status" id="status" ng-model="status">
                            <option value="0">Pending</option>
                            <option value="1">Approved</option>
                            <option value="2">Rejected</option>
                        </select>
                    </div>
                </div> -->

                <div class="row">
                    <div class="my-2" style="min-height: 70px;">
                        <label class="form-label d-block">Status</label>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="status_pending" ng-model="radio.status" value="0">
                            <label class="form-check-label" for="status_pending">Pending</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="status_approved" ng-model="radio.status" value="1">
                            <label class="form-check-label" for="status_approved">Approved</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="status_rejected" ng-model="radio.status" value="2">
                            <label class="form-check-label" for="status_rejected">Rejected</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 my-2">
                        <label class="form-label" for="amount">Amount</label>
                        <input type="text" ng-model="amount" class="form-control" name="amount" id="amount" required>
                        <div ng-messages="topupRequestForm.amount.$error" ng-if="submitted" class="text-danger">
                            <div ng-message="required">Amount is required.</div>
                        </div>
                    </div>

                    <div class="col-md-6 my-2">
                        <label class="form-label" for="payment_date">Payment Date</label>
                        <input ng-model="payment_date" class="form-control" type="text" name="payment_date" id="payment_date">
                    </div>
                </div>

                <div class="row">
                    <div class="my-2" style="min-height: 70px;">
                        <label class="form-label d-block">Payment Method</label>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="bank_transfer" ng-model="radio.payment_method" value="0">
                            <label class="form-check-label" for="bank_transfer">Bank Transfer</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="payment_gateway" ng-model="radio.payment_method" value="1">
                            <label class="form-check-label" for="payment_gateway">Payment Gateway</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="e-wallet" ng-model="radio.payment_method" value="2">
                            <label class="form-check-label" for="e-wallet">E-wallet</label>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="my-2" style="min-height: 70px;">
                        <label class="form-label d-block">Payment Status</label>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="payment_status_pending" ng-model="radio.payment_status" value="0">
                            <label class="form-check-label" for="payment_status_pending">Pending</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="payment_status_paid" ng-model="radio.payment_status" value="1">
                            <label class="form-check-label" for="payment_status_paid">Paid</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="payment_status_failed" ng-model="radio.payment_status" value="2">
                            <label class="form-check-label" for="payment_status_failed">Failed</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="my-2">
                        <label class="form-label" for="admin_remark">Admin Remark</label>
                        <textarea ng-model="admin_remark" class="form-control" name="admin_remark" id="admin_remark"></textarea>
                    </div>
                </div>

                <input class="btn btn-sm btn-primary my-3" type="submit" value="<?= $mode ?>" />
            </div>

            <div class="col-md-6">
                <div class="row">
                    <div class="my-2">
                        <label class="form-label" for="attachment">Attachment</label>
                        <input ng-model="attachment" type="file" onchange="angular.element(this).scope().fileSelected(this)" name="attachment" id="attachment" accept="image/*" class="form-control">

                        <div class="mt-2" ng-if="attachmentPreview">
                            <img ng-src="{{ attachmentPreview }}" alt="Selected Attachment" class="img-fluid">
                        </div>

                        <div ng-if="!attachmentPreview && ori_attachment_url">
                            <div class="mt-2">
                                <label class="form-label d-block" for="ori_attachment">Original Attachment</label>
                                <img name="ori_attachment" id="ori_attachment" ng-src="{{ baseUrl + 'uploads/wallet_log/' + ori_attachment_url }}" alt="Selected attachment" class="img-fluid mt-1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

<script>
    angular.module('myApp').controller('topupRequestFormCtrl', function($scope, $timeout, $http, $window) {
        // #region Datepicker
        $timeout(function() {
            const commonOptions = {
                dateFormat: "Y-m-d",
                time_24hr: true,
                defaultDate: $scope.date ? new Date($scope.date) : new Date(),
                onReady: function(selectedDates, dateStr, instance) {
                    const inputId = instance.input.id;
                    if (inputId === 'date') {
                        $scope.date = dateStr;
                    } else if (inputId === 'payment_date') {
                        $scope.payment_date = dateStr;
                    }
                    $scope.$apply();
                },
                onChange: function(selectedDates, dateStr, instance) {
                    const inputId = instance.input.id;
                    if(inputId === 'date') {
                        $scope.date = dateStr;
                    } else if (inputId === 'payment_date') {
                        $scope.payment_date = dateStr;
                    }
                    $scope.$apply();
                }
            }

            flatpickr("#date", commonOptions);
            flatpickr("#payment_date", commonOptions);
        }, 0);
        // #endregion

        // init
        $scope.userList = [];
        $scope.radio = {
            status: '',
            payment_method: '',
            payment_status: '',
        }
        $scope.submitted = false;

        $http.get('<?= base_url('api/fetchUserList') ?>')
                .then((res) => {
                    $scope.userList = res.data;
                    $scope.onUserChange();
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

        $scope.onUserChange = function () {
            let user_id = $scope.user;
            const selectedUser = $scope.userList.find(u => u.user_id === user_id);
            if (selectedUser) {
                $scope.user = selectedUser.user_id;
                $scope.name = selectedUser.name;
                $scope.email = selectedUser.email;
                $scope.mobile = selectedUser.phonenum;
            }
        }

        $scope.mode                   = '<?= $mode ?>';
        $scope.id                     = '<?= isset($id) ? $id : ''?>';
        $scope.serial_no              = '<?= esc(isset($topupRequestData) ? $topupRequestData['serial_no'] : '') ?>'
        $scope.user                   = '<?= esc(isset($topupRequestData) ? $topupRequestData['user_id'] : '') ?>';
        $scope.amount                 = '<?= esc(isset($topupRequestData) ? $topupRequestData['amount'] : '0.00') ?>';
        $scope.radio.status           = '<?= esc(isset($topupRequestData) ? $topupRequestData['status'] : '0') ?>';
        $scope.radio.payment_method   = '<?= esc(isset($topupRequestData) ? $topupRequestData['payment_method'] : '0') ?>';
        $scope.date                   = '<?= esc(isset($topupRequestData) ? $topupRequestData['date'] : '') ?>';
        $scope.radio.payment_status   = '<?= esc(isset($topupRequestData) ? $topupRequestData['payment_status'] : '0') ?>';
        $scope.admin_remark           = '<?= esc(isset($topupRequestData) ? $topupRequestData['admin_remark'] : '') ?>';

        $scope.baseUrl                = '<?= base_url() ?>';
        $scope.ori_attachment_url     = '<?= esc(isset($topupRequestData) ? basename($topupRequestData['attachment']) : '' ) ?>';

        $scope.submitForm = function () {
            if(confirm("Are you sure?")) {
                // set to true for form validation
                $scope.submitted = true;

                if(+$scope.amount < 0) {
                    alert('Topup amount cannot be negative!');
                    return;
                }

                if($scope.topupRequestForm.$valid) {
                    const postData = new FormData();

                    postData.append('mode', $scope.mode);
                    postData.append('id', $scope.id);
                    postData.append('serial_no', $scope.serial_no);
                    postData.append('date', $scope.date);
                    postData.append('user_id', $scope.user);
                    postData.append('name', $scope.name);
                    postData.append('email', $scope.email);
                    postData.append('mobile', $scope.mobile);
                    postData.append('amount', $scope.amount);
                    postData.append('status', $scope.radio.status);
                    postData.append('payment_method', $scope.radio.payment_method);
                    postData.append('payment_date', $scope.payment_date);
                    postData.append('payment_status', $scope.radio.payment_status);
                    postData.append('admin_remark', $scope.admin_remark);

                    if($scope.attachment) {
                        postData.append('attachment', $scope.attachment);
                    }

                    for (let [key, value] of postData.entries()) {
                        console.log(key, value);

                        if (value instanceof File) {
                            console.log('File name:', value.name);
                            console.log('File size:', value.size);
                            console.log('File type:', value.type);
                        }
                    }

                    $http.post('<?= base_url('topup_request_submit') ?>', postData, {
                        'headers': { 'Content-Type': undefined },
                        transformRequest: angular.identity,
                    })
                        .then((res) => {
                            if(res.data.status == "Success") {
                                alert('Done');
                                $window.location.href = '<?= base_url('topup_request_list') ?>'
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