import {ComponentNameToClassKey} from "@material-ui/core/styles/overrides"
import {PaletteOptions} from "@material-ui/core/styles/createPalette";
import {PaletteColorOptions, Palette, PaletteColor} from "@material-ui/core";

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

declare module "@material-ui/core/styles/createPalette" {
    interface Palette {
        success?: PaletteColor
    }

    interface PaletteOptions {
        success?: PaletteColorOptions
    }
}