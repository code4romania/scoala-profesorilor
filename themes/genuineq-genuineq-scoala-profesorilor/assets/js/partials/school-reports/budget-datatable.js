$(document).ready(function () {
    $('#school-teachers-datatable').DataTable({
        dom: '<"row view-filter"<"col-sm-12"<"float-none float-sm-left float-md-left float-lg-left float-xl-left my-2"l><"float-none float-sm-right float-md-right float-lg-right float-xl-right my-2"f><"clearfix">><"col-sm-12"<"float-none float-sm-left float-md-left float-lg-left float-xl-left my-2"B><"clearfix">>>t<"row view-pager"<"col-12"<"text-center"p>>>',
        buttons: [
            { extend: 'copyHtml5', text: 'Copiază conținutul', className: 'btn-sm mx-2 rounded' },
            { extend: 'excelHtml5', text: 'Exportă XLXS', className: 'btn-sm mx-2 rounded' },
            { extend: 'csvHtml5', text: 'Exportă CSV', className: 'btn-sm mx-2 rounded' }
        ],
        lengthMenu: [ [5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "Toate"] ],
        processing: true,
        pagingType: 'full_numbers',
        language: {
            'search': 'Caută',
            'lengthMenu': "Număr rânduri: _MENU_",
            'paginate': {
                'first':    '«',
                'previous': '‹',
                'next':     '›',
                'last':     '»'
            },
            'aria': {
                'paginate': {
                    'first':    'Prima',
                    'previous': 'Ultima',
                    'next':     'Înainte',
                    'last':     'Înapoi'
                }
            }
        }
    });
    $('.dataTables_length').addClass('bs-select');
});
