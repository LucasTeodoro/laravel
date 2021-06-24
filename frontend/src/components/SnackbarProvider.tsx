// @flow 
import * as React from 'react';
import {SnackbarProvider as SnackbarProviderDefault, SnackbarProviderProps} from "notistack";
import {IconButton, makeStyles, Theme} from "@material-ui/core";
import {Close} from "@material-ui/icons";

const useStyles = makeStyles((theme: Theme) => {
    return {
        variantSuccess: {
            backgroundColor: theme.palette.success.main
        },
        variantError: {
            backgroundColor: theme.palette.error.main
        },
        variantInfo: {
            backgroundColor: theme.palette.primary.main
        }
    }
});

export const SnackbarProvider: React.FC<SnackbarProviderProps> = (props) => {
    let snackbarProviderRef;
    const classes = useStyles();
    const defaultProps: SnackbarProviderProps = {
        classes,
        autoHideDuration: 3000,
        maxSnack: 3,
        anchorOrigin: {
            horizontal: 'right',
            vertical: 'top'
        },
        ref: (el) => snackbarProviderRef = el,
        action: (key) => (
            <IconButton
                color={"inherit"}
                style={{fontSize: 20}}
                onClick={() => snackbarProviderRef.closeSnackbar(key)}
            >
                <Close />
            </IconButton>
        ),
        ...props
    }

    return (
        <SnackbarProviderDefault {...defaultProps}>
            {props.children}
        </SnackbarProviderDefault>
    );
};