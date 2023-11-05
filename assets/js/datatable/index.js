import DataTable from 'datatables.net-dt';
import 'datatables.net-rowgroup-dt';
import 'datatables.net-dt/css/jquery.dataTables.min.css';


function dataTable_addCell(tr, content, colSpan = 1) {
    let td = document.createElement('td');

    td.colSpan = colSpan;
    td.textContent = content;

    tr.appendChild(td);
}

document.addEventListener("DOMContentLoaded", () => {
    
    let dataTableTimeSlots = document.getElementById("timeslots-table");
    if(dataTableTimeSlots) {
        let colNum = $("#timeslots-table > tbody > tr:first > td").length;
        $('#timeslots-table').dataTable( {
            "pageLength": 50,
            "order": [[3, 'asc'],[1, 'asc']],
            /*"language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
            },*/
            'columnDefs': [ 
                {
                    'targets': [4], /* column index */
                    'orderable': false, /* true or false */
                },
            ],
            "dom": '<"card-body border-bottom py-3"<"d-flex"<"text-secondary"l><"ms-auto text-secondary"f>>>t<"card-footer d-flex align-items-center"<"m-0 text-secondary"i><"pagination m-0 ms-auto"p>>',
            rowGroup: {
                dataSrc: 3,
                startRender: function ( rows, group ) {
                    return $('<tr/>')
                        .append( '<td class="text-center fw-bold" colspan="'+colNum+'">'+group +' ('+rows.count()+' matière(s))'+'</td>' );
                }
            }
        } );
    }

    let dataTableTopics = document.getElementById("topics-table");
    if(dataTableTopics) {
        let colNum = $("#topics-table > tbody > tr:first > td").length;
        $('#topics-table').dataTable( {
            "pageLength": 50,
            "order": [[6, 'asc'],[0, 'asc']],
            /*"language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
            },*/
            'columnDefs': [ 
                {
                    'targets': [8], /* column index */
                    'orderable': false, /* true or false */
                },
                {
                    'targets': [6],
                    'visible': false
                }
            ],
            "dom": '<"card-body border-bottom py-3"<"d-flex"<"text-secondary"l><"ms-auto text-secondary"f>>>t<"card-footer d-flex align-items-center"<"m-0 text-secondary"i><"pagination m-0 ms-auto"p>>',
            rowGroup: {
                dataSrc: 6,
                startRender: function ( rows, group ) {
                    return $('<tr/>')
                        .append( '<td class="text-center fw-bold" colspan="'+colNum+'">'+group +' ('+rows.count()+' matière(s))'+'</td>' );
                },
                endRender: function (rows, group) {
                    let sumCm =
                        rows
                            .data()
                            .pluck(2)
                            .reduce((a, b) => a*1 + b*1);
                    let sumTd =
                        rows
                            .data()
                            .pluck(3)
                            .reduce((a, b) => a*1 + b*1);
                    let sumTp =
                        rows
                            .data()
                            .pluck(4)
                            .reduce((a, b) => a*1 + b*1);
                    let sumTotal =
                        rows
                            .data()
                            .pluck(5)
                            .reduce((a, b) => a*1 + b*1);
    
                    let tr = document.createElement('tr');
                    tr.classList.add("text-muted")
                    dataTable_addCell(tr, 'Totaux pour ' + group, 2);
                    dataTable_addCell(tr, sumCm+' H');
                    dataTable_addCell(tr, sumTd+' H');
                    dataTable_addCell(tr, sumTp+' H');
                    dataTable_addCell(tr, sumTotal+' H');
                    dataTable_addCell(tr, '', 2);
                    return tr;
                }
            }
        } );
    }

    let dataTableTopicsGroupsSlots = document.getElementById("topics-groups-table");
    if(dataTableTopicsGroupsSlots) {
        let colNum = $("#topics-groups-table > tbody > tr:first > td").length;
        $('#topics-groups-table').dataTable( {
            "pageLength": 50,
            "order": [0, 'asc'],
            /*"language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
            },*/
            'columnDefs': [ 
                {
                    'targets': [2], /* column index */
                    'orderable': false, /* true or false */
                },
            ],
            "dom": '<"card-body border-bottom py-3"<"d-flex"<"text-secondary"l><"ms-auto text-secondary"f>>>t<"card-footer d-flex align-items-center"<"m-0 text-secondary"i><"pagination m-0 ms-auto"p>>',
        } );
    }
});