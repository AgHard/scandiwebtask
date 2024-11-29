// graphql/getCategories.js
import { gql } from 'graphql-request';

export const GET_CATEGORIES = gql`
  {
    categories {
      id
      name
    }
  }
`;
