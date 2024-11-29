import React, { useState, useEffect } from 'react';
import { request, gql } from 'graphql-request';
import { useParams, useNavigate } from 'react-router-dom';
import { useCategory } from '../context/CategoryContext';
import './ProductDetails.css';
import { useCart } from '../context/CartContext';
import { GET_PRODUCT_DETAILS } from '../graphql/getProductDetails';

const ProductDetails = ({ openCartOverlay }) => {
  const { id } = useParams();
  const [product, setProduct] = useState(null);
  const [mainImage, setMainImage] = useState(null);
  const [selectedCapacity, setSelectedCapacity] = useState(null);
  const [selectedUSBPorts, setSelectedUSBPorts] = useState(null);
  const [selectedTouchID, setSelectedTouchID] = useState(null);
  const [selectedSize, setSelectedSize] = useState(null);
  const [selectedColor, setSelectedColor] = useState(null);
  const [currentImageIndex, setCurrentImageIndex] = useState(0);
  const { activeCategory, setActiveCategory } = useCategory();
  const navigate = useNavigate();
  const { addToCart } = useCart();

  useEffect(() => {
    const fetchProductDetails = async () => {
      const endpoint = 'https://scandiwebtask-c44363ca7aa2.herokuapp.com/graphql.php';
      const data = await request(endpoint, GET_PRODUCT_DETAILS, { id });
      const productData = data.products.find((product) => product.id === id);
      if (productData) {
        setProduct(productData);
        setMainImage(productData?.galleries[0]?.imageUrl);
      }
    };

    fetchProductDetails();
  }, [id]);

  const handleAddToCart = () => {
    const productToAdd = {
      id: product.id,
      name: product.name,
      price: product.prices[0].amount,
      currencySymbol: product.prices[0].currencySymbol,
      selectedSize,
      selectedColor,
      selectedCapacity,
      selectedUSBPorts,
      selectedTouchID,
      imageUrl: product.galleries[0].imageUrl,
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

    addToCart(productToAdd);
    openCartOverlay();
  };

  const handleCapacitySelect = (capacity) => {
    setSelectedCapacity(capacity);
  };

  const handleUSBPortsSelect = (usbPorts) => {
    setSelectedUSBPorts(usbPorts);
  };

  const handleTouchIDSelect = (touchID) => {
    setSelectedTouchID(touchID);
  };

  const handleSizeSelect = (size) => {
    setSelectedSize(size);
  };

  const handleColorSelect = (color) => {
    setSelectedColor(color);
  };

  const handleImageSelect = (imageUrl, index) => {
    setMainImage(imageUrl);
    setCurrentImageIndex(index);
  };

  const handleNextImage = () => {
    const newIndex = (currentImageIndex + 1) % product.galleries.length;
    setCurrentImageIndex(newIndex);
    setMainImage(product.galleries[newIndex].imageUrl);
  };

  const handlePrevImage = () => {
    const newIndex = (currentImageIndex - 1 + product.galleries.length) % product.galleries.length;
    setCurrentImageIndex(newIndex);
    setMainImage(product.galleries[newIndex].imageUrl);
  };

  if (!product) return null;

  const isAddToCartDisabled = !product.inStock || 
    (!selectedCapacity && product.attributes.some(attr => attr.name === 'Capacity')) ||
    (!selectedUSBPorts && product.attributes.some(attr => attr.name === 'With USB 3 ports')) ||
    (!selectedTouchID && product.attributes.some(attr => attr.name === 'Touch ID in keyboard')) ||
    (!selectedSize && product.attributes.some(attr => attr.name === 'Size')) ||
    (!selectedColor && product.attributes.some(attr => attr.name === 'Color'));

  return (
    <div className="product-details-container">
      <div className="product-gallery-section" data-testid="product-gallery">
        <div className="image-thumbnails">
          {product.galleries.map((gallery, index) => (
            <img
              key={index}
              src={gallery.imageUrl}
              alt={`Thumbnail ${index}`}
              onClick={() => handleImageSelect(gallery.imageUrl, index)}
              className={`thumbnail ${mainImage === gallery.imageUrl ? 'selected' : ''}`}
            />
          ))}
        </div>
      </div>
      <div className="product-slider-section">
        <div className="main-image-box">
          <button className="prev-button" onClick={handlePrevImage}>
            &#8249;
          </button>
          <img src={mainImage} alt="Main Product" />
          <button className="next-button" onClick={handleNextImage}>
            &#8250;
          </button>
        </div>
      </div>
      <div className="product-info">
        <h1 className='product-name'>{product.name}</h1>
        {product.attributes?.length > 0 && (
          <>
            {product.attributes.some(attr => attr.name === 'Capacity') && (
              <div className="product-attribute" data-testid="product-attribute-capacity">
                <h3 className="product-props">Capacity:</h3>
                <div className="capacity-options">
                  {product.attributes
                    .filter(attr => attr.name === 'Capacity')
                    .flatMap(attr => attr.items)
                    .map(item => (
                      <button
                        key={item.id}
                        className={selectedCapacity === item.value ? 'selected' : ''}
                        onClick={() => handleCapacitySelect(item.value)}
                        data-testid={`product-attribute-capacity-${item.value}`}
                      >
                        {item.display_value}
                      </button>
                    ))}
                </div>
              </div>
            )}

            {product.attributes.some(attr => attr.name === 'With USB 3 ports') && (
              <div className="product-attribute" data-testid="product-attribute-with-usb-3-ports">
                <h3 className="product-props">With USB 3 Ports:</h3>
                <div className="usb-ports-options">
                  {product.attributes
                    .filter(attr => attr.name === 'With USB 3 ports')
                    .flatMap(attr => attr.items)
                    .map(item => (
                      <button
                        key={item.id}
                        className={selectedUSBPorts === item.value ? 'selected' : ''}
                        onClick={() => handleUSBPortsSelect(item.value)}
                        data-testid={`product-attribute-with-usb-3-ports-${item.value}`}
                      >
                        {item.display_value}
                      </button>
                    ))}
                </div>
              </div>
            )}

            {product.attributes.some(attr => attr.name === 'Touch ID in keyboard') && (
              <div className="product-attribute" data-testid="product-attribute-touch-id-in-keyboard">
                <h3 className="product-props">Touch ID in keyboard:</h3>
                <div className="touch-id-options">
                  {product.attributes
                    .filter(attr => attr.name === 'Touch ID in keyboard')
                    .flatMap(attr => attr.items)
                    .map(item => (
                      <button
                        key={item.id}
                        className={selectedTouchID === item.value ? 'selected' : ''}
                        onClick={() => handleTouchIDSelect(item.value)}
                        data-testid={`product-attribute-touch-id-in-keyboard-${item.value}`}
                      >
                        {item.display_value}
                      </button>
                    ))}
                </div>
              </div>
            )}

            {product.attributes.some(attr => attr.name === 'Size') && (
              <div className="product-attribute" data-testid="product-attribute-size">
                <h3 className="product-props">Size:</h3>
                <div className="size-options">
                  {product.attributes
                    .filter(attr => attr.name === 'Size')
                    .flatMap(attr => attr.items)
                    .map(item => (
                      <button
                        key={item.id}
                        className={selectedSize === item.value ? 'selected' : ''}
                        onClick={() => handleSizeSelect(item.value)}
                        data-testid={`product-attribute-size-${item.value}`}
                      >
                        {item.value}
                      </button>
                    ))}
                </div>
              </div>
            )}

            {product.attributes.some(attr => attr.name === 'Color') && (
              <div className="product-attribute" data-testid="product-attribute-color">
                <h3 className="product-props">Color:</h3>
                <div className="color-option">
                  {product.attributes
                    .filter(attr => attr.name === 'Color')
                    .flatMap(attr => attr.items)
                    .map(item => (
                      <button
                        key={item.id}
                        className={`color-switch ${selectedColor === item.value ? 'selected' : ''}`}
                        style={{ backgroundColor: item.value }}
                        onClick={() => handleColorSelect(item.value)}
                        data-testid={`product-attribute-color-${item.value}`}
                      />
                    ))}
                </div>
              </div>
            )}
          </>
        )}
        <h3 className="price-heading">PRICE:<br></br> <br></br>{`${product.prices[0].currencySymbol}${product.prices[0].amount.toFixed(2)}`}</h3>

        <button
          className={`add-to-cart-button ${isAddToCartDisabled ? 'disabled' : ''}`}
          data-testid="add-to-cart"
          disabled={isAddToCartDisabled}
          onClick={handleAddToCart}
        >
          Add to Cart
        </button>

        <div className="product-description" data-testid="product-description">
          {product.description}
        </div>
      </div>
    </div>
  );
};

export default ProductDetails;
