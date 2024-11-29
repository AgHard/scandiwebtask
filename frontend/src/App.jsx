import React, { useState, useCallback } from 'react';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import { CategoryProvider } from './context/CategoryContext';
import { CartProvider } from './context/CartContext';
import { ApolloProvider, ApolloClient, InMemoryCache } from '@apollo/client';
import Header from './Components/Header';
import ProductList from './Components/ProductList';  // Removed lazy loading
import ProductDetails from './Components/ProductDetails';  // Removed lazy loading
import CartOverlay from './Components/CartOverlay';  // Removed lazy loading

// Initialize Apollo Client
const client = new ApolloClient({
  uri: 'https://scandiwebtask-c44363ca7aa2.herokuapp.com/graphql.php',
  cache: new InMemoryCache(),
});

function App() {
  const [isCartOverlayOpen, setIsCartOverlayOpen] = useState(false);

  // Memoized functions to handle cart overlay
  const openCartOverlay = () => {
    
    setIsCartOverlayOpen(true);
  };

  const closeCartOverlay = useCallback(() => {
    setIsCartOverlayOpen(false);
  }, []);

  return (
    <ApolloProvider client={client}>
      <Router>
        <CategoryProvider>
          <CartProvider>
            <div>
              <Header isCartOpen= {isCartOverlayOpen} setCartOpen = {setIsCartOverlayOpen}/>
              <Routes>
                <Route path="/" element={<ProductList />} />
                <Route
                  path="/product/:id"
                  element={<ProductDetails openCartOverlay={openCartOverlay} />}
                />
              </Routes>
            </div>
          </CartProvider>
        </CategoryProvider>
      </Router>
    </ApolloProvider>
  );
}

export default App;
