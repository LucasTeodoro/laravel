import * as yup from "yup";

yup.setLocale({
    mixed: {
      required: "${path} é requirido"
    },
    string: {
        required: "${path} e requi"
    },
    array: {
        length: "${path} deve ter pelo menos 1 item"
    }
});
const name = yup.string().label("Nome").required();
const is_active = yup.boolean();

export const CategorySchema = yup.object().shape({
    name,
    description: yup.string(),
    is_active
});

export const CastMemberSchema = yup.object().shape({
    name,
    type: yup.number().required()
});

export const GenreSchema = yup.object().shape({
    name,
    categories_id: yup.array().label("Categoria").required().length(1),
    is_active
});