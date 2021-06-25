// @flow
import * as React from 'react';
import {Box, Fab} from "@material-ui/core";
import AddIcon from "@material-ui/icons/Add";
import {Page, PageProps} from "./Page";
import Table, {UtilTableProps} from "../util/table/index";
import {ExtendButtonBase} from "@material-ui/core/ButtonBase";
import {FabTypeMap} from "@material-ui/core/Fab/Fab";

interface DefaultPageListProps {
    PageProps: PageProps;
    FabProps: ExtendButtonBase<FabTypeMap>;
    TableProps: UtilTableProps;
}
const DefaultPageList: React.FC<DefaultPageListProps> = ({PageProps, FabProps, TableProps}) => {
    return (
        <Page {...PageProps}>
            <Box dir={'rtl'} paddingBottom={2}>
                <Fab {...FabProps}>
                    <AddIcon />
                </Fab>
            </Box>
            <Box>
                <Table {...TableProps}/>
            </Box>
        </Page>
    );
};

export default DefaultPageList;