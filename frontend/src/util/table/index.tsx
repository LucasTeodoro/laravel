import * as React from 'react';
import MUIDataTable, {MUIDataTableColumn} from "mui-datatables";
import {useEffect, useState} from "react";

export interface UtilTableProps {
    data: any[];
    columnsDefinitions: MUIDataTableColumn[];
    title?: string;
}
const Index: React.FC<UtilTableProps> = (props) => {
    const [data, setData] = useState<any[]>([]);
    useEffect(() => {
        setData(props.data);
    }, [props])
    return (
        <MUIDataTable
            columns={props.columnsDefinitions}
            title={props.title}
            data={data}
        />
    );
};

export default Index;