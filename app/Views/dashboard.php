
<script>
    // angular.module('myApp').controller('companyListTable', function($scope, $http) {

    //     $scope.companyList = [];

    //     function loadCompanyList () {
    //         $http.get('<?= base_url('api/fetchCompanyList') ?>')
    //         .then((res) => {
    //             $scope.companyList = res.data;

    //         })
    //         .catch((err) => {
    //             alert('Error submitting form. See console for details.');
    //             console.error(err);
    //         })
    //     }

    //      $scope.deleteCompany = function (id) {
    //         if(confirm('Are you sure you want to delete this company')) {
    //             $http.post('<?= base_url('company_del') ?>', {
    //                 'id': id,
    //             })
    //             .then((res) => {
    //                 if(res.data.status == "Success") {
    //                     alert('Company deleted.')
    //                     loadCompanyList();
    //                 } else {
    //                     alert('Error delete company. See console for details.');
    //                     console.error(err);
    //                 }
    //             })
    //         } else {
    //             alert('Operation cancel.')
    //         }
    //     }

    //     loadCompanyList();
    // });
</script>