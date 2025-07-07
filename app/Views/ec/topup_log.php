        <main ng-controller="topupLogListCtrl">
            <section class="py-5 text-center container">
                <div class="row">
                    <div class="col-lg-6 col-md-8 mx-auto">
                        <h1 class="fw-light">Wallet Log</h1>
                    </div>
                </div>
            </section>
            <div class="album py-5 bg-body-tertiary">
                <div class="container">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Topup Date</th>
                                    <th scope="col">Serial No</th>
                                    <th scope="col">Topup Amount</th>
                                    <th scope="col">Topup Method</th>
                                    <th scope="col">Payment Status</th>
                                    <th scope="col">Payment Date</th>
                                    <th scope="col">Topup Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="x in topupLogList">
                                    <td>{{ x.date }}</td>
                                    <td>{{ x.serial_no }}</td>
                                    <td>{{ x.amount }}</td>
                                    <td>{{ x.payment_method == '0' ? 'Bank Transfer' : x.payment_method == '1' ? 'Payment Gateway' : 'E-wallet' }}</td>
                                    <td>{{ x.payment_status == '0' ? 'Pending' : x.payment_status == 1 ? 'Paid' : 'Failed' }}</td>
                                    <td>{{ x.payment_status == '1' ? x.payment_date : '-' }}</td>
                                    <td>{{ x.status == '0' ? 'Pending' : x.status == '1' ? 'Approved' : 'Rejected' }}</td>
                                </tr>

                                <tr ng-if="topupLogList.length == 0">
                                    <td colspan="7">No topup existed.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

<script>
angular.module('myApp').controller('topupLogListCtrl', function($scope, $http) {
    $scope.topupLogList = <?= isset($topupLogList) ? $topupLogList : '[]' ?>;

});
</script>