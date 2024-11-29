import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useCategory } from '../context/CategoryContext';
import { useCart } from '../context/CartContext';
import CartOverlay from './CartOverlay';
import { request, gql } from 'graphql-request';
import './Header.css';
import Logo from '../assets/logo.svg';
import Cart from '../assets/cart.svg';
import { GET_CATEGORIES } from '../graphql/getCategories';

const Header = ({isCartOpen, setCartOpen}) => {
  const { activeCategory, setActiveCategory } = useCategory();
  const { cartItems } = useCart();
  const navigate = useNavigate();
  const [categories, setCategories] = useState([]);

  const fetchCategories = async () => {
    const cachedCategories = sessionStorage.getItem('categories');
    if (cachedCategories) {
      setCategories(JSON.parse(cachedCategories));
    } else {
      const data = await request('https://scandiwebtask-c44363ca7aa2.herokuapp.com/graphql.php', GET_CATEGORIES);
      setCategories(data.categories);
      sessionStorage.setItem('categories', JSON.stringify(data.categories));
    }
  };

  useEffect(() => {
    // Fetch categories if not cached
    fetchCategories();
  }, []);

  const handleCategoryClick = (category) => {
    setActiveCategory(category);
    navigate('/');
  };

  const toggleCartOverlay = () => {
    setCartOpen(!isCartOpen);
  };

  const closeCartOverlay = () => {
    setCartOpen(false);
  };

  const totalItems = cartItems.reduce((total, item) => total + item.quantity, 0);

  return (
    <header className="header-container">
      <nav className="nav">
  {/* Dynamically render category links */}
  {categories.map((category) => (
    <a
      key={category.id}
      href={`/${category.name}`}
      className={`nav-link ${activeCategory === category.name ? 'active' : ''}`}
      data-testid={activeCategory === category.name ? 'active-category-link' : undefined}
      onClick={(e) => {
        e.preventDefault();
        handleCategoryClick(category.name);
      }}
    >
      {category.name}
    </a>
  ))}
</nav>

<div className="logo-container">
        <img src={Logo} alt="Logo" className="logo" />
      </div>

<div
        className="cart-icon-container"
        onClick={toggleCartOverlay}
        data-testid="cart-btn"
      >
        <img src={Cart} alt="Cart" className="cart-icon"/> {/* Lazy-load the cart */}
        {totalItems > 0 && (
          <div className="cart-item-count">
            {totalItems}
          </div>
        )}
      </div>

      {isCartOpen && <CartOverlay isOpen={isCartOpen} onClose={closeCartOverlay} />}
    </header>
  );
};

export default Header;
