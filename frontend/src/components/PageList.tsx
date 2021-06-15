// @flow
import * as React from 'react';
import {Box, Fab} from "@material-ui/core";
import AddIcon from "@material-ui/icons/Add";
import {Link} from "react-router-dom";
import {MUIDataTableColumn} from "mui-datatables";
import {Page} from "./Page";
import Table from "../util/table/index";

type Props = {
    pageTitle: string,
    createButtonTitle: string,
    createButtonURL: string,
    tableTitle?: string,
    data: any[],
    columnsDefinition: MUIDataTableColumn[]
}
const PageList = (props: Props) => {
    return (
        <Page title={props.pageTitle}>
            <Box dir={'rtl'} paddingBottom={2}>
                <Fab
                    title={props.createButtonTitle}
                    size={"small"}
                    color={"secondary"}
                    component={Link}
                    to={props.createButtonURL}
                >
                    <AddIcon />
                </Fab>
            </Box>
            <Box>
                <Table
                    data={props.data}
                    columnsDefinitions={props.columnsDefinition}
                    title={props.tableTitle}
                />
            </Box>
        </Page>
    );
};

export default PageList;