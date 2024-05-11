<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../grid/css/jquery-ui-1.9.2.custom.css">
    <link rel="stylesheet" href="../grid/css/ui.jqgrid.css">
    <link rel="stylesheet" href="../../../components/css/admin.css">
    <script src="../../../js/jquery-1.7.2.min.js"></script>
    <script src="../../../js/jquery-ui-1.9.2.custom.min.js"></script>
    <script src="../../../js/jqGrid/i18n/grid.locale-ua.js"></script>
    <script src="../../../js/jqGrid/jquery.jqGrid.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#usersGrid").jqGrid({
                url: 'users.php',
                datatype: 'json',
                editurl: "edit.php",
                mtype: 'POST',
                colNames: ['Username', 'Email', 'Age', 'Admin', 'Created at'],
                colModel: [{
                        name: 'Username',
                        index: 'Username',
                        key: true,
                        sortable: true,
                        editable: false,
                        searchoptions: {
                            sopt: ['eq', 'ne', 'bw', 'bn', 'ew', 'en', 'cn', 'nc'],
                        },
                        search: true
                    },
                    {
                        name: 'Email',
                        index: 'Email',
                        sortable: true,
                        editable: false,
                        searchoptions: {
                            sopt: ['eq', 'ne', 'bw', 'bn', 'ew', 'en', 'cn', 'nc'],
                        },
                    },
                    {
                        name: 'Age',
                        index: 'Age',
                        sortable: true,
                        searchoptions: {
                            sopt: ['eq', 'ne', 'lt', 'le', 'gt', 'ge'],
                        },
                        
                        searchrules: {
                            number: true
                        },
                        editable: false,
                    },
                    {
                        name: 'Admin',
                        index: 'Admin',
                        sortable: true,
                        editable: true,
                        add: false,
                        edittype: 'select',
                        editoptions: {
                            value: '0:No;1:Invite'
                        },
                        searchoptions: {
                            sopt: ['eq', 'ne', 'bw', 'bn', 'ew', 'en', 'cn', 'nc'],
                        },

                    },
                    {
                        name: 'Created at',
                        index: 'Created at',
                        sortable: true,
                        searchoptions: {
                            sopt: ['eq', 'ne', 'bw', 'bn', 'ew', 'en', 'cn', 'nc'],
                        },
                        editable: false,
                    },
                ],
                add: false,
                rowNum: 10,
                rowList: [10, 20, 30],
                pager: '#usersPager',
                sortname: 'Usernames',
                sortorder: 'asc',
                caption: 'Users',
                gridview: true,
                shrinkToFit: false,
                height: 'auto',
                width: '1028',
                toppager: true,
                viewrecords: true,
                gridview: true,
                editable: true,
                
            });
            $("#usersGrid").navGrid('#usersPager', {
                del: true,
                add: true,
                edit: true,
                search: true,
                refresh: true,
                view: true,
                cloneToTop: true,
            }, {}, {}, {}, {
                multipleSearch: true,
            }, {});
        });
    </script>

</head>

<body>

    <table id="usersGrid"></table>
    <div id="usersPager"></div>
</body>

</html>