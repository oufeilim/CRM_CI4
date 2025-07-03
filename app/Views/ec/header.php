<!DOCTYPE html>
<html lang="en" data-bs-theme="auto" ng-app="myApp">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
        <meta name="generator" content="Astro v5.9.2">
        <title>Ec</title>
        <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/album/">
        <script src="<?= base_url('dist/assets/js/color-modes.js') ?>"></script>
        <link href="<?= base_url('dist/assets/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
        <meta name="theme-color" content="#712cf9">
        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none
            }
            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                    font-size: 3.5rem
                }
            }
            .b-example-divider {
                width: 100%;
                height: 3rem;
                background-color: #0000001a;
                border: solid rgba(0,0,0,.15);
                border-width: 1px 0;
                box-shadow: inset 0 .5em 1.5em #0000001a, inset 0 .125em .5em #00000026
            }
            .b-example-vr {
                flex-shrink: 0;
                width: 1.5rem;
                height: 100vh
            }
            .bi {
                vertical-align: -.125em;
                fill: currentColor
            }
            .nav-scroller {
                position: relative;
                z-index: 2;
                height: 2.75rem;
                overflow-y: hidden
            }
            .nav-scroller .nav {
                display: flex;
                flex-wrap: nowrap;
                padding-bottom: 1rem;
                margin-top: -1px;
                overflow-x: auto;
                text-align: center;
                white-space: nowrap;
                -webkit-overflow-scrolling: touch
            }
            .btn-bd-primary {
                --bd-violet-bg: #712cf9;
                --bd-violet-rgb: 112.520718, 44.062154, 249.437846;
                --bs-btn-font-weight: 600;
                --bs-btn-color: var(--bs-white);
                --bs-btn-bg: var(--bd-violet-bg);
                --bs-btn-border-color: var(--bd-violet-bg);
                --bs-btn-hover-color: var(--bs-white);
                --bs-btn-hover-bg: #6528e0;
                --bs-btn-hover-border-color: #6528e0;
                --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
                --bs-btn-active-color: var(--bs-btn-hover-color);
                --bs-btn-active-bg: #5a23c8;
                --bs-btn-active-border-color: #5a23c8
            }
            .bd-mode-toggle {
                z-index: 1500
            }
            .bd-mode-toggle .bi {
                width: 1em;
                height: 1em
            }
            .bd-mode-toggle .dropdown-menu .active .bi {
                display: block!important
            }
        </style>
        <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.3/angular-messages.min.js"></script>
        <script>
            // angularjs
            var app = angular.module('myApp', ['ngMessages'])

            .factory('cartService', function($http) {
                let cartItems = null;

                function calcTotalQty (list) {
                    return list.reduce((sum, item) => sum + Number(item.product_qty || 0), 0);
                }

                return {
                    getCartItems: function(forceRefresh = false) {
                        if (cartItems && !forceRefresh) {
                            return Promise.resolve(cartItems);
                        }
                        return $http.get('<?= base_url('api/ec/fetchCartList') ?>')
                            .then((res) => {
                                if(res.data.status == "Success") {
                                    cartItems = res.data.data;
                                    return cartItems;
                                } else {
                                    cartItems = [];
                                }
                            })
                            .catch((err) => {
                                console.log(err);
                                cartItems = [];
                                return cartItems;
                            });
                    },
                    refreshCartList: function() {
                        return this.getCartItems(true);
                    },
                    getCartQty: function() {
                        return cartItems ? calcTotalQty(cartItems) : 0;
                    },
                    updateCartQty: function (id, qty) {
                        return $http.post('<?= base_url("api/ec/updateCartItemQty") ?>', { id: id, qty: qty });
                    },
                    deleteCartItem: function(cartId) {
                        return $http.post('<?= base_url('api/ec/deleteCartItem') ?>', { id: cartId });
                    },
                    bulkDeleteCartItems: function(cartIds) {
                        return $http.post('<?= base_url("api/ec/deleteCartItems") ?>', { ids: cartIds });
                    },
                    checkDiscount: function(ori_price, type, value, maxcap) {
                        var deductAmount = 0;
                        var finalAmount = 0;

                        if(type == "0") {
                            deductAmount = value;
                            finalAmount = ori_price - deductAmount;

                            if(finalAmount < 0) {
                                finalAmount = 1;
                            }
                        }
                        
                        if (type == "1") {
                            deductAmount = ori_price * value;
                            
                            if(deductAmount > maxcap) {
                                deductAmount = maxcap;
                            }

                            finalAmount = ori_price - deductAmount;
                        }

                        return {finalAmount, deductAmount};
                    }
                };
            })

            .controller('headerView', function($scope, $rootScope, cartService) {
                $scope.cartQty = 0;

                cartService.getCartItems().then(function(items) {
                    $scope.cartQty = cartService.getCartQty();;
                });

                var cartUpdating = $rootScope.$on('cartUpdated', function(event, qty) {
                    $scope.cartQty = qty;
                });

                $scope.$on('$destroy', cartUpdating );
            })
        </script>
    </head>
    <body>
        <!-- <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
            <symbol id="check2" viewBox="0 0 16 16">
                <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"></path>
            </symbol>
            <symbol id="circle-half" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"></path>
            </symbol>
            <symbol id="moon-stars-fill" viewBox="0 0 16 16">
                <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"></path>
                <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"></path>
            </symbol>
            <symbol id="sun-fill" viewBox="0 0 16 16">
                <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"></path>
            </symbol>
        </svg>
        <div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle">
            <button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
                <svg class="bi my-1 theme-icon-active" aria-hidden="true">
                    <use href="#circle-half"></use>
                </svg>
                <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
                <li>
                    <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                        <svg class="bi me-2 opacity-50" aria-hidden="true">
                            <use href="#sun-fill"></use>
                        </svg>
                        Light
                        <svg class="bi ms-auto d-none" aria-hidden="true">
                            <use href="#check2"></use>
                        </svg>
                    </button>
                </li>
                <li>
                    <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                        <svg class="bi me-2 opacity-50" aria-hidden="true">
                            <use href="#moon-stars-fill"></use>
                        </svg>
                        Dark
                        <svg class="bi ms-auto d-none" aria-hidden="true">
                            <use href="#check2"></use>
                        </svg>
                    </button>
                </li>
                <li>
                    <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
                        <svg class="bi me-2 opacity-50" aria-hidden="true">
                            <use href="#circle-half"></use>
                        </svg>
                        Auto
                        <svg class="bi ms-auto d-none" aria-hidden="true">
                            <use href="#check2"></use>
                        </svg>
                    </button>
                </li>
            </ul>
        </div> -->
        <header data-bs-theme="dark" ng-controller="headerView">
            <div class="collapse text-bg-dark" id="navbarHeader">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-8 col-md-7 py-4">
                            <!-- <h4>About</h4>
                            <p class="text-body-secondary">Add some information about the album below, the author, or any other background context. Make it a few sentences long so folks can pick up some informative tidbits. Then, link them off to some social networking sites or contact information.</p> -->
                        </div>
                        <div class="col-sm-4 offset-md-1 py-4">
                            <h4>Menu</h4>
                            <ul class="list-unstyled">
                                <li><a href="<?= base_url('ec/category') ?>" class="text-white">Category</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="navbar navbar-dark bg-dark shadow-sm">
                <div class="container d-flex justify-content-between align-items-center">
                    <a href="<?= base_url('ec/category') ?>" class="navbar-brand d-flex align-items-center">
                        
                        <strong>E-commerce</strong>
                    </a>
                    <div class="d-flex ms-auto align-items-center">
                        <a class="btn p-0 bg-transparent border-0 mx-4 position-relative" href="<?= base_url('ec/cart') ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                            </svg>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ cartQty }}
                                <span class="visually-hidden">cart items</span>
                            </span>
                        </a>
                        <button class="navbar-toggler mx-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </div>
                </div>
            </div>
        </header>