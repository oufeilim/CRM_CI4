
                <main ng-controller="serviceZoneListTable" class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Service Zone List</h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <div class="btn-group me-2">
                                <a class="btn btn-sm btn-outline-primary" href="<?= base_url('service_zone_upsert') ?>" >
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
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 5%">#</th>
                                    <th scope="col" style="width: 20%">Service</th>
                                    <th scope="col" style="width: 20%">Zone</th>
                                    <th scope="col" style="width: 10%">Title</th>
                                    <th scope="col" style="width: 7%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="x in serviceZoneList" ng-if="serviceZoneList.length >= 1">
                                    <td>{{ x.service_zone_id }}</td>
                                    <td>{{ serviceNameList[x.service_id] || 'Unknown Service' }}</td>
                                    <td>{{ x.zone }}</td>
                                    <td>{{ x.title }}</td>                                    
                                    <td>
                                        <div class="btn-toolbar mb-2 mb-md-0">
                                            <div class="btn-group me-2">
                                                <a href="<?= base_url('servicezone__upsert/') ?>{{x.service_zone_id}}" class="btn btn-sm btn-secondary">
                                                    <!-- Edit SVG -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                                    </svg>
                                                </a>
                                                <button type="button" ng-click="deleteServiceZone(x.service_zone_id)" class="btn btn-sm btn-danger">
                                                    <!-- Delete SVG -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr ng-if="serviceZoneList.length == 0">
                                    <td colspan="8">No data exist.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </main>
            </div>
        </div>

<script>
    angular.module('myApp').controller('serviceZoneListTable', function($scope, $http, $q) {

        $scope.serviceZoneList = [];
        $scope.serviceList = [];
        let serviceListLoaded = $q.defer();

        $http.get('<?= base_url('api/fetchServiceList') ?>')
                .then((res) => {
                    if(res.data.status == 'Error') {
                        $scope.serviceList = [];
                        console.log(res.data.message);
                        console.log(res.data.errors);
                    } else {
                        $scope.serviceList = res.data;
                    }
                    serviceListLoaded.resolve();
                })

        function loadServiceZoneList () {
            serviceListLoaded.promise.then(() => {
                $http.get('<?= base_url('api/fetchServiceZoneList') ?>')
                    .then((res) => {
                        if(res.data.status == 'Error') {
                            $scope.serviceZoneList = [];
                            console.log(res.data.message);
                            console.log(res.data.errors);
                        } else {
                            $scope.serviceZoneList = res.data;

                            $scope.serviceNameList = {};
                            $scope.serviceList.forEach(function(item) {
                                $scope.serviceNameList[item.service_id] = item.title;
                            });
                        }
                    })
                    .catch((err) => {
                        console.error(err);
                        $scope.serviceZoneList = [];
                    })
            })
        }

        

         $scope.deleteServiceZone = function (id) {
            if(confirm('Are you sure you want to delete this service zone')) {
                $http.post('<?= base_url('service_zone_del') ?>', {
                    'id': id,
                })
                .then((res) => {
                    if(res.data.status == "Success") {
                        alert('Service Zone deleted.')
                        loadServiceZoneList();
                    } else {
                        alert('Error delete service zone. See console for details.');
                        console.error(err);
                    }
                })
            } else {
                alert('Operation cancel.')
            }
        }

        loadServiceZoneList();
    });
</script>