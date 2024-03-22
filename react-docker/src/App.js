import './App.css';
import { Outlet } from "react-router-dom";

import { useState } from 'react';

import Header from './components/Header';
import Footer from './components/Footer';
import FormContact from './components/FormContact';

/**
 * Gère l'affichage du composant App
 * App appelle ici le composant Title avec deux arguments sous la forme de clés/valeurs 
 * en utilisant la syntaxe des balises HTML et de leurs attributs
 * @returns JSX
 */
function App() {
  console.log('hello world')
  fetch('http://localhost:80/test').then(response => {
    return response.json();
  }).then((json) => {
    console.log(json)
  })
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [colorMode, setColorMode] = useState("light");

  function toggleMode() {
    if (colorMode === "light") setColorMode("dark");
    else setColorMode("light")
  }
  return (
    <div className={colorMode}>
      <div className="App container ">
        <Header isLoggedIn={isLoggedIn} toggleMode={toggleMode} darkMode={colorMode} />
        <main>
          {/* Outlet indique l'endroit où vont s'afficher les composants définis dans les routes enfants */}
          <Outlet context={[isLoggedIn, setIsLoggedIn]} />
          {/* <FormContact /> */}
        </main>

        <Footer />
      </div>
    </div>
  );
}

export default App;
