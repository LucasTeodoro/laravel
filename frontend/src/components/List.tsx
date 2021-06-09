// @flow
import * as React from 'react';
import {Box, Chip, Fab} from "@material-ui/core";
import AddIcon from "@material-ui/icons/Add";
import {Link} from "react-router-dom";
import {MUIDataTableColumn} from "mui-datatables";
import {format, parseISO} from "date-fns";
import {Page} from "./Page";
import Table from "../util/table/index";

type Props = {
    pageTitle: string,
    createButtonTitle: string,
    createButtonURL: string,
    tableTitle?: string,
    pageURL: string,
    columnsDefinition: MUIDataTableColumn[]
}
const List = (props: Props) => {
    return (
        <Page title={props.pageTitle}>
            <Box dir={'rtl'}>
                <Fab
                    title={props.createButtonTitle}
                    size={"small"}
                    component={Link}
                    to={props.createButtonURL}
                >
                    <AddIcon />
                </Fab>
            </Box>
            <Box>
                <Table url={props.pageURL} columnsDefinitions={props.columnsDefinition} title={props.tableTitle}/>
            </Box>
        </Page>
    );
};

export default List;