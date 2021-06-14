import {ComponentNameToClassKey} from "@material-ui/core/styles/overrides"

declare module "@material-ui/core/styles/overrides" {
    interface ComponentNameToClassKey {
        MUIDataTable: any;
        MUIDataTableToolBar: any;
        MUIDataTableHeadCell: any;
        MUIDataTableSelectCell: any;
        MUIDataTableBodyCell: any;
        MUIDataTableToolbarSelect: any;
        MUIDataTableBodyRow: any;
        MUITablePagination: any;
        MuiTableSortLabel: any;
    }
}