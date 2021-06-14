import * as React from 'react';
import MUIDataTable, {MUIDataTableColumn} from "mui-datatables";
import {useEffect, useState} from "react";

type Props = {
    data: any[],
    columnsDefinitions: MUIDataTableColumn[],
    title?: string
};
const Index = (props: Props) => {
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