<!doctype html>
<html lang="en" ng-app="myApp">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Demo</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
      <link rel="stylesheet" href="<?= base_url('/dist/dashboard.css') ?>">
      <style>
          .bd-placeholder-img{font-size:1.125rem;text-anchor:middle;-webkit-user-select:none;-moz-user-select:none;user-select:none}
          @media (min-width: 768px){.bd-placeholder-img-lg{font-size:3.5rem}}
          .b-example-divider{width:100%;height:3rem;background-color:#0000001a;border:solid rgba(0,0,0,.15);border-width:1px 0;box-shadow:inset 0 .5em 1.5em #0000001a,inset 0 .125em .5em #00000026}
          .b-example-vr{flex-shrink:0;width:1.5rem;height:100vh}
          .bi{vertical-align:-.125em;fill:currentColor}
          .nav-scroller{position:relative;z-index:2;height:2.75rem;overflow-y:hidden}
          .nav-scroller .nav{display:flex;flex-wrap:nowrap;padding-bottom:1rem;margin-top:-1px;overflow-x:auto;text-align:center;white-space:nowrap;-webkit-overflow-scrolling:touch}
          .btn-bd-primary{--bd-violet-bg: #712cf9;--bd-violet-rgb: 112.520718, 44.062154, 249.437846;--bs-btn-font-weight: 600;--bs-btn-color: var(--bs-white);--bs-btn-bg: var(--bd-violet-bg);--bs-btn-border-color: var(--bd-violet-bg);--bs-btn-hover-color: var(--bs-white);--bs-btn-hover-bg: #6528e0;--bs-btn-hover-border-color: #6528e0;--bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);--bs-btn-active-color: var(--bs-btn-hover-color);--bs-btn-active-bg: #5a23c8;--bs-btn-active-border-color: #5a23c8}
          .bd-mode-toggle{z-index:1500}
          .bd-mode-toggle .bi{width:1em;height:1em}
          .bd-mode-toggle .dropdown-menu .active .bi{display:block!important}
          .text-danger {
                font-size: 0.9rem;
                color: #dc3545;
                margin-top: 0.25rem;
            }
      </style>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.3/angular-messages.min.js"></script>
        <script>
            // angularjs
            var app = angular.module('myApp', ['ngMessages']);
        </script>
    </head>
    <header class="navbar sticky-top bg-dark flex-md-nowrap p-0 shadow" data-bs-theme="dark">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white" href="<?= base_url() ?>">Company name</a>
        <div id="navbarSearch" class="navbar-search w-100 collapse">
            <input class="form-control w-100 rounded-0 border-0" type="text" placeholder="Search" aria-label="Search">
        </div>
    </header>
  <body>
<div class="container-fluid">
            <div class="row">
                <div class="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary">
                    <div class="offcanvas-md offcanvas-end bg-body-tertiary" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="sidebarMenuLabel">Customer Relationship Manager</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center gap-2 active" aria-current="page" href="<?= base_url() ?>">
                                        <svg class="bi" aria-hidden="true"></svg>
                                        Dashboard
                                    </a>
                                </li>

                                <hr />

                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center gap-2 active" aria-current="page" href="<?= base_url('company_list') ?>">
                                        <svg class="bi" aria-hidden="true"></svg>
                                        Company
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center gap-2 active" aria-current="page" href="<?= base_url('user_list') ?>">
                                        <svg class="bi" aria-hidden="true"></svg>
                                        User
                                    </a>
                                </li>

                                <hr />

                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center gap-2 active" aria-current="page" href="<?= base_url('category_list') ?>">
                                        <svg class="bi" aria-hidden="true"></svg>
                                        Category
                                    </a>
                                </li>
                            </ul>
                            <!-- <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-body-secondary text-uppercase">
                                <span>Saved reports</span>
                                <a class="link-secondary" href="#" aria-label="Add a new report">
                                    <svg class="bi" aria-hidden="true"><use xlink:href="#plus-circle"></use></svg>
                                </a>
                            </h6>
                            <ul class="nav flex-column mb-auto">
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center gap-2" href="#">
                                        <svg class="bi" aria-hidden="true"><use xlink:href="#file-earmark-text"></use></svg>
                                        Current month
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center gap-2" href="#">
                                        <svg class="bi" aria-hidden="true"><use xlink:href="#file-earmark-text"></use></svg>
                                        Last quarter
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center gap-2" href="#">
                                        <svg class="bi" aria-hidden="true"><use xlink:href="#file-earmark-text"></use></svg>
                                        Social engagement
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center gap-2" href="#">
                                        <svg class="bi" aria-hidden="true"><use xlink:href="#file-earmark-text"></use></svg>
                                        Year-end sale
                                    </a>
                                </li>
                            </ul>
                            <hr class="my-3">
                            <ul class="nav flex-column mb-auto">
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center gap-2" href="#">
                                        <svg class="bi" aria-hidden="true"><use xlink:href="#gear-wide-connected"></use></svg>
                                        Settings
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center gap-2" href="#">
                                        <svg class="bi" aria-hidden="true"><use xlink:href="#door-closed"></use></svg>
                                        Sign out
                                    </a>
                                </li>
                            </ul> -->
                        </div>
                    </div>
                </div>