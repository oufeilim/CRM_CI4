<div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?= $mode ?> Product</h1>
    </div>

    <form ng-controller="productFormCtrl" name="productForm" ng-submit="submitForm()" enctype="multipart/form-data" novalidate>
        <input ng-model="mode" type="hidden" name="mode">
        <input ng-model="id" type="hidden" name="id">

        <div class="form-group my-2">
            <label class="form-label" for="prod_name">Product name</label>
            <input ng-model="prod_name" name="prod_name" id="prod_name" type="text" class="form-control"  required>
            <div ng-messages="productForm.prod_name.$error" ng-if="submitted" class="text-danger">
                <div ng-message="required">Product name is required.</div>
            </div>
        </div>

        <div class="row">
            <div class="col-4 form-group my-2">
                <label class="form-label" for="prod_category">Category</label>
                <select class="form-control" ng-model="prod_category" name="prod_category" id="prod_category" ng-options="category.category_id as category.title for category in categoryList" required>
                    <option value="">-- Select Category --</option>
                </select>
                <div ng-messages="productForm.prod_category.$error" ng-if="submitted" class="text-danger">
                    <div ng-message="required">Category is required.</div>
                </div>
            </div>


            <div class="col-4 form-group my-2">
                <label class="form-label" for="price">Price</label>
                <input ng-model="price" name="price" id="price" type="text" class="form-control" required price-input readonly>
                <div ng-messages="productForm.price.$error" ng-if="submitted" class="text-danger">
                    <div ng-message="required">Price is required.</div>
                </div>
            </div>

            <div class="col-4 form-group my-2">
                <label class="form-label" for="stock_qty">Stock Quantity</label>
                <input ng-model="stock_qty" name="stock_qty" id="stock_qty" type="text" class="form-control" required>
                <div ng-messages="productForm.stock_qty.$error" ng-if="submitted" class="text-danger">
                    <div ng-message="required">Stock Quantity is required.</div>
                </div>
            </div>
        </div>
        
        <div class="row align-items-center">
            <div class="col-6 form-group my-2">
                <label class="form-label mx-5" for="isDisplay">Display on main page?</label>
                <input class="form-check-input" name="isDisplay" id="isDisplay" ng-model="isDisplay" type="checkbox">
            </div>

            <div class="col-6 form-group my-2">
                <label class="form-label" for="priority">Priority</label>
                <input ng-model="priority" name="priority" id="priority" type="text" class="form-control" required>
                <div ng-messages="productForm.priority.$error" ng-if="submitted" class="text-danger">
                    <div ng-message="required">Priority is required.</div>
                </div>
            </div>
        </div>

        <div class="form-group my-2">
            <label class="form-label" for="desc">Description</label>
            <textarea ng-model="desc" class="form-control" name="desc" id="desc" required></textarea>
            <div ng-messages="productForm.desc.$error" ng-if="submitted" class="text-danger">
                <div ng-message="required">Description is required.</div>
            </div>
        </div>

        <div class="form-group my-2">
            <label class="form-label" for="image">Image</label>
            <input ng-model="image" type="file" onchange="angular.element(this).scope().fileSelected(this)" name="image" id="image" accept="image/*" class="form-control">
            
            <div class="mt-2" ng-if="imagePreview">
                <img ng-src="{{ imagePreview }}" alt="Selected image" class="img-thumbnail" style="max-width: 200px;">
            </div>

            <div class="form-group mt-2" ng-if="!imagePreview && ori_image_url">
                <label class="form-label d-block" for="ori_image">Original Image</label>
                <img name="ori_image" id="ori_image" ng-src="{{ baseUrl + 'uploads/product/' + ori_image_url }}" alt="Selected image" class="img-thumbnail mt-1" style="max-width: 200px;">
            </div>
        </div>

        <hr />

        <input class="btn btn-sm btn-primary my-2" type="submit" value="<?= $mode ?>" />

    </form>
</div>

