
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Sales Order List</h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <div class="btn-group me-2">
                                <a class="btn btn-sm btn-outline-primary" href="<?= base_url('sales_order_upsert') ?>" >
                                    <!-- Add SVG -->
                                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive small">
                        <table ng-controller="salesOrderListTable" class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col" style="width: 20%">Serial Number</th>
                                    <th scope="col" style="width: 15%">User</th>
                                    <th scope="col" style="width: 15%">Order Date</th>
                                    <th scope="col" style="width: 10%">Final Amount</th>
                                    <th scope="col" style="width: 15%">Order Status</th>
                                    <th scope="col" style="width: 15%">Payment Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="x in salesOrderList">
                                    <td>{{ x.sales_order_id }}</td>
                                    <td>{{ x.serial_number }}</td>
                                    <td>{{ x.user_name }}</td>
                                    <td>{{ x.order_date }}</td>
                                    <td>{{ x.final_amount }}</td>
                                    <td>{{ x.order_status == 0 ? 'Pending' : x.order_status == 1 ? 'Success' : 'Cancel' }}</td>
                                    <td>{{ x.payment_status == 0 ? 'Pending' : x.payment_status == 1 ? 'Paid' : 'Failed' }}</td>
                                    <td>
                                        <div class="btn-toolbar mb-2 mb-md-0">
                                            <div class="btn-group me-2">
                                                <a href="<?= base_url('sales_order_upsert/') ?>{{x.sales_order_id}}" class="btn btn-sm btn-secondary">
                                                    <!-- Edit SVG -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                                    </svg>
                                                </a>
                                                <button type="button" ng-click="deleteSalesOrder(x.sales_order_id)" class="btn btn-sm btn-danger">
                                                    <!-- Delete SVG -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                                    </svg>
                                                </button>
                                                <a target="_blank" href="<?= base_url('sales_order_invoice/') ?>{{x.sales_order_id}}" class="btn btn-sm btn-warning">
                                                    <!-- Edit SVG -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                                                        <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/>
                                                        <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </main>
            </div>
        </div>

<script>
    angular.module('myApp').controller('salesOrderListTable', function($scope, $http) {

        $scope.salesOrderList = [];

        function loadSalesOrderList () {
            $http.get('<?= base_url('api/fetchSalesOrderList') ?>')
            .then((res) => {
                $scope.salesOrderList = res.data;
            })
            .catch((err) => {
                alert('Error retrive sales order. See console for details.');
                console.error(err);
            })
        }

         $scope.deleteSalesOrder = function (id) {
            if(confirm('Are you sure you want to delete this sales_order')) {
                $http.post('<?= base_url('sales_order_del') ?>', {
                    'id': id,
                })
                .then((res) => {
                    if(res.data.status == "Success") {
                        alert('Sales order deleted.')
                        loadSalesOrderList();
                    } else {
                        alert('Error delete sales order. See console for details.');
                        console.error(err);
                    }
                })
            } else {
                alert('Operation cancel.')
            }
        }

        loadSalesOrderList();
    });
</script>