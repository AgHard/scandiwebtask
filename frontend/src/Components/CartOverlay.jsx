import React from 'react';
import { useCart } from '../context/CartContext';
import './CartOverlay.css';
import { useMutation, gql } from '@apollo/client';
import { PLACE_ORDER_MUTATION } from '../graphql/mutations/placeOrder';

const CartOverlay = ({ isOpen, onClose }) => {
  const { cartItems, addToCart, removeFromCart, clearCart, getTotalItems } = useCart();
  const [placeOrder, { error }] = useMutation(PLACE_ORDER_MUTATION);
  const totalAmount = cartItems.reduce((total, item) => total + (item.price || 0) * item.quantity, 0);

  const handleDecreaseQuantity = (item) => {
    removeFromCart(item.id, item.selectedSize, item.selectedColor, item.selectedCapacity, item.selectedTouchID, item.selectedUSBPorts);
  };

  const handleIncreaseQuantity = (item) => {
    addToCart(item);
  };

  const handlePlaceOrder = async () => {
    try {
      console.log('Placing order with cart items:', cartItems);

      const { data } = await placeOrder({
        variables: {
          cartItems: cartItems.map(item => {
            if (!item.id) {
              console.error('Product ID is null for item:', item);
              return null;
            }
            return {
              productId: item.id,
              quantity: item.quantity,
              selectedSize: item.selectedSize,
              selectedColor: item.selectedColor,
              selectedCapacity: item.selectedCapacity,
              selectedTouchID: item.selectedTouchID,
              selectedUSBPorts: item.selectedUSBPorts,
            };
          }).filter(item => item !== null),
        },
      });
      if (data.placeOrder.success) {
        clearCart();
        alert('Order placed successfully!');
      } else {
        alert('Failed to place the order: ' + data.placeOrder.message);
      }
    } catch (e) {
      console.error('Error placing order:', e);
    }
  };

  return (
    <>
      {isOpen && (
        <div
          className="overlay-background"
          onClick={onClose}
          data-testid="background-overlay"
        />
      )}

      <div
        className={`cart-overlay ${isOpen ? 'open' : ''}`}
        data-testid="cart-overlay"
      >
        <div className="cart-content">
          <div style={{ display: 'flex', alignItems: 'center', gap: '5px' }}>
            <h3>My Bag,</h3>
            <p className='MyBag'>{getTotalItems()} {getTotalItems() > 1 ? 'Items' : 'Item'}</p>
          </div>

          <ul className="cart-items-list">
            {cartItems.map((item, index) => (
              <li key={index} className="cart-item">
                <div className="cart-item-details">
                  <p className="cart-item-name">{item.name}</p>
                  <div className="cart-item-price">
                    ${(item.price || 0).toFixed(2)}
                  </div>

                  <div className="cart-item-options">
                    {item.availableSizes && item.availableSizes.length > 0 && (
                      <div className="size-options" data-testid="cart-item-attribute-size">
                        <div className='product-attr'>Size:</div>
                        <div className="size-swatch-container">
                          {item.availableSizes.map((sizeOption) => (
                            <div
                              key={sizeOption}
                              className={`size-swatch ${item.selectedSize === sizeOption ? 'selected' : ''}`}
                              data-testid={`cart-item-attribute-size-${sizeOption.toLowerCase()}${item.selectedSize === sizeOption ? '-selected' : ''}`}
                            >
                              {sizeOption}
                            </div>
                          ))}
                        </div>
                      </div>
                    )}

                    {item.availableColors && item.availableColors.length > 0 && (
                      <div className="color-options" data-testid="cart-item-attribute-color">
                        <div className='product-attr'>Color:</div>
                        <div className="color-swatch-container">
                          {item.availableColors.map((colorOption) => (
                            <div
                              key={colorOption}
                              className={`color-swatch ${item.selectedColor === colorOption ? 'selected' : ''}`}
                              style={{ backgroundColor: colorOption }}
                              data-testid={`cart-item-attribute-color-${colorOption.toLowerCase()}${item.selectedColor === colorOption ? '-selected' : ''}`}
                            />
                          ))}
                        </div>
                      </div>
                    )}

                    {item.availableCapacities && item.availableCapacities.length > 0 && (
                      <div className="capacity-options" data-testid="cart-item-attribute-capacity">
                        <div className='product-attr'>Capacity:</div>
                        <div className="capacity-swatch-container">
                          {item.availableCapacities.map((capacityOption) => (
                            <div
                              key={capacityOption}
                              className={`capacity-swatch ${item.selectedCapacity === capacityOption ? 'selected' : ''}`}
                              data-testid={`cart-item-attribute-capacity-${capacityOption.toLowerCase()}${item.selectedCapacity === capacityOption ? '-selected' : ''}`}
                            >
                              {capacityOption}
                            </div>
                          ))}
                        </div>
                      </div>
                    )}

                    {item.availableUSBPorts && item.availableUSBPorts.length > 0 && (
                      <div className="usb-options" data-testid="cart-item-attribute-usb-ports">
                        <div className='product-attr'>USB Ports:</div>
                        <div className="usb-swatch-container">
                          {item.availableUSBPorts.map((usbOption) => (
                            <div
                              key={usbOption}
                              className={`usb-swatch ${item.selectedUSBPorts === usbOption ? 'selected' : ''}`}
                              data-testid={`cart-item-attribute-usb-ports-${usbOption.toLowerCase()}${item.selectedUSBPorts === usbOption ? '-selected' : ''}`}
                            >
                              {usbOption}
                            </div>
                          ))}
                        </div>
                      </div>
                    )}

                    {item.availableTouchIDOptions && item.availableTouchIDOptions.length > 0 && (
                      <div className="touch-id-options" data-testid="cart-item-attribute-touch-id">
                        <div className='product-attr'>Touch ID:</div>
                        <div className="touch-id-swatch-container">
                          {item.availableTouchIDOptions.map((touchIDOption) => (
                            <div
                              key={touchIDOption}
                              className={`touch-id-swatch ${item.selectedTouchID === touchIDOption ? 'selected' : ''}`}
                              data-testid={`cart-item-attribute-touch-id-${touchIDOption.toLowerCase()}${item.selectedTouchID === touchIDOption ? '-selected' : ''}`}
                            >
                              {touchIDOption}
                            </div>
                          ))}
                        </div>
                      </div>
                    )}
                  </div>
                </div>
                <div className="cart-item-controls">
                  <div className="cart-item-quantity">
                    <button
                      className="quantity-btn"
                      onClick={() => handleIncreaseQuantity(item)}
                    >
                      +
                    </button>
                    <span>{item.quantity}</span>
                    <button
                      className="quantity-btn"
                      onClick={() => handleDecreaseQuantity(item)}
                    >
                      -
                    </button>
                  </div>
                </div>
                  <img src={item.imageUrl} alt={item.name} className="cart-item-image" />
                
              </li>
            ))}
          </ul>
          <div style={{ display: 'flex', alignItems: 'center', gap: '250px' }} data-testid="cart-total">
            <p className="cart-total-price">Total:</p>
            <h3 className='total-price'>${totalAmount.toFixed(2)}</h3>
          </div>
          <button
            className={`place-order-button ${cartItems.length === 0 ? 'disabled' : ''}`}
            disabled={cartItems.length === 0}
            onClick={handlePlaceOrder}
          >
            Place Order
          </button>
        </div>
      </div>
    </>
  );
};

export default CartOverlay;
