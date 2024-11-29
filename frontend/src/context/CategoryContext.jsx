import React, { createContext, useState, useContext, useEffect } from 'react';

const CategoryContext = createContext();

export const CategoryProvider = ({ children }) => {
  const [activeCategory, setActiveCategory] = useState('all');

  // Ensure that when the app loads, the active category is set to 'all'
  useEffect(() => {
    setActiveCategory('all'); // Explicitly set this to 'all' when the component mounts
  }, []);

  return (
    <CategoryContext.Provider value={{ activeCategory, setActiveCategory }}>
      {children}
    </CategoryContext.Provider>
  );
};

export const useCategory = () => useContext(CategoryContext);
