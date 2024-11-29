// graphql/mutations/placeOrder.js
import { gql } from '@apollo/client';

export const PLACE_ORDER_MUTATION = gql`
  mutation PlaceOrder($cartItems: [OrderItemInput!]!) {
    placeOrder(cartItems: $cartItems) {
      success
      message
      orderId
    }
  }
`;
