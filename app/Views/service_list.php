
                <main ng-controller="serviceListTable" class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Service List</h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <div class="btn-group me-2">
                                <a class="btn btn-sm btn-outline-primary" href="<?= base_url('service_upsert') ?>" >
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
                                    <th scope="col" style="width: 20%">Title</th>
                                    <th scope="col" style="width: 10%">Description</th>
                                    <th scope="col" style="width: 10%">Service Type</th>
                                    <th scope="col" style="width: 10%">Status</th>
                                    <th scope="col" style="width: 10%">Base weight</th>
                                    <th scope="col" style="width: 7%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="x in serviceList" ng-if="serviceList.length >= 1">
                                    <td>{{ x.service_id }}</td>
                                    <td>{{ x.title }}</td>
                                    <td>{{ x.description }}</td>
                                    <td>{{ x.service_type == 0 ? 'Domestic' : 'International' }}</td>                                    
                                    <td>{{ x.status == 1 ? 'Active' : 'Inactive' }}</td>
                                    <td>{{ x.base_weight + ' kg' }}</td>
                                    <td>
                                        <div class="btn-toolbar mb-2 mb-md-0">
                                            <div class="btn-group me-2">
                                                <a href="<?= base_url('service_upsert/') ?>{{x.service_id}}" class="btn btn-sm btn-secondary me-1">
                                                    <!-- Edit SVG -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                                    </svg>
                                                </a>
                                                <button type="button" ng-click="deleteService(x.service_id)" class="btn btn-sm btn-danger me-1">
                                                    <!-- Delete SVG -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                                    </svg>
                                                </button>
                                                <button type="button" ng-click="serviceRate(x)" class="btn btn-sm btn-warning me-1">
                                                    <!-- Delete SVG -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-tags" viewBox="0 0 16 16">
                                                        <path d="M3 2v4.586l7 7L14.586 9l-7-7zM2 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 2 6.586z"/>
                                                        <path d="M5.5 5a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1m0 1a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3M1 7.086a1 1 0 0 0 .293.707L8.75 15.25l-.043.043a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 0 7.586V3a1 1 0 0 1 1-1z"/>
                                                    </svg>
                                                </button>

                                                <button type="button" ng-click="displayRate(x)" class="btn btn-sm btn-info me-1">
                                                    <!-- Delete SVG -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-table" viewBox="0 0 16 16">
                                                        <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm15 2h-4v3h4zm0 4h-4v3h4zm0 4h-4v3h3a1 1 0 0 0 1-1zm-5 3v-3H6v3zm-5 0v-3H1v2a1 1 0 0 0 1 1zm-4-4h4V8H1zm0-4h4V4H1zm5-3v3h4V4zm4 4H6v3h4z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr ng-if="serviceList.length == 0">
                                    <td colspan="8">No data exist.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- serviceRate Modal -->
                    <div class="modal fade" id="serviceRateList" tabindex="-1" aria-labelledby="serviceRateListLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="serviceRateListLabel">{{ selectedServiceTitle }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- <select multiple size="12" ng-model="selectedItems">
                                        <option ng-repeat="item in serviceZoneList" value="{{ item.service_zone_id }}">
                                            {{ item.zone + ' - ' +item.title }}
                                        </option>
                                    </select> -->
                                    <div class="row mb-3">
                                        <div class="col-12 mb-3">
                                            <label for="zoneFrom" class="formLabel">Zone From</label>
                                            <select class="form-select form-select-sm w-100" name="zoneFrom" id="zoneFrom" ng-model="selectedZoneFrom" size="8" multiple>
                                                <option ng-repeat="item in serviceZoneList" value="{{ item.zone }}">
                                                    {{ item.zone + ' - ' + item.title }}
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label for="zoneTo" class="formLabel">Zone To</label>
                                            <select class="form-select form-select-sm w-100" name="zoneTo" id="zoneTo" ng-model="selectedZoneTo" size="8" multiple>
                                                <option ng-repeat="item in serviceZoneList" value="{{ item.zone }}">
                                                    {{ item.zone + ' - ' + item.title }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <hr/>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 10%;">#</th>
                                                    <th>Weight</th>
                                                    <th>Price</th>
                                                    <th style="width: 10%;" class="text-center align-middle"><button class="btn btn-success btn-sm" type="button" ng-click="addRow()">+</button></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr ng-repeat="row in rateRows track by $index">
                                                    <td>{{$index + 1}}</td>
                                                    <td><input type="text" ng-model="row.weight" class="form-control" /></td>
                                                    <td><input type="text" ng-model="row.price" class="form-control" /></td>
                                                    <td class="text-center">
                                                        <button class="btn btn-danger btn-sm" type="button" ng-click="removeRow($index)">×</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary" type="button" ng-click="generateRateList()">Generate</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- displayRate Modal -->
                    <div class="modal fade" id="displayRateList" tabindex="-1" aria-labelledby="displayRateListLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="displayRateListLabel">{{ selectedServiceTitle }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Zone From</th>
                                                    <th>Zone To</th>
                                                    <th>Weight (KG)</th>
                                                    <th>Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr ng-repeat="rateItem in serviceRateList">
                                                    <td>{{ rateItem.zone_from + ' - ' + rateItem.from_title }}</td>
                                                    <td>{{ rateItem.zone_to + ' - ' + rateItem.to_title }}</td>
                                                    <td><input type="text" class="form-control" ng-model="rateItem.weight" ng-change="markChanged(rateItem)" /></td>
                                                    <td><input type="text" class="form-control" ng-model="rateItem.price" ng-change="markChanged(rateItem)" /></td>
                                                </tr>

                                                <tr ng-if="serviceRateList.length == 0">
                                                    <td colspan="4">No Data Exist</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                     <button class="btn btn-primary" type="button" ng-click="saveEditedRates()">
                                        Save Changes
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>

<script>
    angular.module('myApp').controller('serviceListTable', function($scope, $http, $q) {

        $scope.serviceList = [];
        $scope.serviceZoneList = [];
        $scope.selectedZoneFrom = $scope.selectedZoneFrom || [];
        $scope.selectedZoneTo = $scope.selectedZoneTo || [];
        $scope.rateRows = $scope.rateRows || [];
        $scope.serviceRateList = [];

        // #region list, delete
        function loadServiceList () {
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
            .catch((err) => {
                console.error(err);
                $scope.serviceList = [];
            })
        }
        loadServiceList();

        $scope.deleteService = function (id) {
            if(confirm('Are you sure you want to delete this service')) {
                $http.post('<?= base_url('service_del') ?>', {
                    'id': id,
                })
                .then((res) => {
                    if(res.data.status == "Success") {
                        alert('Service deleted.')
                        loadServiceList();
                    } else {
                        alert('Error delete service. See console for details.');
                        console.error(err);
                    }
                })
            } else {
                alert('Operation cancel.')
            }
        }
        // #endregion
        
        // #region Generate Service Rate
        $scope.serviceRate = function (item) {
            var modal = new bootstrap.Modal(document.getElementById('serviceRateList'));
            modal.toggle();

            $scope.selectedServiceID    = item.service_id;
            $scope.selectedServiceTitle = item.title;

            // get zone of the service
            $scope.getServiceZoneList = function () {
                return $http.get('<?= base_url('api/fetchServiceZoneList') ?>' + '?service_id=' + $scope.selectedServiceID);
            }

            $q.all([$scope.getServiceZoneList()])
                .then((res) => {
                    $scope.serviceZoneList = res[0].data;
                })
                .catch((err) => {
                    $scope.serviceZoneList = [];
                    console.log(err);
                })
        }
        
        document.getElementById('serviceRateList')
            .addEventListener('hidden.bs.modal', function () {
                $scope.$apply(function () {
                    $scope.serviceZoneList = [];
                    $scope.selectedServiceID = '';
                    $scope.selectedServiceTitle = '';
                    $scope.rateRows = [{ weight: '1', price: '1.00' }];
                });
            });

        $scope.generateRateList = function () {
            const fromList = $scope.selectedZoneFrom || [];
            const toList = $scope.selectedZoneTo || [];
            const rateRows = $scope.rateRows || [];

            if (fromList.length === 0 || toList.length === 0 || rateRows.length === 0) {
                alert("Please select both zone from, zone to, and at least one rate row.");
                return;
            }

            for (let i = 0; i < rateRows.length; i++) {
                const rate = rateRows[i];
                if (!rate.weight || isNaN(rate.weight) || parseFloat(rate.weight) <= 0) {
                    alert(`Invalid weight at row ${i + 1}. Please enter a number greater than 0.`);
                    return;
                }
                if (!rate.price || isNaN(rate.price) || parseFloat(rate.price) < 0) {
                    alert(`Invalid price at row ${i + 1}. Please enter a valid number.`);
                    return;
                }
            }

            const result = [];

            fromList.forEach(from => {
                toList.forEach(to => {
                    rateRows.forEach(rate => {
                        result.push({
                            service_id: $scope.selectedServiceID,
                            from_zone: from,
                            to_zone: to,
                            weight: rate.weight,
                            price: rate.price
                        });
                    });
                });
            });

            console.log("Generated Combinations:", result);
            $scope.combinedRateList = result;

            if(confirm('Are you sure you want to generate service rate based on the selection and rows?')) {
                $http.post('<?= base_url('api/insertBatchServiceRate') ?>', { data: $scope.combinedRateList })
                    .then((res) => {
                        if(res.data.status == 'Success') {
                            alert('Rate insert.');
                            bootstrap.Modal.getInstance(document.getElementById('serviceRateList')).hide();
                        } else {
                            console.log(res.data.errors)
                            console.log(res);
                        }
                    })
                    .catch((err) => {
                        console.log(err);
                    })
            }

        }

        $scope.rateRows = [{ weight: '1', price: '1.00' }];

        $scope.addRow = function() {
            $scope.rateRows.push({ weight: '1', price: '1.00' });
        };

        $scope.removeRow = function(index) {
            if($scope.rateRows.length <= 1) return;
            $scope.rateRows.splice(index, 1);
        };
        // #endregion

        $scope.displayRate = function (item) {
            var modal = new bootstrap.Modal(document.getElementById('displayRateList'));
            modal.toggle();

            $scope.selectedServiceID    = item.service_id;
            $scope.selectedServiceTitle = item.title;

            // get service rate
            $http.get('<?= base_url('api/fetchServiceRateList') ?>' + '?service_id=' + $scope.selectedServiceID)
                    .then((res) => {
                        if(res.data.status == 'Error') {
                            console.log(res.data.message)
                        } else {
                            $scope.serviceRateList = res.data;
                            $scope.originalServiceRateList = angular.copy(res.data);
                        }
                    })
        }

        document.getElementById('displayRateList')
            .addEventListener('hidden.bs.modal', function () {
                $scope.$apply(function () {
                    $scope.selectedServiceID = '';
                    $scope.selectedServiceTitle = '';
                    $scope.serviceRateList = [];
                });
            });

        $scope.editedRates = [];

        $scope.markChanged = function(rateItem) {
            const original = $scope.originalServiceRateList.find(item => item.service_rate_id === rateItem.service_rate_id);

            const isChanged = rateItem.weight != original.weight || rateItem.price != original.price;

            const alreadyTracked = $scope.editedRates.find(item => item.service_rate_id === rateItem.service_rate_id);

            if (isChanged && !alreadyTracked) {
                $scope.editedRates.push(rateItem);
            } else if (!isChanged && alreadyTracked) {
                // If reverted back, remove from editedRates
                $scope.editedRates = $scope.editedRates.filter(item => item.service_rate_id !== rateItem.service_rate_id);
            }
        };

        $scope.saveEditedRates = function () {
            if ($scope.editedRates.length === 0) {
                alert('No changes to save.');
                return;
            }

            if (confirm('Are you sure you want to save your changes?')) {
                $http.post('<?= base_url('api/updateServiceRates') ?>', { data: $scope.editedRates })
                    .then((res) => {
                        if (res.data.status == 'Success') {
                            alert('Changes saved.');
                            bootstrap.Modal.getInstance(document.getElementById('displayRateList')).hide();
                        } else {
                            console.log(res.data.errors);
                            alert('Error saving changes.');
                        }
                    })
                    .catch((err) => {
                        console.log(err);
                        alert('Request failed.');
                    });
            }
        }
    });
</script>