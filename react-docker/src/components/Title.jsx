/**
 * Gère l'affichage du composant Title
 * @param {Object} props (souvent destructuré)
 * @returns JSX
 */

import { useState } from "react";

export default function Title({ color }) {
  // Déclaration du state title et du setter setTitle
  const [title, setTitle] = useState("");

  setTimeout(() => {
    setTitle("Titre au bout de 3 secondes");
  }, 3000);


  console.log(`props : `, color);
  return <h1 className="App-logo" style={{ color: color }
  } >{title}</h1>
}
