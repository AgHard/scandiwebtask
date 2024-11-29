import React, { createContext, useContext, useState, useEffect } from 'react';

// Create a context for the cart
const CartContext = createContext();

// Custom hook to use the CartContext
export const useCart = () => useContext(CartContext);

export const CartProvider = ({ children }) => {
  const [cartItems, setCartItems] = useState([]);

  // Load cart from localStorage on initial load
  useEffect(() => {
    const storedCart = localStorage.getItem('cart');
    if (storedCart) {
      setCartItems(JSON.parse(storedCart));
    }
  }, []);

  // Save cart to localStorage whenever it changes
  useEffect(() => {
    localStorage.setItem('cart', JSON.stringify(cartItems));
  }, [cartItems]);

  // Helper to calculate total number of items
  const getTotalItems = () => cartItems.reduce((sum, item) => sum + item.quantity, 0);

  // Helper to calculate total price
  const getTotalPrice = () => cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);

  // Add item to cart
  const addToCart = (product) => {
    setCartItems((prevItems) => {
      const existingItemIndex = prevItems.findIndex(
        (item) =>
          item.id === product.id &&
          item.selectedSize === product.selectedSize &&
          item.selectedColor === product.selectedColor &&
          item.selectedCapacity === product.selectedCapacity &&
          item.selectedTouchID === product.selectedTouchID &&
          item.selectedUSBPorts === product.selectedUSBPorts
      );
  
      if (existingItemIndex > -1) {
        // Item already exists, increase quantity
        const updatedItems = [...prevItems];
        updatedItems[existingItemIndex] = {
          ...updatedItems[existingItemIndex],
          quantity: updatedItems[existingItemIndex].quantity + 1,
        };
        return updatedItems;
      }
  
      // New item, add to cart
      return [...prevItems, { ...product, quantity: 1 }];
    });
  };

  // Remove item from cart or decrease quantity
  const removeFromCart = (id, size, color, capacity, touchID, usbPorts) => {
    setCartItems((prevItems) => {
      const existingItemIndex = prevItems.findIndex(
        (item) =>
          item.id === id &&
          item.selectedSize === size &&
          item.selectedColor === color &&
          item.selectedCapacity === capacity &&
          item.selectedTouchID === touchID &&
          item.selectedUSBPorts === usbPorts
      );
  
      if (existingItemIndex > -1) {
        const updatedItems = [...prevItems];
        const item = updatedItems[existingItemIndex];
  
        if (item.quantity > 1) {
          // Decrease quantity
          updatedItems[existingItemIndex] = {
            ...item,
            quantity: item.quantity - 1,
          };
        } else {
          // Remove item completely if quantity is 1
          updatedItems.splice(existingItemIndex, 1);
        }
  
        return updatedItems;
      }
  
      return prevItems;
    });
  };

  // Update attributes of a cart item
  const updateCartItemAttributes = (id, updatedAttributes) => {
    setCartItems((prevItems) => {
      const itemIndex = prevItems.findIndex((item) => item.id === id);
      if (itemIndex !== -1) {
        const updatedItems = [...prevItems];
        updatedItems[itemIndex] = {
          ...updatedItems[itemIndex],
          ...updatedAttributes,  // Apply the updated attributes
        };
        return updatedItems;
      }
      return prevItems;
    });
  };

  // Clear the cart after placing an order
  const clearCart = () => {
    setCartItems([]); // Clear the cart items
    localStorage.removeItem('cart'); // Optionally clear from localStorage too
  };

  return (
    <CartContext.Provider
      value={{
        cartItems,
        addToCart,
        removeFromCart,
        updateCartItemAttributes,  // Expose the new function
        getTotalItems,
        getTotalPrice,
        clearCart, // Expose clearCart function
      }}
    >
      {children}
    </CartContext.Provider>
  );
};
