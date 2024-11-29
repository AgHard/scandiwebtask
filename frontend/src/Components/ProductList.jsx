import React, { useEffect, useState} from 'react';
import './ProductList.css';
import { request, gql } from 'graphql-request';
import CartIcon from '../assets/carticon.svg';
import { Link } from 'react-router-dom';
import { useCategory } from '../context/CategoryContext';
import { useCart } from '../context/CartContext';
import { GET_PRODUCTS } from '../graphql/getProducts';

const ProductList = () => {
  const { activeCategory } = useCategory();
  const [products, setProducts] = useState([]);
  const { addToCart } = useCart();

  useEffect(() => {
    const fetchProducts = async () => {
      const endpoint = 'https://scandiwebtask-c44363ca7aa2.herokuapp.com/graphql.php';
      const categoryValue = activeCategory === 'all' ? null : activeCategory === 'clothes' ? '2' : '3';

        const data = await request(endpoint, GET_PRODUCTS, { category: categoryValue });
        setProducts(data.products);
    };

    fetchProducts();
  }, [activeCategory]);

  const handleQuickShop = (event, product) => {
    event.preventDefault();
    event.stopPropagation();
    const selectedColor = product.attributes.find(attr => attr.name === "Color")?.items[0]?.value || "";
  const selectedCapacity = product.attributes.find(attr => attr.name === "Capacity")?.items[0]?.value || "";
  const selectedSize = product.attributes.find(attr => attr.name === "Size")?.items[0]?.value || "";
  const selectedTouchID = product.attributes.find(attr => attr.name === "Touch ID in keyboard")?.items[0]?.value || "";
  const selectedUSBPorts = product.attributes.find(attr => attr.name === "With USB 3 ports")?.items[0]?.value || "";
  console.log(selectedColor, selectedSize, selectedCapacity, selectedTouchID, selectedUSBPorts);
  

  // Construct the product with defaults
  const productWithDefaults = {
    ...product,
    selectedColor,
    selectedCapacity,
    selectedSize,
    selectedTouchID,
    selectedUSBPorts,
    price: product.prices?.[0]?.amount || 0,
    quantity: 1,

    availableSizes: product.attributes
      .filter(attr => attr.name === 'Size')
      .flatMap(attr => attr.items.map(item => item.value)),

    availableColors: product.attributes
      .filter(attr => attr.name === 'Color')
      .flatMap(attr => attr.items.map(item => item.value)),

    availableCapacities: product.attributes
      .filter(attr => attr.name === 'Capacity')
      .flatMap(attr => attr.items.map(item => item.display_value)),

    availableUSBPorts: product.attributes
      .filter(attr => attr.name === 'With USB 3 ports')
      .flatMap(attr => attr.items.map(item => item.display_value)),

    availableTouchIDOptions: product.attributes
      .filter(attr => attr.name === 'Touch ID in keyboard')
      .flatMap(attr => attr.items.map(item => item.display_value)),
  };

  addToCart(productWithDefaults);
  };

  const filteredProducts = activeCategory === 'all'
    ? products
    : products.filter(product => activeCategory === 'clothes' ? product.category === '2' : product.category === '3');

  return (
    <div className="product-list-container">
      <h2 className='product-cateory'>{activeCategory === 'all' ? 'All' : activeCategory}</h2>
      <div className="product-grid">
        {filteredProducts.map((product) => (
          <div
            key={product.id}
            className={`product-card ${product.inStock ? '' : 'out-of-stock'}`}
            
          >
            <Link to={`/product/${product.id}`} className="product-image-link" data-testid={`product-${product.name.toLowerCase().replaceAll(" ", '-')}`}>
              <img
                src={product.imageUrl}
                alt={product.name}
                className="product-image"
                loading="lazy"
                width="200"
                height="200"
                x
              />
              {!product.inStock && (
                <div className="out-of-stock-label">OUT OF STOCK</div>
              )}
            </Link>
            <div className="product-info">
              <h3 className='product-name'>{product.name}</h3>
              <p className='product-price'>
                {product.prices?.[0]?.currencySymbol}
                {(product.prices?.[0]?.amount).toFixed(2)}
              </p>
            </div>
            {product.inStock && (
              <div className="quick-shop" onClick={(e) => handleQuickShop(e, product)}>
                <img src={CartIcon} alt="Cart"/>
              </div>
            )}
          </div>
        ))}
      </div>
    </div>
  );
};

export default ProductList;
