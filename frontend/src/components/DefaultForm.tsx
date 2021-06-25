// @flow 
import * as React from 'react';
import {Grid, GridProps} from "@material-ui/core";

interface DefaultFormProps extends React.DetailedHTMLProps<React.FormHTMLAttributes<HTMLFormElement>, HTMLFormElement>{
    GridContainerProps?: GridProps
    GridItemProps?: GridProps
}
export const DefaultForm: React.FC<DefaultFormProps> = ({GridContainerProps, GridItemProps, ...others}) => {
    return (
        <form {...others}>
            <Grid container {...GridContainerProps}>
                <Grid item {...GridItemProps}>
                    {others.children}
                </Grid>
            </Grid>
        </form>
    );
};