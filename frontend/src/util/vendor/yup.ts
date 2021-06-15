import {setLocale} from "yup";

setLocale({
    mixed: {
        require: '${path} é requerido'
    },
    string: {
        max: '${path} precisa ter no máximo ${max} caracteres',
    },
    number: {
        min: '${path} precisa ter um mínimo de ${min} caracteres'
    }
});

export * from 'yup';