<script>
    angular.module('myApp').directive('priceInput', function() {
        return {
            restrict: 'A',
            require: 'ngModel',
            link: function(scope, element, attrs, ngModelCtrl) {
                let rawInput = '';

                scope.$watch(() => ngModelCtrl.$viewValue, function(newVal) {
                    if (newVal && rawInput === '') {
                        // only run once at init
                        rawInput = newVal.replace('.', '').replace(/^0+/, '');
                        updateView();
                    }
                });

                function formatPrice(raw) {
                    if (!raw) return '0.00';
                    const padded = raw.padStart(3, '0');
                    const intPart = padded.slice(0, -2);
                    const decimalPart = padded.slice(-2);
                    return `${parseInt(intPart)}.${decimalPart}`;
                }

                function updateView() {
                    const formatted = formatPrice(rawInput);
                    ngModelCtrl.$setViewValue(formatted);
                    ngModelCtrl.$render();
                }

                element.on('keydown', function(event) {
                    const key = event.key;

                    if (/^[0-9]$/.test(key)) {
                        rawInput += key;
                        updateView();
                    } else if (key === 'Backspace') {
                        rawInput = rawInput.slice(0, -1);
                        updateView();
                    } else if (key === 'Tab' || key === 'ArrowLeft' || key === 'ArrowRight') {
                        // allow navigation
                    } else {
                        event.preventDefault();
                    }

                    event.preventDefault();
                });

                // initialize
                // ngModelCtrl.$setViewValue('0.00');
                // ngModelCtrl.$render();
            }
        };
    });

    angular.module('myApp').controller('productFormCtrl', function($scope, $http, $window) {

        $scope.categoryList = [];
        $http.get('<?= base_url('api/fetchCategoryList') ?>')
            .then((res) => {
                $scope.categoryList = res.data;
            })
            .catch((err) => {
                alert('Error retrive company. See console for details.');
                console.error(err);
            })

        $scope.imagePreview = '';

        $scope.fileSelected = function(input) {
            const file = input.files[0];

            if(file && file.type.startsWith('image/')) {
                $scope.image = file;
                const reader = new FileReader();

                reader.onload = function(e) {
                    $scope.$apply(function () {
                        $scope.imagePreview = e.target.result;
                    });
                };

                reader.readAsDataURL(file);
            } else {
                $scope.$apply(function () {
                    $scope.imagePreview = '';
                    $scope.imageFile = null;
                });
            }

        };

        // ifelse for the error message
        $scope.submitted = false;

        $scope.mode             = '<?= $mode ?>';
        $scope.id               = '<?= isset($id) ? $id : ''?>';
        $scope.prod_name        = '<?= esc(isset($productData) ? $productData['name'] : '') ?>';
        $scope.prod_category    = '<?= esc(isset($productData) ? $productData['category_id'] : '') ?>';
        $scope.price            = '<?= esc(isset($productData) ? $productData['price'] : '') ?>';
        $scope.stock_qty        = '<?= esc(isset($productData) ? $productData['stock_qty'] : '') ?>';

        $scope.is_display       = '<?= esc(isset($productData) ? $productData['is_display'] : '') ?>';
        $scope.isDisplay        = $scope.is_display == '1' ? true : false;

        $scope.priority         = '<?= esc(isset($productData) ? $productData['priority'] : '') ?>';
        $scope.desc             = '<?= esc(isset($productData) ? $productData['description'] : '') ?>';

        $scope.baseUrl          = '<?= base_url() ?>';
        $scope.ori_image_url    = '<?= esc(isset($productData) ? basename($productData['image_url']) : '' ) ?>';

        $scope.submitForm = function () {
            if(confirm("Are you sure?")) {
                //set to true for form validation
                $scope.submitted = true;

                if($scope.productForm.$valid) {
                    $scope.is_display = $scope.isDisplay ? 1 : 0;

                    // JSON cannot handle image file submission
                    const formData = new FormData();

                    formData.append('mode', $scope.mode);
                    formData.append('id', $scope.id);
                    formData.append('prod_name', $scope.prod_name);
                    formData.append('category_id', $scope.prod_category);
                    formData.append('price', $scope.price);
                    formData.append('stock_qty', $scope.stock_qty);
                    formData.append('is_display', $scope.is_display);
                    formData.append('priority', $scope.priority);
                    formData.append('description', $scope.desc);
                    if($scope.image) {
                        formData.append('image', $scope.image);
                    }

                    // for (let [key, value] of formData.entries()) {
                    //     console.log(`${key}:`, value);
                    // }

                    $http.post('<?= base_url('product_submit') ?>', formData, {
                            'headers': { 'Content-Type': undefined },
                            transformRequest: angular.identity
                        })
                        .then((res) => {
                            if(res.data.status == "Success") {
                                alert('Done');
                                $window.location.href = '<?= base_url('product_list') ?>'
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