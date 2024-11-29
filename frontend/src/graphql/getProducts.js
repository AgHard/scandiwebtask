// graphql/getProducts.js
import { gql } from 'graphql-request';

export const GET_PRODUCTS = gql`
  query($category: String) {
    products(category: $category) {
      id
      name
      inStock
      category
      imageUrl
      prices {
        amount
        currencySymbol
      }
      attributes {
        id
        name
        items {
          id
          display_value
          value
        }
      }
    }
  }
`;
