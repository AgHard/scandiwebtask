// graphql/getProductDetails.js
import { gql } from 'graphql-request';

export const GET_PRODUCT_DETAILS = gql`
  query($id: String!) {
    products(id: $id) {
      id
      name
      description
      inStock
      galleries {
        imageUrl
      }
      prices {
        amount
        currencySymbol
      }
      attributes {
        name
        type
        items {
          id
          display_value
          value
        }
      }
    }
  }
`;
