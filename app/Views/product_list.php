
                <main ng-controller="productListTable" class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Product List</h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <div class="btn-group me-2">
                                <a class="btn btn-sm btn-outline-primary" href="<?= base_url('product_upsert') ?>" >
                                    <!-- Add SVG -->
                                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <select class="form-select mb-3" ng-model="filterList">
                        <option value="onlyParent">Show Only Parent</option>
                        <option value="showAll">Show All</option>
                    </select>

                    <div class="table-responsive small">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 5%">#</th>
                                    <th scope="col" style="width: 20%">Name</th>
                                    <th scope="col" style="width: 10%">Category</th>
                                    <th scope="col" style="width: 8%">Stock Quantity</th>
                                    <th scope="col" style="width: 8%">Price</th>
                                    <th scope="col" style="width: 8%">Weight</th>
                                    <th scope="col" style="width: 5%">Priority</th>
                                    <th scope="col" style="width: 5%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="x in productList">
                                    <td>{{ x.product_id }}</td>
                                    <td>{{ x.name }}</td>
                                    <td>{{ x.category_title }}</td>
                                    <td>{{ x.stock_qty }}</td>
                                    <td>{{ x.price }}</td>
                                    <td>{{ x.weight + ' kg' }}</td>
                                    <td>{{ x.priority }}</td>
                                    <td>
                                        <div class="btn-toolbar mb-2 mb-md-0">
                                            <div class="btn-group me-2">
                                                <a href="<?= base_url('product_upsert/') ?>{{x.product_id}}" class="btn btn-sm btn-secondary me-2">
                                                    <!-- Edit SVG -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                                    </svg>
                                                </a>
                                                <button type="button" ng-click="deleteProduct(x.product_id)" class="btn btn-sm btn-danger me-2">
                                                    <!-- Delete SVG -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                                    </svg>
                                                </button>
                                                <button type="button" ng-click="itemVariation(x)" class="btn btn-sm btn-warning me-2" ng-if="x.parent_id == 0">
                                                    <!-- Delete SVG -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-tags" viewBox="0 0 16 16">
                                                        <path d="M3 2v4.586l7 7L14.586 9l-7-7zM2 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 2 6.586z"/>
                                                        <path d="M5.5 5a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1m0 1a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3M1 7.086a1 1 0 0 0 .293.707L8.75 15.25l-.043.043a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 0 7.586V3a1 1 0 0 1 1-1z"/>
                                                    </svg>
                                                </button>
                                                <button type="button" ng-click="loadProductList({ parent_id: x.product_id })" class="btn btn-sm btn-info me-1" ng-if="x.parent_id == 0">
                                                    <!-- Delete SVG -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-diagram-3" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M6 3.5A1.5 1.5 0 0 1 7.5 2h1A1.5 1.5 0 0 1 10 3.5v1A1.5 1.5 0 0 1 8.5 6v1H14a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0v-1A.5.5 0 0 1 2 7h5.5V6A1.5 1.5 0 0 1 6 4.5zM8.5 5a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5zM0 11.5A1.5 1.5 0 0 1 1.5 10h1A1.5 1.5 0 0 1 4 11.5v1A1.5 1.5 0 0 1 2.5 14h-1A1.5 1.5 0 0 1 0 12.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm4.5.5A1.5 1.5 0 0 1 7.5 10h1a1.5 1.5 0 0 1 1.5 1.5v1A1.5 1.5 0 0 1 8.5 14h-1A1.5 1.5 0 0 1 6 12.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm4.5.5a1.5 1.5 0 0 1 1.5-1.5h1a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1-1.5 1.5h-1a1.5 1.5 0 0 1-1.5-1.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="itemVariationModal" tabindex="-1" aria-labelledby="itemVariationModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="itemVariationModalLabel">{{ chosenItem }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div ng-if="parentAttrList.length >= 1">
                                        <div class="form-group">
                                            <label class="mb-2">Attribute: </label>
                                            <div ng-repeat="parentAttr in parentAttrList">
                                                <label class="form-label">
                                                    <input class="form-check-input" type="checkbox" ng-checked="selectedParentAttr.indexOf(parentAttr.attribute_id) > -1" ng-click="toggleAttribute(parentAttr.attribute_id)">
                                                    {{ parentAttr.title }}
                                                </label>
                                            </div>

                                            <button class="btn btn-primary my-2" type="button" ng-click="generateVariation()">Generate</button>
                                        </div>
                                        
                                        <div ng-if="variationTable != null">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Select</th>
                                                            <th>Name</th>
                                                            <th ng-repeat="key in selectedAttributeKeys">{{ key | uppercase }}</th>
                                                            <th>Price</th>
                                                            <th>Stock Qty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr ng-repeat="row in variationTable">
                                                            <td>
                                                                <input type="checkbox" ng-model="row.checked">
                                                            </td>
                                                            <td><input type="text" class="form-control form-control-sm" ng-model="row.name"></td>
                                                            <td ng-repeat="value in row.attributes">
                                                                {{ value }}
                                                            </td>
                                                            <td>
                                                                <input type="number" ng-blur="formatPrice(row)" class="form-control form-control-sm" ng-model="row.price" ng-change="updateTotal(row)">
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control form-control-sm" ng-model="row.qty" ng-change="updateTotal(row)">
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <button type="button" class="btn btn-primary" ng-click="submitVariation()">Submit</button>
                                            </div>
                                        </div>

                                    </div>

                                    <div ng-if="parentAttrList.length === 0">
                                        <p>Attribute Not Added.</p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>

<script>
    angular.module('myApp').controller('productListTable', function($scope, $timeout, $http) {

        $scope.productList = [];
        $scope.selectedParentAttr = [];
        $scope.filterList = 'onlyParent';
        let suppressFilterWatch = false;

        $scope.loadProductList = function (params = {}) {
            
            console.log("Params received:", params);

            const queryString = Object.keys(params)
                .map(key => `${encodeURIComponent(key)}=${encodeURIComponent(params[key])}`)
                .join('&');

            const url = '<?= base_url('api/fetchProductList') ?>' + (queryString ? '?' + queryString : '');

            console.log("Requesting URL:", url);

            $http.get(url)
            .then((res) => {
                if (res.data.status == "Error") {
                    alert('This product does not have any variation avaliable');
                } else {
                   $scope.productList = res.data;

                   if (params.parent_id !== undefined) {
                        suppressFilterWatch = true;
                        $scope.filterList = 'showAll';
                        $timeout(() => suppressFilterWatch = false);
                    }
                }

            })
            .catch((err) => {
                console.error(err);
            })
        }
        $scope.loadProductList();

        $scope.$watch('filterList', function (newVal) {
            if (suppressFilterWatch) return;

            if (newVal === 'onlyParent') {
                $scope.loadProductList(); // load only parent
            } else if (newVal === 'showAll') {
                bySelection = true;
                $scope.loadProductList({ parent_id: 0 }); // load all
            }
        });

        $scope.deleteProduct = function (id) {
            if(confirm('Are you sure you want to delete this product')) {
                $http.post('<?= base_url('product_del') ?>', {
                    'id': id,
                })
                .then((res) => {
                    if(res.data.status == "Success") {
                        alert('Product deleted.')
                        loadProductList();
                    } else {
                        alert('Error delete product. See console for details.');
                        console.error(err);
                    }
                })
            } else {
                alert('Operation cancel.')
            }
        }

        $scope.itemVariation = function (item) {
            var modal = new bootstrap.Modal(document.getElementById('itemVariationModal'));
            modal.toggle();
            $scope.currentProduct = item;
            $scope.chosenItem = $scope.currentProduct.name + ' (' + $scope.currentProduct.slug + ')';

            $scope.attrParentList = [];
            $http.get('<?= base_url('api/fetchAttributeList') ?>')
                    .then((res) => {
                        $scope.attrParentList = []

                        $scope.attributeList = res.data;

                        $scope.parentAttrList = $scope.attributeList.filter((item) => {
                            return item.parent_id == '0';
                        });
                    })

            console.log(item);
        }

        document.getElementById('itemVariationModal')
            .addEventListener('hidden.bs.modal', function () {
                $scope.$apply(function () {
                    $scope.chosenItem = '';
                    $scope.selectedParentAttr = [];
                    $scope.variationTable = null;
                });
            });

        $scope.buildSelectedAttributes = function () {
            $scope.selectedAttributes = {};

            $scope.selectedParentAttr.forEach(function (parentId) {
                const parent = $scope.attributeList.find(attr => attr.attribute_id === parentId);
                 const children = $scope.attributeList
                    .filter(attr => attr.parent_id === parentId)
                    .sort((a, b) => parseInt(a.priority) - parseInt(b.priority)) // sort by priority
                    .map(attr => attr.title); // then map to title only

                if (parent && children.length) {
                    $scope.selectedAttributes[parent.title.toLowerCase()] = children;
                }
            });

            $scope.selectedAttributeKeys = Object.keys($scope.selectedAttributes);
        };

        $scope.toggleAttribute = function (id) {
            const index = $scope.selectedParentAttr.indexOf(id);
            if(index > -1) {
                $scope.selectedParentAttr.splice(index, 1);
            } else {
                $scope.selectedParentAttr.push(id);
            }
            $scope.buildSelectedAttributes();
        }

        $scope.generateVariation = function () {
            if(confirm('Do you want to generate variation based on the selected attribute?')) {
                if($scope.selectedParentAttr.length >= 1) {
                    $scope.buildSelectedAttributes();

                    const attributeValues = Object.values($scope.selectedAttributes).map(val =>
                        Array.isArray(val) ? val : [val]
                    );
                    const attributeKeys = Object.keys($scope.selectedAttributes);

                    // Generate Cartesian product
                    function cartesianProduct(arr) {
                        if (arr.length === 1) {
                            return arr[0].map(val => [val]); // Fix: wrap each value as an array
                        }

                        return arr.reduce((a, b) =>
                            a.flatMap(d => b.map(e => [].concat(d, e)))
                        );  
                    }

                    const combinations = cartesianProduct(attributeValues);
                    const defaultPrice = parseFloat($scope.currentProduct.price) || 0;
                    
                    $scope.variationTable = combinations.map(combination => {
                        const attributeLabels = Array.isArray(combination) ? combination : [combination];
                        return {
                            checked: false,
                            product_id: $scope.currentProduct.product_id,
                            attributes: attributeLabels,
                            name: $scope.currentProduct.name + ' - ' + attributeLabels.join(' - '),
                            price: +parseFloat(defaultPrice).toFixed(2),
                            qty: 1,
                        };
                    });

                    $scope.selectedAttributeKeys = attributeKeys;
                } else {
                    alert('Please select at least one attribute!')
                }
            }
        }

        $scope.formatPrice = function (row) {
            row.price = +parseFloat(parseFloat(row.price || 0).toFixed(2));
        };

        $scope.submitVariation = function () {
            const selectedVariations = $scope.variationTable.filter(row => row.checked);

            if (selectedVariations.length === 0) {
                alert("Please select at least one variation to submit.");
                return;
            }

            console.log(selectedVariations);

            const payload = {
                    product_id: $scope.currentProduct.product_id,
                    variations: selectedVariations.map(row => ({
                        name: row.name,
                        attributes: row.attributes,
                        price: parseFloat(row.price),
                        qty: parseFloat(row.qty),
                    }))
                };

            console.log(payload);

            if(confirm('Are you sure you want to add selected variation?')) {
                
                $http.post('<?= base_url('product_submit_variation') ?>', payload)
                    .then(function (res) {
                        if (res.data.status === 'Success') {
                            alert('Variation saved successfully!');
                            bootstrap.Modal.getInstance(document.getElementById('itemVariationModal')).hide();
                            loadProductList();
                        } else {
                            alert('Failed to save variations. See console for details.');
                            console.error(res.data);
                        }
                    })
                    .catch(function (err) {
                        alert('Error occurred while saving variations.');
                        console.error(err);
                    });
            }
        };
    });
</script>