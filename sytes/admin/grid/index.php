<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/jquery-ui-1.9.2.custom.css">
    <link rel="stylesheet" href="css/ui.jqgrid.css">
    <script src="../../../js/jquery-1.7.2.min.js"></script>
    <script src="../../../js/jquery-ui-1.9.2.custom.min.js"></script>
    <script src="../../../js/jqGrid/i18n/grid.locale-ua.js"></script>
    <script src="../../../js/jqGrid/jquery.jqGrid.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#grid").jqGrid({
                url: 'price.php',
                datatype: 'json',
                editurl: "edit.php",
                mtype: 'POST',
                colNames: ['ID', 'Price', 'Date', 'Time', 'Crypto', 'Currency'],
                colModel: [{
                        name: 'id',
                        index: 'id',
                        editable: false,
                       
                        searchoptions: {
                            sopt: ['eq', 'ne', 'lt', 'le', 'gt', 'ge'],
                        },
                        searchrules: {
                            number: true
                        },
                        search: true
                    },
                    {
                        name: 'price',
                        index: 'price',
                        sortable: true,
                        editable: true,
                        searchoptions: {
                            sopt: ['eq', 'ne', 'lt', 'le', 'gt', 'ge'],
                        },
                    },
                    {
                        name: 'date',
                        index: 'date',
                        sortable: true,
                        editable: true,
                        default: 'Y-m-d',
                        
                    },
                    {
                        name: 'time',
                        index: 'time',
                        sortable: true,
                        editable: true,
                        default: 'H:i:s',
                    },
                    {
                        name: 'crypto_id',
                        index: 'crypto_name',
                        sortable: true,
                        editable: true,
                        edittype: 'select',
                        editoptions: {
                            dataUrl: 'cryptocurrencies.php',
                            buildSelect: function(data) {
                                var response = jQuery.parseJSON(data);
                                var s = '<select>';
                                if (response) {
                                    for (var i = 0; i < response.length; i++) {
                                        s += '<option value="' + response[i].crypto_id + '">' + response[i].crypto_name + '</option>';
                                    }
                                }
                                return s + '</select>';
                            }
                        },

                    },
                    {
                        name: 'currency_id',
                        index: 'currency_name',
                        sortable: true,
                        editable: true,
                        edittype: 'select',
                        editoptions: {
                            dataUrl: 'icurrencies.php',
                            buildSelect: function(data) {
                                var response = jQuery.parseJSON(data);
                                var s = '<select>';
                                if (response) {
                                    for (var i = 0; i < response.length; i++) {
                                        if (response[i].currency_name === 'USD'){
                                            s += '<option value="' + response[i].currency_id + '" selected>' + response[i].currency_name + '</option>';
                                            continue;
                                        }
                                        s += '<option value="' + response[i].currency_id + '">' + response[i].currency_name + '</option>';
                                    }
                                }
                                return s + '</select>';
                            }
                        }
                    }
                ],
                rowNum: 10,
                rowList: [10, 20, 30],
                pager: '#pager',
                sortname: 'id',
                sortorder: 'asc',
                caption: 'My first grid',
                gridview: true,
                shrinkToFit: false,
                height: 'auto',
                toppager: true,
                viewrecords: true,
                gridview: true,
                editable: true,
            });
            $("#grid").navGrid('#pager', {
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

    <table id="grid"></table>
    <div id="pager"></div>
</body>

</html>